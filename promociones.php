<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
define('SAMS_API_REQUEST', true);
require_once __DIR__ . '/auth.php';
include 'database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =========================================================
    // ACCIÓN: list  (productos con su promo activa para esta sucursal)
    // =========================================================
    if ($action === 'list') {

        $sucursal_id = (int)$_SESSION['sucursal_id'];
        $sql = "SELECT
                    p.id, p.sku, p.nombre, p.marca, p.tipo,
                    c.nombre AS categoria,
                    COALESCE(pr.nombre_promo, '')   AS nombre_promo,
                    COALESCE(pr.descuento_pct, 0)   AS descuento_pct,
                    COALESCE(pr.descuento_monto, 0) AS descuento_monto,
                    COALESCE(prs.activo, 0)          AS promo_activa,
                    pr.id AS promo_id,
                    pr.fecha_inicio,
                    pr.fecha_fin,
                    pr.aplica_a_todos
                FROM producto_ICA_final p
                LEFT JOIN categoria_ICA_final c ON p.categoria_id = c.id
                LEFT JOIN promocion_ICA_final pr ON pr.producto_id = p.id
                LEFT JOIN promocion_sucursal_ICA_final prs ON prs.promocion_id = pr.id AND prs.sucursal_id = ?
                WHERE p.activo = 1
                ORDER BY p.nombre";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sucursal_id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: list_promos  (todas las promos globales con estado para esta sucursal)
    // =========================================================
    } elseif ($action === 'list_promos') {

        $sucursal_id = (int)$_SESSION['sucursal_id'];
        $sql = "SELECT pr.id, pr.producto_id, pr.nombre_promo, pr.descuento_pct, pr.descuento_monto,
                       pr.fecha_inicio, pr.fecha_fin, pr.aplica_a_todos,
                       COALESCE(prs.activo, 0) AS activo,
                       p.nombre AS producto_nombre, p.sku,
                       GROUP_CONCAT(tm.nombre ORDER BY tm.nombre SEPARATOR ', ') AS membresias_aplicables
                FROM promocion_ICA_final pr
                JOIN producto_ICA_final p ON pr.producto_id = p.id
                LEFT JOIN promocion_sucursal_ICA_final prs ON prs.promocion_id = pr.id AND prs.sucursal_id = ?
                LEFT JOIN promocion_membresia_ICA_final pm ON pm.promocion_id = pr.id
                LEFT JOIN tipo_membresia_ICA_final tm ON pm.tipo_membresia_id = tm.id
                GROUP BY pr.id, p.nombre, p.sku, prs.activo
                ORDER BY prs.activo DESC, pr.fecha_fin DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sucursal_id]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: create  (crea promo global, inactiva para todas las sucursales)
    // =========================================================
    } elseif ($action === 'create') {

        $aplica_a_todos = (isset($_POST['aplica_a_todos']) && $_POST['aplica_a_todos'] == '1') ? 1 : 0;
        $tipos_ids      = json_decode($_POST['tipos_membresia_ids'] ?? '[]', true);

        if (!$aplica_a_todos && empty($tipos_ids)) {
            throw new Exception('Debes seleccionar al menos un tipo de membresía o marcar "aplica a todos".');
        }

        $pdo->beginTransaction();

        // 1. Insertar promoción global
        $stmt = $pdo->prepare(
            "INSERT INTO promocion_ICA_final
                 (producto_id, nombre_promo, descuento_pct, descuento_monto, fecha_inicio, fecha_fin, aplica_a_todos)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_POST['producto_id'],
            $_POST['nombre_promo'],
            $_POST['descuento_pct']   ?: 0,
            $_POST['descuento_monto'] ?: 0,
            $_POST['fecha_inicio']    ?: null,
            $_POST['fecha_fin']       ?: null,
            $aplica_a_todos,
        ]);
        $promo_id = $pdo->lastInsertId();

        // 2. Insertar relaciones con tipos de membresía
        if (!$aplica_a_todos && !empty($tipos_ids)) {
            $stmtPM = $pdo->prepare(
                "INSERT IGNORE INTO promocion_membresia_ICA_final (promocion_id, tipo_membresia_id) VALUES (?, ?)"
            );
            foreach ($tipos_ids as $tipo_id) {
                $stmtPM->execute([$promo_id, (int)$tipo_id]);
            }
        }

        // 3. Crear fila inactiva en cada sucursal existente
        $sucursales = $pdo->query("SELECT id FROM sucursales")->fetchAll(PDO::FETCH_COLUMN);
        $stmtPS = $pdo->prepare(
            "INSERT IGNORE INTO promocion_sucursal_ICA_final (promocion_id, sucursal_id, activo) VALUES (?, ?, 0)"
        );
        foreach ($sucursales as $sid) {
            $stmtPS->execute([$promo_id, $sid]);
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Promoción creada. Aparece inactiva en todas las sucursales.']);

    // =========================================================
    // ACCIÓN: toggle  (activa/desactiva solo para esta sucursal)
    // =========================================================
    } elseif ($action === 'toggle') {

        $sucursal_id = (int)$_SESSION['sucursal_id'];
        $promo_id    = (int)$_POST['id'];

        $pdo->beginTransaction();

        // Garantizar que exista la fila antes de hacer toggle
        $pdo->prepare(
            "INSERT IGNORE INTO promocion_sucursal_ICA_final (promocion_id, sucursal_id, activo) VALUES (?, ?, 0)"
        )->execute([$promo_id, $sucursal_id]);

        $pdo->prepare(
            "UPDATE promocion_sucursal_ICA_final SET activo = NOT activo WHERE promocion_id = ? AND sucursal_id = ?"
        )->execute([$promo_id, $sucursal_id]);

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Estado de promoción actualizado']);

    // =========================================================
    // ACCIÓN: productos_list  (solo lectura)
    // =========================================================
    } elseif ($action === 'productos_list') {

        $stmt = $pdo->query(
            "SELECT id, sku, nombre, marca
             FROM producto_ICA_final
             WHERE activo = 1
             ORDER BY nombre"
        );
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: tipos_membresia  (solo lectura)
    // =========================================================
    } elseif ($action === 'tipos_membresia') {

        $stmt = $pdo->query("SELECT id, nombre FROM tipo_membresia_ICA_final ORDER BY id");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    }

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en Base de Datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    if (class_exists('Database')) Database::disconnect();
}
?>

<?php
header('Content-Type: application/json');
include 'database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $pdo = Database::connect();

    if ($action === 'list') {
        $sql = "SELECT 
                    p.id, p.sku, p.nombre, p.marca, p.tipo,
                    c.nombre AS categoria,
                    COALESCE(pr.nombre_promo, '') AS nombre_promo,
                    COALESCE(pr.descuento_pct, 0) AS descuento_pct,
                    COALESCE(pr.descuento_monto, 0) AS descuento_monto,
                    COALESCE(pr.activo, 0) AS promo_activa,
                    pr.id AS promo_id,
                    pr.fecha_inicio,
                    pr.fecha_fin,
                    pr.aplica_a_todos
                FROM producto_ICA_final p
                LEFT JOIN categoria_ICA_final c ON p.categoria_id = c.id
                LEFT JOIN promocion_ICA_final pr ON pr.producto_id = p.id AND pr.activo = 1
                WHERE p.activo = 1
                ORDER BY p.nombre";
        $stmt = $pdo->query($sql);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($action === 'list_promos') {
        // Traer promociones con sus membresías asociadas
        $sql = "SELECT pr.*, p.nombre AS producto_nombre, p.sku,
                       GROUP_CONCAT(tm.nombre ORDER BY tm.nombre SEPARATOR ', ') AS membresias_aplicables
                FROM promocion_ICA_final pr
                JOIN producto_ICA_final p ON pr.producto_id = p.id
                LEFT JOIN promocion_membresia_ICA_final pm ON pm.promocion_id = pr.id
                LEFT JOIN tipo_membresia_ICA_final tm ON pm.tipo_membresia_id = tm.id
                GROUP BY pr.id, p.nombre, p.sku
                ORDER BY pr.activo DESC, pr.fecha_fin DESC";
        $stmt = $pdo->query($sql);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($action === 'create') {
        // tipos_membresia_ids viene como JSON array de IDs, o vacío si aplica_a_todos=1
        $aplica_a_todos = isset($_POST['aplica_a_todos']) && $_POST['aplica_a_todos'] == '1' ? 1 : 0;
        $tipos_ids = json_decode($_POST['tipos_membresia_ids'] ?? '[]', true);

        if (!$aplica_a_todos && empty($tipos_ids)) {
            throw new Exception('Debes seleccionar al menos un tipo de membresía o marcar "aplica a todos".');
        }

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO promocion_ICA_final 
            (producto_id, nombre_promo, descuento_pct, descuento_monto, fecha_inicio, fecha_fin, aplica_a_todos, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $_POST['producto_id'],
            $_POST['nombre_promo'],
            $_POST['descuento_pct'] ?: 0,
            $_POST['descuento_monto'] ?: 0,
            $_POST['fecha_inicio'] ?: null,
            $_POST['fecha_fin'] ?: null,
            $aplica_a_todos
        ]);
        $promo_id = $pdo->lastInsertId();

        // Insertar relaciones con tipos de membresía (solo si no aplica a todos)
        if (!$aplica_a_todos && !empty($tipos_ids)) {
            $stmtPM = $pdo->prepare("INSERT IGNORE INTO promocion_membresia_ICA_final (promocion_id, tipo_membresia_id) VALUES (?, ?)");
            foreach ($tipos_ids as $tipo_id) {
                $stmtPM->execute([$promo_id, (int)$tipo_id]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Promoción creada correctamente']);

    } elseif ($action === 'toggle') {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE promocion_ICA_final SET activo = NOT activo WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Estado de promoción actualizado']);

    } elseif ($action === 'delete') {
        $pdo->beginTransaction();
        // La FK ON DELETE CASCADE borra promocion_membresia_ICA_final automáticamente
        $stmt = $pdo->prepare("DELETE FROM promocion_ICA_final WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Promoción eliminada']);

    } elseif ($action === 'productos_list') {
        $stmt = $pdo->query("SELECT id, sku, nombre, marca FROM producto_ICA_final WHERE activo=1 ORDER BY nombre");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } elseif ($action === 'tipos_membresia') {
        // Devolver los tres tipos de membresía disponibles
        $stmt = $pdo->query("SELECT id, nombre FROM tipo_membresia_ICA_final ORDER BY id");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } else {
        echo json_encode(['error' => 'Acción no válida']);
    }
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
Database::disconnect();
?>
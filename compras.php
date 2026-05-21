<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =========================================================
    // ACCIÓN: list_proveedores  (solo lectura, sin transacción)
    // =========================================================
    if ($action === 'list_proveedores') {

        $stmt = $pdo->query("SELECT id, nombre FROM proveedor_ICA_final ORDER BY nombre");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: list_productos  (solo lectura, sin transacción)
    // =========================================================
    } elseif ($action === 'list_productos') {

        $stmt = $pdo->query(
            "SELECT p.id, p.sku, p.nombre, p.marca, p.tipo,
                    COALESCE(lp.precio, 0)         AS precio,
                    COALESCE(SUM(inv.cantidad), 0) AS stock_actual
             FROM producto_ICA_final p
             LEFT JOIN lista_precio_ICA_final lp ON lp.producto_id = p.id AND lp.vigente = 1
             LEFT JOIN inventario_ICA_final inv ON inv.producto_id = p.id
             WHERE p.activo = 1
             GROUP BY p.id, p.sku, p.nombre, p.marca, p.tipo, lp.precio
             ORDER BY p.nombre"
        );
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: list_zonas  (solo lectura, sin transacción)
    // =========================================================
    } elseif ($action === 'list_zonas') {

        $stmt = $pdo->query("SELECT id, nombre, tipo FROM zona_operativa_ICA_final ORDER BY nombre");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: registrar  (escritura múltiple — TRANSACCIÓN)
    // =========================================================
    } elseif ($action === 'registrar') {

        $items        = json_decode($_POST['items'], true);
        $proveedor_id = $_POST['proveedor_id'];
        $zona_id      = $_POST['zona_id'];
        $es_reserva   = (int)($_POST['es_reserva'] ?? 0);

        if (empty($items)) throw new Exception('No hay artículos en la compra');

        // --- Inicio de transacción ---
        $pdo->beginTransaction();

        // Preparar sentencias reutilizables
        $stmtMovimiento = $pdo->prepare(
            "INSERT INTO inventario_movimiento_ICA_final
                 (producto_id, tipo, cantidad, fecha, proveedor_id)
             VALUES (?, 'RECEPCION', ?, NOW(), ?)"
        );

        $stmtCheck = $pdo->prepare(
            "SELECT id, cantidad FROM inventario_ICA_final
             WHERE producto_id = ? AND zona_id = ? AND es_reserva = ?"
        );

        $stmtUpdate = $pdo->prepare(
            "UPDATE inventario_ICA_final SET cantidad = cantidad + ? WHERE id = ?"
        );

        $stmtInsert = $pdo->prepare(
            "INSERT INTO inventario_ICA_final (producto_id, zona_id, cantidad, es_reserva)
             VALUES (?, ?, ?, ?)"
        );

        $stmtDesactivarPrecio = $pdo->prepare(
            "UPDATE lista_precio_ICA_final SET vigente = 0 WHERE producto_id = ?"
        );

        $stmtNuevoPrecio = $pdo->prepare(
            "INSERT INTO lista_precio_ICA_final (producto_id, precio, vigente, fecha)
             VALUES (?, ?, 1, NOW())"
        );

        foreach ($items as $item) {
            $producto_id  = $item['producto_id'];
            $cantidad     = $item['cantidad'];
            $precio_nuevo = $item['precio_costo'] ?? null;

            // 1. Registrar movimiento de recepción
            $stmtMovimiento->execute([$producto_id, $cantidad, $proveedor_id]);

            // 2. Actualizar o insertar registro de inventario en la zona indicada
            $stmtCheck->execute([$producto_id, $zona_id, $es_reserva]);
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $stmtUpdate->execute([$cantidad, $existing['id']]);
            } else {
                $stmtInsert->execute([$producto_id, $zona_id, $cantidad, $es_reserva]);
            }

            // 3. Actualizar precio si se proporcionó uno nuevo
            if ($precio_nuevo && $precio_nuevo > 0) {
                $stmtDesactivarPrecio->execute([$producto_id]);
                $stmtNuevoPrecio->execute([$producto_id, $precio_nuevo]);
            }
        }

        // --- Confirmar transacción ---
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Compra registrada y existencias actualizadas correctamente']);

    // =========================================================
    // ACCIÓN: historial  (solo lectura, sin transacción)
    // =========================================================
    } elseif ($action === 'historial') {

        $sql = "SELECT im.id, im.fecha, im.cantidad, im.tipo,
                       p.nombre AS producto, p.sku,
                       pv.nombre AS proveedor
                FROM inventario_movimiento_ICA_final im
                JOIN producto_ICA_final p ON im.producto_id = p.id
                LEFT JOIN proveedor_ICA_final pv ON im.proveedor_id = pv.id
                WHERE im.tipo = 'RECEPCION'
                ORDER BY im.fecha DESC
                LIMIT 100";

        $stmt = $pdo->query($sql);
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
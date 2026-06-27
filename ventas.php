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
    // ACCIÓN: buscar_producto  (solo lectura, sin transacción)
    // =========================================================
    if ($action === 'buscar_producto') {

        $q = '%' . ($_GET['q'] ?? '') . '%';

        $sql = "SELECT p.id, p.sku, p.nombre, p.marca, p.tipo, p.multipack,
                       COALESCE(lp.precio, 0) AS precio,
                       COALESCE(SUM(CASE WHEN inv.es_reserva=0 THEN inv.cantidad ELSE 0 END), 0) AS stock
                FROM producto_ICA_final p
                LEFT JOIN lista_precio_ICA_final lp ON lp.producto_id = p.id AND lp.vigente = 1
                LEFT JOIN inventario_ICA_final inv ON inv.producto_id = p.id AND inv.es_reserva = 0 AND inv.sucursal_id = ?
                WHERE p.activo = 1 AND (p.nombre LIKE ? OR p.sku LIKE ? OR p.marca LIKE ?)
                GROUP BY p.id, p.sku, p.nombre, p.marca, p.tipo, p.multipack, lp.precio
                LIMIT 20";

        $stmt = $pdo->prepare($sql);
        $sucursal_id = (int)$_SESSION['sucursal_id'];
        $stmt->execute([$sucursal_id, $q, $q, $q]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtPromos = $pdo->prepare("
            SELECT pr.id AS promo_id, pr.nombre_promo, pr.descuento_pct, pr.descuento_monto, pr.aplica_a_todos,
                   GROUP_CONCAT(CAST(pm.tipo_membresia_id AS CHAR) SEPARATOR ',') AS tipos_ids,
                   GROUP_CONCAT(tm.nombre ORDER BY tm.nombre SEPARATOR ',')       AS tipos_nombres
            FROM promocion_ICA_final pr
            LEFT JOIN promocion_membresia_ICA_final pm ON pm.promocion_id = pr.id
            LEFT JOIN tipo_membresia_ICA_final tm ON tm.id = pm.tipo_membresia_id
            WHERE pr.producto_id = ? AND pr.activo = 1
              AND (pr.fecha_inicio IS NULL OR pr.fecha_inicio <= CURDATE())
              AND (pr.fecha_fin   IS NULL OR pr.fecha_fin   >= CURDATE())
            GROUP BY pr.id
        ");

        foreach ($productos as &$prod) {
            $stmtPromos->execute([$prod['id']]);
            $prod['promociones'] = $stmtPromos->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($prod);

        echo json_encode(['success' => true, 'data' => $productos]);

    // =========================================================
    // ACCIÓN: buscar_socio  (solo lectura, sin transacción)
    // =========================================================
    } elseif ($action === 'buscar_socio') {

        $q = '%' . ($_GET['q'] ?? '') . '%';

        $sql = "SELECT s.id AS socio_id, s.nombre,
                       sm.id AS socio_membresia_id, sm.numero_socio,
                       tm.id AS tipo_membresia_id, tm.nombre AS membresia,
                       sm.saldo_cashback, sm.fecha_fin
                FROM socio_ICA_final s
                JOIN socio_membresia_ICA_final sm ON sm.socio_id = s.id AND sm.activo = 1
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.fecha_fin >= CURDATE()
                  AND (sm.numero_socio LIKE ? OR s.nombre LIKE ?)
                LIMIT 10";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$q, $q]);

        echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    // =========================================================
    // ACCIÓN: procesar_venta  (TRANSACCIÓN)
    // =========================================================
    } elseif ($action === 'procesar_venta') {

        $items              = json_decode($_POST['items'],  true);
        $socio_membresia_id = !empty($_POST['socio_membresia_id']) ? (int)$_POST['socio_membresia_id'] : null;
        $canal              = $_POST['canal'] ?? 'CAJA';
        $pagos              = json_decode($_POST['pagos'],  true);

        if (empty($items)) throw new Exception('No hay artículos en la venta');
        if (empty($pagos)) throw new Exception('No hay información de pago');

        // --- Inicio de transacción ---
        $sucursal_id = (int)$_SESSION['sucursal_id'];
        $pdo->beginTransaction();

        // 1. Verificar stock y calcular total
        $total = 0;

        $stmtStock = $pdo->prepare(
            "SELECT COALESCE(SUM(cantidad), 0) AS stock
             FROM inventario_ICA_final
             WHERE producto_id = ? AND es_reserva = 0 AND sucursal_id = ?"
        );

        foreach ($items as $item) {
            $stmtStock->execute([$item['producto_id'], $sucursal_id]);
            $stockRow = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if ((int)$stockRow['stock'] < (int)$item['cantidad']) {
                throw new Exception("Stock insuficiente para producto ID {$item['producto_id']}");
            }

            $subtotal  = $item['precio'] * $item['cantidad'];
            $descuento = $item['descuento'] ?? 0;
            $total    += $subtotal - $descuento;
        }

        // 2. Registrar cabecera de venta
        $stmtVenta = $pdo->prepare(
            "INSERT INTO venta_ICA_final (socio_membresia_id, canal, total, fecha, sucursal_id)
             VALUES (?, ?, ?, NOW(), ?)"
        );
        $stmtVenta->execute([$socio_membresia_id, $canal, $total, $sucursal_id]);
        $venta_id = $pdo->lastInsertId();

        // 3. Registrar ítems, descontar inventario FIFO y movimientos
        $stmtItem = $pdo->prepare(
            "INSERT INTO venta_item_ICA_final
                 (venta_id, producto_id, cantidad, precio, descuento, promocion_id, tipo_descuento)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmtZonas = $pdo->prepare(
            "SELECT id, cantidad FROM inventario_ICA_final
             WHERE producto_id = ? AND es_reserva = 0 AND cantidad > 0 AND sucursal_id = ?
             ORDER BY id ASC"
        );

        $stmtDescontarInv = $pdo->prepare(
            "UPDATE inventario_ICA_final SET cantidad = cantidad - ? WHERE id = ?"
        );

        $stmtMovimiento = $pdo->prepare(
            "INSERT INTO inventario_movimiento_ICA_final (producto_id, tipo, cantidad, fecha, sucursal_id)
             VALUES (?, 'VENTA', ?, NOW(), ?)"
        );

        foreach ($items as $item) {
            $promo_id  = !empty($item['promo_id'])       ? (int)$item['promo_id']       : null;
            $descuento = $item['descuento']               ?? 0;
            $tipo_desc = $item['tipo_descuento']          ?? 'NINGUNO';

            // Insertar ítem
            $stmtItem->execute([
                $venta_id,
                $item['producto_id'],
                $item['cantidad'],
                $item['precio'],
                $descuento,
                $promo_id,
                $tipo_desc,
            ]);

            // Descuento FIFO
            $stmtZonas->execute([$item['producto_id'], $sucursal_id]);
            $zonas       = $stmtZonas->fetchAll(PDO::FETCH_ASSOC);
            $porDescontar = (int)$item['cantidad'];

            foreach ($zonas as $zona) {
                if ($porDescontar <= 0) break;
                $descQty = min($porDescontar, (int)$zona['cantidad']);
                $stmtDescontarInv->execute([$descQty, $zona['id']]);
                $porDescontar -= $descQty;
            }

            // Movimiento de inventario
            $stmtMovimiento->execute([$item['producto_id'], $item['cantidad'], $sucursal_id]);
        }

        // 4. Registrar pagos
        $stmtPago = $pdo->prepare(
            "INSERT INTO pago_ICA_final (venta_id, metodo, monto) VALUES (?, ?, ?)"
        );

        foreach ($pagos as $pago) {
            $stmtPago->execute([$venta_id, $pago['metodo'], $pago['monto']]);
        }

        // 5. Aplicar cashback si corresponde
        // IMPORTANTE: Si es un familiar con Plus, el cashback va al titular
        if ($socio_membresia_id) {
            $stmtMemb = $pdo->prepare(
                "SELECT sm.id, sm.cuenta_titular_id, tm.cashback, tm.nombre as tipo_membresia
                 FROM socio_membresia_ICA_final sm
                 JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                 WHERE sm.id = ? AND sm.fecha_fin >= CURDATE()"
            );
            $stmtMemb->execute([$socio_membresia_id]);
            $membRow = $stmtMemb->fetch(PDO::FETCH_ASSOC);

            if ($membRow && $membRow['cashback'] > 0) {
                $cashback_ganado = round($total * ($membRow['cashback'] / 100), 2);
                
                // Determinar a quién acumularle el cashback
                $cuenta_destino_id = $membRow['id']; // Por defecto, al mismo socio
                
                // Si es familiar (tiene cuenta_titular_id) y es Plus, acumular al titular
                if ($membRow['cuenta_titular_id'] && $membRow['tipo_membresia'] === 'PLUS') {
                    $cuenta_destino_id = $membRow['cuenta_titular_id'];
                }
                
                $pdo->prepare(
                    "UPDATE socio_membresia_ICA_final
                     SET saldo_cashback = saldo_cashback + ?
                     WHERE id = ?"
                )->execute([$cashback_ganado, $cuenta_destino_id]);
            }
        }

        // --- Confirmar transacción ---
        $pdo->commit();

        echo json_encode([
            'success'  => true,
            'venta_id' => $venta_id,
            'total'    => $total,
            'message'  => "Venta #$venta_id registrada correctamente",
        ]);

    // =========================================================
    // ACCIÓN: historial  (solo lectura, sin transacción)
    // =========================================================
    } elseif ($action === 'historial') {

        $suc = (int)$_SESSION['sucursal_id'];
        $sql = "SELECT v.id, v.fecha, v.total, v.canal,
                       s.nombre AS socio, sm.numero_socio,
                       COUNT(vi.id) AS num_items
                FROM venta_ICA_final v
                LEFT JOIN socio_membresia_ICA_final sm ON v.socio_membresia_id = sm.id
                LEFT JOIN socio_ICA_final s ON sm.socio_id = s.id
                LEFT JOIN venta_item_ICA_final vi ON vi.venta_id = v.id
                WHERE v.sucursal_id = ?
                GROUP BY v.id, v.fecha, v.total, v.canal, s.nombre, sm.numero_socio
                ORDER BY v.fecha DESC
                LIMIT 50";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$suc]);
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
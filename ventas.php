<?php
header('Content-Type: application/json');
include 'database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $pdo = Database::connect();

    if ($action === 'buscar_producto') {
        $q = '%' . ($_GET['q'] ?? '') . '%';
        // Devolvemos el producto con TODAS sus promociones activas (no filtramos por membresía aquí,
        // eso se hace al procesar la venta y al buscar descuentos en el frontend según el socio seleccionado)
        $sql = "SELECT p.id, p.sku, p.nombre, p.marca, p.tipo, p.multipack,
                       COALESCE(lp.precio, 0) AS precio,
                       COALESCE(SUM(CASE WHEN inv.es_reserva=0 THEN inv.cantidad ELSE 0 END), 0) AS stock
                FROM producto_ICA_final p
                LEFT JOIN lista_precio_ICA_final lp ON lp.producto_id = p.id AND lp.vigente = 1
                LEFT JOIN inventario_ICA_final inv ON inv.producto_id = p.id AND inv.es_reserva = 0
                WHERE p.activo = 1 AND (p.nombre LIKE ? OR p.sku LIKE ? OR p.marca LIKE ?)
                GROUP BY p.id, p.sku, p.nombre, p.marca, p.tipo, p.multipack, lp.precio
                LIMIT 20";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$q, $q, $q]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada producto, traer sus promociones activas con info de membresías requeridas
        $stmtPromos = $pdo->prepare("
            SELECT pr.id AS promo_id, pr.nombre_promo, pr.descuento_pct, pr.descuento_monto, pr.aplica_a_todos,
                   GROUP_CONCAT(CAST(pm.tipo_membresia_id AS CHAR) SEPARATOR ',') AS tipos_ids,
                   GROUP_CONCAT(tm.nombre ORDER BY tm.nombre SEPARATOR ',') AS tipos_nombres
            FROM promocion_ICA_final pr
            LEFT JOIN promocion_membresia_ICA_final pm ON pm.promocion_id = pr.id
            LEFT JOIN tipo_membresia_ICA_final tm ON tm.id = pm.tipo_membresia_id
            WHERE pr.producto_id = ? AND pr.activo = 1
              AND (pr.fecha_inicio IS NULL OR pr.fecha_inicio <= CURDATE())
              AND (pr.fecha_fin IS NULL OR pr.fecha_fin >= CURDATE())
            GROUP BY pr.id
        ");

        foreach ($productos as &$prod) {
            $stmtPromos->execute([$prod['id']]);
            $prod['promociones'] = $stmtPromos->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($prod);

        echo json_encode(['success' => true, 'data' => $productos]);

    } elseif ($action === 'buscar_socio') {
        $q = '%' . ($_GET['q'] ?? '') . '%';
        // Devolvemos socio + su membresía activa (la vigente)
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

    } elseif ($action === 'procesar_venta') {
        $items            = json_decode($_POST['items'], true);
        $socio_membresia_id = !empty($_POST['socio_membresia_id']) ? (int)$_POST['socio_membresia_id'] : null;
        $canal            = $_POST['canal'] ?? 'CAJA';
        $pagos            = json_decode($_POST['pagos'], true);

        if (empty($items))  throw new Exception('No hay artículos en la venta');
        if (empty($pagos))  throw new Exception('No hay información de pago');

        $pdo->beginTransaction();

        // Calcular total verificando stock
        $total = 0;
        foreach ($items as $item) {
            // Verificar stock
            $checkStock = $pdo->prepare("SELECT COALESCE(SUM(cantidad),0) AS stock 
                                         FROM inventario_ICA_final WHERE producto_id=? AND es_reserva=0");
            $checkStock->execute([$item['producto_id']]);
            $stockRow = $checkStock->fetch(PDO::FETCH_ASSOC);
            if ($stockRow['stock'] < $item['cantidad']) {
                throw new Exception("Stock insuficiente para producto ID {$item['producto_id']}");
            }
            $subtotal  = $item['precio'] * $item['cantidad'];
            $descuento = $item['descuento'] ?? 0; // ya viene calculado desde el frontend
            $total    += $subtotal - $descuento;
        }

        // Registrar venta vinculada a socio_membresia_id (puede ser null si sin socio)
        $stmtV = $pdo->prepare("INSERT INTO venta_ICA_final (socio_membresia_id, canal, total, fecha) VALUES (?,?,?,NOW())");
        $stmtV->execute([$socio_membresia_id, $canal, $total]);
        $venta_id = $pdo->lastInsertId();

        foreach ($items as $item) {
            $promo_id    = !empty($item['promo_id']) ? (int)$item['promo_id'] : null;
            $descuento   = $item['descuento'] ?? 0;
            $tipo_desc   = $item['tipo_descuento'] ?? 'NINGUNO';

            $stmtI = $pdo->prepare("INSERT INTO venta_item_ICA_final 
                (venta_id, producto_id, cantidad, precio, descuento, promocion_id, tipo_descuento)
                VALUES (?,?,?,?,?,?,?)");
            $stmtI->execute([
                $venta_id,
                $item['producto_id'],
                $item['cantidad'],
                $item['precio'],
                $descuento,
                $promo_id,
                $tipo_desc
            ]);

            // Descontar inventario FIFO piso
            $zonas = $pdo->prepare("SELECT id, cantidad FROM inventario_ICA_final 
                                    WHERE producto_id=? AND es_reserva=0 AND cantidad>0 
                                    ORDER BY id ASC");
            $zonas->execute([$item['producto_id']]);
            $porDescontar = $item['cantidad'];
            foreach ($zonas->fetchAll(PDO::FETCH_ASSOC) as $zona) {
                if ($porDescontar <= 0) break;
                $descQty = min($porDescontar, $zona['cantidad']);
                $pdo->prepare("UPDATE inventario_ICA_final SET cantidad = cantidad - ? WHERE id = ?")
                    ->execute([$descQty, $zona['id']]);
                $porDescontar -= $descQty;
            }

            // Registrar movimiento
            $pdo->prepare("INSERT INTO inventario_movimiento_ICA_final (producto_id, tipo, cantidad, fecha)
                           VALUES (?, 'VENTA', ?, NOW())")->execute([$item['producto_id'], $item['cantidad']]);
        }

        // Registrar pagos
        foreach ($pagos as $pago) {
            $pdo->prepare("INSERT INTO pago_ICA_final (venta_id, metodo, monto) VALUES (?,?,?)")
                ->execute([$venta_id, $pago['metodo'], $pago['monto']]);
        }

        // Aplicar cashback si hay membresía Benefits/Plus
        if ($socio_membresia_id) {
            $memb = $pdo->prepare("SELECT sm.id, tm.cashback FROM socio_membresia_ICA_final sm 
                                   JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                                   WHERE sm.id = ? AND sm.fecha_fin >= CURDATE()");
            $memb->execute([$socio_membresia_id]);
            $membRow = $memb->fetch(PDO::FETCH_ASSOC);
            if ($membRow && $membRow['cashback'] > 0) {
                $cashback_ganado = round($total * ($membRow['cashback'] / 100), 2);
                $pdo->prepare("UPDATE socio_membresia_ICA_final SET saldo_cashback = saldo_cashback + ? WHERE id=?")
                    ->execute([$cashback_ganado, $membRow['id']]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'venta_id' => $venta_id, 'total' => $total,
                          'message' => "Venta #$venta_id registrada correctamente"]);

    } elseif ($action === 'historial') {
        $sql = "SELECT v.id, v.fecha, v.total, v.canal,
                       s.nombre AS socio, sm.numero_socio,
                       COUNT(vi.id) AS num_items
                FROM venta_ICA_final v
                LEFT JOIN socio_membresia_ICA_final sm ON v.socio_membresia_id = sm.id
                LEFT JOIN socio_ICA_final s ON sm.socio_id = s.id
                LEFT JOIN venta_item_ICA_final vi ON vi.venta_id = v.id
                GROUP BY v.id, v.fecha, v.total, v.canal, s.nombre, sm.numero_socio
                ORDER BY v.fecha DESC
                LIMIT 50";
        $stmt = $pdo->query($sql);
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
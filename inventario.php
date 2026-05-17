<?php
// 1. Forzar a PHP a reportar cualquier error interno de sintaxis antes del JSON
ini_set('display_errors', 0); // Manténlo en 0 en producción, pero puedes cambiarlo a 1 si el JSON sigue vacío
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    $pdo = Database::connect();
    
    // CONFIGURACIÓN CRÍTICA: Forzar a PDO a lanzar excepciones si falla el SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($action === 'list') {
        $search = '%' . ($_GET['q'] ?? '') . '%';
        
        // SQL Optimizado: Agrupamos de forma estricta por el ID del producto para evitar conflictos de ONLY_FULL_GROUP_BY
        $sql = "SELECT 
                    p.id, p.sku, p.nombre, p.marca, p.tipo, p.es_members_mark,
                    c.nombre AS categoria,
                    d.nombre AS division,
                    COALESCE(SUM(CASE WHEN inv.es_reserva = 0 THEN inv.cantidad ELSE 0 END), 0) AS stock_piso,
                    COALESCE(SUM(CASE WHEN inv.es_reserva = 1 THEN inv.cantidad ELSE 0 END), 0) AS stock_reserva,
                    COALESCE(SUM(inv.cantidad), 0) AS stock_total,
                    COALESCE(pr.descuento_pct, 0) AS descuento_pct,
                    COALESCE(pr.descuento_monto, 0) AS descuento_monto,
                    COALESCE(pr.nombre_promo, '') AS promo_nombre,
                    COALESCE(lp.precio, 0) AS precio_actual
                FROM producto_ICA_final p
                LEFT JOIN categoria_ICA_final c ON p.categoria_id = c.id
                LEFT JOIN division_ICA_final d ON c.division_id = d.id
                LEFT JOIN inventario_ICA_final inv ON inv.producto_id = p.id
                LEFT JOIN promocion_ICA_final pr ON pr.producto_id = p.id AND pr.activo = 1
                LEFT JOIN lista_precio_ICA_final lp ON lp.producto_id = p.id AND lp.vigente = 1
                WHERE p.activo = 1
                  AND (p.nombre LIKE ? OR p.sku LIKE ? OR p.marca LIKE ?)
                GROUP BY 
                    p.id, p.sku, p.nombre, p.marca, p.tipo, p.es_members_mark,
                    c.nombre, d.nombre, pr.descuento_pct, pr.descuento_monto,
                    pr.nombre_promo, lp.precio
                ORDER BY p.nombre ASC
                LIMIT 200";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search, $search, $search]);
        
        echo json_encode([
            'success' => true, 
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);

    } elseif ($action === 'stats') {
        $stats = [];
        
        // Consultas directas y seguras
        $stats['total_productos'] = (int)$pdo->query("SELECT COUNT(*) FROM producto_ICA_final WHERE activo=1")->fetchColumn();
        $stats['con_stock']       = (int)$pdo->query("SELECT COUNT(DISTINCT producto_id) FROM inventario_ICA_final WHERE cantidad > 0")->fetchColumn();
        $stats['sin_stock']       = $stats['total_productos'] - $stats['con_stock'];
        $stats['con_promo']       = (int)$pdo->query("SELECT COUNT(DISTINCT producto_id) FROM promocion_ICA_final WHERE activo=1")->fetchColumn();
        
        echo json_encode([
            'success' => true, 
            'data' => $stats
        ]);

    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'error' => 'Accion no valida']);
    }

} catch (PDOException $e) {
    // Si el error fue de la Base de Datos, capturamos el mensaje exacto
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Error en Base de Datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Cualquier otro error general
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => 'Error General: ' . $e->getMessage()
    ]);
} finally {
    // El bloque finally asegura que la desconexión ocurra pase lo que pase, 
    // siempre y cuando la clase e instancia existan.
    if (class_exists('Database')) {
        Database::disconnect();
    }
}
?>
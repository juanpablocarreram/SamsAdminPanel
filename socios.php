<?php
// ═══════════════════════════════════════════════════════════
// SOCIOS · Gestión de Membresías y Jerarquía Familiar
// ═══════════════════════════════════════════════════════════

require_once __DIR__ . '/database.php';

$db = Database::connect();
header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? null;

try {
    switch ($action) {
        // ─────────────────────────────────────────────────
        // 1. LISTAR SOCIOS TITULARES
        // ─────────────────────────────────────────────────
        case 'list_titulares':
            $query = "
                SELECT 
                    sm.id as socio_membresia_id,
                    sm.numero_socio,
                    s.id as socio_id,
                    s.nombre,
                    s.correo,
                    s.telefono,
                    tm.nombre as tipo_membresia,
                    sm.parentesco,
                    sm.saldo_cashback,
                    sm.fecha_fin as vencimiento,
                    sm.activo,
                    (SELECT COUNT(*) FROM socio_membresia_ICA_final WHERE cuenta_titular_id = sm.id) as num_familiares
                FROM socio_membresia_ICA_final sm
                JOIN socio_ICA_final s ON sm.socio_id = s.id
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.parentesco = 'TITULAR' AND sm.cuenta_titular_id IS NULL
                ORDER BY s.nombre ASC
            ";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $titulares = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $titulares]);
            break;

        // ─────────────────────────────────────────────────
        // 2. OBTENER DETALLES DE SOCIO Y SUS FAMILIARES
        // ─────────────────────────────────────────────────
        case 'get_socio_detalles':
            $socio_membresia_id = $_GET['socio_membresia_id'] ?? null;
            if (!$socio_membresia_id) throw new Exception('ID de socio requerido');

            $query = "
                SELECT 
                    sm.id,
                    sm.numero_socio,
                    s.id as socio_id,
                    s.nombre,
                    s.correo,
                    s.telefono,
                    tm.id as tipo_id,
                    tm.nombre as tipo_membresia,
                    tm.cashback as cashback_pct,
                    sm.saldo_cashback,
                    sm.fecha_fin as vencimiento,
                    sm.activo
                FROM socio_membresia_ICA_final sm
                JOIN socio_ICA_final s ON sm.socio_id = s.id
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.id = ? AND sm.parentesco = 'TITULAR'
            ";
            $stmt = $db->prepare($query);
            $stmt->execute([$socio_membresia_id]);
            $titular = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$titular) throw new Exception('Socio no encontrado');

            $query = "
                SELECT 
                    sm.id as socio_membresia_id,
                    sm.numero_socio,
                    s.id as socio_id,
                    s.nombre,
                    s.correo,
                    s.telefono,
                    sm.parentesco,
                    tm.nombre as tipo_membresia,
                    sm.saldo_cashback,
                    sm.fecha_fin as vencimiento,
                    sm.es_complementaria,
                    sm.activo
                FROM socio_membresia_ICA_final sm
                JOIN socio_ICA_final s ON sm.socio_id = s.id
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.cuenta_titular_id = ?
                ORDER BY sm.parentesco, s.nombre ASC
            ";
            $stmt = $db->prepare($query);
            $stmt->execute([$socio_membresia_id]);
            $familiares = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'titular' => $titular,
                'familiares' => $familiares
            ]);
            break;

        // ─────────────────────────────────────────────────
        // 3. CREAR NUEVO SOCIO TITULAR
        // ─────────────────────────────────────────────────
        case 'crear_socio_titular':
            $nombre            = $_POST['nombre'] ?? null;
            $correo            = $_POST['correo'] ?? null;
            $telefono          = $_POST['telefono'] ?? null;
            $tipo_membresia_id = $_POST['tipo_membresia_id'] ?? null;
            $fecha_fin         = $_POST['fecha_fin'] ?? null;

            if (!$nombre || !$tipo_membresia_id || !$fecha_fin) {
                throw new Exception('Faltan datos requeridos');
            }
            if (strtotime($fecha_fin) <= time()) {
                throw new Exception('La fecha de vencimiento debe ser futura');
            }

            $db->beginTransaction();
            try {
                $stmtSocio = $db->prepare("INSERT INTO socio_ICA_final (nombre, correo, telefono) VALUES (?, ?, ?)");
                $stmtSocio->execute([$nombre, $correo, $telefono]);
                $socio_id = $db->lastInsertId();

                $numero_socio = 'SAM-' . date('Ymd') . str_pad($socio_id, 4, '0', STR_PAD_LEFT);

                $stmtMembresia = $db->prepare("
                    INSERT INTO socio_membresia_ICA_final 
                    (numero_socio, socio_id, tipo_id, parentesco, cuenta_titular_id, saldo_cashback, fecha_fin, activo)
                    VALUES (?, ?, ?, 'TITULAR', NULL, 0, ?, 1)
                ");
                $stmtMembresia->execute([$numero_socio, $socio_id, $tipo_membresia_id, $fecha_fin]);
                $socio_membresia_id = $db->lastInsertId();

                $db->commit();
                echo json_encode([
                    'success'           => true,
                    'message'           => 'Socio titular creado correctamente',
                    'socio_membresia_id'=> $socio_membresia_id,
                    'numero_socio'      => $numero_socio
                ]);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        // ─────────────────────────────────────────────────
        // 4. CREAR SOCIO FAMILIAR (hereda tipo y fecha del titular)
        // ─────────────────────────────────────────────────
        case 'crear_socio_familiar':
            $cuenta_titular_id = $_POST['cuenta_titular_id'] ?? null;
            $nombre            = $_POST['nombre'] ?? null;
            $correo            = $_POST['correo'] ?? null;
            $telefono          = $_POST['telefono'] ?? null;
            $parentesco        = $_POST['parentesco'] ?? 'OTRO';
            $es_complementaria = $_POST['es_complementaria'] ?? 0;

            if (!$cuenta_titular_id || !$nombre || !$parentesco) {
                throw new Exception('Faltan datos requeridos');
            }

            // Obtener tipo y fecha del titular
            $stmtTitular = $db->prepare("
                SELECT sm.id, sm.tipo_id, sm.fecha_fin, tm.cashback
                FROM socio_membresia_ICA_final sm
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.id = ? AND sm.parentesco = 'TITULAR'
            ");
            $stmtTitular->execute([$cuenta_titular_id]);
            $titular = $stmtTitular->fetch(PDO::FETCH_ASSOC);
            if (!$titular) throw new Exception('Socio titular no encontrado');

            // Verificar límite de complementaria gratuita
            if ($es_complementaria) {
                $stmtCheck = $db->prepare("
                    SELECT COUNT(*) as cnt FROM socio_membresia_ICA_final
                    WHERE cuenta_titular_id = ? AND es_complementaria = 1
                ");
                $stmtCheck->execute([$cuenta_titular_id]);
                $check = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                if ($check['cnt'] > 0) {
                    throw new Exception('El titular ya tiene una tarjeta complementaria gratis');
                }
            }

            $db->beginTransaction();
            try {
                $stmtSocio = $db->prepare("INSERT INTO socio_ICA_final (nombre, correo, telefono) VALUES (?, ?, ?)");
                $stmtSocio->execute([$nombre, $correo, $telefono]);
                $socio_id = $db->lastInsertId();

                $numero_socio = 'SAM-' . date('Ymd') . str_pad($socio_id, 4, '0', STR_PAD_LEFT);

                // El familiar hereda tipo_id (membresía) y fecha_fin del titular
                $stmtFamiliar = $db->prepare("
                    INSERT INTO socio_membresia_ICA_final 
                    (numero_socio, socio_id, cuenta_titular_id, tipo_id, parentesco, es_complementaria, saldo_cashback, fecha_fin, activo)
                    VALUES (?, ?, ?, ?, ?, ?, 0, ?, 1)
                ");
                $stmtFamiliar->execute([
                    $numero_socio,
                    $socio_id,
                    $cuenta_titular_id,
                    $titular['tipo_id'],   // misma membresía que el titular
                    $parentesco,
                    $es_complementaria ? 1 : 0,
                    $titular['fecha_fin']  // misma fecha de vencimiento
                ]);

                $db->commit();
                echo json_encode([
                    'success'      => true,
                    'message'      => 'Familiar vinculado correctamente',
                    'numero_socio' => $numero_socio
                ]);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        // ─────────────────────────────────────────────────
        // 5. TIPOS DE MEMBRESÍA
        // ─────────────────────────────────────────────────
        case 'tipos_membresia':
            $stmt = $db->prepare("SELECT id, nombre, cashback FROM tipo_membresia_ICA_final ORDER BY nombre ASC");
            $stmt->execute();
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        // ─────────────────────────────────────────────────
        // 6. ACTUALIZAR INFORMACIÓN DE SOCIO
        // ─────────────────────────────────────────────────
        case 'actualizar_socio':
            $socio_id = $_POST['socio_id'] ?? null;
            $nombre   = $_POST['nombre']   ?? null;
            $correo   = $_POST['correo']   ?? null;
            $telefono = $_POST['telefono'] ?? null;
            if (!$socio_id) throw new Exception('ID de socio requerido');

            $stmt = $db->prepare("UPDATE socio_ICA_final SET nombre = ?, correo = ?, telefono = ? WHERE id = ?");
            $stmt->execute([$nombre, $correo, $telefono, $socio_id]);
            echo json_encode(['success' => true, 'message' => 'Socio actualizado correctamente']);
            break;

        // ─────────────────────────────────────────────────
        // 7. DESACTIVAR MEMBRESÍA DE SOCIO (sin borrar)
        // ─────────────────────────────────────────────────
        case 'desactivar_membresia':
            $socio_membresia_id = $_POST['socio_membresia_id'] ?? null;
            if (!$socio_membresia_id) throw new Exception('ID de membresía requerido');

            $stmt = $db->prepare("UPDATE socio_membresia_ICA_final SET activo = 0 WHERE id = ?");
            $stmt->execute([$socio_membresia_id]);
            echo json_encode(['success' => true, 'message' => 'Membresía desactivada']);
            break;

        // ─────────────────────────────────────────────────
        // 8. ELIMINAR SOCIO TITULAR (y todos sus familiares)
        // ─────────────────────────────────────────────────
        case 'eliminar_socio_titular':
            $socio_membresia_id = $_POST['socio_membresia_id'] ?? null;
            if (!$socio_membresia_id) throw new Exception('ID de membresía requerido');

            // Verificar que es titular
            $stmtCheck = $db->prepare("
                SELECT sm.id, sm.socio_id FROM socio_membresia_ICA_final sm
                WHERE sm.id = ? AND sm.parentesco = 'TITULAR' AND sm.cuenta_titular_id IS NULL
            ");
            $stmtCheck->execute([$socio_membresia_id]);
            $titular = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if (!$titular) throw new Exception('Socio titular no encontrado');

            $db->beginTransaction();
            try {
                // 1. Obtener los socio_id de todos los familiares para eliminarlos también
                $stmtFams = $db->prepare("
                    SELECT socio_id FROM socio_membresia_ICA_final
                    WHERE cuenta_titular_id = ?
                ");
                $stmtFams->execute([$socio_membresia_id]);
                $familiaresIds = $stmtFams->fetchAll(PDO::FETCH_COLUMN);

                // 2. Eliminar membresías de los familiares
                $stmtDelFamMemb = $db->prepare("
                    DELETE FROM socio_membresia_ICA_final WHERE cuenta_titular_id = ?
                ");
                $stmtDelFamMemb->execute([$socio_membresia_id]);

                // 3. Eliminar registros de persona de los familiares
                foreach ($familiaresIds as $fam_socio_id) {
                    $stmtDelFamSocio = $db->prepare("DELETE FROM socio_ICA_final WHERE id = ?");
                    $stmtDelFamSocio->execute([$fam_socio_id]);
                }

                // 4. Eliminar membresía del titular
                $stmtDelTitMemb = $db->prepare("DELETE FROM socio_membresia_ICA_final WHERE id = ?");
                $stmtDelTitMemb->execute([$socio_membresia_id]);

                // 5. Eliminar registro de persona del titular
                $stmtDelTitSocio = $db->prepare("DELETE FROM socio_ICA_final WHERE id = ?");
                $stmtDelTitSocio->execute([$titular['socio_id']]);

                $db->commit();
                $numFamiliares = count($familiaresIds);
                echo json_encode([
                    'success' => true,
                    'message' => "Titular eliminado" . ($numFamiliares > 0 ? " junto con {$numFamiliares} familiar(es)" : "")
                ]);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        // ─────────────────────────────────────────────────
        // 9. ELIMINAR FAMILIAR (solo el familiar, no al titular)
        // ─────────────────────────────────────────────────
        case 'eliminar_familiar':
            $socio_membresia_id = $_POST['socio_membresia_id'] ?? null;
            if (!$socio_membresia_id) throw new Exception('ID de membresía requerido');

            // Verificar que es un familiar (tiene cuenta_titular_id)
            $stmtCheck = $db->prepare("
                SELECT sm.id, sm.socio_id FROM socio_membresia_ICA_final sm
                WHERE sm.id = ? AND sm.cuenta_titular_id IS NOT NULL
            ");
            $stmtCheck->execute([$socio_membresia_id]);
            $familiar = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if (!$familiar) throw new Exception('Familiar no encontrado');

            $db->beginTransaction();
            try {
                // Eliminar membresía del familiar
                $stmtDelMemb = $db->prepare("DELETE FROM socio_membresia_ICA_final WHERE id = ?");
                $stmtDelMemb->execute([$socio_membresia_id]);

                // Eliminar registro de persona del familiar
                $stmtDelSocio = $db->prepare("DELETE FROM socio_ICA_final WHERE id = ?");
                $stmtDelSocio->execute([$familiar['socio_id']]);

                $db->commit();
                echo json_encode(['success' => true, 'message' => 'Familiar eliminado correctamente']);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        // ─────────────────────────────────────────────────
        // 10. CASHBACK DEL TITULAR
        // ─────────────────────────────────────────────────
        case 'obtener_cashback_titular':
            $socio_membresia_id = $_GET['socio_membresia_id'] ?? null;
            if (!$socio_membresia_id) throw new Exception('ID de socio requerido');

            $stmt = $db->prepare("
                SELECT sm.saldo_cashback, tm.nombre as tipo_membresia, tm.cashback as cashback_pct
                FROM socio_membresia_ICA_final sm
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.id = ? AND sm.parentesco = 'TITULAR'
            ");
            $stmt->execute([$socio_membresia_id]);
            $cashback = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cashback) throw new Exception('Socio no encontrado');
            echo json_encode(['success' => true, 'data' => $cashback]);
            break;

        // ─────────────────────────────────────────────────
        // 11. RENOVAR MEMBRESÍA
        // ─────────────────────────────────────────────────
        case 'renovar_membresia':
            $socio_membresia_id = $_POST['socio_membresia_id'] ?? null;
            $nueva_fecha_fin    = $_POST['nueva_fecha_fin']    ?? null;

            if (!$socio_membresia_id || !$nueva_fecha_fin) throw new Exception('Faltan datos requeridos');
            if (strtotime($nueva_fecha_fin) <= time()) throw new Exception('La fecha de vencimiento debe ser futura');

            $db->beginTransaction();
            try {
                $stmt = $db->prepare("UPDATE socio_membresia_ICA_final SET fecha_fin = ? WHERE id = ? AND parentesco = 'TITULAR'");
                $stmt->execute([$nueva_fecha_fin, $socio_membresia_id]);

                $stmt = $db->prepare("UPDATE socio_membresia_ICA_final SET fecha_fin = ? WHERE cuenta_titular_id = ?");
                $stmt->execute([$nueva_fecha_fin, $socio_membresia_id]);

                $db->commit();
                echo json_encode(['success' => true, 'message' => 'Membresía renovada. Los familiares heredan la nueva fecha.']);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            break;

        // ─────────────────────────────────────────────────
        // 12. BUSCAR SOCIOS
        // ─────────────────────────────────────────────────
        case 'buscar_socios':
            $q = $_GET['q'] ?? '';
            $pattern = '%' . $q . '%';
            $stmt = $db->prepare("
                SELECT 
                    sm.id as socio_membresia_id,
                    sm.numero_socio,
                    s.nombre,
                    s.correo,
                    tm.nombre as tipo_membresia,
                    sm.parentesco
                FROM socio_membresia_ICA_final sm
                JOIN socio_ICA_final s ON sm.socio_id = s.id
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                WHERE sm.activo = 1 AND (s.nombre LIKE ? OR s.correo LIKE ? OR sm.numero_socio LIKE ?)
                ORDER BY s.nombre ASC
                LIMIT 10
            ");
            $stmt->execute([$pattern, $pattern, $pattern]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        // ─────────────────────────────────────────────────
        // 13. LISTAR FAMILIARES CON SUS TITULARES
        // ─────────────────────────────────────────────────
        case 'list_familiares':
            $stmt = $db->prepare("
                SELECT 
                    sm.id as socio_membresia_id,
                    sm.numero_socio,
                    s.nombre,
                    s.correo,
                    s.telefono,
                    sm.parentesco,
                    tm.nombre as tipo_membresia,
                    sm.saldo_cashback,
                    sm.es_complementaria,
                    sm.fecha_fin as vencimiento,
                    sm.activo,
                    sm.cuenta_titular_id,
                    st.nombre as titular_nombre,
                    st.correo as titular_correo,
                    sm_titular.numero_socio as titular_numero_socio
                FROM socio_membresia_ICA_final sm
                JOIN socio_ICA_final s ON sm.socio_id = s.id
                JOIN tipo_membresia_ICA_final tm ON sm.tipo_id = tm.id
                JOIN socio_membresia_ICA_final sm_titular ON sm.cuenta_titular_id = sm_titular.id
                JOIN socio_ICA_final st ON sm_titular.socio_id = st.id
                WHERE sm.cuenta_titular_id IS NOT NULL
                ORDER BY st.nombre, s.nombre ASC
            ");
            $stmt->execute();
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        default:
            throw new Exception('Acción no reconocida: ' . $action);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
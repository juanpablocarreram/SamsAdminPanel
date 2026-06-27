USE SAMS;
-- ═══════════════════════════════════════════════════════════
-- SCRIPT DE ELIMINACIÓN · Sistema Sam's Club (ICA_final)
-- Ejecutar en este orden exacto para evitar conflictos de FK
-- ═══════════════════════════════════════════════════════════

-- 1. ELIMINAR TABLAS DE TRANSACCIONES Y DETALLES (Nivel 4 de dependencia)
DROP TABLE IF EXISTS pago_ICA_final;
DROP TABLE IF EXISTS venta_item_ICA_final;
DROP TABLE IF EXISTS venta_ICA_final;

-- 2. ELIMINAR TABLAS DE CONTROL OPERATIVO, LOGÍSTICA Y PROMOCIONES (Nivel 3 de dependencia)
DROP TABLE IF EXISTS asignacion_operativa_ICA_final;
DROP TABLE IF EXISTS asistencia_ICA_final;
DROP TABLE IF EXISTS empleado_turno_ICA_final;
DROP TABLE IF EXISTS inventario_movimiento_ICA_final;
DROP TABLE IF EXISTS inventario_ICA_final;
DROP TABLE IF EXISTS promocion_membresia_ICA_final;
DROP TABLE IF EXISTS promocion_ICA_final;

-- 3. ELIMINAR TABLAS INTERMEDIAS DE ENTIDADES (Nivel 2 de dependencia)
DROP TABLE IF EXISTS lista_precio_ICA_final;
DROP TABLE IF EXISTS producto_ICA_final;
DROP TABLE IF EXISTS socio_membresia_ICA_final;
DROP TABLE IF EXISTS categoria_ICA_final;
DROP TABLE IF EXISTS empleado_ICA_final;

-- 4. ELIMINAR TABLAS BASE / CATÁLOGOS PRIMARIOS (Nivel 1, sin dependencias)
DROP TABLE IF EXISTS tipo_membresia_ICA_final;
DROP TABLE IF EXISTS socio_ICA_final;
DROP TABLE IF EXISTS zona_operativa_ICA_final;
DROP TABLE IF EXISTS division_ICA_final;
DROP TABLE IF EXISTS proveedor_ICA_final;
DROP TABLE IF EXISTS turno_ICA_final;
DROP TABLE IF EXISTS puesto_ICA_final;

-- 5. ELIMINAR TABLAS DE AUTH Y SUCURSALES
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS sucursales;

-- ═══════════════════════════════
-- VERIFICACIÓN DE LIMPIEZA
-- ═══════════════════════════════
-- Si ejecutas un 'SHOW TABLES;' después de esto, tu base de datos debería estar completamente vacía.
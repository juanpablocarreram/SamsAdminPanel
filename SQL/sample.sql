USE SAMS;
-- =========================================================
-- DATOS DE EJEMPLO ACTUALIZADOS Y CORREGIDOS (ICA_final)
-- Password de prueba para usuarios de muestra: Admin1234!
-- =========================================================

-- 0. SUCURSALES
INSERT INTO sucursales (id, nombre, codigo, ubicacion) VALUES
(1, 'Sam''s Polanco',   'SUC-0001', 'Av. Presidente Masaryk 111, Polanco'),
(2, 'Sam''s Santa Fe',  'SUC-0002', 'Av. Vasco de Quiroga 3800, Santa Fe');

-- 0b. USUARIOS ADMINISTRADORES
INSERT INTO usuarios (nombre, email, password_hash, google_id, rol, sucursal_id) VALUES
('Admin Polanco',   'admin.polanco@sams.mx',  '$2y$10$cD8sLdLuykzzkpaUIrPoA.PXaH0BmYzXFMnRgDzomMi31gOYYjFbO', NULL, 'ADMIN', 1),
('Admin Santa Fe',  'admin.santafe@sams.mx',  '$2y$10$cD8sLdLuykzzkpaUIrPoA.PXaH0BmYzXFMnRgDzomMi31gOYYjFbO', NULL, 'ADMIN', 2);

-- 1. PUESTOS
INSERT INTO puesto_ICA_final (nombre, area, nivel) VALUES
('Cajero', 'CAJAS', 'OPERATIVO'),
('Supervisor de Cajas', 'CAJAS', 'SUPERVISOR'),
('Auxiliar de Piso', 'PISO_VENTA', 'OPERATIVO'),
('Gerente de Tienda', 'OPERACIONES', 'GERENTE'),
('Recibidor', 'RECIBO', 'OPERATIVO'),
('Farmacéutico', 'FARMACIA', 'OPERATIVO'),
('Guardia de Seguridad', 'SEGURIDAD', 'OPERATIVO');

-- 2. TURNOS
INSERT INTO turno_ICA_final (nombre, hora_inicio, hora_fin) VALUES
('Matutino', '07:00:00', '15:00:00'),
('Vespertino', '15:00:00', '22:00:00'),
('Mixto', '10:00:00', '18:00:00');

-- 3. PROVEEDORES
INSERT INTO proveedor_ICA_final (nombre) VALUES
('Bimbo S.A. de C.V.'),
('Coca-Cola FEMSA'),
('Procter & Gamble México'),
('Nestlé México'),
('Unilever de México'),
('Lala Corporativo'),
('Kellogg de México'),
('Samsung Electronics México'),
('Apple México'),
('Nike México');

-- 4. DIVISIONES
INSERT INTO division_ICA_final (nombre) VALUES
('Alimentos y Bebidas'),
('Cuidado Personal'),
('Electrónica'),
('Ropa y Calzado'),
('Hogar y Limpieza'),
('Farmacia'),
('Frescos y Lácteos');

-- 5. CATEGORÍAS
INSERT INTO categoria_ICA_final (division_id, nombre) VALUES
(1, 'Panadería y Tortillería'),
(1, 'Bebidas No Alcohólicas'),
(1, 'Snacks y Botanas'),
(1, 'Cereales y Desayunos'),
(2, 'Shampoo y Acondicionador'),
(2, 'Jabones y Desodorantes'),
(3, 'Teléfonos y Accesorios'),
(3, 'Televisores'),
(4, 'Calzado Deportivo'),
(4, 'Ropa Casual'),
(5, 'Detergentes'),
(5, 'Limpiadores del Hogar'),
(6, 'Medicamentos OTC'),
(7, 'Lácteos'),
(7, 'Embutidos y Carnes Frías');

-- 6. ZONAS OPERATIVAS (catálogo global — compartido por todas las sucursales)
INSERT INTO zona_operativa_ICA_final (nombre, tipo) VALUES
('Piso Principal A',    'PISO_PALLET'),
('Piso Principal B',    'PISO_PALLET'),
('Rack Reserva 1',      'RACK_RESERVA'),
('Rack Reserva 2',      'RACK_RESERVA'),
('Refrigerados Centro', 'REFRIGERADO'),
('Congelados Fondo',    'CONGELADO'),
('Cajas 1-10',          'CAJAS'),
('Andén de Recibo',     'ANDEN'),
('Farmacia',            'SERVICIO'),
('Salida de Control',   'SALIDA_CONTROL');

-- 7. EMPLEADOS (con sucursal_id)
INSERT INTO empleado_ICA_final (numero_empleado, nombre, puesto_id, fecha_ingreso, activo, sucursal_id) VALUES
('EMP001', 'María López García',   1, '2021-03-15', 1, 1), -- Cajera Polanco
('EMP002', 'Juan Pérez Martínez',  2, '2019-06-01', 1, 1), -- Supervisor Cajas Polanco
('EMP003', 'Ana Torres Ruiz',      3, '2022-01-10', 1, 2), -- Aux. Piso Santa Fe
('EMP004', 'Carlos Sánchez Vega',  4, '2018-09-20', 1, 1), -- Gerente Polanco
('EMP005', 'Rosa Hernández Cruz',  5, '2023-02-28', 1, 2); -- Recibidora Santa Fe

-- 8. SOCIOS (La información humana básica)
INSERT INTO socio_ICA_final (nombre, correo, telefono) VALUES
('Roberto Gutiérrez Flores',  'roberto@email.com',  '5551234567'), -- ID 1
('Patricia Morales Luna',     'patricia@email.com', '5557654321'), -- ID 2
('Diego Ramírez Ochoa',      'diego@email.com',    '5559876543'), -- ID 3
('Sofía Jiménez Reyes',      'sofia@email.com',    '5553210987'), -- ID 4
('Miguel Ángel Castro Nava',  'miguel@email.com',   '5554567890'), -- ID 5
('Elena Ramírez Ochoa',      'elena@email.com',     '5551112233'); -- ID 6 (Nueva familiar para el ejemplo)

-- 9. TIPOS DE MEMBRESÍA
INSERT INTO tipo_membresia_ICA_final (nombre, cashback) VALUES
('CLASICA', 0.00),  -- ID 1
('BENEFITS', 2.00), -- ID 2
('PLUS', 3.50);     -- ID 3

-- 10. MEMBRESÍAS DE SOCIOS (con fecha_inicio — socios son globales, sin sucursal_id)
INSERT INTO socio_membresia_ICA_final (id, numero_socio, socio_id, cuenta_titular_id, tipo_id, parentesco, es_complementaria, saldo_cashback, fecha_inicio, fecha_fin, activo) VALUES
(1, 'SAM-100001',   1, NULL, 1, 'TITULAR', 0,   0.00, '2025-12-31', '2026-12-31', 1), -- Roberto: Clásica
(2, 'SAM-100002',   2, NULL, 2, 'TITULAR', 0, 145.50, '2025-08-15', '2026-08-15', 1), -- Patricia: Benefits
(3, 'SAM-100003',   3, NULL, 3, 'TITULAR', 0, 320.00, '2026-01-01', '2027-01-01', 1), -- Diego: PLUS
(4, 'SAM-100004',   4, NULL, 2, 'TITULAR', 0,  75.25, '2025-11-30', '2026-11-30', 1), -- Sofía: Benefits
(5, 'SAM-100005',   5, NULL, 1, 'TITULAR', 0,   0.00, '2025-09-15', '2026-09-15', 1), -- Miguel: Clásica
(6, 'SAM-999999',   3, NULL, 1, 'TITULAR', 0,   0.00, '2026-03-01', '2027-03-01', 1), -- Diego: Clásica Empresarial
(7, 'SAM-100003-F', 6,    3, 3, 'HERMANO', 1,   0.00, '2026-01-01', '2027-01-01', 1); -- Elena: Complementaria PLUS de Diego


-- 11. PRODUCTOS
INSERT INTO producto_ICA_final (sku, nombre, marca, es_members_mark, categoria_id, proveedor_id, tipo, multipack, dias_vida_util, requiere_refrigeracion, requiere_congelacion, activo) VALUES
('SKU-00001', 'Pan de Caja Blanco 680g', 'Bimbo', 0, 1, 1, 'PERECEDERO', 1, 7, 0, 0, 1),
('SKU-00002', 'Pan Integral Member Mark 1kg', 'Members Mark', 1, 1, 1, 'PERECEDERO', 1, 10, 0, 0, 1),
('SKU-00003', 'Coca-Cola 600ml Pack x24', 'Coca-Cola', 0, 2, 2, 'BULK', 24, 365, 0, 0, 1),
('SKU-00004', 'Agua Natural Members Mark 1L x40', 'Members Mark', 1, 2, 2, 'BULK', 40, 730, 0, 0, 1),
('SKU-00005', 'Jugo de Naranja 1.89L', 'Del Valle', 0, 2, 2, 'PERECEDERO', 1, 21, 1, 0, 1),
('SKU-00006', 'Papas Sabritas 170g x8', 'Sabritas', 0, 3, 1, 'BULK', 8, 180, 0, 0, 1),
('SKU-00007', 'Nuez de la India Members Mark 1kg', 'Members Mark', 1, 3, 1, 'BULK', 1, 365, 0, 0, 1),
('SKU-00008', 'Corn Flakes 1.2kg', 'Kelloggs', 0, 4, 7, 'BULK', 1, 365, 0, 0, 1),
('SKU-00009', 'Granola Members Mark 1.5kg', 'Members Mark', 1, 4, 7, 'BULK', 1, 180, 0, 0, 1),
('SKU-00010', 'Shampoo Head & Shoulders 1L x2', 'Procter & Gamble', 0, 5, 3, 'BULK', 2, 1095, 0, 0, 1),
('SKU-00011', 'Desodorante Dove 150g x6', 'Unilever', 0, 6, 5, 'BULK', 6, 1095, 0, 0, 1),
('SKU-00012', 'Televisor Samsung 55" 4K UHD', 'Samsung', 0, 8, 8, 'ELECTRONICA', 1, 0, 0, 0, 1),
('SKU-00013', 'iPhone 15 128GB', 'Apple', 0, 7, 9, 'ELECTRONICA', 1, 0, 0, 0, 1),
('SKU-00014', 'Tenis Nike Air Max Talla 28', 'Nike', 0, 9, 10, 'ROPA', 1, 0, 0, 0, 1),
('SKU-00015', 'Playera Members Mark Paquete x5', 'Members Mark', 1, 10, 10, 'ROPA', 5, 0, 0, 0, 1),
('SKU-00016', 'Detergente Ariel 10kg', 'Procter & Gamble', 0, 11, 3, 'BULK', 1, 1095, 0, 0, 1),
('SKU-00017', 'Fabuloso 5L x2', 'Colgate-Palmolive', 0, 12, 3, 'BULK', 2, 1095, 0, 0, 1),
('SKU-00018', 'Paracetamol 500mg x100', 'Members Mark', 1, 13, 4, 'BULK', 100, 730, 0, 0, 1),
('SKU-00019', 'Leche Lala Entera 1L x12', 'Lala', 0, 14, 6, 'PERECEDERO', 12, 14, 1, 0, 1),
('SKU-00020', 'Yogurt Griego Members Mark 1kg', 'Members Mark', 1, 14, 6, 'PERECEDERO', 1, 21, 1, 0, 1),
('SKU-00021', 'Pizza Members Mark 4 piezas', 'Members Mark', 1, 1, 4, 'CONGELADO', 4, 180, 0, 1, 1),
('SKU-00022', 'Helado Nestlé 4L', 'Nestlé', 0, 1, 4, 'CONGELADO', 1, 365, 0, 1, 1);

-- 12. PRECIOS
INSERT INTO lista_precio_ICA_final (producto_id, precio, vigente, fecha) VALUES
(1,  89.90,   1, NOW()),
(2,  129.00,  1, NOW()),
(3,  285.00,  1, NOW()),
(4,  189.00,  1, NOW()),
(5,  52.50,   1, NOW()),
(6,  148.00,  1, NOW()),
(7,  349.00,  1, NOW()),
(8,  98.00,   1, NOW()),
(9,  178.00,  1, NOW()),
(10, 219.00,  1, NOW()),
(11, 195.00,  1, NOW()),
(12, 12999.00,1, NOW()),
(13, 19999.00,1, NOW()),
(14, 1899.00, 1, NOW()),
(15, 549.00,  1, NOW()),
(16, 389.00,  1, NOW()),
(17, 165.00,  1, NOW()),
(18, 85.00,   1, NOW()),
(19, 245.00,  1, NOW()),
(20, 89.00,   1, NOW()),
(21, 319.00,  1, NOW()),
(22, 189.00,  1, NOW());

-- 13. INVENTARIO
-- Las zonas son catálogo global (IDs 1-10). El aislamiento por sucursal lo da sucursal_id.
INSERT INTO inventario_ICA_final (producto_id, zona_id, cantidad, es_reserva, sucursal_id) VALUES
-- Sucursal 1 — Piso Principal A (zona 1)
( 1,  1,  48, 0, 1), ( 2,  1,  36, 0, 1), ( 3,  1, 120, 0, 1), ( 4,  1, 200, 0, 1), ( 5,  1,  60, 0, 1),
( 6,  1,  96, 0, 1), ( 7,  1,  40, 0, 1), ( 8,  1,  55, 0, 1), ( 9,  1,  45, 0, 1), (10,  1,  72, 0, 1),
(11,  1,  84, 0, 1), (16,  1,  38, 0, 1), (17,  1,  50, 0, 1),
-- Sucursal 1 — Piso Principal B (zona 2)
(12,  2,  15, 0, 1), (13,  2,  22, 0, 1), (14,  2,  30, 0, 1), (15,  2,  60, 0, 1),
-- Sucursal 1 — Refrigerados (zona 5)
( 5,  5,  40, 0, 1), (19,  5,  96, 0, 1), (20,  5,  72, 0, 1),
-- Sucursal 1 — Rack Reserva 1 (zona 3)
( 1,  3, 200, 1, 1), ( 3,  3, 480, 1, 1), ( 4,  3, 600, 1, 1), ( 6,  3, 288, 1, 1),
(10,  3, 144, 1, 1), (16,  3, 120, 1, 1),
-- Sucursal 1 — Rack Reserva 2 (zona 4)
(12,  4,  10, 1, 1), (13,  4,   8, 1, 1), (19,  4, 288, 1, 1),
-- Sucursal 2 — Piso Principal A (zona 1)
( 3,  1,  60, 0, 2), ( 4,  1, 100, 0, 2), ( 8,  1,  30, 0, 2), ( 9,  1,  25, 0, 2),
(10,  1,  36, 0, 2), (11,  1,  42, 0, 2), (16,  1,  20, 0, 2), (17,  1,  25, 0, 2),
-- Sucursal 2 — Piso Principal B (zona 2)
(12,  2,   8, 0, 2), (13,  2,  10, 0, 2), (14,  2,  15, 0, 2), (15,  2,  30, 0, 2),
-- Sucursal 2 — Congelados (zona 6)
(21,  6,  50, 0, 2), (22,  6,  35, 0, 2),
-- Sucursal 2 — Farmacia (zona 9)
(18,  9, 150, 0, 2),
-- Sucursal 2 — Rack Reserva 1 (zona 3)
( 3,  3, 240, 1, 2), ( 4,  3, 300, 1, 2), (10,  3,  72, 1, 2),
-- Sucursal 2 — Rack Reserva 2 (zona 4)
(21,  4, 100, 1, 2), (12,  4,   5, 1, 2);

-- 14. PROMOCIONES GLOBALES
INSERT INTO promocion_ICA_final (id, producto_id, nombre_promo, descuento_pct, descuento_monto, fecha_inicio, fecha_fin, aplica_a_todos) VALUES
(1, 3,  'Refresco x24 Oferta',    10.00, 0.00,  '2026-05-01', '2026-05-31', 1),
(2, 6,  'Snack Pack Ahorro',       0.00, 20.00,  '2026-05-01', '2026-05-31', 1),
(3, 10, 'Cuidado Personal -15%',  15.00, 0.00,  '2026-05-10', '2026-05-25', 1),
(4, 19, 'Leche en Pack Ahorro',    0.00, 25.00,  '2026-05-01', '2026-05-31', 0),
(5, 12, 'Pantalla Especial PLUS', 15.00, 0.00,  '2026-05-15', '2026-05-20', 0);

-- Estado de activación por sucursal (activo=1 donde las ventas de ejemplo las usan)
INSERT INTO promocion_sucursal_ICA_final (promocion_id, sucursal_id, activo) VALUES
(1, 1, 1), (1, 2, 0),
(2, 1, 1), (2, 2, 0),
(3, 1, 1), (3, 2, 0),
(4, 1, 0), (4, 2, 1),
(5, 1, 1), (5, 2, 0); 

-- REGLAS DE ASIGNACIÓN DE DESCUENTOS SEGMENTADOS
INSERT INTO promocion_membresia_ICA_final (promocion_id, tipo_membresia_id) VALUES
(4, 2), -- Promo 4 (Leche) para BENEFITS
(4, 3), -- Promo 4 (Leche) para PLUS
(5, 3); -- Promo 5 (Pantalla) ÚNICAMENTE para PLUS

-- 15. VENTAS DE EJEMPLO (Apuntando a los IDs correctos de la tabla socio_membresia_ICA_final)
INSERT INTO venta_ICA_final (socio_membresia_id, canal, total, fecha, sucursal_id) VALUES
(1, 'CAJA',    434.80,  '2026-05-13 10:30:00', 1), -- Roberto (Clásica - ID 1)
(2, 'SELF',     226.00,  '2026-05-13 11:15:00', 1), -- Patricia (Benefits - ID 2)
(3, 'SCAN_GO', 11049.15, '2026-05-13 12:45:00', 1), -- Diego (Titular PLUS - ID 3)
(4, 'CAJA',     284.00,  '2026-05-14 09:20:00', 2), -- Sofía (Benefits - ID 4)
(6, 'CAJA',    12999.00, '2026-05-14 14:00:00', 2), -- Diego (Su otra cuenta Clásica - ID 6)
(7, 'CAJA',     220.00,  '2026-05-14 15:30:00', 2); -- Elena (Familiar PLUS vinculada - ID 7)

-- DETALLE DE ELEMENTOS VENDIDOS E HISTORIAL DE PROMO APLICADA
INSERT INTO venta_item_ICA_final (venta_id, producto_id, cantidad, precio, descuento, promocion_id, tipo_descuento) VALUES
-- Venta 1 (Clásica)
(1, 3,  1, 285.00, 28.50,  1,    'PROMOCION_GENERAL'),
(1, 1,  2, 89.90,  0.00,   NULL, 'NINGUNO'),
-- Venta 2 (Benefits)
(2, 6,  1, 148.00, 20.00,  2,    'PROMOCION_GENERAL'),
(2, 8,  1, 98.00,  0.00,   NULL, 'NINGUNO'),
-- Venta 3 (Plus)
(3, 12, 1, 12999.00, 1949.85, 5, 'PROMOCION_MEMBRESIA'),
-- Venta 4 (Benefits)
(4, 19, 1, 245.00, 25.00,  4,    'PROMOCION_MEMBRESIA'),
(4, 20, 1, 89.00,  0.00,   NULL, 'NINGUNO'),
-- Venta 5 (Clásica Empresarial de Diego)
(5, 12, 1, 12999.00, 0.00, NULL, 'NINGUNO'),
-- Venta 6 (Familiar PLUS - Elena compra leche con el descuento segmentado heredado por el tipo de cuenta)
(6, 19, 1, 245.00, 25.00,  4,    'PROMOCION_MEMBRESIA');

-- PAGOS ASOCIADOS
INSERT INTO pago_ICA_final (venta_id, metodo, monto) VALUES
(1, 'TARJETA',  434.80),
(2, 'EFECTIVO', 226.00),
(3, 'TARJETA',  11049.15),
(4, 'CASHI',    284.00),
(5, 'EFECTIVO', 12999.00),
(6, 'TARJETA',  220.00);

-- 16. MOVIMIENTOS DE INVENTARIO
INSERT INTO inventario_movimiento_ICA_final (producto_id, tipo, cantidad, fecha, proveedor_id, sucursal_id) VALUES
(1,  'RECEPCION', 200, '2026-05-10 08:00:00', 1, 1),
(3,  'RECEPCION', 480, '2026-05-10 08:30:00', 2, 1),
(4,  'RECEPCION', 600, '2026-05-10 09:00:00', 2, 1),
(10, 'RECEPCION', 144, '2026-05-11 07:45:00', 3, 1),
(12, 'RECEPCION', 25,  '2026-05-11 10:00:00', 8, 1),
(13, 'RECEPCION', 30,  '2026-05-11 10:15:00', 9, 1),
(19, 'RECEPCION', 288, '2026-05-12 06:30:00', 6, 1),
(3,  'VENTA',     24,  '2026-05-13 10:30:00', NULL, 1),
(19, 'VENTA',     1,   '2026-05-13 10:30:00', NULL, 1),
(12, 'VENTA',     1,   '2026-05-13 12:45:00', NULL, 1);

-- ═══════════════════════════════
-- VERIFICACIÓN RÁPIDA DE ESTRUCTURA Y SEGMENTACIÓN
-- ═══════════════════════════════
SELECT 'Membresías Totales' AS Control, COUNT(*) AS Registros FROM socio_membresia_ICA_final UNION ALL
SELECT 'Reglas de Descuento Especial', COUNT(*) FROM promocion_membresia_ICA_final UNION ALL
SELECT 'Ventas Procesadas', COUNT(*) FROM venta_ICA_final;
-- ═══════════════════════════════════════════════════════════
-- DATOS DE EJEMPLO ACTUALIZADOS · Sam's Club style (ICA_final)
-- Ejecutar DESPUÉS del nuevo schema_ICA_final.sql
-- ═══════════════════════════════════════════════════════════

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

-- 6. ZONAS OPERATIVAS
INSERT INTO zona_operativa_ICA_final (nombre, tipo) VALUES
('Piso Principal A', 'PISO_PALLET'),
('Piso Principal B', 'PISO_PALLET'),
('Rack Reserva 1', 'RACK_RESERVA'),
('Rack Reserva 2', 'RACK_RESERVA'),
('Refrigerados Centro', 'REFRIGERADO'),
('Congelados Fondo', 'CONGELADO'),
('Cajas 1-10', 'CAJAS'),
('Andén de Recibo', 'ANDEN'),
('Farmacia', 'SERVICIO'),
('Salida de Control', 'SALIDA_CONTROL');

-- 7. EMPLEADOS
INSERT INTO empleado_ICA_final (numero_empleado, nombre, puesto_id, fecha_ingreso, active) VALUES
('EMP001', 'María López García', 1, '2021-03-15', 1),
('EMP002', 'Juan Pérez Martínez', 2, '2019-06-01', 1),
('EMP003', 'Ana Torres Ruiz', 3, '2022-01-10', 1),
('EMP004', 'Carlos Sánchez Vega', 4, '2018-09-20', 1),
('EMP005', 'Rosa Hernández Cruz', 5, '2023-02-28', 1);

-- 8. SOCIOS (Únicamente la información de la persona humana)
INSERT INTO socio_ICA_final (nombre, correo, telefono) VALUES
('Roberto Gutiérrez Flores',  'roberto@email.com',  '5551234567'),
('Patricia Morales Luna',     'patricia@email.com', '5557654321'),
('Diego Ramírez Ochoa',      'diego@email.com',    '5559876543'),
('Sofía Jiménez Reyes',      'sofia@email.com',    '5553210987'),
('Miguel Ángel Castro Nava',  'miguel@email.com',   '5554567890');

-- 9. TIPOS DE MEMBRESÍA
INSERT INTO tipo_membresia_ICA_final (nombre, cashback) VALUES
('CLASICA', 0.00),
('BENEFITS', 2.00),
('PLUS', 3.50);

-- 10. MEMBRESÍAS DE SOCIOS (Se asigna el plástico/número a la persona)
-- Aquí simulamos que el Socio 3 (Diego) tiene dos plásticos: un PLUS y uno CLASICA (Negocio)
INSERT INTO socio_membresia_ICA_final (numero_socio, socio_id, tipo_id, saldo_cashback, fecha_fin, activo) VALUES
('SAM-100001', 1, 1, 0.00,   '2026-12-31', 1), -- Roberto: Clásica
('SAM-100002', 2, 2, 145.50, '2026-08-15', 1), -- Patricia: Benefits
('SAM-100003', 3, 3, 320.00, '2027-01-01', 1), -- Diego: Cuenta Personal PLUS
('SAM-100004', 4, 2, 75.25,  '2026-11-30', 1), -- Sofía: Benefits
('SAM-100005', 5, 1, 0.00,   '2026-09-15', 1), -- Miguel: Clásica
('SAM-999999', 3, 1, 0.00,   '2027-03-01', 1); -- Diego: Segunda Cuenta (Clásica Empresarial)

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
INSERT INTO inventario_ICA_final (producto_id, zona_id, cantidad, es_reserva) VALUES
(1,  1, 48,  0), (2,  1, 36,  0), (3,  1, 120, 0), (4,  1, 200, 0), (5,  1, 60,  0),
(6,  1, 96,  0), (7,  1, 40,  0), (8,  1, 55,  0), (9,  1, 45,  0), (10, 1, 72,  0),
(11, 1, 84,  0), (16, 1, 38,  0), (17, 1, 50,  0), (12, 2, 15,  0), (13, 2, 22,  0),
(14, 2, 30,  0), (15, 2, 60,  0), (5,  5, 40,  0), (19, 5, 96,  0), (20, 5, 72,  0),
(21, 6, 50,  0), (22, 6, 35,  0), (18, 9, 150, 0), (1,  3, 200, 1), (3,  3, 480, 1),
(4,  3, 600, 1), (6,  3, 288, 1), (10, 3, 144, 1), (16, 3, 120, 1), (12, 4, 10,  1),
(13, 4, 8,   1), (19, 4, 288, 1), (21, 4, 100, 1);

-- 14. PROMOCIONES SEGMENTADAS (Configuración de ofertas)
INSERT INTO promocion_ICA_final (id, producto_id, nombre_promo, descuento_pct, descuento_monto, fecha_inicio, fecha_fin, aplica_a_todos, activo) VALUES
(1, 3,  'Refresco x24 Oferta',    10.00, 0.00,  '2026-05-01', '2026-05-31', 1, 1), -- General (Aplica a todos)
(2, 6,  'Snack Pack Ahorro',       0.00, 20.00,  '2026-05-01', '2026-05-31', 1, 1), -- General
(3, 10, 'Cuidado Personal -15%',  15.00, 0.00,  '2026-05-10', '2026-05-25', 1, 1), -- General
(4, 19, 'Leche en Pack Ahorro',    0.00, 25.00,  '2026-05-01', '2026-05-31', 0, 1), -- Exclusiva (Segmentada)
(5, 12, 'Pantalla Especial PLUS', 15.00, 0.00,  '2026-05-15', '2026-05-20', 0, 1); -- Exclusiva (Segmentada)

-- REGLAS DE ASIGNACIÓN: ¿Quién goza de las promociones exclusivas?
-- (Membresías de referencia: 1 = CLASICA, 2 = BENEFITS, 3 = PLUS)
INSERT INTO promocion_membresia_ICA_final (promocion_id, tipo_membresia_id) VALUES
(4, 2), -- Promo 4 (Leche) disponible para BENEFITS
(4, 3), -- Promo 4 (Leche) disponible para PLUS (La clásica no goza de este descuento)
(5, 3); -- Promo 5 (Pantalla Samsung) ÚNICAMENTE disponible para socios PLUS

-- 15. VENTAS DE EJEMPLO (Apuntando a la tarjeta de socio específica usada)
INSERT INTO venta_ICA_final (socio_membresia_id, canal, total, fecha) VALUES
(1, 'CAJA',    434.80,  '2026-05-13 10:30:00'), -- Usó tarjeta 'SAM-100001' (Clásica)
(2, 'SELF',     226.00,  '2026-05-13 11:15:00'), -- Usó tarjeta 'SAM-100002' (Benefits)
(3, 'SCAN_GO', 11049.15, '2026-05-13 12:45:00'), -- Usó tarjeta 'SAM-100003' (Plus)
(4, 'CAJA',     284.00,  '2026-05-14 09:20:00'), -- Usó tarjeta 'SAM-100004' (Benefits)
(6, 'CAJA',    12999.00, '2026-05-14 14:00:00'); -- Diego usó su OTRA tarjeta 'SAM-999999' (Clásica Empresarial)

-- DETALLE DE ELEMENTOS VENDIDOS E HISTORIAL DE PROMO APLICADA
INSERT INTO venta_item_ICA_final (venta_id, producto_id, cantidad, precio, descuento, promocion_id, tipo_descuento) VALUES
-- Venta 1 (Clásica): Aplica la promo general de Coca Cola, pero paga leche a precio completo (no tiene derecho a promo 4)
(1, 3,  1, 285.00, 28.50,  1,    'PROMOCION_GENERAL'),
(1, 1,  2, 89.90,  0.00,   NULL, 'NINGUNO'),
-- Venta 2 (Benefits): Aplica promo general de Sabritas
(2, 6,  1, 148.00, 20.00,  2,    'PROMOCION_GENERAL'),
(2, 8,  1, 98.00,  0.00,   NULL, 'NINGUNO'),
-- Venta 3 (Plus): Diego compra pantalla con su membresía PLUS. Aplica descuento exclusivo del 15% ($1,949.85)
(3, 12, 1, 12999.00, 1949.85, 5, 'PROMOCION_MEMBRESIA'),
-- Venta 4 (Benefits): Goza del descuento segmentado de la leche porque su membresía lo permite
(4, 19, 1, 245.00, 25.00,  4,    'PROMOCION_MEMBRESIA'),
(4, 20, 1, 89.00,  0.00,   NULL, 'NINGUNO'),
-- Venta 5 (Clásica Empresarial): Diego compra la misma pantalla pero con su OTRA tarjeta (Clásica). Paga precio FULL sin descuento.
(5, 12, 1, 12999.00, 0.00, NULL, 'NINGUNO');

-- PAGOS ASOCIADOS
INSERT INTO pago_ICA_final (venta_id, metodo, monto) VALUES
(1, 'TARJETA',  434.80),
(2, 'EFECTIVO', 226.00),
(3, 'TARJETA',  11049.15),
(4, 'CASHI',    284.00),
(5, 'EFECTIVO', 12999.00);

-- 16. MOVIMIENTOS DE INVENTARIO
INSERT INTO inventario_movimiento_ICA_final (producto_id, tipo, cantidad, fecha, proveedor_id) VALUES
(1,  'RECEPCION', 200, '2026-05-10 08:00:00', 1),
(3,  'RECEPCION', 480, '2026-05-10 08:30:00', 2),
(4,  'RECEPCION', 600, '2026-05-10 09:00:00', 2),
(10, 'RECEPCION', 144, '2026-05-11 07:45:00', 3),
(12, 'RECEPCION', 25,  '2026-05-11 10:00:00', 8),
(13, 'RECEPCION', 30,  '2026-05-11 10:15:00', 9),
(19, 'RECEPCION', 288, '2026-05-12 06:30:00', 6),
(3,  'VENTA',     24,  '2026-05-13 10:30:00', NULL),
(19, 'VENTA',     1,   '2026-05-13 10:30:00', NULL),
(12, 'VENTA',     1,   '2026-05-13 12:45:00', NULL);

-- ═══════════════════════════════
-- VERIFICACIÓN RÁPIDA DE ESTRUCTURA Y SEGMENTACIÓN
-- ═══════════════════════════════
SELECT 'Membresías Totales' AS Control, COUNT(*) AS Registros FROM socio_membresia_ICA_final UNION ALL
SELECT 'Reglas de Descuento Especial', COUNT(*) FROM promocion_membresia_ICA_final UNION ALL
SELECT 'Ventas Procesadas', COUNT(*) FROM venta_ICA_final;
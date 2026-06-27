CREATE DATABASE IF NOT EXISTS SAMS;
USE SAMS;
-- ═══════════════════════════════════════════════════════════
-- ESQUEMA COMPLETO UNIFICADO · Sistema Sam's Club (ICA_final)
-- ═══════════════════════════════════════════════════════════

-- =========================================================
-- 0. MULTI-SUCURSAL — Tablas base del sistema de auth
-- =========================================================

CREATE TABLE IF NOT EXISTS sucursales (
    id         BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre     VARCHAR(150) NOT NULL,
    codigo     VARCHAR(20)  NOT NULL UNIQUE,
    ubicacion  VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usuarios (
    id            BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre        VARCHAR(150) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NULL,
    google_id     VARCHAR(255) NULL UNIQUE,
    rol           ENUM('ADMIN','SUPERVISOR','CAJERO') DEFAULT 'ADMIN',
    sucursal_id   BIGINT NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

-- =========================================================
-- 1. TABLAS BASE (Sin dependencias externas)
-- =========================================================

CREATE TABLE IF NOT EXISTS puesto_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    area ENUM('OPERACIONES','PISO_VENTA','CAJAS','RECIBO','BODEGA','FARMACIA','OPTICA','LLANTERA','CAFE','MEMBRESIAS','SEGURIDAD'),
    nivel ENUM('OPERATIVO','SUPERVISOR','GERENTE')
);

CREATE TABLE IF NOT EXISTS turno_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50),
    hora_inicio TIME,
    hora_fin TIME
);

CREATE TABLE IF NOT EXISTS proveedor_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS division_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS zona_operativa_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    tipo ENUM('PISO_PALLET','RACK_RESERVA','REFRIGERADO','CONGELADO','CAJAS','SALIDA_CONTROL','ANDEN','SERVICIO')
);

-- Aquí se guardan únicamente los datos humanos/biométricos del cliente
CREATE TABLE IF NOT EXISTS socio_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    telefono VARCHAR(20)
);

-- Configuración global de los tipos de membresía del club
CREATE TABLE IF NOT EXISTS tipo_membresia_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nombre ENUM('CLASICA','BENEFITS','PLUS'),
    cashback DECIMAL(5,2)
);

-- =========================================================
-- 2. TABLAS QUE DEPENDEN DE LAS TABLAS BASE
-- =========================================================

CREATE TABLE IF NOT EXISTS empleado_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    numero_empleado VARCHAR(50) UNIQUE,
    nombre VARCHAR(100),
    puesto_id BIGINT,
    fecha_ingreso DATE,
    activo BOOLEAN,
    sucursal_id BIGINT NOT NULL,
    FOREIGN KEY (puesto_id) REFERENCES puesto_ICA_final(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

CREATE TABLE IF NOT EXISTS categoria_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    division_id BIGINT,
    nombre VARCHAR(100),
    FOREIGN KEY (division_id) REFERENCES division_ICA_final(id)
);

-- Une al Humano con su Membresía. Implementa jerarquía familiar (auto-referencia).
-- El límite de 1 tarjeta complementaria gratuita por titular se valida en la capa de aplicación.
CREATE TABLE IF NOT EXISTS socio_membresia_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    numero_socio VARCHAR(50) UNIQUE,
    socio_id BIGINT,
    cuenta_titular_id BIGINT NULL,
    tipo_id BIGINT,
    parentesco ENUM('TITULAR', 'CONYUGE', 'HIJO', 'PADRE', 'HERMANO', 'OTRO') DEFAULT 'TITULAR',
    es_complementaria BOOLEAN DEFAULT 0,
    saldo_cashback DECIMAL(10,2) DEFAULT 0.00,
    fecha_inicio DATE,
    fecha_fin DATE,
    activo BOOLEAN DEFAULT 1,

    FOREIGN KEY (socio_id) REFERENCES socio_ICA_final(id),
    FOREIGN KEY (tipo_id) REFERENCES tipo_membresia_ICA_final(id),
    FOREIGN KEY (cuenta_titular_id) REFERENCES socio_membresia_ICA_final(id) ON DELETE SET NULL
);

-- =========================================================
-- 3. PRODUCTOS Y PRECIOS
-- =========================================================

CREATE TABLE IF NOT EXISTS producto_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(50) UNIQUE,
    nombre VARCHAR(255),
    marca VARCHAR(100),
    es_members_mark BOOLEAN,
    categoria_id BIGINT,
    proveedor_id BIGINT NULL,
    tipo ENUM('BULK','PERECEDERO','CONGELADO','ROPA','ELECTRONICA','SERVICIO'),
    multipack INT,
    dias_vida_util INT,
    requiere_refrigeracion BOOLEAN,
    requiere_congelacion BOOLEAN,
    activo BOOLEAN,
    FOREIGN KEY (categoria_id) REFERENCES categoria_ICA_final(id),
    FOREIGN KEY (proveedor_id) REFERENCES proveedor_ICA_final(id)
);

CREATE TABLE IF NOT EXISTS lista_precio_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    producto_id BIGINT NOT NULL,
    precio DECIMAL(10,2),
    vigente BOOLEAN DEFAULT 1,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES producto_ICA_final(id)
);

-- =========================================================
-- 4. CONTROL OPERATIVO Y ASISTENCIAS
-- =========================================================

CREATE TABLE IF NOT EXISTS empleado_turno_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    empleado_id BIGINT,
    turno_id BIGINT,
    fecha DATE,
    FOREIGN KEY (empleado_id) REFERENCES empleado_ICA_final(id),
    FOREIGN KEY (turno_id) REFERENCES turno_ICA_final(id)
);

CREATE TABLE IF NOT EXISTS asistencia_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    empleado_id BIGINT,
    fecha DATE,
    hora_entrada DATETIME,
    hora_salida DATETIME,
    estatus ENUM('ASISTIO','FALTA','RETARDO'),
    FOREIGN KEY (empleado_id) REFERENCES empleado_ICA_final(id)
);

CREATE TABLE IF NOT EXISTS asignacion_operativa_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    empleado_id BIGINT,
    zona_id BIGINT,
    turno_id BIGINT,
    fecha DATE,
    sucursal_id BIGINT NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleado_ICA_final(id),
    FOREIGN KEY (zona_id) REFERENCES zona_operativa_ICA_final(id),
    FOREIGN KEY (turno_id) REFERENCES turno_ICA_final(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

-- =========================================================
-- 5. LOGÍSTICA E INVENTARIOS
-- =========================================================

CREATE TABLE IF NOT EXISTS inventario_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    producto_id BIGINT,
    zona_id BIGINT,
    cantidad DECIMAL(10,2),
    es_reserva BOOLEAN,
    sucursal_id BIGINT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES producto_ICA_final(id),
    FOREIGN KEY (zona_id) REFERENCES zona_operativa_ICA_final(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

CREATE TABLE IF NOT EXISTS inventario_movimiento_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    producto_id BIGINT,
    tipo ENUM('RECEPCION','VENTA','MERMA','REUBICACION','RECALL'),
    cantidad DECIMAL(10,2),
    fecha DATETIME,
    proveedor_id BIGINT NULL,
    sucursal_id BIGINT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES producto_ICA_final(id),
    FOREIGN KEY (proveedor_id) REFERENCES proveedor_ICA_final(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

-- =========================================================
-- 6. PROMOCIONES SEGMENTADAS
-- =========================================================

CREATE TABLE IF NOT EXISTS promocion_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    producto_id BIGINT NOT NULL,
    nombre_promo VARCHAR(150),
    descuento_pct DECIMAL(5,2) DEFAULT 0,
    descuento_monto DECIMAL(10,2) DEFAULT 0,
    fecha_inicio DATE,
    fecha_fin DATE,
    aplica_a_todos BOOLEAN DEFAULT 0, -- 1 = Todo público, 0 = Solo tipos de membresía autorizados
    activo BOOLEAN DEFAULT 1,
    FOREIGN KEY (producto_id) REFERENCES producto_ICA_final(id)
);

-- Tabla intermedia que decide qué tipo de membresía tiene derecho a qué promoción
CREATE TABLE IF NOT EXISTS promocion_membresia_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    promocion_id BIGINT NOT NULL,
    tipo_membresia_id BIGINT NOT NULL,
    FOREIGN KEY (promocion_id) REFERENCES promocion_ICA_final(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_membresia_id) REFERENCES tipo_membresia_ICA_final(id),
    UNIQUE KEY uq_promocion_membresia (promocion_id, tipo_membresia_id)
);

-- =========================================================
-- 7. TRANSACCIONES / VENTAS
-- =========================================================

-- La venta apunta a la membresía específica escaneada en la caja (sea del titular o del familiar)
CREATE TABLE IF NOT EXISTS venta_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    socio_membresia_id BIGINT,
    canal ENUM('CAJA','SELF','SCAN_GO'),
    total DECIMAL(10,2),
    fecha DATETIME,
    sucursal_id BIGINT NOT NULL,
    FOREIGN KEY (socio_membresia_id) REFERENCES socio_membresia_ICA_final(id),
    FOREIGN KEY (sucursal_id) REFERENCES sucursales(id)
);

-- Detalle de los productos vendidos e historial de promociones aplicadas en el ticket
CREATE TABLE IF NOT EXISTS venta_item_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    venta_id BIGINT,
    producto_id BIGINT,
    cantidad DECIMAL(10,2),
    precio DECIMAL(10,2), 
    descuento DECIMAL(10,2) DEFAULT 0.00, 
    promocion_id BIGINT NULL, -- Indica qué promoción específica se usó (si aplica)
    tipo_descuento ENUM('PROMOCION_MEMBRESIA','PROMOCION_GENERAL','MANUAL','NINGUNO') DEFAULT 'NINGUNO',
    FOREIGN KEY (venta_id) REFERENCES venta_ICA_final(id),
    FOREIGN KEY (producto_id) REFERENCES producto_ICA_final(id),
    FOREIGN KEY (promocion_id) REFERENCES promocion_ICA_final(id)
);

CREATE TABLE IF NOT EXISTS pago_ICA_final (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    venta_id BIGINT,
    metodo ENUM('EFECTIVO','TARJETA','CASHI','INBURSA','VALES'),
    monto DECIMAL(10,2),
    FOREIGN KEY (venta_id) REFERENCES venta_ICA_final(id)
);
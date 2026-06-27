# Sam's Club Admin Panel

Panel de administración web para la gestión operativa de tiendas Sam's Club. Diseñado con arquitectura **multi-sucursal**: cada cuenta representa una sucursal independiente con inventario, ventas, compras y promociones aisladas, compartiendo únicamente el catálogo global de socios y productos.

---

## Características principales

- **Autenticación** con email/password y OAuth2 con Google
- **Multi-sucursal**: aislamiento completo de datos por tienda
- **Gestión de inventario** con stock en piso y reserva por zona operativa
- **Punto de venta** con soporte para múltiples métodos de pago y cashback
- **Compras / recepción de mercancía** con actualización automática de precios
- **Promociones globales** con activación independiente por sucursal
- **Gestión de socios** con jerarquía familiar (titular + complementarios)
- **Segmentación de descuentos** por tipo de membresía (Clásica, Benefits, Plus)

---

## Tech Stack

| Capa | Tecnología |
|---|---|
| Frontend | HTML5, Tailwind CSS (CDN), Vanilla JS, Lucide Icons |
| Backend | PHP 8+ (API JSON, PDO) |
| Base de datos | MySQL 8 (`only_full_group_by` compatible) |
| Autenticación | PHP Sessions + Google OAuth2 (`google/apiclient`) |
| Configuración | `vlucas/phpdotenv` |
| Servidor local | XAMPP / LAMPP |

---

## Estructura del proyecto

```
SamsAdminPanel/
├── SQL/
│   ├── schema.sql          # Esquema completo de la base de datos
│   ├── sample.sql          # Datos de ejemplo para desarrollo
│   └── delete_tables.sql   # Script para resetear la BD
├── auth.php                # Guard de sesión para APIs y páginas
├── auth_handler.php        # Login, registro, logout, OAuth Google
├── google_callback.php     # Callback OAuth2 de Google
├── login.php               # UI de autenticación (login / registro)
├── index.php               # SPA principal del panel
├── database.php            # Singleton PDO (carga .env)
├── inventario.php          # API: gestión de inventario
├── ventas.php              # API: punto de venta
├── compras.php             # API: recepción de mercancía
├── promociones.php         # API: gestión de promociones
├── socios.php              # API: gestión de socios y membresías
└── composer.json
```

---

## Instalación local

### Requisitos previos

- XAMPP / LAMPP con PHP 8+ y MySQL 8
- Composer

### Pasos

**1. Clonar el repositorio**

```bash
git clone https://github.com/juanpablocarreram/SamsAdminPanel.git
cd SamsAdminPanel
```

**2. Instalar dependencias PHP**

```bash
composer install
```

**3. Configurar variables de entorno**

Crea un archivo `.env` en la raíz del proyecto:

```env
DB_HOST=127.0.0.1
DB_NAME=SAMS
DB_USER=root
DB_PASS=

# Opcional: Google OAuth2
GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://localhost/SamsAdminPanel/google_callback.php
```

**4. Iniciar LAMPP**

```bash
sudo /opt/lampp/lampp start
```

**5. Crear la base de datos**

```bash
/opt/lampp/bin/mysql -u root < SQL/schema.sql
/opt/lampp/bin/mysql -u root < SQL/sample.sql   # opcional: carga datos de ejemplo
```

**6. Acceder al panel**

```
http://localhost/SamsAdminPanel/login.php
```

---

## Credenciales de ejemplo

Si ejecutaste `sample.sql`, puedes iniciar sesión con:

| Sucursal | Email | Contraseña |
|---|---|---|
| Sam's Polanco | admin.polanco@sams.mx | Admin1234! |
| Sam's Santa Fe | admin.santafe@sams.mx | Admin1234! |

---

## Modelo de datos (resumen)

### Tablas globales (compartidas entre sucursales)
- `sucursales` — tiendas registradas
- `usuarios` — administradores por sucursal
- `producto_ICA_final` — catálogo de productos
- `lista_precio_ICA_final` — historial de precios vigentes
- `socio_ICA_final` + `socio_membresia_ICA_final` — socios y membresías
- `tipo_membresia_ICA_final` — Clásica, Benefits, Plus
- `promocion_ICA_final` — definición global de promociones
- `zona_operativa_ICA_final` — catálogo de zonas físicas

### Tablas por sucursal (aisladas)
- `inventario_ICA_final` — stock con `sucursal_id`
- `inventario_movimiento_ICA_final` — recepciones, ventas, mermas
- `venta_ICA_final` + `venta_item_ICA_final` — tickets de venta
- `empleado_ICA_final` — personal por tienda
- `promocion_sucursal_ICA_final` — estado de activación de cada promo por sucursal

---

## Flujo de registro

Al crear una cuenta nueva se generan automáticamente:
1. Una **sucursal** con código único (`SUC-0001`, `SUC-0002`, …)
2. Un **usuario ADMIN** vinculado a esa sucursal

El inventario, empleados, ventas y compras inician vacíos y se gestionan desde el panel.

---

## Resetear la base de datos

```bash
/opt/lampp/bin/mysql -u root < SQL/delete_tables.sql
/opt/lampp/bin/mysql -u root < SQL/schema.sql
/opt/lampp/bin/mysql -u root < SQL/sample.sql
```

---

## Autor

**Juan Pablo Carrera**

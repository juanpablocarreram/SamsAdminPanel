<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAMS · Sistema de Administración de Membresías</title>
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sam-blue: #003DA5;
            --sam-yellow: #FFC220;
            --sam-red: #C8102E;
            --sam-dark: #0A1628;
            --sam-light: #F5F7FA;
            --sam-card: #FFFFFF;
            --sam-border: #E5E9F0;
            --sam-hover: #F0F4FF;
            --sam-text-secondary: #6B7280;
        }
        * { box-sizing: border-box; }
        body { background: var(--sam-light); font-family: 'Inter', sans-serif; color: #1F2937; }

        /* ── NAV ── */
        .sam-navbar {
            background: linear-gradient(135deg, #001f6b 0%, #003DA5 55%, #0052cc 100%);
            padding: 0; position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 24px rgba(0,0,0,.28);
        }
        .sam-navbar-inner { display: flex; align-items: stretch; flex-wrap: wrap; padding: 0; }
        .sam-brand { display: flex; align-items: center; gap: 14px; padding: 0 28px; min-height: 64px; text-decoration: none; }
        .sam-brand-logo { width:5vw; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0,0,0,.5)) brightness(1.1); }
        .sam-nav-divider { width: 1px; background: rgba(255,255,255,.1); margin: 10px 0; }
        .sam-nav-tabs { border: none; margin: 0; padding: 0 8px; display: flex; align-items: stretch; }
        .sam-nav-tabs .nav-item { display: flex; align-items: stretch; }
        .sam-nav-tabs .nav-link {
            color: rgba(255,255,255,.6) !important; border: none; border-radius: 0;
            padding: 0 22px; font-weight: 600; font-size: .82rem; letter-spacing: .8px;
            text-transform: uppercase; border-bottom: 3px solid transparent;
            transition: all .2s ease; display: flex; align-items: center; gap: 8px; min-height: 64px; position: relative;
        }
        .sam-nav-tabs .nav-link:hover { color: #fff !important; background: rgba(255,255,255,.07); }
        .sam-nav-tabs .nav-link.active { color: #fff !important; border-bottom-color: var(--sam-yellow); background: rgba(255,255,255,.06); }
        .sam-nav-tabs .nav-link.active::after {
            content: ''; position: absolute; bottom: -3px; left: 50%; transform: translateX(-50%);
            width: 6px; height: 6px; background: var(--sam-yellow); border-radius: 50%;
        }
        .sam-nav-right { margin-left: auto; display: flex; align-items: center; padding: 0 20px; gap: 12px; }
        .sam-system-badge {
            display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12); border-radius: 20px; padding: 5px 12px;
            font-size: .72rem; font-weight: 700; color: rgba(255,255,255,.6); letter-spacing: 1px; text-transform: uppercase;
        }
        .sam-status-dot {
            width: 7px; height: 7px; background: #10B981; border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(16,185,129,.3); animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot { 0%, 100% { box-shadow: 0 0 0 2px rgba(16,185,129,.3); } 50% { box-shadow: 0 0 0 5px rgba(16,185,129,.1); } }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--sam-card); border-radius: 12px; padding: 22px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08); border-left: 5px solid var(--sam-blue);
            border-top: 1px solid var(--sam-border); border-right: 1px solid var(--sam-border);
            border-bottom: 1px solid var(--sam-border); transition: all .3s ease;
        }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.12); transform: translateY(-2px); }
        .stat-card.yellow { border-left-color: var(--sam-yellow); }
        .stat-card.red    { border-left-color: var(--sam-red); }
        .stat-card.green  { border-left-color: #10B981; }
        .stat-card h3 { font-size: 2.2rem; font-weight: 800; color: var(--sam-dark); margin: 0; letter-spacing: -0.5px; }
        .stat-card p  { margin: 8px 0 0 0; color: var(--sam-text-secondary); font-size: .9rem; font-weight: 500; }

        /* ── TABLES ── */
        .sam-table { background: var(--sam-card); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08); border: 1px solid var(--sam-border); }
        .sam-table .table { margin-bottom: 0; }
        .sam-table thead th {
            background: linear-gradient(135deg, var(--sam-blue) 0%, #002E85 100%);
            color: #fff; font-size: .85rem; letter-spacing: .4px; border: none;
            padding: 14px 16px; font-weight: 700; text-transform: uppercase;
        }
        .sam-table tbody tr { border-bottom: 1px solid var(--sam-border); transition: all .2s ease; }
        .sam-table tbody tr:last-child { border-bottom: none; }
        .sam-table tbody tr:hover { background: var(--sam-hover); }
        .sam-table td { vertical-align: middle; font-size: .9rem; padding: 14px 16px; color: #374151; }
        .sam-table tbody tr td strong { color: var(--sam-dark); font-weight: 600; }
        .sam-table .text-muted { color: var(--sam-text-secondary) !important; font-size: .85rem; }

        /* ── BADGES ── */
        .badge-tipo { font-size: .75rem; font-weight: 600; padding: 4px 8px; border-radius: 6px; }
        .badge-members { background: var(--sam-blue); color: #fff; font-weight: 700; }
        .badge-promo { background: var(--sam-yellow); color: #000; font-weight: 700; }
        .badge-membresia-clasica  { background: #6B7280; color: #fff; }
        .badge-membresia-benefits { background: #003DA5; color: #fff; }
        .badge-membresia-plus     { background: #7C3AED; color: #fff; }
        .badge-desc-ok  { background: #D1FAE5; color: #065F46; font-size:.75rem; font-weight:700; padding:3px 7px; border-radius:6px; }
        .badge-desc-no  { background: #FEF3C7; color: #92400E; font-size:.75rem; font-weight:600; padding:3px 7px; border-radius:6px; }

        /* ── FORMS ── */
        .sam-form-card {
            background: var(--sam-card); border-radius: 12px; padding: 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08); border: 1px solid var(--sam-border);
            overflow-y: auto; max-height: calc(100vh - 180px);
        }
        .sam-form-card .mb-3 { margin-bottom: 18px !important; }
        .form-label { font-size: 0.9rem; margin-bottom: 8px; font-weight: 600; color: var(--sam-dark); display: block; }
        .form-control, .form-select {
            font-size: 0.95rem; padding: 10px 13px; border: 1.5px solid var(--sam-border);
            border-radius: 8px; background: var(--sam-card); color: #374151;
            transition: all .2s ease; height: auto; min-height: 40px;
        }
        .form-control:focus, .form-select:focus { border-color: var(--sam-blue); box-shadow: 0 0 0 3px rgba(0, 61, 165, 0.1); outline: none; }
        .form-control::placeholder { color: #9CA3AF; }
        .section-title {
            font-weight: 800; color: var(--sam-dark); font-size: 1rem; margin-bottom: 12px;
            padding-bottom: 8px; border-bottom: 3px solid var(--sam-yellow); display: block;
        }

        /* ── MEMBRESÍA CHECKBOXES ── */
        .membresia-checks { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
        .memb-check-label {
            display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px;
            cursor: pointer; border: 1.5px solid var(--sam-border); font-size: .85rem; font-weight: 600;
            transition: all .2s; user-select: none;
        }
        .memb-check-label:hover { border-color: var(--sam-blue); background: var(--sam-hover); }
        .memb-check-label input { display: none; }
        .memb-check-label.checked-CLASICA  { background: #F3F4F6; border-color: #6B7280; color: #374151; }
        .memb-check-label.checked-BENEFITS { background: #EFF6FF; border-color: var(--sam-blue); color: var(--sam-blue); }
        .memb-check-label.checked-PLUS     { background: #F5F3FF; border-color: #7C3AED; color: #7C3AED; }
        .todos-check-wrap { margin-bottom: 8px; }
        .todos-check-wrap label { font-size:.85rem; font-weight:600; color:var(--sam-text-secondary); cursor:pointer; }

        /* ── POS PANEL ── */
        .pos-panel {
            background: var(--sam-card); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08);
            border: 1px solid var(--sam-border); height: calc(100vh - 160px); display: flex;
            flex-direction: column; overflow: hidden;
        }
        .pos-panel-header {
            background: linear-gradient(135deg, var(--sam-blue) 0%, #002E85 100%);
            color: #fff; padding: 16px 20px; border-radius: 12px 12px 0 0; font-weight: 700; font-size: 1rem;
        }
        .pos-panel-body { flex: 1; overflow-y: auto; padding: 20px; }
        .pos-panel-footer {
            padding: 16px 20px; border-top: 2px solid var(--sam-border);
            background: var(--sam-light); border-radius: 0 0 12px 12px;
        }
        .pos-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px;
            border-radius: 8px; margin-bottom: 8px; background: var(--sam-light);
            border: 1px solid var(--sam-border); transition: all .2s ease;
        }
        .pos-item:hover { background: var(--sam-hover); border-color: var(--sam-blue); }
        .pos-item-name { flex: 1; font-size: .9rem; font-weight: 600; color: var(--sam-dark); }
        .pos-item-price { font-size: .95rem; font-weight: 700; color: var(--sam-blue); white-space: nowrap; }
        .pos-total { font-size: 1.8rem; font-weight: 800; color: var(--sam-blue); letter-spacing: -0.5px; }

        /* ── SEARCH RESULTS ── */
        #searchResults, #socioResults {
            position: absolute; z-index: 1050; width: 100%; background: var(--sam-card);
            border: 1px solid var(--sam-border); border-top: none; border-radius: 0 0 8px 8px;
            max-height: 300px; overflow-y: auto; box-shadow: 0 8px 16px rgba(0,0,0,.12);
        }
        #searchResults .res-item, #socioResults > div {
            padding: 12px 14px; cursor: pointer; border-bottom: 1px solid var(--sam-border); font-size: .9rem; transition: all .2s ease;
        }
        #searchResults .res-item:last-child, #socioResults > div:last-child { border-bottom: none; }
        #searchResults .res-item:hover, #socioResults > div:hover { background: var(--sam-hover); }
        #searchResults .res-sku { color: var(--sam-text-secondary); font-size: .8rem; margin-top: 4px; }
        .res-memb-tag { font-size: .72rem; font-weight: 700; padding: 2px 6px; border-radius: 5px; background: #EFF6FF; color: var(--sam-blue); border: 1px solid #BFDBFE; }

        /* ── BUTTONS ── */
        .btn { font-weight: 600; padding: 10px 16px; border-radius: 8px; transition: all .2s ease; font-size: 0.9rem; }
        .btn-primary { background: var(--sam-blue); border: none; }
        .btn-primary:hover { background: #002E85; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 61, 165, 0.3); }
        .btn-success { background: #10B981; border: none; }
        .btn-success:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        .btn-danger { background: var(--sam-red); border: none; }
        .btn-danger:hover { background: #B50A23; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(200, 16, 46, 0.3); }
        .btn-outline-primary { color: var(--sam-blue); border: 1.5px solid var(--sam-blue); background: transparent; }
        .btn-outline-primary:hover { background: var(--sam-blue); color: white; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }

        /* ── ALERTS ── */
        .alert { border-radius: 8px; border: 1px solid; padding: 12px 16px; }

        /* ── UTILITIES ── */
        .tab-content > .tab-pane { padding: 24px 0; opacity: 1 !important; visibility: visible !important; }
        .compra-item-row td { vertical-align: middle; }
        .input-group-text { background: var(--sam-light); border: 1.5px solid var(--sam-border); color: var(--sam-blue); font-weight: 600; }
        .input-group .form-control { border: 1.5px solid var(--sam-border); }

        /* ── TOAST ── */
        #toastArea { position: fixed; bottom: 24px; right: 24px; z-index: 9999; }
        .sam-toast {
            padding: 16px 20px; border-radius: 8px; color: #fff; font-weight: 600;
            margin-top: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.15);
            animation: slideIn .3s ease; min-width: 300px; font-size: 0.95rem;
        }
        .sam-toast.success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .sam-toast.error   { background: linear-gradient(135deg, var(--sam-red) 0%, #B50A23 100%); }
        .sam-toast.warn    { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
        @keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* ── LOADER ── */
        .spinner-sam { display: inline-block; width: 18px; height: 18px; border: 3px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) { .sam-form-card { padding: 20px; max-height: none; } .btn { padding: 9px 14px; font-size: 0.85rem; } }

        /* ── COMPRA EDITABLE TABLE ── */
        .compra-items-table thead th { background: linear-gradient(135deg, #374151 0%, #1F2937 100%); color: #fff; font-size: .78rem; letter-spacing: .4px; border: none; padding: 10px 12px; font-weight: 700; text-transform: uppercase; }
        .compra-items-table td { padding: 8px 10px; vertical-align: middle; font-size: .88rem; }
        .compra-items-table input[type="number"] { font-size: .88rem; padding: 5px 8px; border: 1.5px solid var(--sam-border); border-radius: 6px; width: 100%; min-height: 32px; color: #374151; background: var(--sam-card); transition: border-color .2s; }
        .compra-items-table input[type="number"]:focus { border-color: var(--sam-blue); box-shadow: 0 0 0 2px rgba(0,61,165,.08); outline: none; }
        .compra-items-table .subtotal-cell { font-weight: 700; color: var(--sam-blue); }

        /* ── SOCIO BLOCK ── */
        .socio-block { background: var(--sam-light); border: 1.5px solid var(--sam-border); border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; position: relative; }
        .socio-block .socio-label-title { font-size: .78rem; font-weight: 700; color: var(--sam-text-secondary); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 8px; }
        .socio-block .socio-badge-active { display: flex; align-items: center; gap: 8px; background: var(--sam-blue); color: #fff; border-radius: 8px; padding: 8px 12px; font-size: .88rem; font-weight: 600; }
        .socio-block .socio-badge-active .quitar-btn { margin-left: auto; background: rgba(255,255,255,.2); border: none; color: #fff; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .85rem; flex-shrink: 0; transition: background .2s; }
        .socio-block .socio-badge-active .quitar-btn:hover { background: rgba(255,255,255,.35); }
        .memb-pill { display: inline-block; font-size: .72rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; margin-left: 4px; }
        .memb-pill.CLASICA  { background: rgba(255,255,255,.25); }
        .memb-pill.BENEFITS { background: rgba(255,220,0,.35); color: #FFC220; }
        .memb-pill.PLUS     { background: rgba(180,100,255,.35); color: #E9D5FF; }

        /* ── PAGO ── */
        #pagoResumen { display: flex; justify-content: space-between; font-size: .82rem; color: var(--sam-text-secondary); margin-top: 4px; }
        #pagoResumen .pago-faltante { color: var(--sam-red); font-weight: 700; }
        #pagoResumen .pago-ok { color: #10B981; font-weight: 700; }

        /* ── TABLE NOWRAP ── */
        .sam-table tbody tr td { white-space: nowrap; }
        .sam-table tbody tr td:first-child, .sam-table tbody tr td:nth-child(2) { white-space: normal; }

        /* ── PAGO ROW ── */
        .pago-row { gap: 12px !important; }
        .pago-row .form-select, .pago-row .form-control { min-height: 36px; }

        .badge { padding: 6px 10px; font-size: 0.8rem; font-weight: 600; border-radius: 6px; }
        @media (max-width: 1024px) { .sam-table { font-size: 0.85rem; } .sam-table th, .sam-table td { padding: 10px 8px; } }
        .spinner-border-sm { width: 16px; height: 16px; border-width: 2px; }

        /* ── INPUT GROUPS ── */
        .input-group-sm .btn { padding: 4px 8px; font-size: 0.8rem; }
        .input-group-sm input { font-weight: 600; text-align: center; }
        .form-group, .mb-3 { position: relative; }
        .pos-panel-body .d-flex { gap: 8px; }
        .pos-panel-body .section-title { margin-top: 20px; margin-bottom: 14px; }
        #cambioInfo { margin-top: 6px; }

        /* ── COMPRAS: form card sin altura máxima ── */
        #panel-compras .sam-form-card { max-height: none; overflow-y: visible; }

        /* ── ACCIONES SOCIOS ── */
        .acciones-socio { display: flex; gap: 4px; flex-wrap: nowrap; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="sam-navbar">
    <div class="sam-navbar-inner container-fluid px-0">
        <div class="sam-brand">
            <img src="img/sams_logo.png" alt="Sam's Club" class="sam-brand-logo">
        </div>
        <ul class="nav sam-nav-tabs" id="mainTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-inv" data-bs-toggle="tab" href="#panel-inventario" role="tab">Inventario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-socios" data-bs-toggle="tab" href="#panel-socios" role="tab">Socios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-promo" data-bs-toggle="tab" href="#panel-promos" role="tab">Promociones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-comp" data-bs-toggle="tab" href="#panel-compras" role="tab">Compras</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-vta" data-bs-toggle="tab" href="#panel-ventas" role="tab">Punto de Venta</a>
            </li>
        </ul>
        <div class="sam-nav-right">
            <div class="sam-system-badge">
                <span class="sam-status-dot"></span>
                Sistema activo
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-3">
<div class="tab-content" id="mainTabContent">

<!-- ═══════════════════════════════════════════════
     TAB 1 · INVENTARIO
═══════════════════════════════════════════════ -->
<div class="tab-pane fade show active" id="panel-inventario" role="tabpanel">
    <div class="row g-3 mb-4" id="invStats">
        <div class="col-6 col-md-3"><div class="stat-card"><h3 id="st-total">—</h3><p>Total productos</p></div></div>
        <div class="col-6 col-md-3"><div class="stat-card green"><h3 id="st-stock">—</h3><p>Con existencia</p></div></div>
        <div class="col-6 col-md-3"><div class="stat-card red"><h3 id="st-sin">—</h3><p>Sin existencia</p></div></div>
        <div class="col-6 col-md-3"><div class="stat-card yellow"><h3 id="st-promo">—</h3><p>Con promoción activa</p></div></div>
    </div>
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="flex: 1; max-width: 400px;">
            <input id="invSearch" type="text" class="form-control" placeholder="🔍  Buscar por nombre, SKU o marca…">
        </div>
        <button class="btn btn-primary fw-bold" onclick="loadInventario()">↺ Actualizar</button>
    </div>
    <div class="sam-table table-responsive">
        <table class="table table-hover mb-0" id="invTable">
            <thead>
                <tr>
                    <th>SKU</th><th>Nombre</th><th>Marca</th><th>Tipo</th>
                    <th>Categoría</th><th class="text-end">Precio</th>
                    <th class="text-end">Stock Piso</th><th class="text-end">Stock Reserva</th>
                    <th>Promoción</th>
                </tr>
            </thead>
            <tbody id="invBody">
                <tr><td colspan="9" class="text-center py-4 text-muted">Cargando inventario…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 2 · SOCIOS
═══════════════════════════════════════════════ -->
<div class="tab-pane fade" id="panel-socios" role="tabpanel">
    <div style="display: flex; gap: 20px; height: 100%; align-items: flex-start;">
        <!-- FORM PANEL -->
        <div style="flex: 0 0 35%; min-width: 0;">
            <div class="sam-form-card">
                <div id="socioFormNuevo">
                    <div class="section-title">👤 Nuevo Socio Titular</div>
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" id="socioNombre" class="form-control" placeholder="Ej: Juan Pérez">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" id="socioCorreo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" id="socioTelefono" class="form-control" placeholder="+52 222 123 4567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">🎫 Tipo de membresía</label>
                        <select id="socioTipoMembresia" class="form-select">
                            <option value="">Seleccionar tipo…</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">📅 Fecha de vencimiento</label>
                        <input type="date" id="socioFechaFin" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100 fw-bold" onclick="crearSocioTitular()">
                        ✅ Registrar Socio Titular
                    </button>
                    <button class="btn btn-outline-secondary w-100 mt-2" onclick="mostrarFormFamiliar()">
                        👨‍👩‍👧 Vincular Familiar
                    </button>
                </div>

                <!-- FORM FAMILIAR -->
                <div id="socioFormFamiliar" style="display: none;">
                    <div class="section-title">👨‍👩‍👧 Vincular Familiar</div>
                    <div class="mb-3">
                        <label class="form-label">Socio Titular</label>
                        <select id="socioTitularSel" class="form-select">
                            <option value="">Seleccionar titular…</option>
                        </select>
                    </div>
                    <div class="alert alert-info" style="font-size:.82rem; padding:10px 12px;">
                        ℹ️ El familiar heredará automáticamente el <strong>tipo de membresía</strong> y la <strong>fecha de vencimiento</strong> del titular.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del familiar</label>
                        <input type="text" id="familiarNombre" class="form-control" placeholder="Nombre completo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" id="familiarCorreo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" id="familiarTelefono" class="form-control" placeholder="+52 222 123 4567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">👨‍👩‍👧 Parentesco</label>
                        <select id="familiarParentesco" class="form-select">
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="HIJO">Hijo/a</option>
                            <option value="PADRE">Padre/Madre</option>
                            <option value="HERMANO">Hermano/a</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" id="familiarComplementaria">
                            <span>¿Tarjeta complementaria gratis?</span>
                        </label>
                        <small class="text-muted">Solo 1 complementaria gratis por titular.</small>
                    </div>
                    <button class="btn btn-success w-100 fw-bold" onclick="crearSocioFamiliar()">
                        ✅ Vincular Familiar
                    </button>
                    <button class="btn btn-outline-secondary w-100 mt-2" onclick="mostrarFormNuevo()">
                        ↩️ Volver
                    </button>
                </div>
            </div>
        </div>

        <!-- LISTADO Y DETALLES -->
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column;">
            <div class="d-flex justify-content-between align-items-center mb-2" style="gap: 12px; flex-shrink: 0;">
                <h3 class="section-title mb-0" style="margin: 0; padding-bottom: 8px; border-bottom: 3px solid #FFC220; flex: 1;">👥 Socios Registrados</h3>
                <div style="display: flex; gap: 6px;">
                    <input type="text" id="sociosBuscar" class="form-control form-control-sm" placeholder="🔍 Buscar…" style="width: 200px;">
                    <button class="btn btn-outline-primary btn-sm" onclick="loadSocios()">↺</button>
                </div>
            </div>

            <ul class="nav nav-tabs mb-2" id="sociosTab" role="tablist" style="flex-shrink: 0;">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-socios-list" data-bs-toggle="tab" href="#socios-listado" role="tab">📋 Titulares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-socios-familiares" data-bs-toggle="tab" href="#socios-familiares" role="tab">👨‍👩‍👧 Familiares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-socios-detalles" data-bs-toggle="tab" href="#socios-detalles" role="tab">👤 Detalles</a>
                </li>
            </ul>

            <div class="tab-content" style="flex: 1; overflow-y: auto;">
                <!-- Listado de titulares -->
                <div class="tab-pane fade show active" id="socios-listado" role="tabpanel">
                    <div class="sam-table table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th><th>Número</th><th>Nombre</th><th>Membresía</th>
                                    <th class="text-center">Familiares</th><th>Vencimiento</th>
                                    <th class="text-end">Cashback</th><th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sociosBody">
                                <tr><td colspan="8" class="text-center text-muted py-4">Cargando socios…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Listado de familiares -->
                <div class="tab-pane fade" id="socios-familiares" role="tabpanel">
                    <div class="d-flex gap-2 mb-3">
                        <input type="text" id="familiarBuscar" class="form-control form-control-sm" placeholder="🔍 Buscar familiar…" style="max-width: 300px;">
                        <button class="btn btn-outline-primary btn-sm" onclick="loadFamiliares()">↺</button>
                    </div>
                    <div class="sam-table table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Familiar</th><th>Número</th><th>Parentesco</th><th>Membresía</th>
                                    <th>Titular</th><th class="text-center">Complem.</th>
                                    <th>Vencimiento</th><th class="text-end">Cashback</th><th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="familiaresBody">
                                <tr><td colspan="9" class="text-center text-muted py-4">Cargando familiares…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detalles de socio -->
                <div class="tab-pane fade" id="socios-detalles" role="tabpanel">
                    <div id="socioDetallesContent" class="text-center text-muted py-4">
                        Selecciona un socio para ver los detalles
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 3 · PROMOCIONES
═══════════════════════════════════════════════ -->
<div class="tab-pane fade" id="panel-promos" role="tabpanel">
    <div style="display: flex; gap: 20px; height: 100%;">
        <div style="flex: 0 0 40%; min-width: 0;">
            <div class="sam-form-card">
                <div class="section-title">➕ Nueva Promoción</div>
                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select id="promoProducto" class="form-select"><option value="">Cargando…</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de la promoción</label>
                    <input type="text" id="promoNombre" class="form-control" placeholder="Ej: Promocion Verano">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descuento %</label>
                    <input type="number" id="promoDescPct" class="form-control" placeholder="0" min="0" max="100" step="0.01">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descuento $</label>
                    <input type="number" id="promoDescMonto" class="form-control" placeholder="0.00" min="0" step="0.01">
                </div>
                <div class="alert alert-info mb-3" style="font-size: 0.85rem; padding: 10px 12px;">
                    💡 <strong>Ingresa SOLO descuento por % O por $</strong><br>No puedes usar ambos al mismo tiempo.
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de inicio</label>
                    <input type="date" id="promoFechaIni" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha de fin</label>
                    <input type="date" id="promoFechaFin" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">🎫 Elegibilidad por membresía</label>
                    <div class="todos-check-wrap">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" id="promoAplicaTodos" onchange="toggleTodosCheck()">
                            <span>Aplica a <strong>todos</strong> (sin importar membresía)</span>
                        </label>
                    </div>
                    <div id="membresiaChecks" class="membresia-checks"></div>
                    <div class="mt-2" style="font-size:.8rem;color:var(--sam-text-secondary);">
                        Si no marcas "todos", solo los tipos seleccionados verán el descuento.
                    </div>
                </div>
                <button class="btn btn-primary w-100 fw-bold mt-2" onclick="crearPromo()">✅ Registrar Promoción</button>
            </div>
        </div>
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column;">
            <div class="d-flex justify-content-between align-items-center mb-2" style="gap: 12px; flex-shrink: 0;">
                <h3 class="section-title mb-0" style="margin: 0; padding-bottom: 8px; border-bottom: 3px solid #FFC220; flex: 1;">📋 Promociones Registradas</h3>
                <button class="btn btn-outline-primary btn-sm" style="white-space: nowrap; flex-shrink: 0;" onclick="loadPromos()">↺ Actualizar</button>
            </div>
            <div class="sam-table table-responsive" style="flex: 1; overflow-y: auto; margin: 0;">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Producto</th><th>Promoción</th>
                            <th class="text-end">Desc %</th><th class="text-end">Desc $</th>
                            <th>Vigencia</th><th>Membresías</th><th>Estado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="promoBody">
                        <tr><td colspan="9" class="text-center py-4 text-muted">Cargando…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 4 · COMPRAS
═══════════════════════════════════════════════ -->
<div class="tab-pane fade" id="panel-compras" role="tabpanel">
    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div style="flex: 1; min-width: 0;">
            <div class="sam-form-card">
                <div class="section-title">Registrar Recepción de Mercancía</div>
                <div class="mb-3">
                    <label class="form-label">Proveedor</label>
                    <select id="compraProveedor" class="form-select"><option value="">Seleccionar proveedor…</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Zona de destino</label>
                    <select id="compraZona" class="form-select"><option value="">Seleccionar zona…</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">¿Es reserva?</label>
                    <select id="compraEsReserva" class="form-select">
                        <option value="0">No (Piso de venta)</option>
                        <option value="1">Sí (Bodega / Reserva)</option>
                    </select>
                </div>
                <hr class="my-2">
                <div class="section-title" style="margin-bottom: 12px; font-size: 0.95rem;">Agregar Productos</div>
                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select id="compraProductoSel" class="form-select"><option value="">Seleccionar producto…</option></select>
                </div>
                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                    <div style="flex: 1;">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="compraCantidad" class="form-control" placeholder="1" min="1">
                    </div>
                    <div style="flex: 1;">
                        <label class="form-label">Precio costo</label>
                        <input type="number" id="compraPrecio" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>
                <button class="btn btn-success w-100 mb-3" onclick="agregarItemCompra()">➕ Agregar a lista</button>
                <div class="section-title" style="margin-bottom: 12px;">Items Agregados</div>
                <div style="margin-bottom: 12px; border-radius: 8px; border: 1px solid var(--sam-border); overflow: hidden;">
                    <table class="table table-sm mb-0 compra-items-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width: 90px;" class="text-center">Cantidad</th>
                                <th style="width: 110px;" class="text-center">Precio costo</th>
                                <th style="width: 90px;" class="text-end">Total</th>
                                <th style="width: 38px;"></th>
                            </tr>
                        </thead>
                        <tbody id="compraItemsBody">
                            <tr id="compraEmptyRow"><td colspan="5" class="text-center text-muted py-3">Sin productos agregados</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mb-3 p-3 bg-light border rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-dark" id="compraTotalUnidades">Total: 0 unidades</span>
                        <span class="fw-bold text-primary" id="compraTotalCosto" style="font-size:1rem;">$0.00</span>
                    </div>
                </div>
                <button class="btn btn-primary w-100 fw-bold" onclick="procesarCompra()">✅ Registrar Compra</button>
            </div>
        </div>
        <div style="flex: 0 0 35%; min-width: 0;">
            <h3 class="section-title mb-2" style="margin-top: 0;">📋 Recepciones Recientes</h3>
            <div class="sam-table table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr><th>Fecha</th><th>Producto</th><th class="text-end">Cant.</th><th>Proveedor</th></tr>
                    </thead>
                    <tbody id="histCompraBody">
                        <tr><td colspan="4" class="text-center text-muted py-3">Cargando…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 5 · PUNTO DE VENTA
═══════════════════════════════════════════════ -->
<div class="tab-pane fade" id="panel-ventas" role="tabpanel">
    <div style="display: flex; gap: 16px; height: calc(100vh - 180px);">
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column;">
            <div style="display: grid; grid-template-columns: 0.6fr 1fr; gap: 12px; margin-bottom: 12px; flex-shrink: 0;">
                <div>
                    <label class="form-label d-block mb-2">📱 Canal de venta</label>
                    <select id="posCanal" class="form-select">
                        <option value="CAJA">🏦 Caja</option>
                        <option value="SELF">🤖 Self-checkout</option>
                        <option value="SCAN_GO">📱 Scan &amp; Go</option>
                    </select>
                </div>
                <div style="position:relative;">
                    <label class="form-label d-block mb-2">🔍 Buscar producto para agregar</label>
                    <input type="text" id="posSearch" class="form-control" placeholder="Nombre, SKU o marca…" oninput="buscarProductoPos()">
                    <div id="searchResults"></div>
                </div>
            </div>
            <div class="sam-table table-responsive" style="flex: 1; overflow-y:auto; margin: 0; border: 1px solid var(--sam-border);">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th><th class="text-end">Precio U.</th>
                            <th class="text-center" style="width:120px">Cantidad</th>
                            <th class="text-end">Desc.</th><th class="text-end">Subtotal</th><th style="width: 40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="posCartBody">
                        <tr><td colspan="6" class="text-center text-muted py-4">Busca un producto para comenzar la venta</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="flex: 0 0 34%; min-width: 0;">
            <div class="pos-panel">
                <div class="pos-panel-header">Resumen de Venta</div>
                <div class="pos-panel-body">
                    <div class="socio-block">
                        <div class="socio-label-title">👤 Asociar Socio (opcional)</div>
                        <div id="socioInputWrap" style="position:relative;">
                            <input type="text" id="posSearchSocio" class="form-control form-control-sm"
                                   placeholder="Buscar por nombre o número de socio…" oninput="buscarSocio()">
                            <div id="socioResults"></div>
                        </div>
                        <div id="socioSeleccionado" class="mt-2 d-none">
                            <div class="socio-badge-active">
                                <span>👤</span>
                                <span id="socioLabel"></span>
                                <span class="memb-pill" id="socioMembPill"></span>
                                <button class="quitar-btn" onclick="quitarSocio()" title="Quitar socio">✕</button>
                            </div>
                            <div style="font-size:.75rem;color:rgba(255,255,255,.7);margin-top:6px;padding-left:4px;" id="socioMembInfo"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted fw-500">Subtotal:</span>
                        <span class="fw-bold" id="posSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted fw-500">Descuentos:</span>
                        <span class="text-danger fw-bold" id="posDescuentos">-$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="fw-bold" style="font-size: 1.1rem;">TOTAL A PAGAR:</span>
                        <span class="pos-total" id="posTotal">$0.00</span>
                    </div>
                    <div class="section-title" style="margin-top: 16px; margin-bottom: 12px;">💳 Método de Pago</div>
                    <div id="pagosContainer" style="max-height: 160px; overflow-y: auto;">
                        <div class="pago-row d-flex gap-2 mb-2" data-idx="0">
                            <select class="form-select form-select-sm pago-metodo" onchange="actualizarResumenPago()">
                                <option value="EFECTIVO">💵 Efectivo</option>
                                <option value="TARJETA">💳 Tarjeta</option>
                                <option value="CASHI">📲 Cashi</option>
                                <option value="INBURSA">🏦 Inbursa</option>
                                <option value="VALES">🎟️ Vales</option>
                            </select>
                            <input type="number" class="form-control form-control-sm pago-monto"
                                   placeholder="Monto" min="0" step="0.01" oninput="actualizarResumenPago()">
                        </div>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="agregarPago()">+ Agregar método de pago</button>
                    <div id="pagoResumen" class="mt-2 d-none">
                        <span>Pagado: <strong id="pagoTotalIngresado">$0.00</strong></span>
                        <span id="pagoEstado"></span>
                    </div>
                    <div id="cambioInfo" class="mt-2 text-success fw-semibold d-none"></div>
                </div>
                <div class="pos-panel-footer">
                    <button class="btn btn-danger btn-sm w-100 mb-2" onclick="cancelarVenta()">🗑️ Cancelar venta</button>
                    <button class="btn btn-success w-100 fw-bold" style="padding: 12px;" onclick="procesarVenta()">✅ Cobrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2" style="gap: 12px;">
            <div class="section-title mb-0" style="flex-grow: 1;">📋 Ventas Recientes</div>
            <button class="btn btn-outline-primary btn-sm" style="white-space: nowrap;" onclick="loadHistVentas()">↺ Actualizar</button>
        </div>
        <div class="sam-table table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Fecha</th><th>Socio</th><th>Canal</th><th class="text-end">Artículos</th><th class="text-end">Total</th></tr>
                </thead>
                <tbody id="histVentasBody">
                    <tr><td colspan="6" class="text-center text-muted py-4">Cargando…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div><!-- /tab-content -->
</div><!-- /container-fluid -->

<div id="toastArea"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
window.addEventListener('error', (e) => { console.error('❌ ERROR:', e.error); toast('Error: ' + (e.error?.message || 'Desconocido'), 'error'); });
window.addEventListener('unhandledrejection', (e) => { console.error('❌ PROMISE:', e.reason); toast('Error: ' + (e.reason?.message || 'Desconocido'), 'error'); });
</script>

<script>
// ═══════════════════════════════════════════
// UTILS
// ═══════════════════════════════════════════
function toast(msg, type='success') {
    const d = document.getElementById('toastArea');
    const t = document.createElement('div');
    t.className = `sam-toast ${type}`;
    t.textContent = msg;
    d.appendChild(t);
    setTimeout(() => t.remove(), 4500);
}
function fmt(n) {
    return '$' + parseFloat(n||0).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2});
}
async function api(url, data=null) {
    try {
        const opts = { method: data ? 'POST' : 'GET', headers: {} };
        if (data) { opts.body = new URLSearchParams(data); opts.headers['Content-Type'] = 'application/x-www-form-urlencoded'; }
        const r = await fetch(url, opts);
        return await r.json();
    } catch (err) {
        console.error('❌ API Error:', err.message);
        return { success: false, error: err.message };
    }
}

// ═══════════════════════════════════════════
// TAB 1 · INVENTARIO
// ═══════════════════════════════════════════
async function loadInventario() {
    const q = document.getElementById('invSearch').value;
    const body = document.getElementById('invBody');
    body.innerHTML = '<tr><td colspan="9" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api('inventario.php?action=list&q=' + encodeURIComponent(q));
    if (!res.success) { toast(res.error,'error'); return; }
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="9" class="text-center text-muted py-4">Sin resultados</td></tr>';
    res.data.forEach(r => {
        const tipoColor = {BULK:'secondary',PERECEDERO:'warning',CONGELADO:'info',ROPA:'light',ELECTRONICA:'dark',SERVICIO:'success'}[r.tipo]||'secondary';
        const promo = r.promo_nombre ? `<span class="badge badge-promo">${r.promo_nombre}${r.descuento_pct>0?' -'+r.descuento_pct+'%':''}</span>` : '<span class="text-muted small">—</span>';
        const mm = r.es_members_mark=='1'?'<span class="badge badge-members ms-1">MM</span>':'';
        const stockClass = r.stock_total==0?'text-danger fw-bold':'';
        body.innerHTML += `<tr>
            <td class="text-muted small">${r.sku}</td>
            <td><strong>${r.nombre}</strong>${mm}</td>
            <td>${r.marca||'—'}</td>
            <td><span class="badge text-bg-${tipoColor} badge-tipo">${r.tipo}</span></td>
            <td class="small">${r.categoria||'—'}</td>
            <td class="text-end fw-semibold">${fmt(r.precio_actual)}</td>
            <td class="text-end ${stockClass}">${parseFloat(r.stock_piso).toFixed(0)}</td>
            <td class="text-end">${parseFloat(r.stock_reserva).toFixed(0)}</td>
            <td>${promo}</td>
        </tr>`;
    });
}
async function loadInvStats() {
    const res = await api('inventario.php?action=stats');
    if (!res.success) return;
    document.getElementById('st-total').textContent = res.data.total_productos;
    document.getElementById('st-stock').textContent = res.data.con_stock;
    document.getElementById('st-sin').textContent   = res.data.sin_stock;
    document.getElementById('st-promo').textContent = res.data.con_promo;
}
let invTimer;
document.getElementById('invSearch').addEventListener('input', () => { clearTimeout(invTimer); invTimer = setTimeout(loadInventario, 350); });

// ═══════════════════════════════════════════
// TAB 2 · PROMOCIONES
// ═══════════════════════════════════════════
let tiposMembresia = [];

async function loadTiposMembresia() {
    const res = await api('promociones.php?action=tipos_membresia');
    if (!res.success) return;
    tiposMembresia = res.data;
    renderMembresiaChecks();
}

function renderMembresiaChecks() {
    const container = document.getElementById('membresiaChecks');
    container.innerHTML = tiposMembresia.map(tm => {
        const cls = tm.nombre;
        const colorMap = { CLASICA: '#6B7280', BENEFITS: '#003DA5', PLUS: '#7C3AED' };
        const color = colorMap[cls] || '#6B7280';
        return `<label class="memb-check-label" id="lbl-memb-${tm.id}" style="border-color:${color}20;" onclick="toggleMembCheck(${tm.id}, '${cls}', '${color}')">
                    <input type="checkbox" id="chk-memb-${tm.id}" value="${tm.id}">
                    <span>${cls}</span>
                </label>`;
    }).join('');
}

function toggleMembCheck(id, nombre, color) {
    const chk = document.getElementById(`chk-memb-${id}`);
    const lbl = document.getElementById(`lbl-memb-${id}`);
    chk.checked = !chk.checked;
    if (chk.checked) { lbl.classList.add(`checked-${nombre}`); lbl.style.borderColor = color; }
    else { lbl.classList.remove(`checked-${nombre}`); lbl.style.borderColor = `${color}20`; }
}

function toggleTodosCheck() {
    const todosCheck = document.getElementById('promoAplicaTodos').checked;
    const container = document.getElementById('membresiaChecks');
    container.style.opacity = todosCheck ? '0.4' : '1';
    container.style.pointerEvents = todosCheck ? 'none' : '';
    if (todosCheck) {
        tiposMembresia.forEach(tm => {
            const chk = document.getElementById(`chk-memb-${tm.id}`);
            const lbl = document.getElementById(`lbl-memb-${tm.id}`);
            if (chk) chk.checked = false;
            if (lbl) lbl.className = 'memb-check-label';
        });
    }
}

async function loadProductosPromo() {
    const res = await api('promociones.php?action=productos_list');
    const sel = document.getElementById('promoProducto');
    sel.innerHTML = '<option value="">Seleccionar producto…</option>';
    if (res.success) res.data.forEach(p => sel.innerHTML += `<option value="${p.id}">[${p.sku}] ${p.nombre} — ${p.marca}</option>`);
}

async function loadPromos() {
    const body = document.getElementById('promoBody');
    body.innerHTML = '<tr><td colspan="9" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api('promociones.php?action=list_promos');
    if (!res.success) { toast(res.error,'error'); return; }
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="9" class="text-center text-muted py-4">Sin promociones</td></tr>';
    res.data.forEach(r => {
        const badge = r.activo=='1' ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-secondary">Inactiva</span>';
        let membTag = '';
        if (r.aplica_a_todos == '1') { membTag = '<span class="badge bg-dark">Todos</span>'; }
        else if (r.membresias_aplicables) {
            membTag = r.membresias_aplicables.split(', ').map(m => {
                const style = m === 'PLUS' ? 'background:#7C3AED;' : (m === 'BENEFITS' ? 'background:#003DA5;' : 'background:#6B7280;');
                return `<span class="badge" style="font-size:.7rem;${style}color:#fff;">${m}</span>`;
            }).join(' ');
        } else { membTag = '<span class="text-muted small">—</span>'; }
        body.innerHTML += `<tr>
            <td class="text-muted small">${r.id}</td>
            <td><strong>${r.producto_nombre}</strong><br><span class="text-muted small">${r.sku}</span></td>
            <td>${r.nombre_promo}</td>
            <td class="text-end">${r.descuento_pct>0?r.descuento_pct+'%':'—'}</td>
            <td class="text-end">${r.descuento_monto>0?fmt(r.descuento_monto):'—'}</td>
            <td class="small">${r.fecha_inicio||'—'}<br>al ${r.fecha_fin||'—'}</td>
            <td>${membTag}</td>
            <td>${badge}</td>
            <td>
                <button class="btn btn-outline-warning btn-sm me-1" onclick="togglePromo(${r.id})">${r.activo=='1'?'⏸ Desactivar':'▶ Activar'}</button>
                <button class="btn btn-outline-danger btn-sm" onclick="deletePromo(${r.id})">🗑️</button>
            </td>
        </tr>`;
    });
}

async function crearPromo() {
    const prod_id = document.getElementById('promoProducto').value;
    const nombre  = document.getElementById('promoNombre').value.trim();
    const pct     = parseFloat(document.getElementById('promoDescPct').value) || 0;
    const monto   = parseFloat(document.getElementById('promoDescMonto').value) || 0;
    const fi      = document.getElementById('promoFechaIni').value;
    const ff      = document.getElementById('promoFechaFin').value;
    const aplicaTodos = document.getElementById('promoAplicaTodos').checked ? '1' : '0';

    if (!prod_id || !nombre) { toast('Selecciona producto y nombre de la promoción','error'); return; }
    if ((pct > 0 && monto > 0) || (pct === 0 && monto === 0)) { toast('⚠️ Ingresa SOLO descuento por % O por $, no ambos ni ninguno','error'); return; }

    const tiposSeleccionados = tiposMembresia
        .filter(tm => document.getElementById(`chk-memb-${tm.id}`)?.checked)
        .map(tm => tm.id);

    if (aplicaTodos === '0' && tiposSeleccionados.length === 0) {
        toast('Selecciona al menos un tipo de membresía o marca "Aplica a todos"','error'); return;
    }

    const res = await api('promociones.php', {
        action: 'create', producto_id: prod_id, nombre_promo: nombre,
        descuento_pct: pct, descuento_monto: monto, fecha_inicio: fi, fecha_fin: ff,
        aplica_a_todos: aplicaTodos, tipos_membresia_ids: JSON.stringify(tiposSeleccionados)
    });
    if (res.success) {
        toast(res.message);
        ['promoNombre','promoDescPct','promoDescMonto','promoFechaIni','promoFechaFin'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('promoAplicaTodos').checked = false;
        toggleTodosCheck();
        tiposMembresia.forEach(tm => {
            const chk = document.getElementById(`chk-memb-${tm.id}`);
            const lbl = document.getElementById(`lbl-memb-${tm.id}`);
            if (chk) chk.checked = false;
            if (lbl) lbl.className = 'memb-check-label';
        });
        loadPromos(); loadInvStats();
    } else toast(res.error,'error');
}

async function togglePromo(id) {
    const res = await api('promociones.php', { action:'toggle', id });
    if (res.success) { toast(res.message); loadPromos(); } else toast(res.error,'error');
}
async function deletePromo(id) {
    if (!confirm('¿Eliminar esta promoción permanentemente?')) return;
    const res = await api('promociones.php', { action:'delete', id });
    if (res.success) { toast(res.message); loadPromos(); loadInvStats(); } else toast(res.error,'error');
}

// ═══════════════════════════════════════════
// TAB 3 · COMPRAS
// ═══════════════════════════════════════════
let compraItems = [];

async function loadCompraData() {
    const [prov, prod, zonas] = await Promise.all([
        api('compras.php?action=list_proveedores'),
        api('compras.php?action=list_productos'),
        api('compras.php?action=list_zonas')
    ]);
    if (prov.success) {
        const sel = document.getElementById('compraProveedor');
        sel.innerHTML = '<option value="">Seleccionar proveedor…</option>';
        prov.data.forEach(p => sel.innerHTML += `<option value="${p.id}">${p.nombre}</option>`);
    }
    if (prod.success) {
        const sel = document.getElementById('compraProductoSel');
        sel.innerHTML = '<option value="">Seleccionar producto…</option>';
        prod.data.forEach(p => sel.innerHTML += `<option value="${p.id}" data-nombre="${p.nombre}" data-sku="${p.sku}">[${p.sku}] ${p.nombre}</option>`);
    }
    if (zonas.success) {
        const sel = document.getElementById('compraZona');
        sel.innerHTML = '<option value="">Seleccionar zona…</option>';
        zonas.data.forEach(z => sel.innerHTML += `<option value="${z.id}">${z.nombre} (${z.tipo})</option>`);
    }
    loadHistCompra();
}

function agregarItemCompra() {
    const sel = document.getElementById('compraProductoSel');
    const prod_id = sel.value;
    const nombre  = sel.options[sel.selectedIndex]?.dataset?.nombre || '';
    const sku     = sel.options[sel.selectedIndex]?.dataset?.sku || '';
    const cant    = parseFloat(document.getElementById('compraCantidad').value) || 0;
    const precio  = parseFloat(document.getElementById('compraPrecio').value) || 0;
    if (!prod_id || cant <= 0) { toast('Selecciona producto y cantidad válida','error'); return; }
    const existing = compraItems.find(i => i.producto_id === prod_id);
    if (existing) { existing.cantidad += cant; existing.precio_costo = precio || existing.precio_costo; toast(`Cantidad actualizada para ${nombre}`); }
    else { compraItems.push({ producto_id: prod_id, nombre, sku, cantidad: cant, precio_costo: precio }); }
    renderCompraItems();
    document.getElementById('compraCantidad').value = '';
    document.getElementById('compraPrecio').value = '';
}

function renderCompraItems() {
    const body = document.getElementById('compraItemsBody');
    if (!compraItems.length) {
        body.innerHTML = '<tr id="compraEmptyRow"><td colspan="5" class="text-center text-muted py-3">Sin productos agregados</td></tr>';
        document.getElementById('compraTotalUnidades').textContent = 'Total: 0 unidades';
        document.getElementById('compraTotalCosto').textContent = '$0.00';
        return;
    }
    body.innerHTML = compraItems.map((it, i) => {
        const sub = (it.cantidad || 0) * (it.precio_costo || 0);
        return `<tr class="compra-item-row">
            <td><strong style="font-size:.85rem">[${it.sku}]</strong><span style="font-size:.85rem"> ${it.nombre}</span></td>
            <td class="text-center"><input type="number" value="${it.cantidad}" min="1" step="1" style="width:75px;" onchange="updateCompraItem(${i},'cantidad',this.value)" oninput="updateCompraItem(${i},'cantidad',this.value)"></td>
            <td class="text-center"><input type="number" value="${it.precio_costo || ''}" min="0" step="0.01" placeholder="0.00" style="width:90px;" onchange="updateCompraItem(${i},'precio_costo',this.value)" oninput="updateCompraItem(${i},'precio_costo',this.value)"></td>
            <td class="text-end subtotal-cell" id="sub-compra-${i}">${sub > 0 ? fmt(sub) : '—'}</td>
            <td class="text-center"><button class="btn btn-sm btn-outline-danger" style="padding:3px 8px;" onclick="removeCompraItem(${i})">✕</button></td>
        </tr>`;
    }).join('');
    actualizarTotalesCompra();
}

function updateCompraItem(idx, field, value) {
    compraItems[idx][field] = parseFloat(value) || 0;
    const sub = (compraItems[idx].cantidad || 0) * (compraItems[idx].precio_costo || 0);
    const cell = document.getElementById(`sub-compra-${idx}`);
    if (cell) cell.textContent = sub > 0 ? fmt(sub) : '—';
    actualizarTotalesCompra();
}

function actualizarTotalesCompra() {
    const totalUnidades = compraItems.reduce((s, i) => s + (i.cantidad || 0), 0);
    const totalCosto    = compraItems.reduce((s, i) => s + ((i.cantidad || 0) * (i.precio_costo || 0)), 0);
    document.getElementById('compraTotalUnidades').textContent = `Total: ${totalUnidades} unidades`;
    document.getElementById('compraTotalCosto').textContent    = fmt(totalCosto);
}

function removeCompraItem(idx) { compraItems.splice(idx, 1); renderCompraItems(); }

async function procesarCompra() {
    const prov_id = document.getElementById('compraProveedor').value;
    const zona_id = document.getElementById('compraZona').value;
    const es_res  = document.getElementById('compraEsReserva').value;
    if (!prov_id || !zona_id) { toast('Selecciona proveedor y zona','error'); return; }
    if (!compraItems.length)  { toast('Agrega al menos un producto','error'); return; }
    const res = await api('compras.php', { action: 'registrar', proveedor_id: prov_id, zona_id, es_reserva: es_res, items: JSON.stringify(compraItems) });
    if (res.success) { toast(res.message); compraItems = []; renderCompraItems(); loadHistCompra(); loadInventario(); loadInvStats(); }
    else toast(res.error, 'error');
}

async function loadHistCompra() {
    const body = document.getElementById('histCompraBody');
    const res = await api('compras.php?action=historial');
    if (!res.success) { body.innerHTML = '<tr><td colspan="4" class="text-danger">Error</td></tr>'; return; }
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="4" class="text-center text-muted py-3">Sin registros</td></tr>';
    res.data.forEach(r => {
        body.innerHTML += `<tr>
            <td class="small">${r.fecha}</td><td><strong>${r.producto}</strong></td>
            <td class="text-end">${r.cantidad}</td><td class="small">${r.proveedor||'—'}</td>
        </tr>`;
    });
}

// ═══════════════════════════════════════════════════════
// TAB 4 · PUNTO DE VENTA
// ═══════════════════════════════════════════════════════
let cart = [];
let selectedSocio = null;

function calcularMejorDescuento(producto) {
    const precio  = parseFloat(producto.precio);
    const promos  = producto.promociones || [];
    if (!promos.length) return { descuento: 0, promo_id: null, tipo_descuento: 'NINGUNO', promo_nombre: null, aplico: false };

    const tipoMembId = selectedSocio?.tipo_membresia_id ?? null;
    const elegibles = promos.filter(pr => {
        if (pr.aplica_a_todos == '1') return true;
        if (!tipoMembId) return false;
        const ids = (pr.tipos_ids || '').split(',').map(x => parseInt(x)).filter(Boolean);
        return ids.includes(parseInt(tipoMembId));
    });

    if (!elegibles.length) {
        const todasPromos = promos.map(p => p.promo_nombre).join(', ');
        return { descuento: 0, promo_id: null, tipo_descuento: 'NINGUNO', promo_nombre: null, aplico: false,
                 promo_bloqueada: todasPromos, memb_requerida: promos.map(p => p.tipos_nombres || 'N/A').join('; ') };
    }

    let mejor = null, mejorValor = -1;
    elegibles.forEach(pr => {
        const pct   = parseFloat(pr.descuento_pct || 0);
        const monto = parseFloat(pr.descuento_monto || 0);
        const valor = pct > 0 ? (precio * pct / 100) : monto;
        if (valor > mejorValor) { mejorValor = valor; mejor = pr; }
    });

    return {
        descuento: parseFloat(mejorValor.toFixed(2)),
        promo_id: mejor.promo_id,
        tipo_descuento: 'PROMOCION_MEMBRESIA',
        promo_nombre: mejor.promo_nombre,
        aplico: true
    };
}

function recalcularDescuentosCarrito() {
    cart.forEach(it => {
        const resultado = calcularMejorDescuento(it._producto_raw);
        it.descuento = resultado.descuento;
        it.promo_id = resultado.promo_id;
        it.tipo_descuento = resultado.tipo_descuento;
        it.promo_nombre = resultado.promo_nombre;
        it._desc_info = resultado;
    });
    renderCart();
}

function calcularTotalCarrito() {
    return cart.reduce((sum, it) => sum + (it.precio * it.cantidad) - (it.descuento * it.cantidad), 0);
}
function calcularTotalPagado() {
    return [...document.querySelectorAll('.pago-monto')].reduce((sum, el) => sum + (parseFloat(el.value) || 0), 0);
}

function actualizarResumenPago() {
    const total  = calcularTotalCarrito();
    const pagado = calcularTotalPagado();
    const resumen = document.getElementById('pagoResumen');
    const cambioInfo = document.getElementById('cambioInfo');
    const estadoEl = document.getElementById('pagoEstado');
    const ingresadoEl = document.getElementById('pagoTotalIngresado');

    if (total <= 0) { resumen.classList.add('d-none'); cambioInfo.classList.add('d-none'); return; }
    resumen.classList.remove('d-none');
    ingresadoEl.textContent = fmt(pagado);

    const diff = pagado - total;
    if (diff < -0.009) {
        estadoEl.innerHTML = `<span class="pago-faltante">Falta: ${fmt(Math.abs(diff))}</span>`;
        cambioInfo.classList.add('d-none');
    } else if (diff > 0.009) {
        estadoEl.innerHTML = `<span class="pago-ok">✓ Cubierto</span>`;
        cambioInfo.textContent = `Cambio a devolver: ${fmt(diff)}`;
        cambioInfo.classList.remove('d-none');
    } else {
        estadoEl.innerHTML = `<span class="pago-ok">✓ Exacto</span>`;
        cambioInfo.classList.add('d-none');
    }
}

let searchProductoTimer;
async function buscarProductoPos() {
    const q = document.getElementById('posSearch').value.trim();
    const container = document.getElementById('searchResults');
    if (q.length < 2) { container.innerHTML = ''; return; }
    clearTimeout(searchProductoTimer);
    searchProductoTimer = setTimeout(async () => {
        const res = await api('ventas.php?action=buscar_producto&q=' + encodeURIComponent(q));
        container.innerHTML = '';
        if (!res.success || !res.data.length) {
            container.innerHTML = '<div class="res-item text-muted">Sin resultados</div>'; return;
        }
        res.data.forEach(p => {
            const d = document.createElement('div');
            d.className = 'res-item';
            const promos = p.promociones || [];
            let promoTag = '';
            if (promos.length) {
                const resultado = calcularMejorDescuento(p);
                if (resultado.aplico) {
                    promoTag = ` <span class="badge badge-promo">${resultado.promo_nombre} −${resultado.descuento > 0 ? fmt(resultado.descuento) : ''}</span>`;
                } else if (resultado.promo_bloqueada) {
                    promoTag = ` <span class="res-memb-tag" title="Requiere: ${resultado.memb_requerida}">🔒 ${resultado.promo_bloqueada}</span>`;
                }
            }
            d.innerHTML = `<strong>${p.nombre}</strong>${promoTag}<br>
                           <span class="res-sku">${p.sku} · ${p.tipo} · Stock: ${parseFloat(p.stock).toFixed(0)}</span>
                           <span class="float-end fw-bold">${fmt(p.precio)}</span>`;
            d.onclick = () => { addToCart(p); container.innerHTML = ''; document.getElementById('posSearch').value = ''; };
            container.appendChild(d);
        });
    }, 250);
}

function addToCart(p) {
    const existing = cart.find(i => i.producto_id == p.id);
    if (existing) { existing.cantidad++; }
    else {
        const resultado = calcularMejorDescuento(p);
        cart.push({
            producto_id: p.id, nombre: p.nombre, sku: p.sku, tipo: p.tipo,
            precio: parseFloat(p.precio), cantidad: 1,
            descuento: resultado.descuento, promo_id: resultado.promo_id,
            tipo_descuento: resultado.tipo_descuento, promo_nombre: resultado.promo_nombre,
            _desc_info: resultado, _producto_raw: p, stock_max: parseFloat(p.stock)
        });
        if (!resultado.aplico && resultado.promo_bloqueada) {
            toast(`⚠️ "${p.nombre}" tiene promo para ${resultado.memb_requerida} — precio regular aplicado`, 'warn');
        }
    }
    renderCart();
}

function renderCart() {
    const body = document.getElementById('posCartBody');
    if (!cart.length) {
        body.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Busca un producto para comenzar la venta</td></tr>';
        document.getElementById('posSubtotal').textContent   = '$0.00';
        document.getElementById('posDescuentos').textContent = '-$0.00';
        document.getElementById('posTotal').textContent      = '$0.00';
        actualizarResumenPago();
        return;
    }
    let subtotal = 0, totalDesc = 0;
    body.innerHTML = cart.map((it, idx) => {
        const sub  = it.precio * it.cantidad;
        const desc = it.descuento * it.cantidad;
        subtotal += sub; totalDesc += desc;
        let descTag = '—';
        if (desc > 0) { descTag = `<span class="badge-desc-ok">−${fmt(desc)} ✓</span>`; }
        else if (it._desc_info?.promo_bloqueada) { descTag = `<span class="badge-desc-no" title="Requiere: ${it._desc_info.memb_requerida}">🔒 Sin desc.</span>`; }
        const promoTag = it.promo_nombre ? `<span class="badge badge-promo ms-1" style="font-size:.7rem;">${it.promo_nombre}</span>` : '';
        return `<tr>
            <td><strong>${it.nombre}</strong>${promoTag}<br><span class="text-muted small">${it.sku}</span></td>
            <td class="text-end">${fmt(it.precio)}</td>
            <td class="text-center">
                <div class="input-group input-group-sm" style="width:100px">
                    <button class="btn btn-outline-secondary" onclick="changeQty(${idx},-1)">−</button>
                    <input type="number" class="form-control text-center" value="${it.cantidad}" min="1" onchange="setQty(${idx},this.value)" style="width:45px">
                    <button class="btn btn-outline-secondary" onclick="changeQty(${idx},1)">+</button>
                </div>
            </td>
            <td class="text-end">${descTag}</td>
            <td class="text-end fw-semibold">${fmt(sub - desc)}</td>
            <td><button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${idx})">✕</button></td>
        </tr>`;
    }).join('');
    const total = subtotal - totalDesc;
    document.getElementById('posSubtotal').textContent   = fmt(subtotal);
    document.getElementById('posDescuentos').textContent = '-' + fmt(totalDesc);
    document.getElementById('posTotal').textContent      = fmt(total);
    const firstMonto = document.querySelector('.pago-monto');
    if (firstMonto && !firstMonto.value) firstMonto.value = total.toFixed(2);
    actualizarResumenPago();
}

function changeQty(idx, delta) { cart[idx].cantidad = Math.max(1, cart[idx].cantidad + delta); renderCart(); }
function setQty(idx, val) { cart[idx].cantidad = Math.max(1, parseInt(val)||1); renderCart(); }
function removeFromCart(idx) { cart.splice(idx, 1); renderCart(); }

function cancelarVenta() {
    cart = []; selectedSocio = null; renderCart();
    document.getElementById('posSearchSocio').value = '';
    document.getElementById('socioSeleccionado').classList.add('d-none');
    document.getElementById('socioInputWrap').style.display = '';
    const rows = document.querySelectorAll('#pagosContainer .pago-row');
    rows.forEach((r, i) => { if (i > 0) r.remove(); });
    const firstMonto = document.querySelector('.pago-monto');
    if (firstMonto) firstMonto.value = '';
    actualizarResumenPago();
}

function agregarPago() {
    const c = document.getElementById('pagosContainer');
    const d = document.createElement('div');
    d.className = 'pago-row d-flex gap-2 mb-2';
    d.innerHTML = `<select class="form-select form-select-sm pago-metodo" onchange="actualizarResumenPago()">
        <option value="EFECTIVO">💵 Efectivo</option>
        <option value="TARJETA">💳 Tarjeta</option>
        <option value="CASHI">📲 Cashi</option>
        <option value="INBURSA">🏦 Inbursa</option>
        <option value="VALES">🎟️ Vales</option>
    </select>
    <input type="number" class="form-control form-control-sm pago-monto" placeholder="Monto" min="0" step="0.01" oninput="actualizarResumenPago()">
    <button class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove(); actualizarResumenPago();">✕</button>`;
    c.appendChild(d);
}

let socioTimer;
async function buscarSocio() {
    const q    = document.getElementById('posSearchSocio').value.trim();
    const cont = document.getElementById('socioResults');
    if (q.length < 2) { cont.style.display = 'none'; return; }
    clearTimeout(socioTimer);
    socioTimer = setTimeout(async () => {
        const res = await api('ventas.php?action=buscar_socio&q=' + encodeURIComponent(q));
        cont.innerHTML = '';
        if (!res.success || !res.data.length) { cont.style.display = 'none'; return; }
        res.data.forEach(s => {
            const d = document.createElement('div');
            d.style.cssText = 'padding:9px 14px;cursor:pointer;border-bottom:1px solid #f0f0f0;font-size:.85rem';
            const membColors = { CLASICA:'#6B7280', BENEFITS:'#003DA5', PLUS:'#7C3AED' };
            const color = membColors[s.membresia] || '#333';
            d.innerHTML = `<strong>${s.nombre}</strong> — <span class="text-muted">#${s.numero_socio}</span><br>
                           <span class="badge" style="background:${color};color:#fff;">${s.membresia}</span>
                           <span class="text-muted" style="font-size:.8rem;"> Cashback: ${fmt(s.saldo_cashback)}</span>`;
            d.onmouseover = () => d.style.background = '#f0f4ff';
            d.onmouseout  = () => d.style.background = '';
            d.onclick = () => {
                selectedSocio = s;
                document.getElementById('socioLabel').textContent = `${s.nombre} · #${s.numero_socio}`;
                const pill = document.getElementById('socioMembPill');
                pill.textContent = s.membresia;
                pill.className = `memb-pill ${s.membresia}`;
                document.getElementById('socioMembInfo').textContent = `Cashback acumulado: ${fmt(s.saldo_cashback)} · Vence: ${s.fecha_fin || '—'}`;
                document.getElementById('socioSeleccionado').classList.remove('d-none');
                document.getElementById('socioInputWrap').style.display = 'none';
                cont.style.display = 'none';
                recalcularDescuentosCarrito();
            };
            cont.appendChild(d);
        });
        cont.style.display = 'block';
    }, 300);
}

function quitarSocio() {
    selectedSocio = null;
    document.getElementById('socioSeleccionado').classList.add('d-none');
    document.getElementById('socioInputWrap').style.display = '';
    document.getElementById('posSearchSocio').value = '';
    document.getElementById('socioLabel').textContent = '';
    document.getElementById('socioMembPill').textContent = '';
    document.getElementById('socioMembPill').className = 'memb-pill';
    document.getElementById('socioMembInfo').textContent = '';
    recalcularDescuentosCarrito();
}

async function procesarVenta() {
    if (!cart.length) { toast('El carrito está vacío','error'); return; }
    const pagos = [...document.querySelectorAll('.pago-row')].map(r => ({
        metodo: r.querySelector('.pago-metodo').value,
        monto:  parseFloat(r.querySelector('.pago-monto').value) || 0
    })).filter(p => p.monto > 0);
    if (!pagos.length) { toast('Ingresa al menos un monto de pago','error'); return; }
    const totalCarrito = calcularTotalCarrito();
    const totalPagado  = pagos.reduce((s, p) => s + p.monto, 0);
    const diferencia   = totalPagado - totalCarrito;
    if (diferencia < -0.009) { toast(`Pago insuficiente. Faltan ${fmt(Math.abs(diferencia))} para cubrir el total de ${fmt(totalCarrito)}`, 'error'); return; }
    const res = await api('ventas.php', {
        action: 'procesar_venta',
        socio_membresia_id: selectedSocio?.socio_membresia_id || '',
        canal: document.getElementById('posCanal').value,
        items: JSON.stringify(cart.map(i => ({
            producto_id: i.producto_id, cantidad: i.cantidad, precio: i.precio,
            descuento: i.descuento * i.cantidad, promo_id: i.promo_id, tipo_descuento: i.tipo_descuento
        }))),
        pagos: JSON.stringify(pagos)
    });
    if (res.success) {
        const cambio = diferencia > 0.009 ? ` · Cambio: ${fmt(diferencia)}` : '';
        toast(res.message + ' · Total: ' + fmt(res.total) + cambio);
        cancelarVenta(); loadHistVentas(); loadInventario(); loadInvStats();
    } else toast(res.error, 'error');
}

async function loadHistVentas() {
    const body = document.getElementById('histVentasBody');
    const res = await api('ventas.php?action=historial');
    if (!res.success) { body.innerHTML = '<tr><td colspan="6" class="text-danger">Error</td></tr>'; return; }
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="6" class="text-center text-muted py-3">Sin ventas</td></tr>';
    res.data.forEach(r => {
        body.innerHTML += `<tr>
            <td class="text-muted small">#${r.id}</td>
            <td class="small">${r.fecha}</td>
            <td>${r.socio||'<span class="text-muted">—</span>'}</td>
            <td><span class="badge bg-secondary">${r.canal}</span></td>
            <td class="text-end">${r.num_items}</td>
            <td class="text-end fw-semibold">${fmt(r.total)}</td>
        </tr>`;
    });
}

// ═══════════════════════════════════════════
// TAB 2 · SOCIOS
// ═══════════════════════════════════════════
let sociosData = [];
let socioSeleccionado = null;

async function cargarTiposMembresiaForm() {
    const res = await api('socios.php?action=tipos_membresia');
    if (!res.success) return;
    const selectNuevo = document.getElementById('socioTipoMembresia');
    selectNuevo.innerHTML = '<option value="">Seleccionar tipo…</option>';
    res.data.forEach(t => {
        selectNuevo.innerHTML += `<option value="${t.id}">${t.nombre} (${t.cashback}% cashback)</option>`;
    });
}

async function loadSocios() {
    const q = document.getElementById('sociosBuscar').value;
    const body = document.getElementById('sociosBody');
    let url = 'socios.php?action=list_titulares';
    if (q) url = 'socios.php?action=buscar_socios&q=' + encodeURIComponent(q);
    body.innerHTML = '<tr><td colspan="8" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api(url);
    if (!res.success) { toast(res.error,'error'); return; }
    sociosData = res.data;
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="8" class="text-center text-muted py-4">Sin socios registrados</td></tr>';
    res.data.forEach(r => {
        const colorMembresia = {'CLASICA': '#6B7280', 'BENEFITS': '#003DA5', 'PLUS': '#7C3AED'}[r.tipo_membresia] || '#6B7280';
        body.innerHTML += `<tr style="cursor:pointer;" onclick="mostrarDetallesSocio(${r.socio_membresia_id})">
            <td class="text-muted small">#${r.socio_membresia_id}</td>
            <td class="fw-semibold">${r.numero_socio}</td>
            <td><strong>${r.nombre}</strong></td>
            <td><span class="badge" style="background:${colorMembresia};color:#fff;">${r.tipo_membresia}</span></td>
            <td class="text-center"><strong>${r.num_familiares || 0}</strong></td>
            <td class="small">${r.vencimiento}</td>
            <td class="text-end fw-semibold">${fmt(r.saldo_cashback)}</td>
            <td>
                <div class="acciones-socio" onclick="event.stopPropagation()">
                    <button class="btn btn-sm btn-outline-warning" title="Desactivar" onclick="desactivarMembresia(${r.socio_membresia_id})">⏸</button>
                    <button class="btn btn-sm btn-danger" title="Eliminar titular y sus familiares" onclick="eliminarTitular(${r.socio_membresia_id}, '${r.nombre.replace(/'/g,"\\'")}', ${r.num_familiares || 0})">🗑️</button>
                </div>
            </td>
        </tr>`;
    });
}

async function mostrarDetallesSocio(socio_membresia_id) {
    socioSeleccionado = socio_membresia_id;
    const res = await api('socios.php?action=get_socio_detalles&socio_membresia_id=' + socio_membresia_id);
    if (!res.success) { toast(res.error,'error'); return; }
    const titular = res.titular;
    const familiares = res.familiares || [];
    const colorMap = {'CLASICA':'#6B7280','BENEFITS':'#003DA5','PLUS':'#7C3AED'};

    let html = `
        <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <h5 class="mb-3">👤 ${titular.nombre}</h5>
            <div class="row g-3">
                <div class="col-6">
                    <div><strong>Número:</strong> ${titular.numero_socio}</div>
                    <div><strong>Correo:</strong> ${titular.correo || '—'}</div>
                    <div><strong>Teléfono:</strong> ${titular.telefono || '—'}</div>
                </div>
                <div class="col-6">
                    <div><strong>Membresía:</strong> <span class="badge" style="background:${colorMap[titular.tipo_membresia]||'#6B7280'};color:#fff;">${titular.tipo_membresia}</span></div>
                    <div><strong>Vencimiento:</strong> ${titular.vencimiento}</div>
                    <div><strong>Cashback:</strong> <span style="color: #10B981; font-weight: bold; font-size: 1.1rem;">${fmt(titular.saldo_cashback)}</span></div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-sm btn-primary" onclick="renovarMembresia(${titular.id})">🔄 Renovar Membresía</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarTitular(${titular.id}, '${titular.nombre.replace(/'/g,"\\'")}', ${familiares.length})">🗑️ Eliminar Titular</button>
            </div>
        </div>
    `;

    if (familiares.length > 0) {
        html += '<h6 class="mt-4 mb-2">👨‍👩‍👧 Familiares Vinculados</h6>';
        html += `<div class="sam-table table-responsive"><table class="table table-sm mb-0">
            <thead><tr><th>Nombre</th><th>Parentesco</th><th>Membresía</th><th class="text-center">Complem.</th><th class="text-end">Cashback</th><th>Acciones</th></tr></thead>
            <tbody>`;
        familiares.forEach(f => {
            html += `<tr>
                <td>${f.nombre}</td>
                <td><small>${f.parentesco}</small></td>
                <td><span class="badge" style="background:${colorMap[f.tipo_membresia]||'#6B7280'};color:#fff;">${f.tipo_membresia}</span></td>
                <td class="text-center">${f.es_complementaria == 1 ? '<span class="badge bg-success">Gratis</span>' : '<span class="text-muted">—</span>'}</td>
                <td class="text-end">${fmt(f.saldo_cashback)}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="eliminarFamiliar(${f.socio_membresia_id}, '${f.nombre.replace(/'/g,"\\'")}', ${socio_membresia_id})">🗑️ Eliminar</button>
                </td>
            </tr>`;
        });
        html += '</tbody></table></div>';
    } else {
        html += `<div class="alert alert-info mt-3 mb-0">
            📌 Este socio no tiene familiares vinculados aún.
            <button class="btn btn-sm btn-link p-0" onclick="mostrarFormFamiliar()">Vincular un familiar</button>
        </div>`;
    }

    document.getElementById('socioDetallesContent').innerHTML = html;
    const tab = new bootstrap.Tab(document.getElementById('tab-socios-detalles'));
    tab.show();
}

function mostrarFormNuevo() {
    document.getElementById('socioFormNuevo').style.display = 'block';
    document.getElementById('socioFormFamiliar').style.display = 'none';
    limpiarFormNuevo();
}

function mostrarFormFamiliar() {
    document.getElementById('socioFormNuevo').style.display = 'none';
    document.getElementById('socioFormFamiliar').style.display = 'block';
    cargarTitularesSelect();
    limpiarFormFamiliar();
}

function limpiarFormNuevo() {
    ['socioNombre','socioCorreo','socioTelefono','socioFechaFin'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('socioTipoMembresia').value = '';
}

function limpiarFormFamiliar() {
    document.getElementById('socioTitularSel').value = '';
    ['familiarNombre','familiarCorreo','familiarTelefono'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('familiarParentesco').value = 'HIJO';
    document.getElementById('familiarComplementaria').checked = false;
}

async function cargarTitularesSelect() {
    const res = await api('socios.php?action=list_titulares');
    if (!res.success) return;
    const select = document.getElementById('socioTitularSel');
    select.innerHTML = '<option value="">Seleccionar titular…</option>';
    res.data.forEach(t => {
        select.innerHTML += `<option value="${t.socio_membresia_id}">${t.nombre} (${t.numero_socio}) — ${t.tipo_membresia}</option>`;
    });
}

async function crearSocioTitular() {
    const nombre = document.getElementById('socioNombre').value;
    const correo = document.getElementById('socioCorreo').value;
    const telefono = document.getElementById('socioTelefono').value;
    const tipo_membresia_id = document.getElementById('socioTipoMembresia').value;
    const fecha_fin = document.getElementById('socioFechaFin').value;
    if (!nombre || !tipo_membresia_id || !fecha_fin) { toast('Por favor completa todos los campos requeridos', 'error'); return; }
    const res = await api('socios.php', { action: 'crear_socio_titular', nombre, correo, telefono, tipo_membresia_id, fecha_fin });
    if (res.success) { toast('✅ ' + res.message); limpiarFormNuevo(); loadSocios(); }
    else toast(res.error, 'error');
}

async function crearSocioFamiliar() {
    const cuenta_titular_id = document.getElementById('socioTitularSel').value;
    const nombre = document.getElementById('familiarNombre').value;
    const correo = document.getElementById('familiarCorreo').value;
    const telefono = document.getElementById('familiarTelefono').value;
    const parentesco = document.getElementById('familiarParentesco').value;
    const es_complementaria = document.getElementById('familiarComplementaria').checked ? 1 : 0;
    if (!cuenta_titular_id || !nombre || !parentesco) { toast('Por favor completa todos los campos requeridos', 'error'); return; }
    const res = await api('socios.php', { action: 'crear_socio_familiar', cuenta_titular_id, nombre, correo, telefono, parentesco, es_complementaria });
    if (res.success) {
        toast('✅ ' + res.message + ' · Número: ' + res.numero_socio);
        limpiarFormFamiliar(); loadSocios(); mostrarFormNuevo();
    } else toast(res.error, 'error');
}

async function desactivarMembresia(socio_membresia_id) {
    if (!confirm('¿Desactivar esta membresía? El socio dejará de aparecer activo.')) return;
    const res = await api('socios.php', { action: 'desactivar_membresia', socio_membresia_id });
    if (res.success) { toast('✅ ' + res.message); loadSocios(); }
    else toast(res.error, 'error');
}

// ── ELIMINAR TITULAR (y todos sus familiares) ──
async function eliminarTitular(socio_membresia_id, nombre, numFamiliares) {
    const msg = numFamiliares > 0
        ? `¿Eliminar permanentemente al titular "${nombre}" y sus ${numFamiliares} familiar(es)? Esta acción NO se puede deshacer.`
        : `¿Eliminar permanentemente al titular "${nombre}"? Esta acción NO se puede deshacer.`;
    if (!confirm(msg)) return;
    const res = await api('socios.php', { action: 'eliminar_socio_titular', socio_membresia_id });
    if (res.success) {
        toast('🗑️ ' + res.message);
        loadSocios();
        loadFamiliares();
        // Limpiar panel de detalles si estaba mostrando este socio
        if (socioSeleccionado == socio_membresia_id) {
            socioSeleccionado = null;
            document.getElementById('socioDetallesContent').innerHTML = '<div class="text-center text-muted py-4">Selecciona un socio para ver los detalles</div>';
        }
    } else toast(res.error, 'error');
}

// ── ELIMINAR FAMILIAR (solo el familiar) ──
async function eliminarFamiliar(socio_membresia_id, nombre, titular_id) {
    if (!confirm(`¿Eliminar permanentemente al familiar "${nombre}"? Esta acción NO se puede deshacer.`)) return;
    const res = await api('socios.php', { action: 'eliminar_familiar', socio_membresia_id });
    if (res.success) {
        toast('🗑️ ' + res.message);
        loadFamiliares();
        // Refrescar detalles del titular si está abierto
        if (titular_id) mostrarDetallesSocio(titular_id);
        else loadSocios();
    } else toast(res.error, 'error');
}

async function renovarMembresia(socio_membresia_id) {
    const nuevaFecha = prompt('Ingresa la nueva fecha de vencimiento (YYYY-MM-DD):');
    if (!nuevaFecha) return;
    const res = await api('socios.php', { action: 'renovar_membresia', socio_membresia_id, nueva_fecha_fin: nuevaFecha });
    if (res.success) { toast('✅ ' + res.message); mostrarDetallesSocio(socio_membresia_id); loadSocios(); }
    else toast(res.error, 'error');
}

async function loadFamiliares() {
    const q = document.getElementById('familiarBuscar').value;
    const body = document.getElementById('familiaresBody');
    body.innerHTML = '<tr><td colspan="9" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api('socios.php?action=list_familiares');
    if (!res.success) { toast(res.error,'error'); return; }
    let datos = res.data;
    if (q) {
        const qLower = q.toLowerCase();
        datos = datos.filter(f =>
            f.nombre.toLowerCase().includes(qLower) ||
            f.numero_socio.toLowerCase().includes(qLower) ||
            f.titular_nombre.toLowerCase().includes(qLower)
        );
    }
    body.innerHTML = datos.length ? '' : '<tr><td colspan="9" class="text-center text-muted py-4">Sin familiares registrados</td></tr>';
    const colorMap = {'CLASICA': '#6B7280', 'BENEFITS': '#003DA5', 'PLUS': '#7C3AED'};
    datos.forEach(f => {
        const colorMembresia = colorMap[f.tipo_membresia] || '#6B7280';
        const complementariaTag = f.es_complementaria == 1
            ? '<span class="badge bg-success">Gratis</span>'
            : '<span class="text-muted small">—</span>';
        body.innerHTML += `<tr>
            <td><strong>${f.nombre}</strong></td>
            <td class="text-muted">${f.numero_socio}</td>
            <td class="small">${f.parentesco}</td>
            <td><span class="badge" style="background:${colorMembresia};color:#fff;">${f.tipo_membresia}</span></td>
            <td><strong>${f.titular_nombre}</strong><br><span class="text-muted small">${f.titular_numero_socio}</span></td>
            <td class="text-center">${complementariaTag}</td>
            <td class="small">${f.vencimiento}</td>
            <td class="text-end fw-semibold">${fmt(f.saldo_cashback)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="eliminarFamiliar(${f.socio_membresia_id}, '${f.nombre.replace(/'/g,"\\'")}', ${f.cuenta_titular_id})" title="Eliminar familiar">🗑️</button>
            </td>
        </tr>`;
    });
}

let familiarBuscarTimer;
let sociosBuscarTimer;

// ═══════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    loadInventario();
    loadInvStats();
    loadProductosPromo();
    loadPromos();
    loadTiposMembresia();
    cargarTiposMembresiaForm();
    loadCompraData();
    loadHistVentas();
    loadSocios();
    loadFamiliares();

    document.getElementById('tab-inv').addEventListener('shown.bs.tab', () => { loadInventario(); loadInvStats(); });
    document.getElementById('tab-socios').addEventListener('shown.bs.tab', () => { loadSocios(); loadFamiliares(); });
    document.getElementById('tab-promo').addEventListener('shown.bs.tab', () => { loadPromos(); });
    document.getElementById('tab-comp').addEventListener('shown.bs.tab', () => { loadCompraData(); });
    document.getElementById('tab-vta').addEventListener('shown.bs.tab', () => { loadHistVentas(); });
    document.getElementById('pagosContainer').addEventListener('input', actualizarResumenPago);

    document.getElementById('familiarBuscar')?.addEventListener('input', () => {
        clearTimeout(familiarBuscarTimer); familiarBuscarTimer = setTimeout(loadFamiliares, 350);
    });
    document.getElementById('sociosBuscar')?.addEventListener('input', () => {
        clearTimeout(sociosBuscarTimer); sociosBuscarTimer = setTimeout(loadSocios, 350);
    });

    // Validación descuentos: solo % o $
    const promoDescPct = document.getElementById('promoDescPct');
    const promoDescMonto = document.getElementById('promoDescMonto');
    promoDescPct?.addEventListener('input', function() {
        if (parseFloat(this.value) > 0) { promoDescMonto.value = ''; promoDescMonto.style.opacity = '0.5'; promoDescMonto.disabled = true; }
        else { promoDescMonto.style.opacity = '1'; promoDescMonto.disabled = false; }
    });
    promoDescMonto?.addEventListener('input', function() {
        if (parseFloat(this.value) > 0) { promoDescPct.value = ''; promoDescPct.style.opacity = '0.5'; promoDescPct.disabled = true; }
        else { promoDescPct.style.opacity = '1'; promoDescPct.disabled = false; }
    });
});
</script>
</body>
</html>
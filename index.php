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
    --sam-blue:    #003DA5;
    --sam-yellow:  #FFC220;
    --sam-red:     #C8102E;
    --sam-dark:    #0A1628;
    --sam-light:   #F5F7FA;
    --sam-card:    #FFFFFF;
    --sam-border:  #E5E9F0;
    --sam-hover:   #F0F4FF;
    --sam-text:    #1F2937;
    --sam-muted:   #6B7280;
    --sam-green:   #10B981;
    --sam-purple:  #7C3AED;
    --sidebar-w:   260px;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--sam-light); font-family: 'Inter', sans-serif; color: var(--sam-text); }

/* ── SIDEBAR ── */
.sam-sidebar {
    position: fixed; left: 0; top: 0; width: var(--sidebar-w);
    height: 100vh; background: var(--sam-dark); display: flex;
    flex-direction: column; z-index: 1000; overflow-y: auto;
}
.sam-sidebar-brand {
    display: flex; align-items: center; gap: 12px;
    padding: 22px 20px 18px; border-bottom: 2px solid var(--sam-yellow);
}
.sam-sidebar-brand img { width: 36px; object-fit: contain; filter: brightness(1.1); }
.sam-sidebar-brand-text { font-size: .95rem; font-weight: 800; color: #fff; letter-spacing: .3px; }
.sam-sidebar-brand-sub { font-size: .65rem; color: rgba(255,255,255,.4); font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
.sam-nav { flex: 1; padding: 12px 0; }
.sam-nav-item {
    display: flex; align-items: center; gap: 13px; padding: 13px 20px;
    cursor: pointer; border-left: 3px solid transparent;
    color: rgba(255,255,255,.55); font-size: .88rem; font-weight: 500;
    transition: all .18s ease; user-select: none;
}
.sam-nav-item:hover { color: #fff; background: rgba(255,255,255,.06); border-left-color: rgba(255,204,32,.35); }
.sam-nav-item.active { color: #fff; background: rgba(255,255,255,.08); border-left-color: var(--sam-yellow); font-weight: 600; }
.sam-nav-item .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }
.sam-sidebar-footer {
    padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: 8px;
    font-size: .72rem; color: rgba(255,255,255,.4); font-weight: 500; text-transform: uppercase; letter-spacing: .6px;
}
.sam-status-dot {
    width: 7px; height: 7px; background: var(--sam-green); border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(16,185,129,.3); flex-shrink: 0;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot { 0%,100% { box-shadow: 0 0 0 2px rgba(16,185,129,.3); } 50% { box-shadow: 0 0 0 5px rgba(16,185,129,.1); } }

/* ── MAIN AREA ── */
.sam-main { margin-left: var(--sidebar-w); min-height: 100vh; padding: 28px 32px; }
.sam-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px; padding-bottom: 16px; border-bottom: 1px solid var(--sam-border);
}
.sam-page-title { font-size: 1.4rem; font-weight: 800; color: var(--sam-dark); position: relative; padding-bottom: 6px; }
.sam-page-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: var(--sam-yellow); border-radius: 2px; }
.sam-page-meta { display: flex; align-items: center; gap: 10px; }
.sam-date-chip { font-size: .78rem; font-weight: 600; color: var(--sam-muted); background: var(--sam-card); border: 1px solid var(--sam-border); border-radius: 20px; padding: 5px 12px; }

/* ── SECTIONS ── */
.sam-section { display: none; }
.sam-section.active { display: block; }

/* ── STAT CARDS ── */
.stat-card {
    background: var(--sam-card); border-radius: 12px; padding: 22px 24px;
    border: 1px solid var(--sam-border); border-left-width: 5px;
    transition: transform .2s ease, box-shadow .2s ease; position: relative; overflow: hidden;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.stat-card-icon { position: absolute; top: 16px; right: 18px; font-size: 1.6rem; opacity: .18; }
.stat-card h3 { font-size: 2.2rem; font-weight: 800; color: var(--sam-dark); letter-spacing: -1px; line-height: 1; }
.stat-card p { margin-top: 8px; color: var(--sam-muted); font-size: .85rem; font-weight: 500; }
.stat-card.blue   { border-left-color: var(--sam-blue); }
.stat-card.green  { border-left-color: var(--sam-green); }
.stat-card.red    { border-left-color: var(--sam-red); }
.stat-card.yellow { border-left-color: var(--sam-yellow); }

/* ── CARDS ── */
.sam-card { background: var(--sam-card); border-radius: 12px; border: 1px solid var(--sam-border); padding: 24px; }
.sam-card.top-accent { border-top: 4px solid var(--sam-blue); }

/* ── SECTION TITLE ── */
.section-title {
    font-weight: 800; color: var(--sam-dark); font-size: .95rem;
    border-bottom: 3px solid var(--sam-yellow); padding-bottom: 8px; margin-bottom: 16px; display: block;
}

/* ── TABLES ── */
.sam-table { background: var(--sam-card); border-radius: 12px; overflow: hidden; border: 1px solid var(--sam-border); }
.sam-table .table { margin-bottom: 0; }
.sam-table thead th {
    background: var(--sam-dark); color: #fff; font-size: .78rem;
    letter-spacing: .5px; text-transform: uppercase; border: none; padding: 13px 16px; font-weight: 700;
}
.sam-table tbody tr { border-bottom: 1px solid var(--sam-border); transition: background .15s ease; }
.sam-table tbody tr:last-child { border-bottom: none; }
.sam-table tbody tr:hover { background: var(--sam-hover); }
.sam-table td { vertical-align: middle; font-size: .9rem; padding: 14px 16px; color: #374151; border: none; }
.sam-table td strong { color: var(--sam-dark); font-weight: 600; }
.stock-zero { color: var(--sam-red); }

/* ── FORMS ── */
.form-label { font-size: .85rem; font-weight: 600; color: var(--sam-dark); margin-bottom: 6px; display: block; }
.form-control, .form-select {
    font-size: .9rem; padding: 10px 13px; border: 1.5px solid var(--sam-border);
    border-radius: 8px; background: var(--sam-card); color: var(--sam-text);
    transition: border-color .2s, box-shadow .2s; min-height: 42px; width: 100%;
}
.form-control:focus, .form-select:focus {
    border-color: var(--sam-blue); box-shadow: 0 0 0 3px rgba(0,61,165,.1); outline: none;
}
.form-control::placeholder { color: #9CA3AF; }

/* ── BUTTONS ── */
.btn { font-weight: 600; padding: 10px 18px; border-radius: 8px; transition: all .18s ease; font-size: .88rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
.btn-primary   { background: var(--sam-blue); color: #fff; }
.btn-primary:hover { background: #002E85; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,61,165,.28); color: #fff; }
.btn-success   { background: var(--sam-green); color: #fff; }
.btn-success:hover { background: #059669; color: #fff; }
.btn-danger    { background: var(--sam-red); color: #fff; }
.btn-danger:hover { background: #B50A23; color: #fff; }
.btn-outline-primary { background: transparent; color: var(--sam-blue); border: 1.5px solid var(--sam-blue); }
.btn-outline-primary:hover { background: var(--sam-blue); color: #fff; }
.btn-outline-danger { background: transparent; color: var(--sam-red); border: 1.5px solid var(--sam-red); }
.btn-outline-danger:hover { background: var(--sam-red); color: #fff; }
.btn-outline-secondary { background: transparent; color: var(--sam-text); border: 1.5px solid var(--sam-border); }
.btn-outline-secondary:hover { background: var(--sam-light); }
.btn-sm { padding: 6px 12px; font-size: .8rem; }
.btn-cobrar { background: var(--sam-yellow) !important; color: var(--sam-dark) !important; font-weight: 800 !important; font-size: 1rem !important; letter-spacing: .3px; }
.btn-cobrar:hover { background: #e6ae00 !important; box-shadow: 0 4px 16px rgba(255,194,32,.4); }
.w-100 { width: 100%; }

/* ── BADGES ── */
.badge { padding: 4px 10px; font-size: .72rem; font-weight: 700; border-radius: 20px; display: inline-block; }
.badge-blue   { background: #EFF6FF; color: var(--sam-blue); }
.badge-green  { background: #D1FAE5; color: #065F46; }
.badge-red    { background: #FEE2E2; color: #991B1B; }
.badge-yellow { background: #FEF3C7; color: #92400E; }
.badge-gray   { background: #F3F4F6; color: #374151; }
.badge-purple { background: #F5F3FF; color: var(--sam-purple); }
.badge-gold   { background: var(--sam-yellow); color: var(--sam-dark); border-radius: 20px; font-size: .7rem; padding: 2px 8px; font-weight: 700; }
.badge-promo  { background: #D1FAE5; color: #065F46; border-radius: 20px; font-size: .7rem; padding: 2px 8px; font-weight: 600; }
.badge-tipo   { font-size: .72rem; padding: 3px 9px; }

/* ── MEMBERSHIP PILL TOGGLES ── */
.memb-pill-toggle {
    display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px;
    border-radius: 20px; cursor: pointer; border: 1.5px solid var(--sam-border);
    font-size: .82rem; font-weight: 600; transition: all .18s; user-select: none;
    color: var(--sam-muted); background: var(--sam-card);
}
.memb-pill-toggle:hover { border-color: var(--sam-blue); }
.memb-pill-toggle input { display: none; }
.memb-pill-toggle.active-CLASICA  { background: #F3F4F6; border-color: #6B7280; color: #374151; }
.memb-pill-toggle.active-BENEFITS { background: #EFF6FF; border-color: var(--sam-blue); color: var(--sam-blue); }
.memb-pill-toggle.active-PLUS     { background: #F5F3FF; border-color: var(--sam-purple); color: var(--sam-purple); }

/* ── PILL SUB-TABS (Socios) ── */
.pill-tabs { display: flex; gap: 6px; margin-bottom: 16px; }
.pill-tab {
    padding: 7px 18px; border-radius: 20px; cursor: pointer; font-size: .82rem;
    font-weight: 600; border: 1.5px solid var(--sam-border); background: var(--sam-card);
    color: var(--sam-muted); transition: all .18s;
}
.pill-tab:hover { border-color: var(--sam-blue); color: var(--sam-blue); }
.pill-tab.active { background: var(--sam-blue); border-color: var(--sam-blue); color: #fff; }

/* ── POS ── */
.pos-wrap { display: flex; gap: 20px; height: calc(100vh - 200px); min-height: 520px; }
.pos-light { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 12px; }
.pos-terminal {
    flex: 0 0 320px; background: var(--sam-dark); border-radius: 12px;
    display: flex; flex-direction: column; overflow: hidden;
}
.pos-terminal-header { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
.pos-terminal-header img { width: 28px; filter: brightness(1.2); }
.pos-terminal-header span { font-size: .78rem; font-weight: 700; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1px; }
.pos-terminal-body { flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 14px; }
.pos-terminal-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); display: flex; flex-direction: column; gap: 8px; }
.input-dark {
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; color: #fff; padding: 9px 12px; font-size: .88rem; width: 100%;
}
.input-dark::placeholder { color: rgba(255,255,255,.35); }
.input-dark:focus { outline: none; border-color: var(--sam-yellow); background: rgba(255,255,255,.1); }
.select-dark {
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; color: #fff; padding: 9px 12px; font-size: .88rem; width: 100%;
    appearance: none; -webkit-appearance: none;
}
.select-dark option { background: var(--sam-dark); color: #fff; }
.select-dark:focus { outline: none; border-color: var(--sam-yellow); }
.pos-label { font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
.pos-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.06); }
.pos-row:last-child { border-bottom: none; }
.pos-row-label { font-size: .85rem; color: rgba(255,255,255,.55); font-weight: 500; }
.pos-row-value { font-size: .9rem; color: #fff; font-weight: 600; }
.pos-total-amount { font-size: 2.4rem; font-weight: 800; color: var(--sam-yellow); letter-spacing: -1px; text-align: right; line-height: 1; }
.pos-total-label { font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.35); text-transform: uppercase; letter-spacing: 1px; text-align: right; margin-bottom: 4px; }
.pos-divider { border: none; border-top: 1px solid rgba(255,255,255,.08); margin: 4px 0; }
.socio-terminal-block { background: rgba(255,255,255,.06); border-radius: 8px; padding: 10px 12px; border: 1px solid rgba(255,255,255,.1); }
.socio-terminal-active { display: flex; align-items: center; gap: 8px; }
.socio-terminal-name { flex: 1; font-size: .88rem; font-weight: 600; color: #fff; }
.socio-terminal-pill { font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: rgba(255,204,32,.2); color: var(--sam-yellow); }
.socio-terminal-remove { background: rgba(255,255,255,.12); border: none; color: rgba(255,255,255,.6); border-radius: 50%; width: 22px; height: 22px; cursor: pointer; font-size: .8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.socio-terminal-remove:hover { background: rgba(255,255,255,.22); }
.pos-cart-wrap { flex: 1; overflow-y: auto; }
.pos-qty-control { display: flex; align-items: center; gap: 4px; }
.pos-qty-btn { width: 26px; height: 26px; border: 1.5px solid var(--sam-border); background: var(--sam-card); border-radius: 6px; cursor: pointer; font-size: .9rem; display: flex; align-items: center; justify-content: center; color: var(--sam-dark); transition: all .15s; }
.pos-qty-btn:hover { border-color: var(--sam-blue); color: var(--sam-blue); }
.pos-qty-input { width: 42px; text-align: center; border: 1.5px solid var(--sam-border); border-radius: 6px; padding: 3px 4px; font-size: .85rem; font-weight: 600; }

/* ── SEARCH DROPDOWNS ── */
#searchResults, #socioResults {
    position: absolute; z-index: 1050; width: 100%; background: var(--sam-card);
    border: 1.5px solid var(--sam-border); border-top: none; border-radius: 0 0 10px 10px;
    max-height: 280px; overflow-y: auto; box-shadow: 0 8px 20px rgba(0,0,0,.1);
}
#searchResults .res-item, #socioResults > div {
    padding: 11px 14px; cursor: pointer; border-bottom: 1px solid var(--sam-border); font-size: .88rem; transition: background .15s;
}
#searchResults .res-item:hover, #socioResults > div:hover { background: var(--sam-hover); }
#searchResults .res-item:last-child, #socioResults > div:last-child { border-bottom: none; }
#searchResults .res-sku { color: var(--sam-muted); font-size: .75rem; margin-top: 2px; }
.res-memb-tag { font-size: .68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; background: #EFF6FF; color: var(--sam-blue); }

/* ── DASHBOARD BARS ── */
.memb-bar-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.memb-bar-label { font-size: .8rem; font-weight: 600; color: var(--sam-text); width: 72px; flex-shrink: 0; }
.memb-bar-track { flex: 1; height: 10px; background: var(--sam-light); border-radius: 10px; overflow: hidden; }
.memb-bar-fill { height: 100%; border-radius: 10px; transition: width .6s ease; }
.memb-bar-count { font-size: .8rem; font-weight: 700; color: var(--sam-muted); width: 32px; text-align: right; flex-shrink: 0; }

/* ── COMPRAS ── */
.compra-items-sub { border-radius: 8px; border: 1px solid var(--sam-border); overflow: hidden; margin-bottom: 12px; }
.compra-total-row { border-left: 4px solid var(--sam-blue); padding: 12px 16px; background: var(--sam-card); display: flex; justify-content: space-between; align-items: center; border-radius: 0 8px 8px 0; margin-bottom: 16px; }
.compra-item-input { font-size: .85rem; padding: 4px 8px; border: 1.5px solid var(--sam-border); border-radius: 6px; color: var(--sam-text); background: var(--sam-card); }
.compra-item-input:focus { border-color: var(--sam-blue); outline: none; }

/* ── TOAST ── */
#toastArea { position: fixed; bottom: 24px; right: 24px; z-index: 9999; pointer-events: none; }
.sam-toast {
    padding: 14px 18px; border-radius: 10px; color: #fff; font-weight: 600;
    margin-top: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.18);
    animation: toastIn .3s ease; min-width: 280px; font-size: .9rem; pointer-events: all;
}
.sam-toast.success { background: linear-gradient(135deg, var(--sam-green), #059669); }
.sam-toast.error   { background: linear-gradient(135deg, var(--sam-red), #B50A23); }
.sam-toast.warn    { background: linear-gradient(135deg, #F59E0B, #D97706); }
@keyframes toastIn { from { transform: translateX(80px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* ── UTILITIES ── */
.spinner-sam { display: inline-block; width: 16px; height: 16px; border: 2.5px solid rgba(0,61,165,.2); border-top-color: var(--sam-blue); border-radius: 50%; animation: spin .6s linear infinite; }
.spinner-border-sm { display: inline-block; width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.alert { border-radius: 8px; padding: 12px 16px; font-size: .85rem; border: 1px solid; }
.alert-info { background: #EFF6FF; color: #1E40AF; border-color: #BFDBFE; }
.mb-2 { margin-bottom: 10px; }
.mb-3 { margin-bottom: 16px; }
.mt-2 { margin-top: 8px; }
.mt-3 { margin-top: 14px; }
.mt-4 { margin-top: 20px; }
.text-muted { color: var(--sam-muted) !important; }
.text-end { text-align: right !important; }
.text-center { text-align: center !important; }
.fw-bold { font-weight: 700 !important; }
.fw-semibold { font-weight: 600 !important; }
.fw-600 { font-weight: 600; }
.small { font-size: .82rem; }
.text-xs { font-size: .72rem; }
.text-nowrap { white-space: nowrap; }
.sam-input { border: 1.5px solid var(--sam-border); border-radius: 8px; padding: 10px 13px; font-size: .9rem; }
.d-flex { display: flex; }
.d-none { display: none !important; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-4 { gap: 20px; }
.flex-wrap { flex-wrap: wrap; }
.py-3 { padding-top: 14px; padding-bottom: 14px; }
.py-4 { padding-top: 20px; padding-bottom: 20px; }
#pagoResumen { font-size: .82rem; margin-top: 6px; display: flex; justify-content: space-between; color: rgba(255,255,255,.55); }
#pagoResumen .pago-faltante { color: #F87171; font-weight: 700; }
#pagoResumen .pago-ok { color: var(--sam-green); font-weight: 700; }
.acciones-socio { display: flex; gap: 4px; flex-wrap: nowrap; }
#cambioInfo { font-size: .85rem; font-weight: 600; color: var(--sam-green); margin-top: 4px; }
.todos-check-wrap label { font-size: .85rem; font-weight: 600; color: var(--sam-muted); cursor: pointer; display: flex; align-items: center; gap: 8px; }
@media (max-width: 900px) { .sam-main { margin-left: 0; padding: 16px; } .sam-sidebar { display: none; } .pos-terminal { flex: 0 0 280px; } }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sam-sidebar">
    <div class="sam-sidebar-brand">
        <img src="img/sams_logo.png" alt="Sam's">
        <div>
            <div class="sam-sidebar-brand-text">Sam's Admin</div>
            <div class="sam-sidebar-brand-sub">Panel de Control</div>
        </div>
    </div>
    <nav class="sam-nav">
        <div class="sam-nav-item active" data-section="dashboard" onclick="showSection('dashboard')">
            <span class="nav-icon">⊞</span> Dashboard
        </div>
        <div class="sam-nav-item" data-section="inventario" onclick="showSection('inventario')">
            <span class="nav-icon">📦</span> Inventario
        </div>
        <div class="sam-nav-item" data-section="socios" onclick="showSection('socios')">
            <span class="nav-icon">👥</span> Socios
        </div>
        <div class="sam-nav-item" data-section="promociones" onclick="showSection('promociones')">
            <span class="nav-icon">🏷️</span> Promociones
        </div>
        <div class="sam-nav-item" data-section="compras" onclick="showSection('compras')">
            <span class="nav-icon">🛒</span> Compras
        </div>
        <div class="sam-nav-item" data-section="ventas" onclick="showSection('ventas')">
            <span class="nav-icon">💳</span> Punto de Venta
        </div>
    </nav>
    <div class="sam-sidebar-footer">
        <span class="sam-status-dot"></span>
        Sistema activo
    </div>
</aside>

<!-- MAIN -->
<main class="sam-main">
    <div class="sam-page-header">
        <div class="sam-page-title" id="page-title">Dashboard</div>
        <div class="sam-page-meta">
            <span class="sam-date-chip" id="page-date"></span>
            <button class="btn btn-sm btn-outline-primary" onclick="refreshCurrentSection()">↺ Actualizar</button>
        </div>
    </div>

<!-- ═══ DASHBOARD ═══ -->
<div class="sam-section active" id="section-dashboard">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <span class="stat-card-icon">👥</span>
                <h3 id="dash-socios">—</h3>
                <p>Socios activos</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <span class="stat-card-icon">💰</span>
                <h3 id="dash-ingresos">—</h3>
                <p>Ingresos hoy</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card red">
                <span class="stat-card-icon">📦</span>
                <h3 id="dash-sinstock">—</h3>
                <p>Sin existencia</p>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card yellow">
                <span class="stat-card-icon">🏷️</span>
                <h3 id="dash-promos">—</h3>
                <p>Promociones activas</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="sam-card" style="height:100%;">
                <span class="section-title">Ventas Recientes</span>
                <div class="sam-table">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>#</th><th>Canal</th><th class="text-end">Total</th></tr>
                        </thead>
                        <tbody id="dash-ventas-body">
                            <tr><td colspan="3" class="text-center py-3 text-muted">Cargando…</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="#" class="btn btn-sm btn-outline-primary" onclick="showSection('ventas'); return false;">Ver todas las ventas →</a>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="sam-card" style="height:100%;">
                <span class="section-title">Distribución de Membresías</span>
                <div id="dash-memb-bars">
                    <div class="text-center text-muted py-3 small">Cargando…</div>
                </div>
            </div>
        </div>
    </div>

    <div class="sam-card">
        <span class="section-title">Accesos Rápidos</span>
        <div class="d-flex gap-3 flex-wrap">
            <button class="btn btn-primary" onclick="showSection('ventas'); setTimeout(()=>document.getElementById('posSearch')?.focus(),100)">
                💳 Nueva Venta
            </button>
            <button class="btn btn-outline-primary" onclick="showSection('socios'); setTimeout(()=>document.getElementById('socioNombre')?.focus(),100)">
                👤 Nuevo Socio
            </button>
            <button class="btn btn-outline-primary" onclick="showSection('compras'); setTimeout(()=>document.getElementById('compraProveedor')?.focus(),100)">
                🛒 Registrar Compra
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 1 · INVENTARIO
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-inventario">
    <div class="sam-card mb-3">
        <div class="d-flex align-items-center gap-3">
            <input type="text" id="invSearch" class="form-control sam-input" placeholder="🔍  Buscar por nombre, SKU o marca…" oninput="clearTimeout(invTimer);invTimer=setTimeout(()=>loadInventario(this.value),350)">
            <div class="text-nowrap text-muted small" id="invCount"></div>
        </div>
    </div>

    <div class="sam-card">
        <div class="sam-table">
            <table class="table mb-0" id="invTable">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Marca / Cat.</th>
                        <th class="text-center">Stock Piso</th>
                        <th class="text-center">Stock Reserva</th>
                        <th class="text-end">Precio</th>
                        <th>Promo</th>
                    </tr>
                </thead>
                <tbody id="invBody">
                    <tr><td colspan="7" class="text-center py-4 text-muted">Cargando…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 2 · SOCIOS
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-socios">
    <div class="d-flex gap-4" style="align-items:flex-start;gap:24px;">
        <div style="flex:0 0 340px; min-width:0;">
            <div class="sam-card top-accent">
                <div id="socioFormNuevo">
                    <span class="section-title">Nuevo Socio Titular</span>
                    <div class="mb-3"><label class="form-label">Nombre completo</label><input type="text" id="socioNombre" class="form-control" placeholder="Ej: Juan Pérez"></div>
                    <div class="mb-3"><label class="form-label">Correo electrónico</label><input type="email" id="socioCorreo" class="form-control" placeholder="correo@ejemplo.com"></div>
                    <div class="mb-3"><label class="form-label">Teléfono</label><input type="tel" id="socioTelefono" class="form-control" placeholder="+52 222 123 4567"></div>
                    <div class="mb-3"><label class="form-label">Tipo de membresía</label><select id="socioTipoMembresia" class="form-select"><option value="">Seleccionar tipo…</option></select></div>
                    <div class="mb-3"><label class="form-label">Fecha de vencimiento</label><input type="date" id="socioFechaFin" class="form-control"></div>
                    <button class="btn btn-primary w-100 mb-2" onclick="crearSocioTitular()">Registrar Socio Titular</button>
                    <button class="btn btn-outline-secondary w-100" onclick="mostrarFormFamiliar()">Vincular Familiar</button>
                </div>
                <div id="socioFormFamiliar" style="display:none;">
                    <span class="section-title">Vincular Familiar</span>
                    <div class="mb-3"><label class="form-label">Socio Titular</label><select id="socioTitularSel" class="form-select"><option value="">Seleccionar titular…</option></select></div>
                    <div class="alert alert-info mb-3">El familiar heredará el tipo de membresía y fecha de vencimiento del titular.</div>
                    <div class="mb-3"><label class="form-label">Nombre del familiar</label><input type="text" id="familiarNombre" class="form-control" placeholder="Nombre completo"></div>
                    <div class="mb-3"><label class="form-label">Correo electrónico</label><input type="email" id="familiarCorreo" class="form-control" placeholder="correo@ejemplo.com"></div>
                    <div class="mb-3"><label class="form-label">Teléfono</label><input type="tel" id="familiarTelefono" class="form-control" placeholder="+52 222 123 4567"></div>
                    <div class="mb-3"><label class="form-label">Parentesco</label>
                        <select id="familiarParentesco" class="form-select">
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="HIJO">Hijo/a</option>
                            <option value="PADRE">Padre/Madre</option>
                            <option value="HERMANO">Hermano/a</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:600;">
                            <input type="checkbox" id="familiarComplementaria"> ¿Tarjeta complementaria gratis?
                        </label>
                        <small class="text-muted">Solo 1 complementaria gratis por titular.</small>
                    </div>
                    <button class="btn btn-success w-100 mb-2" onclick="crearSocioFamiliar()">Vincular Familiar</button>
                    <button class="btn btn-outline-secondary w-100" onclick="mostrarFormNuevo()">← Volver</button>
                </div>
            </div>
        </div>
        <div style="flex:1; min-width:0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" id="sociosBuscar" class="form-control" placeholder="Buscar socio…" style="max-width:260px;">
                <button class="btn btn-sm btn-outline-primary" onclick="loadSocios(); loadFamiliares();">↺ Actualizar</button>
            </div>
            <div class="pill-tabs">
                <div class="pill-tab active" onclick="showSocioTab('listado', this)">Titulares</div>
                <div class="pill-tab" onclick="showSocioTab('familiares', this)">Familiares</div>
                <div class="pill-tab" onclick="showSocioTab('detalles', this)">Detalles</div>
            </div>
            <div id="socios-listado">
                <div class="sam-table table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>#</th><th>Número</th><th>Nombre</th><th>Membresía</th><th class="text-center">Familiares</th><th>Vencimiento</th><th class="text-end">Cashback</th><th>Acciones</th></tr></thead>
                        <tbody id="sociosBody"><tr><td colspan="8" class="text-center text-muted py-4">Cargando socios…</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div id="socios-familiares" style="display:none;">
                <div class="d-flex gap-2 mb-3">
                    <input type="text" id="familiarBuscar" class="form-control" placeholder="Buscar familiar…" style="max-width:300px;">
                    <button class="btn btn-sm btn-outline-primary" onclick="loadFamiliares()">↺</button>
                </div>
                <div class="sam-table table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>Familiar</th><th>Número</th><th>Parentesco</th><th>Membresía</th><th>Titular</th><th class="text-center">Complem.</th><th>Vencimiento</th><th class="text-end">Cashback</th><th>Acciones</th></tr></thead>
                        <tbody id="familiaresBody"><tr><td colspan="9" class="text-center text-muted py-4">Cargando familiares…</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div id="socios-detalles" style="display:none;">
                <div id="socioDetallesContent" class="text-center text-muted py-4">Selecciona un socio para ver los detalles</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 3 · PROMOCIONES
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-promociones">
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
<div class="sam-section" id="section-compras">
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
<div class="sam-section" id="section-ventas">
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

</main><!-- /sam-main -->

<div id="toastArea"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const sectionTitles = {
    dashboard:   'Dashboard',
    inventario:  'Inventario',
    socios:      'Socios',
    promociones: 'Promociones',
    compras:     'Compras',
    ventas:      'Punto de Venta'
};

let currentSection = 'dashboard';

function showSection(name) {
    document.querySelectorAll('.sam-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.sam-nav-item').forEach(i => i.classList.remove('active'));
    const section = document.getElementById('section-' + name);
    if (section) section.classList.add('active');
    const navItem = document.querySelector('[data-section="' + name + '"]');
    if (navItem) navItem.classList.add('active');
    document.getElementById('page-title').textContent = sectionTitles[name] || name;
    currentSection = name;
}

function showSocioTab(name, el) {
    ['listado', 'familiares', 'detalles'].forEach(t => {
        const pane = document.getElementById('socios-' + t);
        if (pane) pane.style.display = 'none';
    });
    document.querySelectorAll('#section-socios .pill-tab').forEach(t => t.classList.remove('active'));
    const pane = document.getElementById('socios-' + name);
    if (pane) pane.style.display = 'block';
    if (el) el.classList.add('active');
}

function refreshCurrentSection() {
    const map = {
        dashboard:   loadDashboard,
        inventario:  () => { loadInventario(); },
        socios:      loadSocios,
        promociones: loadPromos,
        compras:     loadHistCompra,
        ventas:      loadHistVentas
    };
    if (map[currentSection]) map[currentSection]();
}

document.getElementById('page-date').textContent = new Date().toLocaleDateString('es-MX', {
    weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
});
</script>

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
function esc(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
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
// DASHBOARD
// ═══════════════════════════════════════════
async function loadDashboard() {
    const [sociosRes, invRes, promoRes, ventasRes] = await Promise.all([
        api('socios.php?action=list_titulares'),
        api('inventario.php?action=stats'),
        api('promociones.php?action=list_promos'),
        api('ventas.php?action=historial')
    ]);

    // Socios activos + membership breakdown
    if (sociosRes.success) {
        document.getElementById('dash-socios').textContent = sociosRes.data.length;
        const counts = { CLASICA: 0, BENEFITS: 0, PLUS: 0 };
        sociosRes.data.forEach(s => { if (s.tipo_membresia in counts) counts[s.tipo_membresia]++; });
        const total = sociosRes.data.length || 1;
        const colors = { CLASICA: '#6B7280', BENEFITS: '#003DA5', PLUS: '#7C3AED' };
        document.getElementById('dash-memb-bars').innerHTML =
            Object.entries(counts).map(([tipo, count]) =>
                `<div class="memb-bar-row">
                    <span class="memb-bar-label">${tipo}</span>
                    <div class="memb-bar-track">
                        <div class="memb-bar-fill" style="width:${Math.round(count/total*100)}%;background:${colors[tipo]};"></div>
                    </div>
                    <span class="memb-bar-count">${count}</span>
                </div>`
            ).join('');
    }

    // Sin stock count
    if (invRes.success) document.getElementById('dash-sinstock').textContent = invRes.data.sin_stock;

    // Promociones activas count
    if (promoRes.success) {
        document.getElementById('dash-promos').textContent = promoRes.data.filter(p => p.activo == 1).length;
    }

    // Ventas recientes + ingresos hoy
    const dashBody = document.getElementById('dash-ventas-body');
    if (ventasRes.success && ventasRes.data.length) {
        const now = new Date();
        const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
        let todayTotal = 0;
        ventasRes.data.forEach(v => {
            if (v.fecha && v.fecha.startsWith(todayStr)) todayTotal += parseFloat(v.total || 0);
        });
        document.getElementById('dash-ingresos').textContent = fmt(todayTotal);
        dashBody.innerHTML = ventasRes.data.slice(0, 5).map(v =>
            `<tr>
                <td class="text-muted small">#${v.id}</td>
                <td><span class="badge badge-blue">${v.canal || '—'}</span></td>
                <td class="text-end fw-bold">${fmt(v.total)}</td>
            </tr>`
        ).join('');
    } else {
        dashBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">Sin ventas registradas</td></tr>';
        document.getElementById('dash-ingresos').textContent = fmt(0);
    }
}

// ═══════════════════════════════════════════
// TAB 1 · INVENTARIO
// ═══════════════════════════════════════════
async function loadInventario(q = '') {
    if (q === '' || q === undefined) {
        const el = document.getElementById('invSearch');
        if (el) q = el.value;
    }
    const body = document.getElementById('invBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="7" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api('inventario.php?action=list&q=' + encodeURIComponent(q));
    if (!res.success) { toast(res.error,'error'); return; }
    const data = res.data;
    body.innerHTML = data.length ? '' : '<tr><td colspan="7" class="text-center text-muted py-4">Sin resultados</td></tr>';
    body.innerHTML += data.map(p => `<tr>
    <td class="text-muted small">${esc(p.sku)}</td>
    <td>
        <span class="fw-600">${esc(p.nombre)}</span>
        ${p.es_members_mark == 1 ? '<span class="badge badge-gold ms-1">Member\'s Mark</span>' : ''}
    </td>
    <td class="small text-muted">${esc(p.marca)}<br><span class="text-xs">${esc(p.categoria || '')}</span></td>
    <td class="text-center ${p.stock_piso == 0 ? 'stock-zero fw-bold' : ''}">${Number(p.stock_piso)}</td>
    <td class="text-center text-muted">${Number(p.stock_reserva)}</td>
    <td class="text-end">${fmt(p.precio_actual)}</td>
    <td>${p.promo_nombre ? `<span class="badge badge-promo">${p.descuento_pct > 0 ? Number(p.descuento_pct)+'%' : '$'+Number(p.descuento_monto)} ${esc(p.promo_nombre)}</span>` : '<span class="text-muted small">—</span>'}</td>
</tr>`).join('');
    const countEl = document.getElementById('invCount');
    if (countEl) countEl.textContent = data.length + ' producto' + (data.length !== 1 ? 's' : '');
}
async function loadInvStats() {
    const res = await api('inventario.php?action=stats');
    if (!res.success) return;
    const el = (id) => document.getElementById(id);
    if (el('st-total')) el('st-total').textContent = res.data.total_productos;
    if (el('st-stock')) el('st-stock').textContent = res.data.con_stock;
    if (el('st-sin'))   el('st-sin').textContent   = res.data.sin_stock;
    if (el('st-promo')) el('st-promo').textContent = res.data.con_promo;
}
let invTimer;

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
        const membClass = r.tipo_membresia === 'PLUS' ? 'badge-purple' : r.tipo_membresia === 'BENEFITS' ? 'badge-blue' : 'badge-gray';
        body.innerHTML += `<tr style="cursor:pointer;" onclick="mostrarDetallesSocio(${r.socio_membresia_id})">
            <td class="text-muted small">#${r.socio_membresia_id}</td>
            <td class="fw-semibold">${esc(r.numero_socio)}</td>
            <td><strong>${esc(r.nombre)}</strong></td>
            <td><span class="badge ${membClass}">${esc(r.tipo_membresia)}</span></td>
            <td class="text-center"><strong>${r.num_familiares || 0}</strong></td>
            <td class="small">${esc(r.vencimiento)}</td>
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
    showSocioTab('detalles', document.querySelector('#section-socios .pill-tab:nth-child(3)'));
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
    showSection('dashboard');
    loadDashboard();
    loadInventario();
    loadProductosPromo();
    loadPromos();
    loadTiposMembresia();
    cargarTiposMembresiaForm();
    loadCompraData();
    loadHistVentas();
    loadSocios();
    loadFamiliares();

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
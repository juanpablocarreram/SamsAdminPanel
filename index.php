<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAMS · Sistema de Administración de Membresías</title>
    <script>window.tailwind={config:{theme:{extend:{colors:{'sams-blue':'#003DA5','sams-yellow':'#FFC220','sams-red':'#C8102E'}}}}}</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
/* ── DESIGN TOKENS ── */
:root {
    --sam-blue:   #003DA5;
    --sam-yellow: #FFC220;
    --sam-red:    #C8102E;
    --sam-green:  #10B981;
    --sam-muted:  #64748b;
    --sam-bg:     #F5F7FA;
}
/* ── FONT ── */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.6; }

/* ── SECTION VISIBILITY (JS: classList.add/remove 'active') ── */
.sam-section { display: none; }
.sam-section.active { display: block; }

/* ── SIDEBAR NAV ITEMS (JS: classList.add/remove 'active') ── */
.sam-nav-item {
    display: flex; align-items: center; gap: 10px; padding: 8px 12px;
    cursor: pointer; border-radius: 7px; margin: 1px 8px;
    color: rgb(148 163 184); font-size: .84rem; font-weight: 500;
    transition: all .15s; user-select: none;
}
.sam-nav-item:hover { color: #fff; background: rgba(255,255,255,.07); }
.sam-nav-item.active { color: #fff; background: rgba(0,61,165,.28); font-weight: 600; }
.sam-nav-item svg { width: 15px; height: 15px; stroke: currentColor; flex-shrink: 0; }
.sam-nav-item i { width: 15px; height: 15px; flex-shrink: 0; display: flex; }

/* ── PILL SUB-TABS — Socios (JS: classList.add/remove 'active') ── */
.pill-tab {
    padding: 6px 16px; border-radius: 20px; cursor: pointer;
    font-size: .82rem; font-weight: 500; border: 1.5px solid #e2e8f0;
    background: #fff; color: #64748b; transition: all .15s;
}
.pill-tab:hover { border-color: #003DA5; color: #003DA5; }
.pill-tab.active { background: #003DA5; border-color: #003DA5; color: #fff; }

/* ── MEMBERSHIP PILL TOGGLES (JS: classList.add/remove 'active-TYPE') ── */
.memb-pill-toggle {
    display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
    border-radius: 20px; cursor: pointer; border: 1.5px solid #e2e8f0;
    font-size: .82rem; font-weight: 500; transition: all .15s;
    color: #64748b; background: #fff; user-select: none;
}
.memb-pill-toggle input { display: none; }
.memb-pill-toggle:hover { border-color: #6366f1; }
.memb-pill-toggle.active-CLASICA  { background: #f1f5f9; border-color: #94a3b8; color: #475569; }
.memb-pill-toggle.active-BENEFITS { background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; }
.memb-pill-toggle.active-PLUS     { background: #f5f3ff; border-color: #7c3aed; color: #7c3aed; }

/* ── BOOTSTRAP COMPAT — JS adds/removes these class names ── */
.d-none { display: none !important; }
.d-flex { display: flex !important; }
.text-center { text-align: center; }
.text-end { text-align: right; }
.fw-bold { font-weight: 700; }
.text-muted { color: #64748b; }
.w-100 { width: 100%; }
.mt-2 { margin-top: 8px; }
.ms-1 { margin-left: 4px; }
.mb-0 { margin-bottom: 0; }
.py-3 { padding-top: 12px; padding-bottom: 12px; }
.py-4 { padding-top: 18px; padding-bottom: 18px; }
.small { font-size: .82rem; }
.pe-none { pointer-events: none; }
.opacity-50 { opacity: .5; }

/* ── BADGE SYSTEM — used in JS-generated table rows ── */
.badge { padding: 3px 10px; font-size: .71rem; font-weight: 600; border-radius: 20px; display: inline-block; white-space: nowrap; }
.badge-blue   { background: #eff6ff; color: #1d4ed8; }
.badge-green  { background: #d1fae5; color: #065f46; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-yellow { background: #fef3c7; color: #92400e; }
.badge-gray   { background: #f1f5f9; color: #475569; }
.badge-purple { background: #f5f3ff; color: #6d28d9; }
.badge-gold   { background: #fbbf24; color: #1c1917; }
.badge-tipo   { font-size: .7rem; padding: 2px 8px; }

/* ── POS TERMINAL — kept dark for functional contrast ── */
.pos-wrap { display: flex; gap: 20px; height: calc(100vh - 210px); min-height: 520px; }
.pos-light { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 12px; }
.pos-terminal {
    flex: 0 0 370px; background: #0f172a; border-radius: 14px;
    display: flex; flex-direction: column; overflow: hidden;
    border: 1px solid rgba(255,255,255,.06);
}
.pos-terminal-header { padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,.07); display: flex; align-items: center; gap: 10px; }
.pos-terminal-header img { width: 26px; }
.pos-terminal-header span { font-size: .73rem; font-weight: 700; color: rgba(255,255,255,.38); text-transform: uppercase; letter-spacing: 1px; }
.pos-terminal-body { flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 14px; scrollbar-width: thin; scrollbar-color: #1e293b transparent; }
.pos-terminal-footer { padding: 15px 20px; border-top: 1px solid rgba(255,255,255,.07); display: flex; flex-direction: column; gap: 8px; }
.input-dark { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.11); border-radius: 8px; color: #fff; padding: 9px 12px; font-size: .88rem; width: 100%; font-family: inherit; }
.input-dark::placeholder { color: rgba(255,255,255,.28); }
.input-dark:focus { outline: none; border-color: #818cf8; background: rgba(255,255,255,.09); }
.select-dark { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.11); border-radius: 8px; color: #fff; padding: 9px 12px; font-size: .88rem; width: 100%; appearance: none; font-family: inherit; }
.select-dark option { background: #0f172a; }
.select-dark:focus { outline: none; border-color: #818cf8; }
.pos-label { font-size: .67rem; font-weight: 700; color: rgba(255,255,255,.32); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
.pos-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.05); }
.pos-row:last-child { border-bottom: none; }
.pos-row-label { font-size: .84rem; color: rgba(255,255,255,.48); font-weight: 500; }
.pos-row-value { font-size: .88rem; color: #fff; font-weight: 600; }
.pos-total-amount { font-size: 2.3rem; font-weight: 800; color: #818cf8; letter-spacing: -1.5px; text-align: right; line-height: 1; }
.pos-total-label { font-size: .67rem; font-weight: 700; color: rgba(255,255,255,.28); text-transform: uppercase; letter-spacing: 1px; text-align: right; margin-bottom: 4px; }
.pos-divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 2px 0; }
.socio-terminal-block { background: rgba(255,255,255,.05); border-radius: 8px; padding: 10px 12px; border: 1px solid rgba(255,255,255,.07); }
.socio-terminal-active { display: flex; align-items: center; gap: 8px; }
.socio-terminal-name { flex: 1; font-size: .87rem; font-weight: 600; color: #fff; }
.socio-terminal-pill { font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: rgba(99,102,241,.25); color: #a5b4fc; }
.socio-terminal-remove { background: rgba(255,255,255,.09); border: none; color: rgba(255,255,255,.48); border-radius: 50%; width: 22px; height: 22px; cursor: pointer; font-size: .8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.socio-terminal-remove:hover { background: rgba(255,255,255,.18); }
.pos-cart-wrap { flex: 1; overflow-y: auto; }
.pos-qty-control { display: flex; align-items: center; gap: 4px; }
.pos-qty-btn { width: 30px; height: 30px; border: 1.5px solid #e2e8f0; background: #fff; border-radius: 6px; cursor: pointer; font-size: .95rem; display: flex; align-items: center; justify-content: center; color: #475569; transition: all .15s; }
.pos-qty-btn:hover { border-color: #6366f1; color: #6366f1; }
.pos-qty-input { width: 46px; text-align: center; border: 1.5px solid #e2e8f0; border-radius: 6px; padding: 5px 6px; font-size: .88rem; font-weight: 600; font-family: inherit; color: #1e293b; }
.pago-row { display: flex; gap: 8px; align-items: center; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.06); }
.pago-row:last-child { border-bottom: none; }

/* ── SEARCH DROPDOWNS ── */
#searchResults, #socioResults {
    position: absolute; z-index: 1050; width: 100%;
    background: #fff; border: 1.5px solid #e2e8f0; border-top: none;
    border-radius: 0 0 10px 10px; max-height: 280px; overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
}
#searchResults .res-item, #socioResults > div {
    padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f1f5f9;
    font-size: .88rem; transition: background .12s; color: #334155;
}
#searchResults .res-item:hover, #socioResults > div:hover { background: #f8fafc; }
#searchResults .res-item:last-child, #socioResults > div:last-child { border-bottom: none; }
#searchResults .res-sku { color: #94a3b8; font-size: .75rem; margin-top: 2px; }
.res-memb-tag { font-size: .68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; background: #eff6ff; color: #1d4ed8; }

/* ── COMPRAS ITEMS TABLE ── */
.compra-items-sub { border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 14px; }
.compra-items-sub tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
.compra-items-sub tbody tr:last-child { border-bottom: none; }
.compra-items-sub tbody tr:hover { background: #f8fafc; }
.compra-items-sub tbody td { padding: 12px 16px; }
.compra-total-row { border-left: 4px solid var(--sam-blue); padding: 12px 18px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; border-radius: 0 8px 8px 0; margin-bottom: 18px; }
.compra-item-input { font-size: .87rem; padding: 6px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px; color: #334155; background: #fff; font-family: inherit; transition: border-color .12s; }
.compra-item-input:focus { border-color: var(--sam-blue); outline: none; box-shadow: 0 0 0 3px rgba(0,61,165,.08); }

/* ── TOAST SYSTEM ── */
#toastArea { position: fixed; bottom: 24px; right: 24px; z-index: 9999; pointer-events: none; }
.sam-toast { padding: 13px 18px; border-radius: 10px; color: #fff; font-weight: 600; margin-top: 8px; box-shadow: 0 8px 24px rgba(0,0,0,.13); animation: toastIn .3s ease; min-width: 280px; font-size: .9rem; pointer-events: all; font-family: inherit; }
.sam-toast.success { background: linear-gradient(135deg, #10b981, #059669); }
.sam-toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); }
.sam-toast.warn    { background: linear-gradient(135deg, #f59e0b, #d97706); }
@keyframes toastIn { from { transform: translateX(80px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

/* ── SPINNERS ── */
.spinner-sam { display: inline-block; width: 16px; height: 16px; border: 2.5px solid rgba(0,61,165,.2); border-top-color: #003DA5; border-radius: 50%; animation: spin .6s linear infinite; }
.spinner-border-sm { display: inline-block; width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── MISC JS-REFERENCED STYLES ── */
.alert { border-radius: 8px; padding: 12px 16px; font-size: .85rem; border: 1px solid; }
.alert-info { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.btn-cobrar { background: #fbbf24 !important; color: #0f172a !important; font-weight: 800 !important; font-size: 1rem !important; letter-spacing: .1px; }
.btn-cobrar:hover { background: #f59e0b !important; box-shadow: 0 4px 16px rgba(251,191,36,.38); }
#pagoResumen { font-size: .82rem; margin-top: 6px; display: flex; justify-content: space-between; color: rgba(255,255,255,.46); }
#cambioInfo { font-size: .85rem; font-weight: 600; color: #34d399; margin-top: 4px; }
.todos-check-wrap label { font-size: .85rem; font-weight: 500; color: #475569; cursor: pointer; display: flex; align-items: center; gap: 8px; }
/* Socios detail panel */
.socio-detail-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; }
.socio-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; }
.socio-detail-item-label { color: #94a3b8; font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 3px; }
.socio-detail-item-val { font-size: .875rem; color: #334155; font-weight: 500; }
.socio-detail-cashback { color: #10b981; font-weight: 700; font-size: 1.1rem; }
.socio-detail-actions { display: flex; gap: 8px; margin-top: 16px; padding-top: 14px; border-top: 1px solid #f1f5f9; }
/* Dashboard bars */
.memb-bar-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.memb-bar-label { font-size: .8rem; font-weight: 600; color: #334155; width: 72px; flex-shrink: 0; }
.memb-bar-track { flex: 1; height: 8px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
.memb-bar-fill { height: 100%; border-radius: 10px; transition: width .6s ease; }
.memb-bar-count { font-size: .8rem; font-weight: 700; color: #94a3b8; width: 32px; text-align: right; flex-shrink: 0; }

@media (max-width: 900px) {
    .sam-main { margin-left: 0 !important; padding: 14px !important; }
    .sam-sidebar { display: none !important; }
    .pos-terminal { flex: 0 0 300px; }
}
/* JS-generated classes not expressible as Tailwind utilities */
.badge-promo { background: #d1fae5; color: #065f46; }
.stock-zero { color: #ef4444; font-weight: 700; }
.fw-600 { font-weight: 600; }

/* ── MISSING UTILITIES (used in JS-generated markup) ── */
.fw-semibold { font-weight: 600; }
.td-num { color: #94a3b8; font-size: .78rem; font-variant-numeric: tabular-nums; }
.badges-cell { display: flex; flex-wrap: wrap; gap: 4px; }
.section-title { font-size: .78rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .6px; margin: 18px 0 10px; }
.count-badge { display: inline-flex; align-items: center; background: #e2e8f0; color: #475569; font-size: .7rem; font-weight: 700; border-radius: 10px; padding: 1px 8px; }
.socio-detail-name { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 14px; }

/* ── ICON ACTION BUTTONS ── */
.cell-actions { display: flex; gap: 6px; align-items: center; }
.btn-icon { width: 30px; height: 30px; border: 1.5px solid #e2e8f0; background: #fff; border-radius: 8px; cursor: pointer; font-size: .8rem; display: inline-flex; align-items: center; justify-content: center; transition: all .15s; font-family: inherit; color: #64748b; }
.btn-icon:hover { box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.btn-icon-success { border-color: #d1fae5; color: #059669; } .btn-icon-success:hover { background: #d1fae5; border-color: #059669; }
.btn-icon-warn    { border-color: #fef3c7; color: #d97706; } .btn-icon-warn:hover    { background: #fef3c7; border-color: #d97706; }
.btn-icon-danger  { border-color: #fee2e2; color: #dc2626; } .btn-icon-danger:hover  { background: #fee2e2; border-color: #dc2626; }

/* ── POS/PAYMENT STATUS + BOOTSTRAP SHIM ── */
.pago-faltante { color: #f87171; font-weight: 600; }
.pago-ok       { color: #34d399; font-weight: 600; }
.badge-desc-ok { color: #059669; font-weight: 600; font-size: .82rem; }
.badge-desc-no { color: #94a3b8; font-size: .82rem; }
.btn { display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-family: inherit; border: none; background: transparent; padding: 0; }
.btn-sm { font-size: .82rem; padding: 3px 7px; border-radius: 6px; }

/* ── DATA CARD (Inventario grid, Promociones grid) ── */
.data-grid { display: grid; gap: 1rem; }
.data-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,.05); padding: 18px 20px; display: flex; flex-direction: column; transition: box-shadow .15s, transform .12s; }
.data-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
.data-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 10px; }
.data-card-name { font-size: .92rem; font-weight: 700; color: #1e293b; line-height: 1.35; }
.data-card-sku  { font-size: .72rem; color: #94a3b8; font-weight: 600; margin-top: 3px; letter-spacing: .3px; }
.data-card-meta { display: flex; flex-wrap: wrap; gap: 4px 12px; font-size: .8rem; color: #64748b; margin-bottom: 10px; }
.data-card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid #f1f5f9; margin-top: auto; }
.data-card-price { font-size: 1.05rem; font-weight: 800; color: var(--sam-blue); }
.promo-card-discount { font-size: 1.35rem; font-weight: 800; color: var(--sam-blue); line-height: 1.1; }
.promo-card-dates { font-size: .72rem; color: #94a3b8; margin-top: 2px; }
.stock-ok  { color: #059669; font-weight: 700; }
.stock-low { color: #d97706; font-weight: 700; }
.stock-out { color: #dc2626; font-weight: 700; }

/* ── LIST ROW (Socios, history tables, dashboard) ── */
.list-container { display: flex; flex-direction: column; gap: 8px; }
.list-row { background: #fff; border-radius: 10px; border: 1px solid #e8edf3; box-shadow: 0 1px 3px rgba(0,0,0,.04); padding: 14px 18px; display: flex; align-items: center; gap: 16px; transition: box-shadow .12s, border-color .12s; }
.list-row:hover { box-shadow: 0 3px 12px rgba(0,0,0,.07); border-color: #d0d9e8; }
.list-row-id     { font-size: .72rem; font-weight: 600; color: #94a3b8; width: 34px; flex-shrink: 0; font-variant-numeric: tabular-nums; }
.list-row-main   { flex: 1; min-width: 0; }
.list-row-name   { font-size: .9rem; font-weight: 700; color: #1e293b; word-break: break-word; line-height: 1.35; }
.list-row-sub    { font-size: .76rem; color: #94a3b8; margin-top: 1px; }
.list-row-field  { display: flex; flex-direction: column; gap: 2px; flex-shrink: 0; }
.list-row-label  { font-size: .63rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
.list-row-value  { font-size: .83rem; font-weight: 600; color: #334155; }
.list-row-amount { font-size: .97rem; font-weight: 800; color: var(--sam-blue); flex-shrink: 0; }
.list-row-actions { flex-shrink: 0; }

/* ── SOCIOS DETAIL PANEL BUTTONS (replace Bootstrap btn classes) ── */
.btn-detail-primary { background: #003DA5; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background .15s; }
.btn-detail-primary:hover { background: #002f80; }
.btn-detail-danger  { background: #fff; color: #dc2626; border: 1.5px solid #fee2e2; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all .15s; }
.btn-detail-danger:hover  { background: #fee2e2; }
.btn-detail-link { background: transparent; border: none; color: #003DA5; font-size: .85rem; font-weight: 600; cursor: pointer; padding: 0; font-family: inherit; text-decoration: underline; }
.detail-fam-list { display: flex; flex-direction: column; gap: 6px; margin-top: 8px; }
.detail-fam-row  { display: flex; align-items: center; gap: 12px; background: #f8fafc; border-radius: 8px; padding: 10px 14px; border: 1px solid #f1f5f9; }

@media (max-width: 768px) {
    .list-row { flex-wrap: wrap; gap: 10px; }
    .list-row-main { min-width: 60%; }
    .data-grid { grid-template-columns: 1fr !important; }
}

/* ── NUEVO PRODUCTO MODAL ── */
#nuevoProductoOverlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); z-index: 1000;
    align-items: center; justify-content: center; padding: 16px;
}
#nuevoProductoOverlay.active { display: flex; }
#nuevoProductoCard {
    background: #fff; border-radius: 16px; padding: 28px;
    width: 100%; max-width: 680px; max-height: 90vh; overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
}
.np-close-btn {
    background: #f1f5f9; border: none; color: #64748b; border-radius: 8px;
    width: 32px; height: 32px; cursor: pointer; font-size: .9rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: inherit;
}
.np-close-btn:hover { background: #e2e8f0; color: #334155; }
.np-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.np-form-grid .np-full { grid-column: 1 / -1; }
.np-section-title { font-size: .68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; }

/* ── SIDEBAR BRAND HEADER ── */
.sidebar-brand {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 22px 20px 18px;
    overflow: hidden;
}
.sidebar-brand-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 90% 80% at 50% 5%, rgba(0,61,165,.22) 0%, transparent 65%);
    pointer-events: none;
}
.sidebar-brand-rule {
    position: absolute;
    bottom: 0; left: 16px; right: 16px;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,61,165,.55) 30%, rgba(99,102,241,.4) 65%, transparent);
}
.brand-icon-box {
    width: 46px; height: 46px;
    background: linear-gradient(150deg, rgba(0,61,165,.28) 0%, rgba(15,23,42,.85) 100%);
    border: 1px solid rgba(0,61,165,.32);
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 11px;
    animation: brand-icon-in .5s cubic-bezier(.34,1.56,.64,1) both,
               brand-glow 4s .7s ease-in-out infinite;
}
.brand-icon-box img {
    width: 27px; height: 27px; object-fit: contain;
    filter: brightness(1.05);
}
.brand-name-row {
    display: flex; align-items: baseline; gap: 5px;
    animation: brand-text-in .4s .13s ease both;
    opacity: 0;
}
.brand-name-sams {
    font-size: .83rem; font-weight: 400;
    color: rgba(148,163,184,.85);
    letter-spacing: .01em;
}
.brand-name-admin {
    font-size: .95rem; font-weight: 700;
    color: #fff;
    letter-spacing: -.01em;
}
.brand-sub-label {
    font-size: .6rem;
    color: rgba(100,116,139,.65);
    text-transform: uppercase;
    letter-spacing: .17em;
    margin-top: 4px;
    animation: brand-text-in .4s .24s ease both;
    opacity: 0;
}
@keyframes brand-icon-in {
    from { opacity: 0; transform: scale(.75) translateY(-5px); }
    to   { opacity: 1; transform: scale(1)   translateY(0); }
}
@keyframes brand-text-in {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes brand-glow {
    0%,100% { box-shadow: 0 0 0   0px rgba(0,61,165,.0); }
    50%      { box-shadow: 0 0 18px 2px rgba(0,61,165,.22); }
}
@media (prefers-reduced-motion: reduce) {
    .brand-icon-box, .brand-name-row, .brand-sub-label { animation: none; opacity: 1; }
}
    </style>
</head>
<body class="bg-[#F5F7FA] text-slate-900 antialiased">
<div id="toastArea"></div>

<!-- ═══ SIDEBAR ═══ -->
<aside class="sam-sidebar fixed inset-y-0 left-0 w-56 bg-slate-900 flex flex-col z-50">
    <div class="sidebar-brand">
        <div class="sidebar-brand-bg"></div>
        <div class="sidebar-brand-rule"></div>
        <div class="brand-icon-box">
            <img src="img/sams_logo.png" alt="Sam's">
        </div>
        <div class="brand-name-row">
            <span class="brand-name-sams">Sam's</span>
            <span class="brand-name-admin">Admin</span>
        </div>
        <div class="brand-sub-label">Panel de Control</div>
    </div>
    <nav class="flex-1 py-3">
        <div class="px-4 mb-1.5 text-xs font-semibold text-slate-600 uppercase tracking-widest">General</div>
        <div class="sam-nav-item active" data-section="dashboard" onclick="showSection('dashboard')">
            <i data-lucide="layout-dashboard"></i> Dashboard
        </div>
        <div class="px-4 mt-4 mb-1.5 text-xs font-semibold text-slate-600 uppercase tracking-widest">Gestión</div>
        <div class="sam-nav-item" data-section="inventario" onclick="showSection('inventario')">
            <i data-lucide="package"></i> Inventario
        </div>
        <div class="sam-nav-item" data-section="socios" onclick="showSection('socios')">
            <i data-lucide="users"></i> Socios
        </div>
        <div class="sam-nav-item" data-section="promociones" onclick="showSection('promociones')">
            <i data-lucide="tag"></i> Promociones
        </div>
        <div class="px-4 mt-4 mb-1.5 text-xs font-semibold text-slate-600 uppercase tracking-widest">Operaciones</div>
        <div class="sam-nav-item" data-section="compras" onclick="showSection('compras')">
            <i data-lucide="shopping-cart"></i> Compras
        </div>
        <div class="sam-nav-item" data-section="ventas" onclick="showSection('ventas')">
            <i data-lucide="credit-card"></i> Punto de Venta
        </div>
    </nav>
    <div class="flex items-center gap-2.5 px-5 py-4 border-t border-slate-700/40">
        <span class="sam-status-dot w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0" style="box-shadow:0 0 0 3px rgba(52,211,153,.18);animation:pulse-dot 2.5s ease-in-out infinite;"></span>
        <span class="text-xs text-slate-500 uppercase tracking-wider">Sistema activo</span>
    </div>
</aside>
<style>@keyframes pulse-dot{0%,100%{box-shadow:0 0 0 3px rgba(52,211,153,.18)}50%{box-shadow:0 0 0 6px rgba(52,211,153,.06)}}</style>

<!-- ═══ MAIN ═══ -->
<main class="ml-56 min-h-screen bg-[#F5F7FA] px-10 py-9">
    <div class="flex items-center justify-between mb-9 pb-6 border-b border-slate-200">
        <h1 id="page-title" class="text-xl font-bold text-slate-900 tracking-tight">Dashboard</h1>
        <div class="flex items-center gap-3">
            <span id="page-date" class="text-xs font-medium text-slate-500 bg-white border border-slate-200 rounded-full px-3 py-1.5"></span>
            <button onclick="refreshCurrentSection()" class="flex items-center gap-1.5 text-sm font-medium text-slate-600 border border-slate-200 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Actualizar
            </button>
        </div>
    </div>

<!-- ═══ DASHBOARD ═══ -->
<div class="sam-section active" id="section-dashboard">
    <!-- Stat cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm border-t-4 border-t-sams-blue">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Socios activos</p>
            <p id="dash-socios" class="text-3xl font-bold text-slate-900 tracking-tight">—</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm border-t-4 border-t-emerald-500">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Ingresos hoy</p>
            <p id="dash-ingresos" class="text-3xl font-bold text-slate-900 tracking-tight">—</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm border-t-4 border-t-rose-500">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sin existencia</p>
            <p id="dash-sinstock" class="text-3xl font-bold text-slate-900 tracking-tight">—</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm border-t-4 border-t-amber-400">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Promos activas</p>
            <p id="dash-promos" class="text-3xl font-bold text-slate-900 tracking-tight">—</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-7 gap-5 mb-8">
        <!-- Ventas recientes -->
        <div class="md:col-span-4 bg-white rounded-xl border border-slate-200 shadow-sm p-7">
            <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Ventas Recientes</p>
            <div id="dash-ventas-list" class="list-container mt-1 min-h-[80px]">
                <div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Cargando…</div>
            </div>
            <div class="mt-4">
                <button onclick="showSection('ventas')" class="text-sm font-medium text-sams-blue hover:text-sams-blue/80 transition-colors">Ver todas las ventas →</button>
            </div>
        </div>

        <!-- Membresías breakdown -->
        <div class="md:col-span-3 bg-white rounded-xl border border-slate-200 shadow-sm p-7">
            <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Distribución de Membresías</p>
            <div id="dash-memb-bars">
                <div class="text-center text-slate-400 text-sm py-4">Cargando…</div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-7">
        <p class="text-sm font-semibold text-slate-800 mb-4">Accesos Rápidos</p>
        <div class="flex flex-wrap gap-3">
            <button onclick="showSection('ventas'); setTimeout(()=>document.getElementById('posSearch')?.focus(),100)"
                class="flex items-center gap-2 bg-sams-blue hover:bg-sams-blue/90 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                Nueva Venta
            </button>
            <button onclick="showSection('socios'); setTimeout(()=>document.getElementById('socioNombre')?.focus(),100)"
                class="flex items-center gap-2 border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                Nuevo Socio
            </button>
            <button onclick="showSection('compras'); setTimeout(()=>document.getElementById('compraProveedor')?.focus(),100)"
                class="flex items-center gap-2 border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                Registrar Compra
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 1 · INVENTARIO
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-inventario">
    <div class="flex items-center gap-3 mb-5">
        <div class="relative flex-1" style="max-width:420px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;"></svg>
            <input type="text" id="invSearch"
                oninput="clearTimeout(invTimer);invTimer=setTimeout(()=>loadInventario(this.value),350)"
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition bg-white"
                placeholder="Buscar por nombre, SKU o marca…">
        </div>
        <span id="invCount" class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full" style="display:none;"></span>
        <button onclick="abrirNuevoProducto()"
            style="background:var(--sam-blue);"
            class="flex items-center gap-2 hover:opacity-90 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-opacity flex-shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i> Nuevo Producto
        </button>
    </div>
    <div id="invGrid" class="data-grid" style="grid-template-columns:repeat(auto-fill,minmax(270px,1fr));">
        <div style="grid-column:1/-1;text-align:center;padding:32px 0;"><div class="spinner-sam"></div></div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 2 · SOCIOS
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-socios">
    <div class="flex gap-6" style="align-items:flex-start;">
        <!-- Left: Form panel -->
        <div class="flex-shrink-0" style="width:330px;">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 border-t-4 border-t-sams-blue">
                <div id="socioFormNuevo">
                    <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Nuevo Socio Titular</p>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Nombre completo</label>
                        <input type="text" id="socioNombre" autocomplete="off" placeholder="Ej: Juan Pérez García"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Correo electrónico <span class="font-normal normal-case tracking-normal text-slate-400">(opcional)</span></label>
                        <input type="email" id="socioCorreo" placeholder="correo@ejemplo.com"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Teléfono <span class="font-normal normal-case tracking-normal text-slate-400">(opcional)</span></label>
                        <input type="tel" id="socioTelefono" placeholder="55 1234 5678"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                    <div class="border-t border-slate-100 pt-4 mb-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Membresía</p>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Tipo de membresía</label>
                            <select id="socioTipoMembresia"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                                <option value="">Seleccionar tipo…</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Fecha de vencimiento</label>
                            <input type="date" id="socioFechaFin"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                    </div>
                    <button onclick="crearSocioTitular()"
                        class="w-full bg-sams-blue hover:bg-sams-blue/90 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors mb-2">
                        Registrar Socio Titular
                    </button>
                    <button onclick="mostrarFormFamiliar()"
                        class="w-full border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium py-2.5 rounded-lg transition-colors">
                        Vincular un familiar existente
                    </button>
                </div>
                <div id="socioFormFamiliar" style="display:none;">
                    <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Vincular Familiar</p>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Socio Titular</label>
                        <select id="socioTitularSel"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Seleccionar titular…</option>
                        </select>
                    </div>
                    <div class="border-t border-slate-100 pt-4 mb-4">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Datos del Familiar</p>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Nombre completo</label>
                            <input type="text" id="familiarNombre" placeholder="Nombre completo"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Correo <span class="font-normal normal-case tracking-normal text-slate-400">(opcional)</span></label>
                            <input type="email" id="familiarCorreo" placeholder="correo@ejemplo.com"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Teléfono <span class="font-normal normal-case tracking-normal text-slate-400">(opcional)</span></label>
                            <input type="tel" id="familiarTelefono" placeholder="55 1234 5678"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Parentesco</label>
                            <select id="familiarParentesco"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                                <option value="CONYUGE">Cónyuge</option>
                                <option value="HIJO">Hijo/a</option>
                                <option value="PADRE">Padre/Madre</option>
                                <option value="HERMANO">Hermano/a</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-slate-600">
                                <input type="checkbox" id="familiarComplementaria" class="rounded accent-[#003DA5]">
                                Tarjeta complementaria gratis
                            </label>
                            <p class="text-xs text-slate-400 mt-1 ml-5">Solo se permite 1 por titular</p>
                        </div>
                    </div>
                    <button onclick="crearSocioFamiliar()"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors mb-2">
                        Vincular Familiar
                    </button>
                    <button onclick="mostrarFormNuevo()"
                        class="w-full border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium py-2.5 rounded-lg transition-colors">
                        ← Volver
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: List panels -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center mb-4">
                <input type="text" id="sociosBuscar" placeholder="Buscar socio por nombre o número…"
                    class="text-sm border border-slate-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white"
                    style="max-width:280px;">
                <button onclick="loadSocios(); loadFamiliares();"
                    class="border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium px-3 py-2 rounded-lg transition-colors">
                    ↺ Actualizar
                </button>
            </div>
            <div class="flex gap-2 mb-4">
                <div class="pill-tab active" onclick="showSocioTab('listado', this)">Titulares</div>
                <div class="pill-tab" onclick="showSocioTab('familiares', this)">Familiares</div>
                <div class="pill-tab" onclick="showSocioTab('detalles', this)">Detalles</div>
            </div>
            <div id="socios-listado">
                <div id="sociosBody" class="list-container">
                    <div style="text-align:center;padding:24px 0;"><div class="spinner-sam"></div></div>
                </div>
            </div>
            <div id="socios-familiares" style="display:none;">
                <div class="flex gap-2 mb-3">
                    <input type="text" id="familiarBuscar" placeholder="Buscar familiar…"
                        class="text-sm border border-slate-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white"
                        style="max-width:300px;">
                    <button onclick="loadFamiliares()"
                        class="border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm px-3 py-2 rounded-lg transition-colors">↺</button>
                </div>
                <div id="familiaresBody" class="list-container">
                    <div style="text-align:center;padding:24px 0;"><div class="spinner-sam"></div></div>
                </div>
            </div>
            <div id="socios-detalles" style="display:none;">
                <div id="socioDetallesContent" class="text-center text-slate-400 text-sm py-8">
                    Selecciona un socio para ver los detalles
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 3 · PROMOCIONES
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-promociones">
    <div class="flex gap-6" style="align-items:flex-start;">
        <!-- Left: New promo form -->
        <div class="flex-shrink-0" style="width:370px;">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 border-t-4 border-t-sams-blue">
                <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Nueva Promoción</p>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Producto</label>
                    <select id="promoProducto"
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                        <option value="">Cargando…</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Nombre de la promoción</label>
                    <input type="text" id="promoNombre" placeholder="Ej: Promo Verano 2026"
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                </div>
                <div class="border-t border-slate-100 pt-4 mb-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Tipo de descuento — elige uno</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Descuento %</label>
                            <div class="relative">
                                <input type="number" id="promoDescPct" placeholder="0" min="0" max="100" step="0.01"
                                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 pr-8 outline-none focus:ring-2 focus:ring-sams-blue transition">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Descuento $</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">$</span>
                                <input type="number" id="promoDescMonto" placeholder="0.00" min="0" step="0.01"
                                    class="w-full text-sm border border-slate-300 rounded-lg pl-7 pr-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4 mb-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Vigencia</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Inicio</label>
                            <input type="date" id="promoFechaIni"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Fin</label>
                            <input type="date" id="promoFechaFin"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-3 mb-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Elegibilidad</p>
                    <label class="flex items-start gap-2 cursor-pointer text-sm font-medium text-slate-600 mb-2">
                        <input type="checkbox" id="promoAplicaTodos" onchange="toggleTodosCheck()" class="mt-0.5 accent-[#003DA5]">
                        <span>Aplica a todos los socios <span class="text-slate-400 font-normal">(sin importar membresía)</span></span>
                    </label>
                    <div id="membresiaChecks" class="flex flex-wrap gap-2"></div>
                    <p class="text-xs text-slate-400 mt-2">Si no marcas "todos", solo los tipos seleccionados verán el descuento.</p>
                </div>
                <button onclick="crearPromo()"
                    class="w-full bg-sams-blue hover:bg-sams-blue/90 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                    Registrar Promoción
                </button>
            </div>
        </div>

        <!-- Right: Promos table -->
        <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm font-semibold text-slate-800">Promociones Registradas</p>
                <button onclick="loadPromos()"
                    class="border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium px-3 py-2 rounded-lg transition-colors">
                    ↺ Actualizar
                </button>
            </div>
            <div id="promoBody" class="data-grid" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
                <div style="grid-column:1/-1;text-align:center;padding:32px 0;"><div class="spinner-sam"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 4 · COMPRAS
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-compras">
    <div class="flex gap-6 mb-6" style="align-items:flex-start;">
        <!-- Form card — 2/3 of the row -->
        <div style="flex:2;min-width:0;" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 border-t-4 border-t-sams-blue">
                <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Registrar Recepción de Mercancía</p>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Proveedor</label>
                    <select id="compraProveedor"
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                        <option value="">Seleccionar proveedor…</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Zona de destino</label>
                        <select id="compraZona"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Seleccionar zona…</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Destino</label>
                        <select id="compraEsReserva"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="0">Piso de venta</option>
                            <option value="1">Bodega / Reserva</option>
                        </select>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 mb-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Agregar producto a la lista</p>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Producto</label>
                        <select id="compraProductoSel"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Seleccionar producto…</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Cantidad</label>
                            <input type="number" id="compraCantidad" placeholder="1" min="1"
                                class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Precio de costo</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">$</span>
                                <input type="number" id="compraPrecio" placeholder="0.00" step="0.01" min="0"
                                    class="w-full text-sm border border-slate-300 rounded-lg pl-7 pr-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition">
                            </div>
                        </div>
                    </div>
                    <button onclick="agregarItemCompra()"
                        class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors mb-4">
                        + Agregar producto a la lista
                    </button>
                </div>
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Items Agregados</p>
                <div class="compra-items-sub">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-800 text-white">
                            <tr>
                                <th class="text-left text-xs font-semibold uppercase tracking-wider px-4 py-3">Producto</th>
                                <th class="text-center text-xs font-semibold uppercase tracking-wider px-4 py-3" style="width:90px;">Cant.</th>
                                <th class="text-center text-xs font-semibold uppercase tracking-wider px-4 py-3" style="width:120px;">Precio costo</th>
                                <th class="text-right text-xs font-semibold uppercase tracking-wider px-4 py-3" style="width:100px;">Total</th>
                                <th class="px-4 py-3" style="width:42px;"></th>
                            </tr>
                        </thead>
                        <tbody id="compraItemsBody">
                            <tr id="compraEmptyRow"><td colspan="5" class="text-center text-slate-400 py-4">Sin productos agregados</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="compra-total-row">
                    <span class="text-sm font-semibold text-slate-700" id="compraTotalUnidades">Total: 0 unidades</span>
                    <span class="text-base font-bold text-sams-blue" id="compraTotalCosto">$0.00</span>
                </div>
                <button onclick="procesarCompra()"
                    class="w-full bg-sams-blue hover:bg-sams-blue/90 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">
                    Registrar Compra
                </button>
            </div>

        <!-- Proveedor card — 1/3 of the row -->
        <div style="flex:1;min-width:240px;" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 border-t-4 border-t-sams-blue">
            <p class="text-sm font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">Añadir Proveedor</p>
            <form onsubmit="guardarNuevoProveedor(event)">
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" id="nuevoProveedorNombre" required
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition"
                        placeholder="Ej. Nestlé México">
                </div>
                <button type="submit" id="btnGuardarProveedor"
                    style="background:var(--sam-blue);"
                    class="w-full hover:opacity-90 text-white text-sm font-semibold py-2.5 rounded-lg transition-opacity">
                    + Añadir Proveedor
                </button>
            </form>
            <div id="proveedorListWrap" class="mt-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Proveedores registrados</p>
                <div id="proveedorListBody" class="flex flex-col gap-1.5"></div>
            </div>
        </div><!-- /proveedor card -->

    </div><!-- /flex row -->

        <!-- Recepciones Recientes — full width below the form -->
        <p class="text-sm font-semibold text-slate-800 mb-3">Recepciones Recientes</p>
        <div id="histCompraBody" class="list-container" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(400px,1fr));gap:8px;">
            <div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Cargando…</div>
        </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 5 · PUNTO DE VENTA
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-ventas">
<div class="pos-wrap">
    <!-- LEFT: Light product zone -->
    <div class="pos-light">
        <div class="flex gap-3 flex-shrink-0">
            <div style="flex:0 0 175px;">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Canal de venta</label>
                <select id="posCanal"
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                    <option value="CAJA">Caja</option>
                    <option value="SELF">Self-checkout</option>
                    <option value="SCAN_GO">Scan &amp; Go</option>
                </select>
            </div>
            <div class="flex-1 relative">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Buscar producto</label>
                <input type="text" id="posSearch" oninput="buscarProductoPos()" autocomplete="off"
                    placeholder="Nombre, SKU o marca…"
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                <div id="searchResults"></div>
            </div>
        </div>
        <div class="pos-cart-wrap bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="text-left text-xs font-semibold uppercase tracking-wider px-4 py-3">Producto</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider px-4 py-3">Precio U.</th>
                            <th class="text-center text-xs font-semibold uppercase tracking-wider px-4 py-3" style="width:130px;">Cantidad</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider px-4 py-3">Desc.</th>
                            <th class="text-right text-xs font-semibold uppercase tracking-wider px-4 py-3">Subtotal</th>
                            <th class="px-4 py-3" style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="posCartBody">
                        <tr><td colspan="6" class="text-center text-slate-400 py-8">Busca un producto para comenzar la venta</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: Dark terminal -->
    <div class="pos-terminal">
        <div class="pos-terminal-header">
            <img src="img/sams_logo.png" alt="Sam's">
            <span>Resumen de Venta</span>
        </div>
        <div class="pos-terminal-body">
            <div>
                <div class="pos-label">Asociar Socio (opcional)</div>
                <div class="socio-terminal-block">
                    <div id="socioInputWrap" style="position:relative;">
                        <input type="text" id="posSearchSocio" class="input-dark"
                            placeholder="Nombre o número de socio…" oninput="buscarSocio()">
                        <div id="socioResults"></div>
                    </div>
                    <div id="socioSeleccionado" class="mt-2 d-none">
                        <div class="socio-terminal-active">
                            <span style="font-size:.9rem;">👤</span>
                            <span class="socio-terminal-name" id="socioLabel"></span>
                            <span class="socio-terminal-pill" id="socioMembPill"></span>
                            <button class="socio-terminal-remove" onclick="quitarSocio()" title="Quitar">✕</button>
                        </div>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.36);margin-top:5px;padding-left:4px;" id="socioMembInfo"></div>
                    </div>
                </div>
            </div>
            <div>
                <div class="pos-row"><span class="pos-row-label">Subtotal</span><span class="pos-row-value" id="posSubtotal">$0.00</span></div>
                <div class="pos-row"><span class="pos-row-label">Descuentos</span><span style="color:#f87171;font-weight:600;" id="posDescuentos">−$0.00</span></div>
            </div>
            <div>
                <div class="pos-total-label">TOTAL A PAGAR</div>
                <div class="pos-total-amount" id="posTotal">$0.00</div>
            </div>
            <hr class="pos-divider">
            <div>
                <div class="pos-label">Método de Pago</div>
                <div id="pagosContainer"></div>
                <button onclick="agregarPago()"
                    style="width:100%;margin-top:6px;padding:8px;background:rgba(255,255,255,.07);color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.1);border-radius:8px;cursor:pointer;font-size:.82rem;font-weight:600;font-family:inherit;">
                    + Agregar método de pago
                </button>
                <div id="pagoResumen" class="mt-2 d-none">
                    <span>Pagado: <strong id="pagoTotalIngresado">$0.00</strong></span>
                    <span id="pagoEstado"></span>
                </div>
                <div id="cambioInfo" class="mt-2 d-none"></div>
            </div>
        </div>
        <div class="pos-terminal-footer">
            <button onclick="cancelarVenta()"
                style="width:100%;padding:10px;background:transparent;color:#f87171;border:1.5px solid rgba(248,113,113,.35);border-radius:8px;cursor:pointer;font-size:.88rem;font-weight:600;font-family:inherit;transition:all .15s;"
                onmouseover="this.style.background='rgba(248,113,113,.1)'" onmouseout="this.style.background='transparent'">
                Cancelar venta
            </button>
            <button class="btn-cobrar w-100"
                style="padding:13px;border:none;border-radius:8px;cursor:pointer;transition:all .15s;display:block;width:100%;font-family:inherit;"
                onclick="procesarVenta()">✓ COBRAR</button>
        </div>
    </div>
</div>

<!-- Ventas history below POS -->
<div class="mt-6">
    <div class="flex justify-between items-center mb-3">
        <p class="text-sm font-semibold text-slate-800">Ventas Recientes</p>
        <button onclick="loadHistVentas()"
            class="border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium px-3 py-2 rounded-lg transition-colors">
            ↺ Actualizar
        </button>
    </div>
    <div id="histVentasBody" class="list-container">
        <div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Cargando…</div>
    </div>
</div>
</div>

</main><!-- /sam-main -->

<div id="toastArea"></div>
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
window.addEventListener('error', (e) => { console.error('ERROR:', e.error); toast('Error: ' + (e.error?.message || 'Desconocido'), 'error'); });
window.addEventListener('unhandledrejection', (e) => { console.error('PROMISE:', e.reason); toast('Error: ' + (e.reason?.message || 'Desconocido'), 'error'); });
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
        console.error('API Error:', err.message);
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
    const ventasList = document.getElementById('dash-ventas-list');
    if (ventasRes.success && ventasRes.data.length) {
        const now = new Date();
        const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
        let todayTotal = 0;
        ventasRes.data.forEach(v => {
            if (v.fecha && v.fecha.startsWith(todayStr)) todayTotal += parseFloat(v.total || 0);
        });
        document.getElementById('dash-ingresos').textContent = fmt(todayTotal);
        ventasList.innerHTML = ventasRes.data.slice(0, 5).map(v =>
            `<div class="list-row">
                <div class="list-row-id">#${esc(String(v.id))}</div>
                <div class="list-row-main">
                    <div class="list-row-name">${v.socio ? esc(v.socio) : 'Venta directa'}</div>
                    <div class="list-row-sub">${esc(v.fecha || '—')}</div>
                </div>
                <span class="badge badge-blue">${esc(v.canal || '—')}</span>
                <div class="list-row-amount">${fmt(v.total)}</div>
            </div>`
        ).join('');
    } else {
        ventasList.innerHTML = '<div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Sin ventas registradas</div>';
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
    const grid = document.getElementById('invGrid');
    if (!grid) return;
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:32px 0;"><div class="spinner-sam"></div></div>';
    const res = await api('inventario.php?action=list&q=' + encodeURIComponent(q));
    if (!res.success) { toast(res.error,'error'); return; }
    const data = res.data;
    if (!data.length) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:40px 0;font-size:.9rem;">Sin resultados</div>';
    } else {
        grid.innerHTML = data.map(p => {
            const sc = p.stock_piso == 0 ? 'stock-out' : p.stock_piso <= 5 ? 'stock-low' : 'stock-ok';
            const promoTag = p.promo_nombre
                ? `<span class="badge badge-promo">${p.descuento_pct > 0 ? Number(p.descuento_pct)+'% off' : '$'+Number(p.descuento_monto)+' off'}</span>`
                : '';
            return `<div class="data-card">
                <div class="data-card-header">
                    <div style="min-width:0;">
                        <div class="data-card-name">${esc(p.nombre)}${p.es_members_mark == 1 ? ' <span class="badge badge-gold" style="font-size:.65rem;vertical-align:middle;">M\'s Mark</span>' : ''}</div>
                        <div class="data-card-sku">${esc(p.sku)}</div>
                    </div>
                    ${promoTag}
                </div>
                <div class="data-card-meta">
                    <span style="font-weight:600;">${esc(p.marca)}</span>
                    ${p.categoria ? `<span>${esc(p.categoria)}</span>` : ''}
                </div>
                <div class="data-card-footer">
                    <div style="display:flex;gap:18px;">
                        <div class="list-row-field">
                            <span class="list-row-label">Stock Piso</span>
                            <span class="${sc}" style="font-size:.9rem;">${Number(p.stock_piso)}</span>
                        </div>
                        <div class="list-row-field">
                            <span class="list-row-label">Reserva</span>
                            <span class="list-row-value">${Number(p.stock_reserva)}</span>
                        </div>
                    </div>
                    <div class="data-card-price">${fmt(p.precio_actual)}</div>
                </div>
            </div>`;
        }).join('');
    }
    const countEl = document.getElementById('invCount');
    if (countEl) { countEl.textContent = data.length + ' producto' + (data.length !== 1 ? 's' : ''); countEl.style.display = data.length ? 'inline-flex' : 'none'; }
}

// ── NUEVO PRODUCTO MODAL ──────────────────────────────
let _npOpcionesLoaded = false;

function abrirNuevoProducto() {
    document.getElementById('nuevoProductoOverlay').classList.add('active');
    document.getElementById('nuevoProductoForm').reset();
    if (!_npOpcionesLoaded) cargarOpcionesFormProducto();
    lucide.createIcons();
}

function cerrarNuevoProducto() {
    document.getElementById('nuevoProductoOverlay').classList.remove('active');
}

async function cargarOpcionesFormProducto() {
    const [catRes, provRes] = await Promise.all([
        api('inventario.php?action=categorias'),
        api('inventario.php?action=proveedores'),
    ]);
    const catSel  = document.getElementById('npCategoria');
    const provSel = document.getElementById('npProveedor');
    if (catRes.success)  catRes.data.forEach(c => catSel.add(new Option(c.nombre, c.id)));
    if (provRes.success) provRes.data.forEach(p => provSel.add(new Option(p.nombre, p.id)));
    _npOpcionesLoaded = true;
}

async function guardarNuevoProducto(e) {
    e.preventDefault();
    const form = document.getElementById('nuevoProductoForm');
    const btn  = form.querySelector('[type=submit]');
    btn.disabled = true; btn.textContent = 'Guardando…';
    const data = Object.fromEntries(new FormData(form));
    const res  = await api('inventario.php?action=create', data);
    btn.disabled = false; btn.textContent = 'Guardar Producto';
    if (res.success) {
        toast('Producto creado correctamente', 'success');
        cerrarNuevoProducto();
        loadInventario(document.getElementById('invSearch').value);
    } else {
        toast(res.error || 'Error al crear producto', 'error');
    }
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
    if (!container) return;
    container.innerHTML = tiposMembresia.map(tm =>
        `<label class="memb-pill-toggle" id="lbl-memb-${tm.id}" onclick="toggleMembCheck(${Number(tm.id)}, ${JSON.stringify(tm.nombre)})">
            <input type="checkbox" id="chk-memb-${tm.id}" value="${Number(tm.id)}">
            <span>${esc(tm.nombre)}</span>
        </label>`
    ).join('');
}

function toggleMembCheck(id, nombre) {
    const chk = document.getElementById(`chk-memb-${id}`);
    const lbl = document.getElementById(`lbl-memb-${id}`);
    if (!chk || !lbl) return;
    chk.checked = !chk.checked;
    if (chk.checked) lbl.classList.add(`active-${nombre}`);
    else lbl.classList.remove(`active-${nombre}`);
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
            if (lbl) lbl.className = 'memb-pill-toggle';
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
    body.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:32px 0;"><div class="spinner-sam"></div></div>';
    const res = await api('promociones.php?action=list_promos');
    if (!res.success) { toast(res.error,'error'); return; }
    if (!res.data.length) {
        body.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:40px 0;font-size:.9rem;">Sin promociones registradas</div>';
        return;
    }
    body.innerHTML = res.data.map(r => {
        const isActivo = r.activo == 1;
        const statusBadge = isActivo
            ? '<span class="badge badge-green" style="display:inline-flex;align-items:center;gap:4px;"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>Activa</span>'
            : '<span class="badge badge-gray" style="display:inline-flex;align-items:center;gap:4px;"><span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span>Inactiva</span>';
        let membTag = '';
        if (r.aplica_a_todos == '1') {
            membTag = '<span class="badge badge-blue">Todos</span>';
        } else if (r.membresias_aplicables) {
            const badges = r.membresias_aplicables.split(', ').map(m => {
                const cls = m === 'PLUS' ? 'badge-purple' : (m === 'BENEFITS' ? 'badge-blue' : 'badge-gray');
                return `<span class="badge ${cls}">${esc(m)}</span>`;
            }).join('');
            membTag = `<div class="badges-cell">${badges}</div>`;
        } else {
            membTag = '<span style="color:#94a3b8;">—</span>';
        }
        const discText = Number(r.descuento_pct) > 0
            ? `${Number(r.descuento_pct)}% off`
            : `${fmt(r.descuento_monto)} off`;
        return `<div class="data-card">
            <div class="data-card-header">
                <div style="min-width:0;">
                    <div class="data-card-name">${esc(r.nombre_promo)}</div>
                    <div class="data-card-sku">${esc(r.producto_nombre)} · ${esc(r.sku)}</div>
                </div>
                ${statusBadge}
            </div>
            <div style="margin-bottom:10px;">
                <div class="promo-card-discount">${discText}</div>
                <div class="promo-card-dates">Vigencia: ${esc(r.fecha_inicio||'—')} → ${esc(r.fecha_fin||'—')}</div>
            </div>
            <div style="margin-bottom:10px;">${membTag}</div>
            <div class="data-card-footer">
                <div class="cell-actions">
                    <button class="btn-icon ${isActivo ? 'btn-icon-warn' : 'btn-icon-success'}" title="${isActivo ? 'Desactivar' : 'Activar'}" onclick="togglePromo(${Number(r.id)})">${isActivo ? '⏸' : '▶'}</button>
                    <button class="btn-icon btn-icon-danger" title="Eliminar promoción" onclick="deletePromo(${Number(r.id)})">✕</button>
                </div>
                <span class="list-row-label" style="font-variant-numeric:tabular-nums;">#${Number(r.id)}</span>
            </div>
        </div>`;
    }).join('');
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
    if ((pct > 0 && monto > 0) || (pct === 0 && monto === 0)) { toast('Ingresa SOLO descuento por % O por $, no ambos ni ninguno','error'); return; }

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
            if (lbl) lbl.className = 'memb-pill-toggle';
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
        renderProveedorList(prov.data);
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

async function guardarNuevoProveedor(e) {
    e.preventDefault();
    const nombre = document.getElementById('nuevoProveedorNombre').value.trim();
    if (!nombre) return;
    const btn = document.getElementById('btnGuardarProveedor');
    btn.disabled = true; btn.textContent = 'Guardando…';
    const res = await api('compras.php?action=crear_proveedor', { nombre });
    btn.disabled = false; btn.textContent = '+ Añadir Proveedor';
    if (res.success) {
        toast('Proveedor añadido correctamente', 'success');
        document.getElementById('nuevoProveedorNombre').value = '';
        // Reload proveedor select + sidebar list
        const prov = await api('compras.php?action=list_proveedores');
        if (prov.success) {
            const sel = document.getElementById('compraProveedor');
            sel.innerHTML = '<option value="">Seleccionar proveedor…</option>';
            prov.data.forEach(p => sel.innerHTML += `<option value="${p.id}">${p.nombre}</option>`);
            renderProveedorList(prov.data);
        }
    } else {
        toast(res.error || 'Error al añadir proveedor', 'error');
    }
}

function renderProveedorList(proveedores) {
    const body = document.getElementById('proveedorListBody');
    if (!body) return;
    if (!proveedores.length) {
        body.innerHTML = '<p style="font-size:.8rem;color:#94a3b8;">Sin proveedores registrados</p>';
        return;
    }
    body.innerHTML = proveedores.map(p =>
        `<div style="font-size:.82rem;color:#334155;background:#f8fafc;border:1px solid #e8edf3;border-radius:7px;padding:7px 12px;font-weight:500;">${esc(p.nombre)}</div>`
    ).join('');
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
        return `<tr>
            <td>
                <div style="font-size:.88rem;font-weight:700;color:#1e293b;">${esc(it.nombre)}</div>
                ${it.sku ? `<div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">${esc(it.sku)}</div>` : ''}
            </td>
            <td class="text-center"><input type="number" class="compra-item-input" value="${Number(it.cantidad)}" min="1" step="1" style="width:76px;" onchange="updateCompraItem(${i},'cantidad',this.value)" oninput="updateCompraItem(${i},'cantidad',this.value)"></td>
            <td class="text-center"><input type="number" class="compra-item-input" value="${it.precio_costo || ''}" min="0" step="0.01" placeholder="0.00" style="width:92px;" onchange="updateCompraItem(${i},'precio_costo',this.value)" oninput="updateCompraItem(${i},'precio_costo',this.value)"></td>
            <td class="text-end" style="font-weight:700;color:var(--sam-blue);font-size:.93rem;" id="sub-compra-${i}">${sub > 0 ? fmt(sub) : '—'}</td>
            <td class="text-center"><button class="btn btn-sm" style="color:var(--sam-red);padding:4px 10px;font-size:1rem;" onclick="removeCompraItem(${i})">✕</button></td>
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
    if (!res.success) {
        body.innerHTML = '<div class="list-row" style="justify-content:center;color:#ef4444;font-size:.87rem;">Error al cargar</div>'; return;
    }
    body.innerHTML = res.data.length ? res.data.map(r =>
        `<div class="list-row">
            <div class="list-row-main">
                <div class="list-row-name" title="${esc(r.producto)}">${esc(r.producto)}</div>
                <div class="list-row-sub">${esc(r.fecha)}</div>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Cantidad</span>
                <span class="list-row-value">${Number(r.cantidad)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Proveedor</span>
                <span class="list-row-value">${esc(r.proveedor || '—')}</span>
            </div>
        </div>`
    ).join('') : '<div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Sin registros</div>';
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
                    promoTag = ` <span class="res-memb-tag" title="Requiere: ${resultado.memb_requerida}">${resultado.promo_bloqueada}</span>`;
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
            toast(`"${p.nombre}" tiene promo para ${resultado.memb_requerida} — precio regular aplicado`, 'warn');
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
        else if (it._desc_info?.promo_bloqueada) { descTag = `<span class="badge-desc-no" title="Requiere: ${it._desc_info.memb_requerida}">Sin desc.</span>`; }
        const promoTag = it.promo_nombre ? `<span class="badge badge-promo ms-1" style="font-size:.7rem;">${it.promo_nombre}</span>` : '';
        return `<tr>
            <td class="px-4 py-3">
                <strong style="font-size:.93rem;">${esc(it.nombre)}</strong>
                ${it.promo_nombre ? `<br><span class="badge badge-green" style="font-size:.65rem;">${esc(it.promo_nombre)}</span>` : ''}
            </td>
            <td class="text-end px-4 py-3">${fmt(it.precio)}</td>
            <td class="text-center px-4 py-3">
                <div class="pos-qty-control" style="justify-content:center;">
                    <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, -1)">−</button>
                    <input type="number" class="pos-qty-input" value="${Number(it.cantidad)}" min="1"
                        onchange="setCantidadPos(${idx}, this.value)" oninput="setCantidadPos(${idx}, this.value)">
                    <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, 1)">+</button>
                </div>
            </td>
            <td class="text-end px-4 py-3">${descTag}</td>
            <td class="text-end px-4 py-3 fw-bold">${fmt(sub - desc)}</td>
            <td class="px-2 py-3"><button class="btn btn-sm" style="color:var(--sam-red);padding:4px 8px;" onclick="removeFromCart(${idx})">✕</button></td>
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
function cambiarCantidadPos(idx, delta) { if (!cart[idx]) return; cart[idx].cantidad = Math.max(1, (cart[idx].cantidad || 1) + delta); recalcularDescuentosCarrito(); }
function setCantidadPos(idx, val) { if (!cart[idx]) return; cart[idx].cantidad = Math.max(1, parseInt(val) || 1); recalcularDescuentosCarrito(); }
function removeFromCart(idx) { cart.splice(idx, 1); renderCart(); }

function cancelarVenta() {
    cart = []; selectedSocio = null; renderCart();
    document.getElementById('posSearchSocio').value = '';
    document.getElementById('socioSeleccionado').classList.add('d-none');
    document.getElementById('socioInputWrap').style.display = '';
    document.getElementById('pagosContainer').innerHTML = '';
    agregarPago();
    actualizarResumenPago();
}

function agregarPago() {
    const c = document.getElementById('pagosContainer');
    const d = document.createElement('div');
    d.className = 'pago-row';
    d.innerHTML = `<select class="select-dark pago-metodo" onchange="actualizarResumenPago()" style="flex:1;min-width:0;">
        <option value="EFECTIVO">Efectivo</option>
        <option value="TARJETA">Tarjeta</option>
        <option value="CASHI">Cashi</option>
        <option value="INBURSA">Inbursa</option>
        <option value="VALES">Vales</option>
    </select>
    <input type="number" class="input-dark pago-monto" placeholder="$0.00" min="0" step="0.01" oninput="actualizarResumenPago()" style="width:86px;flex-shrink:0;">
    <button class="socio-terminal-remove" style="flex-shrink:0;" onclick="this.closest('.pago-row').remove(); actualizarResumenPago();" title="Quitar">✕</button>`;
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
            d.style.cssText = 'padding:9px 13px;cursor:pointer;border-bottom:1px solid #f1f5f9;font-size:.85rem';
            const membCls = { CLASICA:'badge-gray', BENEFITS:'badge-blue', PLUS:'badge-purple' };
            const cls = membCls[s.membresia] || 'badge-gray';
            d.innerHTML = `<div style="font-weight:600;margin-bottom:3px;">${esc(s.nombre)} <span class="text-muted" style="font-weight:400;font-size:.82rem;">#${s.numero_socio}</span></div>
                           <span class="badge ${cls}">${s.membresia}</span>
                           <span class="text-muted" style="font-size:.77rem;margin-left:5px;">Cashback: ${fmt(s.saldo_cashback)}</span>`;
            d.onmouseover = () => { d.style.background = '#eef2ff'; };
            d.onmouseout  = () => { d.style.background = ''; };
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
    if (!res.success) {
        body.innerHTML = '<div class="list-row" style="justify-content:center;color:#ef4444;font-size:.87rem;">Error al cargar</div>'; return;
    }
    body.innerHTML = res.data.length ? res.data.map(r =>
        `<div class="list-row">
            <div class="list-row-id">#${Number(r.id)}</div>
            <div class="list-row-main">
                <div class="list-row-name">${r.socio ? esc(r.socio) : '<span style="color:#94a3b8;">Venta directa</span>'}</div>
                <div class="list-row-sub">${esc(r.fecha)}</div>
            </div>
            <span class="badge badge-blue">${esc(r.canal)}</span>
            <div class="list-row-field">
                <span class="list-row-label">Artículos</span>
                <span class="list-row-value">${Number(r.num_items)}</span>
            </div>
            <div class="list-row-amount">${fmt(r.total)}</div>
        </div>`
    ).join('') : '<div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Sin ventas registradas</div>';
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
    body.innerHTML = '<div style="text-align:center;padding:24px 0;"><div class="spinner-sam"></div></div>';
    const res = await api(url);
    if (!res.success) { toast(res.error,'error'); return; }
    sociosData = res.data;
    body.innerHTML = res.data.length ? res.data.map(r => {
        const membClass = r.tipo_membresia === 'PLUS' ? 'badge-purple' : r.tipo_membresia === 'BENEFITS' ? 'badge-blue' : 'badge-gray';
        return `<div class="list-row" style="cursor:pointer;" onclick="mostrarDetallesSocio(${r.socio_membresia_id})">
            <div class="list-row-id">#${r.socio_membresia_id}</div>
            <div class="list-row-main">
                <div class="list-row-name">${esc(r.nombre)}</div>
                <div class="list-row-sub">${esc(r.numero_socio)}</div>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Membresía</span>
                <span class="badge ${membClass}">${esc(r.tipo_membresia)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Familiares</span>
                <span class="badge badge-gray">${r.num_familiares || 0}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Vence</span>
                <span class="list-row-value">${esc(r.vencimiento)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Cashback</span>
                <span class="list-row-amount" style="font-size:.88rem;">${fmt(r.saldo_cashback)}</span>
            </div>
            <div class="list-row-actions" onclick="event.stopPropagation()">
                <div class="cell-actions">
                    <button class="btn-icon btn-icon-warn" title="Desactivar membresía" onclick="desactivarMembresia(${r.socio_membresia_id})">⏸</button>
                    <button class="btn-icon btn-icon-danger" title="Eliminar titular y familiares" onclick="eliminarTitular(${r.socio_membresia_id}, ${JSON.stringify(r.nombre)}, ${r.num_familiares || 0})">✕</button>
                </div>
            </div>
        </div>`;
    }).join('') : '<div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Sin socios registrados</div>';
}

async function mostrarDetallesSocio(socio_membresia_id) {
    socioSeleccionado = socio_membresia_id;
    const res = await api('socios.php?action=get_socio_detalles&socio_membresia_id=' + socio_membresia_id);
    if (!res.success) { toast(res.error,'error'); return; }
    const titular = res.titular;
    const familiares = res.familiares || [];
    const membClass = (t) => t === 'PLUS' ? 'badge-purple' : t === 'BENEFITS' ? 'badge-blue' : 'badge-gray';

    let html = `
        <div class="socio-detail-card">
            <div class="socio-detail-name">${esc(titular.nombre)}</div>
            <div class="socio-detail-grid">
                <div>
                    <span class="socio-detail-item-label">Número de socio</span>
                    <span class="socio-detail-item-val fw-semibold">${esc(titular.numero_socio)}</span>
                </div>
                <div>
                    <span class="socio-detail-item-label">Membresía</span>
                    <span class="badge ${membClass(titular.tipo_membresia)}">${esc(titular.tipo_membresia)}</span>
                </div>
                <div>
                    <span class="socio-detail-item-label">Correo</span>
                    <span class="socio-detail-item-val">${titular.correo ? esc(titular.correo) : '<span class="text-muted">—</span>'}</span>
                </div>
                <div>
                    <span class="socio-detail-item-label">Vencimiento</span>
                    <span class="socio-detail-item-val">${esc(titular.vencimiento)}</span>
                </div>
                <div>
                    <span class="socio-detail-item-label">Teléfono</span>
                    <span class="socio-detail-item-val">${titular.telefono ? esc(titular.telefono) : '<span class="text-muted">—</span>'}</span>
                </div>
                <div>
                    <span class="socio-detail-item-label">Cashback acumulado</span>
                    <span class="socio-detail-cashback">${fmt(titular.saldo_cashback)}</span>
                </div>
            </div>
            <div class="socio-detail-actions">
                <button class="btn-detail-primary" onclick="renovarMembresia(${titular.id})">Renovar Membresía</button>
                <button class="btn-detail-danger" onclick="eliminarTitular(${titular.id}, ${JSON.stringify(titular.nombre)}, ${familiares.length})">Eliminar Titular</button>
            </div>
        </div>
    `;

    if (familiares.length > 0) {
        html += `<div class="section-title" style="display:block;margin-top:20px;">Familiares Vinculados <span class="count-badge" style="margin-left:8px;">${familiares.length}</span></div>`;
        html += '<div class="detail-fam-list">';
        familiares.forEach(f => {
            html += `<div class="detail-fam-row">
                <div style="flex:1;min-width:0;">
                    <div class="list-row-name" style="font-size:.88rem;">${esc(f.nombre)}</div>
                    <div class="list-row-sub">${esc(f.parentesco)}</div>
                </div>
                <span class="badge ${membClass(f.tipo_membresia)}">${esc(f.tipo_membresia)}</span>
                ${f.es_complementaria == 1 ? '<span class="badge badge-green">Gratis</span>' : ''}
                <span style="color:var(--sam-green);font-weight:700;font-size:.85rem;">${fmt(f.saldo_cashback)}</span>
                <div class="cell-actions">
                    <button class="btn-icon btn-icon-danger" title="Eliminar familiar" onclick="eliminarFamiliar(${f.socio_membresia_id}, ${JSON.stringify(f.nombre)}, ${socio_membresia_id})">✕</button>
                </div>
            </div>`;
        });
        html += '</div>';
    } else {
        html += `<div class="alert alert-info mt-3 mb-0">
            Este socio no tiene familiares vinculados aún.
            <button class="btn-detail-link" onclick="mostrarFormFamiliar()">Vincular un familiar</button>
        </div>`;
    }

    document.getElementById('socioDetallesContent').innerHTML = html;
    showSocioTab('detalles', document.querySelector('#section-socios .pill-tab[onclick*="detalles"]'));
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
    if (res.success) { toast(res.message); limpiarFormNuevo(); loadSocios(); }
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
        toast(res.message + ' · Número: ' + res.numero_socio);
        limpiarFormFamiliar(); loadSocios(); mostrarFormNuevo();
    } else toast(res.error, 'error');
}

async function desactivarMembresia(socio_membresia_id) {
    if (!confirm('¿Desactivar esta membresía? El socio dejará de aparecer activo.')) return;
    const res = await api('socios.php', { action: 'desactivar_membresia', socio_membresia_id });
    if (res.success) { toast(res.message); loadSocios(); }
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
        toast(res.message);
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
        toast(res.message);
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
    if (res.success) { toast(res.message); mostrarDetallesSocio(socio_membresia_id); loadSocios(); }
    else toast(res.error, 'error');
}

async function loadFamiliares() {
    const q = document.getElementById('familiarBuscar').value;
    const body = document.getElementById('familiaresBody');
    body.innerHTML = '<div style="text-align:center;padding:24px 0;"><div class="spinner-sam"></div></div>';
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
    body.innerHTML = datos.length ? datos.map(f => {
        const membClass = f.tipo_membresia === 'PLUS' ? 'badge-purple' : f.tipo_membresia === 'BENEFITS' ? 'badge-blue' : 'badge-gray';
        return `<div class="list-row">
            <div class="list-row-main">
                <div class="list-row-name">${esc(f.nombre)}</div>
                <div class="list-row-sub">${esc(f.numero_socio)}</div>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Parentesco</span>
                <span class="badge badge-gray" style="font-size:.67rem;text-transform:uppercase;">${esc(f.parentesco)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Membresía</span>
                <span class="badge ${membClass}">${esc(f.tipo_membresia)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Titular</span>
                <div>
                    <div class="list-row-value">${esc(f.titular_nombre)}</div>
                    <div class="list-row-sub">${esc(f.titular_numero_socio)}</div>
                </div>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Complem.</span>
                ${f.es_complementaria == 1 ? '<span class="badge badge-green">Gratis</span>' : '<span style="color:#94a3b8;">—</span>'}
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Vence</span>
                <span class="list-row-value">${esc(f.vencimiento)}</span>
            </div>
            <div class="list-row-field">
                <span class="list-row-label">Cashback</span>
                <span class="list-row-amount" style="font-size:.88rem;">${fmt(f.saldo_cashback)}</span>
            </div>
            <div class="list-row-actions">
                <div class="cell-actions">
                    <button class="btn-icon btn-icon-danger" onclick="eliminarFamiliar(${f.socio_membresia_id}, ${JSON.stringify(f.nombre)}, ${f.cuenta_titular_id})" title="Eliminar familiar">✕</button>
                </div>
            </div>
        </div>`;
    }).join('') : '<div class="list-row" style="justify-content:center;color:#94a3b8;font-size:.87rem;">Sin familiares registrados</div>';
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

    agregarPago();
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
lucide.createIcons();
</script>

<!-- ═══════════════════════════════════════════════
     MODAL · NUEVO PRODUCTO
═══════════════════════════════════════════════ -->
<div id="nuevoProductoOverlay" onclick="if(event.target===this)cerrarNuevoProducto()">
    <div id="nuevoProductoCard">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Nuevo Producto</h2>
                <p class="text-xs text-slate-500 mt-0.5">Completa los datos del producto</p>
            </div>
            <button type="button" onclick="cerrarNuevoProducto()" class="np-close-btn">✕</button>
        </div>

        <form id="nuevoProductoForm" onsubmit="guardarNuevoProducto(event)">

            <!-- ── Identificación ── -->
            <p class="np-section-title">Identificación</p>
            <div class="np-form-grid mb-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">SKU / Código <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" required
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition"
                        placeholder="Ej. SKU-001234">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" required
                        class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition"
                        placeholder="Nombre del producto">
                </div>
            </div>

            <!-- ── Clasificación ── -->
            <div class="border-t border-slate-100 pt-4 mb-5">
                <p class="np-section-title">Clasificación</p>
                <div class="np-form-grid">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Marca</label>
                        <input type="text" name="marca"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition"
                            placeholder="Ej. Kirkland">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Tipo</label>
                        <select name="tipo"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Seleccionar…</option>
                            <option value="BULK">Bulk</option>
                            <option value="PERECEDERO">Perecedero</option>
                            <option value="CONGELADO">Congelado</option>
                            <option value="ROPA">Ropa</option>
                            <option value="ELECTRONICA">Electrónica</option>
                            <option value="SERVICIO">Servicio</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ── Catálogos ── -->
            <div class="border-t border-slate-100 pt-4 mb-5">
                <p class="np-section-title">Catálogos</p>
                <div class="np-form-grid">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Categoría</label>
                        <select id="npCategoria" name="categoria_id"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Sin categoría…</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Proveedor</label>
                        <select id="npProveedor" name="proveedor_id"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue transition bg-white">
                            <option value="">Sin proveedor…</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ── Precio & Stock ── -->
            <div class="border-t border-slate-100 pt-4 mb-5">
                <p class="np-section-title">Precio & Stock inicial</p>
                <div class="np-form-grid">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Precio <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">$</span>
                            <input type="number" name="precio" step="0.01" min="0" required
                                class="w-full text-sm border border-slate-300 rounded-lg pl-7 pr-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition"
                                placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Stock Piso</label>
                        <input type="number" name="stock_piso" min="0" value="0"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Stock Reserva</label>
                        <input type="number" name="stock_reserva" min="0" value="0"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Multipack</label>
                        <input type="number" name="multipack" min="1" placeholder="Ej. 6"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                </div>
            </div>

            <!-- ── Especificaciones ── -->
            <div class="border-t border-slate-100 pt-4 mb-5">
                <p class="np-section-title">Especificaciones</p>
                <div class="np-form-grid">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-2">Días Vida Útil</label>
                        <input type="number" name="dias_vida_util" min="0" placeholder="Ej. 365"
                            class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 outline-none focus:ring-2 focus:ring-sams-blue focus:border-sams-blue transition">
                    </div>
                </div>
            </div>

            <!-- ── Opciones ── -->
            <div class="border-t border-slate-100 pt-4 mb-6">
                <p class="np-section-title">Opciones</p>
                <div class="flex flex-wrap gap-x-6 gap-y-3">
                    <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-slate-600">
                        <input type="checkbox" name="es_members_mark" class="mt-0.5 accent-[#003DA5]">
                        <span>Es Member's Mark</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-slate-600">
                        <input type="checkbox" name="requiere_refrigeracion" class="mt-0.5 accent-[#003DA5]">
                        <span>Requiere Refrigeración</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-slate-600">
                        <input type="checkbox" name="requiere_congelacion" class="mt-0.5 accent-[#003DA5]">
                        <span>Requiere Congelación</span>
                    </label>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex gap-3 justify-end border-t border-slate-100 pt-5">
                <button type="button" onclick="cerrarNuevoProducto()"
                    class="border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    style="background:var(--sam-blue);"
                    class="hover:opacity-90 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-opacity">
                    Guardar Producto
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
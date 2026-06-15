# SamsAdminPanel UI Rebuild Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Completely rebuild `index.php` frontend — dark sidebar nav, Dashboard home tab, Sam's Club branding, terminal-style POS — zero changes to any backend PHP file.

**Architecture:** Single-file SPA rewrite. All CSS lives in a `<style>` block in `index.php`. Sections toggled via `showSection()` JS (replaces Bootstrap tabs). New Dashboard section aggregates data from existing APIs. All backend API calls (`socios.php`, `inventario.php`, `compras.php`, `ventas.php`, `promociones.php`) are untouched.

**Tech Stack:** PHP (render only), Bootstrap 5.3 (grid + utilities only), vanilla CSS, vanilla JS, Inter font (Google Fonts CDN)

---

## File Map

| File | Action | Notes |
|------|--------|-------|
| `index.php` | Modify | Complete frontend rewrite — CSS, HTML structure, JS nav system |
| `.env` | Create | DB credentials template |
| All `*.php` API files | No change | Backend is correct and untouched |

---

## Task 1: Replace the CSS block

**Files:**
- Modify: `index.php` lines 9–261 (the entire `<style>` block content)

- [ ] **Step 1: Replace everything between `<style>` and `</style>` in `<head>`** with the following complete CSS:

```css
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
    background: var(--sam-dark); color: #fff; font-size: .75rem;
    letter-spacing: .5px; text-transform: uppercase; border: none; padding: 13px 16px; font-weight: 700;
}
.sam-table tbody tr { border-bottom: 1px solid var(--sam-border); transition: background .15s ease; }
.sam-table tbody tr:last-child { border-bottom: none; }
.sam-table tbody tr:hover { background: var(--sam-hover); }
.sam-table td { vertical-align: middle; font-size: .9rem; padding: 14px 16px; color: #374151; border: none; }
.sam-table td strong { color: var(--sam-dark); font-weight: 600; }

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
.badge-gold   { background: var(--sam-yellow); color: var(--sam-dark); }
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
.small { font-size: .82rem; }
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
.pe-none { pointer-events: none; }
.opacity-50 { opacity: .5; }
#pagoResumen { font-size: .82rem; margin-top: 6px; display: flex; justify-content: space-between; color: rgba(255,255,255,.55); }
#cambioInfo { font-size: .85rem; font-weight: 600; color: var(--sam-green); margin-top: 4px; }
.todos-check-wrap label { font-size: .85rem; font-weight: 600; color: var(--sam-muted); cursor: pointer; display: flex; align-items: center; gap: 8px; }
@media (max-width: 900px) { .sam-main { margin-left: 0; padding: 16px; } .sam-sidebar { display: none; } .pos-terminal { flex: 0 0 280px; } }
```

- [ ] **Step 2: Verify the page loads** (serve with `php -S localhost:8000` from project root, open `http://localhost:8000`). The page will look broken visually but open DevTools → Elements → `:root` should show all `--sam-*` CSS variables. No console CSS errors.

- [ ] **Step 3: Commit**
```bash
git add index.php
git commit -m "style: replace CSS with complete Sam's Club design system"
```

---

## Task 2: New HTML shell — sidebar + main container

**Files:**
- Modify: `index.php` — replace old navbar + container with sidebar + main

- [ ] **Step 1: Replace the body opening + navbar block.** Find the block from `</style>\n</head>\n<body>` through the line `<div class="tab-content" id="mainTabContent">` (inclusive). Replace it entirely with:

```html
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
```

- [ ] **Step 2: Replace the closing container divs.** Find `</div><!-- /tab-content -->` followed by `</div><!-- /container-fluid -->` and replace both with:

```html
</main><!-- /sam-main -->
```

- [ ] **Step 3: Rename all tab-pane divs.** Find and replace each old section wrapper:
  - `<div class="tab-pane fade show active" id="panel-inventario" role="tabpanel">` → `<div class="sam-section" id="section-inventario">`
  - `<div class="tab-pane fade" id="panel-socios" role="tabpanel">` → `<div class="sam-section" id="section-socios">`
  - `<div class="tab-pane fade" id="panel-promos" role="tabpanel">` → `<div class="sam-section" id="section-promociones">`
  - `<div class="tab-pane fade" id="panel-compras" role="tabpanel">` → `<div class="sam-section" id="section-compras">`
  - `<div class="tab-pane fade" id="panel-ventas" role="tabpanel">` → `<div class="sam-section" id="section-ventas">`

- [ ] **Step 4: Add navigation JS block.** Find the first `<script>` tag (the one with `window.addEventListener('error'`) and insert this block immediately before it:

```html
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

function refreshCurrentSection() {
    const map = {
        dashboard:   loadDashboard,
        inventario:  () => { loadInventario(); loadInvStats(); },
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
```

- [ ] **Step 5: Verify sidebar renders.** Reload page. Dark navy sidebar appears on the left with all 6 nav items. Sam's logo and "Sam's Admin" label visible. "Sistema activo" pulse dot at the bottom. Clicking nav items switches the active yellow-left-border highlight. Main content area is offset 260px to the right.

- [ ] **Step 6: Commit**
```bash
git add index.php
git commit -m "feat: add dark sidebar navigation shell and showSection() routing system"
```

---

## Task 3: Dashboard section

**Files:**
- Modify: `index.php` — add `#section-dashboard` HTML block and `loadDashboard()` JS

- [ ] **Step 1: Insert Dashboard HTML** immediately before `<div class="sam-section" id="section-inventario">`:

```html
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
                            <tr><th>#</th><th>Fecha</th><th>Canal</th><th class="text-end">Total</th></tr>
                        </thead>
                        <tbody id="dash-ventas-body">
                            <tr><td colspan="4" class="text-center py-3 text-muted">Cargando…</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-primary" onclick="showSection('ventas')">Ver todas las ventas →</button>
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
```

- [ ] **Step 2: Add `loadDashboard()` to the main JS script block** (place alongside the other load functions):

```javascript
async function loadDashboard() {
    // Socios activos + membership breakdown
    const sociosRes = await api('socios.php?action=list_titulares');
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

    // Sin stock
    const invRes = await api('inventario.php?action=stats');
    if (invRes.success) document.getElementById('dash-sinstock').textContent = invRes.data.sin_stock;

    // Promociones activas
    const promoRes = await api('promociones.php?action=list_promos');
    if (promoRes.success) {
        document.getElementById('dash-promos').textContent = promoRes.data.filter(p => p.activo === '1').length;
    }

    // Ventas recientes + ingresos hoy
    const ventasRes = await api('ventas.php?action=historial');
    const dashBody = document.getElementById('dash-ventas-body');
    if (ventasRes.success && ventasRes.data.length) {
        const todayStr = new Date().toISOString().slice(0, 10);
        let todayTotal = 0;
        ventasRes.data.forEach(v => {
            if (v.fecha && v.fecha.startsWith(todayStr)) todayTotal += parseFloat(v.total || 0);
        });
        document.getElementById('dash-ingresos').textContent = fmt(todayTotal);
        dashBody.innerHTML = ventasRes.data.slice(0, 5).map(v =>
            `<tr>
                <td class="text-muted small">#${v.id}</td>
                <td class="small">${v.fecha ? v.fecha.slice(0,10) : '—'}</td>
                <td><span class="badge badge-blue">${v.canal || '—'}</span></td>
                <td class="text-end fw-bold">${fmt(v.total)}</td>
            </tr>`
        ).join('');
    } else {
        dashBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Sin ventas registradas</td></tr>';
        document.getElementById('dash-ingresos').textContent = fmt(0);
    }
}
```

- [ ] **Step 3: Update `DOMContentLoaded` listener** at the bottom of the JS to call `loadDashboard()` first and add `showSection('dashboard')`:

```javascript
document.addEventListener('DOMContentLoaded', () => {
    showSection('dashboard');
    loadDashboard();
    loadInventario();
    loadInvStats();
    loadSocios();
    loadFamiliares();
    loadTiposMembresia();
    loadTitularesSelect();
    loadProductosPromo();
    loadPromos();
    loadCompraData();
    loadHistVentas();
});
```

- [ ] **Step 4: Verify Dashboard.** Reload. Dashboard is the active section. Stat cards show real numbers. Membership bars render with colored fills. Ventas Recientes table shows rows. Quick-access buttons navigate to the correct section.

- [ ] **Step 5: Commit**
```bash
git add index.php
git commit -m "feat: add Dashboard section with stat cards, membership bars and quick-access buttons"
```

---

## Task 4: Restyle Inventario section

**Files:**
- Modify: `index.php` — update `#section-inventario` HTML and `loadInventario()` JS

- [ ] **Step 1: Replace the entire inner content of `<div class="sam-section" id="section-inventario">`** with:

```html
<div class="d-flex align-items-center gap-3 mb-4">
    <div style="flex:1; max-width:420px; position:relative;">
        <input id="invSearch" type="text" class="form-control" placeholder="Buscar por nombre, SKU o marca…">
    </div>
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
```

- [ ] **Step 2: Update the row template inside `loadInventario()`** — find the `body.innerHTML +=` block and replace just the template literal with:

```javascript
const tipoColors = { BULK:'badge-gray', PERECEDERO:'badge-yellow', CONGELADO:'badge-blue', ROPA:'badge-purple', ELECTRONICA:'badge-gray', SERVICIO:'badge-green' };
const tipoClass = tipoColors[r.tipo] || 'badge-gray';
const mm = r.es_members_mark == '1' ? '<span class="badge badge-gold ms-1" style="margin-left:6px;">MM</span>' : '';
const promo = r.promo_nombre
    ? `<span class="badge badge-green">${r.promo_nombre}${r.descuento_pct > 0 ? ' −' + r.descuento_pct + '%' : ''}</span>`
    : '<span class="text-muted small">—</span>';
const stockStyle = parseFloat(r.stock_total) == 0 ? 'style="color:var(--sam-red);font-weight:700;"' : '';
body.innerHTML += `<tr>
    <td class="text-muted small">${r.sku}</td>
    <td><strong>${r.nombre}</strong>${mm}</td>
    <td>${r.marca || '—'}</td>
    <td><span class="badge ${tipoClass} badge-tipo">${r.tipo}</span></td>
    <td class="small">${r.categoria || '—'}</td>
    <td class="text-end fw-bold">${fmt(r.precio_actual)}</td>
    <td class="text-end" ${stockStyle}>${parseFloat(r.stock_piso).toFixed(0)}</td>
    <td class="text-end">${parseFloat(r.stock_reserva).toFixed(0)}</td>
    <td>${promo}</td>
</tr>`;
```

- [ ] **Step 3: Verify.** Click Inventario in the sidebar. Table loads with dark navy header. Badge pills use new colors. Out-of-stock rows show red bold text. MM badge is gold.

- [ ] **Step 4: Commit**
```bash
git add index.php
git commit -m "feat: restyle Inventario section with new badge system and table design"
```

---

## Task 5: Restyle Socios section

**Files:**
- Modify: `index.php` — update `#section-socios` HTML and add `showSocioTab()` JS

- [ ] **Step 1: Replace the entire inner content of `<div class="sam-section" id="section-socios">`** with:

```html
<div class="d-flex gap-4" style="align-items:flex-start;">
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
```

- [ ] **Step 2: Add `showSocioTab()` to the JS block:**

```javascript
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
```

- [ ] **Step 3: Update membership badge rendering in `loadSocios()`.** Find the badge template in the `sociosBody` row and replace membership badge with:

```javascript
const membClass = r.tipo_membresia === 'PLUS' ? 'badge-purple' : r.tipo_membresia === 'BENEFITS' ? 'badge-blue' : 'badge-gray';
// use: <span class="badge ${membClass}">${r.tipo_membresia}</span>
```

- [ ] **Step 4: Verify.** Socios section: form card has blue `4px` top border. Pill tabs switch between Titulares / Familiares / Detalles panels. Membership badges use new colored pill style.

- [ ] **Step 5: Commit**
```bash
git add index.php
git commit -m "feat: restyle Socios section with top-accent card and pill sub-tab navigation"
```

---

## Task 6: Restyle Promociones section

**Files:**
- Modify: `index.php` — update `#section-promociones` HTML, `renderMembresiaChecks()`, `toggleMembCheck()`, and status badge in `loadPromos()`

- [ ] **Step 1: Replace the entire inner content of `<div class="sam-section" id="section-promociones">`** with:

```html
<div class="d-flex gap-4" style="align-items:flex-start;">
    <div style="flex:0 0 380px; min-width:0;">
        <div class="sam-card top-accent">
            <span class="section-title">Nueva Promoción</span>
            <div class="mb-3"><label class="form-label">Producto</label><select id="promoProducto" class="form-select"><option value="">Cargando…</option></select></div>
            <div class="mb-3"><label class="form-label">Nombre de la promoción</label><input type="text" id="promoNombre" class="form-control" placeholder="Ej: Promo Verano"></div>
            <div class="mb-3"><label class="form-label">Descuento %</label><input type="number" id="promoDescPct" class="form-control" placeholder="0" min="0" max="100" step="0.01"></div>
            <div class="mb-3"><label class="form-label">Descuento $</label><input type="number" id="promoDescMonto" class="form-control" placeholder="0.00" min="0" step="0.01"></div>
            <div class="alert alert-info mb-3">Ingresa SOLO descuento por % O por $, no ambos.</div>
            <div class="mb-3"><label class="form-label">Fecha de inicio</label><input type="date" id="promoFechaIni" class="form-control"></div>
            <div class="mb-3"><label class="form-label">Fecha de fin</label><input type="date" id="promoFechaFin" class="form-control"></div>
            <div class="mb-3">
                <label class="form-label">Elegibilidad por membresía</label>
                <div class="mb-2 todos-check-wrap">
                    <label>
                        <input type="checkbox" id="promoAplicaTodos" onchange="toggleTodosCheck()">
                        Aplica a todos (sin importar membresía)
                    </label>
                </div>
                <div id="membresiaChecks" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
                <div class="mt-2 small text-muted">Si no marcas "todos", solo los tipos seleccionados verán el descuento.</div>
            </div>
            <button class="btn btn-primary w-100 mt-2" onclick="crearPromo()">Registrar Promoción</button>
        </div>
    </div>
    <div style="flex:1; min-width:0;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="section-title" style="border:none;padding:0;margin:0;">Promociones Registradas</span>
            <button class="btn btn-sm btn-outline-primary" onclick="loadPromos()">↺ Actualizar</button>
        </div>
        <div class="sam-table table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Producto</th><th>Promoción</th><th class="text-end">Desc %</th><th class="text-end">Desc $</th><th>Vigencia</th><th>Membresías</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody id="promoBody"><tr><td colspan="9" class="text-center py-4 text-muted">Cargando…</td></tr></tbody>
            </table>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Replace `renderMembresiaChecks()` and `toggleMembCheck()` entirely:**

```javascript
function renderMembresiaChecks() {
    const container = document.getElementById('membresiaChecks');
    container.innerHTML = tiposMembresia.map(tm =>
        `<label class="memb-pill-toggle" id="lbl-memb-${tm.id}" onclick="toggleMembCheck(${tm.id}, '${tm.nombre}')">
            <input type="checkbox" id="chk-memb-${tm.id}" value="${tm.id}">
            <span>${tm.nombre}</span>
        </label>`
    ).join('');
}

function toggleMembCheck(id, nombre) {
    const chk = document.getElementById(`chk-memb-${id}`);
    const lbl = document.getElementById(`lbl-memb-${id}`);
    chk.checked = !chk.checked;
    if (chk.checked) lbl.classList.add(`active-${nombre}`);
    else lbl.classList.remove(`active-${nombre}`);
}
```

- [ ] **Step 3: Update status badge inside `loadPromos()`.** Find the `const badge = ...` line and replace it with:

```javascript
const badge = r.activo == '1'
    ? '<span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;color:var(--sam-green);">● Activa</span>'
    : '<span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;color:var(--sam-muted);">● Inactiva</span>';
```

- [ ] **Step 4: Verify.** Promociones section: form card has blue top border. Membership pills are large rounded toggles that change color when clicked (gray/blue/purple). Status column shows green/gray dot + text, no filled badge.

- [ ] **Step 5: Commit**
```bash
git add index.php
git commit -m "feat: restyle Promociones section with pill membership toggles and dot status"
```

---

## Task 7: Restyle Compras section

**Files:**
- Modify: `index.php` — update `#section-compras` HTML

- [ ] **Step 1: Replace the entire inner content of `<div class="sam-section" id="section-compras">`** with:

```html
<div class="d-flex gap-4" style="align-items:flex-start;">
    <div style="flex:1; min-width:0;">
        <div class="sam-card top-accent">
            <span class="section-title">Registrar Recepción de Mercancía</span>
            <div class="mb-3"><label class="form-label">Proveedor</label><select id="compraProveedor" class="form-select"><option value="">Seleccionar proveedor…</option></select></div>
            <div class="mb-3"><label class="form-label">Zona de destino</label><select id="compraZona" class="form-select"><option value="">Seleccionar zona…</option></select></div>
            <div class="mb-3"><label class="form-label">¿Es reserva?</label>
                <select id="compraEsReserva" class="form-select">
                    <option value="0">No (Piso de venta)</option>
                    <option value="1">Sí (Bodega / Reserva)</option>
                </select>
            </div>
            <hr style="border-color:var(--sam-border);margin:16px 0;">
            <span class="section-title">Agregar Productos</span>
            <div class="mb-3"><label class="form-label">Producto</label><select id="compraProductoSel" class="form-select"><option value="">Seleccionar producto…</option></select></div>
            <div class="d-flex gap-3 mb-3">
                <div style="flex:1;"><label class="form-label">Cantidad</label><input type="number" id="compraCantidad" class="form-control" placeholder="1" min="1"></div>
                <div style="flex:1;"><label class="form-label">Precio costo</label><input type="number" id="compraPrecio" class="form-control" placeholder="0.00" step="0.01" min="0"></div>
            </div>
            <button class="btn btn-success w-100 mb-3" onclick="agregarItemCompra()">+ Agregar a lista</button>
            <span class="section-title">Items Agregados</span>
            <div class="compra-items-sub">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr style="background:var(--sam-dark);">
                            <th style="color:#fff;font-size:.72rem;padding:10px 12px;text-transform:uppercase;border:none;">Producto</th>
                            <th style="color:#fff;font-size:.72rem;padding:10px 12px;text-transform:uppercase;border:none;width:90px;" class="text-center">Cant.</th>
                            <th style="color:#fff;font-size:.72rem;padding:10px 12px;text-transform:uppercase;border:none;width:110px;" class="text-center">Precio costo</th>
                            <th style="color:#fff;font-size:.72rem;padding:10px 12px;text-transform:uppercase;border:none;width:90px;" class="text-end">Total</th>
                            <th style="color:#fff;border:none;width:38px;"></th>
                        </tr>
                    </thead>
                    <tbody id="compraItemsBody">
                        <tr id="compraEmptyRow"><td colspan="5" class="text-center text-muted py-3">Sin productos agregados</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="compra-total-row">
                <span style="font-weight:600;" id="compraTotalUnidades">Total: 0 unidades</span>
                <span style="font-size:1.1rem;font-weight:700;color:var(--sam-blue);" id="compraTotalCosto">$0.00</span>
            </div>
            <button class="btn btn-primary w-100" onclick="procesarCompra()">Registrar Compra</button>
        </div>
    </div>
    <div style="flex:0 0 340px; min-width:0;">
        <span class="section-title">Recepciones Recientes</span>
        <div class="sam-table table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead><tr><th>Fecha</th><th>Producto</th><th class="text-end">Cant.</th><th>Proveedor</th></tr></thead>
                <tbody id="histCompraBody"><tr><td colspan="4" class="text-center text-muted py-3">Cargando…</td></tr></tbody>
            </table>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Update `renderCompraItems()` row template** to use `.compra-item-input` class on the quantity/price inputs:

```javascript
return `<tr>
    <td><strong style="font-size:.85rem;">[${it.sku}]</strong> <span style="font-size:.85rem;">${it.nombre}</span></td>
    <td class="text-center"><input type="number" class="compra-item-input" value="${it.cantidad}" min="1" step="1" style="width:70px;" onchange="updateCompraItem(${i},'cantidad',this.value)" oninput="updateCompraItem(${i},'cantidad',this.value)"></td>
    <td class="text-center"><input type="number" class="compra-item-input" value="${it.precio_costo || ''}" min="0" step="0.01" placeholder="0.00" style="width:85px;" onchange="updateCompraItem(${i},'precio_costo',this.value)" oninput="updateCompraItem(${i},'precio_costo',this.value)"></td>
    <td class="text-end" style="font-weight:700;color:var(--sam-blue);" id="sub-compra-${i}">${sub > 0 ? fmt(sub) : '—'}</td>
    <td class="text-center"><button class="btn btn-sm" style="color:var(--sam-red);padding:3px 8px;" onclick="removeCompraItem(${i})">✕</button></td>
</tr>`;
```

- [ ] **Step 3: Verify.** Compras section: form card has blue top border. Items sub-table has dark navy header inline. Total row has blue left border with large cost number. Side panel shows recent receipts.

- [ ] **Step 4: Commit**
```bash
git add index.php
git commit -m "feat: restyle Compras section with top-accent card and styled items table"
```

---

## Task 8: Restyle Punto de Venta — dark terminal

**Files:**
- Modify: `index.php` — update `#section-ventas` HTML and update `renderCart()` JS

- [ ] **Step 1: Replace the entire inner content of `<div class="sam-section" id="section-ventas">`** with:

```html
<div class="pos-wrap">
    <!-- LEFT: Light zone -->
    <div class="pos-light">
        <div class="d-flex gap-3" style="flex-shrink:0;">
            <div style="flex:0 0 180px;">
                <label class="form-label">Canal de venta</label>
                <select id="posCanal" class="form-select">
                    <option value="CAJA">Caja</option>
                    <option value="SELF">Self-checkout</option>
                    <option value="SCAN_GO">Scan &amp; Go</option>
                </select>
            </div>
            <div style="flex:1; position:relative;">
                <label class="form-label">Buscar producto para agregar</label>
                <input type="text" id="posSearch" class="form-control" placeholder="Nombre, SKU o marca…" oninput="buscarProductoPos()">
                <div id="searchResults"></div>
            </div>
        </div>
        <div class="pos-cart-wrap sam-table table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Producto</th><th class="text-end">Precio U.</th>
                        <th class="text-center" style="width:130px;">Cantidad</th>
                        <th class="text-end">Desc.</th><th class="text-end">Subtotal</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody id="posCartBody">
                    <tr><td colspan="6" class="text-center text-muted py-4">Busca un producto para comenzar la venta</td></tr>
                </tbody>
            </table>
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
                        <input type="text" id="posSearchSocio" class="input-dark" placeholder="Nombre o número de socio…" oninput="buscarSocio()">
                        <div id="socioResults"></div>
                    </div>
                    <div id="socioSeleccionado" class="mt-2 d-none">
                        <div class="socio-terminal-active">
                            <span>👤</span>
                            <span class="socio-terminal-name" id="socioLabel"></span>
                            <span class="socio-terminal-pill" id="socioMembPill"></span>
                            <button class="socio-terminal-remove" onclick="quitarSocio()" title="Quitar">✕</button>
                        </div>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.4);margin-top:5px;padding-left:4px;" id="socioMembInfo"></div>
                    </div>
                </div>
            </div>
            <div>
                <div class="pos-row"><span class="pos-row-label">Subtotal</span><span class="pos-row-value" id="posSubtotal">$0.00</span></div>
                <div class="pos-row"><span class="pos-row-label">Descuentos</span><span style="color:#F87171;font-weight:600;" id="posDescuentos">−$0.00</span></div>
            </div>
            <div>
                <div class="pos-total-label">TOTAL A PAGAR</div>
                <div class="pos-total-amount" id="posTotal">$0.00</div>
            </div>
            <hr class="pos-divider">
            <div>
                <div class="pos-label">Método de Pago</div>
                <div id="pagosContainer">
                    <div class="pago-row d-flex gap-2 mb-2" data-idx="0">
                        <select class="select-dark pago-metodo" onchange="actualizarResumenPago()" style="flex:1;">
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TARJETA">Tarjeta</option>
                            <option value="CASHI">Cashi</option>
                            <option value="INBURSA">Inbursa</option>
                            <option value="VALES">Vales</option>
                        </select>
                        <input type="number" class="input-dark pago-monto" placeholder="Monto" min="0" step="0.01" oninput="actualizarResumenPago()" style="width:100px;">
                    </div>
                </div>
                <button style="width:100%;margin-top:6px;padding:8px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.12);border-radius:8px;cursor:pointer;font-size:.82rem;font-weight:600;" onclick="agregarPago()">+ Agregar método de pago</button>
                <div id="pagoResumen" class="mt-2 d-none">
                    <span>Pagado: <strong id="pagoTotalIngresado">$0.00</strong></span>
                    <span id="pagoEstado"></span>
                </div>
                <div id="cambioInfo" class="mt-2 d-none"></div>
            </div>
        </div>
        <div class="pos-terminal-footer">
            <button class="btn btn-outline-danger w-100" onclick="cancelarVenta()">Cancelar venta</button>
            <button class="btn btn-cobrar w-100" style="padding:13px;" onclick="procesarVenta()">✓ COBRAR</button>
        </div>
    </div>
</div>

<!-- Ventas recientes below POS -->
<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="section-title" style="border:none;padding:0;margin:0;">Ventas Recientes</span>
        <button class="btn btn-sm btn-outline-primary" onclick="loadHistVentas()">↺ Actualizar</button>
    </div>
    <div class="sam-table table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead><tr><th>#</th><th>Fecha</th><th>Socio</th><th>Canal</th><th class="text-end">Artículos</th><th class="text-end">Total</th></tr></thead>
            <tbody id="histVentasBody"><tr><td colspan="6" class="text-center text-muted py-4">Cargando…</td></tr></tbody>
        </table>
    </div>
</div>
```

- [ ] **Step 2: Update the cart row template inside `renderCart()`** — replace the existing `body.innerHTML +=` template:

```javascript
body.innerHTML += `<tr>
    <td>
        <strong style="font-size:.88rem;">${it.nombre}</strong>
        ${it.promo_nombre ? `<br><span class="badge badge-green" style="font-size:.65rem;">${it.promo_nombre}</span>` : ''}
    </td>
    <td class="text-end">${fmt(it.precio)}</td>
    <td class="text-center">
        <div class="pos-qty-control" style="justify-content:center;">
            <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, -1)">−</button>
            <input type="number" class="pos-qty-input" value="${it.cantidad}" min="1"
                onchange="setCantidadPos(${idx}, this.value)" oninput="setCantidadPos(${idx}, this.value)">
            <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, 1)">+</button>
        </div>
    </td>
    <td class="text-end">${it.descuento > 0 ? '<span style="color:var(--sam-red);">−' + fmt(it.descuento) + '</span>' : '—'}</td>
    <td class="text-end fw-bold">${fmt((it.precio - it.descuento) * it.cantidad)}</td>
    <td><button class="btn btn-sm" style="color:var(--sam-red);padding:4px 8px;" onclick="removeFromCart(${idx})">✕</button></td>
</tr>`;
```

- [ ] **Step 3: Add `cambiarCantidadPos()` and `setCantidadPos()` helpers** to the JS block (place near other cart functions):

```javascript
function cambiarCantidadPos(idx, delta) {
    if (!cart[idx]) return;
    cart[idx].cantidad = Math.max(1, (cart[idx].cantidad || 1) + delta);
    recalcularDescuentosCarrito();
}
function setCantidadPos(idx, val) {
    if (!cart[idx]) return;
    cart[idx].cantidad = Math.max(1, parseInt(val) || 1);
    recalcularDescuentosCarrito();
}
```

- [ ] **Step 4: Verify POS.** Click Punto de Venta in the sidebar.
  - Dark terminal panel (`#0A1628`) is on the right.
  - Sam's logo visible in the terminal header.
  - Total amount field is large and `#FFC220` yellow.
  - COBRAR button is yellow with dark text, full width at the bottom.
  - Cancelar is outlined red above COBRAR.
  - Searching for a product shows autocomplete results.
  - Adding a product shows `−` / `+` quantity buttons in the cart.
  - Linking a socio shows their name + membership pill in white inside the terminal.

- [ ] **Step 5: Commit**
```bash
git add index.php
git commit -m "feat: restyle POS section with dark terminal panel, yellow total and COBRAR button"
```

---

## Task 9: Create `.env` and clean up Bootstrap tab remnants

**Files:**
- Create: `.env`
- Modify: `index.php` — remove leftover Bootstrap tab references

- [ ] **Step 1: Create `.env` at the project root:**

```
DB_HOST=127.0.0.1
DB_NAME=ICA_final
DB_USER=
DB_PASS=
```

Fill in `DB_USER` and `DB_PASS` with the local MySQL credentials before first run.

- [ ] **Step 2: Search `index.php` for Bootstrap tab remnants** and remove them:
  - Remove any remaining `data-bs-toggle="tab"` attributes
  - Remove any remaining `role="tablist"` or `role="tab"` attributes
  - Remove `id="mainTabContent"` if still present
  - Remove `id="sociosTab"` inner Bootstrap tab nav if still present (replaced by pill-tabs)

- [ ] **Step 3: Full navigation smoke test.** Walk through every section in order:
  1. Load page → Dashboard shows, stat cards load with real data, sidebar Dashboard item has yellow left border
  2. Click Inventario → table loads with dark navy header, badges in new pill style
  3. Click Socios → form card has `4px` blue top border, pill tabs (Titulares/Familiares/Detalles) work
  4. Click Promociones → membership eligibility pills toggle colors correctly
  5. Click Compras → form card has blue top border, items sub-table dark header visible
  6. Click Punto de Venta → dark terminal on right, yellow total, yellow COBRAR button
  7. Click "↺ Actualizar" button in page header → refreshes current section's data

- [ ] **Step 4: Commit**
```bash
git add .env index.php
git commit -m "feat: add .env template and remove Bootstrap tab remnants — UI rebuild complete"
```

---

## Self-Review

**Spec coverage:**
- ✅ §3 Brand tokens — Task 1 CSS defines all `--sam-*` variables
- ✅ §4.1 Sidebar (fixed, 260px, dark, yellow left border on active) — Task 2
- ✅ §4.2 Main area + page header (title, date chip, refresh button) — Task 2
- ✅ §5.1 Dashboard (4 stat cards, ventas recientes, membership bars, quick access) — Task 3
- ✅ §5.2 Inventario (no stat cards, dark table header, gold MM badge, green promo badge, red stock=0) — Task 4
- ✅ §5.3 Socios (top-accent card, pill sub-tabs, colored membership badges) — Task 5
- ✅ §5.4 Promociones (pill membership toggles, dot status) — Task 6
- ✅ §5.5 Compras (top-accent card, dark sub-table header, blue total border) — Task 7
- ✅ §5.6 POS (dark terminal, yellow total, yellow COBRAR, dark inputs, Sam's logo in header, qty controls) — Task 8
- ✅ §6 Shared component standards (CSS) — Task 1
- ✅ §7 `.env` — Task 9
- ✅ §8 Navigation JS (`showSection`, `refreshCurrentSection`) — Task 2

**Placeholder scan:** No TBDs, TODOs, or vague steps. Every step contains complete code.

**Type consistency:**
- `showSection('dashboard')` called in Task 3 DOMContentLoaded — defined in Task 2 ✅
- `showSocioTab('listado', this)` called in Task 5 HTML — defined in Task 5 JS ✅
- `cambiarCantidadPos(idx, delta)` called in Task 8 HTML — defined in Task 8 JS ✅
- `setCantidadPos(idx, val)` called in Task 8 HTML — defined in Task 8 JS ✅
- `loadDashboard()` called in Task 3 DOMContentLoaded — defined in Task 3 JS ✅
- `recalcularDescuentosCarrito()` called in `cambiarCantidadPos`/`setCantidadPos` — already exists in original JS ✅

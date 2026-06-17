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
    --sam-blue:    #4D8BFF;
    --sam-yellow:  #FFC220;
    --sam-red:     #FF6B6B;
    --sam-dark:    #010409;
    --sam-light:   #0D1117;
    --sam-card:    #161B22;
    --sam-border:  #30363D;
    --sam-hover:   #2D333B;
    --sam-text:    #E6EDF3;
    --sam-muted:   #848D97;
    --sam-green:   #3FB950;
    --sam-purple:  #BC8CFF;
    --sidebar-w:   220px;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--sam-light); font-family: 'Inter', sans-serif; color: var(--sam-text); color-scheme: dark; }
::selection { background: rgba(77,139,255,.3); color: var(--sam-text); }
/* Custom scrollbar */
* { scrollbar-width: thin; scrollbar-color: #30363D transparent; }
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #30363D; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #4A5568; }

/* ── SIDEBAR ── */
.sam-sidebar {
    position: fixed; left: 0; top: 0; width: var(--sidebar-w);
    height: 100vh; background: var(--sam-dark); display: flex;
    flex-direction: column; z-index: 1000; overflow-y: auto;
    border-right: 1px solid rgba(255,255,255,.06);
}
.sam-sidebar-brand {
    display: flex; align-items: center; gap: 12px;
    padding: 20px 18px 16px;
    border-bottom: 1px solid rgba(255,194,32,.12);
    background: linear-gradient(160deg, rgba(255,194,32,.07) 0%, transparent 70%);
}
.sam-sidebar-brand img { width: 32px; object-fit: contain; filter: drop-shadow(0 0 8px rgba(255,194,32,.3)); }
.sam-sidebar-brand-text { font-size: .9rem; font-weight: 800; color: #fff; letter-spacing: .2px; }
.sam-sidebar-brand-sub { font-size: .58rem; color: rgba(255,255,255,.3); font-weight: 500; text-transform: uppercase; letter-spacing: 1.2px; margin-top: 1px; }
.sam-nav { flex: 1; padding: 10px 0 6px; }
.sam-nav-label { font-size: .57rem; font-weight: 700; color: rgba(255,255,255,.2); text-transform: uppercase; letter-spacing: 1.2px; padding: 6px 18px 4px; margin-top: 6px; }
.sam-nav-item {
    display: flex; align-items: center; gap: 10px; padding: 10px 18px;
    cursor: pointer; border-left: 2px solid transparent;
    color: rgba(255,255,255,.42); font-size: .82rem; font-weight: 500;
    transition: all .15s ease; user-select: none;
}
.sam-nav-item:hover { color: rgba(255,255,255,.82); background: rgba(255,255,255,.04); border-left-color: rgba(255,194,32,.25); }
.sam-nav-item.active { color: #fff; background: rgba(255,194,32,.07); border-left-color: var(--sam-yellow); font-weight: 600; }
.sam-nav-item .nav-icon { font-size: .88rem; width: 17px; text-align: center; flex-shrink: 0; opacity: .65; transition: opacity .15s; }
.sam-nav-item:hover .nav-icon,
.sam-nav-item.active .nav-icon { opacity: 1; }
.sam-sidebar-footer {
    padding: 14px 18px; border-top: 1px solid rgba(255,255,255,.05);
    display: flex; align-items: center; gap: 8px;
    font-size: .63rem; color: rgba(255,255,255,.25); font-weight: 500; text-transform: uppercase; letter-spacing: .6px;
}
.sam-status-dot {
    width: 6px; height: 6px; background: var(--sam-green); border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(63,185,80,.25); flex-shrink: 0;
    animation: pulse-dot 2.5s ease-in-out infinite;
}
@keyframes pulse-dot { 0%,100% { box-shadow: 0 0 0 2px rgba(63,185,80,.25); } 50% { box-shadow: 0 0 0 6px rgba(63,185,80,.07); } }

/* ── MAIN AREA ── */
.sam-main { margin-left: var(--sidebar-w); min-height: 100vh; padding: 28px 34px; }
.sam-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 30px; padding-bottom: 18px; border-bottom: 1px solid var(--sam-border);
}
.sam-page-title { font-size: 1.25rem; font-weight: 700; color: var(--sam-text); position: relative; padding-bottom: 7px; }
.sam-page-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 32px; height: 2px; background: var(--sam-yellow); border-radius: 2px; }
.sam-page-meta { display: flex; align-items: center; gap: 10px; }
.sam-date-chip { font-size: .73rem; font-weight: 600; color: var(--sam-muted); background: var(--sam-card); border: 1px solid var(--sam-border); border-radius: 20px; padding: 5px 13px; letter-spacing: .2px; }

/* ── SECTIONS ── */
.sam-section { display: none; }
.sam-section.active { display: block; animation: sectionIn .18s ease; }
@keyframes sectionIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

/* ── STAT CARDS ── */
.stat-card {
    background: var(--sam-card); border-radius: 12px; padding: 22px 24px;
    border: 1px solid var(--sam-border); border-left-width: 3px;
    transition: transform .18s ease, box-shadow .18s ease; position: relative; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.25);
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(0,0,0,.45); }
.stat-card-icon {
    position: absolute; top: 18px; right: 20px; font-size: 1.6rem;
    opacity: .08; font-weight: 900; line-height: 1; user-select: none;
}
.stat-card-label { font-size: .7rem; font-weight: 600; color: var(--sam-muted); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
.stat-card h3 { font-size: 2.1rem; font-weight: 800; color: var(--sam-text); letter-spacing: -1.5px; line-height: 1; }
.stat-card p { margin-top: 8px; color: var(--sam-muted); font-size: .78rem; font-weight: 500; }
.stat-card.blue   { border-left-color: var(--sam-blue); }
.stat-card.green  { border-left-color: var(--sam-green); }
.stat-card.red    { border-left-color: var(--sam-red); }
.stat-card.yellow { border-left-color: var(--sam-yellow); }

/* ── CARDS ── */
.sam-card { background: var(--sam-card); border-radius: 12px; border: 1px solid var(--sam-border); padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.25); }
.sam-card.top-accent { border-top: 3px solid var(--sam-blue); padding-top: 22px; }
.sam-card .sam-card { box-shadow: none; } /* no nested shadow */

/* ── SECTION TITLE ── */
.section-title {
    font-weight: 700; color: var(--sam-text); font-size: .88rem;
    letter-spacing: .2px;
    border-bottom: 2px solid var(--sam-yellow); padding-bottom: 7px; margin-bottom: 18px; display: inline-block;
}

/* ── TABLE TOOLBAR ── */
.table-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 14px;
}
.table-toolbar-left { display: flex; align-items: center; gap: 10px; flex: 1; min-width: 0; }
.table-toolbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

/* ── TABLES ── */
.sam-table { background: var(--sam-card); border-radius: 12px; overflow: hidden; border: 1px solid var(--sam-border); }
.sam-table .table { margin-bottom: 0; }
.sam-table thead { background: #1A2030; }
.sam-table thead th {
    background: transparent; color: var(--sam-muted); font-size: .69rem;
    letter-spacing: .8px; text-transform: uppercase; border: none; padding: 13px 20px; font-weight: 700;
    border-bottom: 2px solid var(--sam-border); white-space: nowrap;
}
.sam-table thead th:first-child { padding-left: 24px; }
.sam-table thead th:last-child  { padding-right: 24px; }
.sam-table tbody tr { border-bottom: 1px solid rgba(48,54,61,.6); transition: background .12s ease; }
.sam-table tbody tr:nth-child(even) { background: rgba(255,255,255,.018); }
.sam-table tbody tr:last-child { border-bottom: none; }
.sam-table tbody tr:hover { background: #2D333B; }
.sam-table tbody tr:hover > td { color: var(--sam-text) !important; background: transparent !important; }
.sam-table td { vertical-align: middle; font-size: .875rem; padding: 14px 20px; color: var(--sam-text); border: none; line-height: 1.45; }
.sam-table td:first-child { padding-left: 24px; }
.sam-table td:last-child  { padding-right: 24px; }
.sam-table td strong { color: var(--sam-text); font-weight: 600; }
.sam-table .td-num { color: var(--sam-muted); font-size: .8rem; font-weight: 600; font-variant-numeric: tabular-nums; }
.stock-zero { color: var(--sam-red) !important; font-weight: 700; }

/* ── FORMS ── */
.form-label { font-size: .68rem; font-weight: 700; color: var(--sam-muted); margin-bottom: 7px; display: block; text-transform: uppercase; letter-spacing: .6px; }
/* Base rule */
.form-control {
    font-size: .875rem; padding: 10px 14px; border: 1px solid var(--sam-border);
    border-radius: 8px; background-color: #0A0E17; color: var(--sam-text);
    transition: border-color .15s, box-shadow .15s; min-height: 42px; width: 100%;
}
.form-select {
    font-size: .875rem; padding: 10px 38px 10px 14px; border: 1px solid var(--sam-border);
    border-radius: 8px; background-color: #0A0E17; color: var(--sam-text);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23848D97' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center; background-size: 10px;
    transition: border-color .15s, box-shadow .15s; min-height: 42px; width: 100%;
    appearance: none; -webkit-appearance: none;
}
/* Override Bootstrap specificity — this wins */
body .form-control,
body .form-select { background-color: #0A0E17 !important; color: var(--sam-text) !important; }
body .form-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23848D97' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important; background-position: right 14px center !important; background-size: 10px !important;
}
/* Autofill dark override */
.form-control:-webkit-autofill,
.form-control:-webkit-autofill:hover,
.form-control:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #0A0E17 inset !important;
    -webkit-text-fill-color: var(--sam-text) !important;
    caret-color: var(--sam-text);
    transition: background-color 9999s ease 0s, color 9999s ease 0s;
}
.form-control:hover:not(:focus),
.form-select:hover:not(:focus) { border-color: #4A5568; }
.form-control:focus, .form-select:focus {
    border-color: var(--sam-blue); box-shadow: 0 0 0 3px rgba(77,139,255,.15); outline: none;
}
.form-control::placeholder { color: rgba(230,237,243,.3); }
.form-select option { background: #161B22; color: var(--sam-text); }
.form-hint { font-size: .72rem; color: var(--sam-muted); margin-top: 5px; line-height: 1.45; }

/* ── SEARCH WRAP (search icon via CSS) ── */
.search-wrap { position: relative; }
.search-wrap .form-control,
.search-wrap .sam-input { padding-left: 37px; }
.search-wrap::before {
    content: '';
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; pointer-events: none; z-index: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3E%3Ccircle cx='6.5' cy='6.5' r='4' stroke='%23848D97' stroke-width='1.5'/%3E%3Cpath d='M10 10L13.5 13.5' stroke='%23848D97' stroke-width='1.5' stroke-linecap='round'/%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat; background-position: center; background-size: contain;
}

/* ── PREFIX / SUFFIX INPUTS ── */
.pfx-group, .sfx-group { position: relative; display: block; }
.pfx-group .form-control { padding-left: 23px; }
.pfx-group .pfx {
    position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
    color: var(--sam-muted); font-weight: 700; font-size: .8rem; pointer-events: none; z-index: 1;
}
.sfx-group .form-control { padding-right: 30px; }
.sfx-group .sfx {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    color: var(--sam-muted); font-weight: 700; font-size: .8rem; pointer-events: none;
}

/* ── STYLED CHECKBOX CARD ── */
.check-card {
    display: flex; align-items: flex-start; gap: 10px; padding: 10px 13px;
    border-radius: 7px; border: 1px solid var(--sam-border); background: #0A0E17;
    cursor: pointer; transition: border-color .15s, background .15s; user-select: none;
}
.check-card:hover { border-color: var(--sam-blue); background: #090D14; }
.check-card input[type=checkbox] {
    width: 15px; height: 15px; accent-color: var(--sam-blue);
    flex-shrink: 0; margin-top: 1px; cursor: pointer;
}
.check-card-body { display: flex; flex-direction: column; gap: 2px; }
.check-card-text { font-size: .82rem; font-weight: 600; color: var(--sam-text); }
.check-card-hint { font-size: .7rem; color: var(--sam-muted); line-height: 1.4; }

/* ── FORM DIVIDER ── */
.form-divider {
    display: flex; align-items: center; gap: 10px; margin: 22px 0;
    font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--sam-muted);
}
.form-divider::before, .form-divider::after { content: ''; flex: 1; height: 1px; background: var(--sam-border); }

/* ── SECTION SUBTITLE ── */
.section-subtitle { font-size: .79rem; color: var(--sam-muted); margin-top: -10px; margin-bottom: 20px; line-height: 1.6; }

/* ── COUNT BADGE ── */
.count-badge {
    flex-shrink: 0; display: inline-flex; align-items: center; padding: 3px 10px;
    border-radius: 20px; font-size: .71rem; font-weight: 700;
    background: rgba(255,255,255,.05); color: var(--sam-muted); border: 1px solid var(--sam-border); white-space: nowrap;
}

/* ── EMPTY STATE ── */
.empty-state { text-align: center; padding: 36px 16px; }
.empty-state p { font-size: .82rem; color: var(--sam-muted); margin: 0; line-height: 1.5; }
.empty-state strong { display: block; font-size: .88rem; color: var(--sam-text); margin-bottom: 4px; }

/* ── DATE INPUT ── */
input[type=date].form-control { color-scheme: dark; }

/* ── NUMBER INPUT ── */
input[type=number].form-control::-webkit-inner-spin-button { opacity: .4; }

/* ── ADD BUTTON (inline in forms) ── */
.btn-add {
    width: 100%; padding: 9px; background: rgba(255,255,255,.05);
    color: var(--sam-muted); border: 1px dashed var(--sam-border);
    border-radius: 7px; cursor: pointer; font-size: .82rem; font-weight: 600;
    transition: all .15s; display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-add:hover { border-color: var(--sam-blue); color: var(--sam-blue); background: rgba(77,139,255,.06); }

/* ── BUTTONS ── */
.btn { font-weight: 600; padding: 9px 17px; border-radius: 8px; transition: all .15s ease; font-size: .83rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 5px; }
.btn-primary   { background: var(--sam-blue); color: #fff; }
.btn-primary:hover { background: #3A75E8; box-shadow: 0 3px 12px rgba(77,139,255,.35); color: #fff; }
.btn-success   { background: var(--sam-green); color: #0D1117; }
.btn-success:hover { background: #2EA043; color: #0D1117; }
.btn-danger    { background: var(--sam-red); color: #fff; }
.btn-danger:hover { background: #E05555; color: #fff; }
.btn-outline-primary { background: transparent; color: var(--sam-blue); border: 1px solid rgba(77,139,255,.4); }
.btn-outline-primary:hover { background: rgba(77,139,255,.12); color: var(--sam-blue); border-color: var(--sam-blue); }
.btn-outline-danger { background: transparent; color: var(--sam-red); border: 1px solid rgba(255,107,107,.3); }
.btn-outline-danger:hover { background: rgba(255,107,107,.1); color: var(--sam-red); }
.btn-outline-secondary { background: transparent; color: var(--sam-muted); border: 1px solid var(--sam-border); }
.btn-outline-secondary:hover { background: var(--sam-hover); color: var(--sam-text); }
.btn-outline-warning { background: transparent; color: var(--sam-yellow); border: 1px solid rgba(255,194,32,.3); }
.btn-outline-warning:hover { background: rgba(255,194,32,.1); color: var(--sam-yellow); }
.btn-sm { padding: 5px 12px; font-size: .77rem; }
.btn-cobrar { background: var(--sam-yellow) !important; color: #0D1117 !important; font-weight: 800 !important; font-size: .95rem !important; letter-spacing: .3px; }
.btn-cobrar:hover { background: #E6AE00 !important; box-shadow: 0 4px 16px rgba(255,194,32,.35); }
.btn-link { background: none; border: none; color: var(--sam-blue); text-decoration: underline; padding: 0; cursor: pointer; }
.w-100 { width: 100%; }

/* ── BADGES ── */
.badge { padding: 4px 10px; font-size: .7rem; font-weight: 700; border-radius: 20px; display: inline-block; letter-spacing: .2px; }
.badge-blue   { background: rgba(77,139,255,.15); color: #7DAAFF; border: 1px solid rgba(77,139,255,.2); }
.badge-green  { background: rgba(63,185,80,.15); color: #56D364; border: 1px solid rgba(63,185,80,.2); }
.badge-red    { background: rgba(255,107,107,.15); color: #FF8080; border: 1px solid rgba(255,107,107,.2); }
.badge-yellow { background: rgba(255,194,32,.15); color: #FFC220; border: 1px solid rgba(255,194,32,.2); }
.badge-gray   { background: rgba(255,255,255,.07); color: var(--sam-muted); border: 1px solid var(--sam-border); }
.badge-purple { background: rgba(188,140,255,.15); color: #BC8CFF; border: 1px solid rgba(188,140,255,.2); }
.badge-gold   { background: rgba(255,194,32,.2); color: var(--sam-yellow); border-radius: 20px; font-size: .7rem; padding: 3px 9px; font-weight: 700; border: 1px solid rgba(255,194,32,.25); }
.badge-promo  { background: rgba(63,185,80,.15); color: #56D364; border-radius: 20px; font-size: .7rem; padding: 3px 9px; font-weight: 600; border: 1px solid rgba(63,185,80,.2); }
.badge-tipo   { font-size: .7rem; padding: 4px 10px; }
.bg-success   { background: rgba(63,185,80,.15) !important; color: #56D364 !important; }
.badge-desc-ok  { color: var(--sam-green); font-size: .78rem; font-weight: 700; }
.badge-desc-no  { color: var(--sam-muted); font-size: .78rem; }

/* ── MEMBERSHIP PILL TOGGLES ── */
.memb-pill-toggle {
    display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px;
    border-radius: 20px; cursor: pointer; border: 1px solid var(--sam-border);
    font-size: .79rem; font-weight: 600; transition: all .15s; user-select: none;
    color: var(--sam-muted); background: var(--sam-card);
}
.memb-pill-toggle:hover { border-color: var(--sam-blue); color: var(--sam-text); }
.memb-pill-toggle input { display: none; }
.memb-pill-toggle.active-CLASICA  { background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.18); color: var(--sam-text); }
.memb-pill-toggle.active-BENEFITS { background: rgba(77,139,255,.12); border-color: rgba(77,139,255,.35); color: #7DAAFF; }
.memb-pill-toggle.active-PLUS     { background: rgba(188,140,255,.12); border-color: rgba(188,140,255,.35); color: var(--sam-purple); }

/* ── PILL SUB-TABS (Socios) ── */
.pill-tabs { display: flex; gap: 6px; margin-bottom: 16px; }
.pill-tab {
    padding: 7px 17px; border-radius: 20px; cursor: pointer; font-size: .79rem;
    font-weight: 600; border: 1px solid var(--sam-border); background: var(--sam-card);
    color: var(--sam-muted); transition: all .15s;
}
.pill-tab:hover { border-color: var(--sam-blue); color: var(--sam-blue); }
.pill-tab.active { background: var(--sam-blue); border-color: var(--sam-blue); color: #fff; }

/* ── POS ── */
.pos-wrap { display: flex; gap: 18px; height: calc(100vh - 175px); min-height: 520px; }
.pos-light { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 14px; }
.pos-terminal {
    flex: 0 0 300px; background: var(--sam-dark); border-radius: 12px;
    border: 1px solid rgba(255,255,255,.07);
    display: flex; flex-direction: column; overflow: hidden;
}
.pos-terminal-header { padding: 15px 18px; border-bottom: 1px solid rgba(255,255,255,.07); display: flex; align-items: center; gap: 10px; }
.pos-terminal-header img { width: 24px; }
.pos-terminal-header span { font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: 1px; }
.pos-terminal-body { flex: 1; overflow-y: auto; padding: 16px 18px; display: flex; flex-direction: column; gap: 14px; }
.pos-terminal-footer { padding: 15px 18px; border-top: 1px solid rgba(255,255,255,.07); display: flex; flex-direction: column; gap: 8px; }
.input-dark {
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
    border-radius: 7px; color: #fff; padding: 0 11px; font-size: .84rem; width: 100%;
    height: 36px; min-height: 36px; line-height: 36px;
}
.input-dark::placeholder { color: rgba(255,255,255,.22); }
.input-dark:focus { outline: none; border-color: rgba(255,194,32,.5); background: rgba(255,255,255,.07); }
.select-dark {
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
    border-radius: 7px; color: #fff; padding: 0 11px; font-size: .84rem; width: 100%;
    height: 36px; min-height: 36px; line-height: 36px;
}
.select-dark option { background: #0D1117; color: #fff; }
.select-dark:focus { outline: none; border-color: rgba(255,194,32,.5); }
.pos-label { font-size: .66rem; font-weight: 700; color: rgba(255,255,255,.32); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 8px; }
.pos-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid rgba(255,255,255,.05); }
.pos-row:last-child { border-bottom: none; }
.pos-row-label { font-size: .83rem; color: rgba(255,255,255,.42); font-weight: 500; }
.pos-row-value { font-size: .88rem; color: #fff; font-weight: 600; }
.pos-total-amount { font-size: 2.2rem; font-weight: 800; color: var(--sam-yellow); letter-spacing: -1px; text-align: right; line-height: 1; }
.pos-total-label { font-size: .66rem; font-weight: 700; color: rgba(255,255,255,.3); text-transform: uppercase; letter-spacing: 1px; text-align: right; margin-bottom: 6px; }
.pos-divider { border: none; border-top: 1px solid rgba(255,255,255,.06); margin: 4px 0; }
.socio-terminal-block { background: rgba(255,255,255,.04); border-radius: 7px; padding: 9px 11px; border: 1px solid rgba(255,255,255,.08); }
.socio-terminal-active { display: flex; align-items: center; gap: 8px; }
.socio-terminal-name { flex: 1; font-size: .84rem; font-weight: 600; color: #fff; }
.socio-terminal-pill { font-size: .62rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; background: rgba(255,194,32,.15); color: var(--sam-yellow); border: 1px solid rgba(255,194,32,.25); }
.socio-terminal-remove { background: rgba(255,255,255,.08); border: none; color: rgba(255,255,255,.5); border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: .75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background .15s; }
.socio-terminal-remove:hover { background: rgba(255,255,255,.16); color: #fff; }
.pos-cart-wrap { flex: 1; overflow-y: auto; }
.pos-qty-control { display: flex; align-items: center; gap: 4px; }
.pos-qty-btn { width: 26px; height: 26px; border: 1px solid var(--sam-border); background: rgba(255,255,255,.04); border-radius: 6px; cursor: pointer; font-size: .9rem; display: flex; align-items: center; justify-content: center; color: var(--sam-text); transition: all .12s; }
.pos-qty-btn:hover { border-color: var(--sam-blue); color: var(--sam-blue); background: rgba(77,139,255,.12); }
.pos-qty-input { width: 40px; text-align: center; border: 1px solid var(--sam-border); border-radius: 6px; padding: 3px 4px; font-size: .82rem; font-weight: 700; background: #0D1117; color: var(--sam-text); }

/* ── SEARCH DROPDOWNS ── */
#searchResults, #socioResults {
    position: absolute; z-index: 1050; width: 100%; background: #1C2128;
    border: 1px solid var(--sam-border); border-top: none; border-radius: 0 0 10px 10px;
    max-height: 300px; overflow-y: auto; box-shadow: 0 16px 40px rgba(0,0,0,.55);
}
#searchResults .res-item, #socioResults > div {
    padding: 11px 14px; cursor: pointer; border-bottom: 1px solid rgba(48,54,61,.5); font-size: .86rem; transition: background .1s; color: var(--sam-text);
}
#searchResults .res-item:hover, #socioResults > div:hover { background: #252D38; }
#searchResults .res-item:last-child, #socioResults > div:last-child { border-bottom: none; }
#searchResults .res-sku { color: var(--sam-muted); font-size: .73rem; margin-top: 2px; }
.res-memb-tag { font-size: .66rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: rgba(77,139,255,.15); color: #7DAAFF; }

/* ── DASHBOARD BARS ── */
.memb-bar-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.memb-bar-row:last-child { margin-bottom: 0; }
.memb-bar-label { font-size: .77rem; font-weight: 600; color: var(--sam-text); width: 76px; flex-shrink: 0; }
.memb-bar-track { flex: 1; height: 8px; background: rgba(255,255,255,.06); border-radius: 8px; overflow: hidden; }
.memb-bar-fill { height: 100%; border-radius: 8px; transition: width .7s cubic-bezier(.4,0,.2,1); }
.memb-bar-count { font-size: .77rem; font-weight: 700; color: var(--sam-muted); width: 32px; text-align: right; flex-shrink: 0; }

/* ── COMPRAS ── */
.compra-items-sub { border-radius: 10px; border: 1px solid var(--sam-border); overflow: hidden; margin-bottom: 14px; background: var(--sam-card); }
.compra-items-sub thead th { background: #1A2030; color: var(--sam-muted); font-size: .68rem; padding: 11px 16px; text-transform: uppercase; letter-spacing: .7px; border-bottom: 2px solid var(--sam-border); border-top: none; }
.compra-items-sub tbody td { padding: 12px 16px; border-top: 1px solid rgba(48,54,61,.6); color: var(--sam-text); font-size: .86rem; }
.compra-items-sub tbody tr:first-child td { border-top: none; }
.compra-total-row { border-left: 3px solid var(--sam-blue); padding: 13px 18px; background: rgba(77,139,255,.06); display: flex; justify-content: space-between; align-items: center; border-radius: 0 8px 8px 0; margin-bottom: 18px; border: 1px solid rgba(77,139,255,.15); }
.compra-item-input { font-size: .82rem; padding: 5px 10px; border: 1px solid var(--sam-border); border-radius: 6px; color: var(--sam-text); background: #0D1117; }
.compra-item-input:focus { border-color: var(--sam-blue); outline: none; }

/* ── TOAST ── */
#toastArea { position: fixed; bottom: 24px; right: 24px; z-index: 9999; pointer-events: none; display: flex; flex-direction: column; gap: 8px; }
.sam-toast {
    padding: 12px 16px; border-radius: 10px; font-weight: 600;
    box-shadow: 0 8px 32px rgba(0,0,0,.6);
    animation: toastIn .25s cubic-bezier(.16,1,.3,1); min-width: 260px; font-size: .855rem; pointer-events: all;
    border: 1px solid transparent; backdrop-filter: blur(4px);
}
.sam-toast.success { background: rgba(26,61,34,.95); border-color: rgba(63,185,80,.35); color: #56D364; }
.sam-toast.error   { background: rgba(61,26,26,.95); border-color: rgba(255,107,107,.35); color: #FF8080; }
.sam-toast.warn    { background: rgba(61,46,10,.95); border-color: rgba(255,194,32,.35); color: #FFC220; }
@keyframes toastIn { from { transform: translateX(60px) scale(.95); opacity: 0; } to { transform: translateX(0) scale(1); opacity: 1; } }

/* ── UTILITIES ── */
.spinner-sam { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(77,139,255,.2); border-top-color: var(--sam-blue); border-radius: 50%; animation: spin .6s linear infinite; }
.spinner-border-sm { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.2); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.alert { border-radius: 7px; padding: 10px 13px; font-size: .82rem; border: 1px solid; }
.alert-info { background: rgba(77,139,255,.1); color: #7DAAFF; border-color: rgba(77,139,255,.2); }
.mb-2 { margin-bottom: 10px; }
.mb-3 { margin-bottom: 18px; }
.mt-2 { margin-top: 10px; }
.mt-3 { margin-top: 18px; }
.mt-4 { margin-top: 24px; }
.text-muted { color: var(--sam-muted) !important; }
.text-end { text-align: right !important; }
.text-center { text-align: center !important; }
.fw-bold { font-weight: 700 !important; }
.fw-semibold { font-weight: 600 !important; }
.fw-600 { font-weight: 600; }
.small { font-size: .79rem; }
.text-xs { font-size: .7rem; }
.text-nowrap { white-space: nowrap; }
.sam-input { border: 1px solid var(--sam-border); border-radius: 7px; padding: 9px 12px; font-size: .865rem; background: #0D1117; color: var(--sam-text); }
.d-flex { display: flex; }
.d-none { display: none !important; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.gap-2 { gap: 10px; }
.gap-3 { gap: 14px; }
.gap-4 { gap: 22px; }
.flex-wrap { flex-wrap: wrap; }
.py-3 { padding-top: 16px; padding-bottom: 16px; }
.py-4 { padding-top: 24px; padding-bottom: 24px; }
#pagoResumen { font-size: .79rem; margin-top: 5px; display: flex; justify-content: space-between; color: rgba(255,255,255,.42); }
#pagoResumen .pago-faltante { color: #FF8080; font-weight: 700; }
#pagoResumen .pago-ok { color: var(--sam-green); font-weight: 700; }
.acciones-socio { display: flex; gap: 6px; flex-wrap: nowrap; }
#cambioInfo { font-size: .82rem; font-weight: 600; color: var(--sam-green); margin-top: 4px; }
.todos-check-wrap label { font-size: .82rem; font-weight: 600; color: var(--sam-muted); cursor: pointer; display: flex; align-items: center; gap: 8px; }
@media (max-width: 900px) { .sam-main { margin-left: 0; padding: 13px; } .sam-sidebar { display: none; } .pos-terminal { flex: 0 0 260px; } }
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
        <div class="sam-nav-label">General</div>
        <div class="sam-nav-item active" data-section="dashboard" onclick="showSection('dashboard')">
            <span class="nav-icon">▣</span> Dashboard
        </div>
        <div class="sam-nav-label">Gestión</div>
        <div class="sam-nav-item" data-section="inventario" onclick="showSection('inventario')">
            <span class="nav-icon">▦</span> Inventario
        </div>
        <div class="sam-nav-item" data-section="socios" onclick="showSection('socios')">
            <span class="nav-icon">◉</span> Socios
        </div>
        <div class="sam-nav-item" data-section="promociones" onclick="showSection('promociones')">
            <span class="nav-icon">◈</span> Promociones
        </div>
        <div class="sam-nav-label">Operaciones</div>
        <div class="sam-nav-item" data-section="compras" onclick="showSection('compras')">
            <span class="nav-icon">◆</span> Compras
        </div>
        <div class="sam-nav-item" data-section="ventas" onclick="showSection('ventas')">
            <span class="nav-icon">▷</span> Punto de Venta
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
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <span class="stat-card-icon">◉</span>
                <div class="stat-card-label">Socios activos</div>
                <h3 id="dash-socios">—</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <span class="stat-card-icon">$</span>
                <div class="stat-card-label">Ingresos hoy</div>
                <h3 id="dash-ingresos">—</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card red">
                <span class="stat-card-icon">!</span>
                <div class="stat-card-label">Sin existencia</div>
                <h3 id="dash-sinstock">—</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card yellow">
                <span class="stat-card-icon">%</span>
                <div class="stat-card-label">Promociones activas</div>
                <h3 id="dash-promos">—</h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
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
                Nueva Venta
            </button>
            <button class="btn btn-outline-primary" onclick="showSection('socios'); setTimeout(()=>document.getElementById('socioNombre')?.focus(),100)">
                Nuevo Socio
            </button>
            <button class="btn btn-outline-primary" onclick="showSection('compras'); setTimeout(()=>document.getElementById('compraProveedor')?.focus(),100)">
                Registrar Compra
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
            <div class="search-wrap" style="flex:1;">
                <input type="text" id="invSearch" class="form-control" placeholder="Buscar por nombre, SKU o marca…" oninput="clearTimeout(invTimer);invTimer=setTimeout(()=>loadInventario(this.value),350)">
            </div>
            <span class="count-badge" id="invCount" style="display:none;"></span>
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
    <div class="d-flex gap-4" style="align-items:flex-start;gap:28px;">
        <div style="flex:0 0 320px; min-width:0;">
            <div class="sam-card top-accent">
                <div id="socioFormNuevo">
                    <span class="section-title">Nuevo Socio Titular</span>
                    <p class="section-subtitle">Completa los datos para registrar una nueva membresía.</p>
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" id="socioNombre" class="form-control" placeholder="Ej: Juan Pérez García" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico <span style="color:var(--sam-muted);font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
                        <input type="email" id="socioCorreo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono <span style="color:var(--sam-muted);font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
                        <input type="tel" id="socioTelefono" class="form-control" placeholder="55 1234 5678">
                    </div>
                    <div class="form-divider">Membresía</div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de membresía</label>
                        <select id="socioTipoMembresia" class="form-select"><option value="">Seleccionar tipo…</option></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de vencimiento</label>
                        <input type="date" id="socioFechaFin" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100 mb-2" onclick="crearSocioTitular()">Registrar Socio Titular</button>
                    <button class="btn btn-outline-secondary w-100" onclick="mostrarFormFamiliar()">Vincular un familiar existente</button>
                </div>
                <div id="socioFormFamiliar" style="display:none;">
                    <span class="section-title">Vincular Familiar</span>
                    <p class="section-subtitle">El familiar hereda membresía y vencimiento del titular.</p>
                    <div class="mb-3">
                        <label class="form-label">Socio Titular</label>
                        <select id="socioTitularSel" class="form-select"><option value="">Seleccionar titular…</option></select>
                    </div>
                    <div class="form-divider">Datos del Familiar</div>
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" id="familiarNombre" class="form-control" placeholder="Nombre completo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo <span style="color:var(--sam-muted);font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
                        <input type="email" id="familiarCorreo" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono <span style="color:var(--sam-muted);font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span></label>
                        <input type="tel" id="familiarTelefono" class="form-control" placeholder="55 1234 5678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parentesco</label>
                        <select id="familiarParentesco" class="form-select">
                            <option value="CONYUGE">Cónyuge</option>
                            <option value="HIJO">Hijo/a</option>
                            <option value="PADRE">Padre/Madre</option>
                            <option value="HERMANO">Hermano/a</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="check-card" for="familiarComplementaria">
                            <input type="checkbox" id="familiarComplementaria">
                            <div class="check-card-body">
                                <span class="check-card-text">Tarjeta complementaria gratis</span>
                                <span class="check-card-hint">Solo se permite 1 por titular</span>
                            </div>
                        </label>
                    </div>
                    <button class="btn btn-success w-100 mb-2" onclick="crearSocioFamiliar()">Vincular Familiar</button>
                    <button class="btn btn-outline-secondary w-100" onclick="mostrarFormNuevo()">← Volver</button>
                </div>
            </div>
        </div>
        <div style="flex:1; min-width:0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="search-wrap" style="max-width:280px;flex:1;">
                    <input type="text" id="sociosBuscar" class="form-control" placeholder="Buscar socio por nombre o número…">
                </div>
                <button class="btn btn-sm btn-outline-primary" onclick="loadSocios(); loadFamiliares();">↺ Actualizar</button>
            </div>
            <div class="pill-tabs">
                <div class="pill-tab active" onclick="showSocioTab('listado', this)">Titulares</div>
                <div class="pill-tab" onclick="showSocioTab('familiares', this)">Familiares</div>
                <div class="pill-tab" onclick="showSocioTab('detalles', this)">Detalles</div>
            </div>
            <div id="socios-listado">
                <div class="sam-table table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>#</th><th>Número</th><th>Nombre</th><th>Membresía</th><th class="text-center">Familiares</th><th>Vencimiento</th><th class="text-end">Cashback</th><th>Acciones</th></tr></thead>
                        <tbody id="sociosBody"><tr><td colspan="8" class="text-center text-muted py-4">Cargando socios…</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div id="socios-familiares" style="display:none;">
                <div class="d-flex gap-2 mb-3">
                    <div class="search-wrap" style="max-width:300px;flex:1;">
                        <input type="text" id="familiarBuscar" class="form-control" placeholder="Buscar familiar…">
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadFamiliares()">↺</button>
                </div>
                <div class="sam-table table-responsive">
                    <table class="table table-sm mb-0">
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
    <div class="d-flex gap-4" style="align-items:flex-start;">
        <div style="flex:0 0 360px; min-width:0;">
            <div class="sam-card top-accent">
                <span class="section-title">Nueva Promoción</span>
                <p class="section-subtitle">Elige el producto, define el descuento y a quién aplica.</p>
                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select id="promoProducto" class="form-select"><option value="">Cargando…</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de la promoción</label>
                    <input type="text" id="promoNombre" class="form-control" placeholder="Ej: Promo Verano 2026">
                </div>
                <div class="form-divider">Tipo de descuento — elige uno</div>
                <div class="d-flex gap-3 mb-3">
                    <div style="flex:1;">
                        <label class="form-label">Descuento porcentual</label>
                        <div class="sfx-group">
                            <input type="number" id="promoDescPct" class="form-control" placeholder="0" min="0" max="100" step="0.01">
                            <span class="sfx">%</span>
                        </div>
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Descuento en pesos</label>
                        <div class="pfx-group">
                            <span class="pfx">$</span>
                            <input type="number" id="promoDescMonto" class="form-control" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="form-divider">Vigencia</div>
                <div class="d-flex gap-3 mb-3">
                    <div style="flex:1;">
                        <label class="form-label">Inicio</label>
                        <input type="date" id="promoFechaIni" class="form-control">
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Fin</label>
                        <input type="date" id="promoFechaFin" class="form-control">
                    </div>
                </div>
                <div class="form-divider">Elegibilidad</div>
                <div class="mb-3">
                    <label class="check-card" for="promoAplicaTodos" style="margin-bottom:8px;">
                        <input type="checkbox" id="promoAplicaTodos" onchange="toggleTodosCheck()">
                        <div class="check-card-body">
                            <span class="check-card-text">Aplica a todos los socios</span>
                            <span class="check-card-hint">Sin importar el tipo de membresía</span>
                        </div>
                    </label>
                    <div id="membresiaChecks" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                    <p class="form-hint" style="margin-top:8px;">Si no marcas "todos", solo los tipos seleccionados verán el descuento.</p>
                </div>
                <button class="btn btn-primary w-100" onclick="crearPromo()">Registrar Promoción</button>
            </div>
        </div>
        <div style="flex:1; min-width:0;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="section-title" style="border:none;padding:0;margin:0;">Promociones Registradas</span>
                <button class="btn btn-sm btn-outline-primary" onclick="loadPromos()">↺ Actualizar</button>
            </div>
            <div class="sam-table table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th>Producto</th><th>Promoción</th><th class="text-end">Desc %</th><th class="text-end">Desc $</th><th>Vigencia</th><th>Membresías</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody id="promoBody"><tr><td colspan="9" class="text-center py-4 text-muted">Cargando…</td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 4 · COMPRAS
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-compras">
    <div class="d-flex gap-4" style="align-items:flex-start;">
        <div style="flex:1; min-width:0;">
            <div class="sam-card top-accent">
                <span class="section-title">Registrar Recepción de Mercancía</span>
                <p class="section-subtitle">Registra la entrada de productos al inventario.</p>
                <div class="mb-3">
                    <label class="form-label">Proveedor</label>
                    <select id="compraProveedor" class="form-select"><option value="">Seleccionar proveedor…</option></select>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div style="flex:1;">
                        <label class="form-label">Zona de destino</label>
                        <select id="compraZona" class="form-select"><option value="">Seleccionar zona…</option></select>
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Destino</label>
                        <select id="compraEsReserva" class="form-select">
                            <option value="0">Piso de venta</option>
                            <option value="1">Bodega / Reserva</option>
                        </select>
                    </div>
                </div>
                <div class="form-divider">Productos a recibir</div>
                <div class="mb-3">
                    <label class="form-label">Producto</label>
                    <select id="compraProductoSel" class="form-select"><option value="">Seleccionar producto…</option></select>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <div style="flex:1;">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="compraCantidad" class="form-control" placeholder="1" min="1">
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Precio de costo</label>
                        <div class="pfx-group">
                            <span class="pfx">$</span>
                            <input type="number" id="compraPrecio" class="form-control" placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>
                </div>
                <button class="btn-add mb-3" onclick="agregarItemCompra()">+ Agregar producto a la lista</button>
                <span class="section-title">Items Agregados</span>
                <div class="compra-items-sub">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center" style="width:90px;">Cant.</th>
                                <th class="text-center" style="width:120px;">Precio costo</th>
                                <th class="text-end" style="width:100px;">Total</th>
                                <th style="width:42px;"></th>
                            </tr>
                        </thead>
                        <tbody id="compraItemsBody">
                            <tr id="compraEmptyRow"><td colspan="5" class="text-center text-muted py-4">Sin productos agregados</td></tr>
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
                <table class="table table-sm mb-0">
                    <thead><tr><th>Fecha</th><th>Producto</th><th class="text-end">Cant.</th><th>Proveedor</th></tr></thead>
                    <tbody id="histCompraBody"><tr><td colspan="4" class="text-center text-muted py-3">Cargando…</td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════
     TAB 5 · PUNTO DE VENTA
═══════════════════════════════════════════════ -->
<div class="sam-section" id="section-ventas">
<div class="pos-wrap">
    <!-- LEFT: Light zone -->
    <div class="pos-light">
        <div class="d-flex gap-3" style="flex-shrink:0;">
            <div style="flex:0 0 170px;">
                <label class="form-label">Canal de venta</label>
                <select id="posCanal" class="form-select">
                    <option value="CAJA">Caja</option>
                    <option value="SELF">Self-checkout</option>
                    <option value="SCAN_GO">Scan &amp; Go</option>
                </select>
            </div>
            <div style="flex:1; position:relative;">
                <label class="form-label">Buscar producto</label>
                <div class="search-wrap">
                    <input type="text" id="posSearch" class="form-control" placeholder="Nombre, SKU o marca…" oninput="buscarProductoPos()" autocomplete="off">
                </div>
                <div id="searchResults"></div>
            </div>
        </div>
        <div class="pos-cart-wrap sam-table table-responsive">
            <table class="table mb-0">
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
                            <span></span>
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
                <div id="pagosContainer"></div>
                <button class="btn-add" style="margin-top:6px;border-color:rgba(255,255,255,.12);color:rgba(255,255,255,.5);" onmouseover="this.style.borderColor='rgba(255,194,32,.4)';this.style.color='#FFC220';this.style.background='rgba(255,194,32,.05)';" onmouseout="this.style.borderColor='rgba(255,255,255,.12)';this.style.color='rgba(255,255,255,.5)';this.style.background='';" onclick="agregarPago()">+ Agregar método de pago</button>
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
        <table class="table table-sm mb-0">
            <thead><tr><th>#</th><th>Fecha</th><th>Socio</th><th>Canal</th><th class="text-end">Artículos</th><th class="text-end">Total</th></tr></thead>
            <tbody id="histVentasBody"><tr><td colspan="6" class="text-center text-muted py-4">Cargando…</td></tr></tbody>
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
    if (countEl) { countEl.textContent = data.length + ' producto' + (data.length !== 1 ? 's' : ''); countEl.style.display = data.length ? 'inline-flex' : 'none'; }
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
    body.innerHTML = '<tr><td colspan="9" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';
    const res = await api('promociones.php?action=list_promos');
    if (!res.success) { toast(res.error,'error'); return; }
    body.innerHTML = res.data.length ? '' : '<tr><td colspan="9" class="text-center text-muted py-4">Sin promociones</td></tr>';
    res.data.forEach(r => {
        const badge = r.activo == 1
            ? '<span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;color:var(--sam-green);">● Activa</span>'
            : '<span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;color:var(--sam-muted);">● Inactiva</span>';
        let membTag = '';
        if (r.aplica_a_todos == '1') { membTag = '<span class="badge badge-gray">Todos</span>'; }
        else if (r.membresias_aplicables) {
            membTag = r.membresias_aplicables.split(', ').map(m => {
                const style = m === 'PLUS' ? 'background:#7C3AED;' : (m === 'BENEFITS' ? 'background:#003DA5;' : 'background:#6B7280;');
                return `<span class="badge" style="font-size:.7rem;${style}color:#fff;">${esc(m)}</span>`;
            }).join(' ');
        } else { membTag = '<span class="text-muted small">—</span>'; }
        body.innerHTML += `<tr>
            <td class="text-muted small">${Number(r.id)}</td>
            <td><strong>${esc(r.producto_nombre)}</strong><br><span class="text-muted small">${esc(r.sku)}</span></td>
            <td>${esc(r.nombre_promo)}</td>
            <td class="text-end">${Number(r.descuento_pct)>0?Number(r.descuento_pct)+'%':'—'}</td>
            <td class="text-end">${Number(r.descuento_monto)>0?fmt(r.descuento_monto):'—'}</td>
            <td class="small">${esc(r.fecha_inicio||'—')}<br>al ${esc(r.fecha_fin||'—')}</td>
            <td>${membTag}</td>
            <td>${badge}</td>
            <td>
                <button class="btn btn-outline-warning btn-sm me-1" onclick="togglePromo(${Number(r.id)})">${r.activo=='1'?'⏸ Desactivar':'▶ Activar'}</button>
                <button class="btn btn-outline-danger btn-sm" onclick="deletePromo(${Number(r.id)})">Eliminar</button>
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
        return `<tr>
            <td><strong style="font-size:.85rem;">${esc(it.sku ? '['+it.sku+'] ' : '')}${esc(it.nombre)}</strong></td>
            <td class="text-center"><input type="number" class="compra-item-input" value="${Number(it.cantidad)}" min="1" step="1" style="width:70px;" onchange="updateCompraItem(${i},'cantidad',this.value)" oninput="updateCompraItem(${i},'cantidad',this.value)"></td>
            <td class="text-center"><input type="number" class="compra-item-input" value="${it.precio_costo || ''}" min="0" step="0.01" placeholder="0.00" style="width:85px;" onchange="updateCompraItem(${i},'precio_costo',this.value)" oninput="updateCompraItem(${i},'precio_costo',this.value)"></td>
            <td class="text-end" style="font-weight:700;color:var(--sam-blue);" id="sub-compra-${i}">${sub > 0 ? fmt(sub) : '—'}</td>
            <td class="text-center"><button class="btn btn-sm" style="color:var(--sam-red);padding:3px 8px;" onclick="removeCompraItem(${i})">✕</button></td>
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
            <td class="small">${esc(r.fecha)}</td><td><strong>${esc(r.producto)}</strong></td>
            <td class="text-end">${Number(r.cantidad)}</td><td class="small">${esc(r.proveedor||'—')}</td>
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
            <td>
                <strong style="font-size:.88rem;">${esc(it.nombre)}</strong>
                ${it.promo_nombre ? `<br><span class="badge badge-green" style="font-size:.65rem;">${esc(it.promo_nombre)}</span>` : ''}
            </td>
            <td class="text-end">${fmt(it.precio)}</td>
            <td class="text-center">
                <div class="pos-qty-control" style="justify-content:center;">
                    <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, -1)">−</button>
                    <input type="number" class="pos-qty-input" value="${Number(it.cantidad)}" min="1"
                        onchange="setCantidadPos(${idx}, this.value)" oninput="setCantidadPos(${idx}, this.value)">
                    <button class="pos-qty-btn" onclick="cambiarCantidadPos(${idx}, 1)">+</button>
                </div>
            </td>
            <td class="text-end">${descTag}</td>
            <td class="text-end fw-bold">${fmt(sub - desc)}</td>
            <td><button class="btn btn-sm" style="color:var(--sam-red);padding:4px 8px;" onclick="removeFromCart(${idx})">✕</button></td>
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
    d.className = 'pago-row d-flex gap-2 mb-2';
    d.innerHTML = `<select class="select-dark pago-metodo" onchange="actualizarResumenPago()" style="flex:1;">
        <option value="EFECTIVO">Efectivo</option>
        <option value="TARJETA">Tarjeta</option>
        <option value="CASHI">Cashi</option>
        <option value="INBURSA">Inbursa</option>
        <option value="VALES">Vales</option>
    </select>
    <input type="number" class="input-dark pago-monto" placeholder="Monto" min="0" step="0.01" oninput="actualizarResumenPago()" style="width:88px;flex-shrink:0;">
    <button class="socio-terminal-remove" style="flex-shrink:0;" onclick="this.closest('.pago-row').remove(); actualizarResumenPago();">✕</button>`;
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
            d.style.cssText = 'padding:9px 13px;cursor:pointer;border-bottom:1px solid #30363D;font-size:.85rem';
            const membColors = { CLASICA:'#6B7280', BENEFITS:'#1D4ED8', PLUS:'#7C3AED' };
            const color = membColors[s.membresia] || '#6B7280';
            d.innerHTML = `<strong>${s.nombre}</strong> — <span class="text-muted">#${s.numero_socio}</span><br>
                           <span class="badge" style="background:${color};color:#fff;">${s.membresia}</span>
                           <span class="text-muted" style="font-size:.8rem;"> Cashback: ${fmt(s.saldo_cashback)}</span>`;
            d.onmouseover = () => d.style.background = '#21262D';
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
            <td class="text-muted small">#${Number(r.id)}</td>
            <td class="small">${esc(r.fecha)}</td>
            <td>${r.socio ? esc(r.socio) : '<span class="text-muted">—</span>'}</td>
            <td><span class="badge badge-blue">${esc(r.canal)}</span></td>
            <td class="text-end">${Number(r.num_items)}</td>
            <td class="text-end fw-bold">${fmt(r.total)}</td>
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
                    <button class="btn btn-sm btn-danger" title="Eliminar titular y sus familiares" onclick="eliminarTitular(${r.socio_membresia_id}, ${JSON.stringify(r.nombre)}, ${r.num_familiares || 0})">Eliminar</button>
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
    const colorMap = {'CLASICA':'#6B7280','BENEFITS':'#1D4ED8','PLUS':'#7C3AED'};

    let html = `
        <div style="background:#1C2128;padding:18px;border-radius:10px;margin-bottom:18px;border:1px solid #30363D;">
            <h5 class="mb-3" style="color:var(--sam-text);font-size:1rem;font-weight:700;">${esc(titular.nombre)}</h5>
            <div class="row g-3">
                <div class="col-6">
                    <div><strong>Número:</strong> ${esc(titular.numero_socio)}</div>
                    <div><strong>Correo:</strong> ${titular.correo ? esc(titular.correo) : '—'}</div>
                    <div><strong>Teléfono:</strong> ${titular.telefono ? esc(titular.telefono) : '—'}</div>
                </div>
                <div class="col-6">
                    <div><strong>Membresía:</strong> <span class="badge" style="background:${colorMap[titular.tipo_membresia]||'#6B7280'};color:#fff;">${esc(titular.tipo_membresia)}</span></div>
                    <div><strong>Vencimiento:</strong> ${esc(titular.vencimiento)}</div>
                    <div><strong>Cashback:</strong> <span style="color:var(--sam-green);font-weight:700;font-size:1rem;">${fmt(titular.saldo_cashback)}</span></div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-sm btn-primary" onclick="renovarMembresia(${titular.id})">Renovar Membresía</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarTitular(${titular.id}, ${JSON.stringify(titular.nombre)}, ${familiares.length})">Eliminar Titular</button>
            </div>
        </div>
    `;

    if (familiares.length > 0) {
        html += '<h6 class="mt-4 mb-2">Familiares Vinculados</h6>';
        html += `<div class="sam-table table-responsive"><table class="table table-sm mb-0">
            <thead><tr><th>Nombre</th><th>Parentesco</th><th>Membresía</th><th class="text-center">Complem.</th><th class="text-end">Cashback</th><th>Acciones</th></tr></thead>
            <tbody>`;
        familiares.forEach(f => {
            html += `<tr>
                <td>${esc(f.nombre)}</td>
                <td><small>${esc(f.parentesco)}</small></td>
                <td><span class="badge" style="background:${colorMap[f.tipo_membresia]||'#6B7280'};color:#fff;">${esc(f.tipo_membresia)}</span></td>
                <td class="text-center">${f.es_complementaria == 1 ? '<span class="badge bg-success">Gratis</span>' : '<span class="text-muted">—</span>'}</td>
                <td class="text-end">${fmt(f.saldo_cashback)}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="eliminarFamiliar(${f.socio_membresia_id}, ${JSON.stringify(f.nombre)}, ${socio_membresia_id})">Eliminar</button>
                </td>
            </tr>`;
        });
        html += '</tbody></table></div>';
    } else {
        html += `<div class="alert alert-info mt-3 mb-0">
            Este socio no tiene familiares vinculados aún.
            <button class="btn btn-sm btn-link p-0" onclick="mostrarFormFamiliar()">Vincular un familiar</button>
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
            <td><strong>${esc(f.nombre)}</strong></td>
            <td class="text-muted">${esc(f.numero_socio)}</td>
            <td class="small">${esc(f.parentesco)}</td>
            <td><span class="badge" style="background:${colorMembresia};color:#fff;">${esc(f.tipo_membresia)}</span></td>
            <td><strong>${esc(f.titular_nombre)}</strong><br><span class="text-muted small">${esc(f.titular_numero_socio)}</span></td>
            <td class="text-center">${complementariaTag}</td>
            <td class="small">${esc(f.vencimiento)}</td>
            <td class="text-end fw-semibold">${fmt(f.saldo_cashback)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="eliminarFamiliar(${f.socio_membresia_id}, ${JSON.stringify(f.nombre)}, ${f.cuenta_titular_id})" title="Eliminar familiar">Eliminar</button>
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
</script>
</body>
</html>
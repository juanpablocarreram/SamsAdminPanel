# UI Rebuild Design Spec — SamsAdminPanel
**Date:** 2026-06-14  
**Scope:** Complete frontend rebuild of `index.php`. No changes to backend PHP API files.

---

## 1. Goals

Transform the current Bootstrap-tab admin panel into a modern, professional admin dashboard with Sam's Club branding. The backend logic, API endpoints, and database schema are untouched — this is a pure frontend overhaul.

---

## 2. Constraints

- **Stack**: PHP + Bootstrap 5 + vanilla CSS + vanilla JS only. No React, Vue, or other frameworks.
- **Single file**: All frontend lives in `index.php`. No partials, no build system.
- **APIs**: All existing endpoints (`inventario.php`, `socios.php`, `promociones.php`, `compras.php`, `ventas.php`) are unchanged.
- **`.env` file**: Must be created at project root with `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

---

## 3. Sam's Club Brand Tokens

```css
--sam-blue:        #003DA5;   /* Primary actions, sidebar active, table headers */
--sam-yellow:      #FFC220;   /* Accent: active nav indicator, COBRAR button, total in POS */
--sam-red:         #C8102E;   /* Danger, out-of-stock, cancel */
--sam-dark:        #0A1628;   /* Sidebar bg, POS terminal bg, table headers */
--sam-light:       #F5F7FA;   /* Main content bg */
--sam-card:        #FFFFFF;   /* Card/panel bg */
--sam-border:      #E5E9F0;   /* Subtle borders */
--sam-hover:       #F0F4FF;   /* Row hover */
--sam-text-main:   #1F2937;
--sam-text-muted:  #6B7280;
--font:            'Inter', sans-serif;
```

---

## 4. Layout Architecture

### 4.1 Sidebar (fixed, 260px, `#0A1628`)

- `position: fixed; left: 0; top: 0; height: 100vh; width: 260px; overflow-y: auto;`
- **Logo area**: Sam's logo image + "Sam's Admin" text label. Separated from nav by a thin `#FFC220` horizontal line.
- **Nav items**: Icon (unicode) + label. Stacked vertically with `48px` min-height per item.
  - Default: `color: rgba(255,255,255,0.55)`, no background
  - Hover: `color: #fff`, `background: rgba(255,255,255,0.06)`
  - Active: `color: #fff`, `background: rgba(255,255,255,0.08)`, `border-left: 3px solid #FFC220`
- **Sections**: Dashboard, Inventario, Socios, Promociones, Compras, Punto de Venta
- **Bottom**: "● Sistema activo" status badge pinned to the bottom of the sidebar.

### 4.2 Main Content Area

- `margin-left: 260px; min-height: 100vh; background: #F5F7FA; padding: 28px 32px;`
- **Section header row**: Page title (bold, `1.4rem`) on the left, date chip + refresh button on the right. Title has a `3px #FFC220` bottom underline accent.
- Sections toggled by JS (`display: none` / `display: block`) — no Bootstrap tabs.

---

## 5. Section Designs

### 5.1 Dashboard (New — Tab 0)

Default landing section on page load. Aggregates data from existing APIs.

**Stat Cards Row (4 cards):**
| Card | Value source | Left border color |
|------|-------------|-------------------|
| Socios Activos | `socios.php?action=list_titulares` count | `--sam-blue` |
| Ingresos Hoy | `ventas.php?action=historial` sum of today | `#10B981` green |
| Productos Sin Stock | `inventario.php?action=stats` | `--sam-red` |
| Promociones Activas | `promociones.php?action=list_promos` active count | `--sam-yellow` |

Each card: white bg, `12px` border-radius, `1px` border, `border-left: 5px solid <color>`, large number (`2.2rem`, `800` weight), label below, small unicode icon top-right.

**Two-column lower area:**

- **Left — Ventas Recientes**: White card, last 5 sales from `ventas.php`, compact rows (ID, total, canal). "Ver todas →" link navigates to Punto de Venta section.
- **Right — Membresías**: White card with CSS-only horizontal bar chart. Three rows (CLÁSICA / BENEFITS / PLUS), each with a colored fill bar (`width` set as inline `%` via JS) and a count. Colors: gray / `--sam-blue` / `#7C3AED`.

**Accesos Rápidos**: Full-width white card below. Three `btn-outline-primary` buttons: "Nueva Venta", "Nuevo Socio", "Registrar Compra". Each navigates to the relevant section and focuses the first form field.

### 5.2 Inventario

- Search bar full-width, white, `1.5px` border, blue focus ring.
- Table: white card, `12px` border-radius. Header: `#0A1628` background, white text, uppercase, `0.78rem`.
- `Stock Piso = 0`: cell text turns `--sam-red` + bold.
- Members Mark: gold pill badge (`background: #FFC220; color: #0A1628`).
- Promotion: green pill badge with discount value.
- Stat cards above table removed (moved to Dashboard).

### 5.3 Socios

- **Layout**: 35% form panel (left) + 65% data panel (right), `gap: 24px`.
- **Form card**: white bg, `4px solid --sam-blue` top border accent, `12px` border-radius.
- Form toggles (Nuevo Titular / Vincular Familiar) keep existing JS logic.
- **Sub-tab toggle** (Titulares / Familiares / Detalles): replaced with pill-style toggle buttons (`border-radius: 20px`) instead of Bootstrap `.nav-tabs`.
- Tables follow shared pattern (dark navy header).
- Membership badge pills: gray (CLÁSICA), blue (BENEFITS), purple (PLUS).

### 5.4 Promociones

- Same 40/60 split layout as today.
- Form card: `4px solid --sam-blue` top border accent.
- **Membership eligibility checkboxes**: Large pill-shaped toggle buttons (`min-width: 100px`, `border-radius: 20px`). Unchecked: gray outline. Checked: filled with membership color.
- Active/Inactive status: green dot + "Activa" text / gray dot + "Inactiva" — no filled badge background.

### 5.5 Compras

- Form card: `4px solid --sam-blue` top border accent.
- Items sub-table inside form: `background: #F9FAFB` to visually separate from form fields.
- Running total row: `border-left: 4px solid --sam-blue`, larger cost number (`1.1rem`, bold blue).
- Recepciones Recientes table: right column, dark navy header.

### 5.6 Punto de Venta (POS)

**Two-column layout, equal height:**

**Left zone** (light, `#F5F7FA` bg, white card):
- Canal selector + product search bar at top.
- Cart table: dark navy header, comfortable row padding.
- Quantity controls: `−` / `+` buttons flanking an inline number input.
- Discount column shows promo name as a small green pill when applied.

**Right terminal panel** (`#0A1628` bg, `border-radius: 12px`):
- Sam's logo (small, `32px`) at top of panel.
- Socio search: dark-styled input (`background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); color: #fff`).
- Linked socio block: slightly lighter dark card inside panel.
- Subtotal / Descuentos rows: `rgba(255,255,255,0.55)` text, right-aligned values in white.
- **Total**: `#FFC220` yellow, `2.4rem`, `800` weight — dominant visual element.
- Payment method: dark-styled `<select>` + `<input>` matching the panel bg.
- **Cancelar**: `btn-outline-danger`, full width.
- **Cobrar**: `background: #FFC220; color: #0A1628; font-weight: 800` — only yellow button in the app.

**Ventas Recientes table**: below both columns, in light content area, dark navy header.

---

## 6. Shared Component Standards

### Cards
```
background: #fff;
border-radius: 12px;
border: 1px solid #E5E9F0;
padding: 24px;
```

### Tables
```
Header: background #0A1628, color #fff, font-size 0.78rem, uppercase, padding 13px 16px
Rows: border-bottom 1px solid #E5E9F0, padding 14px 16px, font-size 0.9rem
Hover: background #F0F4FF
```

### Buttons
```
Primary:  background #003DA5, white text, border-radius 8px, font-weight 600
Success:  background #10B981
Danger:   background #C8102E
Outline:  border 1.5px solid --sam-blue, transparent bg
POS Cobrar: background #FFC220, color #0A1628, font-weight 800
```

### Form Inputs
```
border: 1.5px solid #E5E9F0;
border-radius: 8px;
padding: 10px 13px;
focus: border-color #003DA5, box-shadow 0 0 0 3px rgba(0,61,165,0.1)
```

### Section Title
```
font-weight: 800;
font-size: 1rem;
border-bottom: 3px solid #FFC220;
padding-bottom: 8px;
margin-bottom: 16px;
```

### Toast Notifications
Keep existing system (bottom-right, slide-in animation). Success: green gradient. Error: red gradient. Warn: amber gradient.

---

## 7. `.env` File

Create `/SamsAdminPanel/.env` with:
```
DB_HOST=127.0.0.1
DB_NAME=ICA_final
DB_USER=
DB_PASS=
```
User fills in `DB_USER` and `DB_PASS` for their MySQL instance.

---

## 8. Navigation JS Pattern

Replace Bootstrap tab system with manual JS:

```javascript
function showSection(name) {
    document.querySelectorAll('.sam-section').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.sam-nav-item').forEach(i => i.classList.remove('active'));
    document.getElementById('section-' + name).style.display = 'block';
    document.querySelector('[data-section="' + name + '"]').classList.add('active');
    document.getElementById('page-title').textContent = sectionTitles[name];
}
```

Each nav item: `onclick="showSection('inventario')"`. Dashboard loads on `DOMContentLoaded`.

---

## 9. What Does NOT Change

- All PHP backend files (`socios.php`, `inventario.php`, `compras.php`, `ventas.php`, `promociones.php`, `database.php`)
- All SQL schema and sample data
- All JavaScript business logic (cart calculation, discount logic, API calls, toast system)
- `css/bootstrap.min.css` and `js/bootstrap.bundle.min.js`
- `img/sams_logo.png`
- `composer.json` / `vendor/`

---

## 10. Implementation Order

1. New CSS variables + reset + sidebar layout
2. Sidebar HTML + nav JS (`showSection`)
3. Dashboard section (stat cards + widgets)
4. Inventario section (search + table, no stat cards)
5. Socios section (form card + pill toggles + tables)
6. Promociones section (form card + pill membership checkboxes)
7. Compras section (form card + items table)
8. Punto de Venta section (split layout + dark terminal)
9. `.env` file creation

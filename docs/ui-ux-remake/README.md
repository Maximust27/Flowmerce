# Flowmerce UI/UX Remake — Modern Workspace

Active development branch: `feature/ui-ux-remake`  
Integration destination: `main2` (never `main`)  
Visual reference: user-provided TikTok dashboard video, adapted to Flowmerce business functionality.

## Visual direction

- Light-first neutral workspace with coral accent, soft surfaces, consistent rounded cards, fine dividers, minimal shadows, spacious bento grids.
- Compact desktop icon rail and horizontal top navigation, mobile drawer/bottom shortcuts.
- Consistent light/dark color semantics and persisted theme preference.
- Controlled card entrance, modal, hover and chart animations; `prefers-reduced-motion` is respected.
- No invented product metrics, accuracy percentages or transactional business claims.

## Reusable design system

- `resources/css/flowmerce-ui.css`: semantic `--fm-*` color, type, spacing, radius, elevation and motion tokens and module-specific responsive layout rules.
- `config/flowmerce-navigation.php`: one navigation configuration for topbar and icon rail.
- `resources/js/flowmerce-theme.js`: appearance persistence and Alpine shell initialization.
- `resources/views/components/ui/`: app-shell, sidebar-menu, theme-toggle, page-header, section-header, button, card, stat-card, badge, feedback, field, select-field, textarea-field, modal, table and empty-state.
- Major Livewire screens are presentation-only redesigns using original wire methods, state and data: dashboard, inventory, warehouse, finance, employee management, POS, AI chat, and profile.
- Public guest/auth layouts, login/register/reset/verify pages, landing page and pagination share the new visual language.
- POS gets a dedicated responsive checkout-first layout without a Tailwind CDN dependency.

## Boundaries

Do not change the domain computations (tax, inventory logs, user roles, transaction creation, revenue/profit rules), Livewire method interfaces or database schema to match the video.
Existing backend code has not been changed in this presentation overhaul.
Legacy CSS and partials remain during gradual stabilization; remove only after verified unused.

## QA before PR or merge

Run locally:
```bash
git switch feature/ui-ux-remake
composer install
npm install
npm run build
php artisan optimize:clear
php artisan route:list
php artisan test
```

Manual acceptance:
1. Admin dashboard loads, chart filter toggles, totals match pre-redesign, all links navigate.
2. Inventory create/edit/delete, image upload and preview, validation errors, pagination, low-stock badges.
3. Warehouse in/out filter and PDF export, timeline, empty states.
4. Finance create INCOME/EXPENSE, totals, pagination, validation, modal closure.
5. Employee create/edit/role switch/delete/export (admin-only).
6. POS admin/cashier authorization, category and search, add/remove quantity, insufficient-stock errors, checkout, stock movement and financial record; checkout double-submit prevention; concurrency validation.
7. AI question prompts, send and received messages, empty responses/errors, scrolling, reduced motion.
8. Profile update, password reset, deletion confirmation, login/register/email recovery.
9. Test widths 320px, 375px, 768px, 1024px, 1440px; keyboard focus/ESC, theme persistence, screen reader labels, `prefers-reduced-motion`.
10. Check bundle and browser console, Livewire navigation and event lifecycle.

## Important pre-existing backend review items

- `InventoryManager::save` uses `Product::findOrFail` for edit rather than a user-scoped lookup (the `edit` and `delete` paths are scoped). Verify cross-user mutation cannot occur.
- `Terminal::addToCart`, `increaseQty`, and checkout lookup product IDs without owner scope. Test that another user's product ID cannot be ordered.
- `AiChat::getAiResponse` aggregates products and transactions without a user filter. Tenant isolation needs security review.
- Employee grouping uses non-unique business name as tenant key, not a dedicated company ID.
- The existing PHPUnit suite principally tests auth/profile; add CRUD/POS/tenant isolation regression tests separately.
- POS calculated subtotal, tax (11%) and grand total are deliberately unchanged.

## Status

Presentation code is implemented in the feature branch. Build, browser interaction, visual snapshot and runtime regression tests **must** pass locally before the work is called production-ready or opened for merge to `main2`. No claim of completed QA without observed results.

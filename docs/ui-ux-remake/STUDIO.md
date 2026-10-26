# Flowmerce Studio — Visual Direction V4

Reference: user's TikTok "Dashboard V14" video. Additional influences: restrained application chrome (Linear), disciplined financial tables (Stripe), data-centric composition (Attio), and simple editorial hierarchy (Mercury). These are visual principles, not copied artwork, data or source code.

## Product identity

**Calm Commerce.** A reliable, daily workspace for Indonesian micro, small and medium businesses.

- Light-first canvas: very pale warm gray with crisp white sections, hairline dividers, almost invisible shadows.
- The ink: deep graphite for primary actions. Coral is an accent for important statistics, selected elements, and plots—not an arbitrary color on every card.
- Typography: Plus Jakarta Sans for clarity and subtle personality. JetBrains Mono for tabular financial amounts where helpful.
- Reusable icon system: Material Symbols, with bespoke geometrical F logo defined once in `ui/brand`.
- Rhythm: consistent 4/8/12/16/24/32 units, smaller radii (9–22px), high contrast and readable focus rings.
- Dark mode uses semantic tokens, not CSS inversion.

## Main UI structure

- Desktop compact icon rail, a clear business breadcrumb, quick POS action, and four contextual workspace tabs.
- Ctrl/Cmd+K command palette searches actual page navigation (no fake catalog search).
- Mobile drawer and persistent bottom shortcuts; design remains keyboard-accessible.
- Dashboard uses **three coordinated bento panels**: revenue overview, four metrics, time-series chart. Followed by stock priorities and recent transactions.
- Revenue chart is a reusable SVG component with actual user-scoped amounts; zero-sales state is honest and accessible.
- Inventory and Finance include real Livewire search/filter interaction without altering transaction/stock computations.
- POS, auth, marketing page, employees, warehouse, AI and settings continue using the same semantic palette.

## Implementation map

- `resources/css/flowmerce-ui.css`: canonical palette and shared semantic tokens.
- `resources/css/flowmerce-studio.css`: isolated V4 shell, dashboard, motion, hierarchy and feature-specific refinements. Loaded **after** migrated styles through Vite. Legacy rules remain for migration stability.
- `resources/views/components/ui/`: brand, app-shell, sidebar-menu, command-palette, revenue-chart, and earlier reusable primitives.
- `resources/js/flowmerce-theme.js`: theme and command-palette state in Alpine.
- `app/Livewire/DashboardPage.php`: user-scoped raw chart totals, product count, daily income and stock alert records for display only.
- `app/Livewire/InventoryManager.php`, `TransactionManager.php`: user-scoped list filtering and pagination reset only.

## Checks before integration

- `npm run build`, `php artisan view:cache`, `php artisan test` using CI.
- Manually inspect at 320, 375, 768, 1024, 1440px: table overflow, topbar, icon-rail, dashboard layout, POS cart, modal.
- Verify keyboard: Ctrl/Cmd+K, Esc, TAB focus states and selected page indicators; screen reader labels on interactive content.
- Verify dark/light across Livewire navigation and first-load theme initialization.
- Check all CRUD and POS checkout business logic, tenant access and exports separately.
- Do not merge before manual sign-off.

## Security audit backlog, not hidden by the redesign

A view-level redesign does not resolve every existing authorization issue. Audit order/stock transactions and AI tenant scope, employee tenant association by business name, and checkout concurrency. These need explicit acceptance tests.

## Status

Prototype implemented on `feature/ui-ux-remake`; `main` is immutable source and `main2` is integration target. A green CI result covers compile and automated tests, **not** visual browser approval or full business/regression coverage.

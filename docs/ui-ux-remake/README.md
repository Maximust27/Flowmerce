# Flowmerce UI/UX Remake — Foundation

Target branch: `feature/ui-ux-remake`, eventual PR target `main2`. Do not modify `main`.

## Design direction
Modern UMKM workspace inspired by the supplied short video: neutral spacious surfaces, coral accent, responsive sidebar, calm bento cards, light/dark mode, and low-distraction motion. This is visual inspiration, not a functional copy.

## Architecture
- `resources/css/flowmerce-ui.css` contains the new `--fm-*` semantic design tokens, responsive rules and reduced-motion fallbacks.
- `resources/views/components/ui` provides reusable Blade primitives: button, card, badge, stat-card, page-header, theme-toggle, sidebar-menu, app-shell.
- `resources/js/flowmerce-theme.js` manages persisted appearance and an Alpine shell; browser storage failure is handled.
- `resources/views/dashboard.blade.php` is the first migration target. It reads the existing Livewire component without modifying its business calculations.
- Legacy classes and remaining modules continue to exist while migration proceeds.

## Audit findings (not automatically fixed)
1. `resources/views/components/layouts/pos.blade.php` loads Tailwind CDN with an independent theme, separate from Vite-based app styling.
2. `resources/views/components/layouts/app.blade.php` and `resources/views/layouts/app.blade.php` duplicate shell responsibility.
3. `DashboardPage` and `DashboardStats` repeat aggregation logic; dashboard Blade and lazy stats Blade repeat markup.
4. Legacy CSS contains hardcoded colors; stat-card and empty-state use undefined `--color-text-*` tokens; glass-card references a `glass` class not defined there.
5. `InventoryManager::save` resolves an edited record using `Product::findOrFail` without a user scope while `edit` and `delete` use a scope. Must be reviewed for cross-user modification risks.
6. `Terminal::addToCart` / `increaseQty` fetch product IDs without a user scope. Check authorization and stock revalidation at checkout; verify transactions remain atomic.
7. `EmployeeManager` uses `business_name` for tenant association; review tenant identity and uniqueness.
8. Topbar displays a static Owner label even though cashier role exists; the new shell derives the label from the authenticated role.
9. Existing test suite centers on authentication/profile; business CRUD/POS/tenant isolation need regression coverage.

## QA acceptance gates
- `php artisan test`, `php artisan route:list`, `npm run build`.
- Desktop/mobile/tablet, light/dark, keyboard navigation, reduced motion, Livewire navigation and updates.
- Dashboard metric parity: revenue = income, profit = income minus expense, low stock count unchanged.
- Before migrating other modules: authorization and tenant separation tests, inventory CRUD, POS checkout/stock mutation, finance exports.
- UI changes are not approval to alter business rules. PR only when tests and review pass.

## Notes
This foundation is progressively introduced, not a promise that all modules have been redesigned. The POS layout and other module screens intentionally remain unchanged for later controlled phases.

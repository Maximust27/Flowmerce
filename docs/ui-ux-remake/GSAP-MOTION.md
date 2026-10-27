# Flowmerce Studio — GSAP Motion System

Branch: `feature/ui-ux-remake`. Merge target: `main2`, never `main`.

## Integration

GSAP v3.15 is an npm dependency and is bundled by Vite. **No GSAP CDN, React migration, or changes to Laravel business logic.**

Files:

- `resources/js/flowmerce-motion.js` — single, reusable motion engine
- `resources/css/flowmerce-motion.css` — presentation overrides that prevent double CSS/GSAP animations
- `resources/js/app.js` — imports the motion engine once
- `resources/js/guest-menu.js` — emits events when a product is added, QRIS is revealed, or an order succeeds
- `resources/js/flowmerce-theme.js` — emits command-palette-open event after Alpine renders
- `resources/views/**` — stable `data-fm-motion-root` markers for each major experience

### Animated surfaces

| Surface | Animation |
| --- | --- |
| Landing page | Hero stagger; independent scroll-reveal groups via GSAP ScrollTrigger |
| Login / register / password reset | Editorial copy and reusable forms fade/translate into view |
| Dashboard | Page heading; bento card entrance; KPI stagger; graph SVG stroke drawing |
| Inventory, finance, employees, warehouse, AI, profile, tables | Heading, stat cards, cards and list arrival |
| POS / Kasir | Product grid and order-queue entrance, summary fade |
| Public table menu | Menu entrance, card stagger; animated cart addition and QRIS reveal |
| Modal | Scrim fade and dialog entrance without moving page ancestors |
| Ctrl/Cmd+K command palette | GSAP dialog entrance + Alpine native scrim exit |
| Hover | Small lift on appropriate cards and shortcuts, no infinite loops |
| Theme toggle | Small GSAP press bounce while native semantic-color transitions remain |

### Safe Livewire integration

Livewire 3 `livewire:navigating` calls `gsap.context().revert()`, which stops active page animations and restores inline styles. `livewire:navigated` mounts the next scene exactly once. New Livewire elements (dialog, chat message, order row, chart) animate through the `morph.added` hook. Global listeners are registered one time only and scene-local hover listeners are cleaned up per navigation.

The animation engine intentionally **never translates or scales .fm-app, .fm-page, .fm-dashboard, the checkout sidebar, or any full-screen backdrop**. This protects the full-viewport modal blur and prevents QRIS/cart overlap regression.

### Accessibility / performance

- `prefers-reduced-motion: reduce` disables GSAP scenes completely.
- Motion is limited to opacity, translate and small scale; no DOM height animation or perpetual movement.
- UI content is readable if JavaScript fails because nothing is hidden by default in CSS.
- Animations use short durations and contextual GSAP cleanup.
- Never animate actual revenue numbers or success states in a way that implies transactions are complete before server confirmation.

### QA plan

Automated: Vite build, Blade cache, full Laravel test suite, and Node JavaScript syntax checks.

Manual browser check (required before merge): Desktop 1440/1024, tablet 768, phone 375/320. Verify page transitions, back/forward navigation, switch dark/light repeatedly, reduced-motion preference, Ctrl+K and Escape, modal open/close, Livewire filters, revenue period switching, QRIS cart with 10+ items and public checkout, POS checkout. Inspect browser devtools for warnings or stale ScrollTriggers.

GSAP adds a bounded client-side bundle; build and test success is not a substitute for manual cross-browser FPS/profiling.

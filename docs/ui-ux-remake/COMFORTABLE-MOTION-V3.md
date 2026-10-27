# Flowmerce Studio — Comfortable Motion (V3)

Development branch: `feature/ui-ux-remake`. Integration target stays `main2`, not `main`.

## Problem diagnosed

The CSS layer still animated `.fm-stat-card` using `fm-enter` from zero opacity with child delays (65/130ms). GSAP also animated parent cards and statistics, creating overlapping enter animations. On public sections, GSAP's `ScrollTrigger` created `fromTo({opacity:0,y:19})` animations for entire feature cards — visually hiding already-visible text and making the "Stok yang lebih terkendali" card flash as it entered the viewport.

The former `softEntrance` was itself scheduled with `immediateRender:false`, so a card could jump down to its starting position only *after* a short delay, even though it was already on screen.

## Changes

- Removed legacy `fm-enter` declarations from dashboard, page, and stat-card CSS; removed per-stat animation delays.
- Disabled legacy chart bar animation in the unified motion CSS to avoid a second entrance.
- Rebuilt `softEntrance`: **translateY only**, normal **100% opacity at all times**, initial position applied on registration; 0.58–0.72s easing and small 6–10px travel.
- Dashboard receives its own restored bento rhythm (summary, insight cards, shortcuts) without nesting a second KPI animation.
- Inventory, Gudang, Keuangan, Pegawai, and Meja use one quieter design: page heading and summary cards move minimally; tables, inventory records, filters, and business numbers remain stable when Livewire morphs the content.
- Landing page features no longer fade or move entire cards on scroll. The feature **icons** gently rise and the illustrative bars grow only the last few percent. Headings and CTA content move a few pixels and remain fully legible.
- POS and public menu cards now use the same no-opacity motion. Payment and checkout panels never move.
- Chart redraw after a Livewire filter change no longer jumps backwards along the SVG path.
- Hover lift reduced to 2px and eased to ~0.4s.
- Unchanged: hero square/circle float, two feature orbits, animated logo core, login and register motion, modal/confirmation motion, reduced-motion support.

## Safety principles

No business logic changes, no checkout or stock recalculation changes, and no route changes.
Full viewport modal/backdrop continues to work because page containers are untransformed.

Automated checks: `npm run test:motion`, `npm run build`, Blade cache, route list, Laravel tests, plus motion regression tests in `tests/Feature/MotionExperienceTest.php`.

**Manual review required**: scroll through the landing feature grid slowly and quickly, back-scroll repeatedly; navigate between all six modules and use filters; check the dashboard graph and mobile performance. Compare both light and dark themes, reduced motion, modals and the QR checkout.

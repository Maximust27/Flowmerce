# Flowmerce Studio — Smooth Navigation and Orbital Landing Motion

Branch: `feature/ui-ux-remake`. Target after approval: `main2`. Never merge to `main`.

## What changed

### No-flash navigation on six core modules

Dashboard, Inventaris, Gudang, Keuangan, Pegawai, and Meja now use the same
`softEntrance()` choreography. Each animated element begins at 97.5% opacity and
a maximum of 7px displacement; nothing jumps from invisible to visible during
Livewire hydration. Parent page containers remain static to protect
`position: fixed` modals and full-screen backdrop blur.

The old CSS `fm-enter` and `fm-page-opacity` animations are disabled immediately
by `flowmerce-motion.css`, **even before GSAP loads**, avoiding the previously
overlapping CSS animation/GSAP tween. The dashboard chart now draws only the
final portion of its stroke instead of vanishing then reappearing.

Table filtering, stock adjustments, modal creation, and chart Livewire morphing
still update data through their original controllers. Newly inserted rows use
soft motion without resetting the entire page.

### Landing page animated hero

The decorative square and circular accent in the hero now float at different
speeds and travel directions. A subtle circular halo rotates very slowly behind
the product mockup. Motion does not affect the real dashboard preview, links,
or page layout.

### Six-feature orbit — Flowmerce as the sun

At "Lebih sedikit distraksi. Lebih banyak kendali." two concentric tracks move
around a stationary Flowmerce logo:

- Outer orbit (32-second clockwise rotation): Inventaris, Kasir POS, Keuangan, Pegawai
- Inner orbit (43-second counterclockwise rotation): Gudang, Asisten AI
- Orbital badges counter-rotate, so their symbols stay upright while traveling
- Core halo breathes very gently; track paths and satellites remain static if JS
  is unavailable or the user prefers reduced motion

GSAP ScrollTrigger watches each ambient section. **Repeating animations pause
offscreen, when the tab is hidden, and for reduced-motion users.** All active
GSAP contexts and ScrollTriggers are reverted during Livewire navigation.
Intervals, timers, or additional per-page animation frameworks are unnecessary.

Feature cards retain scroll reveals and subtle hover responses. The featured
analytics illustration draws its bars when entering the viewport.

## Verification

Workflow: `.github/workflows/ui-remake-validation.yml` runs the JS syntax
checks, Vite build, Blade cache compilation, routes and Laravel feature tests.
`tests/Feature/MotionExperienceTest.php` checks the orbit markup, shared
motion roots, lifecycle hooks, accessibility settings, and six core routes.

Manual visual checks remain necessary: 320, 375, 768, 1024, 1440px in dark/light
mode; rapid navigation between the six modules; Ctrl+K modal blur; content
filtering and pagination while GSAP is active; landing orbit at top/middle/offscreen;
back-forward, tab backgrounding, reduced-motion, and mobile touch.

No PHP business or payment logic is changed by this update.

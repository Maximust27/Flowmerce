# Flowmerce Studio — Brand assets & QR checkout

Reusable brand: `resources/views/components/ui/brand.blade.php`.

- `public/images/branding/flowmerce-icon.webp`: optimized, transparent, 96×96 raster of the newly generated Flowmerce icon.
- `public/images/branding/flowmerce-mark.svg`: scalable vector fallback, matching the new mark.
- `public/images/branding/flowmerce-favicon.png`: browser favicon, 48×48 PNG, derived from the generated icon.
- `resources/views/components/ui/html-document.blade.php`: favicon used across landing, auth, dashboard, POS and public QR menus.

Render `<x-ui.brand />` or `<x-ui.brand :compact="true" />`. Do not copy hand-built F shapes. Wordmark remains live text for readability in light and dark modes.

QR checkout: use a single natural vertical scroll for all cart items, totals, payment methods, QRIS preview and order action. These elements must never overlap or compress when QRIS is displayed.

Browser QA: test 0 / 3 / 10 cart items, toggle CASHIER ↔ QRIS, scroll and submit on 320, 375, 768 and 1440 px. Check brand on the sidebar, login, POS, landing page and QR menu; hard-refresh to verify cached favicon changes.

# Flowmerce Studio — Revisions From Design Review

Development branch: `feature/ui-ux-remake`. The source branch `main` and integration branch `main2` remain untouched.

## Review checklist

| Request | Implementation |
| --- | --- |
| 1. Smooth light/dark animation | Intentional CSS token transitions and icon crossfade/rotation on toggle, respecting reduced motion |
| 2. Theme resets on page navigation | Persist preference in localStorage, restore on Livewire navigation events and on root attribute morph; plain public pages also support the theme toggle |
| 3. Tiny text | Readability improvements with a 12 / 16 / 20 / 24 / 32 px hierarchy and 14 px in dense contexts |
| 4. Pegawai nav missing | Full nav from central configuration, including Pegawai and Meja |
| 5. POS icon in sidebar | POS removed from desktop icon rail; main POS action remains in topbar (mobile retains dedicated quick action) |
| 6. Blurred backdrop clipped | Removed transform-containing page animation; modal/command scrims cover 100vw by 100dvh |
| 7. Search text crowded | Increased command palette input inset and spacing |
| 8. Profit ribbon padding | More specific rule restores 22px vertical / 24px horizontal padding |
| 9. Meja & QR menu disappeared | Restored and adapted table management, public menu, guest order submission, QRIS upload and POS order queue using the `qris` branch as functional reference |
| 10. AI answer too artificial | Formal plain-text business-advisor prompt, formatting normalizer, measured accounting language and business-tenant-scoped DB queries |

## Getting started on local Laravel / Laragon

```bash
git fetch origin
git switch feature/ui-ux-remake
git pull --ff-only origin feature/ui-ux-remake
composer install
npm install
php artisan migrate
php artisan storage:link
php artisan optimize:clear
npm run dev
```

A migrated database is **required** for Meja, QRIS, inventory listing and POS because this change adds 6 migrations. Back up a real database before migrating.

Table links are generated from Laravel's URL configuration. For a QR scanned by another device, set `APP_URL` to an address reachable by that device; `localhost` cannot be accessed from someone else's phone. A local HTTP network IP is suitable only in a trusted LAN. Public production QR routes require an HTTPS domain.

## Ordering/payment behavior

1. Admin opens **Meja & QR**, creates and activates a table.
2. Admin shows/scans the table QR, opening `/menu/{tableCode}` as a public website.
3. Customer browses stock-filtered menu, adds items and submits an order; server recomputes item prices and tax.
4. Guest order is created as **UNPAID** and **PENDING**, even when QRIS is selected. It does not immediately deduct stock or post income.
5. Staff opens POS > **Pesanan meja**, selects the order by code or pending queue, checks items and payment, then processes checkout. For QRIS manual, staff must explicitly confirm receiving payment.
6. Checkout holds a database transaction, locks stock rows, creates POS items, stock logs and recorded income, then marks the guest order completed/paid once.
7. No automated payment-gateway callback is implemented. QRIS is **manual**; do not label it auto-confirmed.

The QR image in the admin dialog currently uses `api.qrserver.com` as in the original `qris` branch. That service receives the generated table URL. For production, prefer a first-party QR renderer or a self-hosted QR library if QR URLs must stay within your infrastructure.

## Validation performed

GitHub Actions runs:
- `npm run build`
- `php artisan view:cache`
- `php artisan route:list`
- `php artisan test`

New tests cover: table creation and state; guest-public menu; user-scoped products; guest order server-side pricing; QRIS default unpaid; POS payment confirmation; stock decrement and recorded income; AI context tenant isolation, output formatting, and stubbed model response.

## Pending manual checks and known risks

Automated tests are not visual browser QA. Manually verify at 320 / 375 / 768 / 1024 / 1440 px, theme persistence across Livewire `wire:navigate`, dark/light theme motion, modal blur covering full viewport, search inset, profit ribbon spacing, QR scanner links from a phone, and POS checkout on a real connection.

**Existing tenant-link limitation:** Cashier accounts are still resolved to an owner by shared `business_name`. A dedicated owner/business ID association is recommended before operating multiple businesses with identical names. Do not claim cross-tenant isolation is complete until cashier ownership is migrated and fully tested.

The Gemini integration is still dependent on provider availability and prompt compliance. It now requests calm, plain Indonesian business advice; it cannot guarantee financial correctness. Recorded revenue minus expenses is not automatically verified net profit, and should not be advertised as audited profitability.

Do not PR/merge into `main2` before manual UX review and core POS/permissions regression checks.

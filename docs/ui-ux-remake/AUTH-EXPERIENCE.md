# Flowmerce Studio — Unified Public & Authentication Experience

Branch: `feature/ui-ux-remake`  
Integration destination: `main2`, never `main`.

## Scope

A single visual language now includes the **landing page, login, registration, forgot/reset password, email verification, password confirmation, POS, authenticated workspace, and profile**. The registration and authentication business logic remains unchanged.

### Shared architecture

| Concern | Reusable source |
| --- | --- |
| Document head / fonts / theme bootstrap / Vite assets | `resources/views/components/ui/html-document.blade.php` |
| Brand / logo | `resources/views/components/ui/brand.blade.php` |
| Auth two-panel chrome | `resources/views/components/ui/auth-shell.blade.php` |
| Auth headings | `resources/views/components/ui/auth-heading.blade.php` |
| Decorative workspace preview | `resources/views/components/ui/workspace-preview.blade.php` |
| Forms and password visibility | `resources/views/components/ui/field.blade.php` |
| Semantic tokens | `resources/css/flowmerce-ui.css` |
| Shared Studio visual system | `resources/css/flowmerce-studio.css` |
| Public / auth / profile refinements | `resources/css/flowmerce-experience.css` |
| Theme persistence / Alpine | `resources/js/flowmerce-theme.js` |

`layouts/guest.blade.php` and `components/layouts/guest.blade.php` delegate to the same shared document/shell. App and POS documents do the same. No duplicated independent HTML document setup.

The dashboard preview on public/auth pages is explicitly an **illustration**, not customer analytics or claimed product results. All actual dashboard metrics continue to use Livewire-backed user-scoped data.

## Functional boundaries

- Login, register, password reset, email verification, profile CRUD and logout keep the original Laravel/Livewire Volt methods.
- New signups still create an admin user; cashier login redirects to the POS.
- Guest registration preserves exact validated fields: `name`, `email`, `business_name`, `business_category`, `password`, `password_confirmation`.
- Inputs keep their original `wire:model` bindings and validation errors.
- Password visibility uses Alpine on the client; no plaintext is logged, sent, or stored beyond the normal form submission.
- Cashier navigation only exposes POS, profile, and logout where relevant; backend middleware remains the enforcement point.

## Design

- Light-first canvas, dark graphite illustration panel, fine dividers, spacious form layout and restrained coral accents.
- Reusable product preview, dynamic light/dark appearance, smooth but optional microinteractions.
- Registration is **one form** visually divided into owner, business, and security sections; no multistep backend rewrites or false success screens.
- Long forms scroll; mobile displays a simplified single-column layout.
- Native labels, accessible validation errors, keyboard focus, show/hide password controls, loading states, reduced-motion support.

## QA

Automated: Vite build, Blade compilation, Laravel routes, auth tests, profile tests and public/auth experience integration smoke tests in GitHub Actions.

Manual browser approval still required:
- 320 / 375 / 768 / 1024 / 1440 px.
- Both themes across guest → signed-in navigation; refresh persistence.
- Login admin and cashier, logout, invalid credentials, registration errors.
- Password reset email/token, verification link, expired/invalid reset states.
- Keyboard navigation, focus, reveal password buttons, Enter submit, disabled/loading.
- Profile update/password change/deletion dialog.
- Contrast, mobile form scrolling, page layout overflow and image-free fallback.
- Checkout and tenant isolation remain separate high-priority regression/security work; **not** implied resolved by visual tests.

Do not create PR or merge into `main2` until visual and business-flow review are approved.

# Flowmerce — Inventory, Sales & POS Management

A full-stack Laravel application exploring the day-to-day workflows of a small retail business: products, inventory, financial transactions, and point-of-sale checkout.

**Portfolio status:** Source code available; no public live demo is currently provided. The project can be reviewed through its code and run locally.

## What this project demonstrates

| Area | Implementation evidence |
| --- | --- |
| Inventory | Product create/edit/delete flows in [InventoryManager](app/Livewire/InventoryManager.php) |
| Stock operations | [Warehouse management component](app/Livewire/GudangManager.php) and inventory log model |
| Sales/POS | Cart and checkout flow in [POS Terminal](app/Livewire/Pos/Terminal.php), including a database transaction |
| Financial records | [Transaction manager](app/Livewire/TransactionManager.php) |
| Access control | Admin-only and admin/cashier routes in [routes/web.php](routes/web.php) |
| Exports | Warehouse and employee export controllers in [app/Http/Controllers](app/Http/Controllers) |
| Additional module | AI chat interface in [AiChat](app/Livewire/AiChat.php) |

These are code-level capabilities, not claims of production readiness or measured business impact.

## Tech stack

- **Backend:** PHP 8.4, Laravel 13, Livewire 3
- **Frontend:** Blade, Tailwind CSS, Vite
- **Data:** Eloquent ORM and Laravel database migrations
- **Testing:** PHPUnit / Laravel test runner
- **Other packages:** DomPDF, Laravel Reverb

## Architecture at a glance

```text
Browser
  -> Blade + Livewire components
  -> Laravel routes, middleware and application logic
  -> Eloquent models
  -> Database (products, inventory logs, transactions, POS orders)
```

See the [database migrations](database/migrations) and [Livewire components](app/Livewire) for implementation details.

## Run locally

Requirements: compatible PHP and Composer, Node.js/npm, and a database supported by your Laravel configuration.

```bash
git clone https://github.com/Maximust27/Flowmerce.git
cd Flowmerce
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Set database credentials in `.env`, then run:

```bash
php artisan migrate
php artisan serve
```

In a second terminal:

```bash
npm run dev
```

To run the existing test suite and build assets:

```bash
php artisan test
npm run build
```

**Note:** Installation and tests have not been executed as part of this documentation update. Depending on your environment, additional configuration or seed data may be needed. Never commit secrets or a populated `.env` file.

## How to review this project

1. Start with [routes/web.php](routes/web.php) to see role-based page access.
2. Review [POS checkout](app/Livewire/Pos/Terminal.php) and the [order models](app/Models).
3. Browse the [migrations](database/migrations) to understand the data model.
4. Read the [project case study](docs/PORTFOLIO_CASE_STUDY.md) for design decisions, limitations and next steps.

## Portfolio assets

A public live demo and screenshots are **not available in this repository** at the time of this README update. A short walkthrough video with fictional sample data would make the project easier for recruiters to assess.

## Next improvements

- Add feature tests around inventory adjustments, checkout totals, and authorization.
- Document installation requirements and provide reproducible demo seed data.
- Introduce automated test/build checks in CI.
- Add screenshots and a short demo walkthrough.

---

**Project maintained by:** [@Maximust27](https://github.com/Maximust27) · [Portfolio](https://maximust27.github.io/)

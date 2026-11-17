# Flowmerce — Portfolio Case Study

## Context and problem

A small retail business needs a consistent way to manage products, track stock changes, record sales, and review transactions. Flowmerce explores those workflows within a single Laravel application.

## Scope shown in the repository

- **Inventory:** A Livewire manager exposes product create, update, and delete operations.
- **Point of sale:** The POS component manages a cart, quantities, totals, and checkout. Checkout uses a database transaction.
- **Finance:** A transaction manager handles financial records.
- **Roles:** Routes are separated into admin-only and admin/cashier areas.
- **Data model:** Migrations cover products, inventory logs, transactions, POS orders, and POS order items.

## Technical approach

```text
UI: Blade + Livewire
        |
Laravel routing and role middleware
        |
Livewire actions + Eloquent
        |
Database tables / transactions
```

**Key files**

- [Application routes](../routes/web.php)
- [POS Terminal](../app/Livewire/Pos/Terminal.php)
- [Inventory manager](../app/Livewire/InventoryManager.php)
- [Transaction manager](../app/Livewire/TransactionManager.php)
- [Database migrations](../database/migrations)

## Engineering discussion points

**Checkout consistency:** The POS checkout flow uses `DB::transaction`; this is relevant for keeping order creation and related stock or sales mutations consistent. It still needs targeted automated tests.

**Authorization:** Route-level role middleware separates the cashier POS from administrative functions. Robust tests should also verify all sensitive component actions.

**Data integrity:** Products, inventory logs, and order items are distinct models/tables. This helps illustrate how operational state and transaction history can be modeled separately.

## Verification status

This document is based on repository inspection. It is **not** a statement that the app is deployed, audited, or production-ready. Existing Laravel auth/profile tests do not by themselves establish coverage for POS or stock workflows. No tests were run during this documentation update.

## Recommended follow-up work

1. Add automated tests for stock decrement, invalid quantities, checkout totals, and permission boundaries.
2. Provide sample seed data with no real customer information.
3. Record a short end-to-end demo and capture representative screenshots.
4. Describe any team collaboration and the author's exact contribution before using this as employment evidence.
5. Document setup quirks discovered during a clean local installation.

## Suggested recruiter walkthrough

- **1 minute:** Show login and relevant user roles.
- **2 minutes:** Create a sample product, adjust inventory, and complete a test sale.
- **1 minute:** Show where the core flow lives in code and explain the database transaction.
- **1 minute:** Discuss test gaps and the next engineering improvement.

Avoid claiming live users, revenue impact, or performance metrics unless you can verify them.

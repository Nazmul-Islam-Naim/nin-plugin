---
name: laravel-structure
description: Use when structuring a Laravel backend — where files go, and how to apply backend-architecture's layers and SOLID principles using the L5 modular pattern (nwidart/laravel-modules). Read backend-architecture first for the why; this skill is the Laravel-specific how.
---

# Laravel Structure

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

Builds on `backend-architecture`: that skill's four layers (route/controller → service/use-case → repository → domain entity) and five SOLID principles apply here unchanged — this skill only maps them onto actual Laravel classes and folders.

## Steps

1. **Look first.** Check for an existing `Modules/` folder or `nwidart/laravel-modules` in `composer.json`. If the project already has a structure — modular or flat `app/` — follow it. Do not migrate an existing flat project to modules unless asked.
2. **New project default: modular.** Install `nwidart/laravel-modules` and organize by business capability, one module per capability, not per file type:

   ```
   Modules/
     Order/
       Http/Controllers/OrderController.php
       Http/Requests/StoreOrderRequest.php
       Services/CreateOrder.php          # or Actions/
       Repositories/OrderRepositoryInterface.php
       Repositories/EloquentOrderRepository.php
       Models/Order.php
       Providers/OrderServiceProvider.php  # binds the repository interface
       routes/api.php
       database/migrations/
     User/
       ...
   ```

3. **Map the layers inside each module:**
   - **Controller** — thin. Injects a service/action via constructor, calls one method, returns a response. No query building, no business rules.
   - **Form Request** — boundary validation (`StoreOrderRequest::rules()`). The controller receives already-valid data.
   - **Service or Action class** — the business logic, one class per use-case for Actions (`CreateOrder`, `CancelOrder`) or a cohesive service per aggregate. Takes the repository interface via constructor injection, never a concrete Eloquent query.
   - **Repository interface + Eloquent implementation** — `OrderRepositoryInterface` declares `find`, `save`, etc.; `EloquentOrderRepository` implements it. Bind the interface to the implementation in the module's own `OrderServiceProvider::register()`, not in the global `AppServiceProvider`.
   - **Eloquent Model** — sits where "domain entity" would be, but stays honest about the difference: a Model is Active Record (it knows how to persist itself), not a pure domain entity. Keep business rules in the Service/Action, not in Model methods or events, so the persistence layer doesn't quietly become the business layer.

4. **SOLID, Laravel-shaped:**
   - **SRP** — split a fat `OrderService` into one Action class per use-case once it grows past a handful of unrelated methods.
   - **OCP** — new payment provider means a new class implementing `PaymentGatewayInterface`, registered in a provider, not a new `case` in an existing method.
   - **LSP** — every `RepositoryInterface` implementation must honor the same contract; don't add a `FakeOrderRepository` that throws on `delete()` if the interface promises it.
   - **ISP** — if most callers only read, give them `OrderReaderInterface` instead of the full read/write `OrderRepositoryInterface`.
   - **DIP** — type-hint interfaces in constructors; let the container resolve them via the binding in the module's provider. Never `new EloquentOrderRepository()` inside a Service/Action.
5. **Module boundary.** One module per business capability (Order, Catalog, Payment), not one per controller or per table. A module justifies itself when it has its own lifecycle and could plausibly be extracted later — not just because a folder is getting crowded.
6. **After scaffolding,** register the module (`php artisan module:list` / the package's autoload), confirm routes load (`php artisan route:list`), and that the container resolves each bound interface without error.

## Red Flags

| Thought | Reality |
|---|---|
| "Put business logic in the Model (fat model)" | Models are Active Record, not the business layer. Logic goes in Services/Actions. |
| "Bind repositories in the global AppServiceProvider" | Keep the binding inside the module's own provider — modules should stay self-contained. |
| "One module per controller" | Modules are business capabilities. A dozen tiny modules is as bad as one giant `app/`. |
| "Skip the modules package, it's just Laravel with extra steps" | That's the point — it's the boundary that keeps modules independently removable/testable. |
| "Query the DB straight from the controller" | Same violation as in `backend-architecture` — go through the repository. |

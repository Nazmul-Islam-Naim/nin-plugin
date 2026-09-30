---
name: fastapi-structure
description: Use when structuring a FastAPI backend — where code goes, and how to apply backend-architecture's layers and SOLID principles using routers, Pydantic schemas, Depends() and SQLAlchemy. Read backend-architecture first for the why; this skill is the FastAPI-specific how.
---

# FastAPI Structure

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

Builds on `backend-architecture`: that skill's four layers (route/controller → service/use-case → repository → domain entity) and five SOLID principles apply here unchanged — this skill only maps them onto actual FastAPI constructs.

## Steps

1. **Look first.** Check the existing project layout — a flat `app/routers`, `app/models` split, or already feature-based packages. Follow what's there. Do not restructure an existing project unless asked.
2. **New project default: feature packages.** One package per business capability, not per layer:

   ```
   app/
     orders/
       router.py       # APIRouter, path operations
       schemas.py       # Pydantic request/response models
       service.py       # business logic
       repository.py    # Protocol interface + SQLAlchemy implementation
       models.py         # SQLAlchemy ORM model
     users/
       ...
     core/
       database.py      # engine, session factory
       dependencies.py  # shared Depends() providers
     main.py             # creates the app, includes each feature's router
   ```

3. **Map the layers inside each package:**
   - **Router / path operation** — thin. Declares the route, takes a Pydantic schema as the body, injects the service via `Depends()`, calls one method, returns a response model. No business logic, no direct DB session use beyond what `Depends()` hands it.
   - **Pydantic schema** — boundary validation and shape: one schema for input (`OrderCreate`), one for output (`OrderRead`). The router and service never pass raw dicts across the boundary.
   - **Service** — the business logic, a plain class or function. Takes the repository (its `Protocol`, not the concrete class) via constructor or function parameter. No `Request`/`Response` objects, no FastAPI imports.
   - **Repository** — a `Protocol` (or `abc.ABC`) declaring `get`, `add`, etc., plus one `SqlAlchemyOrderRepository` implementing it against a `Session`. The service only knows the `Protocol`.
   - **Domain entity / model** — the SQLAlchemy model is the persistence mapping, not the same object as the Pydantic schema. Keep business rules in the service, not in model methods — SQLAlchemy is closer to Data Mapper than Active Record, but it still shouldn't carry business logic.

   Before creating or changing a SQLAlchemy model or Alembic revision, read `data-dictionary` for this entity's fields, types and relationships — the dictionary decides the schema, the revision only implements it.

4. **Wire dependency injection with `Depends()`.** Each layer's dependency is a provider function, not a hardcoded import:

   ```python
   def get_db() -> Session: ...
   def get_order_repository(db: Session = Depends(get_db)) -> OrderRepository:
       return SqlAlchemyOrderRepository(db)
   def get_order_service(repo: OrderRepository = Depends(get_order_repository)) -> OrderService:
       return OrderService(repo)
   ```

   The path operation only ever type-hints `OrderService = Depends(get_order_service)` — swapping `SqlAlchemyOrderRepository` for a fake in tests means overriding one provider (`app.dependency_overrides`), not editing the route.

5. **SOLID, FastAPI-shaped:**
   - **SRP** — one router file, one service, one repository per feature; split a service once it covers unrelated use-cases.
   - **OCP** — a new payment method is a new class implementing the same `Protocol`, not a new `if` in an existing service method.
   - **LSP** — every repository implementation honors the same `Protocol` contract; a read-only implementation should be a narrower `Protocol`, not one that raises on `add()`.
   - **ISP** — split `OrderRepository` into `OrderReader`/`OrderWriter` `Protocol`s if most callers only read.
   - **DIP** — services and routers depend on `Protocol` types, resolved through `Depends()`; never instantiate a concrete repository inside a service.
6. **Package boundary.** One package per business capability (orders, users, payments), not one per table or per router. A capability earns its own package when it has independent business rules — not just because `app/routers` is getting long.
7. **After scaffolding,** confirm the app starts (`uvicorn app.main:app`), routes list correctly (`/docs`), and each `Depends()` chain resolves without a circular import.

## Red Flags

| Thought | Reality |
|---|---|
| "Query the DB straight in the path operation" | Same violation as in `backend-architecture` — go through the repository via `Depends()`. |
| "Return the SQLAlchemy model directly from the route" | Return a Pydantic schema. Leaking the ORM model couples the API contract to the table schema. |
| "Import the concrete repository directly in the service" | Type-hint the `Protocol`; let `Depends()` resolve the concrete class. |
| "One giant `schemas.py` for the whole app" | Schemas live with their feature package, same as everything else. |
| "Skip `Depends()`, just call the function" | `Depends()` is what makes `dependency_overrides` possible in tests — skipping it kills testability. |
| "Edit an Alembic revision that's already applied in a shared environment" | Never edit it. Add a new revision for the change, or teammates' schemas drift out of sync. |

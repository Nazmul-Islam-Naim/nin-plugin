---
name: backend-architecture
description: Use when building or reviewing backend code (API/service layer) in any language or framework, and it needs a SOLID, framework-agnostic layered structure — not a specific database, auth or infra setup.
---

# Backend Architecture

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, code and framework names in English.

## Steps

1. **Layer it.** Four layers, each only talking to the one below:
   - **Route/controller** — parses the request, calls one service method, shapes the response. No business logic, no direct database calls.
   - **Service/use-case** — the actual business logic. Framework-free: no request/response objects, no ORM models leaking in.
   - **Repository** — an interface for persistence (`findById`, `save`, ...) plus one concrete implementation. The service only knows the interface.
   - **Domain entity** — plain data + invariants, no framework or database decorators.
   This holds in Express, NestJS, FastAPI, Django, Spring, Laravel or anything else — only the controller layer's syntax changes per framework.

2. **Single Responsibility.** One class/module, one reason to change. A service that validates, emails, logs, and persists is four services wearing a trenchcoat — split it. Red flag: a class whose methods don't share the same 2-3 instance fields.

3. **Open/Closed.** New behavior means a new implementation, not a new `if` in an existing one. Example: a `PaymentProcessor` interface with `StripeProcessor`/`PaypalProcessor` implementations, chosen by config — not one method with a growing `switch` on provider name.

4. **Liskov Substitution.** Any class implementing an interface must be a true drop-in: same preconditions, no throwing "not supported" for a method the interface promises. If a `ReadOnlyRepository` can't support `save()`, it should not implement the same interface as a full repository — split the interface instead (this is also rule 5).

5. **Interface Segregation.** Small, role-specific interfaces over one god-interface. A consumer that only reads should depend on a `Reader`, not a full `Repository` with `save`/`delete` it never calls.

6. **Dependency Inversion.** Services depend on interfaces, not concrete classes, and receive them via constructor injection (manual, or a DI container if the framework has one). Never `new SomeRepository()` inside a service — that hardwires the implementation and kills testability.

7. **Validate at the boundary.** DTOs/schemas are parsed and validated in the controller layer. The service receives already-valid, framework-free data. Domain errors (not-found, invalid-state) are thrown as typed errors and translated to HTTP status codes back at the controller, not decided inside the service.

8. **Prove it with a test.** Because services depend on interfaces, they can be unit-tested with an in-memory fake repository — no database, no framework bootstrap. If a change can't be tested without spinning up the whole app, a dependency was likely reached into directly instead of injected.

## Red Flags

| Thought | Reality |
|---|---|
| "Just query the DB from the controller, it's faster" | Skips the service/repository boundary. Fine for a true one-off script, never for app code. |
| "One big service class, split it later" | Later never comes. Split by responsibility now, before callers pile up. |
| "Add a new `if (type === 'x')` branch" | Open/Closed violation. New type = new implementation of the same interface. |
| "`new Repository()` right here is simpler" | Simpler until the test suite needs a fake one. Inject it from the start. |
| "This framework has its own DI/module system, skip the layers" | Use the framework's DI to *wire* the layers, don't let it dissolve them. |

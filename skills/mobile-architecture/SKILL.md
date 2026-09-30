---
name: mobile-architecture
description: Use when building or reviewing a mobile app's structure in any framework — a SOLID, framework-agnostic layered architecture (screen → state → repository → API client), not styling or a specific framework's file layout.
---

# Mobile Architecture

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, code and framework names in English.

## Steps

1. **Layer it.** Four layers, each only talking to the one below:
   - **Screen/UI (View)** — renders from state, forwards user actions upward. No API calls, no business logic.
   - **ViewModel/state container** — holds screen state (loading/data/error), calls one repository method per action, exposes plain data to the view.
   - **Repository** — an interface for data access (`getOrders`, `saveProfile`, ...) plus one concrete implementation (API client + optional local cache). The state layer only knows the interface.
   - **API client/service** — the actual network calls (REST/GraphQL) plus mapping raw responses to typed domain data. No UI, no state.
   This holds in React Native, Flutter, native iOS/Android or anything else — only the view/state layer's syntax changes per framework.

2. **Navigation is its own concern.** Routes/stacks live outside the state layer; a screen navigates by calling a navigation function passed to it or via a shared navigator, never by importing another screen directly.

3. **SOLID, mobile-shaped** — the same five principles as `backend-architecture`:
   - **SRP** — a state container that fetches, formats, logs and navigates is doing four jobs; split it.
   - **OCP** — a new data source means a new repository implementation, not a growing `if` in an existing one.
   - **LSP** — every repository implementation must honor its interface fully; a read-only source shouldn't implement a `save()`-bearing interface it can't fulfill.
   - **ISP** — a screen that only reads should depend on a `Reader`, not a full read/write repository.
   - **DIP** — state containers depend on the repository interface, injected (constructor/hook param/context), never `new ApiRepository()` inline.

4. **Offline & caching.** If the app needs to work offline or feel fast, the repository decides cache-first vs network-first — the state layer never knows which. Keep cache invalidation in one place (the repository), not scattered across screens.

5. **Validate at the boundary.** The API client maps raw JSON to typed domain models; the state layer receives already-valid, typed data. Network/domain errors are typed and translated to a UI-friendly message at the state layer, not inside the API client.

6. **Prove it with a test.** Because state containers depend on a repository interface, they can be tested with an in-memory fake — no network, no simulator. If a change can't be tested without running the app, a dependency was likely reached into directly instead of injected.

## Red Flags

| Thought | Reality |
|---|---|
| "Call the API straight from the screen" | Skips the state/repository boundary — untestable and hard to cache. |
| "Import another screen's component to reuse its logic" | Navigate to it, or lift the shared logic into a repository/hook — don't couple screens. |
| "One giant state object for the whole app" | Split by feature/screen; a global store only for truly cross-cutting state (auth, theme). |
| "Cache logic in the screen's effect hook" | Put it in the repository — every screen using that data should get the same behavior. |

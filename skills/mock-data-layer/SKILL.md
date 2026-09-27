---
name: mock-data-layer
description: Use when building a frontend before the backend exists, or when a screen needs realistic sample data, so the real API can replace the mock later by changing one place.
---

# Mock Data Layer

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Types first.** In `features/<name>/types.ts` define the shape the real API will return. The UI depends only on these types.
2. **One api file per feature:** `features/<name>/api.ts` exports async functions (`getProducts`, `getMatch`, `getFeed`) that return typed data. Today they return mock data, later they call `fetch` or an RTK Query endpoint with the same signature. Components never import mock data directly.
3. **Keep mocks in one place:** `features/<name>/mock.ts` (or `lib/mock/`). Realistic values: believable names, prices in a sensible range, plausible scores and dates, varied text length, some missing optional fields.
4. **Simulate the real world.** Add a small delay (200-600ms), plus switches to force loading, empty and error results so all four states get exercised. Paginated lists return pages with a cursor or page number, like a real API.
5. **No fake secrets or real people.** Do not use real names, photos or brands. Use placeholder images (`picsum`, or local files in `public/`).
6. **Mark the seam.** One line at the top of each `api.ts`: `// MOCK: replace with real API`. When the backend arrives, only this file changes, and the mock file can be deleted.
7. **Next.js:** if a route handler helps (`app/api/...`), it may serve the mock JSON so the UI already uses `fetch`. Otherwise keep plain functions.

## Red Flags

| Thought | Reality |
|---|---|
| "Hardcode the array in the component" | The swap to a real API then touches every component. |
| "Only the happy path has data" | Loading, empty and error are where UI bugs hide. |
| "Instant mock responses" | Real APIs are slow. Add delay to see the loading states. |
| "Use a real celebrity's photo and name" | Use placeholders. |

---
name: nextjs-structure
description: Use when creating a Next.js project, adding a route, feature or component to one, or when a Next.js codebase has grown messy and the user asks where files should go or how to organize folders.
---

# Next.js Structure

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

## Steps

1. **Look first.** Read `next.config.*`, `tsconfig.json` (path alias), and whether the project uses `app/` or `pages/`, with or without `src/`. If a structure exists, follow it and do not invent a second one. A Pages Router project stays on Pages Router unless the user asks to migrate.
2. **New project default:** App Router, `src/`, TypeScript, feature-based folders.

   ```
   src/
     app/                    # routing only: layout, page, loading, error, route.ts
       (marketing)/ (app)/   # route groups, URL unchanged
       api/                  # route handlers
     components/ui/          # small reusable pieces (Button, Input)
     features/<name>/        # one feature: components, hooks, actions, types together
     lib/                    # helpers, db and api clients, utils
     hooks/                  # hooks used across features
     types/                  # shared types
     styles/globals.css
   public/                   # static assets
   ```
3. **Rules:**
   - `app/` holds route files only. Logic lives in `features/` or `lib/`. `page.tsx` composes, it does not compute.
   - Server Components by default. Add `"use client"` only for interactivity, as low in the tree as possible.
   - Keep server-only code (db, secrets) in files that import `server-only`. Env vars without `NEXT_PUBLIC_` never reach client code.
   - Import with the `@/` alias, not `../../..`.
4. **Naming:** folders `kebab-case`, component files `PascalCase` (or the project's existing style), route files exactly `page`, `layout`, `loading`, `error`, `not-found`, `route`.
5. **When to split:** a feature with 5 or more files gets its own `features/<name>/`. Move code to `components/` or `lib/` only once two features need it.
6. **State management.** Only when `@reduxjs/toolkit` is in `package.json` (never add Redux on your own). Use Redux Toolkit like this:

   ```
   src/lib/store/
     store.ts            # makeStore() factory, RootState, AppDispatch
     hooks.ts            # typed useAppDispatch, useAppSelector
     StoreProvider.tsx   # "use client", wraps children in app/layout.tsx
     baseApi.ts          # the one RTK Query createApi
   src/features/<name>/
     <name>Slice.ts      # client/UI state of this feature
     <name>Api.ts        # baseApi.injectEndpoints(...) for this feature
   ```

   - No global singleton store. Create it per request with `makeStore()`, or one user's data can leak to another on the server.
   - Redux hooks work in Client Components only. Fetch in Server Components and pass props down, or put `"use client"` on the smallest part that needs the store.
   - Server data lives in RTK Query, slices hold only UI/client state (modal, filter, cart). Never keep the same data in both.
   - Endpoints go in each feature via `injectEndpoints`, not in one giant api file.
   - Always use the typed hooks from `hooks.ts`, not plain `useSelector`/`useDispatch`.
7. **After placing files,** check imports resolve and the route renders (`next build` or the dev server).

## Red Flags

| Thought | Reality |
|---|---|
| "Put every component in `components/`" | Feature-specific components live with their feature. |
| "Fetch and compute in `page.tsx`" | Move it to `features/` or `lib/`. Keep the page thin. |
| "Add `use client` at the top to be safe" | It ships JS to the browser. Push it down to the leaf that needs it. |
| "Restructure the whole project first" | Follow the existing layout. Change it only when asked. |
| "Create `utils.ts` for one helper" | Wait until a second feature needs it. |
| "One `export const store = configureStore(...)`" | In Next.js that is shared across requests. Use the `makeStore()` factory. |
| "Store the API response in a slice" | RTK Query already caches it. A slice copy goes stale. |

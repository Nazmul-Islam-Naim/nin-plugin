---
name: react-native-structure
description: Use when structuring a React Native app — where files go, and how to apply mobile-architecture's layers using React Navigation, a state layer and an API client. Read mobile-architecture first for the why; this skill is the React Native-specific how.
---

# React Native Structure

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

Builds on `mobile-architecture`: that skill's four layers (screen → state → repository → API client) and SOLID principles apply here unchanged — this skill only maps them onto actual React Native files.

## Steps

1. **Look first.** Check for an existing `src/` layout, navigation library and state tool in `package.json`. Follow what's there; do not restructure an existing project unless asked.
2. **New project default:** feature-based, one folder per business capability:

   ```
   src/
     features/<name>/
       screens/<Name>Screen.tsx   # the View: renders from state, calls hooks/actions
       api.ts                      # API client for this feature
       repository.ts               # interface + concrete impl (wraps api.ts, optional cache)
       hooks.ts                    # the state layer for this feature (or store.ts slice)
       types.ts
     navigation/
       RootNavigator.tsx           # stacks/tabs, screens wired to their route names
     lib/
       apiClient.ts                # shared axios/fetch instance, base URL, auth interceptor
     theme/                        # see react-native-ui
   App.tsx
   ```

3. **Map the layers:**
   - **Screen** — a component under `screens/`. Reads state via a hook, renders, forwards taps/inputs to functions from that hook. No `fetch`/`axios` calls inside it.
   - **State layer** — a hook (`useOrders()`) or a store slice per feature. Calls the repository, exposes `{ data, loading, error, actions }`.
   - **Repository** — `OrderRepository` interface + `ApiOrderRepository` (talks to `api.ts`, optionally checks a cache first). Swappable for a fake in tests.
   - **API client** — `api.ts` exports typed functions calling the shared `apiClient` (an axios instance in `lib/apiClient.ts` with base URL + auth header interceptor).

4. **Navigation.** React Navigation. One `RootNavigator` composing stacks/tabs; each screen receives typed route params (`NativeStackScreenProps`). Screens navigate via the `navigation` prop or `useNavigation()`, never by importing another screen's internals.

5. **State management.** Only when the tool is already in `package.json`. Redux Toolkit + RTK Query mirrors this repo's `nextjs-structure` convention — the same backend API, the same pattern on web and mobile. Otherwise, Zustand or plain hooks for a smaller app. Never add a new state library on your own.

6. **SOLID, RN-shaped:** a new data source (e.g. adding a local SQLite cache) means a new repository implementation, not a branch in an existing one; state hooks depend on the repository's interface/type, never `new ApiOrderRepository()` inline — pass it in or resolve it from a small factory/context.

7. **After scaffolding,** confirm the app builds (`npx expo start` or `react-native run-*`), navigation renders every screen, and each repository resolves without a circular import.

## Red Flags

| Thought | Reality |
|---|---|
| "Call `axios` directly in the screen" | Same violation as `mobile-architecture` — go through the repository. |
| "One `store.ts` for the whole app" | Feature-based state/store slices, like the folder structure. |
| "Add a second state library because this feature feels different" | Check what's already installed. Consistency beats a marginally better fit. |
| "Hardcode the base URL in `api.ts`" | Keep it in `lib/apiClient.ts`, one place, env-driven. |

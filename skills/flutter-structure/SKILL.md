---
name: flutter-structure
description: Use when structuring a Flutter app — where files go, and how to apply mobile-architecture's layers using Riverpod, go_router and a dio API client. Read mobile-architecture first for the why; this skill is the Flutter-specific how.
---

# Flutter Structure

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, paths and code in English.

Builds on `mobile-architecture`: that skill's four layers (screen → state → repository → API client) and SOLID principles apply here unchanged — this skill only maps them onto actual Flutter files.

## Steps

1. **Look first.** Read `pubspec.yaml` and the existing `lib/` layout, router and state tool. Follow what's there; do not restructure an existing project unless asked.
2. **New project default:** feature-first, one folder per business capability:

   ```
   lib/
     features/<name>/
       presentation/<name>_screen.dart       # the View: ConsumerWidget, renders from state
       application/<name>_controller.dart    # the state layer: Riverpod (Async)Notifier
       data/<name>_repository.dart           # abstract class + ApiXRepository impl + its Provider
       data/<name>_api.dart                  # API client for this feature (typed calls on dio)
       domain/<name>.dart                    # freezed + json_serializable model
     core/
       api_client.dart                       # shared Dio, base URL, auth interceptor
       router.dart                           # go_router routes + redirect
     theme/                                  # see flutter-ui
   main.dart                                 # runApp(ProviderScope(child: App()))
   ```

3. **Map the layers:**
   - **Screen** — a `ConsumerWidget` under `presentation/`. Reads `ref.watch(ordersControllerProvider)`, renders with `AsyncValue.when(data:, loading:, error:)`, forwards taps to `ref.read(ordersControllerProvider.notifier).method()`. No `Dio` calls inside it.
   - **State layer** — an `AsyncNotifier` per feature (`OrdersController`). Calls the repository, exposes `AsyncValue<T>` plus action methods.
   - **Repository** — `abstract class OrderRepository` + `ApiOrderRepository` (talks to `<name>_api.dart`, optionally checks a cache first), exposed as `orderRepositoryProvider`. Swappable for a fake in tests with `ProviderScope(overrides: [...])`.
   - **API client** — `<name>_api.dart` exposes typed functions on the shared `Dio` from `core/api_client.dart` (base URL from `--dart-define`, auth header interceptor).

4. **Navigation.** go_router. One `router.dart` with all routes (typed routes via `go_router_builder` if already used); auth guarding lives in the router's `redirect`, not in each screen. Screens navigate with `context.go()`/`context.push()`, never by importing another screen's internals.

5. **State management.** Riverpod (`flutter_riverpod`, `riverpod_annotation` if code-gen is already used). If the project already uses Bloc or Provider, follow that. Never add a second state library on your own.

6. **SOLID, Flutter-shaped:** a new data source (e.g. a local Drift/Hive cache) means a new repository implementation, not a branch in an existing one; controllers depend on the abstract repository via `ref.watch(orderRepositoryProvider)`, never `ApiOrderRepository()` inline.

7. **After scaffolding,** run `dart run build_runner build -d` (if freezed/code-gen is used), then `flutter analyze` and `flutter test` with zero issues, and confirm `flutter run` opens every route.

## Red Flags

| Thought | Reality |
|---|---|
| "Call `Dio` directly in the widget" | Same violation as `mobile-architecture` — go through the repository. |
| "`setState` + `FutureBuilder` is quicker" | Fine for local UI state only. Server data goes through the controller. |
| "One global provider file for the whole app" | Providers live in their feature, like the folder structure. |
| "Add Bloc too, this feature feels different" | Check `pubspec.yaml`. Consistency beats a marginally better fit. |
| "Hardcode the base URL in `<name>_api.dart`" | Keep it in `core/api_client.dart`, one place, from `--dart-define`. |

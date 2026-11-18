---
name: flutter-ui
description: Use when building or styling Flutter screens or widgets — theme tokens, layout, a widget's states, safe-area/responsive behavior, accessibility and visual finish. Read business-design first for the chosen direction; this is the Flutter-specific how.
---

# Flutter UI

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

Builds on `business-design`'s picked direction (palette, fonts, mood) — this skill turns that into Flutter theme tokens, widgets and polish.

## Steps

1. **Tokens.** `lib/theme/` builds a Material 3 `ThemeData` for `light` and `dark` from the picked palette (`ColorScheme.fromSeed` then override the exact brand colors) and fonts (`TextTheme`). Spacing and radius go in a `ThemeExtension` (`AppSpacing`, `AppRadius`). `MaterialApp(theme:, darkTheme:, themeMode: ThemeMode.system)`. Widgets read `Theme.of(context).colorScheme.primary`, never a raw `Color(0xFF...)`.
2. **Widget.** Reuse first — grep for an existing widget before writing a new one. Prefer Material 3 widgets (`FilledButton`, `TextField`, `Card`, `ListTile`) styled through the theme, not per-widget overrides. Cover every state: default, pressed, disabled (`onPressed: null`), loading, error — via `WidgetStateProperty` in the theme, not manual flags. Make widgets `const` where possible.
3. **Layout & responsive.** `Row`/`Column`/`Expanded`/`Flexible`, no fixed pixel widths that don't scale. Wrap screens in `SafeArea` (or `Scaffold` + `SafeArea` body) for notches and system bars. `ListView.builder`/`SliverList` for long lists. Check a small phone and a tablet width; use `LayoutBuilder`/`MediaQuery.sizeOf` only where the layout must truly branch by size.
4. **Accessibility.** Every icon-only control gets a `tooltip` or `Semantics(label:)`; minimum 48x48 touch target; meaningful `Semantics` for custom tap areas. Never clamp or override `MediaQuery.textScaler` — users raised their OS font size on purpose; test at 2x text.
5. **States for data.** Every data-driven screen designs loading (skeleton or `CircularProgressIndicator`), empty and error (with retry) states via `AsyncValue.when` — pair with `mock-data-layer` to exercise all three before a real backend exists.
6. **Polish.** One accent color for the primary action, consistent radius/elevation from tokens, short (150-250ms) implicit animations (`AnimatedContainer`, `AnimatedOpacity`, `AnimatedSwitcher`) for state changes and press feedback, skeletons matching the content shape. Use `.adaptive` constructors or `Platform`/`Theme.of(context).platform` only where the OS truly expects it (switches, dialogs, haptics) — not the whole screen.
7. **Self-review before done.** Re-check: contrast, touch targets, all four states present, no overflow stripes on a small device or at 2x text, dark mode has real chosen values (not just inverted defaults), `flutter analyze` is clean.

## Red Flags

| Thought | Reality |
|---|---|
| "Fixed width/height in px" | Breaks on other screen sizes. Use flex, constraints, or `LayoutBuilder`. |
| "`Color(0xFF...)` inline is fine for one widget" | Tokens live in the theme. Raw colors break dark mode. |
| "Skip the tooltip, the icon says enough" | Screen readers see no icon meaning. Label it. |
| "Only test on my emulator" | Check a small phone and a tablet, portrait and landscape, and 2x text. |
| "Clamp textScaler for a fixed look" | Breaks users who increased their OS font size for a reason. |
| "New UI package for one screen" | Check `pubspec.yaml` first; Material 3 covers most needs. |

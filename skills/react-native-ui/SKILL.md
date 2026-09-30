---
name: react-native-ui
description: Use when building or styling React Native screens or components — tokens, layout, a component's states, safe-area/responsive behavior, accessibility and visual finish. Read business-design first for the chosen direction; this is the React Native-specific how.
---

# React Native UI

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

Builds on `business-design`'s picked direction (palette, fonts, mood) — this skill turns that into React Native tokens, components and polish.

## Steps

1. **Tokens.** One `theme.ts` exporting semantic values (`colors.background`, `colors.primary`, `spacing`, `radius`, `fontSize`) built from the picked palette/fonts, in a `light` and a `dark` variant switched via `useColorScheme()`. Components import only semantic names, never raw hex. If NativeWind is already installed, define the same tokens in `tailwind.config.js` instead of a second system; otherwise use `StyleSheet.create()` per component, reading from `theme.ts`.
2. **Component.** Reuse first — grep for an existing one before writing a new one. Build with core primitives (`View`, `Text`, `Pressable`, `TextInput`, `Image`, `FlatList` for lists). Cover every state: default, pressed, disabled, loading, error — use `Pressable`'s style function for the pressed state, not manual tracking.
3. **Layout & responsive.** Flexbox only, no fixed pixel widths that don't scale. Respect safe areas with `react-native-safe-area-context` (`useSafeAreaInsets`/`SafeAreaView`) for notches and status bars. Check on a small phone and a tablet width; reach for `useWindowDimensions` only where the layout must truly branch by size, not on every screen.
4. **Accessibility.** `accessibilityRole` and `accessibilityLabel` on every interactive element, minimum 44x44 touch target (`hitSlop` if the visual is smaller), `accessibilityState={{ disabled, busy }}`. Never disable `allowFontScaling` — users raised their OS font size on purpose.
5. **States for data.** Every data-driven screen designs loading (skeleton or spinner), empty and error states, not just the happy path — pair with `mock-data-layer` to exercise all three before a real backend exists.
6. **Polish.** One accent color for the primary action, consistent radius/shadow from tokens, short (150-250ms) `Animated`/Reanimated transitions for entrance and press feedback, skeletons matching the content shape. Fork by `Platform.OS` only where the OS truly expects it (haptics, back-swipe) — not the whole screen.
7. **Self-review before done.** Re-check: contrast, touch targets, all four states present, no fixed widths clipping on a small device, dark mode has real chosen values (not just inverted defaults).

## Red Flags

| Thought | Reality |
|---|---|
| "Fixed width/height in px" | Breaks on other screen sizes. Use flex and percentages, or safe-area-aware sizing. |
| "Skip accessibilityLabel, the icon says enough" | Screen readers see no icon meaning. Label it. |
| "Only test on my simulator" | Check a small phone and a tablet, portrait and landscape. |
| "Disable allowFontScaling for a fixed look" | Breaks users who increased their OS font size for a reason. |
| "New styling library for one screen" | Check what's installed (NativeWind, styled-components) before adding a new one. |

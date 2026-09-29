---
name: performance-optimization
description: Use when a React + Next.js frontend is built and needs to load and run fast — images, fonts, bundle size, code-splitting and re-renders, not backend or infra performance.
---

# Performance Optimization

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Images.** `next/image` everywhere, correct `width`/`height` or `fill` with a sized parent, `priority` only on the one above-the-fold hero image, everything else lazy by default. Serve modern formats (avif/webp) via the loader, no unsized `<img>` tags.
2. **Fonts.** `next/font` (google or local) instead of a `<link>` tag or `@import`, so fonts self-host and don't block render. Subset to the weights actually used. `font-display: swap` when hand-rolling `@font-face`.
3. **Code-splitting.** `next/dynamic` (or `React.lazy`) for anything heavy and not needed on first paint: modals, charts, rich editors, below-the-fold sections. `ssr: false` only when the module touches `window`/`document`.
4. **Bundle size.** Before adding a dependency, check its size (bundlephobia or similar) against what a few lines of code would cost. Import the specific function, not the whole library (`import debounce from 'lodash/debounce'`, not the full `lodash`). Flag any already-installed dependency pulling in a large transitive tree for a small feature.
5. **Re-renders.** State lives at the lowest component that needs it, not lifted "just in case." `memo` on list items and expensive pure components, stable `key`s (never array index on a reorderable list), derived values computed inline instead of duplicated in state.
6. **Report, don't gold-plate.** Note what's already fine. Fix only what's actually slow or clearly wrong per the steps above — this is a check, not a rewrite.

## Red Flags

| Thought | Reality |
|---|---|
| "It's just a prototype, skip it" | `next/image` and `next/font` cost nothing extra to use correctly from the start. |
| "Add a caching/perf library" | Check the five steps above first — most wins are free, no dependency needed. |
| "Memoize everything" | Memoization has its own cost. Only where a component is expensive or re-renders often with the same props. |
| "Lazy-load the whole page" | Only split what isn't needed for first paint. Over-splitting adds waterfalls. |

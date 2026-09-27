---
name: data-dense-ui
description: Use when building UI that shows lots of changing data in React + Tailwind: feeds and infinite scroll, tables with sort and filter, search and filter panels, live score tiles, charts, and their loading, empty and error states.
---

# Data-Dense UI

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Every data view has 4 states:** loading (skeleton shaped like the content, not a spinner), empty (say why and what to do), error (message and retry), and data. Build all four.
2. **Feed and lists.** Load a page at a time, infinite scroll with an `IntersectionObserver` sentinel and a "load more" button as fallback. Fixed card heights or aspect ratios to avoid layout shift. Virtualize past a few hundred rows.
3. **Tables.** Sortable headers (`aria-sort`), filters above, pagination or virtual scroll, sticky header, right-align numbers, tabular numerals (`tabular-nums`). Mobile: scroll wrapper or cards (see `responsive-design`).
4. **Search and filters.** Debounce input (about 300ms), keep the query in the URL search params so the view is shareable, show active filters as removable chips with a "clear all".
5. **Live tiles (scores, prices, status).** One clear "LIVE" marker (not color alone). Update only the changed value, keep width stable, briefly highlight the change, never re-mount the whole card. Show "last updated" time. Pause updates when the tab is hidden.
6. **Charts.** Use the chart library already in the project. One message per chart, labeled axes, colors from tokens, readable at 360px, a text or table alternative for accessibility.
7. **Density.** Compact spacing is fine, but keep text at least 14px and touch targets at least 44px on touch devices.
8. **Data shape.** Keep types in `features/<name>/types.ts` and fetch through the feature's api function (see `mock-data-layer`).

## Red Flags

| Thought | Reality |
|---|---|
| "Show a spinner while loading" | A skeleton keeps layout stable and feels faster. |
| "Render all 5,000 rows" | Paginate or virtualize. |
| "Re-render the list on every update" | Update the changed cell only, or the page flickers and jumps. |
| "Filter state in `useState`" | Put it in the URL so back button and sharing work. |
| "Red vs green only" | Add an icon or text. Color alone fails for color-blind users. |

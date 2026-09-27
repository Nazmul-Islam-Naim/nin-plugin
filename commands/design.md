---
description: Design and build frontend UI end to end (business-fit direction, tokens, components, responsive, review).
argument-hint: <what to build or redesign>
---

Task: $ARGUMENTS

If the task is empty, infer it from the open file or the current conversation.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Run these steps in order. For each step, invoke the named skill and follow it. Do not restate its rules.

1. **Direction.** Invoke `business-design`: infer the business, make 2-3 directions with samples, show the Bangla card. **Stop and wait for the user's pick. Write no project code before it.**
2. **Pages and tokens.** Invoke `site-patterns` to fix the page list for this type of site, then `design-tokens` to put the chosen palette and fonts in place.
3. **Build.** Invoke `component-build`, `responsive-design` and `ui-design-guidelines` to build the screens and components. Add `data-dense-ui` for feeds, tables and live data, `mock-data-layer` when there is no backend yet, and `visual-polish` for the final finish. If the project is Next.js, also invoke `nextjs-structure` and place files accordingly.
4. **Review.** Invoke `design-review` on the result, fix the High items, then report the remaining Medium and Low items briefly.

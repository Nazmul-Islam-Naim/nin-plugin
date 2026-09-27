---
name: visual-polish
description: Use when a React + Tailwind UI works but looks flat, plain or generic, and needs the finish that makes it attractive: typography, imagery, spacing rhythm, depth, motion and dark mode.
---

# Visual Polish

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

## Steps

1. **Typography.** One display face for headings and one clean text face, or a single family in two weights. Clear size steps, tight leading on headings (`leading-tight`), comfortable on body (`leading-relaxed`). Weight and size carry the hierarchy, not color.
2. **Imagery.** One strong image beats five weak ones. Fixed aspect ratios, `object-cover`, consistent corner radius, a subtle overlay when text sits on a photo. Optimize with `next/image` in Next.js.
3. **Spacing rhythm.** Generous space between sections (`py-16` or more on desktop), tighter inside a group. Consistent gutters. Align to one grid.
4. **Depth and surface.** Cards get a soft shadow or a thin border, not both heavy. Consistent radius from tokens. Use one accent color for the main action, neutrals for the rest.
5. **Motion.** Small and purposeful: hover lift, fade or slide on enter, 150-250ms, `ease-out`. Wrap in `motion-safe:` and never block content behind an animation.
6. **States feel alive.** Hover, focus, pressed and loading all look designed, not default. Skeletons match the content shape.
7. **Dark mode.** Not inverted colors: slightly lifted surfaces, softer white text, reduced saturation, checked contrast (see `design-tokens`).
8. **Restraint.** Stop when the page has one focal point per section. Remove decoration that does not help scanning.

## Red Flags

| Thought | Reality |
|---|---|
| "Add gradients and shadows everywhere" | Depth is a hierarchy tool. Everything raised means nothing stands out. |
| "Animate everything" | It slows and distracts. Motion only where it explains change. |
| "Stock-looking hero, fix later" | The hero is the first impression. Spend time there. |
| "Three fonts look richer" | Two at most. More reads as messy. |

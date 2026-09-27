---
name: ui-design-guidelines
description: Use when designing or laying out a page, screen or section (layout, spacing, typography, color, responsiveness, accessibility) in React + Tailwind, before building individual components.
---

# UI Design Guidelines

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

## Checklist

1. **Layout:** mobile-first. Start at 360px, add `sm:`/`md:`/`lg:` only where the layout must change. One clear primary action per screen. Details in `responsive-design`.
2. **Spacing:** use the Tailwind scale only (`p-2/4/6/8`, `gap-*`). No arbitrary `px` values. Group related items closer than unrelated ones.
3. **Typography:** max 2 font families, 4-5 sizes. Body text at least `text-base`. Line length 45-75 characters (`max-w-prose`).
4. **Color:** text contrast at least 4.5:1 (3:1 for large text and UI borders). Never use color as the only signal (add icon or text). Take colors from tokens, see `design-tokens`.
5. **Accessibility:** semantic HTML first (`nav`, `main`, `button`, `label`). Visible focus ring. Touch targets at least 44px. Every image has `alt`, every input has a `label`.
6. **States:** design empty, loading and error states for every data-driven section, not just the happy path.
7. **Motion:** short (150-250ms), and respect `motion-reduce:`.

## Red Flags

| Thought | Reality |
|---|---|
| "I'll fix mobile later" | Start mobile-first, desktop is the addition. |
| "A `div` with onClick is fine" | Use `button`. It gives keyboard and screen reader support for free. |
| "Gray text looks cleaner" | Check contrast. Light gray on white usually fails. |
| "I'll hardcode this color once" | Use a token, or the theme drifts. |

---
name: component-build
description: Use when building or changing a single React + Tailwind UI component (button, input, form, modal, card, dropdown, table), including its states, variants and accessibility.
---

# Component Build

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

## Steps

1. **Reuse first.** Grep the project for an existing component before writing a new one. Extend it if it is close.
2. **Define the API.** Small props: `variant`, `size`, `disabled`, plus `className` and `...rest` passthrough. No prop for a thing that never changes.
3. **Use semantic elements.** `button` for actions, `a` for navigation, `label` tied to `input`, `dialog` (or `role="dialog"` with `aria-modal`) for modals.
4. **Cover every state:** default, hover, focus-visible, active, disabled, loading, error. Focus ring must be visible (`focus-visible:ring-2`).
5. **Style with tokens** (see `design-tokens`), Tailwind classes only. Merge classes with the project's existing helper (`clsx`, `cn`), do not add a new dependency for it.
6. **Keyboard:** everything reachable by Tab, activated by Enter/Space, Esc closes overlays, focus returns to the trigger.
7. **Responsive:** works from 360px. No fixed widths that overflow. Rules in `responsive-design`.
8. **Check:** render it in all states once. Non-trivial logic (modal focus, form validation) gets one small test.

## Red Flags

| Thought | Reality |
|---|---|
| "Only the default state matters" | Disabled, loading and error are where users get stuck. |
| "I'll add 10 props for flexibility" | Add props when a second use needs them. |
| "Mouse works, so it's done" | Test with keyboard only. |
| "New library for this dropdown" | Check what is already installed, or use native elements first. |

---
name: design-tokens
description: Use when defining or changing colors, fonts, spacing, radius, shadows or dark mode for a React + Tailwind project, or when the same raw value is repeated across components.
---

# Design Tokens

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

## Steps

1. **Look first.** Read `tailwind.config.*` and the global CSS for existing tokens. Extend them, do not create a second system.
2. **Two layers only:**
   - Primitive: raw scale (`gray-50..950`, `brand-500`).
   - Semantic: meaning (`background`, `foreground`, `muted`, `primary`, `border`, `danger`). Components use semantic names only.
3. **Store as CSS variables** in `:root`, override under `.dark` (or `[data-theme="dark"]`), and map them in the Tailwind theme (`colors: { background: "var(--background)" }`). Dark mode then needs no per-component `dark:` classes.
4. **Scales:** spacing stays on the Tailwind scale. Type sizes 4-5 steps. Radius and shadow 2-3 steps each.
5. **Check contrast** for every text/background pair in both light and dark (4.5:1 body text).
6. **Migrate:** grep for hardcoded hex values and arbitrary `[...]` classes, replace with tokens.

## Red Flags

| Thought | Reality |
|---|---|
| "Name it `blue-button`" | Name by role (`primary`), not by appearance. |
| "Dark mode is just inverted colors" | Pick dark values on purpose and check contrast. |
| "One more shade won't hurt" | Every extra token is one more thing to keep consistent. |

---
name: responsive-design
description: Use when a React + Tailwind page or component must work across phone, tablet and desktop, or when something overflows, gets cut off or looks broken at a different screen width (layout, navigation, tables, images, forms).
---

# Responsive Design

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Mobile-first.** Write the base classes for 360px. Add `md:` (768), `lg:` (1024), `xl:` (1280) only where the layout must change. Never `max-*` breakpoints unless unavoidable.
2. **Layout.** Grid goes 1 → 2 → 3 columns (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3`). Side-by-side items stack on small screens (`flex-col md:flex-row`). Wrap content in `max-w-*` with `mx-auto px-4`. Sidebar becomes a drawer or moves below on mobile.
3. **Navigation.** Mobile: menu button opening a drawer, focus trapped, Esc closes. Desktop: links inline. Touch targets at least 44px.
4. **Tables and wide data.** Wrap in `overflow-x-auto`, or turn rows into cards on mobile. Keep the key column visible (sticky). Never let the page itself scroll sideways.
5. **Media and text.** `img` gets `max-w-full h-auto` and a fixed aspect ratio (`aspect-video`) to avoid layout shift. Long words and URLs get `break-words`. Grow text with breakpoints (`text-base md:text-lg`), not pixels.
6. **Forms and inputs.** Full width on mobile. Input font at least 16px (smaller makes iOS zoom in). Fixed bottom bars need `pb-[env(safe-area-inset-bottom)]`. Use the right `type`/`inputmode`.
7. **Use the viewport meta and `min-h-dvh`,** not `100vh`, for full-height layouts on mobile browsers.
8. **Check** at 360, 768, 1024 and 1440px, portrait and landscape on a phone: no horizontal scroll, nothing clipped, nothing overlapping, all actions reachable.

## Red Flags

| Thought | Reality |
|---|---|
| "It looks fine on my monitor" | Most users are on phones. Check 360px first. |
| "Fixed `w-[600px]` is simpler" | It overflows on phones. Use `w-full max-w-*`. |
| "Hide it on mobile" | If users need it on desktop they need it on mobile. Rearrange, don't drop. |
| "Table is too wide, shrink the font" | Scroll the table or use cards. |
| "`h-screen` for full height" | Mobile address bar breaks it. Use `min-h-dvh`. |

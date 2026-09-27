---
name: business-design
description: Use when starting a new UI, redesigning a page that looks plain or generic, or when the user wants the design to fit their business and attract users. Picks a visual direction per business type and shows sample images before any code is written.
---

# Business Design

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish, even if the user writes in Banglish. Keep technical terms in English.

## Overview

A generic white page with a default blue button fits no business. Work out what the business is, propose 2-3 distinct directions, show samples, and build only what the user picks. This skill covers direction and samples. Pages and sections come from `site-patterns`. Building is done by `design-tokens`, `component-build` and `design-review`.

## Steps

1. **Infer the business, don't ask.** Read the requirement, README and existing UI. Decide: what the product is, who uses it, what feeling builds trust or appetite (calm and precise for a data tool, warm for food, bold for a shop). List these under "ধরে নিয়েছি". Ask (max 2 short questions) only if two readings lead to very different designs.
2. **Define 2-3 directions that really differ.** For each: name, mood, palette (semantic roles: background, foreground, primary, accent), font pair, layout (hero, card, grid, density), button and card style, and one line on why it fits this business. Not three shades of the same blue.
3. **Make a sample for each direction, before any project code.** Prefer an image generation tool if one is available (an image skill or MCP). Otherwise write a standalone `design-samples/<direction>.html` (inline CSS, real copy, the actual key screen) and open or screenshot it. Never edit project source in this step.
4. **Show the card in Bangla, then stop:**

   ```
   ব্যবসা (ধরে নেওয়া): ...
   Direction ১/২/৩: নাম, mood, রং, font — ১ লাইনে কেন মানায়
   নমুনা: <image বা file path>
   আমার সুপারিশ: কোনটা এবং কেন
   ```

   Wait for the user's pick or feedback. Nothing is built before it. "Mix 1 and 3" or a tweak means new samples, kept short.
5. **After the pick, build.** Put the chosen palette and fonts in tokens (`design-tokens`), build screens and components (`component-build`, `ui-design-guidelines`), then check the result (`design-review`). Fix High items before showing the user.
6. **Clean up.** Keep `design-samples/` only if the user wants it, otherwise remove it.

## Direction hints (starting points, not rules)

| Business | Direction |
|---|---|
| SaaS / data tool | Clean, high contrast, one strong accent, roomy cards, clear primary action |
| Shop | Large product images, price and buy button stand out, tight grid |
| Food / local | Warm colors, big photos, simple menu and order path |
| Education / portfolio | Friendly or editorial type, generous whitespace, showcase blocks |

## Red Flags

| Thought | Reality |
|---|---|
| "Ask the user what style they want" | Users rarely know. Show samples, let them react. |
| "Start coding, refine later" | Nothing is written before the user picks. |
| "Three directions, same palette" | If they look alike, the choice is fake. |
| "Skip samples, describe in words" | Words hide the ugliness. Show it. |

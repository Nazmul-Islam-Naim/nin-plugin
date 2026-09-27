---
name: design-review
description: Use when the user asks to review, audit or critique existing UI code or a screen (React + Tailwind) for design, accessibility, consistency or responsiveness problems.
---

# Design Review

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Read the code**, do not guess. Open the components and styles under review, plus the tokens if they exist.
2. **Check against** `ui-design-guidelines`, `component-build`, `design-tokens` and `responsive-design`: contrast, semantic HTML, focus ring, states, touch targets, hardcoded values, responsiveness at 360px, keyboard use.
3. **Report a short list, worst first.** Each item: `file:line`, what is wrong, why it matters, one-line fix.
4. **Severity:**
   - **High:** blocks users (no keyboard access, contrast fails, broken on mobile).
   - **Medium:** inconsistent or confusing (missing states, hardcoded colors).
   - **Low:** polish.
5. **Do not edit** anything unless the user asks. End with the count per severity and offer to fix the High items first.

## Red Flags

| Thought | Reality |
|---|---|
| "Looks fine to me" | Run the checklist, appearance hides a11y bugs. |
| "List everything I noticed" | Rank it. Ten Low items bury one High. |
| "I'll just fix it while reviewing" | Review reports, fixing is a separate ok. |

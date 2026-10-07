---
name: report-study
description: Use when the user hands over any report (audit, test, analytics, client, incident, research; file, link or pasted text) and wants it understood, summarised, or turned into a workplan and next steps.
---

# Report Study

## Overview

Read a report fully, give a short summary and a prioritised workplan, then help the user act on it. Read-only: it never edits project files unless the user asks, and it never starts building.

**Language:** every reply and card is in Bangla script (বাংলা অক্ষর), never Banglish, even if the user writes in Banglish. Keep technical terms in English.

## Steps

1. **Read the whole report first**, don't skim (use the Read tool; for a large PDF read it in `pages` chunks). Note its type, goal, audience and key numbers.
2. **Assume, don't ask** for anything vague; list assumptions under "ধরে নিয়েছি". Ask (max 2 short questions) only when the report's type or goal is unclear and changes the plan.
3. **Show one Bangla card, then stop:**

   ```
   রিপোর্ট: <file/link> (<type>)
   সারাংশ: ৩-৫ লাইন
   মূল তথ্য: <findings/সংখ্যা>
   সমস্যা/ঝুঁকি: <item> — High/Med/Low
   ধরে নিয়েছি: ...
   Workplan:
   ১. <কাজ> — priority, কেন, effort (S/M/L), depends on <n>
   ২. ...
   ```
4. **After the user replies,** help based on the report: answer questions, drill into one item, draft fixes, emails or tasks. A correction means re-showing a shorter card.
5. **Handoff, never auto-chain.** If report items are buildable features or changes, offer to pass them to `prd-to-requirements` or `requirement-to-spec`; the user decides.

## Rules

- Cite the section/page for every claim.
- Never invent numbers, dates or causes the report doesn't state.
- Say so when the report is incomplete or contradicts itself.

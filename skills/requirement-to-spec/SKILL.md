---
name: requirement-to-spec
description: Use when the user gives a new requirement, feature request or change request and wants it studied, checked against existing work, and turned into an instruction file and spec.
---

# Requirement to Spec

## Overview

Study a requirement, check it against existing work, show the user a short **Bangla summary card**, and write files only after the user says "ok". The user reads Bangla fast and finds English answers a hassle: summary first, assume instead of interrogating.

Files live in the user's project (root = `git rev-parse --show-toplevel`, else cwd):

```
docs/specs/
  MAP.md                       # | id | title | instruction | spec | relation | date |
  instructions/001-<slug>.md   # frozen requirement
  specs/001-<slug>.md          # English spec, written from the instruction
```

`relation` = `creates` (new spec) or `updates` (change to an existing spec; each change gets a new instruction).

## Steps

1. **Study, read-only.** Restate the requirement in 1-2 lines. Next id = highest id in MAP + 1, three digits (`001` if no MAP).
2. **Check existing work, cheapest first.** Read `docs/specs/MAP.md` (titles usually decide it). Open only the matched instruction/spec. Then Grep/Glob code and README for 3-5 keywords to catch features built without a spec. Explore subagent only if the repo is large.
3. **Assume, don't ask.** Fill each gap with the most sensible default and list it under "ধরে নিয়েছি". Ask only if a gap blocks: two equally likely readings that lead to different specs, or a fact found nowhere in MAP/specs/code. Then max 2 questions via AskUserQuestion; each is simple English (15 words or fewer) plus one Bangla line, 2-4 options, recommended first.
4. **Show the card, in Bangla, before anything is written:**

   ```
   ফলাফল: নতুন | মিলে গেছে | আংশিক মিল
   Requirement (১ লাইন): ...
   আগে যা আছে: MAP id / spec / code path (না থাকলে "কিছু নেই")
   নতুন যা লাগবে: ...
   ধরে নিয়েছি: ৩-৫টা ছোট পয়েন্ট
   আমার সুপারিশ: নতুন spec লিখি / আগের spec update করি / কিছু করার দরকার নেই — ১ লাইনে কারণ
   ```

   Then stop and wait. **Every reply, question and summary is written in Bangla script (বাংলা অক্ষর), never Banglish (English letters), even if the user writes in Banglish.** Keep technical terms in English. The user may reply in English, Bangla or Banglish.
5. **Gate = "ok".** Always show the card as a normal chat reply first and wait — plan mode does not skip this. A corrected assumption means re-show a shorter card. In plan mode, additionally put the same card and a spec outline in the plan file before calling ExitPlanMode; the chat card is what the user reads, ExitPlanMode is only the approval action, not a substitute for showing the card.
6. **After ok, write in this order:** instruction, spec, MAP row. For `updates`: edit the existing spec in place, bump `date`, add a Change log line. If this requirement came from a `prd-to-requirements` Google Sheet (check `docs/specs/prd-tracking-sheet.txt`), also update that row: `status: Spec Written`, `spec` = this MAP id.
7. **Run the check** (prints nothing when fine):

   ```bash
   cd docs/specs && find instructions specs -name '*.md' | xargs -I{} sh -c 'grep -q "{}" MAP.md || echo "NOT IN MAP: {}"'
   ```

## Templates

**Instruction** (frontmatter `id, title, date, status: frozen`): Original request (user's words verbatim) · Assumptions & corrections (as confirmed, clean English) · Agreed scope · Out of scope.

**Spec** (frontmatter `id, title, status: draft, date, instruction: <path>`): Summary · Problem & Goal · Scope · Analysis (impact on existing, assumptions, risks) · Design (approach, components, data flow) · Acceptance & Test criteria · Deploy notes · Open questions. Do not repeat the instruction's assumptions.

## Red Flags

| Thought | Reality |
|---|---|
| "I'll ask a few questions first" | Card first. Assume, list it, let the user correct. |
| "Small change, skip the card" | Every requirement gets the card. It is the user's decision point. |
| "I'll write the spec while they read" | Nothing is written before ok. |
| "Write a Bangla spec too" | One English spec only. Bangla costs tokens and was dropped. |

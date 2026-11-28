---
name: project-sheet
description: Use when the user wants one Google Sheet to manage a project (background, tech stack, summary, requirements, code review, decisions, risks, changelog), or asks to create, update or read that project sheet.
---

# Project Sheet

**Language:** every reply, question and card is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English. Sheet cell text is in English unless the user asks otherwise.

## Overview

One Google Sheet per project is the single place for project management. Its tabs and format come from `sheet-format` (`layout.json`):

| Tab | Holds | Written by |
|---|---|---|
| `Overview` | Project, client, background, goal, summary, current status, links, last updated, AI context | this skill |
| `Tech Stack` | Layer, technology, version, why, notes | this skill |
| `Requirements` | ID, title, summary, source, priority, status, spec | `prd-to-requirements`, `requirement-to-spec`, `parallel-build` |
| `Code Review` | Review findings | `code-review` |
| `Decisions` | Date, decision, reason, decided by, related spec | this skill, any skill on a decision the user makes |
| `Risks` | Item, impact, owner, plan, status | this skill |
| `Changelog` | Date, change, spec/PR, by | `requirement-to-spec`, `parallel-build`, this skill |

## Sheet link

- The link lives in `docs/specs/project-sheet.txt` (one line: the Google Sheet URL).
- If only the old `docs/specs/prd-tracking-sheet.txt` exists, use that sheet as the project sheet: copy its URL to `project-sheet.txt` and keep the old file (other tools may still read it). If its requirements tab is not named `Requirements`, rename it with `updateSheetProperties` after the user's ok.
- Every skill that writes to the sheet reads `project-sheet.txt` first, then `prd-tracking-sheet.txt`.

## Steps: init

1. **Study the project, do not ask first.** Read README, `package.json` / `composer.json` / `pyproject.toml` / `pubspec.yaml`, config files (skip lock files), `docs/specs/MAP.md` and recent git log. Fill Overview and Tech Stack from what you find.
2. **Show a Bangla card, then stop. Nothing is written before "ok":**

   ```
   Project sheet: <project name>
   Background: ...
   Goal: ...
   Summary: ...
   Tech stack: Frontend <...> · Backend <...> · DB <...>
   AI context: <৩-৫ লাইন, নিচের নিয়মে>
   ধরে নিয়েছি: ...
   জানা নেই: client, design link (খালি থাকবে)
   ```

   A correction means re-showing a shorter card.
3. **After "ok", create the sheet** with `sheet-format` (all tabs, new spreadsheet), titled `<Project> - Project Sheet`. Write Overview values in column B next to the labels, Tech Stack rows, and one Changelog row `Project sheet created`. Existing requirements from `prd-tracking-sheet.txt` stay where they are.
4. **Save the link** to `docs/specs/project-sheet.txt` and show it to the user.

## Steps: update

1. Read the sheet (`get_values` on `Overview!A:B` and `'Tech Stack'!A:E`) and compare with the repo now.
2. Show only the differences as a short Bangla card; wait for "ok".
3. Update changed cells in place, set `Last updated`, add one Changelog row. Never delete rows; a removed technology gets a note in `Notes`.
4. If the format looks broken (plain header, no dropdowns), repair it with `sheet-format` step 2.

## AI context

The `AI context` cell is a short prompt any agent reads before working on this project: what the product is, who uses it, the stack, the main conventions, and what not to touch. Plain sentences, 3 to 5 lines, no secrets.

Other skills (`requirement-to-spec`, `code-review`, `parallel-build`, `design`) read `Overview!B2:B11` once at the start when `project-sheet.txt` exists, so the user does not have to explain the project again. Reading is silent; do not show it to the user.

## Red Flags

| Thought | Reality |
|---|---|
| "Ask the user for the background first" | Study the repo, fill a draft, let the user correct the card. |
| "Write it now, they can edit later" | Card first, nothing written before "ok". |
| "A separate sheet for code review" | One project sheet, one tab per kind of record. |
| "Put the API keys in AI context" | Never put secrets in the sheet. |
| "Format the tabs by hand" | `sheet-format` builds every tab the same way. |

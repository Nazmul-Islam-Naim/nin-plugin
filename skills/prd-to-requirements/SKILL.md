---
name: prd-to-requirements
description: Use when a client hands over a PRD or BRD (a document bundling many features/requirements at once) and it needs to be broken into a tracked list of discrete requirements before any one of them goes through requirement-to-spec.
---

# PRD To Requirements

## Overview

A PRD/BRD is one document describing many requirements at once. This skill only extracts and tracks them in a Google Sheet — it never writes an instruction/spec and never starts building. One confirmed row later feeds `requirement-to-spec` on its own, one at a time, whenever the user is ready — extraction and spec-writing are separate decisions, never chained automatically.

**Language:** every reply, question and card is in Bangla script (বাংলা অক্ষর), never Banglish, even if the user writes in Banglish. Keep technical terms in English.

## Tracking sheet

- **Look first.** Check for `docs/specs/prd-tracking-sheet.txt` in the project (one line: the Google Sheet URL). If it exists, use that sheet.
- **If it doesn't exist,** ask the user once for the Sheet URL/ID, then write it to `docs/specs/prd-tracking-sheet.txt` so later runs never ask again.
- Read/write the sheet with whatever Google Sheets tool is available in the session (a Sheets MCP connector, or the `google-workspace` skill if loaded). Don't hardcode a specific tool name — use what's available.
- If the sheet has no header row yet, add one: `id | title | summary | source | priority | status | spec`.
- `status` is one of `Extracted / Confirmed / Rejected / Spec Written`. `spec` stays empty until `requirement-to-spec` fills it in with a MAP id.

## Steps

1. **Read the whole document first**, don't skim. Note its structure (goals, personas, feature list, out-of-scope) so nothing gets missed.
2. **Extract atomic requirements.** One row per independently buildable feature/change — split a bundled "user management with roles and invites" bullet into separate rows if they are independently shippable. Keep the source section/page so it can be traced back.
3. **Assume, don't ask,** for anything the PRD leaves vague (same spirit as `requirement-to-spec`); list assumptions under "ধরে নিয়েছি" in the card below. Ask (max 2 short questions) only when a gap changes which rows exist, not their detail.
4. **Show a Bangla card, then stop — nothing is written before "ok":**

   ```
   PRD/BRD: <file/link>
   পাওয়া গেছে: <n>টা requirement
   ১. <title> — <১ লাইন সারাংশ>
   ২. ...
   ধরে নিয়েছি: ...
   ```

   A correction means re-showing a shorter card, same as `requirement-to-spec`.
5. **After "ok", write the rows** with `status: Extracted`, then `Confirmed` for the ones the user kept. Never mark a row `Spec Written` yourself — only `requirement-to-spec` does that, after it actually runs for that row.
6. **Stop here.** Do not invoke `requirement-to-spec` for any row automatically. When the user later asks for one item, hand its title/summary/source to `requirement-to-spec` and let that skill run its own card-and-gate flow.
7. **Keep the sheet current.** A later PRD revision or a new client doc updates existing rows (by matching title/source) rather than duplicating them; removed scope gets `status: Rejected`, not deleted, so history survives.

## Red Flags

| Thought | Reality |
|---|---|
| "Turn this into one big spec" | One PRD becomes many rows, each its own future spec — bundling defeats tracking. |
| "Run requirement-to-spec for all of them now" | The user decides pace, one at a time. Extraction and spec-writing are separate gates. |
| "Delete the row, scope dropped" | Mark it Rejected. The sheet is the history of what the client asked for. |
| "Skip the card, the list is obvious" | The card is the confirmation point — always show it. |

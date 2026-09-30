---
name: data-dictionary
description: Use when a requirement or spec implies new or changed data (entities, fields, relationships) and it needs to be captured as a data dictionary — the single source of truth that migrations and models are generated from.
---

# Data Dictionary

**Language:** every reply and card is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Overview

One canonical file, `docs/specs/data-dictionary.md`, one Markdown table per entity. Every backend feature that touches new or changed data reads this file before writing a migration or model — it is the only place field names, types and relationships get decided, so two features never disagree about what a column is called.

## Steps

1. **Source.** Read the feature's spec (`docs/specs/specs/<id>.md`, Design section) if it exists; else the raw requirement, or the row's summary if it came from `prd-to-requirements`.
2. **One table per entity:**

   ```
   ## Order
   | field | type | nullable | unique | default | references | notes |
   |---|---|---|---|---|---|---|
   | id | bigint | no | yes (pk) | — | — | |
   | user_id | bigint | no | no | — | User.id | |
   | status | enum(pending,paid,shipped) | no | no | pending | — | |
   ```

3. **Look first, then diff.** If the entity already exists in the dictionary, add or change only the fields this requirement actually touches — never silently rewrite the whole table. Note which spec id added or changed a field, so the history stays traceable.
4. **Show a short Bangla card** before writing (new entity / changed fields / nothing new), same gate as `requirement-to-spec` and `prd-to-requirements`. Nothing is written before "ok."
5. **Hand off to structure.** `laravel-structure`/`fastapi-structure` read this file to generate or update the model and migration. This skill never writes a migration file itself — only the dictionary.

## Red Flags

| Thought | Reality |
|---|---|
| "Decide the column name while writing the migration" | Decide it here first, once — otherwise the model, the API and the DB drift apart. |
| "Overwrite the entity's whole table for one new field" | Diff and append; keep the trace of what changed and when. |
| "Skip the dictionary for a 'quick' field" | Quick fields are exactly the ones that get forgotten and re-added differently later. |

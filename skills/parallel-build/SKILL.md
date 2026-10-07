---
name: parallel-build
description: Use when a spec in docs/specs/specs/ is ready to implement and the work spans more than one layer (frontend, backend, mobile) that can be built in parallel by subagents.
---

# Parallel Build

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Overview

Take a finished spec from `requirement-to-spec` and build it with one subagent per layer, running at the same time. Parallel work only stays safe if all agents share one **API contract**, so the contract is written first, then agents run side by side, each inside its own directory.

Agents: `frontend-builder`, `backend-builder`, `mobile-builder`.

## Steps

1. **Read, read-only.** Open `docs/specs/specs/NNN-*.md` (latest in `MAP.md` if no number given), plus `docs/specs/data-dictionary.md` if present. Find each layer's root directory in the repo (web app, API, mobile app). Decide which layers the spec touches.
2. **Show the card, in Bangla, then wait for "ok":**

   ```
   Spec: NNN — title
   Build হবে: frontend / backend / mobile (যেগুলো লাগবে)
   বাদ: যে layer লাগবে না — ১ লাইনে কারণ
   Directory: layer → path
   ধরে নিয়েছি: ২-৩টা ছোট পয়েন্ট
   ```
3. **Contract first (the only sequential step).** Write `docs/specs/contracts/NNN-slug.md`: endpoints, request/response shapes, auth, error format, status codes. Reuse the spec's design section and `data-dictionary`. Skip only if the spec touches a single layer.
4. **Dispatch in parallel.** In ONE message, launch every needed agent with the Agent tool. Each prompt carries: spec path, contract path, its layer root, and "edit only inside your layer root; do not touch the contract — report deviations instead".
5. **Reconcile.** Check each agent's summary against the contract (field names, status codes, auth). List mismatches by severity and fix or re-dispatch. Then set the spec's `status: built` and add a `built` note to its MAP row. If `docs/specs/prd-tracking-sheet.txt` exists, also update the sheet row whose `spec` column equals this MAP id: `status: Built`. Skip this if no sheet file exists or mismatches are still open.

## Red Flags

| Thought | Reality |
|---|---|
| "Start agents now, write the contract later" | Without the contract they invent different field names. Contract first. |
| "Run the agents one by one" | The point is parallel: one message, all Agent calls. |
| "Let backend also fix the frontend call" | Each agent stays in its own layer root. Report, don't cross over. |
| "Spec only touches the web, still use all three" | Dispatch only the layers the spec needs. |

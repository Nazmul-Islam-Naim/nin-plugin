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
3. **Contract first (the only sequential step).** Write `docs/specs/contracts/NNN-slug.md`: first line `version: 1`, then endpoints (a table: method, path, auth, request, response, status codes), error format, and a final `## Changelog` section (`v1: initial`). Reuse the spec's design section and `data-dictionary`. Skip only if the spec touches a single layer.
4. **Dispatch in parallel.** In ONE message, launch every needed agent with the Agent tool. Each prompt carries: spec path, contract path and current contract version, its layer root, the spec's Acceptance criteria, and "edit only inside your layer root; do not touch the contract — report deviations instead; finish with your Done checklist (review, tests, acceptance table, contract check)".
5. **Reconcile and verify (do not trust summaries).**
   1. Read each agent's report: the Contract check table, the Acceptance table, `CONTRACT-CHANGE` and `BLOCKED` items.
   2. Verify yourself: grep each layer's code for the contract's paths, methods, field names and status codes. List mismatches by severity.
   3. Send a mismatch back to the same agent with `SendMessage` (it keeps its context). Max 2 rounds per agent; after that, mark it `BLOCKED` and carry it to the approval card.
   4. `CONTRACT-CHANGE` from an agent: you apply it to the contract, bump `version`, add a Changelog line (what, which endpoint, breaking or not), then re-sync (see below).
   5. **Integrate.** If backend and frontend/mobile were both built, start the backend, swap each client from the mock layer to the real API (the one place `mock-data-layer` set up) and smoke-check one real call per endpoint. Report what could not run.
6. **Human approval gate.** Show a Bangla card and wait for "ok":

   ```
   Spec: NNN — title (contract vN)
   Layer ফলাফল: layer → done / BLOCKED, test pass/fail
   Acceptance: কয়টি pass, কয়টি fail (fail-গুলোর তালিকা)
   খোলা mismatch / BLOCKED: প্রতিটি ১ লাইনে
   ```

   Without "ok", the spec stays unbuilt. Agents never approve their own work.
7. **Manual test.** If the frontend was built, invoke `manual-test` for this spec. Then set the spec's `status: built` and add a `built` note to its MAP row. If `docs/specs/prd-tracking-sheet.txt` exists, also update the sheet row whose `spec` column equals this MAP id: `status: Built`. Skip this if no sheet file exists, mismatches are still open, or approval was not given.

## Re-sync (backend or contract changed later)

Used by `/sync-contract` and by step 5.4.
1. Read the contract's `version` and Changelog. Each frontend/mobile layer records `contract: NNN-slug vN` in a one-line comment beside its API client; a missing or older value means that layer is stale.
2. Dispatch only the stale layers (parallel, one message). The prompt says: "contract moved vX to vN; read the Changelog entries since vX; fix breaking changes in types and mocks first, then the screens; update your `contract:` comment."
3. Run steps 5 and 6 again for those layers only.

## Red Flags

| Thought | Reality |
|---|---|
| "Start agents now, write the contract later" | Without the contract they invent different field names. Contract first. |
| "Run the agents one by one" | The point is parallel: one message, all Agent calls. |
| "Let backend also fix the frontend call" | Each agent stays in its own layer root. Report, don't cross over. |
| "Spec only touches the web, still use all three" | Dispatch only the layers the spec needs. |
| "The agent said it matches the contract" | Grep the code yourself. Summaries can be wrong. |
| "Agent keeps failing, try a third time" | Two rounds, then BLOCKED to the human. |
| "Mark built, tests passed" | Only after the human says "ok" at the gate. |

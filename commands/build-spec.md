---
description: Build a finished spec in parallel with frontend, backend and mobile subagents.
argument-hint: <spec number or slug, e.g. 003>
---

Task: $ARGUMENTS

If the task is empty, use the latest spec in `docs/specs/MAP.md`.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Run these steps in order. For each step, invoke the named skill and follow it. Do not restate its rules.

1. **Build in parallel.** Invoke `parallel-build`: confirm the layers with the user, write the API contract, run `frontend-builder`, `backend-builder` and `mobile-builder` together as needed, then reconcile their results against the contract, then update the MAP and the PRD tracking sheet row.

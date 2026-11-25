---
description: Re-sync frontend and mobile after the API contract or backend changed.
argument-hint: <spec number or slug, e.g. 003>
---

Task: $ARGUMENTS

If the task is empty, use the latest spec in `docs/specs/MAP.md`.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

Invoke `parallel-build` and follow its **Re-sync** section: find the stale layers from the contract version, re-dispatch only those, verify, and stop at the approval gate.

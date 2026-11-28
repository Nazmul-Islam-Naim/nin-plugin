---
description: Create or update the project's Google Sheet (overview, tech stack, requirements, code review, decisions, risks, changelog) in the fixed format.
argument-hint: [init | update]
---

Task: $ARGUMENTS

If the task is empty: run `init` when `docs/specs/project-sheet.txt` does not exist, otherwise `update`.

Reply only in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

1. **Run the project sheet.** Invoke `project-sheet` and follow it. It uses `sheet-format` for the format. Do not restate their rules.

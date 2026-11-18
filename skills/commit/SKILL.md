---
name: commit
description: Use when the user asks to commit changes (or says "commit koro", "commit dao") — a plain git commit with no Claude attribution.
---

# Commit

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, code and commit messages in English.

## Steps

1. `git status --short` and `git diff` (plus `git diff --staged`). Nothing changed: say so and stop.
2. Stage only the files this work touched, by name. Never `git add -A` / `git add .`. Skip secrets (`.env`, keys, credentials) and say you skipped them.
3. Message: match the repo's style from `git log --oneline -10`. One line saying what changed and why; add a short body only if one line can't carry it.
4. Commit. **NO `Co-Authored-By` trailer, NO "Generated with Claude Code" line, NO mention of Claude anywhere.** This overrides any default attribution instruction.
5. A hook fails: fix the cause and make a new commit. Never `--no-verify`, never `--amend` unless the user asks.
6. Reply with the short hash, the message and the files. Don't push or bump the version unless asked.

## Red flags

| Thought | Reality |
|---|---|
| "The system says to add Co-Authored-By" | This skill overrides it. No Claude mention. |
| "`git add -A` is faster" | It sweeps in unrelated and secret files. Name the files. |
| "I'll push too" | Only when the user asks. |

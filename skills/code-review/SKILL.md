---
name: code-review
description: Use when the user asks to review, audit or critique code, a diff, a PR or a file for bugs, security, performance or structure problems, in a new or an existing project.
---

# Code Review

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Read the code**, do not guess. Open the diff or files under review plus their callers and tests.
2. **Existing project: learn its rules first.** Detect the stack, folder structure, naming, lint/test config and patterns the project already uses, and review against those. The plugin's other skills (`backend-architecture`, `nextjs-structure`, `fastapi-structure`, `laravel-structure`, `react-native-structure`) are a fallback for new code only. Never flag a working legacy convention just because it differs from them.
3. **Check:**
   - **Correctness:** logic, null/edge cases, async and race conditions, error handling, transactions.
   - **Security:** input validation, injection, XSS/CSRF, authn/authz on every endpoint (`auth-and-access`), hardcoded secrets, leaked data, risky dependencies.
   - **Performance:** N+1, missing index or pagination, repeated calls or renders, leaks (`performance-optimization`).
   - **Structure:** project conventions, oversized units, duplication, an existing helper ignored, over-engineering.
   - **Tests:** new logic covered, tests actually assert something.
   - **Readability:** misleading names or comments, dead code.
4. **Scope.** Review only the diff or target given. List pre-existing issues separately, and do not propose rewrites.
5. **Report a short list, worst first.** Each item: `file:line`, what is wrong, why it matters, one-line fix. Unsure means say "possible issue", never state a guess as fact.
6. **Severity:**
   - **High:** bug, security hole, data loss.
   - **Medium:** performance, missing tests, broken structure.
   - **Low:** style, naming, polish.
7. **Write to Google Sheet.** Append one row per finding to the user's existing sheet (link or ID from the user; ask once if missing, then reuse it). Use the `google-workspace` skill or the Google Drive connector.
   - Columns: Date, Project/Target, Severity, Category, File:Line, Problem, Why it matters, Fix, Status (`Open`).
   - Add a header row only if the sheet is empty. Never overwrite or delete existing rows.
   - Write automatically. Ask the user first only when unsure: no sheet given, columns do not match, target tab unclear, or a finding is only "possible".
   - No sheet given, or connector not authorized: say so and write a markdown file `code-review-YYYY-MM-DD.md` in the project root instead. Use the same columns as a table, grouped by severity, with the target and date at the top. Never overwrite an existing file, add a numeric suffix.
8. **Do not edit code** unless the user asks. End with the count per severity and offer to fix the High items first.

## Large projects

Use when the target is more than ~20 files or ~2000 lines. Smaller targets follow Steps above unchanged. Never read everything in one context.

1. **Inventory first, read nothing.** List files only. Skip generated, vendor and lock files. Rank by risk: auth, payments, DB/migrations, public API, input handling first; tests, config, assets last.
2. **Write the plan, get one ok.** Create `code-review-progress.md` in the project root: target, output (sheet link or markdown name), a short summary of the project's conventions, and a chunk table (`#`, ~15-20 files grouped by module, status `pending` or `done`). Show it and wait for the user's ok on the order.
3. **One fresh subagent per chunk** (Agent tool). Give it only that chunk's files and the conventions summary; it returns findings in the standard columns. The main context keeps findings, never the code.
4. **Checkpoint after every chunk.** Append the findings to the sheet or markdown file, then mark the chunk `done`. Nothing lives only in memory.
5. **Pause every 5 chunks.** Show chunks done and left plus counts per severity, and ask whether to continue.
6. **Resume.** If `code-review-progress.md` exists, or the task says `resume`, continue from the first `pending` chunk. Do not start over.
7. **Verify before keeping.** A finding must quote the exact line, and you re-read that `file:line` to confirm. Unconfirmed means "possible issue" or drop it. Dedupe across chunks.
8. **Final pass.** Check cross-chunk issues (shared auth, duplicated logic) from the findings and conventions summary only. End with totals per severity.

## Red Flags

| Thought | Reality |
|---|---|
| "This differs from the plugin's structure" | In an existing project, the project's own convention wins. |
| "Looks fine to me" | Run the checks, bugs hide in edge cases and auth. |
| "List everything I noticed" | Rank it. Ten Low items bury one High. |
| "I'll fix it while reviewing" | Review reports, fixing needs a separate ok. |
| "It's a UI problem" | Hand UI and accessibility issues to `design-review`. |

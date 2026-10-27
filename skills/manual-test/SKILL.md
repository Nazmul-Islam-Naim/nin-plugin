---
name: manual-test
description: Use when a feature with a web UI has just been built (after parallel-build, or inside an issue-queue agent) and needs manual testing in a real browser against its spec's acceptance criteria, using the Playwright MCP.
---

# Manual Test

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Overview

Tests and lint prove the code compiles; they do not prove a user can finish the flow. After a build, open the real app in a browser through the Playwright MCP, walk every acceptance criterion like a user would, fix what breaks, and write a report.

Skip in one line if the spec has no web UI (backend-only or mobile-only). React Native is not covered.

## Steps

0. **Connection.** If Playwright tools (`browser_navigate`, `browser_snapshot`, ...) are available, go to step 1. Otherwise set it up yourself:
   - `node -v`. Below 18 or missing: tell the user to install Node 18+ and stop. Do not install Node yourself.
   - `npx playwright install chromium` (installs the browser if missing).
   - `claude mcp list`. No `playwright` entry: `claude mcp add --scope user playwright -- npx @playwright/mcp@latest --isolated`.
   - Entry listed but failed: read its error and tell the user the cause (usually Node or browser missing).
   - A newly added server does not appear as tools in the running session. Tell the user: reconnect from `/mcp` or restart Claude Code, then run `/nin-plugin:manual-test NNN` again, and stop. In a background agent, end with `BLOCKED: Playwright MCP connect হয়নি, /mcp reconnect বা restart দরকার`.
1. **Cases.** Read `docs/specs/specs/NNN-*.md` (latest in `MAP.md` if no number given). Turn its "Acceptance & Test criteria" into a numbered case list: the happy path, plus empty, invalid input and error states, plus one phone-width check (375px).
2. **Run the app.** Find the web app root and its dev script. Start it in the background on a free port (parallel worktrees collide on the default port) and wait until the URL responds. Use only `localhost`.
3. **Test each case.** `browser_navigate` → `browser_snapshot` → act with `browser_click`, `browser_type`, `browser_fill_form`, `browser_select_option` → `browser_snapshot` again and check the expected text, element or URL. After each case check `browser_console_messages` and `browser_network_requests` for errors and failed requests. Use `browser_resize` for the phone check. On failure take `browser_take_screenshot`.
4. **Fix.** For a failing case find the root cause in the code, fix it, rerun that case. After 2 failed attempts, leave it failing and record why.
5. **Report.** Write `docs/specs/test-reports/NNN-slug.md`: spec link, date, app URL, then `| # | case | steps | expected | result | note |` with result `pass` or `fail`, then the fixes made. Close the browser (`browser_close`) and stop the dev server.
6. **Summary.** Reply with pass/fail counts, fixes made, open failures and the report path.

## Red Flags

| Thought | Reality |
|---|---|
| "The screenshot looks fine, pass" | Every case needs a concrete check in the snapshot: text, element, URL or state. |
| "The frontend is on mock data, nothing to test" | The flow, validation and states still run. Test them. |
| "Playwright isn't connected, skip testing" | Connecting is this skill's job. Do step 0, then ask for the reconnect. |
| "Fix it in the test, not the code" | You change app code for a real bug. A case is wrong only if the spec says otherwise. |
| "Leave the dev server running" | Stop it. Background servers pile up across worktrees. |
| "Backend-only spec, open the browser anyway" | No UI, no browser test. Say so in one line. |

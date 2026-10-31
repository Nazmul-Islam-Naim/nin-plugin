# nin-plugin

Claude Code plugin that turns a requirement into a spec, and designs, builds and reviews frontend UI (React + Tailwind, Next.js) for any type of site. Replies come in Bangla script: skills and commands carry the rule, and a SessionStart hook (`hooks/hooks.json`) enforces it plugin-wide.

## Install

```
/plugin marketplace add Nazmul-Islam-Naim/nin-plugin
/plugin install nin-plugin@nin-plugin
/reload-plugins
```

## Use

One command runs the whole design flow:

```
/nin-plugin:design a live cricket score site like Cricbuzz
/nin-plugin:design an online shop for handmade bags
/nin-plugin:design a dashboard to ask questions about my CSV data
```

It runs in four steps: (1) infers the business and shows 2-3 design directions with samples, then **waits for your pick**; (2) fixes the page list and design tokens; (3) builds the screens with mock data; (4) reviews the result and fixes the important problems.

Any skill also runs alone, for example `/nin-plugin:design-review`.

For a Laravel or FastAPI backend feature, chain `backend-architecture` with the framework-specific skill in one go:

```
/nin-plugin:laravel-backend an order checkout module
/nin-plugin:fastapi-backend a user registration endpoint
```

For a React Native screen or feature, backed by the same API:

```
/nin-plugin:react-native-app a login screen with email and password
```

Once a spec is written, build all its layers at the same time with subagents (`frontend-builder`, `backend-builder`, `mobile-builder`). The API contract is written first so they stay in sync:

```
/nin-plugin:build-spec 003
```

After a build with a web UI, the agent opens the app in a real browser through the bundled Playwright MCP, walks the spec's acceptance criteria, fixes what breaks and writes `docs/specs/test-reports/NNN-slug.md`. It runs automatically at the end of `build-spec` and `issue-queue`, or alone. Needs Node 18+; if the MCP is not connected, the skill sets it up and asks you to reconnect from `/mcp`:

```
/nin-plugin:manual-test 003
```

For a stack of assigned GitHub issues, talk them through one at a time while background agents build each one in its own git worktree, review it and open a PR (`Closes #N`, no Claude attribution). Needs `gh` logged in; worktree cleanup after merge is manual:

```
/nin-plugin:issue-queue
/nin-plugin:issue-queue 42
```

## Other tools (OpenCode, Codex CLI, Gemini CLI)

The skills use the shared `SKILL.md` format, so they work outside Claude Code too. `scripts/export.js` converts the commands and agents and installs everything (Node 18+):

```
node scripts/export.js opencode --global   # ~/.config/opencode: skill/, command/, agent/, AGENTS.md
node scripts/export.js codex --global      # ~/.codex: skills/, prompts/, AGENTS.md
node scripts/export.js gemini --global     # ~/.gemini: skills/, commands/*.toml, GEMINI.md
node scripts/export.js all                 # no --global: stage into ./dist/<tool>/ to inspect first
```

Gemini CLI can also install the repo directly as an extension (`gemini-extension.json` + `GEMINI.md`, includes the Playwright MCP):

```
gemini extensions install https://github.com/Nazmul-Islam-Naim/nin-plugin
```

What carries over and what does not:

- Skills and commands: yes. Commands run as `/design`, `/build-spec` etc. (no `nin-plugin:` prefix); Gemini uses `{{args}}`, converted automatically.
- Bangla reply rule: the SessionStart hook becomes a marked block in `AGENTS.md` / `GEMINI.md` (`targets/rules.md`), so re-running the export updates it in place.
- Subagents: OpenCode gets `frontend-builder`, `backend-builder`, `mobile-builder` as `mode: subagent` agents. Codex and Gemini have none, so `parallel-build` and `issue-queue` fall back to doing the layers one after another (see "Tool mapping" in `targets/rules.md`).
- The `conversation` prompt hook is Claude-only; the rules file tells the other tools to load that skill for questions.
- Playwright MCP for `manual-test`: the export prints the config snippet to add (`opencode.json`, `~/.codex/config.toml`); the Gemini extension already includes it.

## Skills

| Skill | Use it to |
|---|---|
| `prd-to-requirements` | Break a client PRD/BRD into a tracked list of requirements in a Google Sheet |
| `report-study` | Read any report, summarise it, give a workplan and help based on it (`/nin-plugin:study-report <file>`) |
| `requirement-to-spec` | Turn a requirement into a frozen instruction file and an English spec |
| `parallel-build` | Build a finished spec with frontend, backend and mobile subagents in parallel, sharing one API contract |
| `manual-test` | Test a built web UI in a real browser with the Playwright MCP, fix failures, write a report (`/nin-plugin:manual-test <spec>`) |
| `issue-queue` | Take assigned GitHub issues one by one, spec each, and hand it to a background agent that builds, reviews and opens a PR (`/nin-plugin:issue-queue`) |
| `business-design` | Pick a design direction that fits the business, with samples, before coding |
| `site-patterns` | Decide pages and sections for a site type (shop, social, sports, brand, news, dashboard, portfolio) |
| `design-tokens` | Set colors, fonts, spacing and dark mode as tokens |
| `ui-design-guidelines` | Layout, spacing, typography, contrast and accessibility rules |
| `component-build` | Build one component with all its states |
| `responsive-design` | Make it work from phone to desktop |
| `data-dense-ui` | Feeds, tables, filters, live tiles and charts |
| `mock-data-layer` | Run the frontend without a backend, swap the real API later |
| `visual-polish` | Typography, imagery, depth and motion for a finished look |
| `performance-optimization` | Images, fonts, code-splitting, bundle size and re-renders |
| `design-review` | Review a UI and list problems by severity |
| `conversation` | Friendly chat for questions and ideas (auto-suggested by a prompt hook); can turn the outcome into a requirement |
| `code-review` | Review code (new or existing project) by severity and log findings to a Google Sheet (or a markdown file) |
| `nextjs-structure` | Next.js folder structure, including Redux Toolkit setups |
| `backend-architecture` | SOLID, framework-agnostic layered backend structure |
| `laravel-structure` | Apply `backend-architecture` to Laravel using the L5 modular pattern |
| `fastapi-structure` | Apply `backend-architecture` to FastAPI using routers, Pydantic and `Depends()` |
| `mobile-architecture` | SOLID, framework-agnostic layered structure for a mobile app |
| `react-native-structure` | Apply `mobile-architecture` to React Native (navigation, state, API client) |
| `react-native-ui` | Tokens, component states, responsive/accessible layout and polish for React Native |
| `auth-and-access` | Login/registration and role-based access control across backend, web and mobile |
| `data-dictionary` | Capture entities and fields as the source of truth for migrations and models |

## Notes

- `backend-architecture` covers structure only (layers, SOLID). `data-dictionary` covers schema/migrations and `auth-and-access` covers login and role-based access; infra-specific setup (hosting, CI/CD) is not included yet. The frontend runs on mock data until a real backend is wired up.
- Mobile support currently covers React Native only. Flutter is not included yet.
- Structure and patterns of big sites can inspire a design. Do not copy their brand, logo or images.

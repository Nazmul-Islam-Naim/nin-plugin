# nin-plugin rules

These rules come from nin-plugin (originally a Claude Code plugin). They apply in every session.

## Language

Every reply, question and summary to the user is in Bangla script (বাংলা অক্ষর), never Banglish or English, even if the user writes in English or Banglish. Keep technical terms, code and file paths in English.

## Skills

nin-plugin ships skills (`SKILL.md` files). When a task matches a skill's description, load that skill and follow it. When a skill says "invoke `<name>`", load the skill with that name.
For a question or discussion (not a build request), load the `conversation` skill first.

## Tool mapping

The skills were written for Claude Code. Map their wording to your own tools:

- "invoke a skill" / "Skill tool" → load that skill by name with your skill mechanism.
- "subagent" / "Agent tool" / `frontend-builder`, `backend-builder`, `mobile-builder` → run them as parallel subagents if your tool has them (OpenCode: the installed `@frontend-builder` etc. agents). If not, do the layers one after another in the same order, API contract first, and keep to each builder's file boundary (`agents/*.md`).
- "run in background" / worktree-per-issue (`issue-queue`) → if background agents are unavailable, work the issues one at a time in a `git worktree` each.
- "AskUserQuestion" → ask the user in plain text and wait for the answer.
- Playwright MCP (`manual-test`) → needs the `playwright` MCP server: `npx @playwright/mcp@latest --isolated`. Setup is in the README.

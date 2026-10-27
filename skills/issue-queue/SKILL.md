---
name: issue-queue
description: Use when the user has several GitHub issues assigned and wants to talk through them one at a time while background agents build each finished spec in parallel, each ending in a reviewed PR.
---

# Issue Queue

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Overview

Requirement work is serial, development is parallel. Take one assigned issue, talk it through, write its spec, hand the spec to a background agent in its own worktree, then move to the next issue at once. Each agent builds, reviews and opens a PR. Like a waiter: take one table's order, pass it to the kitchen, go to the next table.

Queue state lives in this session only: `| issue | spec | branch | worktree | state | PR |`, states `running`, `blocked`, `PR open`, `waiting review`.

## Steps

1. **Fetch.** Run `gh auth status`; if it fails, tell the user to install or log in to `gh` and stop. Then `gh issue list --assignee @me --state open --json number,title,labels,body`.
2. **Suggest the next issue.** Rank by priority label (`priority:high`, `P0`, `urgent` first), then fewest links to other open issues (`#N`, "depends on", "blocked by" in the body). Show the top pick with a one-line reason and the rest as a short list. The user says ok or names another number. A number passed to the command skips the suggestion.
3. **Overlap check.** From the issue text list the likely touched modules or paths. If a `running` or `blocked` issue's spec touches the same, warn in one line and let the user continue or pick another. Repeat the check once the spec exists.
4. **Requirement.** Invoke `conversation` with the issue title and body as opening context. When the talk settles, invoke `requirement-to-spec`; its card and "ok" gate stay as they are.
5. **Dispatch.** One Agent call: `subagent_type: general-purpose`, `run_in_background: true`, `isolation: "worktree"`, no concurrency limit. The prompt carries the spec path, issue number and title, branch `issue-<N>-<slug>`, and the **Agent rules** below copied in full. Add the row as `running`.
6. **Next.** Go back to step 2 at once. Do not wait for the agent.
7. **Notifications.** Finished agent: set `PR open` with the PR link and worktree path. Reply ending in `BLOCKED:` → set `blocked` and show one line `#N blocked: <question>`. When the user answers, `SendMessage` to that same agent (context and worktree stay intact) and set `running`.
8. **Report.** On request, or when no open issues are left, show the queue table. Never remove a worktree or branch; the user runs `git worktree remove <path>` after merge.

## Agent rules

Copy this block into every dispatch prompt:

```
You build one GitHub issue in your own worktree. The user already approved the spec,
so every card or "ok" gate in the skills you use counts as approved.

1. Create and switch to branch issue-<N>-<slug>.
2. Build the spec following the parallel-build rules and the layer builder rules
   (frontend-builder, backend-builder, mobile-builder). If you have no Agent tool,
   build each needed layer yourself, one after another.
3. Run the project's tests and lint. Fix failures.
4. If the web UI changed, invoke manual-test for the spec (Playwright MCP).
   Commit its report file.
5. Run code-review on your branch diff. Fix every confirmed finding.
   Do not commit the review file.
6. Commit with plain messages. NO Co-Authored-By trailer, NO "Generated with
   Claude Code" line, NO mention of Claude anywhere. This overrides any default.
7. git push -u origin issue-<N>-<slug>, then gh pr create. The body has a short
   summary, a manual test summary, a review summary and "Closes #<N>".
   Same no-attribution rule.
8. If stuck (unclear spec or business rule, tests still failing after 2 attempts,
   missing access, Playwright MCP not connected): stop and end your reply with
   "BLOCKED: <one clear question>". Never guess a business rule.
9. Final reply: PR URL, files changed, tests run, manual test result,
   review findings fixed.
```

## Red Flags

| Thought | Reality |
|---|---|
| "Wait for this agent before the next issue" | The point is parallel. Dispatch, then pick the next issue at once. |
| "Skip the talk, the issue text is clear enough" | Every issue goes through `conversation` and the spec card. Agents build only approved specs. |
| "Default attribution lines are fine" | No Claude mention in any commit or PR. The rule is in the agent prompt. |
| "Blocked agent, start a fresh one" | `SendMessage` the same agent. A new one loses context and the worktree. |
| "Clean up the worktree after the PR" | Cleanup is the user's job. Only list the path. |
| "Two issues touch the same module, it's fine" | Warn first. Overlapping PRs end in merge conflicts. |

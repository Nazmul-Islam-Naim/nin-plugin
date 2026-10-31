#!/usr/bin/env node
// Exports nin-plugin skills, commands and agents to OpenCode, Codex CLI and Gemini CLI.
// Usage: node scripts/export.js <opencode|codex|gemini|all> [--global] [--out DIR]
//   default: writes to ./dist/<target>/ ; --global installs into the tool's home config.
const fs = require("fs");
const path = require("path");
const os = require("os");

const ROOT = path.join(__dirname, "..");
const args = process.argv.slice(2);
const target = args.find((a) => !a.startsWith("--")) || "all";
const global = args.includes("--global");
const outFlag = args.indexOf("--out");
const outBase = outFlag >= 0 ? path.resolve(args[outFlag + 1]) : path.join(ROOT, "dist");
const TARGETS = ["opencode", "codex", "gemini"];
if (target !== "all" && !TARGETS.includes(target)) {
  console.error("target must be one of: " + TARGETS.join(", ") + ", all");
  process.exit(1);
}

const home = os.homedir();
// Where each tool reads things from (global mode) or where we stage them (dist mode).
const dirs = (t) => {
  if (!global) {
    const b = path.join(outBase, t);
    return { skills: path.join(b, "skills"), commands: path.join(b, t === "codex" ? "prompts" : "commands"), agents: path.join(b, "agents"), rules: path.join(b, t === "gemini" ? "GEMINI.md" : "AGENTS.md") };
  }
  if (t === "opencode") { const b = path.join(process.env.XDG_CONFIG_HOME || path.join(home, ".config"), "opencode"); return { skills: path.join(b, "skill"), commands: path.join(b, "command"), agents: path.join(b, "agent"), rules: path.join(b, "AGENTS.md") }; }
  if (t === "codex") { const b = path.join(process.env.CODEX_HOME || path.join(home, ".codex"), ""); return { skills: path.join(b, "skills"), commands: path.join(b, "prompts"), agents: null, rules: path.join(b, "AGENTS.md") }; }
  const b = path.join(home, ".gemini");
  return { skills: path.join(b, "skills"), commands: path.join(b, "commands"), agents: null, rules: path.join(b, "GEMINI.md") };
};

const mkdir = (p) => fs.mkdirSync(p, { recursive: true });
const copyDir = (s, d) => fs.cpSync(s, d, { recursive: true });
const parse = (file) => {
  const t = fs.readFileSync(file, "utf8");
  const m = t.match(/^---\n([\s\S]*?)\n---\n?([\s\S]*)$/);
  const fm = {};
  if (m) for (const l of m[1].split("\n")) { const i = l.indexOf(":"); if (i > 0) fm[l.slice(0, i).trim()] = l.slice(i + 1).trim(); }
  return { fm, body: m ? m[2] : t };
};
const list = (dir, ext) => (fs.existsSync(dir) ? fs.readdirSync(dir).filter((f) => f.endsWith(ext)) : []);

const BEGIN = "<!-- nin-plugin:begin -->", END = "<!-- nin-plugin:end -->";
function writeRules(file) {
  const rules = fs.readFileSync(path.join(ROOT, "targets/rules.md"), "utf8").trim();
  const block = `${BEGIN}\n${rules}\n${END}\n`;
  mkdir(path.dirname(file));
  let cur = fs.existsSync(file) ? fs.readFileSync(file, "utf8") : "";
  const re = new RegExp(`${BEGIN}[\\s\\S]*?${END}\\n?`);
  cur = re.test(cur) ? cur.replace(re, block) : (cur ? cur.replace(/\n*$/, "\n\n") : "") + block;
  fs.writeFileSync(file, cur);
}

const toolsMap = { Read: "read", Write: "write", Edit: "edit", Glob: "glob", Grep: "grep", Bash: "bash", Skill: "skill" };

function exportTarget(t) {
  const d = dirs(t);
  // Skills: the SKILL.md format is the same on all three tools, so copy as-is.
  mkdir(d.skills);
  for (const s of fs.readdirSync(path.join(ROOT, "skills"))) copyDir(path.join(ROOT, "skills", s), path.join(d.skills, s));

  // Commands: each tool has its own prompt format.
  mkdir(d.commands);
  for (const f of list(path.join(ROOT, "commands"), ".md")) {
    const name = f.replace(/\.md$/, "");
    const { fm, body } = parse(path.join(ROOT, "commands", f));
    if (t === "gemini") {
      const prompt = body.trim().replace(/\$ARGUMENTS/g, "{{args}}");
      const toml = `description = ${JSON.stringify(fm.description || name)}\nprompt = '''\n${prompt.replace(/'''/g, "'' '")}\n'''\n`;
      fs.writeFileSync(path.join(d.commands, name + ".toml"), toml);
    } else {
      fs.copyFileSync(path.join(ROOT, "commands", f), path.join(d.commands, f)); // $ARGUMENTS works in both
    }
  }

  // Agents: only OpenCode has file-based subagents.
  if (t === "opencode" && d.agents) {
    mkdir(d.agents);
    for (const f of list(path.join(ROOT, "agents"), ".md")) {
      const { fm, body } = parse(path.join(ROOT, "agents", f));
      const tools = (fm.tools || "").split(",").map((x) => x.trim()).filter(Boolean);
      const toolYaml = tools.map((x) => `  ${toolsMap[x] || x.toLowerCase()}: true`).join("\n");
      fs.writeFileSync(path.join(d.agents, f), `---\ndescription: ${fm.description}\nmode: subagent\ntools:\n${toolYaml}\n---\n${body}`);
    }
  }

  writeRules(d.rules);

  // MCP: tool-specific config the user merges once (not overwritten automatically).
  const mcp = {
    opencode: ['// add to opencode.json under "mcp":', '{ "playwright": { "type": "local", "command": ["npx", "@playwright/mcp@latest", "--isolated"], "enabled": true } }'],
    codex: ["# add to ~/.codex/config.toml:", '[mcp_servers.playwright]\ncommand = "npx"\nargs = ["@playwright/mcp@latest", "--isolated"]'],
    gemini: ["# Not needed when installed as an extension (gemini-extension.json has it). Otherwise add to ~/.gemini/settings.json under \"mcpServers\":", '{ "playwright": { "command": "npx", "args": ["@playwright/mcp@latest", "--isolated"] } }'],
  }[t];
  console.log(`[${t}] skills -> ${d.skills}\n[${t}] commands -> ${d.commands}${t === "opencode" && d.agents ? `\n[${t}] agents -> ${d.agents}` : ""}\n[${t}] rules -> ${d.rules}\n[${t}] Playwright MCP (for manual-test):\n${mcp.join("\n")}\n`);
}

for (const t of target === "all" ? TARGETS : [target]) exportTarget(t);

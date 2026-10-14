// Reminds Claude to use the `conversation` skill when the prompt looks like a question or discussion.
// Heuristic only: the skill itself re-checks "chat or build?". Silent on no match.
let raw = "";
process.stdin.on("data", (c) => (raw += c));
process.stdin.on("end", () => {
  let prompt = "";
  try {
    prompt = String(JSON.parse(raw).prompt || "").trim();
  } catch {
    return;
  }
  if (!prompt || prompt.startsWith("/")) return;

  const hasQuestion = /[?？]/.test(prompt);
  const signal =
    /\b(how|why|what|explain|thoughts|idea|discuss|kivabe|kibhabe|keno|jante chai|bujhao|bujhiye|vabchi|vabtesi)\b|কীভাবে|কেন|কি |বুঝাও|বুঝিয়ে|আইডিয়া|ভাবছি/i;
  const build =
    /\b(build|fix|implement|create|add|run|deploy|banao|banaw|likho|koro|kor)\b|বানাও|লিখো|ঠিক কর/i;

  if (!(hasQuestion || signal.test(prompt))) return;
  if (!hasQuestion && build.test(prompt)) return;

  process.stdout.write(
    JSON.stringify({
      hookSpecificOutput: {
        hookEventName: "UserPromptSubmit",
        additionalContext:
          "This looks like a question or discussion. Invoke the `conversation` skill, unless the user clearly wants you to build, fix or run something.",
      },
    })
  );
});

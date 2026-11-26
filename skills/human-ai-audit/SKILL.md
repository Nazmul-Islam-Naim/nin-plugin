---
name: human-ai-audit
description: Use when the user wants to check how much of a document or a codebase looks AI-written versus human-written (AI likelihood, similarity/plagiarism, git authorship and review evidence).
---

# Human-AI Audit

## Overview

Two read-only modes: **A: Document Audit** and **B: Code Audit**. It reports indicators and evidence, never a verdict, and never edits project files.

**Language:** every reply and card is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms, commands and code in English.

## Pick the mode

- A file, link or pasted text (DOCX, PDF, MD, TXT) means **Mode A**.
- A repo, folder, commit range or PR means **Mode B**.
- Unclear means ask one short question. Both given means run both, as two separate cards.

## Mode A: Document Audit

1. **Read the whole document.** For DOCX/PPTX/XLSX/HTML or a large PDF, convert with the markitdown MCP as `report-study` does (same fallback: Read tool meanwhile).
2. **Paragraph table:** `#`, first words, AI signals, human signals, likelihood (Low/Med/High).
   - AI signals: uniform sentence length, generic transitions, stacked hedging, symmetric lists, no concrete detail.
   - Human signals: specific facts, names or numbers, irregular rhythm, own opinion, local context, small errors.
3. **Overall AI likelihood:** Low/Med/High plus a rough % range, with the 3 strongest signals.
4. **Similarity / plagiarism (separate section).** Web-search 3-5 distinctive sentences. List matches with URL and the matching sentence. No match means "যাচাই করা যায়নি" or "মেলেনি", never an invented source. Similarity is not AI likelihood, report them apart.

## Mode B: Code Audit

1. **Authorship:** `git log --format='%h|%an|%ae|%cn|%ad|%s'` and the `Co-Authored-By` trailers (`git log --format=%b`). Count commits per author and per co-author.
2. **Change pattern:** commit size (`--shortstat`), cadence and gaps, huge single commits, many files in minutes.
3. **File-wise table:** file, main author (`git blame`), commits touching it, last change. Large targets: top 20 files by churn, then offer the rest.
4. **Human review and modification evidence:** follow-up fix commits, amend/rebase traces, PR review comments (`gh pr view --comments` if available), later edits by a different author.
5. **Test and debugging history:** when test files first appeared, fix-after-fail pairs, reverts, bug-fix commits.
6. For a big history, chunk it by commit range and checkpoint as `code-review` does (`code-review-progress.md` idea), never read everything in one context.

## Output card (one Bangla card, then stop)

```
টার্গেট: <file/repo> (Mode A/B)
সারাংশ: ৩-৫ লাইন
স্কোর/সংকেত: <likelihood বা authorship ভাগ>
প্রমাণ: <টেবিল, প্রতিটিতে paragraph/commit hash/file:line>
Similarity: <শুধু Mode A, আলাদা>
সীমাবদ্ধতা: ...
পরবর্তী ধাপ: ...
```

## Rules

- Result is a **probability and evidence, not proof.** AI detectors are unreliable, especially for Bangla, non-native English and formal or edited writing. Say this on every card.
- Cite the paragraph, commit hash or `file:line` for every claim.
- Never invent numbers, sources, dates or authors.
- No accusing language about a person. Describe what the evidence shows.
- Mode B: a missing `Co-Authored-By` does not mean no AI was used, and a present one does not mean the AI wrote it all. Say so.
- Read-only. Never rewrite the document or the code unless asked.

## Red Flags

| Thought | Reality |
|---|---|
| "Score is 90%, so it is AI" | It is an indicator. Report the signals and limits. |
| "No co-author trailer, so human" | Trailers are optional and removable. |
| "No match found, so original" | Say only "no match found in the search". |
| "Merge similarity and AI score" | Different questions, separate sections. |

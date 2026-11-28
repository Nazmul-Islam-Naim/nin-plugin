---
name: sheet-format
description: Use whenever a nin-plugin skill creates a Google Sheet, adds a tab to one, or writes rows into one (project-sheet, code-review, prd-to-requirements, requirement-to-spec, parallel-build), so every sheet gets the same fixed format instead of plain rows.
---

# Sheet Format

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English. Cell text (headers, enum values) stays in English exactly as `layout.json` has it.

## The fixed format

`layout.json` (this skill's folder) is the only source of the format: tab names, columns, widths, dropdown values and colors. Never invent columns, colors or tab names in another skill; change `layout.json` instead.

- Header row: bold white text on dark blue `#1F3864`, row 1 frozen, filter on.
- Arial 10 everywhere, text at the top of the cell, long text columns wrap.
- Fixed column widths. Date columns show `yyyy-mm-dd`.
- Dropdowns on Severity, Priority, Impact, Status, Layer, Category.
- Colors: High red, Medium yellow, Low green; done states (Built, Fixed, Closed, Mitigated) green; in progress (Confirmed, Spec Written, In Progress) blue; Rejected / Won't Fix gray.
- Alternate row banding on table tabs. `Overview` is a two-column Field / Value table with bold shaded labels and borders.
- All of this covers open-ended ranges, so rows added later get the same format automatically.

## How to apply it

Read the `google-workspace` skill and its Sheets reference before the first Google Sheets call. The format needs the **Google Sheets connector** (`update_spreadsheet`, `update_values`, `get_spreadsheet`). The Drive connector alone cannot format a sheet.

1. **New spreadsheet:** Drive `create_file` with `contentMimeType: "application/vnd.google-apps.spreadsheet"`. Then:
   ```bash
   python <this skill>/build_requests.py requests [--tabs "Code Review"] > req.json
   python <this skill>/build_requests.py values   [--tabs "Code Review"] > hdr.json
   ```
   Send `req.json` as one `update_spreadsheet` call (it renames `Sheet1` and adds the other tabs), then write each `hdr.json` entry with `update_values`.
2. **Existing spreadsheet (add a tab or repair the format):** read `get_spreadsheet` with `fields: ["sheets.properties", "sheets.conditionalFormats", "sheets.bandedRanges"]`, save it as `meta.json`, then run `requests --meta meta.json`. Existing tabs keep their id; banding and colors are not added twice. Read row 1 of each tab with `get_values`; write headers only where row 1 is empty.
3. **Adding rows:** append below the last filled row (`get_values` on `'<Tab>'!A:A` gives the end). Write IDs and text that look like numbers with a leading `'`. Dates as `YYYY-MM-DD`. Use only the dropdown values from `layout.json`. Never overwrite or delete existing rows.
4. **Verify:** `get_values` on the header row and the new rows. If a request fails, fix that request and resend; never fall back to unformatted rows silently.

## No Sheets connector

Say in one Bangla line that formatting needs the Google Sheets connector and ask the user to turn it on. If they cannot, the calling skill uses its markdown fallback (for example `code-review`'s markdown file). Do not upload plain CSV as a "sheet" without saying it will be unformatted.

## Red Flags

| Thought | Reality |
|---|---|
| "Just append the rows, format later" | Format first. Plain rows are the problem this skill exists for. |
| "I'll add a nicer column here" | Columns come from `layout.json` only, so every project sheet matches. |
| "Re-run addBanding on the old tab" | Use `--meta`; the script skips banding and colors a tab already has. |
| "Drive create_file with CSV is fine" | CSV loses all formatting. Use the Sheets connector. |

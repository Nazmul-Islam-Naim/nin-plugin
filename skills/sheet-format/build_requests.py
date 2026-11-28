#!/usr/bin/env python3
"""Build the fixed nin-plugin Google Sheet format from layout.json.

No dependencies (Python 3.8+ standard library only).

  python build_requests.py requests [--meta meta.json] [--tabs "Code Review,Requirements"]
      Prints {"requests": [...]} for the Sheets connector's update_spreadsheet
      (one spreadsheets.batchUpdate). Without --meta it assumes a brand new
      spreadsheet whose only tab is "Sheet1" with sheetId 0.

  python build_requests.py values [--tabs ...]
      Prints [{"range": ..., "values": [[...]]}] for update_values: the header
      row of every tab, plus the field labels of the Overview tab.

meta.json is the get_spreadsheet result with
  fields: ["sheets.properties", "sheets.conditionalFormats", "sheets.bandedRanges"]
saved to a file. With it, tabs that already exist keep their sheetId, and
banding / conditional colors are skipped on a tab that already has them, so
the command can be re-run on an existing sheet to repair its format.
"""
import argparse
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))


def load_layout(path=None):
    with open(path or os.path.join(HERE, "layout.json"), encoding="utf-8") as f:
        return json.load(f)


def rgb(hex_color):
    h = hex_color.lstrip("#")
    return {"red": int(h[0:2], 16) / 255, "green": int(h[2:4], 16) / 255, "blue": int(h[4:6], 16) / 255}


def col_letter(i):
    s = ""
    i += 1
    while i:
        i, r = divmod(i - 1, 26)
        s = chr(65 + r) + s
    return s


def quote(title):
    return "'" + title.replace("'", "''") + "'"


def find_sheets(data):
    """Return the list of sheet objects from a saved get_spreadsheet result."""
    if isinstance(data, dict):
        if isinstance(data.get("sheets"), list):
            return data["sheets"]
        for v in data.values():
            found = find_sheets(v)
            if found is not None:
                return found
    elif isinstance(data, list):
        for v in data:
            found = find_sheets(v)
            if found is not None:
                return found
    elif isinstance(data, str):
        try:
            return find_sheets(json.loads(data))
        except ValueError:
            return None
    return None


def pick_tabs(layout, names):
    if not names:
        return layout["tabs"]
    wanted = [n.strip().lower() for n in names.split(",") if n.strip()]
    tabs = [t for t in layout["tabs"] if t["title"].lower() in wanted]
    missing = set(wanted) - {t["title"].lower() for t in tabs}
    if missing:
        sys.exit("unknown tab(s): " + ", ".join(sorted(missing)))
    return tabs


def grid(sheet_id, r0=None, r1=None, c0=None, c1=None):
    g = {"sheetId": sheet_id}
    if r0 is not None:
        g["startRowIndex"] = r0
    if r1 is not None:
        g["endRowIndex"] = r1
    if c0 is not None:
        g["startColumnIndex"] = c0
    if c1 is not None:
        g["endColumnIndex"] = c1
    return g


def tab_requests(tab, sid, style, colors, has_banding, has_cf):
    req = []
    cols = tab["columns"]
    n = len(cols)
    key_value = tab.get("kind") == "key-value"
    frozen = {"frozenRowCount": 1}

    # Whole tab: one font, text at the top of the cell.
    req.append({"repeatCell": {
        "range": grid(sid, 0, None, 0, n),
        "cell": {"userEnteredFormat": {"textFormat": {"fontFamily": style["font"], "fontSize": 10},
                                       "verticalAlignment": "TOP"}},
        "fields": "userEnteredFormat.textFormat.fontFamily,userEnteredFormat.textFormat.fontSize,"
                  "userEnteredFormat.verticalAlignment"}})

    # Header row.
    req.append({"repeatCell": {
        "range": grid(sid, 0, 1, 0, n),
        "cell": {"userEnteredFormat": {
            "backgroundColor": rgb(style["header_bg"]),
            "textFormat": {"bold": True, "foregroundColor": rgb(style["header_fg"]),
                           "fontFamily": style["font"], "fontSize": 10},
            "horizontalAlignment": "LEFT", "verticalAlignment": "MIDDLE", "wrapStrategy": "WRAP"}},
        "fields": "userEnteredFormat.backgroundColor,userEnteredFormat.textFormat,"
                  "userEnteredFormat.horizontalAlignment,userEnteredFormat.verticalAlignment,"
                  "userEnteredFormat.wrapStrategy"}})
    req.append({"updateDimensionProperties": {
        "range": {"sheetId": sid, "dimension": "ROWS", "startIndex": 0, "endIndex": 1},
        "properties": {"pixelSize": 32}, "fields": "pixelSize"}})
    req.append({"updateSheetProperties": {
        "properties": {"sheetId": sid, "gridProperties": frozen},
        "fields": "gridProperties.frozenRowCount"}})

    for i, c in enumerate(cols):
        req.append({"updateDimensionProperties": {
            "range": {"sheetId": sid, "dimension": "COLUMNS", "startIndex": i, "endIndex": i + 1},
            "properties": {"pixelSize": c.get("width", 120)}, "fields": "pixelSize"}})
        if c.get("wrap"):
            req.append({"repeatCell": {
                "range": grid(sid, 1, None, i, i + 1),
                "cell": {"userEnteredFormat": {"wrapStrategy": "WRAP"}},
                "fields": "userEnteredFormat.wrapStrategy"}})
        if c.get("date"):
            req.append({"repeatCell": {
                "range": grid(sid, 1, None, i, i + 1),
                "cell": {"userEnteredFormat": {"numberFormat": {"type": "DATE", "pattern": style["date_format"]}}},
                "fields": "userEnteredFormat.numberFormat"}})
        if c.get("enum"):
            req.append({"setDataValidation": {
                "range": grid(sid, 1, None, i, i + 1),
                "rule": {"condition": {"type": "ONE_OF_LIST",
                                       "values": [{"userEnteredValue": v} for v in c["enum"]]},
                         "strict": True, "showCustomUi": True}}})
            if not has_cf:
                for v in c["enum"]:
                    if v not in colors:
                        continue
                    bg, fg = colors[v]
                    req.append({"addConditionalFormatRule": {"index": 0, "rule": {
                        "ranges": [grid(sid, 1, None, i, i + 1)],
                        "booleanRule": {
                            "condition": {"type": "TEXT_EQ", "values": [{"userEnteredValue": v}]},
                            "format": {"backgroundColor": rgb(bg),
                                       "textFormat": {"foregroundColor": rgb(fg), "bold": True}}}}}})

    if key_value:
        # Field labels in column A.
        req.append({"repeatCell": {
            "range": grid(sid, 1, 1 + len(tab.get("fields", [])), 0, 1),
            "cell": {"userEnteredFormat": {"backgroundColor": rgb(style["label_bg"]),
                                           "textFormat": {"bold": True, "fontFamily": style["font"], "fontSize": 10}}},
            "fields": "userEnteredFormat.backgroundColor,userEnteredFormat.textFormat"}})
        req.append({"updateBorders": {
            "range": grid(sid, 0, 1 + len(tab.get("fields", [])), 0, n),
            "top": {"style": "SOLID", "color": rgb(style["border"])},
            "bottom": {"style": "SOLID", "color": rgb(style["border"])},
            "left": {"style": "SOLID", "color": rgb(style["border"])},
            "right": {"style": "SOLID", "color": rgb(style["border"])},
            "innerHorizontal": {"style": "SOLID", "color": rgb(style["border"])},
            "innerVertical": {"style": "SOLID", "color": rgb(style["border"])}}})
    else:
        if not has_banding:
            req.append({"addBanding": {"bandedRange": {
                "range": grid(sid, 0, None, 0, n),
                "rowProperties": {"headerColor": rgb(style["header_bg"]),
                                  "firstBandColor": rgb("#FFFFFF"),
                                  "secondBandColor": rgb(style["band_bg"])}}}})
        req.append({"setBasicFilter": {"filter": {"range": grid(sid, 0, None, 0, n)}}})
    return req


def build_requests(layout, tabs, meta_sheets):
    style, colors = layout["style"], layout["colors"]
    existing = {}
    used_ids = set()
    if meta_sheets is not None:
        for s in meta_sheets:
            p = s.get("properties", {})
            sid = p.get("sheetId", 0)
            used_ids.add(sid)
            existing[p.get("title", "").lower()] = {
                "id": sid,
                "banding": bool(s.get("bandedRanges")),
                "cf": bool(s.get("conditionalFormats")),
            }

    req = []
    next_id = 1001
    first_reused = False
    for tab in tabs:
        key = tab["title"].lower()
        if key in existing:
            info = existing[key]
            req += tab_requests(tab, info["id"], style, colors, info["banding"], info["cf"])
            continue
        if meta_sheets is None and not first_reused:
            # Brand new spreadsheet: rename the default "Sheet1" (sheetId 0).
            first_reused = True
            req.append({"updateSheetProperties": {"properties": {"sheetId": 0, "title": tab["title"]},
                                                  "fields": "title"}})
            req += tab_requests(tab, 0, style, colors, False, False)
            continue
        while next_id in used_ids:
            next_id += 1
        sid = next_id
        used_ids.add(sid)
        req.append({"addSheet": {"properties": {"sheetId": sid, "title": tab["title"]}}})
        req += tab_requests(tab, sid, style, colors, False, False)
    return {"requests": req}


def build_values(tabs):
    out = []
    for tab in tabs:
        names = [c["name"] for c in tab["columns"]]
        out.append({"range": quote(tab["title"]) + "!A1:" + col_letter(len(names) - 1) + "1", "values": [names]})
        fields = tab.get("fields")
        if fields:
            out.append({"range": quote(tab["title"]) + "!A2:A" + str(1 + len(fields)),
                        "values": [[f] for f in fields]})
    return out


def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("what", choices=["requests", "values"])
    ap.add_argument("--meta", help="saved get_spreadsheet result (sheets.properties, conditionalFormats, bandedRanges)")
    ap.add_argument("--tabs", help="comma-separated tab titles from layout.json (default: all)")
    ap.add_argument("--layout", help="alternative layout.json")
    a = ap.parse_args()

    layout = load_layout(a.layout)
    tabs = pick_tabs(layout, a.tabs)
    if a.what == "values":
        result = build_values(tabs)
    else:
        meta = None
        if a.meta:
            with open(a.meta, encoding="utf-8") as f:
                raw = f.read()
            try:
                meta = find_sheets(json.loads(raw))
            except ValueError:
                sys.exit("meta file is not JSON")
            if meta is None:
                sys.exit("no 'sheets' list in meta file; read with fields [\"sheets.properties\", ...]")
        result = build_requests(layout, tabs, meta)
    json.dump(result, sys.stdout, ensure_ascii=False, indent=1)
    sys.stdout.write("\n")


if __name__ == "__main__":
    main()

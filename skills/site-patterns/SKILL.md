---
name: site-patterns
description: Use when building or planning a whole website or app of a known type (e-commerce or marketplace like Amazon or Alibaba, social feed like Facebook, live sports like Cricbuzz or ESPN, brand marketing like Tesla, news, dashboard, portfolio) to decide its pages, section order and key components.
---

# Site Patterns

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms in English.

## Steps

1. **Pick the closest type** from the table (or blend two). State the choice under "ধরে নিয়েছি". Ask only if two types would give very different sites.
2. **Fix the page list first.** Take the pages from the table, drop what the user does not need, keep the user's names.
3. **Lay out each page** with its sections in the order shown. One primary action per page.
4. **List the key components** and hand them to `component-build`. Dense parts go to `data-dense-ui`, data to `mock-data-layer`, look to `business-design` and `visual-polish`.
5. **Build small first.** Home plus the one page that carries the core value (product page, live match, feed). The rest after the user sees it.

## Patterns

| Type | Pages | Section order and key parts |
|---|---|---|
| **E-commerce / marketplace** (Amazon, Alibaba) | Home, category, search results, product detail, cart, checkout, account | Header with search and cart, category strip, deal and product rows. Product page: gallery, title, price, stock, buy button, variants, reviews, related. Cart to checkout in few steps, sticky order summary |
| **Social / feed** (Facebook) | Feed, profile, post composer, notifications, messages, groups | Left nav, center feed, right suggestions. Post card: author, time, content, reactions, comments. Composer at top. Skeleton and infinite scroll |
| **Live sports** (Cricbuzz, ESPN) | Home with live scores, match detail, schedule, teams, players, news | Score ticker on top, live cards with "LIVE" badge. Match: header score, tabs (summary, scorecard, commentary, squads). Compact dense text, fast to scan |
| **Brand / product marketing** (Tesla) | Home, product pages, spec, configurator, order, support | Full-screen hero, one message per scroll section, large imagery, minimal text, one clear CTA, sticky slim header |
| **News / content** | Home, article, section, search | Lead story, grid of stories, readable article column (`max-w-prose`), related, newsletter |
| **SaaS / dashboard** | Landing, sign in, dashboard, detail, settings | Sidebar nav, KPI cards, table and chart, filters, clear empty states |
| **Portfolio / agency** | Home, work, case study, about, contact | Strong hero, project grid, case study story, contact CTA |

## Red Flags

| Thought | Reality |
|---|---|
| "Clone the whole site" | Build the core pages first. A copy of every page is months of work. |
| "Copy their logo, brand and images" | Take the structure and patterns, not the brand. Use original colors, name and assets. |
| "Same layout for every type" | A shop, a feed and a live score page scan differently. Follow the type. |
| "Skip the page list" | Without it, pages get invented mid-build and the navigation breaks. |

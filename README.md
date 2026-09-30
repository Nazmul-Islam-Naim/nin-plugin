# nin-plugin

Claude Code plugin that turns a requirement into a spec, and designs, builds and reviews frontend UI (React + Tailwind, Next.js) for any type of site. Replies come in Bangla script.

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

## Skills

| Skill | Use it to |
|---|---|
| `prd-to-requirements` | Break a client PRD/BRD into a tracked list of requirements in a Google Sheet |
| `requirement-to-spec` | Turn a requirement into a frozen instruction file and an English spec |
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

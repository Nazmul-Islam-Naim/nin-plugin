---
name: auth-and-access
description: Use when adding login/registration, protecting a route or endpoint, or checking roles/permissions — session or token authentication and role-based access control, across backend, web frontend and mobile.
---

# Auth And Access

**Language:** every reply is in Bangla script (বাংলা অক্ষর), never Banglish. Keep technical terms and code in English.

## Steps

1. **Pick one mechanism, everywhere.** Session cookies for a server-rendered app, or a token (JWT/opaque, with refresh) for API-driven clients (SPA, mobile) — not both for the same client. A backend serving web and mobile from one API almost always means token-based auth: a mobile app cannot hold a browser session cookie.
2. **Hash passwords with the framework's tool**, never a custom one: Laravel's `Hash::make`/bcrypt, FastAPI's `passlib`/bcrypt. Never log or return a password or its hash.
3. **Authentication and authorization stay separate.** Authentication (who is this) happens once at login and issues a token/session. Authorization (can this user do X) is checked per request from the user's role/permissions already attached server-side — never re-derived from a field the client sent (a `role` in the request body is never trusted).
4. **Backend: guard at the boundary.** One middleware/dependency verifies the token/session and attaches the user to the request before it reaches the controller/router. A second, thin guard checks role/permission — never scattered `if user.role == 'admin'` checks inside business logic (the same boundary-validation rule as `backend-architecture`, applied to identity).
   - **Laravel:** Sanctum for SPA/mobile token auth, or the session guard for a server-rendered admin. Policies/Gates for authorization, registered per module (see `laravel-structure`).
   - **FastAPI:** OAuth2 password flow issuing a JWT, verified in a `Depends()` provider that yields the current user; a second `Depends()` checks the role, composed like any other dependency in `fastapi-structure`.
5. **Web (storefront/admin): store the token deliberately.** An `httpOnly` secure cookie when the backend can set one — it can't be read by injected JS. If a JS-readable token is unavoidable, add CSRF protection on state-changing requests. Gate admin-only pages at the route/middleware level (Next.js middleware or a layout check), not by hiding the nav link — a hidden link is not a permission check.
6. **Mobile: never `AsyncStorage` for tokens.** Use secure storage (`expo-secure-store` or `react-native-keychain`) for the access/refresh token. The shared `apiClient` (see `react-native-structure`) attaches the token via an interceptor, refreshes once on a 401, then signs the user out on a second failure. A navigation guard checks auth state before rendering the protected stack — the same principle as the web route gate.
7. **One place decides "is this user allowed here."** Backend: the guard/dependency. Web: the route gate. Mobile: the navigation guard. Never duplicate the role check inside individual screens, components or handlers — duplicated checks drift out of sync.
8. **Token lifetime.** A short-lived access token plus a longer-lived refresh token beats one long-lived token everywhere. Invalidate sessions/tokens server-side on password change and on explicit logout, not just by deleting them client-side.

## Red Flags

| Thought | Reality |
|---|---|
| "Trust the `role` field sent from the client" | The client can send anything. Look up the role server-side from the authenticated user. |
| "Hide the admin link, that's enough" | Anyone can hit the URL or endpoint directly. Guard the route/endpoint itself. |
| "Store the token in AsyncStorage/localStorage" | Both are readable by XSS or any code with app access. Use secure storage or an httpOnly cookie. |
| "Check the role everywhere it's needed, inline" | Centralize the check in one guard/middleware/navigation gate. |
| "Never expire the token, simpler for the user" | A stolen token that never expires is a permanent breach. Short-lived plus refresh. |

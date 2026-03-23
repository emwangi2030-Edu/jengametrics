# Security standards — current and future apps

Use this checklist for **this app** and any **new apps** so they follow the same security baseline. Copy this file into new projects and apply the rules there.

---

## 1. Route protection

- [ ] **App routes behind auth**  
  Every route that serves app/dashboard/data (dashboard, BoQ, BOMs, projects, workers, materials, reports, documents, profile, admin, etc.) MUST be inside `Route::middleware(['auth'])->group(...)` (or `['auth', 'verified']` if using email verification).
- [ ] **Public-only routes**  
  Only these stay outside auth: home (e.g. redirect to login), login, register, forgot-password, reset-password, verify-email, and static/public pages if any.
- [ ] **Wizard / onboarding**  
  If wizard is for logged-in users only, put it inside the auth (and optionally verified) group.

**Example (Laravel):**
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);
    Route::resource('projects', ...);
    // ... all other app routes
});
require __DIR__.'/auth.php';
```

---

## 2. Throttling

- [ ] **Auth routes**  
  Add `->middleware('throttle:5,1')` to:
  - POST login
  - POST register
  - POST forgot-password
- [ ] **API auth**  
  Add `->middleware('throttle:5,1')` to API register and login routes.

---

## 3. API authentication

- [ ] **Single driver**  
  Use one token driver (e.g. **Sanctum**). In `config/auth.php`, set `api` guard `driver` to `sanctum` (not `passport` unless you use Passport everywhere).
- [ ] **Token creation**  
  For Sanctum use `$user->createToken('name')->plainTextToken`. Do not mix Passport (`accessToken`) and Sanctum in the same app.
- [ ] **Protected routes**  
  API routes that need auth use `auth:sanctum` (or the guard you chose).

---

## 4. Proxies and HTTPS

- [ ] **TrustProxies**  
  Do not hardcode `$proxies = '*'`. Read from config/env (e.g. `TRUSTED_PROXIES`). In production set to your reverse proxy IP(s) (e.g. `127.0.0.1` or comma-separated list).
- [ ] **HTTPS in app**  
  When behind a reverse proxy and `APP_URL` is https, force scheme and root URL in `AppServiceProvider` (e.g. `URL::forceRootUrl(config('app.url')); URL::forceScheme('https');`) so links and form actions are https.

---

## 5. Error pages

- [ ] **404 and 500**  
  Add `resources/views/errors/404.blade.php` and `500.blade.php`. Prefer standalone HTML (no app layout) so they work even when the app fails. No stack traces or internal details on 500.

---

## 6. Environment validation

- [ ] **Deploy check**  
  Provide an Artisan command (e.g. `env:validate`) that checks required env vars (APP_KEY, APP_ENV, DB_*, etc.) and exits with code 1 if any are missing or invalid.
- [ ] **Deploy script**  
  Run it during deploy: `php artisan env:validate || exit 1`.

---

## 7. Email verification (if used)

- [ ] **User model**  
  If you require verification: `class User extends Authenticatable implements MustVerifyEmail`.
- [ ] **App routes**  
  Use `middleware(['auth', 'verified'])` for the app route group so unverified users are redirected to the verify-email page.
- [ ] **New user creation**  
  If you auto-verify (e.g. API or sub-account creation), set `email_verified_at => now()` when creating the user. Include `email_verified_at` in the User model’s `$fillable` if you set it via `User::create()`.

---

## 8. New apps

When starting a **new** app:

1. Copy this file into the new repo (e.g. `docs/SECURITY_STANDARDS.md`).
2. Add a Cursor rule (e.g. `.cursor/rules/security-standards.mdc`) that references this checklist and instructs the AI to apply it when adding routes, API auth, or deployment steps. Use `alwaysApply: true` if you want it in every conversation.
3. Implement each checked item for that stack (Laravel, Node, etc.) using the same principles (auth on app routes, throttle on auth, single API token driver, env validation, custom error pages, trusted proxies from env).

---

*Last updated from JengaMetrics implementation. Adjust stack-specific details (e.g. Laravel vs Node) when reusing in other projects.*

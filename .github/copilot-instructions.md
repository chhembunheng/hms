## WinTechWebsite — Copilot instructions

Short, practical guidance for AI coding agents working in this repository.

### Quick context
- Laravel 12 project (PHP ^8.2). Main entry points: `public/index.php`, `routes/*.php`, `app/Http/Controllers`.
- Frontend uses Vite + Tailwind (see `vite.config.js`, `tailwind.config.js`, `resources/js`, `resources/css`).

### Quick start (commands you can run locally)
- Install PHP deps: `composer install`
- Install JS deps: `npm install`
- Run frontend dev: `npm run dev` (uses Vite)
- Full local dev (combined): `composer run dev` — this runs `php artisan serve`, queue listener, pail, and `npm run dev` concurrently (see `composer.json` scripts).
- Run tests: `composer test` (runs `php artisan test`, Pest is configured). PHPUnit config uses in-memory sqlite for tests (`phpunit.xml`).

### Architecture & boundaries (big picture)
- Typical Laravel MVC: routes in `routes/` map to controllers in `app/Http/Controllers` and views in `resources/views`.
- Data layer: Eloquent models live in `app/Models` and factories/seeders under `database/`.
- Server-side UI helpers and DataTables logic: look under `app/DataTables` and `yajra/laravel-datatables` is included in composer.
- Background work & queues: queue worker is started in the `composer dev` script (`php artisan queue:listen`). Queued jobs are in `app/Mail`/`Jobs.php` and other `Jobs` locations.
- Helpers: global helpers are autoloaded from `app/Helpers/helpers.php` and `app/Helpers/jwt.php` (listed in composer.json `autoload.files`).

### Project-specific conventions & patterns
- Tests use Pest (dev dependency). Use Pest style tests where existing tests follow that pattern.
- `phpunit.xml` is configured to use sqlite in-memory DB for fast tests — prefer this for unit/feature tests to avoid external DB dependency.
- `composer run dev` is the canonical convenience command for starting the app during development (runs multiple processes). Use it instead of composing separate commands unless you need finer control.
- `app/Providers/AppServiceProvider.php` and other providers may register bindings used app-wide — changes there can affect many files.

### Important integration points & external deps
- Octane: `laravel/octane` is required — production may use Swoole or RoadRunner; be careful when editing code assumed to run under Octane (stateful single-process caveats).
- JWT auth: `tymon/jwt-auth` with helpers in `app/Helpers/jwt.php` — check `config/jwt.php` and middleware under `app/Http/Middleware` for auth flow.
- DataTables: server-side DataTables live in `app/DataTables` and rely on `yajra/laravel-datatables` packages.
- Caching & SEO: `spatie/laravel-responsecache`, `artesaos/seotools`, `spatie/laravel-sitemap` appear in composer.json — look in `config/` and `app/Console` for commands that populate caches/sitemaps.

### Where to look for common tasks (examples)
- Add a new page + controller: update `routes/web.php` (or `routes/frontend.php`), add controller in `app/Http/Controllers/Frontend`, add view in `resources/views`.
- Add API endpoints: follow existing controllers under `app/Http/Controllers` and request validation under `app/Http/Requests`.
- Add a migration: `php artisan make:migration` and place logic in `database/migrations` — seeds and factories live under `database/seeders` and `database/factories`.

### Tests & CI hints
- Run `composer test` locally. Tests expect sqlite in-memory so no DB setup is required.
- If adding integration tests that need DB state persisted to disk, update `phpunit.xml` or use an explicit sqlite file and ensure migrations run in the test bootstrap.

### Code-style & tooling
- `laravel/pint` is installed for PHP formatting. Use it when formatting PHP code to match project expectations.
- Frontend: Tailwind and Vite are used. CSS utilities and component styling follow Tailwind conventions.

### Gotchas / things agents should not assume
- There is no `.github/copilot-instructions.md` yet (this file will live here). There may be other local conventions (e.g., `app/Helpers/*` autoload) — always check `composer.json` autoload files.
- Octane presence means some code may be expected to be idempotent and safe in long-running workers — avoid introducing per-request global mutable state without reset logic.

### If you change something — what to run to validate
- PHP deps changed: `composer install && composer dump-autoload`
- JS deps/build changed: `npm install && npm run build`
- Run tests: `composer test`

---
If any of the above is unclear or you want short examples for a specific task (add controller, write a Pest test, update Datatables), tell me which area and I will expand with file-level examples and small code snippets.

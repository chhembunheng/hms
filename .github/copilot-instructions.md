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

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.27
- laravel/framework (LARAVEL) - v12
- laravel/octane (OCTANE) - v2
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v3
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v3
- phpunit/phpunit (PHPUNIT) - v11
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v3

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

## WinTechWebsite — Condensed Copilot Instructions

High-impact guidance for AI coding agents in this repository. Redundancies and repeated detail removed.

### Core Stack & Entry Points
- Laravel 12 (PHP ^8.2) MVC: routes (`routes/*.php`) → controllers (`app/Http/Controllers`) → views (`resources/views`).
- Frontend: Vite + Tailwind (`vite.config.js`, `tailwind.config.js`). Dev server: `npm run dev`.
- Helpers autoloaded from `app/Helpers/helpers.php` & `app/Helpers/jwt.php`.

### Essential Commands
- Install deps: `composer install` / `npm install`.
- Full dev (multi-process): `composer run dev`.
- Run tests (sqlite in-memory): `composer test`.
- Rebuild assets: `npm run build`.

### Key Packages / Concerns
- Octane (long-lived processes): avoid global mutable state per request.
- JWT Auth (`tymon/jwt-auth`): see `config/jwt.php` + middleware.
- DataTables (`app/DataTables` + yajra packages).
- SEO & Caching: responsecache, seotools, sitemap.

### When Implementing Features
- Prefer `php artisan make:*` generators (`--no-interaction`).
- Add pages: route file → controller → Blade view.
- Use Form Request classes for validation (no inline `$request->validate()`).
- Eloquent first: relationships, eager loading to prevent N+1. Avoid raw `DB::` unless necessary.
- Name routes & use `route()` for URL generation.

### Testing (Pest)
- All changes require test coverage (feature tests typical). Write concise Pest tests: `it('does X', fn () => ...)`.
- Use factories + states; leverage datasets for repetitive validation.
- Targeted run: `php artisan test tests/Feature/FooTest.php` before full suite.
- Prefer semantic assertions (`assertSuccessful()`, `assertForbidden()`).

### PHP & Code Style
- Use constructor property promotion; always explicit return types & parameter hints.
- Relationship methods typed (e.g. `public function user(): BelongsTo`).
- Use PHPDoc for complex shapes; avoid stray inline comments.
- Run `vendor/bin/pint --dirty` before finalizing.

### Livewire (v3)
- Namespace `App\Livewire`; layout `components.layouts.app`.
- Real-time binding: `wire:model.live`; deferred default: `wire:model`.
- Use `$this->dispatch()` for events; add `wire:key` in loops.
- Validate & authorize inside actions; single root element per component.

### Tailwind
- Follow existing utility patterns; use `gap-*` instead of ad-hoc margins for lists.
- Support dark mode with `dark:` where precedent exists.

### Documentation & Tools (Laravel Boost)
- Search version-specific docs first via `search-docs` (broad topic queries before coding).
- Use `list-artisan-commands` to confirm generator options.
- Use `tinker` for dynamic inspection; `database-query` for read-only SQL.
- Generate absolute links with `get-absolute-url`.
- Check recent browser issues via `browser-logs`.

### Configuration & Environment
- Only reference env values through `config()` in app code (never direct `env()` outside config files).
- For column modifications, repeat full column definition in migrations.

### Common Recovery / Errors
- Vite missing manifest: run `npm run build` or ensure dev server running.

### Quality Checklist Before Completing Work
1. Code follows existing naming/style; no new base folders.
2. Proper artisan generators used.
3. Validation via Form Request.
4. Routes named; URLs generated with `route()`.
5. No N+1 (eager load where needed).
6. Tests added/updated & passing (targeted run done).
7. Pint formatting applied.
8. Assets rebuilt if front-end changed.
9. Long-lived state safe for Octane.

### Quick Reference Commands
```bash
composer run dev
composer test
vendor/bin/pint --dirty
npm run dev
npm run build
```

Need an example (controller, Form Request, Livewire component, Pest test)? Ask with the target artifact and I’ll scaffold succinctly.
### Models

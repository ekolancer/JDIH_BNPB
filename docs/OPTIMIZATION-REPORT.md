# Codebase Optimization Report

**Project:** JDIH BNPB  
**Date:** 2026-06-17  
**Stack:** Laravel 12, PHP 8.2+, Filament 4, Livewire 3, Vite, Tailwind CSS 4, MySQL, database-backed queue/cache/session  
**Files Reviewed:** 244 app/config/route/database/resource/test/doc files, plus dependency manifests and root configuration

## Health Score

| Dimension | Score | Status |
|---|---:|---|
| Performance | 6/10 | Needs Work |
| Security | 4/10 | Needs Work |
| Code Quality | 6/10 | Needs Work |
| Scalability | 5/10 | Needs Work |
| Testing | 4/10 | Needs Work |
| Developer Experience | 6/10 | Needs Work |
| Feature Gaps | 6/10 | Needs Work |
| **Overall** | **5.3/10** | **Needs Work** |

---

## Executive Summary

The application has a solid Laravel foundation: public routes are separated cleanly, request validation exists for sensitive public inputs, document file paths are normalized before access, visitor geo lookup is queued, and several recent migrations show active work on indexing and search performance. The codebase is not in a crisis, but it has a few release-blocking hygiene issues that should be fixed before treating it as production-ready.

The most important issue is a hardcoded database password in `config/api.php`. Even if this file is unused, it is still a credential exposure pattern and should be rotated, removed from source, and replaced with environment-driven config. The second most important issue is test reliability: the test suite depends on MySQL, so it needs an explicit test database setup instead of relying on each developer machine to already have a matching local database.

Performance risks are mostly database-shape issues rather than inefficient PHP loops. The public document list has good pagination and fulltext search for the main UI, but dynamic facet counts and visitor stats have query/index mismatches that will get worse as data grows. These should be measured with `EXPLAIN` on production-like data before claiming gains.

Recommended sprint focus: first remove secrets and make tests portable, then align the main hot-path queries with indexes, then clean up cache invalidation and observer fan-out. These are high impact, bounded changes.

---

## Critical Issues (Fix This Week)

### OPT-001 Hardcoded Database Credential In Source

| Field | Detail |
|---|---|
| Dimension | Security |
| Impact | High - source exposure can leak database access and makes rotation hard |
| Effort | Low |
| Metric | Risk reduction, not performance. Secret count in source: 1 -> 0 |
| Location | `config/api.php:27` |

**Problem:**
`config/api.php` contains a literal database username, password, host, and database name. A search found no current references to `config('api...')`, so the file may be legacy, but the credential should be treated as exposed.

**Evidence:**
```php
return [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => '[REDACTED]',
    'dbname' => 'jdih_bnpb_fix',
];
```

**Recommendation:**
Rotate the database password, remove this file if unused, or replace it with environment-backed config.

```php
return [
    'servername' => env('DB_HOST', '127.0.0.1'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'dbname' => env('DB_DATABASE'),
];
```

Use the existing `DB_*` environment variables unless this file is later proven to require a separate external database connection.

---

### OPT-002 Test Suite Depends On Live MySQL

| Field | Detail |
|---|---|
| Dimension | Testing / Developer Experience |
| Impact | High - tests fail on fresh machines without an explicit MySQL test DB |
| Effort | Medium |
| Metric | Local run observed: 18 pass / 46 fail in 418.57s -> target: documented local/manual test database |
| Location | `phpunit.xml:25` |

**Problem:**
`phpunit.xml` sets `APP_ENV=testing`, but the test database requirements were not explicit enough for a fresh machine. The original local run failed because MySQL refused connections for `jdih_dev`; Sprint 1 now makes the intended MySQL test database explicit.

**Evidence:**
```xml
<!-- <env name="DB_CONNECTION" value="sqlite"/> -->
<!-- <env name="DB_DATABASE" value=":memory:"/> -->
```

Observed verification:
```text
php artisan test
Tests: 46 failed, 18 passed
Failure root cause: SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

**Recommendation:**
Provide a dedicated MySQL test database for local/manual validation. This project has MySQL-specific migrations and schema history, so MySQL is the safer default test driver than retrofitting old migrations for SQLite.

```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="jdih_test"/>
```

Before running tests locally, create the test database and credentials that match `phpunit.xml`, then run `php artisan migrate --force` and `php artisan test`.

**Sprint 1 status:** Completed for explicit local/manual test configuration. Local validation still requires a local MySQL user/password that matches `phpunit.xml`.

---

## High Priority (Fix This Sprint)

### OPT-003 Visitor Date Queries Do Not Match Existing Index

| Field | Detail |
|---|---|
| Dimension | Performance |
| Impact | Medium/High - visitor middleware runs on most public requests |
| Effort | Low |
| Metric | Suspected. Measure with `EXPLAIN` before/after on `visitors`; expected indexed range scan instead of function-wrapped date scan |
| Location | `app/Http/Middleware/VisitorCounter.php:51`, `app/Models/Visitor.php:37` |

**Problem:**
The migration adds an index on `(ip_address, visited_at)`, but the middleware uses `whereDate('visited_at', ...)`. Wrapping a datetime column in `DATE()` commonly prevents efficient index use.

**Evidence:**
```php
$exists = Visitor::whereDate('visited_at', now()->toDateString())
    ->where('ip_address', $ip)
    ->exists();
```

Existing index:
```php
$table->index(['ip_address', 'visited_at'], 'idx_visitors_ip_visited');
```

**Recommendation:**
Use a range predicate.

```php
$exists = Visitor::where('ip_address', $ip)
    ->whereBetween('visited_at', [now()->startOfDay(), now()->endOfDay()])
    ->exists();
```

Apply the same idea to visitor stats by replacing `whereDate`, `whereMonth`, and `whereYear` with explicit date ranges.

---

### OPT-004 Missing Reverse Index For Subject Filtering

| Field | Detail |
|---|---|
| Dimension | Performance |
| Impact | Medium/High - document filtering and facet counts depend on subject lookups |
| Effort | Low |
| Metric | Suspected. Measure with `EXPLAIN` on subject-filtered `/dokumen`; expected faster lookup by `subjek_id` |
| Location | `database/migrations/2025_11_20_040023_dokumen_subjek_table.php:24`, `app/Models/DokumenHukum.php:604` |

**Problem:**
The pivot table has a unique key ordered as `(dokumen_hukum_id, subjek_id)`. The application also filters from the subject side with `ref_subjek.id`, which benefits from an index starting with `subjek_id`.

**Evidence:**
```php
$table->unique(['dokumen_hukum_id', 'subjek_id']);
```

```php
$q->whereHas(
    'subjekRef',
    fn($sq) => $sq->whereIn('ref_subjek.id', $v)
);
```

**Recommendation:**
Add a reverse composite index.

```php
$table->index(['subjek_id', 'dokumen_hukum_id'], 'idx_dokumen_subjek_subjek_dokumen');
```

Run `EXPLAIN` before and after on a representative subject filter and on the dynamic filter count query.

---

### OPT-005 Homepage Cache Is Not Invalidated By Content Changes

| Field | Detail |
|---|---|
| Dimension | Reliability / Feature Gaps |
| Impact | Medium - public counts/lists can stay stale after admin edits |
| Effort | Low |
| Metric | Observed code path. Staleness window: up to 300 seconds -> immediate after commit |
| Location | `app/Services/DashboardService.php:18`, `app/Observers/DokumenHukumObserver.php:83` |

**Problem:**
`DashboardService` caches `homepage_data` for 5 minutes and exposes `clearCache()`, but the document/news/infographic observers do not call it after create/update/delete.

**Evidence:**
```php
return Cache::remember('homepage_data', 300, function () {
```

```php
public function clearCache(): void
{
    Cache::forget('homepage_data');
}
```

**Recommendation:**
Call cache invalidation after committed content changes. Prefer a small service call or event listener so each observer does not duplicate logic.

```php
DB::afterCommit(fn() => app(DashboardService::class)->clearCache());
```

Also consider invalidating `berita_terpopuler` when `view_count` changes or when news is created/deleted.

---

### OPT-006 Turnstile Fails Open In Production Paths

| Field | Detail |
|---|---|
| Dimension | Security |
| Impact | Medium - CAPTCHA failure allows survey/login attempts when provider is unreachable |
| Effort | Low |
| Metric | Policy risk. Target: fail-open only in local/testing, fail-closed or degraded with tighter throttling in production |
| Location | `app/Http/Middleware/VerifyTurnstile.php:50`, `app/Filament/Auth/Login.php:197` |

**Problem:**
Both public survey Turnstile and admin login Turnstile allow the request if Cloudflare verification throws. That may be acceptable in development, but it should be explicit and environment-dependent.

**Evidence:**
```php
// Jika API Cloudflare down, izinkan request (graceful degradation)
return $next($request);
```

```php
// Graceful degradation: jika Cloudflare down, izinkan login
Log::error('Turnstile API unreachable', ['error' => $e->getMessage()]);
```

**Recommendation:**
In production, fail closed for survey spam protection and either fail closed for admin login or allow login only with stricter rate limits and high-severity alerting.

---

## Medium Priority (Next Sprint)

### OPT-007 Notification Observers Fan Out To All Users Synchronously After Commit

| Field | Detail |
|---|---|
| Dimension | Scalability |
| Impact | Medium - content updates grow slower as admin user count grows |
| Effort | Medium |
| Metric | Suspected. Measure content save time with 10, 100, and 1,000 admin users |
| Location | `app/Observers/DokumenHukumObserver.php:30`, `app/Observers/BeritaObserver.php:29`, `app/Observers/InfografisObserver.php:29` |

**Problem:**
Several observers load all users and send database notifications inside `DB::afterCommit`. That avoids transaction bloat, but still makes the request do fan-out work.

**Evidence:**
```php
private function recipients()
{
    return User::query()->get();
}
```

**Recommendation:**
Queue notification fan-out and restrict recipients to roles that need the notification. Reuse the role-filtered approach already used by `ProfilPageObserver`.

---

### OPT-008 Sitemap Generation Loads All Rows Into Memory

| Field | Detail |
|---|---|
| Dimension | Scalability |
| Impact | Medium - nightly sitemap can become memory-heavy as content grows |
| Effort | Low |
| Metric | Suspected. Measure memory with `php artisan sitemap:generate --profile` or Symfony memory output |
| Location | `app/Console/Commands/GenerateSitemap.php:48` |

**Problem:**
The sitemap command uses `->get()` for all documents, all news, and all infographics. This is fine at small scale but avoidable.

**Evidence:**
```php
$dokumen = DokumenHukum::select('slug', 'tipe_dokumen_id', 'updated_at')
    ->with('tipeDokumen:id,kode')
    ->orderByDesc('updated_at')
    ->get();
```

**Recommendation:**
Use `chunkById()` or `cursor()` and stream/write incrementally when catalog size grows.

---

### OPT-009 Metadata API Uses Leading-Wildcard LIKE Despite Fulltext Search Existing Elsewhere

| Field | Detail |
|---|---|
| Dimension | Performance |
| Impact | Medium - broad public API searches can scan tables |
| Effort | Medium |
| Metric | Suspected. Measure `/metadata/api?judul=...` with slow query log and `EXPLAIN` |
| Location | `app/Http/Controllers/ApiController.php:41` |

**Problem:**
The public document UI uses `whereFullText('judul', ...)`, and the schema has `ft_dokumen_judul`, but the metadata API still uses `LIKE '%term%'` for title and related names.

**Evidence:**
```php
$query->where('judul', 'like', '%' . $validated['judul'] . '%');
```

Fulltext exists:
```php
$table->fullText('judul', 'ft_dokumen_judul');
```

**Recommendation:**
Use `whereFullText('judul', $validated['judul'])` for title search where compatible with API expectations. For subject and bidang filters, consider exact slug/id filters or dedicated search indexes once API clients need broad text search.

---

## Low Priority / Technical Debt (Backlog)

- Replace PHPUnit doc-comment metadata with attributes before PHPUnit 12 removes support. The test run emitted many deprecation warnings.
- Add CI configuration. No `.github`, GitLab CI, Jenkins, CircleCI, Dockerfile, or Makefile was found in this scan. **Deferred by project decision; CI will be revisited after the team is ready to adopt it.**
- Add a Composer script for Pint, for example `composer lint` or `composer format`, so formatting is discoverable. **Sprint 1 update: `composer lint` and `composer format` have been added.**
- Avoid shipping `.env.example` with `APP_DEBUG=true` and `APP_ENV=local` as the first visible defaults unless deployment docs clearly override them.
- Consider caching sidebar menu pages in `ProfilPageController::sidebarMenus()` if profile traffic becomes high.
- Decide whether `config/api.php` is legacy and remove it entirely if unused.

---

## Quick Wins (< 1 Hour Each)

- [x] Env-ize `config/api.php` credentials using existing `DB_*` variables - `config/api.php:27`
- [ ] Rotate the previously exposed database password outside the codebase
- [ ] Change visitor duplicate check from `whereDate` to `whereBetween` - `app/Http/Middleware/VisitorCounter.php:51`
- [ ] Add reverse pivot index for subject filters - `database/migrations/2025_11_20_040023_dokumen_subjek_table.php:24`
- [x] Configure explicit MySQL testing settings in `phpunit.xml` - `phpunit.xml:25`
- [ ] Call `DashboardService::clearCache()` after document/news/infographic mutations - `app/Services/DashboardService.php:35`
- [ ] Make Turnstile fail-open behavior environment-controlled - `app/Http/Middleware/VerifyTurnstile.php:50`
- [x] Add named Composer scripts for formatting/testing - `composer.json:43`

---

## Dependency Audit

| Package / Ecosystem | Version | Status | Action |
|---|---:|---|---|
| npm dependencies | lockfile installed | OK - `npm audit --audit-level=moderate` found 0 vulnerabilities | Keep running during release checks |
| Laravel framework | `^12.0` | Not live-verified - Composer CLI not available on PATH | Run `composer audit` in the build/release environment |
| Filament | `^4.0` | Not live-verified - Composer CLI not available on PATH | Run `composer audit` and scheduled dependency review |
| Spatie packages | activitylog `^4.12`, backup `^9.3` | Not live-verified - Composer CLI not available on PATH | Run `composer audit`; review backup config in production |
| Google Analytics Data | `^0.24.0` | Not live-verified - Composer CLI not available on PATH | Run `composer outdated --direct` during maintenance |

Verification note: `composer audit` could not run because `composer` is not recognized in this shell.

---

## Recommended Tooling

| Tool | Purpose | Priority |
|---|---|---|
| CI pipeline | Run tests, npm audit, Composer audit, Pint on every change | Deferred |
| Laravel Pint script | Standardize PHP formatting | High |
| MySQL slow query log / `EXPLAIN` checklist | Validate query/index improvements with real plans | High |
| Laravel Telescope or Clockwork in staging | Observe request query counts and timing | Medium |
| Redis for cache/queue/session | Reduce DB load if traffic grows beyond current single-server assumptions | Medium |
| Dependabot or Renovate | Keep Composer/npm dependencies reviewed | Medium |

---

## Sprint Roadmap

### Sprint 1 - Release Hygiene

- [x] OPT-001: Remove hardcoded database credential from source
- [ ] OPT-001 follow-up: Rotate the previously exposed database password outside the codebase
- [x] OPT-002: Add explicit MySQL testing configuration for local/manual validation
- [ ] Deferred: Add CI with test, npm audit, Composer audit, build, and Pint jobs when the team is ready

### Sprint 2 - Query And Cache Performance

- [ ] OPT-003: Replace visitor date functions with indexed range queries
- [ ] OPT-004: Add reverse index for `dokumen_subjek`
- [ ] OPT-005: Wire homepage cache invalidation into content observers
- [ ] Measure `/dokumen` and `/metadata/api` with `EXPLAIN` before and after

### Sprint 3 - Scalability And Operations

- [ ] OPT-006: Make Turnstile degradation policy production-safe
- [ ] OPT-007: Queue notification fan-out and restrict recipients
- [ ] OPT-008: Stream or chunk sitemap generation
- [ ] Decide whether Redis should replace database cache/queue/session in production

---

## What's Working Well

- Public survey and metadata endpoints have throttling in `routes/web.php`.
- `MetadataApiRequest` validates `sort_by` and `sort_order`, preventing arbitrary order-by input.
- Document file download/view paths are normalized and checked before `Storage` access.
- The main document list uses pagination, eager loading, cached filter metadata, and fulltext search for `judul`.
- Visitor geo resolution is queued instead of blocking public page responses.
- There is useful documentation in `docs/ARCHITECTURE.md`, `docs/DEPLOYMENT.md`, `docs/CONTRIBUTING.md`, and `docs/PRODUCT_SPEC.md`.
- Tests exist for document relations, document controller behavior, visitor middleware, robots, sitemap, API validation, and SEO helper behavior; the main issue is environment portability, not absence of tests.

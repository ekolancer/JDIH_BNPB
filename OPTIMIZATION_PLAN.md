# Optimization Plan — JDIH BNPB

> **Dokumen ini berisi checklist optimasi yang diidentifikasi dari analisis codebase, ARCHITECTURE.md, dan PRODUCT_SPEC.md.**
> Disusun berdasarkan prioritas dampak dan effort implementasi.

---

## Daftar Isi

- [P0 — Kritis (Harus Segera)](#p0--kritis-harus-segera)
- [P1 — Performa & Keamanan](#p1--performa--keamanan)
- [P2 — Kualitas & Maintainability](#p2--kualitas--maintainability)
- [P3 — Fitur Tambahan](#p3--fitur-tambahan)

---

## P0 — Kritis (Harus Segera)

> Bug aktif, blocking performance, dan SEO fundamental yang hilang.

- [x] **FIX: Bug `SeoHelper::berita()` — field `isi` tidak ada**
  - File: `app/Helpers/SeoHelper.php`
  - Masalah: `$berita->isi` seharusnya `$berita->konten`. OG description berita selalu kosong.
  - Effort: ~5 menit

- [x] **FIX: External HTTP call di middleware `VisitorCounter` (blocking request)**
  - File: `app/Http/Middleware/VisitorCounter.php`
  - Solusi: Geo-lookup dipindahkan ke queued job `ResolveVisitorGeo`.
  - File baru: `app/Jobs/ResolveVisitorGeo.php`

- [x] **ADD: File `robots.txt`**
  - File: `public/robots.txt`
  - Disallow: `/webmin/`, `/storage/file/`, `/storage/abstrak/`, `/livewire/`, `/api/`, `/*?page=`

- [x] **FIX: Sitemap statis — tidak auto-update saat dokumen baru ditambahkan**
  - File: `app/Console/Commands/GenerateSitemap.php`
  - Command: `php artisan sitemap:generate`
  - Scheduler: harian jam 03:00

---

## P1 — Performa & Keamanan

> Bottleneck performa yang terukur dan celah keamanan medium.

### Performa

- [x] **CACHE: Homepage queries (`DashboardController@index`)**
  - Solusi: `Cache::remember('homepage_data', 300, ...)` via `DashboardService`

- [x] **CACHE: `Visitor::getVisitorStats()` — 5 COUNT query per homepage hit**
  - Solusi: `Cache::remember('visitor_stats', 600, ...)`

- [x] **CACHE: `beritaTerpopuler` di `BeritaController@show`**
  - Solusi: `Cache::remember('berita_terpopuler', 1800, ...)`

- [x] **FIX: N+1 query di `DokumenHukum::scopeGroupByColumns()`**
  - Solusi: Preload `ref_jenis_dokumen` dan `ref_bidang_hukum` sekali sebelum loop.

- [ ] **ADD: Response caching untuk halaman publik (detail dokumen, berita, infografis)**
  - Solusi: Gunakan `spatie/laravel-responsecache` atau HTTP cache headers (ETag/Last-Modified).
  - Status: Skip — butuh install package baru.
  - Effort: Medium

### Keamanan

- [x] **FIX: API endpoint tanpa pagination (`ApiController@index`)**
  - Solusi: Pagination wajib (`per_page` max 100) + response wrapper `{data, meta}`.

- [x] **ADD: Rate limiting pada survey endpoint (`POST /`)**
  - Solusi: `throttle:5,1` pada route `POST /`.

- [x] **FIX: `RestrictIpMiddleware` tidak support IPv6 CIDR**
  - Solusi: Refactor dengan `ipv6InCidr()` menggunakan binary string comparison (128-bit).
  - File: `app/Http/Middleware/RestrictIpMiddleware.php`

- [x] **ADD: Throttle protection pada rating API endpoints**
  - Solusi: `throttle:30,1` pada `/api/rating*` routes.

---

## P2 — Kualitas & Maintainability

> Technical debt, test coverage, dan operational gaps.

### Testing

- [x] **ADD: Feature tests untuk `DokumenController`**
  - File: `tests/Feature/DokumenControllerTest.php` (7 tests)
  - Cakupan: detail page, view count increment, 404 handling, path traversal.

- [x] **ADD: Feature tests untuk `ApiController`**
  - File: `tests/Feature/ApiControllerTest.php` (9 tests)
  - Cakupan: pagination, filters, validation, sorting, per_page limit.

- [x] **ADD: Unit tests untuk Model (slug, UID, accessors, scopes)**
  - File: `tests/Unit/DokumenHukumModelTest.php` (8 tests)
  - Cakupan: slug generation, UID generation, download_allowed, countByType, applyFilters.

- [x] **ADD: Tests untuk `VisitorCounter` middleware**
  - File: `tests/Feature/VisitorCounterTest.php` (6 tests)
  - Cakupan: record visitor, skip bot, skip admin, prevent duplicate, queue dispatch, non-blocking.

### Search & Indexing

- [x] **ADD: Full-text search pada kolom `judul`**
  - Migration: `2026_05_15_204041_add_fulltext_index_to_dokumen_hukums_judul.php`
  - Model: `scopeApplyFilters` menggunakan `whereFullText('judul', $v)`.

- [x] **ADD: Indeks pada tabel `visitors` (`ip_address`, `visited_at`)**
  - Migration: `2026_05_15_202604_add_index_to_visitors_table.php`
  - Index: `idx_visitors_ip_visited`

### Operational

- [x] **ADD: Scheduled job untuk cleanup tabel `visitors` (> 90 hari)**
  - File: `app/Console/Commands/CleanupVisitors.php`
  - Command: `php artisan visitors:cleanup --days=90`
  - Scheduler: harian jam 02:00

- [ ] **ADD: Error monitoring (Sentry / Bugsnag)**
  - Status: Butuh akun Sentry + DSN dari tim ops.
  - Effort: Kecil (setelah DSN tersedia)

- [ ] **ADD: Database backup scheduler**
  - Solusi: Gunakan `spatie/laravel-backup` dengan notifikasi ke email/Slack.
  - Effort: Medium

### Code Quality

- [x] **REFACTOR: Pindahkan logic `DashboardController` ke dedicated Service class**
  - File: `app/Services/DashboardService.php`
  - Controller sekarang hanya 3 baris, inject via constructor.

- [x] **FIX: Hapus dead code dan debug comments**
  - Dihapus: `// dd(...)` di DashboardController dan RestrictIpMiddleware.
  - Dihapus: unused imports di semua controller.

- [x] **ADD: Strict typing dan return types pada semua Controller methods**
  - `DashboardController`: `View`
  - `BeritaController`: `View`
  - `InfografisController`: `View`
  - `SurveyController`: `JsonResponse`
  - `ApiController`: `JsonResponse`

### SEO

- [x] **ADD: Canonical URL pada semua halaman publik**
  - File: `resources/views/layout/header.blade.php`, `header-clear.blade.php`
  - Tag: `<link rel="canonical" href="{{ url()->current() }}">`

---

## P3 — Fitur Tambahan

> Fitur baru yang meningkatkan nilai produk.

### Konten & UX

- [x] **ADD: Relasi antar dokumen (mencabut / mengubah / melengkapi)**
  - Solusi: Tabel pivot `dokumen_relasi` + UI di panel admin (Filament Relation Manager) dan stepper di detail publik.
  - File: `database/migrations/2026_05_22_092550_create_dokumen_relasi_table.php`
  - Model: `app/Models/DokumenRelasi.php` (dengan `getInverseRelasi()`, `getRelasiOptions()`)
  - Relation Manager: `app/Filament/Resources/DokumenHukumResource/RelationManagers/DokumenRelasiRelationManager.php`
  - View publik: stepper sidebar di `resources/views/dokumen/detail.blade.php`
  - CSS: `public/css/detail-page/dokumen.css` (spine gradient + legend)
  - Tests: `tests/Unit/DokumenRelasiTest.php` (5 tests), `tests/Feature/DokumenRelasiFeatureTest.php` (6 tests)
  - Fitur: inverse otomatis saat tambah/hapus, dedup `semuaRelasi()`, badge warna per jenis
  - Effort: Besar

- [ ] **ADD: Fitur "Dokumen Terkait" di halaman detail**
  - Solusi: Berdasarkan bidang hukum atau subjek yang sama.
  - Effort: Medium

- [ ] **ADD: Fitur share ke media sosial (WhatsApp, Twitter, Facebook)**
  - Effort: Kecil

- [ ] **ADD: Fitur cetak / print-friendly view untuk detail dokumen**
  - Effort: Kecil

### Admin Panel

- [ ] **ADD: Export data dokumen ke Excel/PDF**
  - Solusi: Gunakan Filament Export plugin atau `maatwebsite/excel`.
  - Effort: Medium

- [ ] **ADD: Bulk import dokumen via Excel/CSV**
  - Solusi: Filament Import plugin.
  - Effort: Medium

- [ ] **ADD: Dashboard notifikasi (dokumen baru, error, visitor anomaly)**
  - Effort: Medium

- [x] **ADD: Audit log viewer yang lebih detail di panel admin**
  - Solusi: `ActivityResource` Filament dengan custom glass-morphism view, filter log/event/subject/tanggal, diff old vs new values.
  - File: `app/Filament/Resources/ActivityResource.php`, `resources/views/filament/resources/activity-resource/pages/view-activity.blade.php`

### API & Integrasi

- [ ] **ADD: API versioning (`/api/v1/metadata`)**
  - Masalah: Perubahan format response bisa break sistem eksternal (PUU).
  - Solusi: Prefix versioning + backward compatibility period.
  - Effort: Medium

- [ ] **ADD: API documentation (Swagger/OpenAPI)**
  - Solusi: Gunakan `darkaonline/l5-swagger` atau tulis manual OpenAPI spec.
  - Effort: Medium

- [ ] **ADD: Webhook notification saat dokumen baru dipublikasi**
  - Solusi: Event-driven notification ke subscriber (PUU, email list).
  - Effort: Medium

### SEO & Accessibility

- [ ] **ADD: Structured data (JSON-LD) untuk dokumen hukum**
  - Solusi: Schema.org `Legislation` type pada halaman detail.
  - Effort: Medium

- [ ] **ADD: Breadcrumb structured data (JSON-LD)**
  - Effort: Kecil

- [ ] **ADD: 301 redirect saat slug dokumen berubah**
  - Masalah: Disebutkan di ADR-005 sebagai mitigasi yang belum diimplementasi.
  - Solusi: Simpan slug lama di tabel `slug_redirects`, middleware redirect.
  - Effort: Medium

### Infrastruktur

- [ ] **EVALUATE: Migrasi cache/session ke Redis**
  - Masalah: Database driver cukup untuk traffic saat ini, tapi jika traffic naik signifikan perlu Redis.
  - Solusi: Siapkan konfigurasi Redis sebagai opsi (sudah disebutkan di ADR-002).
  - Effort: Kecil (konfigurasi), Medium (deployment)

- [ ] **ADD: CDN untuk static assets dan file dokumen**
  - Solusi: Cloudflare atau AWS CloudFront di depan storage publik.
  - Effort: Medium

- [ ] **ADD: Queue worker monitoring**
  - Solusi: Laravel Horizon (jika pakai Redis) atau monitoring via health check.
  - Effort: Kecil

---

## Catatan Implementasi

1. **Urutan eksekusi:** P0 → P1 → P2 → P3. Dalam setiap prioritas, kerjakan yang effort "Kecil" terlebih dahulu.
2. **Branch strategy:** Buat branch `feature/optimization-{nomor}` untuk setiap item atau grup item terkait.
3. **Testing:** Setiap perubahan P0 dan P1 harus disertai minimal 1 test yang memvalidasi fix.
4. **Rollback plan:** Untuk perubahan infrastruktur (Redis, CDN), siapkan rollback ke konfigurasi sebelumnya.

---

## Test Suite Summary

| File | Tests | Cakupan |
|---|---|---|
| `tests/Unit/SeoHelperTest.php` | 4 | SEO meta generation |
| `tests/Unit/RestrictIpMiddlewareTest.php` | 7 | IPv4/IPv6 CIDR matching |
| `tests/Unit/DokumenHukumModelTest.php` | 8 | Slug, UID, accessors, scopes |
| `tests/Unit/DokumenRelasiTest.php` | 5 | Inverse mapping, labels, options |
| `tests/Feature/VisitorCounterTest.php` | 6 | Middleware behavior |
| `tests/Feature/RobotsTxtTest.php` | 4 | File existence & directives |
| `tests/Feature/GenerateSitemapTest.php` | 3 | Command & XML output |
| `tests/Feature/CleanupVisitorsTest.php` | 3 | Cleanup command |
| `tests/Feature/DokumenControllerTest.php` | 7 | Controller endpoints |
| `tests/Feature/ApiControllerTest.php` | 9 | API validation & pagination |
| `tests/Feature/DokumenRelasiFeatureTest.php` | 6 | Relasi CRUD, inverse, dedup, view |
| **Total** | **62** | **110+ assertions** |

---

*Dokumen ini di-generate pada: 15 Mei 2026*
*Terakhir diupdate: 22 Mei 2026*
*Berdasarkan analisis: codebase, `docs/ARCHITECTURE.md`, `docs/PRODUCT_SPEC.md`*

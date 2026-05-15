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
  - Masalah: Geo-lookup ke `ipapi.co` / `ipwho.is` dilakukan synchronous di setiap request publik. Menambah 3-6 detik latensi jika API lambat/timeout.
  - Solusi: Pindahkan geo-lookup ke queued job (`dispatch()->afterResponse()`) atau gunakan local GeoIP database (MaxMind GeoLite2).
  - Effort: Medium

- [x] **ADD: File `robots.txt`**
  - Masalah: Tidak ada `robots.txt`. Crawler bisa mengindeks `/webmin/`, `/storage/`, dan URL pagination.
  - Solusi: Buat `public/robots.txt` dengan Disallow untuk path sensitif.
  - Effort: ~5 menit

- [x] **FIX: Sitemap statis — tidak auto-update saat dokumen baru ditambahkan**
  - File: `public/sitemap.xml`
  - Masalah: Dibuat manual via tool eksternal. Dokumen/berita/infografis baru tidak masuk sitemap.
  - Solusi: Buat artisan command atau gunakan `spatie/laravel-sitemap` untuk generate otomatis via scheduler.
  - Effort: Medium

---

## P1 — Performa & Keamanan

> Bottleneck performa yang terukur dan celah keamanan medium.

### Performa

- [x] **CACHE: Homepage queries (`DashboardController@index`)**
  - File: `app/Http/Controllers/DashboardController.php`
  - Masalah: 7+ query dijalankan setiap kunjungan homepage tanpa caching.
  - Solusi: Wrap dalam `Cache::remember()` dengan TTL 5 menit.
  - Effort: Kecil

- [x] **CACHE: `Visitor::getVisitorStats()` — 5 COUNT query per homepage hit**
  - File: `app/Models/Visitor.php`
  - Masalah: 5 `COUNT(*)` terpisah ke tabel yang bisa 1 juta+ row.
  - Solusi: Cache 5-10 menit, atau gabungkan jadi 1 query conditional aggregation.
  - Effort: Kecil

- [x] **CACHE: `beritaTerpopuler` di `BeritaController@show`**
  - File: `app/Http/Controllers/BeritaController.php`
  - Masalah: Query top 5 berita dijalankan ulang setiap buka detail berita.
  - Solusi: Cache 30 menit.
  - Effort: Kecil

- [ ] **FIX: N+1 query di `DokumenHukum::scopeGroupByColumns()`**
  - File: `app/Models/DokumenHukum.php`
  - Masalah: `DB::table('ref_jenis_dokumen')->where('id', ...)->value('nama')` dipanggil di dalam loop.
  - Solusi: Preload semua ref data sebelum loop, atau gunakan JOIN.
  - Effort: Kecil

- [ ] **ADD: Response caching untuk halaman publik (detail dokumen, berita, infografis)**
  - Solusi: Gunakan `spatie/laravel-responsecache` atau HTTP cache headers (ETag/Last-Modified).
  - Effort: Medium

### Keamanan

- [x] **FIX: API endpoint tanpa pagination (`ApiController@index`)**
  - File: `app/Http/Controllers/ApiController.php`
  - Masalah: `$query->get()` mengembalikan semua dokumen tanpa limit. Bisa menyebabkan memory exhaustion.
  - Solusi: Tambahkan pagination wajib (`per_page` max 100).
  - Effort: Kecil

- [x] **ADD: Rate limiting pada survey endpoint (`POST /`)**
  - File: `routes/web.php`
  - Masalah: Endpoint survey tidak memiliki throttle. Bisa di-spam.
  - Solusi: Tambahkan middleware `throttle:5,1`.
  - Effort: ~5 menit

- [ ] **FIX: `RestrictIpMiddleware` tidak support IPv6 CIDR**
  - File: `app/Http/Middleware/RestrictIpMiddleware.php`
  - Masalah: `ip2long()` hanya support IPv4. IPv6 CIDR check gagal silently.
  - Solusi: Tambahkan pengecekan IPv6 atau gunakan library `mlocati/ip-lib`.
  - Effort: Kecil

- [ ] **ADD: CSRF protection pada rating API endpoints**
  - File: `routes/web.php`
  - Masalah: `GET /api/rating*` endpoints di web routes tanpa proteksi abuse.
  - Solusi: Pindahkan ke route group dengan throttle, atau pastikan hanya diakses dari frontend.
  - Effort: Kecil

---

## P2 — Kualitas & Maintainability

> Technical debt, test coverage, dan operational gaps.

### Testing

- [ ] **ADD: Feature tests untuk `DokumenController`**
  - Cakupan: download, path traversal prevention, view count increment, Gate authorization.
  - Effort: Medium

- [ ] **ADD: Feature tests untuk `ApiController`**
  - Cakupan: validasi parameter, throttle, response format, filter combinations.
  - Effort: Medium

- [ ] **ADD: Unit tests untuk Model Observers**
  - Cakupan: slug generation, UID generation, file cleanup on update/delete.
  - Effort: Medium

- [ ] **ADD: Unit tests untuk `VisitorCounter` middleware**
  - Cakupan: bot detection, geo skip, duplicate prevention, local IP handling.
  - Effort: Medium

### Search & Indexing

- [ ] **ADD: Full-text search pada kolom `judul`**
  - Masalah: Pencarian menggunakan `LIKE '%keyword%'` yang tidak memanfaatkan indeks.
  - Solusi: Tambahkan FULLTEXT index dan gunakan `whereFullText()`.
  - Effort: Medium

- [ ] **ADD: Indeks pada tabel `visitors` (`ip_address`, `visited_at`)**
  - Masalah: Query duplikasi harian tanpa indeks pada tabel yang tumbuh cepat.
  - Solusi: Composite index `(ip_address, visited_at)`.
  - Effort: Kecil

### Operational

- [ ] **ADD: Scheduled job untuk cleanup tabel `visitors` (> 90 hari)**
  - Masalah: Tabel tumbuh tanpa batas, bisa jutaan row.
  - Solusi: Artisan command + scheduler harian.
  - Effort: Kecil

- [ ] **ADD: Error monitoring (Sentry / Bugsnag)**
  - Masalah: Error hanya tercatat di log file. Tidak ada notifikasi real-time.
  - Solusi: Integrasi Sentry dengan `sentry/sentry-laravel`.
  - Effort: Kecil

- [ ] **ADD: Database backup scheduler**
  - Solusi: Gunakan `spatie/laravel-backup` dengan notifikasi ke email/Slack.
  - Effort: Medium

### Code Quality

- [ ] **REFACTOR: Pindahkan logic `DashboardController` ke dedicated Service class**
  - Masalah: Controller langsung memanggil banyak model statis. Sulit di-test.
  - Solusi: Buat `DashboardService` yang injectable.
  - Effort: Medium

- [ ] **FIX: Hapus dead code dan debug comments**
  - File: `DashboardController.php` (`// dd($data)`), `RestrictIpMiddleware.php` (`// dd(...)`)
  - Effort: ~5 menit

- [ ] **ADD: Strict typing dan return types pada semua Controller methods**
  - Effort: Kecil

---

## P3 — Fitur Tambahan

> Fitur baru yang meningkatkan nilai produk.

### Konten & UX

- [ ] **ADD: Relasi antar dokumen (mencabut / mengubah / melengkapi)**
  - Masalah: Tidak ada cara menghubungkan dokumen yang saling merujuk.
  - Solusi: Tabel pivot `dokumen_relasi` + UI di panel admin dan detail publik.
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

- [ ] **ADD: Audit log viewer yang lebih detail di panel admin**
  - Masalah: `spatie/laravel-activitylog` sudah terinstall tapi belum ada UI viewer di Filament.
  - Effort: Kecil

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

- [ ] **ADD: Canonical URL pada semua halaman publik**
  - Masalah: Pagination URL bisa menyebabkan duplicate content.
  - Effort: Kecil

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

*Dokumen ini di-generate pada: 15 Mei 2026*
*Berdasarkan analisis: codebase, `docs/ARCHITECTURE.md`, `docs/PRODUCT_SPEC.md`*

# Laporan Audit Teknis — JDIH_BNPB (Infrastructure)

**Tanggal:** 2026-04-07

Ringkasan singkat:
- Aplikasi ini adalah sebuah monolit Laravel (PHP) yang melayani publikasi dan manajemen dokumen hukum. Laporan ini menganalisis arsitektur, alur permintaan, infrastruktur/deployment, kelemahan utama, observability, dan rekomendasi prioritas.

**Cakupan & Metodologi**
- Sumber dianalisis: `composer.json`, `package.json`, `routes/web.php`, controller/model utama (`DokumenController`, `ApiController`, `DokumenHukum`), semua file migrasi di `database/migrations/`, serta konfigurasi di `config/`.
- File referensi utama: [composer.json](composer.json), [package.json](package.json), [.env.example](.env.example), [routes/web.php](routes/web.php), [app/Http/Controllers/DokumenController.php](app/Http/Controllers/DokumenController.php), [app/Http/Controllers/ApiController.php](app/Http/Controllers/ApiController.php), [app/Models/DokumenHukum.php](app/Models/DokumenHukum.php), [database/migrations/](database/migrations/), [config/*.php](config/).

## 1. High-Level Architecture

- Pattern: Monolithic web application (single Laravel codebase that serves both public frontend and admin panel). No microservice boundaries detected in repo.
- Entry point: `public/index.php` → Laravel bootstrap → HTTP kernel → router → controller → model → DB. See [routes/web.php](routes/web.php) and [public/index.php](public/index.php).
- Tech stack: PHP 8.2, Laravel 11.9, Livewire 3.5, Filament 3.2, Spatie packages, Vite/Tailwind.

## 3. Infrastructure & Deployment (apa yang ditemukan)

- Tidak ada file containerisasi di repo: tidak ditemukan `Dockerfile`, `docker-compose.yml`, atau konfigurasi Kubernetes/Helm.
- Tidak ditemukan pipeline CI/CD di repo (tidak ada `.github/workflows/` atau file `.gitlab-ci.yml`).
- `composer.json` menyiapkan script `dev` yang menggunakan `php artisan serve` dan `php artisan queue:listen` (lihat `scripts.dev` pada `composer.json`).
- Health checks: paket `spatie/health` dikonfigurasi (`config/health.php`) — notifikasi aktif dan penyimpanan hasil disimpan ke DB.
- Logging: `config/logging.php` mendefinisikan beberapa channel lokal (single/daily, `ipaccess`, `visitor`, `security`) menulis ke `storage/logs/*.log`.
- Storage: default disk `local` (`public` root `storage/app/public`) — S3 disk dikonfigurasi tetapi tidak sepenuhnya dipakai.
- Queue: default `database` connection (lihat [config/queue.php](config/queue.php)).

## 6. Prioritized Remediation Plan (Infrastructure)

High (must-do soon)
- Add pagination to `ApiController` and other endpoints that return collections.
- Migrate static file storage to S3, serve via CDN, use signed URLs for private files.
- Introduce CI pipelines (unit tests, static analysis, security checks) and add Dockerfile + basic deployment manifest.
- Move queue backend to Redis and run workers under a process manager.
- Rate-limit public APIs and download endpoints.

---

## CHECKLIST VERIFIKASI (Infrastructure) — High → Low

### High
- [ ] Tambahkan pagination pada `ApiController` — **Not Done**
  - Evidence: `app/Http/Controllers/ApiController.php` melakukan `$dokumen = $query->get();` (kembalikan seluruh dataset). Rekomendasi: ganti ke `->paginate($perPage)` atau cursor pagination.
- [ ] Migrasi storage lampiran ke S3 + CDN — **Not Done**
  - Evidence: `config/filesystems.php` default `local` (`public` disk) dan kode memakai `Storage::disk('public')` pada banyak model/controller (lihat `DokumenController`, `DokumenHukum`).
- [ ] Containerization + CI/CD (Dockerfile + GitHub Actions) — **Not Found**
  - Evidence: tidak ada `Dockerfile`, `docker-compose.yml`, atau `.github/workflows` di repo. `composer.json` dev script menjalankan `php artisan serve` (dev-only).
- [ ] Pindahkan queue ke Redis dan jalankan worker dengan `queue:work`/Horizon — **Not Done**
  - Evidence: `config/queue.php` default `database`; `composer.json` dev script menggunakan `php artisan queue:listen`.
- [~] Rate-limit endpoint publik & download — **Partial**
  - Evidence: `routes/web.php` menerapkan `throttle:60,1` hanya pada `/metadata/api` (lihat `Route::middleware(['throttle:60,1'])->group(...)`). Disarankan menambahkan throttle untuk `download-lampiran` dan API lainnya.

### Medium
- [x] Health checks (Spatie) — **Done**
  - Evidence: `config/health.php` dikonfigurasi dan shims Filament Health tersedia.
- [ ] Centralize logs & APM — **Not Done**
  - Evidence: `config/logging.php` hanya lokal; tidak ada integrasi Sentry/Datadog/OpenTelemetry.

### Low
- [ ] Container readiness / Kubernetes probes / infra-as-code — **Not Done**

---

## Verifikasi singkat (ops)
- Saya memverifikasi: `routes/web.php` throttle, `app/Http/Controllers/ApiController.php` penggunaan `->get()`, `config/filesystems.php` default local, `config/queue.php` default database, serta tidak adanya `Dockerfile`/`.github/workflows`.
- Perintah pemeriksaan yang saya jalankan (lokal di repo): pencarian `throttle`, `Storage::disk(`, `RecordVisitorJob`, dan `queue:listen`.

Jika ingin, saya bisa langsung membuat salah satu PR infra: contoh `Dockerfile` + GitHub Actions CI minimal, atau patch pagination `ApiController`.

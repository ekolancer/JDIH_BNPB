# 📚 JDIH BNPB — Jaringan Dokumentasi dan Informasi Hukum

[![Laravel](https://img.shields.io/badge/Laravel-11.x-orange.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.2-blue.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)](https://php.net)

Sistem manajemen dokumen hukum untuk Badan Nasional Penanggulangan Bencana (BNPB) — publikasi, pencarian, dan manajemen konten melalui panel admin Filament.

---

## 📋 Daftar Isi

1. [Deskripsi Singkat](#deskripsi-singkat)
2. [Technology Stack & Dependensi Utama](#technology-stack--dependensi-utama)
3. [Fitur Utama](#fitur-utama)
4. [Struktur Ringkas Proyek](#struktur-ringkas-proyek)
5. [Panduan Developer (singkat)](#panduan-developer-singkat)
6. [Panel Admin (Filament)](#panel-admin-filament)
7. [API & Endpoints](#api--endpoints)
8. [Perintah Berguna](#perintah-berguna)
9. [Keamanan & Env Vars Penting](#keamanan--env-vars-penting)
10. [Troubleshooting Singkat](#troubleshooting-singkat)
11. [Dokumentasi Tambahan](#dokumentasi-tambahan)

---

## Deskripsi Singkat

JDIH BNPB adalah aplikasi untuk menyimpan, mencari, dan mendistribusikan dokumen hukum (peraturan, monografi, artikel, putusan). Menyediakan juga portal admin berbasis Filament untuk CRUD konten, impor Excel, manajemen pengguna, dan analytics.

## Technology Stack & Dependensi Utama

- Backend: Laravel 11 (lihat `composer.json`, requirement `laravel/framework` ^11.9)
- PHP: 8.2+
- Admin: Filament 3.2 (+ beberapa plugin terpasang seperti Filament Shield, Filament Google Analytics, Turnstile integration, edit-profile, auto-logout, filament-logger)
- Frontend interactivity: Livewire 3.5
- Assets: Vite, Tailwind CSS (Tailwind v3.x)
- Beberapa paket penting (lihat `composer.json`): `filament/filament`, `bezhansalleh/filament-shield`, `bezhansalleh/filament-google-analytics`, `afatmustafa/filamentv3-turnstile`, `livewire/livewire` dan lain-lain.
- Node tooling: `vite`, `tailwindcss`, `postcss` (lihat `package.json`).

## Fitur Utama

- Frontend publik:
  - Dashboard publik, daftar & pencarian dokumen (filter: tipe, tahun, subjek, bidang hukum).
  - Halaman detail dokumen dengan metadata, abstrak, dan lampiran.
  - Berita dan Infografis.
  - Survey / rating sederhana.

- API & Integrasi:
  - Endpoint rating dan metadata tersedia (beberapa endpoint didefinisikan langsung di `routes/web.php`, dengan throttle 60 req/menit).
  - Dokumentasi API ringkas tersedia di `APIDocs.md`.

- Panel Admin (Filament):
  - Resource CRUD untuk Dokumen, Berita, Infografis, FAQ, Profil Page, Ref tables, Users & Roles, Visitor logs.
  - Halaman health-check dan integrasi Google Analytics (plugin).

## Struktur Ringkas Proyek

Struktur utama (dipersingkat):

```
app/
  ├─ Filament/         # Resources, Pages, Auth (panel webmin)
  ├─ Http/Controllers/  # ApiController, DashboardController, DokumenController, BeritaController, InfografisController, SurveyController
  ├─ Http/Middleware/   # VisitorCounter, RestrictIpMiddleware
  ├─ Livewire/          # DokumenList, ListDocSearch, MediaFile, DropdownDokumen
  └─ Models/            # DokumenHukum, Berita, Infografis, ProfilPage, Faq, Visitor, Survey, Ref*

routes/
  └─ web.php            # Route utama termasuk beberapa endpoint API ringan

config/
public/
resources/views/
```

Beberapa Filament resources yang ada: `DokumenHukumResource`, `BeritaResource`, `InfografisResource`, `FaqResource`, `ProfilPageResource`, `UserResource`, `RoleResource`, `VisitorResource` (lihat `app/Filament/Resources`).

## Panduan Developer (singkat)

### Prasyarat

- PHP 8.2+, Composer 2+, Node 18+, MySQL/Postgres

### Instalasi singkat

```bash
git clone <repo>
cd JDIH_BNPB
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build   # atau `npm run dev` untuk development
```

### Jalankan (development)

```bash
php artisan serve
npm run dev
```

## Panel Admin (Filament)

- Panel admin diakses pada: `/webmin/login` (panel id `webmin`).
- Untuk generate permission & seeder: `php artisan shield:generate --panel=webmin --all`.
- Untuk membuat super-admin helper: `php artisan shield:super-admin`.
- Detail setup & troubleshooting panel: lihat `AdminPanelDocs.md`.

## API & Endpoints

- Dokumentasi endpoint singkat ada di `APIDocs.md`.
- Beberapa endpoint rating tersedia di route web (contoh: `/api/rating`, `/api/rating-average`, `/api/rating-summary`), dan ada route metadata yang dilindungi throttle: lihat `routes/web.php`.

## Perintah Berguna

- `php artisan serve` — jalankan server lokal
- `npm run dev` — jalankan vite dev server
- `php artisan migrate` — jalankan migrasi database
- `php artisan db:seed` — jalankan seeder
- `php artisan storage:link` — buat symlink storage
- `php artisan route:list` — lihat daftar route

## Keamanan & Env Vars Penting

- Gunakan `Storage::disk('public')->download()` untuk pengunduhan file.
- Pastikan policies/permissions dicek sebelum akses file.
- Var penting di `.env`:

```
APP_KEY=
DB_CONNECTION=
DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
TURNSTILE_SITE_KEY=    # optional
TURNSTILE_SECRET_KEY=  # optional
IPAPI_KEY=             # optional (geo-lookup)
```

## Troubleshooting Singkat

- Jika tidak bisa login di panel: cek `AdminPanelDocs.md` (guard, permission cache, Turnstile).
- Jika upload gagal: cek permission folder `storage/app/public`.
- Jika mendapat 429: rate-limit tercapai (tunggu 1 menit).

## Dokumentasi Tambahan

- `AdminPanelDocs.md` — panduan panel admin + troubleshooting
- `APIDocs.md` — dokumentasi endpoint API
- `ArchitecturDocs.md` — detail arsitektur & daftar route/panel
- `AuditCodeBase.md`, `AuditInfrastructure.md` — laporan audit

---

## Kontak & Lisensi

- Email: eko.lancer@gmail.com
- Issues: https://github.com/jdih-bnpb/jdih-bnpb/issues

Copyright © 2024 JDIH BNPB. All rights reserved.

**Terakhir Diperbarui**: 2026-04-07


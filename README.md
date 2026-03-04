# 📚 JDIH BNPB - Jaringan Dokumentasi dan Informasi Hukum

[![Laravel](https://img.shields.io/badge/Laravel-11.x-orange.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.2-blue.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4.svg)](https://php.net)

Sistem informasi manajemen dokumen hukum Badan Nasional Penanggulangan Bencana (BNPB) Indonesia.

---

## 📋 Daftar Isi

1. [Deskripsi Sistem](#1-deskripsi-sistem)
2. [Technology Stack](#2-technology-stack)
3. [Fitur Utama](#3-fitur-utama)
4. [Struktur Direktori](#4-struktur-direktori)
5. [Panduan Developer](#5-panduan-developer)
6. [Panduan Admin Panel](#6-panduan-admin-panel)
7. [API Reference](#7-api-reference)
8. [Perintah Berguna](#8-perintah-berguna)
9. [Keamanan](#9-keamanan)
10. [Troubleshooting](#10-troubleshooting)
11. [Dokumentasi Lanjutan](#11-dokumentasi-lanjutan)

---

## 1. Deskripsi Sistem

**JDIH BNPB** (Jaringan Dokumentasi dan Informasi Hukum Badan Nasional Penanggulangan Bencana) adalah sistem informasi yang dirancang untuk:

- **Mengelola** dan **menyimpan** dokumen hukum terkait penanggulangan bencana
- **Mendistribusikan** akses publik untuk mencari, melihat, dan mengunduh dokumen hukum
- **Menyediakan panel admin** untuk pengelolaan konten

### Tipe Dokumen yang Didukung
| Kode | Tipe Dokumen | Contoh |
|------|--------------|--------|
| Peraturan | Undang-Undang, Peraturan Pemerintah, Peraturan Presiden | UU 15/2024 |
| Monografi | Buku, Pedoman, Panduan | Pedoman Tanggap Bencana |
| Artikel | Artikel Hukum | Analisis Hukum Bencana |
| Putusan | Putusan Pengadilan | Putusan MA |

---

## 2. Technology Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Backend Framework | Laravel | 11.x |
| PHP | PHP | 8.2+ |
| Admin Panel | Filament | 3.2 |
| Frontend Components | Livewire | 3.5 |
| Role Management | Filament Shield | 3.3 |
| Database | MySQL/PostgreSQL | - |
| Styling | Tailwind CSS, Bootstrap | 5.x |
| File Import | Maatwebsite Excel | 3.1 |

---

## 3. Fitur Utama

### 3.1 Frontend Publik

| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik dokumen, peraturan terbaru, galeri infografis |
| **Pencarian Dokumen** | Filter berdasarkan tipe, tahun, judul, subjek, bidang hukum |
| **Detail Dokumen** | Metadata lengkap, download lampiran & abstrak |
| **Berita** | Berita terkait hukum |
| **Infografis** | Konten visual informasi hukum |
| **Survey** | Feedback kepuasan pengguna |
| **Statistik Pengunjung** | Counter kunjungan |

### 3.2 API Publik

- Endpoint: `/api`
- Rate Limiting: 60 requests/menit per IP
- Dokumentasi lengkap lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### 3.3 Panel Admin (Filament)

Akses: `/webmin/login`

| Modul | Fitur |
|-------|-------|
| **Dokumen Hukum** | CRUD, Import Excel, Upload file |
| **Berita** | CRUD, Upload gambar |
| **Infografis** | CRUD, Upload gambar |
| **FAQ** | Manajemen FAQ |
| **Profil Page** | Manajemen halaman profil |
| **User Management** | Kelola user & role |
| **Analytics** | Chart status peraturan, statistik |

### 3.4 Sistem Keamanan

- **Visitor Counter**: Tracking dengan geo-lookup, deteksi bot
- **Rate Limiting**: Terbatas pada API dan login
- **Role-Based Access**: Filament Shield untuk permissions
- **File Security**: Laravel Storage untuk download

---

## 4. Struktur Direktori

```
JDIH_BNPB/
├── app/
│   ├── Filament/           # Panel admin Filament
│   │   ├── Auth/          # Login, authentication
│   │   ├── Imports/       # Excel import handlers
│   │   ├── Pages/         # Custom pages
│   │   ├── Resources/     # Resource pages
│   │   └── Widgets/       # Widgets
│   ├── Http/
│   │   ├── Controllers/   # Web controllers
│   │   ├── Middleware/    # VisitorCounter, etc.
│   │   └── Resources/     # API resources
│   ├── Livewire/          # Komponen interaktif
│   │   ├── DokumenList.php
│   │   ├── ListDocSearch.php
│   │   ├── MediaFile.php
│   │   └── DropdownDokumen.php
│   ├── Models/            # Eloquent models
│   │   ├── DokumenHukum.php
│   │   ├── Berita.php
│   │   ├── Infografis.php
│   │   ├── Visitor.php
│   │   ├── Survey.php
│   │   └── Ref*.php       # Reference tables
│   ├── Observers/         # Model observers
│   │   ├── DokumenHukumObserver.php
│   │   ├── BeritaObserver.php
│   │   └── ...
│   └── Policies/          # Authorization policies
├── config/                # Configuration files
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── public/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   ├── img/              # Images
│   └── storage/          # Uploaded files (symlink)
├── resources/
│   ├── views/            # Blade templates
│   └── css/              # SCSS/CSS
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
└── storage/              # Logs, cache, uploads
```

---

## 5. Panduan Developer

### 5.1 Prerequisites

| Requirement | Minimum Version |
|-------------|-----------------|
| PHP | 8.2+ |
| Composer | 2.0+ |
| Node.js | 18+ |
| MySQL/PostgreSQL | 5.7+ |

### 5.2 Installation

```bash
# Clone repository
git clone <repository-url>
cd JDIH_BNPB

# Install dependencies
composer install
npm install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=jdih_bnpb
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Create storage symlink
php artisan storage:link

# Build assets
npm run build
```

### 5.3 Running the Application

```bash
# Development server
php artisan serve

# Atau menggunakan Laravel Valet / XAMPP / Laragon
# Akses: http://localhost:8000
```

### 5.4 Setup Admin User

```bash
# Generate permissions
php artisan shield:generate --panel=webmin --all

# Create super admin
php artisan shield:super-admin
```

Untuk panduan lengkap, lihat [ADMIN_PANEL_DOCUMENTATION.md](ADMIN_PANEL_DOCUMENTATION.md)

---

## 6. Panduan Admin Panel

### 6.1 Login

1. Buka `/webmin/login`
2. Masukkan email dan password
3. Jika menggunakan Cloudflare Turnstile, verifikasi captcha

### 6.2 Manajemen Dokumen

| Aksi | Cara |
|------|------|
| Tambah Dokumen | Dashboard → Dokumen Hukum → New |
| Edit Dokumen | Klik record → Edit |
| Hapus Dokumen | Pilih record → Delete |
| Import Excel | Dokumen Hukum → Import |

### 6.3 Upload File

- **Lampiran**: File PDF/DOC maksimal 50MB
- **Abstrak**: File PDF maksimal 10MB
- **Gambar**: JPG/PNG maksimal 5MB

### 6.4 Troubleshooting Login

Jika login gagal:

```bash
# Reset cache
php artisan optimize:clear
php artisan permission:cache-reset

# Cek user di tinker
php artisan tinker
```

```php
use App\Models\User;
$user = User::where('email', 'email@anda.com')->first();
$user->getRoleNames();
$user->getAllPermissions();
```

Untuk detail lengkap, lihat [ADMIN_PANEL_DOCUMENTATION.md](ADMIN_PANEL_DOCUMENTATION.md)

---

## 7. API Reference

### Base URL
```
https://jdih.bnpb.go.id/api
```

### Endpoint

```
GET /api
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `per_page` | int | No | Items per page (1-100, default: 15) |
| `page` | int | No | Page number |
| `tipeDok` | string | No | Filter: Peraturan, Monografi, Artikel, Putusan |
| `tahun` | int | No | Filter tahun |
| `judul` | string | No | Pencarian judul |
| `subjek` | string | No | Pencarian subjek |
| `bidangHukum` | string | No | Filter bidang hukum |
| `sort_by` | string | No | Sorting field |
| `sort_order` | string | No | asc/desc |

### Example Request

```bash
# Get regulations
curl "https://jdih.bnpb.go.id/api?tipeDok=Peraturan&per_page=10"

# Search by title
curl "https://jdih.bnpb.go.id/api?judul=bencana&tahun=2024"
```

### Response

```json
{
  "data": [...],
  "links": {...},
  "meta": {
    "current_page": 1,
    "total": 150,
    "per_page": 15
  }
}
```

Untuk dokumentasi lengkap, lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

---

## 8. Perintah Berguna

### Development

```bash
# Start development server
php artisan serve

# Watch assets
npm run dev

# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list
```

### Database

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seed
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback
```

### Admin & Security

```bash
# Generate permissions
php artisan shield:generate --panel=webmin --all

# Create super admin
php artisan shield:super-admin

# Reset permission cache
php artisan permission:cache-reset

# List users in tinker
php artisan tinker --execute="User::all()->each(fn(\$u) => print \$u->email . PHP_EOL);"
```

### File Management

```bash
# Create storage link
php artisan storage:link

# Clear log files
php artisan log:clear

# View latest logs
tail -f storage/logs/laravel.log
```

---

## 9. Keamanan

### Catatan Penting

1. **File Download**: Selalu gunakan `Storage::disk('public')->download()` untuk mencegah path traversal
2. **Authorization**: Pastikan policy dicek sebelum download
3. **Mass Assignment**: Gunakan `$fillable`/$guarded` di model
4. **Rate Limiting**: API dibatasi 60 req/menit per IP

### Environment Variables Penting

```env
# Required
APP_KEY=
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=jdih_bnpb
DB_USERNAME=root
DB_PASSWORD=

# Optional - Security
TURNSTILE_SITE_KEY=      # Untuk production
TURNSTILE_SECRET_KEY=    # Untuk production

# Optional - External Services
IPAPI_KEY=               # Untuk visitor geo-lookup
```

---

## 10. Troubleshooting

### Masalah Umum

| Masalah | Solusi |
|---------|--------|
| **Login admin gagal** | Cek [ADMIN_PANEL_DOCUMENTATION.md](ADMIN_PANEL_DOCUMENTATION.md) - pastikan Turnstile dinonaktifkan di lokal |
| **File upload gagal** | Cek permission folder `storage/app/public` |
| **API 429 Error** | Rate limit tercapai, tunggu 1 menit |
| **Slug conflict** | Generate ulang slug di model |
| **Geo-lookup failed** | Cek koneksi internet untuk IP lookup |

### Debug Mode

```env
# .env
APP_DEBUG=true
APP_ENV=local
```

> ⚠️ **Peringatan**: Janganaktifkan `APP_DEBUG` di production!

### Logs

```bash
# View application logs
tail -f storage/logs/laravel.log

# Visitor logs
tail -f storage/logs/visitor.log
```

---

## 11. Dokumentasi Lanjutan

| File | Deskripsi |
|------|-----------|
| [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) | Dokumentasi sistem lengkap |
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Referensi API lengkap |
| [ADMIN_PANEL_DOCUMENTATION.md](ADMIN_PANEL_DOCUMENTATION.md) | Panduan panel admin |
| [audit.md](audit.md) | Laporan audit teknis |

---

## 📞 Support

- **Email**: [eko.lancer@gmail.com](mailto:eko.lancer@gmail.com)
- **GitHub Issues**: [Repository Issues](https://github.com/jdih-bnpb/jdih-bnpb/issues)

---

## 📄 Lisensi

Copyright © 2024 JDIH BNPB. All rights reserved.

---

**Versi**: 1.0  
**Terakhir Diperbarui**: 2024


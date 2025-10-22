# Dokumentasi Sistem Aplikasi JDIH BNPB

## 1. Pendahuluan

### 1.1 Deskripsi Sistem
JDIH BNPB (Jaringan Dokumentasi dan Informasi Hukum Badan Nasional Penanggulangan Bencana) adalah sistem informasi yang dirancang untuk mengelola, menyimpan, dan mendistribusikan dokumen hukum terkait penanggulangan bencana di Indonesia. Sistem ini menyediakan akses publik untuk mencari, melihat, dan mengunduh dokumen hukum serta menyediakan panel admin untuk pengelolaan konten.

### 1.2 Tujuan Sistem
- Menyediakan repositori digital dokumen hukum BNPB
- Memfasilitasi akses publik terhadap informasi hukum
- Mengelola berbagai jenis dokumen hukum (Peraturan, Monografi, Artikel, Putusan)
- Menyediakan fitur pencarian dan filter dokumen
- Mengumpulkan feedback pengguna melalui survey
- Melacak statistik pengunjung dan performa sistem

## 2. Arsitektur Sistem

### 2.1 Teknologi Utama
- **Framework**: Laravel 11.x
- **PHP Version**: 8.2+
- **Database**: MySQL/PostgreSQL
- **Admin Panel**: Filament v3
- **Frontend**: Blade Templates dengan Bootstrap
- **JavaScript**: Livewire untuk interaktivitas
- **Styling**: Tailwind CSS, Bootstrap

### 2.2 Struktur Direktori Utama
```
/var/www/jdih_2024/
├── app/                    # Kode aplikasi utama
│   ├── Filament/          # Panel admin Filament
│   ├── Http/              # Controller, Middleware
│   ├── Livewire/          # Komponen Livewire
│   ├── Models/            # Model Eloquent
│   └── Observers/         # Model Observers
├── database/              # Migrasi dan seeders
├── public/                # Asset publik
├── resources/             # Views dan asset
├── routes/                # Definisi routing
└── storage/               # File upload dan cache
```

## 3. Model Data

### 3.1 DokumenHukum
Model utama untuk mengelola semua jenis dokumen hukum.

**Atribut Utama:**
- `tipeDok`: Enum (Peraturan, Monografi, Artikel, Putusan)
- `judul`: Judul dokumen
- `slug`: URL-friendly identifier
- `tahun`: Tahun terbit
- `nomor`: Nomor dokumen (untuk Peraturan/Putusan)
- `lampiran`: Path file dokumen
- `abstrak`: Path file abstrak
- `view_count`: Jumlah dilihat
- `download_count`: Jumlah diunduh

**Fitur Khusus:**
- Auto-generate slug berdasarkan tipe dokumen
- Auto-delete file saat record dihapus
- Metadata dinamis berdasarkan tipe dokumen
- Accessor untuk ukuran file

### 3.2 Berita
Model untuk mengelola berita terkait hukum.

**Atribut Utama:**
- `judul`, `slug`, `konten`
- `penulis`, `kategori`
- `gambar`, `tag`
- `status`: Boolean (Draft/Publikasi)
- `view_count`

### 3.3 Infografis
Model untuk konten visual informasi hukum.

**Atribut Utama:**
- `judul`, `slug`
- `gambar`: Path file infografis
- `tag`: Array tag
- `status`: Boolean aktif/tidak
- `view_count`

### 3.4 Visitor
Model untuk tracking pengunjung.

**Atribut Utama:**
- `ip_address`, `user_agent`
- `path`, `referrer`
- `country`, `city`
- `suspicious`: Flag aktivitas mencurigakan
- `visited_at`

### 3.5 Survey
Model untuk mengumpulkan feedback pengguna.

**Atribut Utama:**
- `rating`: Integer 1-5

## 4. Fitur Utama

### 4.1 Frontend Publik

#### Dashboard Utama
- Statistik jumlah dokumen per kategori
- Highlight peraturan terbaru dan populer
- Galeri infografis dengan swiper
- Berita hukum terbaru
- Statistik pengunjung
- Survey kepuasan pengguna

#### Pencarian Dokumen
- Filter berdasarkan: tipe, tahun, judul, subjek, bidang hukum
- Sorting: terbaru, populer
- Pagination
- Live search dengan Livewire

#### Detail Dokumen
- Metadata lengkap berdasarkan tipe dokumen
- Download lampiran dan abstrak
- Counter view otomatis
- Tombol aksi dinamis (Baca/Cover)

### 4.2 API Publik
Endpoint utama: `/api`

**Parameter:**
- `per_page`: Jumlah item per halaman (1-100)
- `page`: Nomor halaman
- `tipeDok`: Filter tipe dokumen
- `tahun`: Filter tahun
- `judul`: Pencarian judul
- `subjek`: Pencarian subjek
- `bidangHukum`: Filter bidang hukum
- `sort_by`: Field sorting
- `sort_order`: Urutan sorting

**Rate Limiting:** 60 requests per menit per IP

### 4.3 Panel Admin (Filament)

#### Manajemen Dokumen
- CRUD dokumen hukum
- Upload file lampiran dan abstrak
- Import dari Excel
- Bulk operations

#### Manajemen Konten
- CRUD Berita
- CRUD Infografis
- Manajemen User dan Role

#### Analytics
- Chart status peraturan (Berlaku/Tidak Berlaku)
- Statistik pengunjung
- Activity logging

### 4.4 Sistem Keamanan

#### Middleware VisitorCounter
- Tracking pengunjung dengan geo-lookup
- Deteksi bot dan aktivitas mencurigakan
- Rate limiting per IP per hari
- Filter hanya pengunjung Indonesia

#### Authentication
- Filament Shield untuk role-based access
- Multi-guard authentication
- Session management

## 5. Database Schema

### 5.1 Tabel Utama

#### dokumen_hukums
```sql
- id (BIGINT, PRIMARY KEY)
- tipeDok (ENUM: Peraturan, Monografi, Artikel, Putusan)
- judul (TEXT)
- slug (VARCHAR, UNIQUE)
- teuBadan (VARCHAR)
- nomor (INTEGER, NULLABLE)
- tahun (INTEGER)
- singkatan (VARCHAR)
- tempat (VARCHAR)
- penerbit (VARCHAR)
- tglPenetapan (DATE, NULLABLE)
- tglPengundangan (DATE, NULLABLE)
- tglDiBacakan (DATE, NULLABLE)
- nomorPanggil (INTEGER, NULLABLE)
- cetakanEdisi (VARCHAR)
- deskripsiFisik (VARCHAR)
- subjek (TEXT)
- sumber (VARCHAR)
- isbn (VARCHAR)
- status (ENUM: BERLAKU, TIDAK BERLAKU, NULLABLE)
- bahasa (VARCHAR)
- lokasi (VARCHAR)
- bidangHukum (VARCHAR)
- lampiran (VARCHAR)
- abstrak (VARCHAR, NULLABLE)
- nomorIndukBuku (INTEGER, NULLABLE)
- penandatangan (VARCHAR)
- view_count (BIGINT, DEFAULT 0)
- download_count (BIGINT, DEFAULT 0)
- timestamps
```

#### berita
```sql
- id (BIGINT, PRIMARY KEY)
- judul (VARCHAR)
- slug (VARCHAR, UNIQUE)
- konten (TEXT)
- penulis (VARCHAR, NULLABLE)
- kategori (VARCHAR, NULLABLE)
- gambar (VARCHAR, NULLABLE)
- tanggal_publikasi (TIMESTAMP, NULLABLE)
- status (BOOLEAN, DEFAULT FALSE)
- tag (JSON, NULLABLE)
- view_count (BIGINT, DEFAULT 0)
- timestamps
```

#### infografis
```sql
- id (BIGINT, PRIMARY KEY)
- judul (VARCHAR)
- slug (VARCHAR, UNIQUE)
- tag (JSON, NULLABLE)
- gambar (VARCHAR)
- status (BOOLEAN, DEFAULT FALSE)
- view_count (BIGINT, DEFAULT 0)
- timestamps
```

#### visitors
```sql
- id (BIGINT, PRIMARY KEY)
- ip_address (VARCHAR)
- user_agent (TEXT, NULLABLE)
- visited_at (TIMESTAMP)
- path (VARCHAR, NULLABLE)
- referrer (VARCHAR, NULLABLE)
- suspicious (BOOLEAN, DEFAULT FALSE)
- country (VARCHAR, NULLABLE)
- city (VARCHAR, NULLABLE)
- timestamps
```

#### surveys
```sql
- id (BIGINT, PRIMARY KEY)
- rating (INTEGER)
- timestamps
```

## 6. API Endpoints

### 6.1 Public Routes

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET | `/` | DashboardController@index | Halaman utama |
| GET | `/dokumen` | - | Halaman list dokumen |
| GET | `/dokumen/{tipeDok}/{slug}` | DokumenController@show | Detail dokumen |
| GET | `/download-lampiran/{slug}` | DokumenController@unduhLampiran | Download lampiran |
| GET | `/download-abstrak/{slug}` | DokumenController@unduhAbstrak | Download abstrak |
| GET | `/berita` | BeritaController@index | List berita |
| GET | `/berita/{slug}` | BeritaController@show | Detail berita |
| GET | `/infografis` | InfografisController@index | List infografis |
| GET | `/infografis/{slug}` | InfografisController@show | Detail infografis |
| POST | `/` | SurveyController@store | Submit survey |
| GET | `/api/rating` | SurveyController@getRatingDistribution | Distribusi rating |
| GET | `/api/rating-average` | SurveyController@getAverageRating | Rata-rata rating |
| GET | `/api/rating-summary` | SurveyController@getRatingSummary | Summary rating |
| GET | `/api` | ApiController@index | API dokumen |

### 6.2 Admin Routes (Filament)
- `/webmin/*` - Panel admin Filament
- Managed by Filament automatically

## 7. Komponen Livewire

### 7.1 DokumenList
- Komponen utama untuk list dan filter dokumen
- Fitur: pagination, sorting, filtering real-time
- Cache filter options selama 30 menit

### 7.2 ListDocSearch
- Komponen pencarian dokumen (legacy)
- Digunakan untuk filter advanced

### 7.3 MediaFile
- Komponen untuk preview file dokumen
- Mendukung berbagai format file

### 7.4 DropdownDokumen
- Komponen dropdown selector tipe dokumen

## 8. Middleware

### 8.1 VisitorCounter
**Fungsi:**
- Track pengunjung unik per hari per IP+user_agent
- Geo-lookup menggunakan ipapi.co dan ipwho.is sebagai fallback
- Deteksi bot menggunakan CrawlerDetect
- Filter hanya pengunjung Indonesia
- Flag aktivitas mencurigakan

**Konfigurasi:**
- Rate limit: 1 record per hari per kombinasi IP+UA
- Skip routes: `/webmin/*`, `/api/*`
- Local IP detection untuk development

## 9. Observers

### 9.1 DokumenHukumObserver
- Auto-generate slug saat creating/updating
- Delete file lampiran/abstrak saat deleting record

### 9.2 BeritaObserver
- Auto-generate slug
- Delete file gambar saat deleting

### 9.3 InfografisObserver
- Auto-generate slug
- Delete file gambar saat deleting

## 10. Import/Export

### 10.1 DokumenHukumsImport
- Import dari Excel menggunakan Maatwebsite/Excel
- Auto-generate slug berdasarkan tipe dokumen
- Mapping kolom Excel ke database fields

**Format Excel yang didukung:**
- tipe_dok, jenis_dok, judul, teu_badan, nomor, tahun, dll.

## 11. Konfigurasi Sistem

### 11.1 Environment Variables


### 11.2 File Storage
- **Disk**: `public` untuk file upload
- **Path**: `storage/app/public/`
- **URL**: `/storage/` (symlinked)

### 11.3 Cache Configuration
- **Driver**: File cache untuk production
- **TTL**: 30 menit untuk filter options
- **Tags**: Digunakan untuk cache invalidation

## 12. Dependencies Utama

### 12.1 Composer Packages
```json
{
    "laravel/framework": "^11.9",
    "filament/filament": "^3.2",
    "bezhansalleh/filament-shield": "^3.3",
    "maatwebsite/excel": "^3.1",
    "livewire/livewire": "^3.5",
    "spatie/laravel-sluggable": "^3.6",
    "torann/geoip": "^3.0",
    "jaybizzle/crawler-detect": "^1.3",
    "flowframe/laravel-trend": "^0.3.0"
}
```

### 12.2 NPM Packages
```json
{
    "bootstrap": "^5.x",
    "jquery": "^3.x",
    "swiper": "^8.x",
    "chart.js": "^4.x"
}
```

## 13. Deployment

### 13.1 Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web server (Apache/Nginx)

### 13.2 Deployment Steps
1. Clone repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate app key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Install NPM packages: `npm install`
9. Build assets: `npm run build`
10. Create storage symlink: `php artisan storage:link`
11. Set proper permissions
12. Configure web server

### 13.3 Production Optimizations
- Enable OPcache
- Configure Redis untuk cache/session (opsional)
- Setup queue worker untuk background jobs
- Enable HTTPS
- Configure backup database
- Setup monitoring (Laravel Telescope/Horizon)

## 14. Monitoring & Maintenance

### 14.1 Logging
- Laravel default logging ke `storage/logs/`
- Custom logging untuk API errors
- Visitor logging untuk analytics

### 14.2 Health Checks
- Database connection
- File storage accessibility
- External API dependencies (ipapi.co)

### 14.3 Backup Strategy
- Database: Daily backup
- File uploads: Weekly backup
- Codebase: Version controlled

### 14.4 Performance Monitoring
- Response times
- Database query optimization
- Cache hit rates
- Visitor analytics

## 15. Keamanan

### 15.1 Input Validation
- Comprehensive validation di semua form
- Rate limiting pada API
- CSRF protection
- XSS prevention

### 15.2 File Upload Security
- File type validation
- Size limits
- Secure file naming
- Storage isolation

### 15.3 Access Control
- Role-based permissions via Filament Shield
- Route protection
- Admin area isolation

## 16. Troubleshooting

### 16.1 Common Issues

#### File Upload Gagal
- Check storage permissions
- Verify disk configuration
- Check file size limits

#### API Rate Limited
- Check middleware configuration
- Verify IP detection
- Review rate limiting rules

#### Slug Conflicts
- Check unique constraints
- Review slug generation logic
- Manual slug adjustment

#### Geo-lookup Failed
- Check internet connection
- Verify API keys (if any)
- Review fallback mechanisms

### 16.2 Debug Mode
- Enable APP_DEBUG=true untuk development
- Check Laravel logs
- Use Laravel Telescope untuk debugging

## 17. Future Enhancements

### 17.1 Planned Features
- Full-text search dengan Elasticsearch
- Document versioning
- Advanced analytics dashboard
- Mobile app API
- Integration dengan sistem eksternal
- Multi-language support
- Document OCR untuk searchable PDF

### 17.2 Scalability Improvements
- Database sharding
- CDN untuk file storage
- Microservices architecture
- API gateway
- Load balancing

---

**Versi Dokumentasi:** 1.0
**Tanggal:** Desember 2024
**Pengembang:** Tim IT JDIH BNPB

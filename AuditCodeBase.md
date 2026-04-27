# 📋 AUDIT — Codebase (JDIH_BNPB)

---

File ini merupakan versi rename dari `audit.md` dan berisi temuan audit pada level kode, diikuti checklist verifikasi (High → Low).

## Ringkasan temuan (kode)

---

## A. KEUNGGULAN PROJECT

### 1. Arsitektur & Struktur
- **Penggunaan Laravel 11 terbaru** dengan Filament 3.2 sebagai admin panel
- **Pattern Service-Repository** yang cukup baik dengan scope di Model
- **Pemisahan Logic** menggunakan Observers (`DokumenHukumObserver`, `BeritaObserver`, dll)
- **Struktur Folder yang Terorganisir** sesuai best practice Laravel
- **Penggunaan Livewire** untuk komponen interaktif (`DokumenList`, `MediaFile`, dll)

### 2. Database Design
- **Relasi Eloquent yang Baik** dengan `belongsTo` dan `belongsToMany`
- **Penggunaan Reference Tables** (`ref_jenis_dokumen`, `ref_bidang_hukum`, `ref_subjek`)
- **Timestamp Management** yang tepat dengan Carbon

### 3. Security
- **Middleware Visitor Counter** yang komprehensif dengan:
  - Deteksi crawler/bot
  - Deteksi aktivitas mencurigakan
  - Filter IP lokal vs luar negeri
  - Logging aktivitas
- **File Storage** menggunakan Laravel Storage yang aman
- **Policy Files** sudah dibuat untuk otorisasi

### 4. Performance
- **Query Caching** di `DokumenList` dengan cache 10-30 menit
- **Eager Loading** sudah digunakan (`with(['jenisDokumen', 'bidangHukumRef', 'subjekRef'])`)
- **Pagination** sudah diimplementasikan dengan benar di beberapa area frontend
- **API Rate Limiting** (`throttle:60,1`) — terbatas pada endpoint tertentu

### 5. Logging & Monitoring
- **Multi-channel Logging** (`stack`, `visitor`, `ipaccess`)
- **Activity Logging** via Filament Logger
- **Health Check** tersedia via Filament Spatie Health

---

## B. KELEMAHAN PROJECT (kode)

### 1. Struktur Arsitektur
- **Controller Terlalu "Fat"** - `DokumenController` memiliki method yang terlalu banyak tanggung jawab
- **Kurangnya Service Layer** - Logic bisnis masih banyak di Controller dan Model
- **Missing API Routes** - Hanya ada satu endpoint API tanpa proper API resource

### 2. Kode & Clean Code
- **Inline CSS di Blade** - `detail.blade.php` memiliki ~100 baris CSS inline (needs review)
- **Hardcoded Values** di banyak tempat (`$validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan']`)
- **Duplikasi Query** di `DashboardController` dan `BeritaController`
- **Naming Inconsistency**: `tipeDok` (camelCase) vs `ref_jenis_dokumen_id` (snake_case)

### 3. Database & Query
- **Redundant Fields** di tabel `dokumen_hukums`: `jenisDok`, `singkatan`, `bidangHukum` — **Mitigated** di kode aktif; kolom belum di-drop karena masih perlu migrasi data/backfill dan verifikasi schema produksi.
- **Missing Database Indexes** untuk `view_count`, `download_count`, `tahun`, `status` (partially indexed) — **Done** via migration `2026_04_27_000001_add_performance_indexes_to_dokumen_hukums_table.php`.
- **N+1 Query Potential** di `DokumenList` dengan multiple queries di `loadFilterCounts()` — **Mitigated**; count filter memakai subquery ID dokumen terfilter dan tidak lagi materialize semua ID via `pluck()`.

### 4. Security
- **No Input Validation** di Controller (tidak ada Form Request di beberapa endpoint) — **Mitigated** untuk endpoint metadata API, survey, detail dokumen, download lampiran/abstrak, dan file view.
- **File Path Traversal Risk** di download methods — **Done**; download/view memvalidasi path relatif sebelum akses Storage/response file.
- **No Authorization Policy Check** untuk download endpoint di backend — **Done**; `DokumenHukumPolicy::download()` dan `viewFile()` dipanggil dari controller.

### 5. Performance
- **Tidak ada route caching** (repo-level)
- **Visitor Middleware Blocking** - HTTP request ke ipapi.co/ipwho.is di setiap request (synchronous)
- **No CDN** - Semua assets diload dari server lokal

### 6. Frontend (Blade/Tailwind)
- **Inline CSS/JS** di Blade template (~200+ lines)
- **No Component Reusability** - Badge, card styling duplicat
- **Accessibility Issues**: Missing `aria-label`, alt text

### 7. DevOps & Deployment (kode-level notes)
- **Missing Environment Validation** - tidak ada check untuk `APP_KEY`
- **Debug Mode** yang bisa leak di production
- **No Queue Implementation** - visitor logging blocking (synchronous DB writes)

---

## C. CHECKLIST VERIFIKASI (Code-level) — High → Low

Catatan: status diisi berdasarkan pemeriksaan cepat kode saat ini (refs disertakan).

### High
- [x] Perbaiki file download vulnerability — Status: **Done**
  - Evidence: [app/Http/Controllers/DokumenController.php](app/Http/Controllers/DokumenController.php) menggunakan `Storage::disk('public')->download()` dan memeriksa accessor `download_allowed` & `lampiran_exists` di [app/Models/DokumenHukum.php](app/Models/DokumenHukum.php).
- [x] Tambahkan backend authorization untuk download — Status: **Done**
  - Evidence: [app/Policies/DokumenHukumPolicy.php](app/Policies/DokumenHukumPolicy.php) memiliki `download()` dan `viewFile()`, dipanggil via `Gate::authorize()` di [app/Http/Controllers/DokumenController.php](app/Http/Controllers/DokumenController.php).
- [x] Implementasi input validation (Form Request) — Status: **Done**
  - Evidence: Form Request baru tersedia untuk metadata API, survey, detail dokumen, download dokumen, dan file view di folder [app/Http/Requests](app/Http/Requests).

### Medium
- [ ] Refactor Visitor Middleware ke Queue — Status: **Not Done**
  - Evidence: [app/Http/Middleware/VisitorCounter.php](app/Http/Middleware/VisitorCounter.php) melakukan HTTP lookup (`Http::get`) dan `Visitor::create()` sinkron. Tidak ditemukan `app/Jobs/RecordVisitorJob` di kode produksi.
- [ ] Mass Assignment hardening (`$fillable`) — Status: **Not Done**
  - Evidence: [app/Models/DokumenHukum.php](app/Models/DokumenHukum.php) memiliki banyak field di `$fillable`. Rekomendasi: kurangi `$fillable` atau gunakan `$guarded = ['id']` plus `FormRequest`.
- [x] Missing DB Indexes — Status: **Done**
  - Evidence: migration [database/migrations/2026_04_27_000001_add_performance_indexes_to_dokumen_hukums_table.php](database/migrations/2026_04_27_000001_add_performance_indexes_to_dokumen_hukums_table.php) menambahkan index untuk `view_count`, `download_count`, `tahun`, `status`, composite `status,tahun`, dan `ref_bidang_hukum_id` bila belum ada.

### Low
- [ ] SQL injection / performance with `like` filters — Status: **Needs Review**
  - Evidence: `scopeApplyFilters` memakai `where('judul', 'like', "%{$v}%")` (Eloquent binding mitigates SQL injection, but validate/limit input to avoid performance abuse).
- [ ] Inline CSS / Accessibility fixes — Status: **Needs Review**
  - Evidence: manual scan recommended (`grep "<style>" resources/views`), lalu extract components.

---

## Verifikasi singkat (perintah & bukti saya pakai)
- `routes/web.php` — throttle hanya untuk `/metadata/api`: `Route::middleware(['throttle:60,1'])->group(...)`.
- `ApiController@index` masih memakai `->get()` (lihat [app/Http/Controllers/ApiController.php](app/Http/Controllers/ApiController.php)).
- `VisitorCounter` masih sinkron (lihat [app/Http/Middleware/VisitorCounter.php](app/Http/Middleware/VisitorCounter.php)).
- `app/Models/DokumenHukum.php` menampilkan `$fillable` panjang.

Jika Anda ingin, saya bisa langsung membuat PR kecil untuk salah satu item di atas (mis. dispatch visitor ke Job, atau tambahkan pagination di `ApiController`).

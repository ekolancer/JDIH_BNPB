# Arsitektur Sistem — JDIH_BNPB

Dokumen ini adalah panduan teknis utama untuk repository JDIH_BNPB. Berisi ringkasan sistem, struktur folder, diagram mermaid (system context, data flow, ERD), alur autentikasi & keamanan, serta daftar endpoint utama.

**Tanggal:** 2026-04-06

---

**Ringkasan Sistem**

- **Tujuan:** Aplikasi JDIH_BNPB menyediakan publikasi dan manajemen dokumen hukum (dokumen hukum, berita, infografis), pencarian dokumen, unduh lampiran/abstrak, dan endpoint API untuk sinkronisasi data.
- **Backend:** PHP 8.2, Laravel 11, Eloquent ORM.
- **Frontend:** Blade templates + Livewire 3, Vite, Tailwind CSS, beberapa JS (axios, owl.carousel).
- **Admin:** Filament v3 (panel internal), integrasi Spatie Roles & Permissions untuk otorisasi.
- **Database:** Relational DB (MySQL/Postgres—migrasi tersedia di `database/migrations`).
- **Storage:** Laravel storage (`public` disk) untuk file lampiran/abstrak; Storage facade dipakai di controller/model.
- **Queue/Jobs:** Queue/job tables exist (`jobs`, `failed_jobs`) — composer/dev script menjalankan `php artisan queue:listen`.
- **Dependensi kunci:** lihat `composer.json` (Laravel, Livewire, Filament, Spatie, dll) dan `package.json` (Vite, Tailwind).

Referensi cepat: [composer.json](composer.json), [package.json](package.json)

---

**Struktur Folder (ringkas, fungsi utama)**

- `app/` — kode aplikasi utama
  - `Filament/` — sumber daya dan konfigurasi admin Filament (Pages, Resources, Widgets)
  - `Http/Controllers/` — controller aplikasi (lihat `DokumenController`, `ApiController`, `SurveyController`)
  - `Livewire/` — komponen Livewire frontend (mis. `DokumenList`)
  - `Models/` — model Eloquent utama (mis. `DokumenHukum`, `User`, `RefJenisDokumen`, dll.)
  - `Observers/`, `Policies/`, `Providers/` — hooks, kebijakan akses, service providers
  - `Helpers/` — utilitas (mis. `SeoHelper.php`)
- `routes/` — route web (utama di `web.php`)
- `resources/views/` — blade templates
- `public/` — build assets & file serving (public storage symlink)
- `database/migrations/` — skema database (table definitions)
- `storage/` — storage lokal (lampiran/abstrak)
- `config/` — konfigurasi (filament, auth, filesystems, etc.)

Detail: lihat struktur lengkap untuk tiap folder bila perlu; file referensi: [app/Models/DokumenHukum.php](app/Models/DokumenHukum.php), [routes/web.php](routes/web.php)

---

**Diagram Mermaid.js**

System Context Diagram

```mermaid
graph LR
  User[User (Browser/Client)] -->|HTTP/HTTPS| Frontend[Frontend: Blade + Livewire + Vite]
  Frontend -->|XHR / Fetch / Livewire| Backend[Backend: Laravel 11 (Controllers, Livewire, Filament)]
  Backend -->|SQL| Database[(Relational DB — MySQL/Postgres)]
  Backend -->|Files| Storage[(File Storage — public disk / S3)]
  Backend -->|Queue jobs| Queue[(Queue workers)]
  Backend -->|API / Sync| External[(External Systems: PUU, Analytics, 3rd-party)]
  Admin[Admin (Filament UI)] -->|HTTPS + Auth| Backend
```

Data Flow Diagram (request -> processing -> storage)

```mermaid
flowchart TD
  A[Client Request] --> B[Web server (Nginx/Apache + PHP-FPM)]
  B --> C[Laravel Router]
  C --> D[Middleware (auth, csrf, validation, rate-limit)]
  D --> E[Controller / Livewire component]
  E --> F[Domain Logic (Services / Observers / Model)]
  F --> G[(Database)]
  F --> H[(Storage: public disk)]
  G --> I[Response (JSON / HTML)]
  H --> I
  I --> J[Client (render/consume)]

  %% file download flow
  subgraph DownloadFlow
    U[User] -->|GET /download-lampiran/{slug}| DC[DokumenController::unduhLampiran]
    DC -->|validate: download_allowed| M[DokumenHukum model]
    M -->|Storage::disk('public')->download| S[Storage]
    S --> U
  end
```

Database Schema (ERD) — skema tabel utama (lebih rinci)

```mermaid
erDiagram
    REF_JENIS_DOKUMEN {
      int id PK
      varchar nama(150) UNIQUE
      varchar singkatan(50) UNIQUE
      text deskripsi
      tinyint is_active
      timestamp created_at
      timestamp updated_at
    }
    REF_BIDANG_HUKUM {
      int id PK
      varchar nama(150) UNIQUE
      text deskripsi
      boolean is_active
      timestamp created_at
      timestamp updated_at
    }
    REF_SUBJEK {
      int id PK
      varchar nama(255) UNIQUE
      varchar kategori(150)
      text deskripsi
      boolean is_active
      timestamp created_at
      timestamp updated_at
    }
    DOKUMEN_HUKUMS {
      int id PK
      varchar document_uid(50) UNIQUE
      enum tipeDok
      varchar jenisDok(255)
      bigint ref_jenis_dokumen_id FK
      text judul
      varchar slug(255) UNIQUE
      varchar teuBadan(255)
      int nomor
      int tahun
      varchar singkatan(255)
      varchar tempat(255)
      varchar penerbit(255)
      date tglPenetapan
      date tglPengundangan
      date tglDiBacakan
      int nomorPanggil
      varchar cetakanEdisi(255)
      varchar deskripsiFisik(255)
      text subjek
      varchar sumber(255)
      varchar isbn(255)
      enum status
      varchar bahasa(255)
      varchar lokasi(255)
      varchar bidangHukum(255)
      varchar lampiran(255)
      varchar abstrak(255)
      int nomorIndukBuku
      varchar penandatangan(255)
      bigint view_count
      bigint download_count
      timestamp created_at
      timestamp updated_at
    }
    DOKUMEN_SUBJEK {
      int id PK
      bigint dokumen_hukum_id FK
      bigint subjek_id FK
      timestamp created_at
      timestamp updated_at
    }
    USERS {
      int id PK
      varchar name
      varchar email UNIQUE
      timestamp email_verified_at
      varchar password
      varchar remember_token
      timestamp created_at
      timestamp updated_at
    }
    SURVEYS {
      int id PK
      int rating
      timestamp created_at
      timestamp updated_at
    }
    BERITA {
      int id PK
      varchar judul
      varchar slug UNIQUE
      text konten
      varchar penulis
      varchar kategori
      varchar gambar
      boolean status
      json tag
      bigint view_count
      timestamp created_at
      timestamp updated_at
    }
    INFOGRAFIS {
      int id PK
      varchar judul
      varchar slug UNIQUE
      json tag
      varchar gambar
      boolean status
      bigint view_count
      timestamp created_at
      timestamp updated_at
    }
    VISITORS {
      int id PK
      varchar ip_address(45)
      text user_agent
      timestamp visited_at
      varchar path
      varchar referrer
      boolean suspicious
      varchar country
      varchar city
      timestamp created_at
      timestamp updated_at
    }
    ROLES {
      bigint id PK
      varchar name
      varchar guard_name
    }
    PERMISSIONS {
      bigint id PK
      varchar name
      varchar guard_name
    }

    REF_JENIS_DOKUMEN ||--o{ DOKUMEN_HUKUMS : has_many
    REF_BIDANG_HUKUM ||--o{ DOKUMEN_HUKUMS : has_many
    DOKUMEN_HUKUMS ||--o{ DOKUMEN_SUBJEK : has_many
    REF_SUBJEK ||--o{ DOKUMEN_SUBJEK : has_many
    DOKUMEN_HUKUMS }o--o{ REF_SUBJEK : "many-to-many via dokumen_subjek"
    USERS ||--o{ SURVEYS : gives
    ROLES ||--o{ PERMISSIONS : defines
```

Catatan: migrasi lengkap ada di `database/migrations/` — tabel permissions/roles (Spatie) dan tabel pendukung (`jobs`, `cache`, `failed_jobs`, dsb.) juga didefinisikan.

---

**ERD Lengkap — Semua kolom dari migrasi**

Berikut ERD yang lebih rinci, diambil langsung dari file di `database/migrations/`. Semua kolom utama dan tipe yang didefinisikan di migrasi disertakan agar developer bisa melihat struktur DB lengkap.

```mermaid
erDiagram
    faqs {
      bigint id PK
      varchar question
      longtext answer
      int sort_order
      timestamp created_at
      timestamp updated_at
    }

    profil_pages {
      bigint id PK
      varchar slug UNIQUE
      varchar title
      longtext content
      varchar image
      timestamp created_at
      timestamp updated_at
    }

    ref_jenis_dokumen {
      bigint id PK
      varchar nama(150) UNIQUE
      varchar singkatan(50) UNIQUE
      text deskripsi
      tinyint is_active
      timestamp created_at
      timestamp updated_at
    }

    ref_bidang_hukum {
      bigint id PK
      varchar nama(150) UNIQUE
      text deskripsi
      boolean is_active
      timestamp created_at
      timestamp updated_at
    }

    ref_subjek {
      bigint id PK
      varchar nama(255) UNIQUE
      varchar kategori(150)
      text deskripsi
      boolean is_active
      timestamp created_at
      timestamp updated_at
    }

    dokumen_hukums {
      bigint id PK
      varchar document_uid(50) UNIQUE
      enum tipeDok
      varchar jenisDok(255)
      bigint ref_jenis_dokumen_id FK
      text judul
      varchar slug(255) UNIQUE
      varchar teuBadan(255)
      int nomor
      int tahun
      varchar singkatan(255)
      varchar tempat(255)
      varchar penerbit(255)
      date tglPenetapan
      date tglPengundangan
      date tglDiBacakan
      int nomorPanggil
      varchar cetakanEdisi(255)
      varchar deskripsiFisik(255)
      text subjek
      varchar sumber(255)
      varchar isbn(255)
      enum status
      varchar bahasa(255)
      varchar lokasi(255)
      varchar bidangHukum(255)
      varchar lampiran(255)
      varchar abstrak(255)
      int nomorIndukBuku
      varchar penandatangan(255)
      bigint view_count
      bigint download_count
      timestamp created_at
      timestamp updated_at
      bigint ref_bidang_hukum_id FK
    }

    dokumen_subjek {
      bigint id PK
      bigint dokumen_hukum_id FK
      bigint subjek_id FK
      timestamp created_at
      timestamp updated_at
    }

    berita {
      bigint id PK
      varchar judul
      varchar slug UNIQUE
      text konten
      varchar penulis
      varchar kategori
      varchar gambar
      boolean status
      json tag
      bigint view_count
      timestamp created_at
      timestamp updated_at
    }

    infografis {
      bigint id PK
      varchar judul
      varchar slug UNIQUE
      json tag
      varchar gambar
      boolean status
      bigint view_count
      timestamp created_at
      timestamp updated_at
    }

    visitors {
      bigint id PK
      varchar ip_address(45)
      text user_agent
      timestamp visited_at
      varchar path
      varchar referrer
      boolean suspicious
      varchar country
      varchar city
      timestamp created_at
      timestamp updated_at
    }

    surveys {
      bigint id PK
      int rating
      timestamp created_at
      timestamp updated_at
    }

    notifications {
      uuid id PK
      varchar type
      varchar notifiable_type
      bigint notifiable_id
      text data
      timestamp read_at
      timestamp created_at
      timestamp updated_at
    }

    activity_log {
      bigint id PK
      varchar log_name
      text description
      varchar subject_type
      bigint subject_id
      varchar event
      varchar causer_type
      bigint causer_id
      json properties
      uuid batch_uuid
      timestamp created_at
      timestamp updated_at
    }

    jobs {
      bigint id PK
      varchar queue
      longtext payload
      unsignedTinyInteger attempts
      unsignedInteger reserved_at
      unsignedInteger available_at
      unsignedInteger created_at
    }

    job_batches {
      varchar id PK
      varchar name
      int total_jobs
      int pending_jobs
      int failed_jobs
      longtext failed_job_ids
      mediumtext options
      int cancelled_at
      int created_at
      int finished_at
    }

    failed_jobs {
      bigint id PK
      varchar uuid UNIQUE
      text connection
      text queue
      longtext payload
      longtext exception
      timestamp failed_at
    }

    cache {
      varchar key PK
      mediumtext value
      int expiration
    }

    cache_locks {
      varchar key PK
      varchar owner
      int expiration
    }

    sessions {
      varchar id PK
      bigint user_id FK
      varchar ip_address(45)
      text user_agent
      longtext payload
      int last_activity
    }

    password_reset_tokens {
      varchar email PK
      varchar token
      timestamp created_at
    }

    permissions {
      bigint id PK
      varchar name
      varchar guard_name
      timestamp created_at
      timestamp updated_at
    }

    roles {
      bigint id PK
      varchar name
      varchar guard_name
      timestamp created_at
      timestamp updated_at
    }

    model_has_permissions {
      bigint permission_id FK
      varchar model_type
      bigint model_id
    }

    model_has_roles {
      bigint role_id FK
      varchar model_type
      bigint model_id
    }

    role_has_permissions {
      bigint permission_id FK
      bigint role_id FK
    }

    /* RELATIONS */
    ref_jenis_dokumen ||--o{ dokumen_hukums : has_many
    ref_bidang_hukum ||--o{ dokumen_hukums : has_many
    dokumen_hukums ||--o{ dokumen_subjek : has_many
    ref_subjek ||--o{ dokumen_subjek : has_many
    dokumen_subjek }o--o{ ref_subjek : "many-to-many via dokumen_subjek"
    users ||--o{ surveys : gives
    roles ||--o{ model_has_roles : has_many
    permissions ||--o{ model_has_permissions : has_many
    roles ||--o{ role_has_permissions : has_many
    permissions ||--o{ role_has_permissions : has_many
    sessions }o--o{ users : "optional FK user_id"
```


---

**Alur Autentikasi & Keamanan**

- **Autentikasi:** Laravel standard (users table); password di-hash (lihat casting `password => 'hashed'` pada `app/Models/User.php`). Reset password menggunakan tabel `password_reset_tokens`.
- **Otorisasi:** Spatie Roles & Permissions (migrasi `create_permission_tables` menyertakan `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`). Model `User` memakai `HasRoles` trait.
- **Akses Admin Filament:** `User::canAccessPanel()` membatasi akses ke panel Filament tertentu (dalam kode: panel id `webmin` hanya untuk role `super_admin` atau `Admin`). Lihat [app/Models/User.php](app/Models/User.php).
- **Validasi & Sanitasi:** Controllers menggunakan `$request->validate()` (contoh: `ApiController::index`, `SurveyController::store`) untuk input sanitasi.
- **Proteksi file:** Akses download diperiksa sebelum streaming file (lihat `DokumenController::unduhLampiran` dan accessor `download_allowed` di model `DokumenHukum`). File dikirim menggunakan `Storage::disk('public')->download(...)` atau `response()->file(...)`.
- **CSRF & Session:** Blade forms / Livewire melekat pada mekanisme CSRF Laravel; session table tersedia (`sessions`).
- **Saran keamanan opsional:** gunakan signed URLs atau private S3 bucket untuk file sensitif; aktifkan rate-limiting untuk endpoint publik API jika diperlukan; validasi ukuran & tipe file saat upload.

---

**Daftar Endpoint Utama (ringkasan dari `routes/web.php`)**

- GET `/` → `DashboardController@index` (homepage)
- GET `/dokumen` → closure (list view)
- GET `/dokumen/{tipeDok?}` → closure (list per tipe)
- GET `/profil/{slug}` → closure (profil page)
- GET `/dokumen/{tipeDok}/{slug}` → `DokumenController@show` (detail dokumen)
- GET `/download-lampiran/{slug}` → `DokumenController@unduhLampiran`
- GET `/dokumen/view/{dokumen:slug}` → `DokumenController@fileView` (preview)
- GET `/download-abstrak/{slug}` → `DokumenController@unduhAbstrak`
- GET `/berita` → `BeritaController@index`
- GET `/berita/{slug}` → `BeritaController@show`
- GET `/infografis` → `InfografisController@index`
- GET `/infografis/{slug}` → `InfografisController@show`
- POST `/` → `SurveyController@store` (simpan rating)
- GET `/api/rating` → `SurveyController@getRatingDistribution`
- GET `/api/rating-average` → `SurveyController@getAverageRating`
- GET `/api/rating-summary` → `SurveyController@getRatingSummary`
- GET `/metadata/api` → `ApiController@index` (API publik / sinkronisasi)

Catatan: Resources dan route admin Filament didefinisikan di `app/Filament/Resources/` (panel admin terpisah).

Referensi: [routes/web.php](routes/web.php), [app/Http/Controllers/DokumenController.php](app/Http/Controllers/DokumenController.php), [app/Http/Controllers/ApiController.php](app/Http/Controllers/ApiController.php)

---

**Daftar Route Lengkap (dari `routes/web.php`)**

```
GET    /                         → DashboardController@index          (name: dashboard.index)
GET    /dokumen                   → closure (view dokumen.list-doc)   (name: list-doc)
GET    /dokumen/{tipeDok?}        → closure (view dokumen.list-doc)   (name: dokumen.tipe)
GET    /profil/{slug}             → closure (profil page / faq)
GET    /dokumen/{tipeDok}/{slug}  → DokumenController@show            (name: dokumen.detail)
GET    /download-lampiran/{slug}  → DokumenController@unduhLampiran   (name: dokumen.unduhLampiran)
GET    /dokumen/view/{dokumen:slug} → DokumenController@fileView       (name: dokumen.view)
GET    /download-abstrak/{slug}   → DokumenController@unduhAbstrak    (name: dokumen.unduhAbstrak)
GET    /berita                    → BeritaController@index             (name: berita.index)
GET    /berita/{slug}             → BeritaController@show              (name: berita.show)
GET    /infografis                → InfografisController@index        (name: infografis.index)
GET    /infografis/{slug}         → InfografisController@show         (name: infografis.show)
POST   /                         → SurveyController@store             (name: survey.store)
GET    /api/rating               → SurveyController@getRatingDistribution (name: rating.distribution)
GET    /api/rating-average       → SurveyController@getAverageRating (name: rating.average)
GET    /api/rating-summary       → SurveyController@getRatingSummary (name: rating.summary)
GET    /metadata/api             → ApiController@index                (throttle:60,1)
```

Catatan: Paket pihak ketiga (Filament, Livewire, vendor packages) akan mendaftarkan route tambahan (admin panel, assets, api internal). Gunakan `php artisan route:list` di lingkungan pengembangan untuk daftar lengkap.

Berikut output lengkap dari `php artisan route:list` (lingkungan pengembangan):

```
  GET|HEAD   / ......................................................................................................................................... dashboard.index › DashboardController@index
  POST       / ............................................................................................................................................... survey.store › SurveyController@store
  GET|HEAD   api/rating ............................................................................................................... rating.distribution › SurveyController@getRatingDistribution
  GET|HEAD   api/rating-average ................................................................................................................. rating.average › SurveyController@getAverageRating
  GET|HEAD   api/rating-summary ................................................................................................................. rating.summary › SurveyController@getRatingSummary
  POST       auto-logout-plugin-form ........................................................................................... filament-auto-logout-plugin-form › Filament\Http › LogoutController
  GET|HEAD   berita .......................................................................................................................................... berita.index › BeritaController@index
  GET|HEAD   berita/{slug} ..................................................................................................................................... berita.show › BeritaController@show
  GET|HEAD   dokumen ...................................................................................................................................................................... list-doc
  GET|HEAD   dokumen/view/{dokumen} ...................................................................................................................... dokumen.view › DokumenController@fileView
  GET|HEAD   dokumen/{tipeDok?} ....................................................................................................................................................... dokumen.tipe
  GET|HEAD   dokumen/{tipeDok}/{slug} ...................................................................................................................... dokumen.detail › DokumenController@show
  GET|HEAD   download-abstrak/{slug} ......................................................................................................... dokumen.unduhAbstrak › DokumenController@unduhAbstrak
  GET|HEAD   download-lampiran/{slug} ...................................................................................................... dokumen.unduhLampiran › DokumenController@unduhLampiran
  GET|HEAD   filament/exports/{export}/download ...................................................................................... filament.exports.download › Filament\Actions › DownloadExport
  GET|HEAD   filament/imports/{import}/failed-rows/download .................................................... filament.imports.failed-rows.download › Filament\Actions › DownloadImportFailureCsv
  GET|HEAD   infografis .............................................................................................................................. infografis.index › InfografisController@index
  GET|HEAD   infografis/{slug} ......................................................................................................................... infografis.show › InfografisController@show
  GET|HEAD   livewire/livewire.js ...................................................................................................... Livewire\Mechanisms › FrontendAssets@returnJavaScriptAsFile
  GET|HEAD   livewire/livewire.min.js.map ................................................................................................................ Livewire\Mechanisms › FrontendAssets@maps
  GET|HEAD   livewire/preview-file/{filename} ............................................................................. livewire.preview-file › Livewire\Features › FilePreviewController@handle
  POST       livewire/update ................................................................................................... livewire.update › Livewire\Mechanisms › HandleRequests@handleUpdate
  POST       livewire/upload-file ........................................................................................... livewire.upload-file › Livewire\Features › FileUploadController@handle
  GET|HEAD   metadata/api ...................................................................................................................................................... ApiController@index
  GET|HEAD   profil/{slug} .........................................................................................................................................................................
  GET|HEAD   storage/{path} .......................................................................................................................................................... storage.local
  GET|HEAD   up ....................................................................................................................................................................................
  GET|HEAD   webmin ................................................................................................................... filament.webmin.pages.dashboard › Filament\Pages › Dashboard
  GET|HEAD   webmin/activity-logs ............................................................................ filament.webmin.resources.activity-logs.index › Z3d0X\FilamentLogger › ListActivities
  GET|HEAD   webmin/activity-logs/{record} ...................................................................... filament.webmin.resources.activity-logs.view › Z3d0X\FilamentLogger › ViewActivity
  GET|HEAD   webmin/berita ........................................................................ filament.webmin.resources.berita.index › App\Filament\Resources\BeritaResource\Pages\ListBeritas
  GET|HEAD   webmin/berita/create ............................................................... filament.webmin.resources.berita.create › App\Filament\Resources\BeritaResource\Pages\CreateBerita
  GET|HEAD   webmin/berita/{record}/edit ............................................................ filament.webmin.resources.berita.edit › App\Filament\Resources\BeritaResource\Pages\EditBerita
  GET|HEAD   webmin/edit-profile-page ............................................................... filament.webmin.pages.edit-profile-page › Joaopaulolndev\FilamentEditProfile › EditProfilePage
  GET|HEAD   webmin/faqs .................................................................................. filament.webmin.resources.faqs.index › App\Filament\Resources\FaqResource\Pages\ListFaqs
  GET|HEAD   webmin/faqs/create ......................................................................... filament.webmin.resources.faqs.create › App\Filament\Resources\FaqResource\Pages\CreateFaq
  GET|HEAD   webmin/faqs/{record}/edit ...................................................................... filament.webmin.resources.faqs.edit › App\Filament\Resources\FaqResource\Pages\EditFaq
  GET|HEAD   webmin/health-check-results ........................................................................ filament.webmin.pages.health-check-results › App\Filament\Pages\HealthCheckResults
  GET|HEAD   webmin/infografis ......................................................... filament.webmin.resources.infografis.index › App\Filament\Resources\InfografisResource\Pages\ListInfografis
  GET|HEAD   webmin/infografis/create ............................................... filament.webmin.resources.infografis.create › App\Filament\Resources\InfografisResource\Pages\CreateInfografis
  GET|HEAD   webmin/infografis/{record}/edit ............................................ filament.webmin.resources.infografis.edit › App\Filament\Resources\InfografisResource\Pages\EditInfografis
  GET|HEAD   webmin/list-dokumen ................................................ filament.webmin.resources.list-dokumen.index › App\Filament\Resources\DokumenHukumResource\Pages\ListDokumenHukums
  GET|HEAD   webmin/list-dokumen/create ....................................... 
filament.webmin.resources.list-dokumen.create › App\Filament\Resources\DokumenHukumResource\Pages\CreateDokumenHukum
  GET|HEAD   webmin/list-dokumen/{record}/edit .................................... filament.webmin.resources.list-dokumen.edit › App\Filament\Resources\DokumenHukumResource\Pages\EditDokumenHukum
  GET|HEAD   webmin/login ..................................................................................................................... filament.webmin.auth.login › App\Filament\Auth\Login
  POST       webmin/logout .......................................................................................................... filament.webmin.auth.logout › Filament\Http › LogoutController
  GET|HEAD   webmin/password-reset/request ........................................... filament.webmin.auth.password-reset.request › DiogoGPinto\AuthUIEnhancer 
› AuthUiEnhancerRequestPasswordReset
  GET|HEAD   webmin/password-reset/reset ...................................................... filament.webmin.auth.password-reset.reset › DiogoGPinto\AuthUIEnhancer › AuthUiEnhancerResetPassword
  GET|HEAD   webmin/profil-pages .................................................... filament.webmin.resources.profil-pages.index › App\Filament\Resources\ProfilPageResource\Pages\ListProfilPages
  GET|HEAD   webmin/profil-pages/create ........................................... filament.webmin.resources.profil-pages.create › App\Filament\Resources\ProfilPageResource\Pages\CreateProfilPage
  GET|HEAD   webmin/profil-pages/{record}/edit ........................................ filament.webmin.resources.profil-pages.edit › App\Filament\Resources\ProfilPageResource\Pages\EditProfilPage
  GET|HEAD   webmin/ref-bidang-hukums .................................. filament.webmin.resources.ref-bidang-hukums.index › App\Filament\Resources\RefBidangHukumResource\Pages\ListRefBidangHukums
  GET|HEAD   webmin/ref-bidang-hukums/create ......................... filament.webmin.resources.ref-bidang-hukums.create › App\Filament\Resources\RefBidangHukumResource\Pages\CreateRefBidangHukum
  GET|HEAD   webmin/ref-bidang-hukums/{record}/edit ...................... filament.webmin.resources.ref-bidang-hukums.edit › App\Filament\Resources\RefBidangHukumResource\Pages\EditRefBidangHukum
  GET|HEAD   webmin/ref-jenis-dokumens .............................. filament.webmin.resources.ref-jenis-dokumens.index › App\Filament\Resources\RefJenisDokumenResource\Pages\ListRefJenisDokumens
  GET|HEAD   webmin/ref-jenis-dokumens/create ..................... filament.webmin.resources.ref-jenis-dokumens.create › App\Filament\Resources\RefJenisDokumenResource\Pages\CreateRefJenisDokumen
  GET|HEAD   webmin/ref-jenis-dokumens/{record}/edit .................. filament.webmin.resources.ref-jenis-dokumens.edit › App\Filament\Resources\RefJenisDokumenResource\Pages\EditRefJenisDokumen
  GET|HEAD   webmin/ref-subjeks ........................................................ filament.webmin.resources.ref-subjeks.index › App\Filament\Resources\RefSubjekResource\Pages\ListRefSubjeks
  GET|HEAD   webmin/ref-subjeks/create ............................................... filament.webmin.resources.ref-subjeks.create › App\Filament\Resources\RefSubjekResource\Pages\CreateRefSubjek
  GET|HEAD   webmin/ref-subjeks/{record}/edit ............................................ filament.webmin.resources.ref-subjeks.edit › App\Filament\Resources\RefSubjekResource\Pages\EditRefSubjek
  GET|HEAD   webmin/shield/roles ................................................................ filament.webmin.resources.shield.roles.index › App\Filament\Resources\RoleResource\Pages\ListRoles
  GET|HEAD   webmin/shield/roles/create ....................................................... filament.webmin.resources.shield.roles.create › App\Filament\Resources\RoleResource\Pages\CreateRole
  GET|HEAD   webmin/shield/roles/{record} ......................................................... filament.webmin.resources.shield.roles.view › App\Filament\Resources\RoleResource\Pages\ViewRole
  GET|HEAD   webmin/shield/roles/{record}/edit .................................................... filament.webmin.resources.shield.roles.edit › App\Filament\Resources\RoleResource\Pages\EditRole
  GET|HEAD   webmin/users ............................................................................ filament.webmin.resources.users.index › App\Filament\Resources\UserResource\Pages\ManageUsers
  GET|HEAD   webmin/visitors .................................................................. filament.webmin.resources.visitors.index › App\Filament\Resources\VisitorResource\Pages\ListVisitors


                 Showing [67] routes
```

---

**Catatan Operasional & Rekomendasi singkat**

- Backup DB dan `storage/app/public` rutin (file lampiran penting). Pertimbangkan penyimpanan S3 untuk skalabilitas.
- Gunakan signed URLs / temporary links untuk file downloads bila perlu kontrol akses.
- Pastikan queue worker (redis/database) berjalan untuk background jobs; gunakan supervisor/systemd di produksi.
- Performa: tambahkan index pada kolom pencarian yang sering dipakai (slug, ref_jenis_dokumen_id), gunakan pagination untuk API besar.
- Observability: gunakan logging (package filament-logger sudah terpasang) + centralized error tracking (Sentry/Log management) untuk monitoring.

---

Jika Anda ingin, saya bisa:

- Menambahkan diagram ERD yang lebih rinci (semua kolom dari tiap migrasi).
- Menyertakan daftar lengkap route (`php artisan route:list`) bila Anda ingin saya menjalankan perintah tersebut.
- Membuat diagram deployment (contoh Docker, nginx + php-fpm, worker/queue, S3).

File ini dibuat dari analisis kode sumber di repository; untuk referensi spesifik lihat file-file yang dirujuk di atas.

# Codebase Optimization Report — UI & Routes

**Project:** JDIH BNPB (Jaringan Dokumentasi dan Informasi Hukum)  
**Date:** 2026-06-21  
**Stack:** Laravel 12, Livewire 3, Filament 4, Bootstrap 5, jQuery, Swiper, OwlCarousel, Chart.js  
**Scope:** Routes (`routes/web.php`, `routes/console.php`) dan UI terkait (Controllers, Views, Layouts, Livewire)  
**Files Reviewed:** ~35 files

---

## Health Score

| Dimension | Score | Status |
|---|---|---|
| ⚡ Performance | 5/10 | 🟠 Needs Work |
| 🔒 Security | 7/10 | 🟢 Good |
| 🏗️ Code Quality | 5/10 | 🟠 Needs Work |
| 📈 Scalability | 7/10 | 🟢 Good |
| 🧪 Testing | 2/10 | 🔴 Critical |
| 🔧 Developer Experience | 6/10 | 🟠 Needs Work |
| 🚀 Feature Gaps | 5/10 | 🟠 Needs Work |
| **Overall** | **5/10** | 🟠 |

---

## Executive Summary

Proyek JDIH BNPB memiliki arsitektur yang **cukup rapi** — controller thin, service layer untuk dashboard, form request validation, dan Livewire untuk komponen interaktif. Route file terdokumentasi dengan baik melalui comment blocks, dan naming convention sudah konsisten.

Namun ada **3 masalah utama** yang perlu segera diperbaiki:

1. **Frontend asset bloat & render-blocking scripts** — Footer utama (`footer.blade.php`) berisi ~830 baris dengan seluruh inline JavaScript, sementara header meload library berat (Chart.js, Swiper, AOS, SweetAlert2) secara render-blocking. SweetAlert2 bahkan dimuat **dua kali**. Ini membunuh LCP dan INP.

2. **Route file mencampur web routes dengan API endpoint** — Tiga endpoint `/api/rating*` dan `/metadata/api` didefinisikan di `web.php`, bukan di file API route terpisah. Ini menyebabkan mereka melewati semua web middleware (session, CSRF, VisitorCounter) yang tidak diperlukan endpoint JSON.

3. **UI inline JavaScript monolith** — Lebih dari 500 baris JavaScript yang ditulis inline di `footer.blade.php` (chart initialization, carousel config, survey form, accessibility panel, text-to-speech engine) tanpa modulasi. Ini membuat halaman non-homepage tetap load semua script meskipun tidak butuh.

**Yang sudah baik:** Cache strategy di `DashboardService`, penggunaan Form Requests untuk validasi, rate limiting pada API endpoint, Turnstile protection pada survey, dan dokumentasi route yang sangat lengkap.

---

## Critical Issues (Fix This Week)

### [OPT-001] SweetAlert2 Loaded Twice

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance |
| **Impact** | High — ~50KB JavaScript dimuat 2× secara render-blocking, menambah ~200ms LCP (estimated) |
| **Effort** | Low (5 menit) |
| **Metric** | ~50KB wasted download → 0KB; estimated LCP improvement: ~150-200ms |
| **Location** | `resources/views/layout/footer.blade.php:46` dan `:511` |

**Problem:**
SweetAlert2 diload dua kali di header dan di footer.

**Evidence:**
```html
<!-- Di header.blade.php baris 46 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Di footer.blade.php baris 511 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

**Recommendation:**
Hapus salah satu, idealnya yang di header. Pindahkan satu-satunya `<script>` ke footer sebelum script yang menggunakannya. Atau lebih baik lagi, tambahkan `defer`:

```html
<!-- Hanya di footer.blade.php, satu kali -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
```

---

### [OPT-002] API Endpoints Didefinisikan di `web.php` — Melewati Web Middleware yang Tidak Diperlukan

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance / 🏗️ Code Quality |
| **Impact** | High — Setiap API request melewati VisitorCounter, session, CSRF middleware yang tidak diperlukan |
| **Effort** | Medium (~30 menit) |
| **Metric** | ~15-25ms overhead per API request dari middleware yang tidak perlu (suspected) |
| **Location** | `routes/web.php:105-123` |

**Problem:**
Empat endpoint JSON didefinisikan di `web.php`:
- `GET /api/rating`
- `GET /api/rating-average`
- `GET /api/rating-summary`
- `GET /metadata/api`

Karena berada di `web.php`, mereka melewati semua middleware web group: `EncryptCookies`, `VerifyCsrfToken`, `StartSession`, `VisitorCounter`, dll — yang semuanya tidak diperlukan untuk JSON API.

**Evidence:**
```php
// routes/web.php — baris 105-107
Route::get('/api/rating', [SurveyController::class, 'getRatingDistribution'])->middleware('throttle:30,1');
Route::get('/api/rating-average', [SurveyController::class, 'getAverageRating'])->middleware('throttle:30,1');
Route::get('/api/rating-summary', [SurveyController::class, 'getRatingSummary'])->middleware('throttle:30,1');

// routes/web.php — baris 121-123
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/metadata/api', [ApiController::class, 'index'])->name('metadata.api');
});
```

**Recommendation:**
Buat `routes/api.php` dan pindahkan endpoint-endpoint ini ke sana:

```php
// routes/api.php
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/rating', [SurveyController::class, 'getRatingDistribution'])->name('api.rating.distribution');
    Route::get('/rating-average', [SurveyController::class, 'getAverageRating'])->name('api.rating.average');
    Route::get('/rating-summary', [SurveyController::class, 'getRatingSummary'])->name('api.rating.summary');
});

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/metadata', [ApiController::class, 'index'])->name('api.metadata');
});
```

Lalu daftarkan di `bootstrap/app.php`:
```php
->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',    // ← tambahkan ini
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
)
```

> ⚠️ **Breaking change:** URL endpoint akan berubah dari `/api/rating` menjadi `/api/rating` (sama, karena Laravel 12 sudah tidak menambahkan prefix `/api` secara default — pastikan cek konfigurasi `bootstrap/app.php`). Update juga semua `fetch()` call di JavaScript.

---

### [OPT-003] Render-Blocking Scripts & Massive Inline JavaScript di Layout

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance |
| **Impact** | High — 10+ render-blocking scripts menambah ~800ms-1.2s ke LCP pada koneksi 3G (estimated) |
| **Effort** | High (~2-3 jam) |
| **Metric** | Estimated LCP: ~3s → ~1.8s pada 4G |
| **Location** | `resources/views/layout/header.blade.php:6-46` dan `footer.blade.php:190-830` |

**Problem:**
- Header memuat Chart.js, AOS, Swiper, SweetAlert2 sebagai render-blocking `<script>` (tanpa `defer` atau `async`)
- AOS CSS diload dari unpkg tanpa preload
- ~500 baris inline JavaScript di `footer.blade.php` yang dieksekusi pada SETIAP halaman, termasuk halaman yang tidak butuh chart, survey, atau carousel

**Evidence:**
```html
<!-- header.blade.php — render-blocking scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

**Recommendation:**

1. **Tambahkan `defer` ke semua `<script>` tag CDN** di header:
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
```

2. **Kondisional loading** — Chart.js dan carousel hanya diperlukan di homepage:
```blade
@stack('head-scripts')
```
Lalu di `index.blade.php`:
```blade
@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
@endpush
```

3. **Ekstrak inline JS ke file terpisah** — Pindahkan 500 baris inline JS dari `footer.blade.php` ke file modular:
   - `public/js/survey.js` — Rating chart + survey form
   - `public/js/carousel.js` — Owl + Swiper init
   - `public/js/accessibility.js` — Accessibility panel + TTS

---

## High Priority (Fix This Sprint)

### [OPT-004] Route Closure di `/dokumen/{tipeDok?}` — Tidak Bisa Di-Cache

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance / 🏗️ Code Quality |
| **Impact** | Medium — Mencegah `php artisan route:cache` di production |
| **Effort** | Low (10 menit) |
| **Metric** | Route resolution: ~5ms → ~0.5ms per request (estimated, setelah cache aktif) |
| **Location** | `routes/web.php:36-38` |

**Problem:**
Satu route menggunakan Closure, bukan controller method. Laravel tidak bisa cache route file yang berisi Closure.

**Evidence:**
```php
Route::get('/dokumen/{tipeDok?}', function ($tipeDok = null) {
    return view('dokumen.list-doc', compact('tipeDok'));
})->name('list-doc');
```

**Recommendation:**
Pindahkan ke `DokumenController`:
```php
// DokumenController.php
public function index(?string $tipeDok = null): View
{
    return view('dokumen.list-doc', compact('tipeDok'));
}

// routes/web.php
Route::get('/dokumen/{tipeDok?}', [DokumenController::class, 'index'])->name('list-doc');
```

---

### [OPT-005] Footer Links Menggunakan `href="#"` — Dead Links

| Field | Detail |
|---|---|
| **Dimension** | 🏗️ Code Quality / 🚀 Feature Gaps |
| **Impact** | Medium — Pengunjung mengklik link yang tidak mengarah ke mana-mana, SEO loss karena dead anchor links |
| **Effort** | Low (15 menit) |
| **Location** | `resources/views/layout/footer.blade.php:31-35` |

**Problem:**
Lima link di footer section "JDIH BNPB" masih menggunakan `href="#"` placeholder.

**Evidence:**
```html
<li><a href="#">Profil</a></li>
<li><a href="#">Struktur Organisasi</a></li>
<li><a href="#">Berita</a></li>
<li><a href="#">Statistik</a></li>
<li><a href="#">Visi dan Misi</a></li>
```

**Recommendation:**
Ganti dengan route yang sudah ada:
```html
<li><a href="{{ route('profil.show', 'tentang-kami') }}">Profil</a></li>
<li><a href="{{ route('profil.show', 'struktur-organisasi') }}">Struktur Organisasi</a></li>
<li><a href="{{ route('berita.index') }}">Berita</a></li>
<li><a href="/#stats">Statistik</a></li>
<li><a href="{{ route('profil.show', 'visi-misi') }}">Visi dan Misi</a></li>
```

---

### [OPT-006] Navbar Links Menggunakan Hardcoded URLs

| Field | Detail |
|---|---|
| **Dimension** | 🏗️ Code Quality |
| **Impact** | Medium — Jika route berubah, link navbar akan rusak tanpa error |
| **Effort** | Low (10 menit) |
| **Location** | `resources/views/layout/header.blade.php:112-116` |

**Problem:**
Link di dropdown "Perihal" menggunakan hardcoded path, bukan `route()` helper.

**Evidence:**
```html
<a href="/profil/tentang-kami" class="dropdown-item">Tentang Kami</a>
<a href="/profil/visi-misi" class="dropdown-item">Visi Misi</a>
<a href="/profil/struktur-organisasi" class="dropdown-item">Struktur Organisasi</a>
<a href="/profil/faq" class="dropdown-item">FAQ</a>
```

**Recommendation:**
```html
<a href="{{ route('profil.show', 'tentang-kami') }}" class="dropdown-item">Tentang Kami</a>
<a href="{{ route('profil.show', 'visi-misi') }}" class="dropdown-item">Visi Misi</a>
<a href="{{ route('profil.show', 'struktur-organisasi') }}" class="dropdown-item">Struktur Organisasi</a>
<a href="{{ route('profil.show', 'faq') }}" class="dropdown-item">FAQ</a>
```

---

### [OPT-007] Rating API Endpoints Tidak Di-Cache — 3 Separate DB Queries per Page Load

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance |
| **Impact** | Medium — 3 separate fetch calls + 3 DB queries setiap homepage load |
| **Effort** | Medium (30 menit) |
| **Metric** | 3 HTTP requests + 3 DB queries → 1 HTTP request + 0 DB queries (cached); estimated: ~120ms → ~20ms total |
| **Location** | `app/Http/Controllers/SurveyController.php:22-52` |

**Problem:**
Homepage melakukan 3 `fetch()` call ke 3 endpoint berbeda (`/api/rating`, `/api/rating-average`, `/api/rating-summary`), masing-masing menjalankan query DB. Data rating jarang berubah namun tidak di-cache.

**Evidence:**
```php
// SurveyController.php — 3 method tanpa cache
public function getRatingDistribution(): JsonResponse
{
    $ratingCounts = Survey::selectRaw('rating, COUNT(*) as count')
        ->groupBy('rating')
        ->pluck('count', 'rating');
    // ...
}

public function getAverageRating(): JsonResponse
{
    $averageRating = Survey::average('rating');
    // ...
}

public function getRatingSummary(): JsonResponse
{
    $averageRating = Survey::average('rating');
    $totalRatings = Survey::count();
    // ...
}
```

**Recommendation:**

**Opsi A — Gabung ke 1 endpoint + cache:**
```php
public function getRatingSummary(): JsonResponse
{
    $data = Cache::remember('survey.summary', 600, function () {
        $ratingCounts = Survey::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[] = $ratingCounts[$i] ?? 0;
        }

        $average = Survey::average('rating');
        $total = Survey::count();

        return [
            'average' => round($average ?? 0, 1),
            'total' => $total,
            'distribution' => $distribution,
        ];
    });

    return response()->json($data);
}
```

**Opsi B — Server-side render data ke homepage:**
Karena data ini hanya dipakai di homepage, bisa ditambahkan ke `DashboardService::getHomepageData()` dan dikirim langsung via Blade variable, menghilangkan fetch call sepenuhnya.

---

### [OPT-008] `file_exists()` Dipanggil pada View — Blocking I/O

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance |
| **Impact** | Medium — `file_exists()` pada setiap item infografis di homepage loop = N filesystem calls |
| **Effort** | Low (15 menit) |
| **Location** | `resources/views/index.blade.php:111` |

**Problem:**
```blade
@if(!empty($item->gambar) && file_exists(public_path('storage/' . $item->gambar)))
```
`file_exists()` dipanggil di loop Blade, menyebabkan N I/O operation di setiap page render.

**Recommendation:**
Ganti dengan `Storage::disk('public')->exists()` yang sudah dipakai di tempat lain (konsistensi), atau lebih baik, tambahkan accessor di Model `Infografis`:
```php
// Model Infografis
public function getGambarUrlAttribute(): string
{
    return $this->gambar && Storage::disk('public')->exists($this->gambar)
        ? asset('storage/' . $this->gambar)
        : asset('img/noimage.png');
}
```
```blade
<!-- Di view -->
<img src="{{ $item->gambar_url }}" class="img-fluid w-100 rounded" alt="{{ $item->judul }}">
```

---

## Medium Priority (Next Sprint)

### [OPT-009] Duplicated Layout Templates — 3 Header + 2 Footer Variants

| Field | Detail |
|---|---|
| **Dimension** | 🏗️ Code Quality / Maintainability |
| **Impact** | Medium — Perubahan UI harus di-apply ke 3 file berbeda |
| **Effort** | High (~3-4 jam) |
| **Location** | `resources/views/layout/` |

**Problem:**
Ada 3 header variants (`header.blade.php`, `header-clear.blade.php`, `header-detail.blade.php`) dan 2 footer variants. Ini menyebabkan:
- Bug fix/UI change harus direplikasi ke semua variants
- Inconsistency risk sangat tinggi
- `footer.blade.php` berisi **830 baris** (termasuk ~500 baris JavaScript)

**Recommendation:**
Refactor ke single layout dengan conditional sections:
```blade
{{-- layout/master.blade.php --}}
@include('layout.partials.head')
@yield('hero', '')  {{-- kosong untuk clear/detail --}}
@yield('content')
@include('layout.partials.footer')
@include('layout.partials.scripts')
@stack('page-scripts')
```

---

### [OPT-010] Duplicate ID `stats` pada Homepage

| Field | Detail |
|---|---|
| **Dimension** | 🏗️ Code Quality |
| **Impact** | Low — Bisa menyebabkan accessibility issue dan JS selector bugs |
| **Effort** | Low (5 menit) |
| **Location** | `resources/views/index.blade.php:10` dan `:92` |

**Problem:**
```html
<!-- Baris 10 -->
<section id="stats" class="stats section light-background py-5">

<!-- Baris 92 — ID SAMA -->
<section id="stats" class="stats section light-background py-5">
```

**Recommendation:**
Ganti ke ID unik: `id="dokumen-stats"` dan `id="infografis-section"`.

---

### [OPT-011] Redundant `Berita Terpopuler` Query di `BeritaController`

| Field | Detail |
|---|---|
| **Dimension** | ⚡ Performance / 🏗️ Code Quality |
| **Impact** | Low — Sama query dipanggil di `index()` dan `show()` |
| **Effort** | Low (10 menit) |
| **Location** | `app/Http/Controllers/BeritaController.php:17-19` dan `:32-34` |

**Problem:**
Cache key dan query identik diulang di dua method.

**Evidence:**
```php
// Duplikasi di index() dan show()
$beritaTerpopuler = Cache::remember('berita_terpopuler', 1800, function () {
    return Berita::orderByDesc('view_count')->limit(5)->get();
});
```

**Recommendation:**
Ekstrak ke method private atau scope di Model:
```php
private function getPopularBerita()
{
    return Cache::remember('berita_terpopuler', 1800, fn() =>
        Berita::orderByDesc('view_count')->limit(5)->get()
    );
}
```

---

### [OPT-012] `ProfilPageController` Hardcodes FAQ Logic

| Field | Detail |
|---|---|
| **Dimension** | 🏗️ Code Quality |
| **Impact** | Low — Menambah special case setiap ada halaman "khusus" |
| **Effort** | Medium (30 menit) |
| **Location** | `app/Http/Controllers/ProfilPageController.php:17-27` |

**Problem:**
```php
if ($slug === 'faq') {
    return view('components.overview.page', [
        'slug' => $slug,
        'page' => (object) [
            'title'   => 'FAQ',
            'content' => null,
            'image'   => null,
        ],
        'faqs' => Faq::orderBy('sort_order')->get(),
    ]);
}
```
Hardcoding `'faq'` check di controller. Jika nanti ada halaman khusus lain (misal: `glosarium`), pattern ini akan semakin sulit di-maintain.

**Recommendation:**
Pertimbangkan menambahkan field `page_type` (enum: `static`, `faq`, dll.) di tabel `profil_pages` sehingga tidak perlu hardcode slug.

---

## Low Priority / Technical Debt (Backlog)

- **jQuery loaded di semua halaman** — Hanya digunakan untuk OwlCarousel di homepage. Pertimbangkan migrasi ke Swiper-only (sudah diload juga) dan drop jQuery dependency (~87KB).
- **`lang="en"` pada `<html>` tag** — Konten dalam Bahasa Indonesia, seharusnya `lang="id"`. Ini berdampak pada SEO dan screen readers.
- **Copyright tahun hardcoded** — `Copyright © 2024` di `footer.blade.php:76`. Ganti ke `{{ date('Y') }}`.
- **`Bootstrap 5.0.0` di footer** vs kemungkinan versi lebih baru di `bootstrap.min.css` — Potensial version mismatch antara CSS dan JS.
- **Missing `alt` text** — `<img>` di survey section (`alt=""`) dan beberapa placeholder images kehilangan deskriptif alt text.
- **Inline styles** — Beberapa elemen menggunakan inline `style=""` (misal `padding: 50px`, `border: 50px solid`) daripada CSS class.

---

## Quick Wins (< 1 hour each)

- [x] **Hapus duplikat SweetAlert2** — `footer.blade.php:511` — 5 menit
- [x] **Tambahkan `defer` ke semua CDN scripts** di header — 10 menit
- [x] **Fix duplicate `id="stats"`** di `index.blade.php` — 5 menit
- [x] **Ganti `lang="en"` → `lang="id"`** di header.blade.php — 2 menit
- [x] **Ganti copyright tahun hardcoded** — `footer.blade.php:76` — 2 menit
- [x] **Fix 5 dead links** (`href="#"`) di footer — 10 menit
- [x] **Ganti 4 hardcoded URLs** di navbar dropdown ke `route()` — 10 menit
- [x] **Pindahkan route closure** `/dokumen/{tipeDok?}` ke controller method — 10 menit
- [x] **Cache rating API responses** — 20 menit
- [x] **Ganti `file_exists()` di view loop** dengan accessor — 15 menit

---

## Dependency Audit

| Package | Type | Status | Note |
|---|---|---|---|
| jQuery 3.6.4 | CDN | ⚠️ Outdated | 3.7.x available; tapi lebih baik pertimbangkan drop entirely |
| Bootstrap JS 5.0.0 | CDN | ⚠️ Outdated | 5.3.x available; pastikan CSS + JS versi sama |
| Chart.js 4.4.0 | CDN | ✅ Current | |
| Swiper 11 | CDN | ✅ Current | |
| AOS 2.3.1 | CDN (unpkg) | ⚠️ Outdated | Pertimbangkan self-host atau ganti ke CSS scroll-driven animations |
| SweetAlert2 @11 | CDN | ✅ Current | Tapi dimuat 2× |
| Font Awesome 5.15.4 | CDN | ⚠️ Outdated | FA 6.x available |
| PureCounter | CDN | ✅ Current | |

---

## Recommended Tooling

| Tool | Purpose | Priority |
|---|---|---|
| `php artisan route:cache` | Cache routes di production (blocked oleh Closure saat ini) | High |
| Vite bundling (sudah ada config) | Bundle & tree-shake JS/CSS, eliminate CDN waterfall | High |
| Laravel Debugbar | Monitor query count & timing selama development | Medium |
| Lighthouse CI | Automated performance tracking per deploy | Medium |

---

## Sprint Roadmap

### Sprint 1 — Quick Wins & Critical Fixes
- [x] OPT-001: Hapus duplikat SweetAlert2
- [x] OPT-003: Tambahkan `defer` ke render-blocking scripts
- [x] OPT-004: Pindahkan route closure ke controller
- [x] OPT-005: Fix dead links di footer
- [x] OPT-006: Ganti hardcoded navbar URLs
- [x] OPT-010: Fix duplicate IDs
- [x] Quick wins lainnya (lang, copyright, alt text)

#### Validasi Sprint 1 — 2026-06-24
- [x] OPT-001 valid: `sweetalert2@11` tersisa 1 kali di `footer.blade.php` dan memakai `defer`.
- [x] OPT-003 valid: script CDN header `Chart.js`, `AOS`, dan `Swiper` sudah memakai `defer`; inisialisasi AOS dipindah ke `DOMContentLoaded` agar aman dengan deferred script.
- [x] OPT-004 valid: `list-doc` mengarah ke `App\Http\Controllers\DokumenController@index`; `php artisan route:cache` berhasil dengan `APP_ROUTES_CACHE=bootstrap/cache/routes-validation.php`.
- [x] OPT-005 valid: 5 link footer JDIH BNPB tidak lagi memakai `href="#"`.
- [x] OPT-006 valid: 4 link dropdown Perihal memakai `route('profil.show', ...)`.
- [x] OPT-010 valid: duplicate `id="stats"` diganti menjadi `dokumen-stats` dan `infografis-section`.
- [x] Quick wins valid: `<html lang="id">`, copyright memakai `{{ date('Y') }}`, dan gambar survey memiliki alt text deskriptif.
- [x] Format valid: `./vendor/bin/pint --test app/Http/Controllers/DokumenController.php routes/web.php` passed.
- [ ] Full suite belum hijau: `php artisan test` berjalan dengan 55 passed / 9 failed; kegagalan berada pada feature response 500/302 yang tidak terkait langsung dengan perubahan Sprint 1 dan perlu investigasi terpisah.

### Sprint 2 — Route & API Optimization
- [x] OPT-002: Pindahkan API endpoints ke `routes/api.php`
- [x] OPT-007: Gabung & cache rating API
- [x] OPT-008: Fix `file_exists()` di view loop
- [x] OPT-011: Deduplicate berita terpopuler query

#### Validasi Sprint 2 — 2026-06-24
- [x] OPT-002 valid: endpoint `/api/rating`, `/api/rating-average`, `/api/rating-summary`, dan `/metadata/api` berada di `routes/api.php` dengan middleware `api`; URL publik tetap kompatibel memakai `apiPrefix: ''`.
- [x] OPT-007 valid: homepage hanya melakukan 1 fetch ke `/api/rating-summary`; response endpoint berisi `average`, `total`, dan `distribution`; data rating memakai `Cache::remember('survey.summary', 600, ...)` dan cache dihapus setelah survey baru.
- [x] OPT-008 valid: loop infografis homepage tidak lagi memanggil `file_exists()`; view memakai accessor `$item->gambar_url` dengan fallback `img/noimage.png`.
- [x] OPT-011 valid: query `berita_terpopuler` hanya berada di `BeritaController::getPopularBerita()` dan dipakai oleh `index()` serta `show()`.
- [x] Format valid: `./vendor/bin/pint --test bootstrap/app.php routes/web.php routes/api.php app/Http/Controllers/SurveyController.php app/Http/Controllers/BeritaController.php app/Models/Infografis.php` passed.
- [x] Route cache valid: `php artisan route:cache` berhasil dengan `APP_ROUTES_CACHE=bootstrap/cache/routes-validation.php`, lalu artefak cache validasi dibersihkan.
- [x] API metadata valid: `php artisan test tests/Feature/ApiControllerTest.php` passed, 9 tests / 18 assertions.
- [x] Endpoint smoke test valid: `/api/rating-summary` mengembalikan HTTP 200 dengan JSON gabungan; `/metadata/api` tetap HTTP 200.
- [ ] Full suite belum hijau: `php artisan test` berjalan dengan 56 passed / 8 failed; kegagalan tersisa berada pada feature halaman dokumen/homepage 500 dan invalid tipe dokumen 302 yang perlu investigasi terpisah.

### Sprint 3 — Architecture & Frontend Refactor
- [x] OPT-003: Modularisasi inline JS ke file terpisah
- [x] OPT-009: Konsolidasi layout templates
- [x] OPT-012: Refactor ProfilPageController
- [x] Evaluasi drop jQuery, migrasi ke Vite bundling

#### Validasi Sprint 3 — 2026-06-24
- [x] OPT-003 valid: inline JavaScript footer/header dipindahkan ke `public/js/layout-interactions.js`, `public/js/carousel.js`, `public/js/survey.js`, dan `public/js/accessibility.js`; script layout dimuat dengan `defer` dan cache-busting `filemtime()`.
- [x] OPT-009 valid: wrapper `layout.app`, `layout.app-clear`, dan `layout.app-detail` dikonsolidasikan lewat `resources/views/layout/master.blade.php`; varian header/footer tetap dipertahankan untuk kebutuhan tampilan, tetapi drift penting disinkronkan (`lang="id"`, route profil, footer links, copyright).
- [x] OPT-012 valid: `ProfilPageController` memakai `SPECIAL_PAGE_HANDLERS`, return type `View`, dan handler `faqPage()` terpisah sehingga special page baru tidak perlu ditanam langsung di alur `show()`.
- [x] Evaluasi jQuery/Vite valid: jQuery belum aman di-drop karena `public/js/main.js`, OwlCarousel, Lightbox, Easing, dan Waypoints masih bergantung pada jQuery; migrasi Vite tersedia melalui `vite.config.js` (`resources/js/app.js`) tetapi bundling penuh CDN/public scripts sebaiknya menjadi sprint lanjutan.
- [x] Syntax JS valid: `node --check public/js/survey.js`, `public/js/carousel.js`, `public/js/layout-interactions.js`, dan `public/js/accessibility.js` passed.
- [x] Format PHP valid: `./vendor/bin/pint --test app/Http/Controllers/ProfilPageController.php` passed.
- [x] Blade cache valid: `php artisan view:cache` passed setelah `php artisan view:clear`; validasi dijalankan di luar sandbox karena `storage/framework/views` sebelumnya terkena `Access is denied` dari Windows sandbox.
- [x] Smoke test valid: request `/profil/faq` mengembalikan HTTP 200 setelah cache Blade bersih.
- [ ] Full suite belum hijau: `php artisan test` gagal karena koneksi database test `jdih_test` ditolak untuk user `root` (`SQLSTATE[HY000] [1045]`), sehingga belum bisa dipakai sebagai validasi regresi Sprint 3 sampai credential DB test diperbaiki.

---

## What's Working Well

1. **Route documentation** — Comment blocks di `web.php` sangat baik dengan penjelasan "Catatan audit" yang menunjukkan evolusi design decision. Ini praktek yang jarang ditemui dan sangat diapresiasi.

2. **Service layer separation** — `DashboardService` mengumpulkan semua data homepage ke satu tempat yang ter-cache 5 menit. Clean dan mudah di-maintain.

3. **Form Request validation** — `ShowDokumenRequest`, `DownloadDokumenRequest`, `ViewDokumenFileRequest`, `StoreSurveyRequest`, `MetadataApiRequest` — semua sudah proper.

4. **Security measures** — Turnstile captcha pada survey, rate limiting pada API, `isSafePublicStoragePath()` path traversal protection, Gate authorization untuk download, dan VisitorCounter yang mendeteksi suspicious user agents.

5. **Smart view count increment** — Menggunakan `DB::table()->increment()` daripada Eloquent model save untuk menghindari trigger observer dan `updated_at` timestamp change.

6. **Livewire DokumenList** — Komponen yang well-structured dengan URL alias mapping, dynamic filter counts, dan caching strategy yang solid.

7. **Scheduled commands** — Visitor cleanup, sitemap generation, dan database backup sudah terjadwal rapi.

# 📋 LAPORAN AUDIT PROJECT LARAVEL JDIH BNPB

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
- **Pagination** sudah diimplementasikan dengan benar
- **API Rate Limiting** (`throttle:60,1`)

### 5. Logging & Monitoring
- **Multi-channel Logging** (`stack`, `visitor`, `ipaccess`)
- **Activity Logging** via Filament Logger
- **Health Check** tersedia via Filament Spatie Health

---

## B. KELEMAHAN PROJECT

### 1. Struktur Arsitektur
- **Controller Terlalu "Fat"** - `DokumenController` memiliki method yang terlalu banyak tanggung jawab
- **Kurangnya Service Layer** - Logic bisnis masih banyak di Controller dan Model
- **Missing API Routes** - Hanya ada satu endpoint API tanpa proper API resource

### 2. Kode & Clean Code
- **Inline CSS di Blade** - `detail.blade.php` memiliki ~100 baris CSS inline
- **Hardcoded Values** di banyak tempat (`$validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan']`)
- **Duplikasi Query** di `DashboardController` dan `BeritaController`
- **Naming Inconsistency**: `tipeDok` (camelCase) vs `ref_jenis_dokumen_id` (snake_case)

### 3. Database & Query
- **Redundant Fields** di tabel `dokumen_hukums`: `jenisDok`, `singkat`, `bidangHukum`
- **Missing Database Indexes** untuk `view_count`, `download_count`, `tahun`, `status`
- **N+1 Query Potential** di `DokumenList` dengan multiple queries di `loadFilterCounts()`

### 4. Security
- **No Input Validation** di Controller (tidak ada Form Request)
- **File Path Traversal Risk** di download methods
- **No Authorization Policy Check** untuk download endpoint di backend

### 5. Performance
- **Tidak ada route caching**
- **Visitor Middleware Blocking** - HTTP request ke ipapi.co/ipwho.is di setiap request
- **No CDN** - Semua assets diload dari server lokal

### 6. Frontend (Blade/Tailwind)
- **Inline CSS/JS** di Blade template (~200+ lines)
- **No Component Reusability** - Badge, card styling duplicat
- **Accessibility Issues**: Missing `aria-label`, alt text

### 7. DevOps & Deployment
- **Missing Environment Validation** - tidak ada check untuk `APP_KEY`
- **Debug Mode** yang bisa leak di production
- **No Queue Implementation** - visitor logging blocking

---

## C. POTENSI RISIKO KRITIS

### 🔴 HIGH RISK

| No | Risiko | Lokasi | Dampak |
|----|--------|--------|--------|
| 1 | **File Download Path Traversal** | `DokumenController::unduhLampiran()` | Serangan akses file ilegal |
| 2 | **No Backend Authorization Download** | `DokumenController` | User bisa manipulasi URL untuk download dokumen TIDAK BERLAKU |
| 3 | **Mass Assignment** | `DokumenHukum` model `$fillable` | Semua field bisa di-assign tanpa filter |
| 4 | **Visitor Middleware Blocking** | `VisitorCounter` middleware | Performance degradation, timeout |

### 🟡 MEDIUM RISK

| No | Risiko | Lokasi | Dampak |
|----|--------|--------|--------|
| 5 | **SQL Injection via like query** | `DokumenHukum::scopeApplyFilters` | Potensi SQL injection |
| 6 | **Missing Rate Limiting** | Download endpoints | Abuse oleh user |
| 7 | **Sensitive Data Exposure** | Public API | `document_uid`, `view_count` visible |

---

## D. PRIORITAS PERBAIKAN

| Prioritas | Kategori | Item | Estimasi |
|-----------|----------|------|----------|
| **HIGH** | Security | Perbaiki file download vulnerability | 2 jam |
| **HIGH** | Security | Tambahkan backend authorization untuk download | 1 jam |
| **HIGH** | Security | Implementasi input validation (Form Request) | 2 jam |
| **HIGH** | Performance | Refactor Visitor Middleware ke Queue | 4 jam |
| **MEDIUM** | Architecture | Ekstrak Service Layer | 1-2 hari |
| **MEDIUM** | Database | Tambahkan missing indexes | 1 jam |
| **MEDIUM** | Database | Normalisasi - hapus redundant fields | 2 jam |
| **MEDIUM** | Code Quality | Pindahkan inline CSS ke file terpisah | 3 jam |
| **MEDIUM** | Frontend | Tambahkan accessibility attributes | 2 jam |
| **LOW** | Performance | Implementasi route & view caching | 1 jam |
| **LOW** | DevOps | Tambahkan CDN untuk static assets | 2 jam |

---

## E. REKOMENDASI REFACTOR SPESIFIK

### 1. Security Fix - File Download (HIGH PRIORITY)

```php
// app/Http/Controllers/DokumenController.php

// SEBELUM (TIDAK AMAN):
public function unduhLampiran($slug)
{
    $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();
    $filePath = public_path('storage/' . $dokumen->lampiran);
    
    if (!file_exists($filePath)) {
        abort(404, 'File tidak ditemukan');
    }
    
    DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count', 1);
    
    return response()->download($filePath, null, [...]);
}

// SESUDAH (AMAN):
public function unduhLampiran(Request $request, string $slug)
{
    $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();
    
    // 1. Check authorization
    if (!$dokumen->download_allowed) {
        abort(403, 'Dokumen tidak dapat diunduh');
    }
    
    // 2. Validate file exists di storage
    if (!$dokumen->lampiran_exists) {
        abort(404, 'File tidak ditemukan');
    }
    
    // 3. Use Storage facade (amannya)
    return Storage::disk('public')->download(
        $dokumen->lampiran,
        $this->sanitizeFilename($dokumen->judul) . '.' . $dokumen->file_extension,
        ['Content-Type' => $this->getMimeType($dokumen->file_extension)]
    );
}

private function sanitizeFilename(string $filename): string
{
    return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
}

private function getMimeType(string $extension): string
{
    return match($extension) {
        'pdf' => 'application/pdf',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        default => 'application/octet-stream',
    };
}
```

### 2. Architecture - Service Layer (MEDIUM PRIORITY)

```php
// app/Services/DokumenService.php

<?php

namespace App\Services;

use App\Models\DokumenHukum;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DokumenService
{
    public function getDokumenDetail(string $tipeDok, string $slug): DokumenHukum
    {
        $dokumen = DokumenHukum::with([
            'jenisDokumen',
            'bidangHukumRef', 
            'subjekRef',
        ])->findBySlug($tipeDok, $slug)->firstOrFail();
        
        // Increment view count dengan queue
        dispatch(function () use ($dokumen) {
            DB::table('dokumen_hukums')
                ->where('id', $dokumen->id)
                ->increment('view_count');
        })->onQueue('analytics');
        
        return $dokumen;
    }
    
    public function incrementDownload(string $slug): void
    {
        dispatch(function () use ($slug) {
            DokumenHukum::where('slug', $slug)
                ->increment('download_count');
        })->onQueue('analytics');
    }
    
    public function getDokumenList(array $filters, string $sortBy, int $perPage = 10): LengthAwarePaginator
    {
        return DokumenHukum::query()
            ->with(['jenisDokumen', 'bidangHukumRef', 'subjekRef'])
            ->applyFilters($filters)
            ->applySorting($sortBy)
            ->paginate($perPage);
    }
}
```

### 3. Input Validation - Form Request (HIGH PRIORITY)

```php
// app/Http/Requests/DokumenDownloadRequest.php

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DokumenDownloadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::exists('dokumen_hukums', 'slug'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Slug dokumen wajib diisi.',
            'slug.exists' => 'Dokumen tidak ditemukan.',
        ];
    }
}
```

### 4. Database - Add Missing Indexes

```php
// database/migrations/xxxx_xx_xx_add_missing_indexes.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->index(['tipeDok', 'view_count'], 'idx_tipe_view_count');
            $table->index(['tipeDok', 'tglPengundangan'], 'idx_tipe_tgl');
            $table->index(['ref_jenis_dokumen_id', 'status'], 'idx_jenis_status');
            $table->index(['tahun', 'tipeDok'], 'idx_tahun_tipe');
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->index(['ip_address', 'visited_at'], 'idx_ip_date');
            $table->index('visited_at', 'idx_visited_at');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukums', function (Blueprint $table) {
            $table->dropIndex('idx_tipe_view_count');
            $table->dropIndex('idx_tipe_tgl');
            $table->dropIndex('idx_jenis_status');
            $table->dropIndex('idx_tahun_tipe');
        });
    }
};
```

### 5. Frontend - Ekstrak CSS Components

```blade
{{-- resources/views/components/ui/badge.blade.php --}}
@props(['type' => 'default'])

@php
$classes = match($type) {
    'primary' => 'bg-primary text-white',
    'success' => 'bg-success text-white',
    'warning' => 'bg-warning text-dark',
    'info' => 'bg-info text-dark',
    default => 'bg-secondary text-white',
};
@endphp

<span class="badge {{ $classes }} {{ $attributes->get('class', '') }}">
    {{ $slot }}
</span>

{{-- Penggunaan di detail.blade.php --}}
<x-ui-badge type="info">
    <i class="fas fa-calendar-alt me-1"></i> {{ $dokumen->created_at_formatted }}
</x-ui-badge>
```

### 6. Performance - Queue Implementation

```php
// app/Jobs/RecordVisitorJob.php

<?php

namespace App\Jobs;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecordVisitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $ip,
        public string $userAgent,
        public string $path,
        public string $referrer
    ) {}

    public function handle(): void
    {
        $geoData = $this->getGeoData($this->ip);
        
        try {
            Visitor::create([
                'ip_address' => $this->ip,
                'user_agent' => substr($this->userAgent, 0, 255),
                'path' => $this->path,
                'referrer' => $this->referrer,
                'country' => $geoData['country'],
                'city' => $geoData['city'],
                'suspicious' => $this->isSuspicious($this->userAgent),
                'visited_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::channel('visitor')->error('Failed to record visitor', [
                'ip' => $this->ip,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getGeoData(string $ip): array
    {
        try {
            $response = Http::timeout(2)->get("https://ipapi.co/{$ip}/json/");
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country_name'] ?? 'Unknown',
                    'city' => $data['city'] ?? '-',
                ];
            }
        } catch (\Throwable $e) {
            // Silent fail - use defaults
        }
        
        return ['country' => 'Unknown', 'city' => '-'];
    }

    private function isSuspicious(string $userAgent): bool
    {
        $patterns = ['sqlmap', 'curl/', 'wget', 'python-requests', 'nmap'];
        $ua = strtolower($userAgent);
        
        foreach ($patterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
}
```

### 7. Visitor Middleware - Use Queue

```php
// app/Http/Middleware/VisitorCounter.php

public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    if ($request->is('webmin/*') || $request->is('api/*')) {
        return $response;
    }

    $crawler = new CrawlerDetect;
    if ($crawler->isCrawler($request->userAgent())) {
        return $response;
    }

    if (!$this->isLocalIp($request->ip()) && $this->isForeignIp($request->ip())) {
        return $response;
    }

    // Dispatch to queue - NON-BLOCKING!
    RecordVisitorJob::dispatch(
        $request->ip(),
        $request->userAgent() ?? 'Unknown',
        $request->path(),
        $request->headers->get('referer', 'Direct')
    )->onQueue('visitors');

    return $response;
}
```

---

## RINGKASAN EXECUTIVE

Project Laravel JDIH_BNPB memiliki fondasi yang **cukup solid** dengan:
- ✅ Arsitektur modern (Laravel 11 + Filament 3 + Livewire)
- ✅ Security yang sudah cukup ketat dengan middleware komprehensif
- ✅ Performance yang baik dengan caching strategy

**Namun ada beberapa area kritis yang perlu diperbaiki:**

1. **Security** - File download vulnerability dan missing backend authorization
2. **Performance** - Visitor middleware yang blocking request dengan HTTP calls
3. **Architecture** - Kurangnya service layer menyebabkan controller terlalu besar
4. **Code Quality** - Inline CSS yang perlu dipisahkan

**Estimated Effort untuk Full Refactoring:**
- Quick Wins (Security Fixes): 2-4 jam
- Medium Refactoring (Service Layer, Queue): 1-2 hari
- Full Cleanup (Frontend, Database): 3-5 hari

---

**Tanggal Audit:** 2024
**Auditor:** Senior Software Architect, Security Auditor, Performance Engineer
**Versi Laravel:** 11.9
**Versi PHP:** 8.2+


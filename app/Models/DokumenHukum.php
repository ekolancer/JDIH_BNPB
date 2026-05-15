<?php

namespace App\Models;

use App\Observers\DokumenHukumObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy([DokumenHukumObserver::class])]
class DokumenHukum extends Model
{
    use HasFactory, Notifiable;

    // Legacy fields hidden (keberadaannya tetap aman bila ada dalam DB lama)
    protected $hidden = ['jenisDok', 'singkatan'];

    protected $appends = [
        'jenis_dokumen_label',
        'bidang_hukum_label',
        'file_tersedia',
        'download_allowed',
        'created_at_formatted',
    ];

    protected $fillable = [
        'tipeDok',
        'judul',
        'ref_jenis_dokumen_id',
        'slug',
        'teuBadan',
        'nomor',
        'tahun',
        'tempat',
        'penerbit',
        'tglPenetapan',
        'tglPengundangan',
        'tglDiBacakan',
        'nomorPanggil',
        'cetakanEdisi',
        'deskripsiFisik',
        'subjek',
        'sumber',
        'isbn',
        'status',
        'bahasa',
        'lokasi',
        'lampiran',
        'abstrak',
        'nomorIndukBuku',
        'penandatangan',
        'download_count',
        'document_uid',
        'view_count',
    ];

    /***************************************************************
     *  RELATIONS
     ***************************************************************/
    public function jenisDokumen()
    {
        return $this->belongsTo(RefJenisDokumen::class, 'ref_jenis_dokumen_id');
    }

    public function bidangHukumRef()
    {
        return $this->belongsTo(RefBidangHukum::class, 'ref_bidang_hukum_id');
    }

    public function subjekRef()
    {
        return $this->belongsToMany(
            \App\Models\RefSubjek::class,
            'dokumen_subjek',
            'dokumen_hukum_id',
            'subjek_id'
        );
    }

    /**
     * Alias for compatibility. Some legacy code refers to subjekHukum().
     */
    public function subjekHukum()
    {
        return $this->subjekRef();
    }

    /***************************************************************
     *  EVENT MODEL (CREATING + UPDATING + DELETED)
     ***************************************************************/
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->generateDocumentUid();
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });

        static::updating(function ($model) {

            // Trigger slug regeneration if relevant fields changed
            $slugTriggers = [
                'ref_jenis_dokumen_id',
                'nomor',
                'tahun',
                'judul',
                'tipeDok'
            ];

            foreach ($slugTriggers as $column) {
                if ($model->isDirty($column)) {
                    $model->slug = $model->generateSlug();
                    break;
                }
            }

            // Trigger UID regeneration if reference keys changed
            if ($model->isDirty(['ref_jenis_dokumen_id', 'nomor', 'tahun'])) {
                $model->generateDocumentUid();
            }

            // File deletion logic (lampiran)
            if ($model->isDirty('lampiran')) {
                $old = $model->getOriginal('lampiran');
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            // File deletion logic (abstrak)
            if ($model->isDirty('abstrak')) {
                $old = $model->getOriginal('abstrak');
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        static::deleted(function ($model) {
            if ($model->lampiran && Storage::disk('public')->exists($model->lampiran)) {
                Storage::disk('public')->delete($model->lampiran);
            }

            if ($model->abstrak && Storage::disk('public')->exists($model->abstrak)) {
                Storage::disk('public')->delete($model->abstrak);
            }
        });
    }

    /***************************************************************
     *  SLUG GENERATOR — menggunakan master ref_jenis_dokumen
     ***************************************************************/
    public function generateSlug(): string
    {
        $singkatan = $this->jenisDokumen->singkatan ?? '';

        if (in_array($this->tipeDok, ['Putusan', 'Peraturan'])) {
            return Str::slug("{$singkatan}-nomor-{$this->nomor}-tahun-{$this->tahun}");
        }

        return Str::slug($this->judul ?? $this->document_uid ?? 'dokumen');
    }

    /***************************************************************
     *  UID FORMAT: PERKA/BNPB/01/2024
     ***************************************************************/
    public function generateDocumentUid(): void
    {
        if (!$this->ref_jenis_dokumen_id || !$this->nomor || !$this->tahun) {
            return;
        }

        $singkatan = $this->jenisDokumen->singkatan ?? null;
        if (!$singkatan) {
            return;
        }

        $prefix = strtoupper(trim($singkatan));
        $kodeLembaga = config('app.lembaga', 'BNPB');

        $nomor = str_pad(preg_replace('/\D/', '', $this->nomor), 2, '0', STR_PAD_LEFT);
        $tahun = $this->tahun;

        $uid = "{$prefix}/{$kodeLembaga}/{$nomor}/{$tahun}";
        $originalUid = $uid;

        // Avoid duplicate UID by suffix
        $counter = 1;
        while (
            self::where('document_uid', $uid)
            ->when($this->id, fn($q) => $q->where('id', '!=', $this->id))
            ->exists()
        ) {
            $uid = "{$originalUid}-{$counter}";
            $counter++;
        }

        $this->document_uid = $uid;
    }

    /***************************************************************
     *  HELPERS / METADATA
     ***************************************************************/
    /**
     * Metadata dokumen berdasarkan tipeDok,
     * sudah di-refactor untuk memakai relasi ref_jenis_dokumen.
     */
    public function getMetadataAttribute()
    {
        // Ambil nama & singkatan dari master jenis dokumen
        $jenisNama   = $this->jenisDokumen->nama ?? '-';
        $singkatan   = $this->jenisDokumen->singkatan ?? '-';
        $subjekHukum = $this->subjekRef;

        // Helper aman untuk tanggal
        $fmt = fn($t) =>
        $t ? \Carbon\Carbon::parse($t)->locale('id')->translatedFormat('d F Y') : '-';

        $base = [
            'Jenis Dokumen'     => $jenisNama,
            'Judul'             => $this->judul ?? '-',
            'Nomor'             => $this->nomor ?? '-',
            'Tahun Terbit'      => $this->tahun ?? '-',
            'Tajuk Entri Utama' => $this->teuBadan ?? '-',
            'Bidang Hukum'      => $this->bidangHukumRef,
            'Subjek'            => $subjekHukum ?? '-',
            'Tanggal Penetapan'   => $fmt($this->tglPenetapan),
            'Tanggal Pengundangan' => $fmt($this->tglPengundangan),
            'Tempat Terbit'     => $this->tempat ?? '-',
            'Sumber'            => $this->sumber ?? '-',
            'Bahasa'            => $this->bahasa ?? '-',
            'Lokasi'            => $this->lokasi ?? '-',
            'Penandatangan'     => $this->penandatangan ?? '-',
        ];

        // Template per-tipe
        $templates = [
            'Putusan' => array_merge($base, [
                'Nomor Putusan'  => $this->nomor ?? '-',
                'Tempat Peradilan' => $this->tempat ?? '-',
                'Penerbit'       => $this->penerbit ?? '-',
                'Tanggal DiBacakan' => $fmt($this->tglDiBacakan),
            ]),

            'Monografi' => [
                'Jenis Dokumen'     => $jenisNama,
                'Judul'             => $this->judul ?? '-',
                'Bidang Hukum'      => $this->bidangHukumRef,
                'Subjek'            => $subjekHukum ?? '-',
                'Tahun Terbit'      => $this->tahun ?? '-',
                'Tajuk Entri Utama' => $this->teuBadan ?? '-',
                'Tempat Terbit'     => $this->tempat ?? '-',
                'Penerbit'          => $this->penerbit ?? '-',
                'Cetakan / Edisi'   => $this->cetakanEdisi ?? '-',
                'ISBN'              => $this->isbn ?? '-',
                'Bahasa'            => $this->bahasa ?? '-',
                'Lokasi'            => $this->lokasi ?? '-',
            ],

            'Artikel' => [
                'Jenis Dokumen'     => $jenisNama,
                'Judul'             => $this->judul ?? '-',
                'Tajuk Entri Utama' => $this->teuBadan ?? '-',
                'Bidang Hukum'      => $this->bidangHukumRef,
                'Sumber'            => $this->sumber ?? '-',
                'Tahun Terbit'      => $this->tahun ?? '-',
                'Tempat Terbit'     => $this->tempat ?? '-',
                'Penerbit'          => $this->penerbit ?? '-',
                'Subjek'            => $subjekHukum ?? '-',
                'Status'            => $this->status ?? '-',
                'Bahasa'            => $this->bahasa ?? '-',
                'Lokasi'            => $this->lokasi ?? '-',
            ],
        ];

        return $templates[$this->tipeDok] ?? $base;
    }

    public function getTglPenetapanFormattedAttribute()
    {
        return $this->tglPenetapan ? Carbon::parse($this->tglPenetapan)->translatedFormat('d F Y') : null;
    }

    public function getTglPengundanganFormattedAttribute()
    {
        return $this->tglPengundangan ? Carbon::parse($this->tglPengundangan)->translatedFormat('d F Y') : null;
    }

    public function getLampiranSizeAttribute(): array
    {
        if (!$this->lampiran || !Storage::disk('public')->exists($this->lampiran)) {
            return ['kb' => 0, 'mb' => 0];
        }

        $kb = Storage::disk('public')->size($this->lampiran) / 1024;

        return [
            'kb' => round($kb, 2),
            'mb' => round($kb / 1024, 2)
        ];
    }

    public function getAbstrakSizeAttribute(): array
    {
        if (!$this->abstrak || !Storage::disk('public')->exists($this->abstrak)) {
            return ['kb' => 0, 'mb' => 0];
        }

        $kb = Storage::disk('public')->size($this->abstrak) / 1024;

        return [
            'kb' => round($kb, 2),
            'mb' => round($kb / 1024, 2)
        ];
    }

    public function getButtonTextAttribute(): string
    {
        return $this->tipeDok === 'Monografi' ? 'Cover' : 'Baca';
    }

    /**
     * Route key menggunakan slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Ambil nilai unik dari beberapa kolom (berguna untuk filter)
     *
     * @param array $columns
     * @return array
     */
    public static function getUniqueValues(array $columns): array
    {
        $uniqueValues = [];
        foreach ($columns as $column) {
            // special case untuk ref_jenis_dokumen_id — kembalikan nama
            if ($column === 'ref_jenis_dokumen_id') {
                $uniqueValues[$column] = DB::table('ref_jenis_dokumen')
                    ->select('id', 'nama')
                    ->orderBy('nama')
                    ->get()
                    ->mapWithKeys(fn($r) => [$r->id => $r->nama])
                    ->toArray();
                continue;
            }

            $uniqueValues[$column] = self::select($column)
                ->distinct()
                ->pluck($column)
                ->filter(fn($v) => !is_null($v) && $v !== '')
                ->values()
                ->toArray();
        }
        return $uniqueValues;
    }

    /***************************************************************
     *  QUERY HELPERS / SCOPES / AGGREGATIONS
     ***************************************************************/
    /**
     * Hitung jumlah dokumen per tipeDok (Peraturan/Monografi/Artikel/Putusan).
     *
     * @return array
     */
    public static function countByType(): array
    {
        $result = self::select('tipeDok', DB::raw('COUNT(*) as total'))
            ->groupBy('tipeDok')
            ->pluck('total', 'tipeDok')
            ->toArray();

        $types = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];
        return array_merge(array_fill_keys($types, 0), $result);
    }

    /**
     * Ambil dokumen terbaru (option limit dan tipe)
     */
    public function scopeLatestPeraturan(Builder $query, int $limit = 4)
    {
        return $query
            ->where('tipeDok', 'Peraturan')
            ->where('status', 'BERLAKU')
            ->with(['jenisDokumen', 'bidangHukumRef'])
            ->orderByDesc('tglPengundangan')
            ->limit($limit);
    }

    /**
     * Ambil dokumen terpopuler berdasarkan view_count
     */
    public function scopeMostViewedPeraturan(Builder $query, int $limit = 8)
    {
        return $query
            ->where('tipeDok', 'Peraturan')
            ->where('status', 'BERLAKU')
            ->with(['jenisDokumen', 'bidangHukumRef'])
            ->orderByDesc('view_count')
            ->limit($limit);
    }

    /**
     * Scope untuk mencari dokumen berdasarkan tipe dan slug
     */
    public function scopeFindBySlug(Builder $query, $tipeDok, $slug)
    {
        return $query->where('tipeDok', $tipeDok)->where('slug', $slug);
    }

    /**
     * Scope yang mengembalikan grouping counts untuk beberapa kolom
     * Jika kolom adalah ref_jenis_dokumen_id, fungsi akan mengembalikan nama jenis sebagai key.
     */
    public function scopeGroupByColumns(Builder $query)
    {
        $groupableColumns = [
            'ref_jenis_dokumen_id',
            'penerbit',
            'status',
            'ref_bidang_hukum_id',
        ];

        // Preload ref data sekali (menghindari N+1 query di dalam loop)
        $jenisMap = DB::table('ref_jenis_dokumen')
            ->pluck('nama', 'id')
            ->toArray();

        $bidangMap = DB::table('ref_bidang_hukum')
            ->pluck('nama', 'id')
            ->toArray();

        $data = [];

        foreach ($groupableColumns as $column) {
            if ($column === 'ref_jenis_dokumen_id') {
                $rows = $query->select('ref_jenis_dokumen_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('ref_jenis_dokumen_id')
                    ->get();

                $mapped = [];
                foreach ($rows as $r) {
                    $name = $jenisMap[$r->ref_jenis_dokumen_id] ?? 'Tidak Diketahui';
                    $mapped[$name] = $r->count;
                }
                $data[$column] = $mapped;
            } elseif ($column === 'ref_bidang_hukum_id') {
                $rows = $query->select('ref_bidang_hukum_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('ref_bidang_hukum_id')
                    ->get();

                $mapped = [];
                foreach ($rows as $r) {
                    $name = $bidangMap[$r->ref_bidang_hukum_id] ?? 'Tidak Diketahui';
                    $mapped[$name] = $r->count;
                }
                $data[$column] = $mapped;
            } else {
                $rows = $query->select($column, DB::raw('COUNT(*) as count'))
                    ->groupBy($column)
                    ->get();
                $mapped = $rows->pluck('count', $column)->toArray();
                $data[$column] = $mapped;
            }
        }

        return $data;
    }

    public function scopeApplyFilters(Builder $query, array $filters): void
    {
        $query->when(
            $filters['judul'] ?? null,
            fn($q, $v) => $q->whereFullText('judul', $v)
        );

        $query->when(
            $filters['nomor'] ?? null,
            fn($q, $v) => $q->where('nomor', 'like', "%{$v}%")
        );

        $query->when(
            $filters['tahun'] ?? null,
            fn($q, $v) => $q->where('tahun', $v)
        );

        $query->when(
            $filters['tipeDok'] ?? null,
            fn($q, $v) => $q->where('tipeDok', $v)
        );

        $query->when(
            $filters['ref_jenis_dokumen_id'] ?? [],
            fn($q, $v) => $q->whereIn('ref_jenis_dokumen_id', $v)
        );

        $query->when(
            $filters['ref_bidang_hukum_id'] ?? [],
            fn($q, $v) => $q->whereIn('ref_bidang_hukum_id', $v)
        );

        $query->when(
            $filters['status'] ?? [],
            fn($q, $v) => $q->whereIn('status', $v)
        );

        $query->when(
            $filters['subjek'] ?? [],
            fn($q, $v) =>
            $q->whereHas(
                'subjekRef',
                fn($sq) => $sq->whereIn('ref_subjek.id', $v)
            )
        );
    }

    /* ==========================================================
     *  QUERY SCOPES — SORTING
     * ========================================================*/
    public function scopeApplySorting(Builder $query, string $sortBy): void
    {
        match ($sortBy) {
            'popular' => $query->orderByDesc('view_count'),
            default   => $query->orderByDesc('tahun')->orderByDesc('nomor'),
        };
    }

    /***************************************************************
     *  ACCESSORS (UNTUK FRONTEND)
     ***************************************************************/
    /***************************************************************
     *  ACCESSOR: jenis_dokumen_label
     *
     *  CATATAN PENTING:
     *  - Atribut `jenis_dokumen_label` BUKAN kolom database
     *  - Atribut ini dihasilkan oleh method:
     *      getJenisDokumenLabelAttribute()
     *  - Laravel otomatis mengubah:
     *      JenisDokumenLabel -> jenis_dokumen_label (snake_case)
     *  - Digunakan untuk FRONTEND (Blade / API)
     *  - Frontend TIDAK BOLEH mengakses relasi `jenisDokumen` langsung
     ***************************************************************/
    public function getJenisDokumenLabelAttribute(): string
    {
        return $this->jenisDokumen
            ? " {$this->jenisDokumen->nama}"
            : '-';
    }

    public function getJenisDokumenSingkatanAttribute(): string
    {
        return $this->jenisDokumen
            ? strtoupper($this->jenisDokumen->singkatan)
            : '-';
    }

    /***************************************************************
     *  ACCESSOR: bidang_hukum_label
     *
     *  CATATAN PENTING:
     *  - Atribut `bidang_hukum_label` BUKAN kolom database
     *  - Atribut ini dihasilkan oleh method:
     *      getBidangHukumLabelAttribute()
     *  - Laravel otomatis mengubah:
     *      BidangHukumLabel -> bidang_hukum_label (snake_case)
     *  - Digunakan untuk FRONTEND (Blade / API)
     *  - Frontend TIDAK BOLEH mengakses relasi `bidangHukumRef` langsung
     ***************************************************************/

    public function getBidangHukumLabelAttribute(): string
    {
        return $this->bidangHukumRef?->nama ?? '-';
    }

    /***************************************************************
     *  ACCESSOR: DOWNLOAD & FILE HELPERS (DETAIL BLADE SAFE)
     ***************************************************************/

    public function getTipeDokLabelAttribute()
    {
        return match ($this->tipeDok) {
            'Peraturan' => 'Peraturan Perundang-Undangan',
            'Monografi' => 'Monografi Hukum',
            'Artikel'   => 'Artikel Hukum',
            'Putusan'   => 'Putusan Pengadilan',
            default     => $this->tipeDok,
        };
    }

    public function getDownloadAllowedAttribute(): bool
    {
        return $this->status !== 'TIDAK BERLAKU';
    }

    public function getLampiranExistsAttribute(): bool
    {
        if (!$this->lampiran) {
            return false;
        }

        return Storage::disk('public')->exists($this->lampiran);
    }

    public function getFileTersediaAttribute(): bool
    {
        if (!$this->download_allowed) {
            return false;
        }

        if (!$this->lampiran_exists) {
            return false;
        }

        return ($this->lampiran_size['kb'] ?? 0) > 0;
    }

    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at
            ? $this->created_at->locale('id')->translatedFormat('d F Y')
            : '-';
    }

    public function getPdfViewUrlAttribute(): ?string
    {
        if (!$this->file_tersedia) {
            return null;
        }

        return route('dokumen.view', $this->slug);
    }

    public function getIsMonografiAttribute(): bool
    {
        return $this->tipeDok === 'Monografi';
    }

    public function getIsArtikelAttribute(): bool
    {
        return $this->tipeDok === 'Artikel';
    }
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->lampiran_exists) {
            return Storage::url($this->lampiran);
        }

        return asset('img/noimage.png');
    }
    public function getFileExtensionAttribute(): ?string
    {
        return $this->lampiran
            ? strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION))
            : null;
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->file_extension === 'pdf';
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->file_extension, ['jpg', 'jpeg', 'png', 'webp']);
    }
}

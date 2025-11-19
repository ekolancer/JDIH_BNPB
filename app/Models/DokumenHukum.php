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
    protected $hidden = ['jenisDok', 'singkatar'];

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
        'bidangHukum',
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
     *  UID OFFICIAL FORMAT BPHN: PERKA/BNPB/01/2024
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

        // Helper aman untuk tanggal
        $fmt = fn($t) =>
        $t ? \Carbon\Carbon::parse($t)->locale('id')->translatedFormat('d F Y') : '-';

        $base = [
            'Jenis Dokumen'     => $jenisNama,
            'Singkatan'         => $singkatan,
            'Judul'             => $this->judul ?? '-',
            'Tajuk Entri Utama' => $this->teuBadan ?? '-',
            'Nomor'             => $this->nomor ?? '-',
            'Tahun Terbit'      => $this->tahun ?? '-',
            'Tempat Terbit'     => $this->tempat ?? '-',
            'Subjek'            => $this->subjek ?? '-',
            'Sumber'            => $this->sumber ?? '-',
            'Bahasa'            => $this->bahasa ?? '-',
            'Lokasi'            => $this->lokasi ?? '-',
            'Bidang Hukum'      => $this->bidangHukum ?? '-',
            'Penandatangan'     => $this->penandatangan ?? '-',
        ];

        // Template per-tipe
        $templates = [
            'Peraturan' => array_merge($base, [
                'Tanggal Penetapan'   => $fmt($this->tglPenetapan),
                'Tanggal Pengundangan' => $fmt($this->tglPengundangan),
            ]),

            'Putusan' => array_merge($base, [
                'Nomor Putusan'  => $this->nomor ?? '-',
                'Tempat Peradilan' => $this->tempat ?? '-',
                'Penerbit'       => $this->penerbit ?? '-',
                'Tanggal DiBacakan' => $fmt($this->tglDiBacakan),
            ]),

            'Monografi' => [
                'Jenis Dokumen'     => $jenisNama,
                'Judul'             => $this->judul ?? '-',
                'Tajuk Entri Utama' => $this->teuBadan ?? '-',
                'Tahun Terbit'      => $this->tahun ?? '-',
                'Tempat Terbit'     => $this->tempat ?? '-',
                'Penerbit'          => $this->penerbit ?? '-',
                'Nomor Panggil'     => $this->nomorPanggil ?? '-',
                'Cetakan / Edisi'   => $this->cetakanEdisi ?? '-',
                'Deskripsi Fisik'   => $this->deskripsiFisik ?? '-',
                'Subjek'            => $this->subjek ?? '-',
                'ISBN'              => $this->isbn ?? '-',
                'Bahasa'            => $this->bahasa ?? '-',
                'Lokasi'            => $this->lokasi ?? '-',
                'Bidang Hukum'      => $this->bidangHukum ?? '-',
                'Nomor Induk Buku'  => $this->nomorIndukBuku ?? '-',
            ],

            'Artikel' => [
                'Jenis Dokumen'     => $jenisNama,
                'Judul'             => $this->judul ?? '-',
                'Tajuk Entri Utama' => $this->teuBadan ?? '-',
                'Tahun Terbit'      => $this->tahun ?? '-',
                'Tempat Terbit'     => $this->tempat ?? '-',
                'Penerbit'          => $this->penerbit ?? '-',
                'Subjek'            => $this->subjek ?? '-',
                'Sumber'            => $this->sumber ?? '-',
                'Status'            => $this->status ?? '-',
                'Bahasa'            => $this->bahasa ?? '-',
                'Lokasi'            => $this->lokasi ?? '-',
                'Bidang Hukum'      => $this->bidangHukum ?? '-',
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

    public function getLampiranSizeAttribute()
    {
        $path = storage_path("app/public/{$this->lampiran}");
        if (!file_exists($path)) return ['kb' => 0, 'mb' => 0];

        $kb = filesize($path) / 1024;
        return ['kb' => round($kb, 2), 'mb' => round($kb / 1024, 2)];
    }

    public function getAbstrakSizeAttribute()
    {
        $path = storage_path("app/public/{$this->abstrak}");
        if (!file_exists($path)) return ['kb' => 0, 'mb' => 0];

        $kb = filesize($path) / 1024;
        return ['kb' => round($kb, 2), 'mb' => round($kb / 1024, 2)];
    }

    public function getButtonTextAttribute(): string
    {
        return $this->tipeDok === 'Monografi' ? 'Cover' : 'Baca';
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
    public static function getLatest($limit = null, $tipeDok = 'Peraturan')
    {
        $q = self::query()->when($tipeDok, fn($q) => $q->where('tipeDok', $tipeDok));
        return $limit ? $q->orderBy('created_at', 'desc')->take($limit)->get() : $q->orderBy('created_at', 'desc')->get();
    }

    /**
     * Ambil dokumen terpopuler berdasarkan view_count
     */
    public static function getMostViewed($limit = null, $tipeDok = null)
    {
        $q = self::query()->when($tipeDok, fn($q) => $q->where('tipeDok', $tipeDok));
        return $limit ? $q->orderBy('view_count', 'desc')->take($limit)->get() : $q->orderBy('view_count', 'desc')->get();
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

    /**
     * Route key menggunakan slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
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
            'subjek',
            'status',
            'bidangHukum',
        ];

        $data = [];

        foreach ($groupableColumns as $column) {
            if ($column === 'ref_jenis_dokumen_id') {
                $rows = $query->select('ref_jenis_dokumen_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('ref_jenis_dokumen_id')
                    ->get();

                $mapped = [];
                foreach ($rows as $r) {
                    $name = DB::table('ref_jenis_dokumen')->where('id', $r->ref_jenis_dokumen_id)->value('nama') ?? 'Tidak Diketahui';
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
}

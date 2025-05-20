<?php

namespace App\Models;

use App\Observers\DokumenHukumObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy([DokumenHukumObserver::class])]
class DokumenHukum extends Model
{
    use HasFactory, Notifiable;

    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'judul',
        'slug',
        'teuBadan',
        'nomor',
        'tahun',
        'singkatan',
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
    ];


    protected static function boot()
    {
        parent::boot();

        // Event `creating` untuk membuat id dan slug dinamis
        static::creating(function ($dokumenHukum) {

            // Generate slug jika belum ada            
            if (empty($dokumenHukum->slug)) {
                $dokumenHukum->slug = $dokumenHukum->generateSlug();
            }
        });

        static::updating(function ($dokumenHukum) {
            // Daftar kolom yang memengaruhi slug
            $slugColumns = ['singkatan', 'nomor', 'tahun', 'judul', 'tipeDok'];

            foreach ($slugColumns as $column) {
                if ($dokumenHukum->isDirty($column)) {
                    // Jika salah satu kolom berubah, generate slug baru
                    $dokumenHukum->slug = $dokumenHukum->generateSlug();
                    break;
                }
            }

            // Hapus file lampiran lama jika kolom 'lampiran' diperbarui
            if ($dokumenHukum->isDirty('lampiran')) {
                $oldLampiran = $dokumenHukum->getOriginal('lampiran'); // Ambil nilai lama lampiran
                if ($oldLampiran && Storage::disk('public')->exists($oldLampiran)) {
                    Storage::disk('public')->delete($oldLampiran);
                }
            }

            // Hapus file abstrak lama jika kolom 'abstrak' diperbarui
            if ($dokumenHukum->isDirty('abstrak')) {
                $oldAbstrak = $dokumenHukum->getOriginal('abstrak'); // Ambil nilai lama abstrak
                if ($oldAbstrak && Storage::disk('public')->exists($oldAbstrak)) {
                    Storage::disk('public')->delete($oldAbstrak);
                }
            }
        });

        // Event `deleted` untuk menghapus file terkait setelah data dihapus
        static::deleted(function ($dokumenHukum) {
            // Menghapus file lampiran jika ada
            if ($dokumenHukum->lampiran && Storage::disk('public')->exists($dokumenHukum->lampiran)) {
                Storage::disk('public')->delete($dokumenHukum->lampiran);
            }

            // Menghapus file abstrak jika ada
            if ($dokumenHukum->abstrak && Storage::disk('public')->exists($dokumenHukum->abstrak)) {
                Storage::disk('public')->delete($dokumenHukum->abstrak);
            }
        });
    }

    /**
     * Generate slug berdasarkan tipe dokumen.
     *
     * @return string
     */
    public function generateSlug()
    {
        switch ($this->tipeDok) {
            case 'Putusan':
            case 'Peraturan':
                return Str::slug("{$this->singkatan}-nomor-{$this->nomor}-tahun-{$this->tahun}");

            default:
                // Untuk tipe dokumen yang tidak dikenali, return slug dari judul atau nilai default lainnya
                return Str::slug($this->judul);
        }
    }

    // Menghitung jumlah dokumen berdasarkan tipe
    public static function countByType()
    {
        $result = self::select('tipeDok', DB::raw('COUNT(*) as total'))
            ->groupBy('tipeDok')
            ->pluck('total', 'tipeDok')
            ->toArray();

        // Tambahkan default 0 untuk tipe dokumen yang tidak ada
        $types = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];
        return array_merge(array_fill_keys($types, 0), $result);
    }


    // Mendapatkan dokumen terbaru
    public static function getLatest($limit = null, $tipeDok = 'peraturan')
    {
        return self::where('tipeDok', $tipeDok)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    // Mendapatkan dokumen terpopuler
    public static function getMostViewed($limit = null, $tipeDok = 'peraturan')
    {
        return self::where('tipeDok', $tipeDok)
            ->orderBy('view_count', 'desc')
            ->take($limit)
            ->get();
    }

    // Fungsi untuk mengambil nilai unik dari kolom tertentu
    public static function getUniqueValues(array $columns)
    {
        $uniqueValues = [];
        foreach ($columns as $column) {
            $uniqueValues[$column] = self::select($column)
                ->distinct()
                ->pluck($column)
                ->values()
                ->toArray();
        }
        return $uniqueValues;
    }

    public function getRouteKeyName()
    {
        return 'slug'; // Laravel akan otomatis mencari dokumen berdasarkan slug, bukan ID
    }

    // Scope untuk filter berdasarkan tipe dan slug
    public function scopeFindBySlug($query, $tipeDok, $slug)
    {
        return $query->where('tipeDok', $tipeDok)->where('slug', $slug);
    }

    // Accessor untuk format tanggal otomatis
    public function getTglPenetapanFormattedAttribute()
    {
        return $this->tglPenetapan ? Carbon::parse($this->tglPenetapan)->locale('id')->translatedFormat('d F Y') : null;
    }

    public function getTglPengundanganFormattedAttribute()
    {
        return $this->tglPengundangan ? Carbon::parse($this->tglPengundangan)->locale('id')->translatedFormat('d F Y') : null;
    }

    // Accessor untuk mendapatkan ukuran file dalam KB & MB
    public function getLampiranSizeAttribute()
    {
        $filePath = storage_path("app/public/{$this->lampiran}");
        if (!file_exists($filePath)) return ['kb' => 0, 'mb' => 0];

        $sizeInKb = round(filesize($filePath) / 1024, 2);
        return [
            'kb' => $sizeInKb,
            'mb' => round($sizeInKb / 1024, 2),
        ];
    }

    public function getAbstrakSizeAttribute()
    {
        $filePath = storage_path("app/public/{$this->abstrak}");
        if (!file_exists($filePath)) return ['kb' => 0, 'mb' => 0];

        $sizeInKb = round(filesize($filePath) / 1024, 2);
        return [
            'kb' => $sizeInKb,
            'mb' => round($sizeInKb / 1024, 2),
        ];
    }

    // Method untuk mendapatkan metadata berdasarkan tipe
    public function getMetadataAttribute()
    {
        $metadataTemplates = [
            'Peraturan' => [
                'Jenis Dokumen' => $this->jenisDok,
                'Judul' => $this->judul,
                'Tajuk Entri Utama' => $this->teuBadan,
                'Nomor' => $this->nomor,
                'Tahun Terbit' => $this->tahun,
                'Tempat Terbit' => $this->tempat,
                'Tanggal Penetapan' => \Carbon\Carbon::parse($this->tglPenetapan)->locale('id')->translatedFormat('d F Y'),
                'Tanggal Pengundangan' => \Carbon\Carbon::parse($this->tglPengundangan)->locale('id')->translatedFormat('d F Y'),
                'Subjek' => $this->subjek,
                'Sumber' => $this->sumber,
                'Bahasa' => $this->bahasa,
                'Lokasi' => $this->lokasi,
                'Bidang Hukum' => $this->bidangHukum,
                'Penandatangan' => $this->penandatangan,
            ],
            'Putusan' => [
                'Jenis Dokumen' => $this->jenisDok,
                'Judul' => $this->judul,
                'Tajuk Entri Utama' => $this->teuBadan,
                'Nomor Putusan' => $this->nomor,
                'Tahun Terbit' => $this->tahun,
                'Singkatan' => $this->singkatan,
                'Tempat Peradilan' => $this->tempat,
                'Penerbit' => $this->penerbit,
                'Tanggal DiBacakan' => \Carbon\Carbon::parse($this->tglDiBacakan)->locale('id')->translatedFormat('d F Y'),
                'Subjek' => $this->subjek,
                'Sumber' => $this->sumber,
                'Bahasa' => $this->bahasa,
                'Lokasi' => $this->lokasi,
                'Bidang Hukum' => $this->bidangHukum,
                'Penandatangan' => $this->penandatangan,
            ],
            'Monografi' => [
                'Jenis Dokumen' => $this->jenisDok,
                'Judul' => $this->judul,
                'Tajuk Entri Utama' => $this->teuBadan,
                'Tahun Terbit' => $this->tahun,
                'Tempat Terbit' => $this->tempat,
                'Penerbit' => $this->penerbit,
                'Nomor Panggil' => $this->nomorPanggil,
                'Cetakan / Edisi' => $this->cetakanEdisi,
                'Deskripsi Fisik' => $this->deskripsiFisik,
                'Subjek' => $this->subjek,
                'ISBN' => $this->isbn,
                'Bahasa' => $this->bahasa,
                'Lokasi' => $this->lokasi,
                'Bidang Hukum' => $this->bidangHukum,
                'Nomor Induk Buku' => $this->nomorIndukBuku,
            ],
            'Artikel' => [
                'Jenis Dokumen' => $this->jenisDok,
                'Judul' => $this->judul,
                'Tajuk Entri Utama' => $this->teuBadan,
                'Tahun Terbit' => $this->tahun,
                'Tempat Terbit' => $this->tempat,
                'Penerbit' => $this->penerbit,
                'Subjek' => $this->subjek,
                'Sumber' => $this->sumber,
                'Status' => $this->status,
                'Bahasa' => $this->bahasa,
                'Lokasi' => $this->lokasi,
                'Bidang Hukum' => $this->bidangHukum,
            ],
        ];

        return $metadataTemplates[$this->tipeDok] ?? [];
    }

    // Method untuk menentukan teks tombol
    public function getButtonTextAttribute()
    {
        return $this->tipeDok == 'Monografi' ? 'Cover' : 'Baca';
    }

    public function scopeGroupByColumns(Builder $query)
    {
        // Daftar kolom yang ingin dikelompokkan
        $groupableColumns = [
            'jenisDok',
            'singkatan',
            'penerbit',
            'subjek',
            'status',
            'bidangHukum',
        ];

        $data = [];

        foreach ($groupableColumns as $column) {
            $data[$column] = $query->groupBy($column)
                ->selectRaw("$column, COUNT(*) as count")
                ->pluck('count', $column)
                ->toArray();
        }

        return $data;
    }
}

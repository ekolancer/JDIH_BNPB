<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenHukum extends Model
{
    use HasFactory;

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
        'issnIsbn',
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

        // Event `creating` untuk membuat slug
        static::creating(function ($dokumenHukum) {
            if (empty($dokumenHukum->slug)) {
                $dokumenHukum->slug = $dokumenHukum->generateSlug();
            }
        });

        static::updating(function ($dokumenHukum) {
            // Update slug jika ada perubahan pada 'singkatan', 'nomor', atau 'tahunTerbit'
            if ($dokumenHukum->isDirty(['singkatan', 'nomor', 'tahunTerbit'])) {
                $dokumenHukum->slug = Str::slug("{$dokumenHukum->singkatan}-nomor-{$dokumenHukum->nomor}-tahun-{$dokumenHukum->tahun}");
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
            case 'Peraturan Perundang-Undangan':
                return Str::slug("{$this->singkatan}-nomor-{$this->nomor}-tahun-{$this->tahun}");

            case 'Monografi':
            case 'Artikel':
                return Str::slug($this->judul);

            case 'Putusan':
                return Str::slug("{$this->singkatan}-nomor-{$this->nomor}-tahun-{$this->tahun}");

            default:
                // Untuk tipe dokumen yang tidak dikenali, return slug dari judul atau nilai default lainnya
                return Str::slug($this->judul ?? 'dokumen-tanpa-judul');
        }
    }
}

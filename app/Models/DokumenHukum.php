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

        // Event `creating` untuk membuat id dan slug dinamis
        static::creating(function ($dokumenHukum) {
            
            // Generate ID
            $dokumenHukum->id = self::generateDynamicId($dokumenHukum->tipedok);

            // Generate slug jika belum ada            
            if (empty($dokumenHukum->slug)) {
                $dokumenHukum->slug = $dokumenHukum->generateSlug();
            }
        });

        static::updating(function ($dokumenHukum) {
            // Update slug jika ada perubahan pada 'singkatan', 'nomor', atau 'tahun'
            if ($dokumenHukum->isDirty(['singkatan', 'nomor', 'tahun'])) {
                $dokumenHukum->slug = Str::slug("{$dokumenHukum->singkatan}-nomor-{$dokumenHukum->nomor}-tahun-{$dokumenHukum->tahun}");
            }
        });
    }

    /**
     * Fungsi untuk generate ID dinamis berdasarkan tipeDok
     */
    private static function generateDynamicId($tipeDok)
    {
        // Mapping tipeDok ke kode singkat
        $mapping = [
            'Peraturan' => 'PUU',
            'Monografi' => 'MNG',
            'Artikel'   => 'ART',
            'Putusan'   => 'PTS',
        ];

        // Dapatkan kode berdasarkan tipeDok
        $kode = $mapping[$tipeDok] ?? null;

        if (!$kode) {
            throw new \Exception('Kode tidak ditemukan untuk tipeDok: ' . $tipeDok);
        }

        // Cari ID terakhir berdasarkan tipeDok
        $lastDokumen = self::where('tipedok', $tipeDok)
            ->orderBy('id', 'desc')
            ->first();

        // Tentukan nomor urut baru
        $lastId = $lastDokumen ? intval(substr($lastDokumen->id, strlen($kode) + 1)) : 0;
        $newId = $lastId + 1;

        // Format ID baru
        return $kode . '-' . str_pad($newId, 3, '0', STR_PAD_LEFT);
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
            case 'Peraturan Perundang-Undangan':
                return Str::slug("{$this->singkatan}-nomor-{$this->nomor}-tahun-{$this->tahun}");

            case 'Monografi':
            case 'Artikel':
                return Str::slug($this->judul);

            default:
                // Untuk tipe dokumen yang tidak dikenali, return slug dari judul atau nilai default lainnya
                return Str::slug($this->judul ?? 'dokumen-tanpa-judul');
        }
    }
}

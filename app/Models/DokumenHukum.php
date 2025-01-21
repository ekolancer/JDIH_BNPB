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
}

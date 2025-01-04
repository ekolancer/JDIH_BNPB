<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peraturan extends Model
{
    use HasFactory;

    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'singkatan',
        'judul',
        'teuBadan',
        'nomor',
        'tahunTerbit',
        'tempatPenetapan',
        'tglPenetapan',
        'tglPengundangan',
        'sumber',
        'subjek',
        'bidangHukum',
        'bahasa',
        'status',
        'lokasi',
        'lampiran',
        'urusanPemerintahan',
        'pemrakarsa',
        'penandatangan',
        'peraturanTerkait',
        'slug',
        'download_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($peraturan) {
            if (empty($peraturan->slug)) {
                $peraturan->slug = Str::slug("{$peraturan->singkatan}-nomor-{$peraturan->nomor}-tahun-{$peraturan->tahunTerbit}");
            }
        });

        static::updating(function ($peraturan) {
            // Update slug jika ada perubahan pada 'singkatan', 'nomor', atau 'tahunTerbit'
            if ($peraturan->isDirty(['singkatan', 'nomor', 'tahunTerbit'])) {
                $peraturan->slug = Str::slug("{$peraturan->singkatan}-nomor-{$peraturan->nomor}-tahun-{$peraturan->tahunTerbit}");
            }

            // Hapus file lampiran lama jika kolom 'lampiran' diperbarui
            if ($peraturan->isDirty('lampiran')) {
                $oldLampiran = $peraturan->getOriginal('lampiran'); // Ambil nilai lama lampiran
                if ($oldLampiran && Storage::disk('public')->exists($oldLampiran)) {
                    Storage::disk('public')->delete($oldLampiran);
                }
            }
        });

        static::deleted(function ($peraturan) {
            // Menghapus file lampiran setelah data dihapus
            if ($peraturan->lampiran && Storage::disk('public')->exists($peraturan->lampiran)) {
                Storage::disk('public')->delete($peraturan->lampiran);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Putusan extends Model
{
    use HasFactory;
    
    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'judul',
        'teuBadan',
        'nomor',
        'tahunTerbit',
        'singkatan',
        'tempatPenetapan',
        'penerbit',
        'tglPenetapan',
        'tglPengundangan',
        'subjek',
        'sumber',
        'status',
        'bahasa',
        'lokasi',
        'bidangHukum',
        'lampiran',
        'penandatangan',
        'dokumenTerkait',
        'slug',
        'download_count',
    ];

    /**
     * Konfigurasi slug.
     */
    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('judul') // Sumber slug, misalnya dari kolom 'judul'
    //         ->saveSlugsTo('slug')       // Target kolom untuk slug
    //         ->doNotGenerateSlugsOnUpdate(); // Pilihan jika slug tidak boleh berubah saat data diperbarui
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($putusan) {
            if (empty($putusan->slug)) {
                $putusan->slug = Str::slug($putusan->judul);
            }
        });
    }
}

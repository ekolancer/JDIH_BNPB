<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monograf extends Model
{
    use HasFactory;

    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'judul',
        'teuBadan',
        'tahunTerbit',
        'tempatPenetapan',
        'penerbit',
        'tglPenetapan',
        'tglPengundangan',
        'noPanggil',
        'cetakanEdisi',
        'deskripsiFisik',
        'subjek',
        'isbn',
        'bahasa',
        'lokasi',
        'bidangHukum',
        'lampiran',
        'nomorIndukBuku',
        'slug',
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

        static::creating(function ($monograf) {
            if (empty($monograf->slug)) {
                $monograf->slug = Str::slug($monograf->judul);
            }
        });
    }
}

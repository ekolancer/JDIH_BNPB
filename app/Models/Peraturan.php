<?php

namespace App\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
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
        'tahun',
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
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('judul') // Sumber slug, misalnya dari kolom 'judul'
            ->saveSlugsTo('slug')       // Target kolom untuk slug
            ->doNotGenerateSlugsOnUpdate(); // Pilihan jika slug tidak boleh berubah saat data diperbarui
    }
}

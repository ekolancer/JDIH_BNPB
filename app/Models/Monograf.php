<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($monograf) {
            if (empty($monograf->slug)) {
                $monograf->slug = Str::slug($monograf->judul);
            }
        });

        static::deleted(function ($monograf) {
            // Menghapus file lampiran setelah data dihapus
            if ($monograf->lampiran && Storage::exists('public/' . $monograf->lampiran)) {
                Storage::delete('public/' . $monograf->lampiran);
            }
        });
    }
}

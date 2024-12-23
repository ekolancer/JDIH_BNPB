<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artikel extends Model
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
        'subjek',
        'sumber',
        'bahasa',
        'lokasi',
        'bidangHukum',
        'lampiran',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        static::deleted(function ($artikel) {
            // Menghapus file lampiran setelah data dihapus
            if ($artikel->lampiran && Storage::exists('public/' . $artikel->lampiran)) {
                Storage::delete('public/' . $artikel->lampiran);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'penulis',
        'kategori',
        'gambar',
        'tanggal_publikasi',
        'tag',
        'view_count',
    ];

    protected $casts = [
        'tag' => 'array',
        'tanggal_publikasi' => 'datetime',
        'status' => 'boolean',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });

        static::deleted(function ($berita) {
            // Menghapus file lampiran setelah data dihapus
            if ($berita->lampiran && Storage::exists('public/' . $berita->lampiran)) {
                Storage::delete('public/' . $berita->lampiran);
            }
        });
    }
}

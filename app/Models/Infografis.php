<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Infografis extends Model
{
    use HasFactory;

    protected $table = 'infografis';

    protected $fillable = [
        'judul',
        'slug',
        'tag',
        'gambar',
        'view_count',
    ];

    protected $casts = [
        'tag' => 'array',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($infografis) {
            if (empty($infografis->slug)) {
                $infografis->slug = Str::slug($infografis->judul);
            }
        });

        static::deleted(function ($infografis) {
            // Menghapus file lampiran setelah data dihapus
            if ($infografis->lampiran && Storage::exists('public/lampiran/infografis/' . $infografis->lampiran)) {
                Storage::delete('public/lampiran/infografis/' . $infografis->lampiran);
            }
        });
    }
}

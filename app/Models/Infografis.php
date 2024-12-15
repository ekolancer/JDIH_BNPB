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

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        // static::deleting(function ($infografis) {
        //     if ($infografis->gambar && Storage::exists($infografis->gambar)) {
        //         Storage::delete($infografis->gambar);
        //     }
        // });
    }
}

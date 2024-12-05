<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
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
    ];

    protected $appends = ['status'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }

    // Accessor untuk membaca status sebagai boolean
    // // Accessor: Mengonversi ENUM ke boolean
    // public function getStatusBooleanAttribute(): bool
    // {
    //     return $this->status === 'Publikasi'; // Publikasi → true, Draft → false
    // }

    // Mutator: Menyimpan boolean sebagai ENUM
    public function setStatusBooleanAttribute(bool $value): void
    {
        $this->attributes['status'] = $value ? 'Publikasi' : 'Draft'; // true → Publikasi, false → Draft
    }
}

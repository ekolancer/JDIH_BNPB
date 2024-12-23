<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($putusan) {
            if (empty($putusan->slug)) {
                $putusan->slug = Str::slug($putusan->judul);
            }
        });

        static::deleted(function ($putusan) {
            // Menghapus file lampiran setelah data dihapus
            if ($putusan->lampiran && Storage::exists('public/' . $putusan->lampiran)) {
                Storage::delete('public/' . $putusan->lampiran);
            }
        });
    }
}

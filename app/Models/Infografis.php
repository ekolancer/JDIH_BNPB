<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Observers\InfografisObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([InfografisObserver::class])]
class Infografis extends Model
{
    use HasFactory, Notifiable;

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

    // Method untuk mengambil data infografis yang aktif
    public static function getInfografisActive()
    {
        return Infografis::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
    }
}

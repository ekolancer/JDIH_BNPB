<?php

namespace App\Models;

use App\Observers\BeritaObserver;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


#[ObservedBy([BeritaObserver::class])]
class Berita extends Model
{
    use HasFactory, Notifiable, LogsActivity;

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

    /***************************************************************
     *  BOOT
     ***************************************************************/
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });

        static::deleted(function ($berita) {
            if ($berita->gambar && Storage::exists('public/' . $berita->gambar)) {
                Storage::delete('public/' . $berita->gambar);
            }
        });
    }

    /***************************************************************
     *  ACCESSORS (🔥 dari Blade)
     ***************************************************************/

    // ✅ Penulis fallback
    public function getPenulisLabelAttribute()
    {
        return $this->penulis ?: 'Admin JDIH';
    }

    // ✅ Tanggal formatted (Indonesia)
    public function getTanggalFormattedAttribute()
    {
        return $this->created_at
            ? $this->created_at->locale('id')->translatedFormat('d F Y')
            : '-';
    }

    // ✅ Tanggal short (sidebar)
    public function getTanggalShortAttribute()
    {
        return $this->created_at
            ? $this->created_at->translatedFormat('d M Y')
            : '-';
    }

    // ✅ Image URL
    public function getImageUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : asset('img/noimage.png');
    }

    // ✅ View count formatted
    public function getViewCountFormattedAttribute()
    {
        return number_format($this->view_count) . ' Dilihat';
    }

    // ✅ Judul pendek (sidebar)
    public function getJudulShortAttribute()
    {
        return Str::limit($this->judul, 80);
    }

    // ✅ Konten sanitized
    public function getKontenSanitizedAttribute()
    {
        return str($this->konten)->sanitizeHtml();
    }

    /***************************************************************
     *  QUERY HELPER
     ***************************************************************/
    public static function getBeritaActive()
    {
        return self::where('status', 1)
            ->latest()
            ->get();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $judul = $this->judul ?? '-';
                return match ($eventName) {
                    'created' => "Data Berita \"{$judul}\" berhasil ditambahkan",
                    'updated' => "Data Berita \"{$judul}\" berhasil diperbarui",
                    'deleted' => "Data Berita \"{$judul}\" berhasil dihapus",
                    default   => "Data Berita \"{$judul}\" telah di-{$eventName}",
                };
            });
    }
}

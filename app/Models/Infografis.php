<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Observers\InfografisObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


#[ObservedBy([InfografisObserver::class])]
class Infografis extends Model
{
    use HasFactory, Notifiable, LogsActivity;

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

    /***************************************************************
     *  BOOT
     ***************************************************************/
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($infografis) {
            if (empty($infografis->slug)) {
                $infografis->slug = Str::slug($infografis->judul);
            }
        });

        static::deleted(function ($infografis) {
            // ✅ FIX: gunakan field gambar (bukan lampiran)
            if ($infografis->gambar && Storage::disk('public')->exists($infografis->gambar)) {
                Storage::disk('public')->delete($infografis->gambar);
            }
        });
    }

    /***************************************************************
     *  ACCESSORS (🔥 pindahan dari Blade)
     ***************************************************************/

    // ✅ Judul fallback
    public function getJudulLabelAttribute()
    {
        return $this->judul ?: 'Detail Infografis';
    }

    // ✅ Tanggal format Indonesia
    public function getTanggalFormattedAttribute()
    {
        return $this->created_at
            ? $this->created_at->locale('id')->translatedFormat('d F Y')
            : '-';
    }

    // ✅ View count format
    public function getViewCountFormattedAttribute()
    {
        return number_format($this->view_count) . ' Dilihat';
    }

    // ✅ Image URL + validasi file
    public function getImageUrlAttribute()
    {
        if ($this->gambar && Storage::disk('public')->exists($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }

        return null;
    }

    // ✅ Alt image
    public function getImageAltAttribute()
    {
        return $this->judul ?: 'Gambar Infografis';
    }

    /***************************************************************
     *  QUERY HELPER
     ***************************************************************/
    public static function getInfografisActive()
    {
        return self::where('status', 1)
            ->latest()
            ->take(6)
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
                    'created' => "Data Infografis \"{$judul}\" berhasil ditambahkan",
                    'updated' => "Data Infografis \"{$judul}\" berhasil diperbarui",
                    'deleted' => "Data Infografis \"{$judul}\" berhasil dihapus",
                    default   => "Data Infografis \"{$judul}\" telah di-{$eventName}",
                };
            });
    }
}

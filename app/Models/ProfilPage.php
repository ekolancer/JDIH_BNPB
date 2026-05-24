<?php

namespace App\Models;

use App\Observers\ProfilPageObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


#[ObservedBy([ProfilPageObserver::class])]
class ProfilPage extends Model
{
    use Notifiable, LogsActivity;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'has_image',
        'image',
    ];

    protected $casts = [
        'has_image' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $title = $this->title ?? '-';
                return match ($eventName) {
                    'created' => "Data Halaman Profil \"{$title}\" berhasil ditambahkan",
                    'updated' => "Data Halaman Profil \"{$title}\" berhasil diperbarui",
                    'deleted' => "Data Halaman Profil \"{$title}\" berhasil dihapus",
                    default   => "Data Halaman Profil \"{$title}\" telah di-{$eventName}",
                };
            });
    }
}

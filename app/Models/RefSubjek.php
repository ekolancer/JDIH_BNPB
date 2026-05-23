<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RefSubjek extends Model
{
    use LogsActivity;

    protected $table = 'ref_subjek';

    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
        'is_active',
    ];

    public function dokumenHukum()
    {
        return $this->belongsToMany(DokumenHukum::class, 'dokumen_subjek', 'subjek_id', 'dokumen_hukum_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $nama = $this->nama ?? '-';
                return match ($eventName) {
                    'created' => "Data Subjek \"{$nama}\" berhasil ditambahkan",
                    'updated' => "Data Subjek \"{$nama}\" berhasil diperbarui",
                    'deleted' => "Data Subjek \"{$nama}\" berhasil dihapus",
                    default   => "Data Subjek \"{$nama}\" telah di-{$eventName}",
                };
            });
    }
}

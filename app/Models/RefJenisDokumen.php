<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RefJenisDokumen extends Model
{
    use LogsActivity;

    protected $table = 'ref_jenis_dokumen';

    protected $fillable = [
        'nama',
        'singkatan',
        'deskripsi',
        'is_active',
    ];

    public function dokumenHukum()
    {
        return $this->hasMany(DokumenHukum::class, 'ref_jenis_dokumen_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class RefBidangHukum extends Model
{
    use LogsActivity;

    protected $table = 'ref_bidang_hukum';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    public function dokumenHukum()
    {
        return $this->hasMany(DokumenHukum::class, 'ref_bidang_hukum_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

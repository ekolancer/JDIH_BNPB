<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RefJenisDokumen extends Model
{
    use LogsActivity;

    protected $table = 'ref_jenis_dokumen';

    protected $fillable = [
        'tipe_dokumen_id',
        'nama',
        'singkatan',
        'deskripsi',
        'is_active',
    ];

    /**
     * Tipe dokumen induk.
     */
    public function tipeDokumen(): BelongsTo
    {
        return $this->belongsTo(RefTipeDokumen::class, 'tipe_dokumen_id');
    }

    public function dokumenHukum(): HasMany
    {
        return $this->hasMany(DokumenHukum::class, 'ref_jenis_dokumen_id');
    }

    public function scopeByTipe($query, $tipeId)
    {
        return $query->where('tipe_dokumen_id', $tipeId);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

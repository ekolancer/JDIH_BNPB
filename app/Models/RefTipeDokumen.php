<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RefTipeDokumen extends Model
{
    use LogsActivity;

    protected $table = 'ref_tipe_dokumen';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Jenis dokumen yang termasuk dalam tipe ini.
     */
    public function jenisDokumen(): HasMany
    {
        return $this->hasMany(RefJenisDokumen::class, 'tipe_dokumen_id');
    }

    /**
     * Dokumen hukum bertipe ini.
     */
    public function dokumenHukum(): HasMany
    {
        return $this->hasMany(DokumenHukum::class, 'tipe_dokumen_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class DokumenRelasi extends Model
{
    use LogsActivity;

    protected $table = 'dokumen_relasi';

    protected $fillable = [
        'dokumen_id',
        'related_dokumen_id',
        'jenis_relasi',
        'catatan',
    ];

    /***************************************************************
     *  RELATIONS
     ***************************************************************/

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenHukum::class, 'dokumen_id');
    }

    public function relatedDokumen(): BelongsTo
    {
        return $this->belongsTo(DokumenHukum::class, 'related_dokumen_id');
    }

    /***************************************************************
     *  STATIC HELPERS
     ***************************************************************/

    /**
     * Mapping pasangan inverse relasi.
     * Saat admin input "A mencabut B", otomatis buat "B dicabut_oleh A".
     */
    public static function getInverseRelasi(string $jenis): string
    {
        $map = [
            'mencabut'          => 'dicabut_oleh',
            'dicabut_oleh'      => 'mencabut',
            'mengubah'          => 'diubah_oleh',
            'diubah_oleh'       => 'mengubah',
            'melaksanakan'      => 'dilaksanakan_oleh',
            'dilaksanakan_oleh' => 'melaksanakan',
            'melengkapi'        => 'dilengkapi_oleh',
            'dilengkapi_oleh'   => 'melengkapi',
            'dasar_hukum'       => 'menjadi_dasar',
            'menjadi_dasar'     => 'dasar_hukum',
            'terkait'           => 'terkait',
        ];

        return $map[$jenis] ?? $jenis;
    }

    /**
     * Opsi relasi yang ditampilkan di form admin.
     * Hanya relasi "aktif" (bukan inverse otomatis).
     */
    public static function getRelasiOptions(): array
    {
        return [
            'mencabut'      => 'Mencabut',
            'mengubah'      => 'Mengubah',
            'melaksanakan'  => 'Melaksanakan',
            'melengkapi'    => 'Melengkapi',
            'dasar_hukum'   => 'Dasar Hukum',
            'terkait'       => 'Terkait',
        ];
    }

    /**
     * Semua label relasi (termasuk inverse) untuk tampilan.
     */
    public static function getAllRelasiLabels(): array
    {
        return [
            'mencabut'          => 'Mencabut',
            'dicabut_oleh'      => 'Dicabut oleh',
            'mengubah'          => 'Mengubah',
            'diubah_oleh'       => 'Diubah oleh',
            'melaksanakan'      => 'Melaksanakan',
            'dilaksanakan_oleh' => 'Dilaksanakan oleh',
            'melengkapi'        => 'Melengkapi',
            'dilengkapi_oleh'   => 'Dilengkapi oleh',
            'dasar_hukum'       => 'Dasar Hukum',
            'menjadi_dasar'     => 'Menjadi Dasar',
            'terkait'           => 'Terkait',
        ];
    }

    /**
     * Label human-readable untuk jenis relasi.
     */
    public function getLabelAttribute(): string
    {
        return self::getAllRelasiLabels()[$this->jenis_relasi] ?? $this->jenis_relasi;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefSubjek extends Model
{
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
}

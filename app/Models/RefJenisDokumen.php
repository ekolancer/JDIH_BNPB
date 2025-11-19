<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefJenisDokumen extends Model
{
    protected $table = 'ref_jenis_dokumen';

    protected $fillable = [
        'nama',
        'singkatan',
        'deskripsi',
        'is_active',
    ];

    public function dokumens()
    {
        return $this->hasMany(DokumenHukum::class, 'ref_jenis_dokumen_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefBidangHukum extends Model
{
    protected $table = 'ref_bidang_hukum';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    public function dokumen()
    {
        return $this->hasMany(DokumenHukum::class, 'ref_bidang_hukum_id');
    }
}

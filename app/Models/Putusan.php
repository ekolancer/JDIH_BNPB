<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Putusan extends Model
{
    use HasFactory;
    
    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'judul',
        'teuBadan',
        'nomor',
        'tahunTerbit',
        'singkatan',
        'tempatPenetapan',
        'penerbit',
        'tglPenetapan',
        'tglPengundangan',
        'subjek',
        'sumber',
        'status',
        'bahasa',
        'lokasi',
        'bidangHukum',
        'lampiran',
        'penandatangan',
        'dokumenTerkait',
    ];
}

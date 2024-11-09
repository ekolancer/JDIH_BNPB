<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;

    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'singkatan',
        'judul',
        'teuBadan',
        'nomor',
        'tahun',
        'tempatPenetapan',
        'tglPenetapan',
        'tglPengundangan',
        'sumber',
        'subjek',
        'bidangHukum',
        'bahasa',
        'status',
        'lokasi',
        'lampiran',
        'urusanPemerintahan',
        'pemrakarsa',
        'penandatangan',
        'peraturanTerkait',
    ];
}

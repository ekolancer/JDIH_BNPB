<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monograf extends Model
{
    use HasFactory;

    protected $fillable =   [
        'tipeDok',
        'jenisDok',
        'judul',
        'teuBadan',
        'tahunTerbit',
        'tempatPenetapan',
        'penerbit',
        'tglPenetapan',
        'tglPengundangan',
        'noPanggil',
        'cetakanEdisi',
        'deskripsiFisik',
        'subjek',
        'isbn',
        'bahasa',
        'lokasi',
        'bidangHukum',
        'lampiran',
        'nomorIndukBuku',
    ];
}

<?php

namespace App\Http\Controllers;

use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($tipeDok, $slug)
    {
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];

        if (!in_array($tipeDok, $validTypes)) {
            abort(404);
        }

        // Ambil data tanpa memicu observer
        $dokumen = DokumenHukum::where('tipeDok', $tipeDok)
            ->where('slug', $slug)
            ->firstOrFail();

        // Update view_count tanpa memicu event observer
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('view_count');

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $dokumen->lampiran);

        // Cek apakah file ada, kemudian hitung ukuran file
        $fileSize = file_exists($fileLampiranPath) ? filesize($fileLampiranPath) : 0;

        // Mengonversi ukuran file menjadi KB atau MB
        $fileSizeInKb = round($fileSize / 1024, 2); // Ukuran dalam KB
        $fileSizeInMb = round($fileSizeInKb / 1024, 2); // Ukuran dalam MB

        // Pemetaan metadata per tipe dokumen
        $metadata = [
            'Peraturan' => [
                'Tipe Dokumen' => $dokumen->tipeDok,
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'T.E.U Badan' => $dokumen->teuBadan,
                'Nomor' => $dokumen->nomor,
                'Tahun Terbit' => $dokumen->tahun,
                'Singkatan' => $dokumen->singkatan,
                'Tempat Terbit' => $dokumen->tempat,
                'Tanggal Penetapan' => \Carbon\Carbon::parse($dokumen->tglPenetapan)->translatedFormat('d F Y'),
                'Tanggal Pengundangan' => \Carbon\Carbon::parse($dokumen->tglPengundangan)->translatedFormat('d F Y'),
                'Subjek' => $dokumen->subjek,
                'Sumber' => $dokumen->sumber,
                'Bahasa' => $dokumen->bahasa,
                'Lokasi' => $dokumen->lokasi,
                'Bidang Hukum' => $dokumen->bidangHukum,
            ],
            'Putusan' => [
                'Tipe Dokumen' => $dokumen->tipeDok,
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'T.E.U Badan' => $dokumen->teuBadan,
                'Nomor Putusan' => $dokumen->nomor,
                'Tahun Terbit' => $dokumen->tahun,
                'Singkatan' => $dokumen->singkatan,
                'Tempat Peradilan' => $dokumen->tempat,
                'Penerbit' => $dokumen->penerbit,
                'Tanggal DiBacakan' => \Carbon\Carbon::parse($dokumen->tglDiBacakan)->translatedFormat('d F Y'),
                'Subjek' => $dokumen->subjek,
                'Sumber' => $dokumen->sumber,
                'Bahasa' => $dokumen->bahasa,
                'Lokasi' => $dokumen->lokasi,
                'Bidang Hukum' => $dokumen->bidangHukum,
                'Penandatangan' => $dokumen->penandatangan,
            ],
            'Monografi' => [
                'Tipe Dokumen' => $dokumen->tipeDok,
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'T.E.U Badan' => $dokumen->teuBadan,
                'Tahun Terbit' => $dokumen->tahun,
                'Tempat Terbit' => $dokumen->tempat,
                'Penerbit' => $dokumen->penerbit,
                'Nomor Panggil' => $dokumen->nomorPanggil,
                'Cetakan / Edisi' => $dokumen->cetakanEdisi,
                'Deskripsi Fisik' => $dokumen->deskripsiFisik,
                'Subjek' => $dokumen->subjek,
                'ISBN' => $dokumen->isbn,
                'Bahasa' => $dokumen->bahasa,
                'Lokasi' => $dokumen->lokasi,
                'Bidang Hukum' => $dokumen->bidangHukum,
                'Nomor Induk Buku' => $dokumen->nomorIndukBuku,
            ],
            'Artikel' => [
                'Tipe Dokumen' => $dokumen->tipeDok,
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'T.E.U Badan' => $dokumen->teuBadan,
                'Tahun Terbit' => $dokumen->tahun,
                'Tempat Terbit' => $dokumen->tempat,
                'Penerbit' => $dokumen->penerbit,
                'Subjek' => $dokumen->subjek,
                'Sumber' => $dokumen->sumber,
                'Status' => $dokumen->status,
                'Bahasa' => $dokumen->bahasa,
                'Lokasi' => $dokumen->lokasi,
                'Bidang Hukum' => $dokumen->bidangHukum,
            ],
        ];

        return view('dokumen.detail', compact('dokumen', 'fileSizeInKb', 'fileSizeInMb', 'metadata'));
    }

    public function filterByType($tipeDok)
    {
        // Validasi tipeDok agar hanya menerima yang diizinkan
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];

        if (!in_array(ucfirst($tipeDok), $validTypes)) {
            abort(404); // Jika tidak valid, lemparkan error 404
        }

        // Ambil dokumen berdasarkan tipe
        $results = DokumenHukum::where('tipeDok', ucfirst($tipeDok))->paginate(10);

        $dokumenAttributes = DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']);

        return view('dokumen.list-doc', compact('results', 'tipeDok', 'dokumenAttributes'));
    }
}

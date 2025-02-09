<?php

namespace App\Http\Controllers;

use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data DokumenHukum dan urutkan berdasarkan created_at
        $results = DokumenHukum::orderBy('created_at', 'desc')->get()->paginate(10);

        // Kembalikan ke view dengan data dokumen
        return view('dokumen.index', compact('results'));
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

        // Menentukan breadcrumbs berdasarkan tipe dokumen
        $breadcrumbs = [
            ['title' => 'Home', 'url' => route('dashboard.index')],
            ['title' => 'Produk Hukum', 'url' => route('dokumen.index')], // Link ke indeks produk hukum
            ['title' => ucfirst($tipeDok)], // Tidak ada URL untuk breadcrumb terakhir
            ['title' => $dokumen->judul] // Tidak ada URL untuk breadcrumb terakhir
        ];

        // Update view_count tanpa memicu event observer
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('view_count');

        // Tentukan path file lampiran
        $fullTextPath = storage_path('app/public/' . $dokumen->lampiran);
        $abstrakPath = storage_path('app/public/' . $dokumen->abstrak);

        // Cek apakah file ada, kemudian hitung ukuran file
        $lampiranSize = file_exists($fullTextPath) ? filesize($fullTextPath) : 0;
        $abstrakSize = file_exists($abstrakPath) ? filesize($abstrakPath) : 0;

        // Mengonversi ukuran file menjadi KB atau MB
        $lampiranSizeInKb = round($lampiranSize / 1024, 2); // Ukuran lampiran dalam KB
        $abstrakSizeInKb = round($abstrakSize / 1024, 2); // Ukuran lampiran dalam KB
        $lampiranSizeInMb = round($lampiranSizeInKb / 1024, 2); // Ukuran abstrak dalam MB
        $abstrakSizeInMb = round($abstrakSizeInKb / 1024, 2); // Ukuran abstrak dalam MB

        // Tentukan teks tombol berdasarkan tipe dokumen
        $buttonText = $dokumen->tipeDok == 'Monografi' ? 'Cover' : 'Baca';

        // Pemetaan metadata per tipe dokumen
        $metadata = [
            'Peraturan' => [
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'Tajuk Entri Utama' => $dokumen->teuBadan,
                'Nomor' => $dokumen->nomor,
                'Tahun Terbit' => $dokumen->tahun,
                'Tempat Terbit' => $dokumen->tempat,
                'Tanggal Penetapan' => \Carbon\Carbon::parse($dokumen->tglPenetapan)->translatedFormat('d F Y'),
                'Tanggal Pengundangan' => \Carbon\Carbon::parse($dokumen->tglPengundangan)->translatedFormat('d F Y'),
                'Subjek' => $dokumen->subjek,
                'Sumber' => $dokumen->sumber,
                'Bahasa' => $dokumen->bahasa,
                'Lokasi' => $dokumen->lokasi,
                'Bidang Hukum' => $dokumen->bidangHukum,
                'Penandatangan' => $dokumen->penandatangan,
            ],
            'Putusan' => [
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'Tajuk Entri Utama' => $dokumen->teuBadan,
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
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'Tajuk Entri Utama' => $dokumen->teuBadan,
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
                'Jenis Dokumen' => $dokumen->jenisDok,
                'Judul' => $dokumen->judul,
                'Tajuk Entri Utama' => $dokumen->teuBadan,
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

        return view('dokumen.detail', compact('dokumen', 'breadcrumbs', 'lampiranSizeInKb', 'lampiranSizeInMb', 'abstrakSizeInKb', 'abstrakSizeInMb', 'metadata', 'buttonText'));
    }

    public function filterByType($tipeDok)
    {
        // Validasi tipeDok agar hanya menerima yang diizinkan
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];

        if (!in_array(ucfirst($tipeDok), $validTypes)) {
            abort(404); // Jika tidak valid, lemparkan error 404
        }

        // Menentukan breadcrumbs berdasarkan tipe dokumen
        $breadcrumbs = [
            ['title' => 'Home', 'url' => route('dashboard.index')],
            ['title' => 'Produk Hukum', 'url' => route('dokumen.index')], // Link ke indeks produk hukum
            ['title' => ucfirst($tipeDok)] // Tidak ada URL untuk breadcrumb terakhir
        ];

        // Ambil dokumen berdasarkan tipe
        $results = DokumenHukum::where('tipeDok', ucfirst($tipeDok))->paginate(10);

        $dokumenAttributes = DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']);

        return view('dokumen.list-doc', compact('results', 'tipeDok', 'dokumenAttributes', 'breadcrumbs'));
    }

    /**
     * Unduh file lampiran
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function unduhLampiran($dokumenId)
    {
        // Cari dokumen berdasarkan ID
        $dokumen = DokumenHukum::findOrFail($dokumenId);

        // Mendapatkan path file
        $filePath = public_path('storage/' . $dokumen->lampiran);

        // Mengecek apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment download_count di database tanpa memperhatikan cache
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count');

        // Mengembalikan file untuk diunduh
        return response()->download($filePath, null, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Unduh file abstrak
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function unduhAbstrak($dokumenId)
    {
        // Cari dokumen berdasarkan ID
        $dokumen = DokumenHukum::findOrFail($dokumenId);

        // Mendapatkan path file
        $filePath = public_path('storage/' . $dokumen->abstrak);

        // Mengecek apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment download_count di database tanpa memperhatikan cache
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count');

        // Mengembalikan file untuk diunduh
        return response()->download($filePath, null, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}

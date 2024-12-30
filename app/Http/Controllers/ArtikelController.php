<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $judul = $request->input('judul');
        $tipeDok = $request->input('tipeDok');
        $nomorPanggil = $request->input('nomorPanggil');
        $tahunTerbit = $request->input('tahunTerbit');
        $penerbit = $request->input('penerbit');

        // Query builder untuk pencarian
        $query = Artikel::query();

        // Filter berdasarkan input
        if ($judul) {
            $query->where('judul', 'LIKE', '%' . $judul . '%');
        }

        if ($tipeDok) {
            $query->where('tipeDok', $tipeDok);
        }

        if ($nomorPanggil) {
            $query->where('nomorPanggil', 'LIKE', '%' . $nomorPanggil . '%');
        }

        if ($tahunTerbit) {
            $query->whereYear('tahunTerbit', $tahunTerbit);
        }

        if ($penerbit) {
            $query->where('penerbit', 'LIKE', '%' . $penerbit . '%');
        }

        // Ambil data hasil pencarian atau seluruh data jika filter kosong
        $dokumen = $query->paginate(10);

        // Ambil daftar tipeDok unik dari tabel peraturan
        $tipeDokOptions = Artikel::select('tipeDok')->distinct()->pluck('tipeDok');

        // Kirim data ke view
        return view('artikel.list', compact('dokumen', 'tipeDokOptions'));
    }

    public function show($slug)
    {
        // Mengambil data peraturan berdasarkan judul
        $artikel = Artikel::where('slug', $slug)->firstOrFail();

        // Tingkatkan view count
        $artikel->increment('view_count');

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $artikel->lampiran);

        // Cek apakah file ada, kemudian hitung ukuran file
        $fileSize = file_exists($fileLampiranPath) ? filesize($fileLampiranPath) : 0;

        // Mengkonversi ukuran file menjadi KB atau MB untuk tampilan
        $fileSizeInKb = $fileSize / 1024; // Ukuran dalam KB
        $fileSizeInMb = $fileSizeInKb / 1024; // Ukuran dalam MB


        return view('artikel.detail', compact('artikel', 'fileSizeInKb', 'fileSizeInMb'));
    }
}

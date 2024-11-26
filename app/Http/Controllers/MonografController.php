<?php

namespace App\Http\Controllers;

use App\Models\Monograf;
use Illuminate\Http\Request;

class MonografController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $judul = $request->input('judul');
        $tipeDok = $request->input('tipeDok');
        $isbn = $request->input('isbn');
        $tahunTerbit = $request->input('tahunTerbit');
        $penerbit = $request->input('penerbit');

        // Query builder untuk pencarian
        $query = Monograf::query();

        // Filter berdasarkan input
        if ($judul) {
            $query->where('judul', 'LIKE', '%' . $judul . '%');
        }

        if ($tipeDok) {
            $query->where('tipeDok', $tipeDok);
        }

        if ($isbn) {
            $query->where('isbn', 'LIKE', '%' . $isbn . '%');
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
        $tipeDokOptions = Monograf::select('tipeDok')->distinct()->pluck('tipeDok');

        // Kirim data ke view
        return view('monograf.list', compact('dokumen', 'tipeDokOptions'));
    }

    public function show($judul)
    {
        // Mengambil data peraturan berdasarkan judul
        $monograf = Monograf::where('judul', $judul)->firstOrFail();

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $monograf->lampiran);

        // Cek apakah file ada, kemudian hitung ukuran file
        $fileSize = file_exists($fileLampiranPath) ? filesize($fileLampiranPath) : 0;

        // Mengkonversi ukuran file menjadi KB atau MB untuk tampilan
        $fileSizeInKb = $fileSize / 1024; // Ukuran dalam KB
        $fileSizeInMb = $fileSizeInKb / 1024; // Ukuran dalam MB


        return view('monograf.detail', compact('monograf', 'fileSizeInKb', 'fileSizeInMb'));
    }
}

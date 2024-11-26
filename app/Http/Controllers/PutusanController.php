<?php

namespace App\Http\Controllers;

use App\Models\Putusan;
use Illuminate\Http\Request;

class PutusanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $judul = $request->input('judul');
        $tipeDok = $request->input('tipeDok');
        $nomor = $request->input('nomor');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        // Query builder untuk pencarian
        $query = Putusan::query();

        // Filter berdasarkan input
        if ($judul) {
            $query->where('judul', 'LIKE', '%' . $judul . '%');
        }

        if ($tipeDok) {
            $query->where('tipeDok', $tipeDok);
        }

        if ($nomor) {
            $query->where('nomor', 'LIKE', '%' . $nomor . '%');
        }

        if ($tahun) {
            $query->whereYear('tahun', $tahun);
        }

        if ($status) {
            $query->where('status', strtoupper($status));
        }

        // Ambil data hasil pencarian atau seluruh data jika filter kosong
        $dokumen = $query->paginate(10);

        // Ambil daftar tipeDok unik dari tabel putusan
        $tipeDokOptions = Putusan::select('tipeDok')->distinct()->pluck('tipeDok');

        // Kirim data ke view
        return view('putusan.list', compact('dokumen', 'tipeDokOptions'));
    }

    public function show($judul)
    {
        // Mengambil data putusan berdasarkan judul
        $putusan = Putusan::where('judul', $judul)->firstOrFail();

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $putusan->lampiran);

        // Cek apakah file ada, kemudian hitung ukuran file
        $fileSize = file_exists($fileLampiranPath) ? filesize($fileLampiranPath) : 0;

        // Mengkonversi ukuran file menjadi KB atau MB untuk tampilan
        $fileSizeInKb = $fileSize / 1024; // Ukuran dalam KB
        $fileSizeInMb = $fileSizeInKb / 1024; // Ukuran dalam MB


        return view('putusan.detail', compact('putusan', 'fileSizeInKb', 'fileSizeInMb'));
    }
}

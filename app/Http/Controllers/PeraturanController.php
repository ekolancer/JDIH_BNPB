<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeraturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil input pencarian dari form
        $judul = $request->input('judul');
        $tipeDok = $request->input('tipeDok');
        $nomor = $request->input('nomor');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        // Query builder untuk pencarian
        $query = Peraturan::query();

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

        // Ambil daftar tipeDok unik dari tabel peraturan
        $tipeDokOptions = Peraturan::select('tipeDok')->distinct()->pluck('tipeDok');

        // Kirim data ke view
        return view('peraturan.list', compact('dokumen', 'tipeDokOptions'));
    }

    public function show($slug)
    {
        // Mengambil data peraturan berdasarkan judul
        $peraturan = Peraturan::where('slug', $slug)->firstOrFail();

        // Tingkatkan view count
        $peraturan->increment('view_count');

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $peraturan->lampiran);

        // Cek apakah file ada, kemudian hitung ukuran file
        $fileSize = file_exists($fileLampiranPath) ? filesize($fileLampiranPath) : 0;

        // Mengkonversi ukuran file menjadi KB atau MB untuk tampilan
        $fileSizeInKb = $fileSize / 1024; // Ukuran dalam KB
        $fileSizeInMb = $fileSizeInKb / 1024; // Ukuran dalam MB

        return view('peraturan.detail', compact('peraturan', 'fileSizeInKb', 'fileSizeInMb'));
    }

    public function download($lampiran)
    {
        // Lokasi file di folder public storage
        $filePath = 'lampiran/peraturan/' . $lampiran;
        

        // Cek apakah file ada di storage
        if (Storage::exists('public/' . $filePath)) {
            // Tingkatkan download count
            $peraturan = Peraturan::where('lampiran', $lampiran)->firstOrFail();
            $peraturan->increment('download_count');

            // Download file dari storage
            return response()->download(storage_path('app/public/' . $filePath));
        }

        // Jika file tidak ditemukan
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}

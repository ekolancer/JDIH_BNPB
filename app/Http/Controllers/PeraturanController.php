<?php

namespace App\Http\Controllers;

use App\Models\Peraturan;
use Illuminate\Http\Request;

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
            $query->whereYear('created_at', $tahun);
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Survey;
use App\Models\Artikel;
use App\Models\Putusan;
use App\Models\Visitor;
use App\Models\Monograf;
use App\Models\Peraturan;
use App\Models\Infografis;
use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            // Menghitung jumlah data berdasarkan tipeDok
            'jumlahDokumen' => DokumenHukum::countByType(),
            // Mengambil data visitorStats dari model Visitor
            'visitorStats' => Visitor::getVisitorStats(),
            // Mengambil peraturan terbaru dari model DokumenHukum
            'peraturanTerbaru' => DokumenHukum::getLatest(3),
            // Mengambil peraturan terpopuler dari model DokumenHukum
            'peraturanPopuler' => DokumenHukum::getMostViewed(6),
            // Mengambil nilai unik untuk tipeDok dan jenisDok
            'dokumenAttributes' => DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']),
            // Mengambil data berita aktif dari model Berita
            'berita' => Berita::getBeritaActive(),
            // Mengambil data Infografis aktif dari model Infografis
            'infografis' => Infografis::getInfografisActive(),
        ];

        // Debugging sementara
        // dd($data);

        // Mengirimkan data ke view
        return view('index', compact('data'));
    }

    // Search dengan Validasi dan Pagination
    public function search(Request $request)
    {
        // Mengambil nilai unik untuk tipeDok dan jenisDok
        $dokumenAttributes = DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']);

        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'tipe_dokumen' => 'nullable|string|max:100',
            'bentuk_dokumen' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer',
        ]);

        $query = isset($validated['q']) ? $validated['q'] : null;
        $tipeDokumen = isset($validated['tipe_dokumen']) ? $validated['tipe_dokumen'] : null;
        $bentukDokumen = isset($validated['bentuk_dokumen']) ? $validated['bentuk_dokumen'] : null;
        $tahun = isset($validated['tahun']) ? $validated['tahun'] : null;

        $data = DokumenHukum::query()->groupByColumns(); // Ambil hasil groupBy dari model Dokumen

        $filterNames = [
            'tipeDok' => 'Tipe Dokumen',
            'jenisDok' => 'Jenis Dokumen',
            'singkatan' => 'Singkatan',
            'tempat' => 'Tempat Penetapan',
            'penerbit' => 'Penerbit',
            'nomorPanggil' => 'Nomor Panggil',
            'subjek' => 'Subjek',
            'sumber' => 'Sumber',
            'isbn' => 'ISBN',
            'status' => 'Status',
            'bahasa' => 'Bahasa',
            'bidangHukum' => 'Bidang Hukum',
            'nomorIndukBuku' => 'Nomor Induk Buku',
        ];

        // Query hanya menggunakan tabel DokumenHukum
        $results = DokumenHukum::query()
            ->when($query, function($q) use ($query) {
                return $q->where('judul', 'LIKE', "%{$query}%");
            })
            ->when($tipeDokumen, function($q) use ($tipeDokumen) {
                return $q->where('tipeDok', $tipeDokumen);
            })
            ->when($bentukDokumen, function($q) use ($bentukDokumen) {
                return $q->where('jenisDok', $bentukDokumen);
            })
            ->when($tahun, function($q) use ($tahun) {
                return $q->where('tahun', $tahun);
            })
            ->paginate(10); // Pagination bawaan Laravel

        return view('list-doc', compact('data', 'filterNames'), [
            'dokumenAttributes' => $dokumenAttributes,
            'results' => $results, // Jika pakai pagination bawaan
        ]);
    }

    // Function Webview Perihal
    public function about()
    {
        return view('overview.about');
    }

    public function visiMisi()
    {
        return view('overview.visiMisi');
    }

    public function struktur()
    {
        return view('overview.struktur');
    }
}

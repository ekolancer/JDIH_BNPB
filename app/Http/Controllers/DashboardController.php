<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Artikel;
use App\Models\Putusan;
use App\Models\Visitor;
use App\Models\Monograf;
use App\Models\Peraturan;
use App\Models\Infografis;
use Illuminate\Http\Request;
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
        // Menghitung jumlah data dari masing-masing tabel
        $data = [
            'peraturan' => Peraturan::count(),
            'monografi' => Monograf::count(),
            'artikel' => Artikel::count(),
            'putusan' => Putusan::count(),
        ];

        // Mengambil data terbaru dari tabel peraturan
        $peraturanTerbaru = Peraturan::orderBy('created_at', 'desc')->take(4)->get();
        $peraturanPopuler = Peraturan::orderBy('view_count', 'desc')->take(8)->get();
        $berita = Berita::where('status', 1)->orderBy('created_at', 'desc')->get();
        $infografis = Infografis::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(6) // Ambil 6 data untuk ditampilkan
            ->get();


        // Ambil nilai unik tipeDok dan jenisDok dari keempat tabel
        $tipeDokumen = collect()
            ->merge(Peraturan::select('tipeDok')->distinct()->pluck('tipeDok'))
            ->merge(Monograf::select('tipeDok')->distinct()->pluck('tipeDok'))
            ->merge(Artikel::select('tipeDok')->distinct()->pluck('tipeDok'))
            ->merge(Putusan::select('tipeDok')->distinct()->pluck('tipeDok'))
            ->unique()
            ->values();

        $bentukDokumen = collect()
            ->merge(Peraturan::select('jenisDok')->distinct()->pluck('jenisDok'))
            ->merge(Monograf::select('jenisDok')->distinct()->pluck('jenisDok'))
            ->merge(Artikel::select('jenisDok')->distinct()->pluck('jenisDok'))
            ->merge(Putusan::select('jenisDok')->distinct()->pluck('jenisDok'))
            ->unique()
            ->values();

        // Hitung jumlah pengunjung hari ini
        $todayVisitors = Visitor::whereDate('visited_at', now()->toDateString())->count();

        // Hitung jumlah pengunjung kemarin
        $yesterdayVisitors = Visitor::whereDate('visited_at', now()->subDay()->toDateString())->count();

        // Hitung jumlah pengunjung bulan ini
        $thisMonthVisitors = Visitor::whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->count();

        // Hitung jumlah pengunjung tahun ini
        $thisYearVisitors = Visitor::whereYear('visited_at', now()->year)->count();


        // Mengirimkan data ke view
        return view('index', compact(
            'data',
            'peraturanTerbaru', 
            'peraturanPopuler', 
            'berita',
            'infografis',
            'tipeDokumen',
            'bentukDokumen',
            'todayVisitors',
            'yesterdayVisitors',
            'thisMonthVisitors',
            'thisYearVisitors'
        ));
    }

    public function download($lampiran)
    {
        // Cari di tabel `peraturan`
        $data = Peraturan::where('lampiran', $lampiran)->first();

        if ($data) {
            // Log query SQL sebelum increment
            logger('Increment before: ' . $data->download_count);
            $data->increment('download_count');
            logger('Increment after: ' . $data->download_count);
        } else {
            // Cari di tabel `putusan`
            $data = Putusan::where('lampiran', $lampiran)->first();

            if ($data) {
                // Log query SQL sebelum increment
                logger('Increment before: ' . $data->download_count);
                $data->increment('download_count');
                logger('Increment after: ' . $data->download_count);
            } else {
                // Data tidak ditemukan
                abort(404, 'File lampiran tidak ditemukan.');
            }
        }

        // Periksa keberadaan file di storage
        if (!Storage::exists($lampiran)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        // Unduh file
        return Storage::download($lampiran, basename($lampiran));
    }

    // Search dengan Validasi dan Pagination
    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'tipe_dokumen' => 'nullable|string|max:255',
            'bentuk_dokumen' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
        ]);

        $query = $validated['q'] ?? null;
        $tipeDokumen = $validated['tipe_dokumen'] ?? null;
        $bentukDokumen = $validated['bentuk_dokumen'] ?? null;
        $tahun = $validated['tahun'] ?? null;

        // Gabungkan hasil pencarian dari keempat tabel
        $results = collect();

        // Peraturan
        $results = $results->merge(
            Peraturan::query()
                ->when($query, fn($q) => $q->where('judul', 'LIKE', "%{$query}%"))
                ->when($tipeDokumen, fn($q) => $q->where('tipeDok', $tipeDokumen))
                ->when($bentukDokumen, fn($q) => $q->where('jenisDok', $bentukDokumen))
                ->when($tahun, fn($q) => $q->where('tahun', $tahun))
                ->get()
        );

        // Monograf
        $results = $results->merge(
            Monograf::query()
                ->when($query, fn($q) => $q->where('judul', 'LIKE', "%{$query}%"))
                ->when($tipeDokumen, fn($q) => $q->where('tipeDok', $tipeDokumen))
                ->when($bentukDokumen, fn($q) => $q->where('jenisDok', $bentukDokumen))
                ->when($tahun, fn($q) => $q->where('tahun', $tahun))
                ->get()
        );

        // Artikel
        $results = $results->merge(
            Artikel::query()
                ->when($query, fn($q) => $q->where('judul', 'LIKE', "%{$query}%"))
                ->when($tipeDokumen, fn($q) => $q->where('tipeDok', $tipeDokumen))
                ->when($bentukDokumen, fn($q) => $q->where('jenisDok', $bentukDokumen))
                ->when($tahun, fn($q) => $q->where('tahun', $tahun))
                ->get()
        );

        // Putusan
        $results = $results->merge(
            Putusan::query()
                ->when($query, fn($q) => $q->where('judul', 'LIKE', "%{$query}%"))
                ->when($tipeDokumen, fn($q) => $q->where('tipeDok', $tipeDokumen))
                ->when($bentukDokumen, fn($q) => $q->where('jenisDok', $bentukDokumen))
                ->when($tahun, fn($q) => $q->where('tahun', $tahun))
                ->get()
        );

        // Pagination manual
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $results->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedResults = new LengthAwarePaginator(
            $currentItems,
            $results->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('list-doc', compact('paginatedResults'));
    }

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

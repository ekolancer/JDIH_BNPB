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
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Cache::remember('homepage_data', 300, function () {
            return [
                // Menghitung jumlah data berdasarkan tipeDok
                'jumlahDokumen' => DokumenHukum::countByType(),
                // Mengambil data visitorStats dari model Visitor
                'visitorStats' => Visitor::getVisitorStats(),
                // Mengambil peraturan terbaru dari model DokumenHukum
                'peraturanTerbaru' => DokumenHukum::latestPeraturan(4)->get(),
                // Mengambil peraturan terpopuler dari model DokumenHukum
                'peraturanPopuler' => DokumenHukum::mostViewedPeraturan(8)->get(),
                // Mengambil nilai unik untuk tipeDok dan jenisDok
                'dokumenAttributes' => DokumenHukum::getUniqueValues(['tipeDok', 'ref_jenis_dokumen_id']),
                // Mengambil data berita aktif dari model Berita
                'berita' => Berita::getBeritaActive(),
                // Mengambil data Infografis aktif dari model Infografis
                'infografis' => Infografis::getInfografisActive(),
            ];
        });

        // Mengirimkan data ke view
        return view('index', compact('data'));
    }
}

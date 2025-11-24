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
            'peraturanTerbaru' => DokumenHukum::getLatest(4),
            // Mengambil peraturan terpopuler dari model DokumenHukum
            'peraturanPopuler' => DokumenHukum::getMostViewed(8),
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
}

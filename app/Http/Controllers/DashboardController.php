<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Visitor;
use App\Models\Infografis;
use App\Models\DokumenHukum;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $data = Cache::remember('homepage_data', 300, function () {
            return [
                'jumlahDokumen' => DokumenHukum::countByType(),
                'visitorStats' => Visitor::getVisitorStats(),
                'peraturanTerbaru' => DokumenHukum::latestPeraturan(4)->get(),
                'peraturanPopuler' => DokumenHukum::mostViewedPeraturan(8)->get(),
                'dokumenAttributes' => DokumenHukum::getUniqueValues(['tipeDok', 'ref_jenis_dokumen_id']),
                'berita' => Berita::getBeritaActive(),
                'infografis' => Infografis::getInfografisActive(),
            ];
        });

        return view('index', compact('data'));
    }
}

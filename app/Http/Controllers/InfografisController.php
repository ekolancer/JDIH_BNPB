<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SeoHelper;

class InfografisController extends Controller
{
    public function index()
    {
        // Ambil data infografis dengan paginasi dan olah data gambar
        $infografis = Infografis::where('status', 1)
            ->latest()
            ->paginate(10); // Atur jumlah data per halaman

        return view('infografis.list', compact('infografis'));
    }

    public function show(string $slug)
    {
        $infografis = Infografis::where('slug', $slug)->firstOrFail();

        // increment view
        DB::table('infografis')
            ->where('id', $infografis->id)
            ->increment('view_count');

        // 🔥 SEO CONNECT
        $seo = SeoHelper::meta($infografis);

        return view('infografis.detail', [
            'infografis' => $infografis,
            'seo' => $seo, // 🔥 WAJIB
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SeoHelper;

use Illuminate\Support\Facades\Cache;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data berita dengan pagination
        $berita = Berita::where('status', 1)->latest()->paginate(10);

        // Ambil 5 berita terpopuler (cached 30 menit)
        $beritaTerpopuler = Cache::remember('berita_terpopuler', 1800, function () {
            return Berita::orderByDesc('view_count')->limit(5)->get();
        });

        return view('berita.list', compact('berita', 'beritaTerpopuler'));
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        DB::table('berita')
            ->where('id', $berita->id)
            ->increment('view_count');

        // Cached 30 menit
        $beritaTerpopuler = Cache::remember('berita_terpopuler', 1800, function () {
            return Berita::orderByDesc('view_count')->limit(5)->get();
        });

        $seo = SeoHelper::meta($berita);

        return view('berita.detail', [
            'berita' => $berita,
            'beritaTerpopuler' => $beritaTerpopuler,
            'seo' => $seo,
        ]);
    }
}

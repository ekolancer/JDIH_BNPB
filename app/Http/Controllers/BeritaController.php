<?php

namespace App\Http\Controllers;

use App\Helpers\SeoHelper;
use App\Models\Berita;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = Berita::where('status', 1)->latest()->paginate(10);

        $beritaTerpopuler = $this->getPopularBerita();

        return view('berita.list', compact('berita', 'beritaTerpopuler'));
    }

    public function show(string $slug): View
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        DB::table('berita')
            ->where('id', $berita->id)
            ->increment('view_count');

        $beritaTerpopuler = $this->getPopularBerita();

        $seo = SeoHelper::meta($berita);

        return view('berita.detail', [
            'berita' => $berita,
            'beritaTerpopuler' => $beritaTerpopuler,
            'seo' => $seo,
        ]);
    }

    private function getPopularBerita()
    {
        return Cache::remember('berita_terpopuler', 1800, function () {
            return Berita::orderByDesc('view_count')->limit(5)->get();
        });
    }
}

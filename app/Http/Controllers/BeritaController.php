<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SeoHelper;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data berita dengan pagination
        $berita = Berita::where('status', 1)->latest()->paginate(10); // Mengambil 10 data per halaman

        // Ambil 5 berita terpopuler berdasarkan view terbanyak
        $beritaTerpopuler = Berita::orderByDesc('view_count')
            ->limit(5)
            ->get();

        // Kirim data ke view
        return view('berita.list', compact('berita', 'beritaTerpopuler'));
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        DB::table('berita')
            ->where('id', $berita->id)
            ->increment('view_count');

        $beritaTerpopuler = Berita::orderByDesc('view_count')
            ->limit(5)
            ->get();

        // 🔥 TAMBAHKAN INI
        $seo = SeoHelper::meta($berita);

        return view('berita.detail', [
            'berita' => $berita,
            'beritaTerpopuler' => $beritaTerpopuler,
            'seo' => $seo, // 🔥 WAJIB
        ]);
    }
}

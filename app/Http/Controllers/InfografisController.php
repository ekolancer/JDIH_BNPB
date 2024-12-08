<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;

class InfografisController extends Controller
{
    public function index()
    {
        // Ambil data infografis dengan paginasi dan olah data gambar
        $infografis = Infografis::where('status', 1)
            ->latest()
            ->paginate(10) // Atur jumlah data per halaman
            ->through(function ($item) {
                // Decode JSON `gambar`
                if (is_string($item->gambar) && is_array(json_decode($item->gambar, true))) {
                    $gambarArray = json_decode($item->gambar, true);
                    $item->gambar = $gambarArray[0] ?? null; // Ambil gambar pertama
                } else {
                    $item->gambar = null; // Jika tidak valid, default null
                }
                return $item;
            });

        return view('infografis.list', compact('infografis'));
    }

    public function show(string $slug)
    {
        // Mengambil data peraturan berdasarkan judul
        $infografis = Infografis::where('slug', $slug)->firstOrFail();

        // Tingkatkan view count
        $infografis->increment('view_count');

        // Cek apakah gambar berupa array dan ambil gambar pertama
        $gambarPertama = is_array($infografis->gambar) ? $infografis->gambar[0] : $infografis->gambar;

        // Tentukan path file lampiran
        $fileLampiranPath = storage_path('app/public/' . $gambarPertama);

        return view('infografis.detail', compact('infografis'));
    }
}

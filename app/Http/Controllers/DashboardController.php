<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Monograf;
use App\Models\Peraturan;
use App\Models\Putusan;

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

        // Debugging untuk memastikan data dikirim
        // dd($data);

        // Mengirim data ke view
        return view('index', compact('data'));
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($tipeDok, $slug)
    {
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];
        if (!in_array($tipeDok, $validTypes)) {
            abort(404);
        }

        // Ambil data tanpa memicu observer
        $dokumen = DokumenHukum::findBySlug($tipeDok, $slug)->firstOrFail();

        // Update view_count tanpa trigger observer
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('view_count');

        return view('dokumen.detail', [
            'dokumen' => $dokumen,
            'lampiranSizeInKb' => $dokumen->lampiran_size['kb'],
            'lampiranSizeInMb' => $dokumen->lampiran_size['mb'],
            'abstrakSizeInKb' => $dokumen->abstrak_size['kb'],
            'abstrakSizeInMb' => $dokumen->abstrak_size['mb'],
            'metadata' => $dokumen->metadata,
            'buttonText' => $dokumen->button_text,
        ]);
    }

    public function filterByType($tipeDok)
    {
        // Validasi tipeDok agar hanya menerima yang diizinkan
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];

        if (!in_array(ucfirst($tipeDok), $validTypes)) {
            abort(404); // Jika tidak valid, lemparkan error 404
        }

        // Ambil dokumen berdasarkan tipe
        $results = DokumenHukum::where('tipeDok', ucfirst($tipeDok))->paginate(10);

        $dokumenAttributes = DokumenHukum::getUniqueValues(['tipeDok', 'jenisDok']);

        return view('dokumen.list-doc', compact('results', 'tipeDok', 'dokumenAttributes'));
    }

    /**
     * Unduh file lampiran
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function unduhLampiran($slug)
    {
        // Cari dokumen berdasarkan ID
        $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();

        // Mendapatkan path file
        $filePath = public_path('storage/' . $dokumen->lampiran);

        // Mengecek apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment download_count di database tanpa memperhatikan cache
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count', 1);

        // Mengembalikan file untuk diunduh
        return response()->download($filePath, null, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Unduh file abstrak
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function unduhAbstrak($slug)
    {
        // Cari dokumen berdasarkan ID
        $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();

        // Mendapatkan path file
        $filePath = public_path('storage/' . $dokumen->abstrak);

        // Mengecek apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Increment download_count di database tanpa memperhatikan cache
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count', 1);

        // Mengembalikan file untuk diunduh
        return response()->download($filePath, null, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}

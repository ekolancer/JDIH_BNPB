<?php

namespace App\Http\Controllers;

use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

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

        // Ambil dokumen + relasi referensi
        $dokumen = DokumenHukum::with([
            'jenisDokumen',
            'bidangHukumRef',
            'subjekRef',
        ])->findBySlug($tipeDok, $slug)->firstOrFail();

        // Update view_count tanpa trigger observer
        DB::table('dokumen_hukums')
            ->where('id', $dokumen->id)
            ->increment('view_count');

        return view('dokumen.detail', [
            'dokumen' => $dokumen,

            // ukuran file
            'lampiranSizeInKb' => $dokumen->lampiran_size['kb'],
            'lampiranSizeInMb' => $dokumen->lampiran_size['mb'],
            'abstrakSizeInKb'  => $dokumen->abstrak_size['kb'],
            'abstrakSizeInMb'  => $dokumen->abstrak_size['mb'],

            // 🔥 METADATA SUDAH BERBASIS REF TABLE
            'metadata' => [
                'Jenis Dokumen'        => $dokumen->jenisDokumen,            // ref
                // 'Singkatan'            => $dokumen->jenisDokumen?->singkatan,
                'Judul'                => $dokumen->judul,
                'Nomor'                => $dokumen->nomor,
                'Tahun'         => $dokumen->tahun,
                'Tajuk Entri Utama'    => $dokumen->teuBadan,
                'Bidang Hukum'         => $dokumen->bidangHukumRef,           // ref
                'Subjek'               => $dokumen->subjekRef,               // ref many
                // 'Penandatangan'        => $dokumen->penandatangan,
                'Tanggal Penetapan'    => $dokumen->tgl_penetapan_formatted ?? '—',
                'Tanggal Pengundangan' => $dokumen->tgl_pengundangan_formatted ?? '—',
                'Tempat Terbit'        => $dokumen->tempat,
                'Sumber'               => $dokumen->sumber,
                'Bahasa'               => $dokumen->bahasa,
                'Lokasi'               => $dokumen->lokasi,
            ],

            // 'breadcrumbs' => [
            //     ['label' => 'Dokumen Hukum', 'url' => route('list-doc')],
            //     ['label' => $dokumen->jenisDokumen?->nama ?? 'Dokumen', 'url' => route('list-doc') . '?ref_jenis_dokumen_id=' . $dokumen->ref_jenis_dokumen_id],
            //     ['label' => Str::limit($dokumen->judul, 50), 'url' => ''],
            // ],

            'buttonText' => $dokumen->button_text,
        ]);
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

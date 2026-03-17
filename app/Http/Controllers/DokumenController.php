<?php

namespace App\Http\Controllers;

use App\Models\DokumenHukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SeoHelper;
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
        // ✅ Validasi tipe dokumen
        $validTypes = ['Peraturan', 'Monografi', 'Artikel', 'Putusan'];

        abort_unless(in_array($tipeDok, $validTypes), 404);

        // ✅ Ambil dokumen + relasi
        $dokumen = DokumenHukum::with([
            'jenisDokumen',
            'bidangHukumRef',
            'subjekRef',
        ])->findBySlug($tipeDok, $slug)->firstOrFail();

        // ✅ Increment view tanpa trigger observer
        DB::table('dokumen_hukums')
            ->where('id', $dokumen->id)
            ->increment('view_count');

        // ✅ Generate SEO (INI YANG BARU 🔥)
        $seo = SeoHelper::meta($dokumen);

        return view('dokumen.detail', [
            'dokumen' => $dokumen,

            // ukuran file
            'lampiranSizeInKb' => $dokumen->lampiran_size['kb'],
            'lampiranSizeInMb' => $dokumen->lampiran_size['mb'],
            'abstrakSizeInKb'  => $dokumen->abstrak_size['kb'],
            'abstrakSizeInMb'  => $dokumen->abstrak_size['mb'],

            // metadata
            'metadata' => [
                'Jenis Dokumen'        => $dokumen->jenisDokumen,
                'Judul'                => $dokumen->judul,
                'Nomor'                => $dokumen->nomor,
                'Tahun'                => $dokumen->tahun,
                'Tajuk Entri Utama'    => $dokumen->teuBadan,
                'Bidang Hukum'         => $dokumen->bidangHukumRef,
                'Subjek'               => $dokumen->subjekRef,
                'Tanggal Penetapan'    => $dokumen->tgl_penetapan_formatted ?? '—',
                'Tanggal Pengundangan' => $dokumen->tgl_pengundangan_formatted ?? '—',
                'Tempat Terbit'        => $dokumen->tempat,
                'Sumber'               => $dokumen->sumber,
                'Bahasa'               => $dokumen->bahasa,
                'Lokasi'               => $dokumen->lokasi,
            ],

            'buttonText' => $dokumen->button_text,

            // 🔥 KIRIM SEO KE BLADE
            'seo' => $seo,
        ]);
    }


    /**
     * Unduh file lampiran
     *
     * @param int $dokumenId
     * @return \Illuminate\Http\Response
     */
    public function unduhLampiran(Request $request, string $slug)
    {
        $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();

        // 1. Check authorization
        if (!$dokumen->download_allowed) {
            abort(403, 'Dokumen tidak dapat diunduh');
        }

        // 2. Validate file exists di storage
        if (!$dokumen->lampiran_exists) {
            abort(404, 'File tidak ditemukan');
        }

        // 3. Use Storage facade (amannya)
        $filePath = storage_path('app/public/' . $dokumen->lampiran);
        return response()->download(
            $filePath,
            $this->sanitizeFilename($dokumen->judul) . '.' . $dokumen->file_extension,
            ['Content-Type' => $this->getMimeType($dokumen->file_extension)]
        );
    }

    private function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
    }

    private function getMimeType(string $extension): string
    {
        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'application/octet-stream',
        };
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
    public function fileView(DokumenHukum $dokumen)
    {
        abort_if(!$dokumen->file_tersedia, 404);

        return response()->file(storage_path('app/public/' . $dokumen->lampiran));
    }
}

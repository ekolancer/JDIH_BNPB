<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dokumen\DownloadDokumenRequest;
use App\Http\Requests\Dokumen\ShowDokumenRequest;
use App\Http\Requests\Dokumen\ViewDokumenFileRequest;
use App\Models\DokumenHukum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\SeoHelper;

class DokumenController extends Controller
{
    /**
     * Menampilkan detail dokumen hukum
     */
    public function show(ShowDokumenRequest $request, $tipeDok, $slug)
    {
        $dokumen = DokumenHukum::with(['jenisDokumen', 'bidangHukumRef', 'subjekRef'])
            ->findBySlug($tipeDok, $slug)
            ->firstOrFail();

        // 3. Increment view tanpa trigger event observer / updated_at (Best Practice)
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('view_count');

        // 4. Return ke view. 
        // Variabel yang dikirim jauh lebih sedikit karena Blade dapat langsung memanggil 
        // accessor dari Model (misal: $dokumen->tipe_dok_label, $dokumen->metadata)
        return view('dokumen.detail', [
            'dokumen'  => $dokumen,
            'metadata' => $dokumen->metadata, // Memanggil getMetadataAttribute() dari Model
            'tipeDokLabel' => $dokumen->tipe_dok_label,
            'seo'      => SeoHelper::meta($dokumen),
        ]);
    }

    /**
     * Unduh file lampiran utama
     */
    public function unduhLampiran(DownloadDokumenRequest $request, $slug)
    {
        $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();

        Gate::authorize('download', $dokumen);

        // 1. Validasi akses dan ketersediaan file menggunakan Accessor Model
        abort_unless($dokumen->download_allowed, 403, 'Dokumen tidak dapat diunduh');
        abort_unless($this->isSafePublicStoragePath($dokumen->lampiran), 404, 'Path file tidak valid');
        abort_unless($dokumen->lampiran_exists, 404, 'File fisik tidak ditemukan');

        // 2. Increment download count
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count');

        // 3. Format nama file agar rapi (misal: peraturan-nomor-1-tahun-2024.pdf)
        $filename = basename($dokumen->lampiran);

        // 4. Gunakan Storage::download (Otomatis handle MimeType & Headers)
        return Storage::disk('public')->download($dokumen->lampiran, $filename);
    }

    /**
     * Unduh file abstrak
     */
    public function unduhAbstrak(DownloadDokumenRequest $request, $slug)
    {
        $dokumen = DokumenHukum::where('slug', $slug)->firstOrFail();

        Gate::authorize('download', $dokumen);

        // 1. Validasi eksistensi file menggunakan facade Storage
        abort_unless($this->isSafePublicStoragePath($dokumen->abstrak), 404, 'Path file abstrak tidak valid');
        abort_unless($dokumen->abstrak && Storage::disk('public')->exists($dokumen->abstrak), 404, 'File abstrak tidak ditemukan');

        // 2. Increment download count (Atau buat abstrak_download_count di tabel jika ingin dipisah)
        DB::table('dokumen_hukums')->where('id', $dokumen->id)->increment('download_count');

        // 3. Format nama file
        $extension = pathinfo($dokumen->abstrak, PATHINFO_EXTENSION);
        $filename = 'Abstrak-' . Str::slug($dokumen->judul) . '.' . $extension;

        return Storage::disk('public')->download($dokumen->abstrak, $filename, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0'
        ]);
    }

    /**
     * Pratinjau file langsung di browser (PDF Viewer)
     */
    public function fileView(ViewDokumenFileRequest $request, DokumenHukum $dokumen)
    {
        Gate::authorize('viewFile', $dokumen);

        // Route Model Binding ($dokumen otomatis dicari berdasarkan slug sesuai getRouteKeyName di model)
        abort_unless($this->isSafePublicStoragePath($dokumen->lampiran), 404, 'Path file tidak valid');
        abort_unless($dokumen->file_tersedia, 404, 'File tidak tersedia untuk dilihat');

        // Gunakan Storage::path untuk mendapatkan path absolute yang benar di berbagai OS
        return response()->file(Storage::disk('public')->path($dokumen->lampiran));
    }

    private function isSafePublicStoragePath(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        $normalized = str_replace('\\', '/', $path);

        return !str_starts_with($normalized, '/')
            && !preg_match('/(^|\/)\.\.(\/|$)/', $normalized);
    }
}

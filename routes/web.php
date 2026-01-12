<?php

use App\Http\Controllers\ApiController;
use App\Models\DokumenHukum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfografisController;
use App\Models\Faq;
use App\Models\ProfilPage;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dokumen', function () {
    return view('dokumen.list-doc');
})->name('list-doc');

Route::get('/profil/{slug}', function ($slug) {

    // Jika halaman FAQ
    if ($slug === 'faq') {
        return view('overview.page', [
            'slug' => $slug,
            'page' => (object)[
                'title' => 'FAQ',
                'content' => null,
                'image' => null,
            ],
            'faqs' => Faq::orderBy('sort_order')->get(),
        ]);
    }

    // Halaman biasa dari tabel profil_pages
    $page = ProfilPage::where('slug', $slug)->firstOrFail();

    return view('overview.page', [
        'page' => $page,
        'slug' => $slug,
        'faqs' => [],
    ]);
});

// Route Dokumen Hukum
Route::get('dokumen/{tipeDok}/{slug}', [DokumenController::class, 'show'])
    ->where('tipeDok', 'Peraturan|Monografi|Artikel|Putusan')
    ->name('dokumen.detail');
Route::get('/download-lampiran/{slug}', [DokumenController::class, 'unduhLampiran'])->name('dokumen.unduhLampiran');
Route::get('/download-abstrak/{slug}', [DokumenController::class, 'unduhAbstrak'])->name('dokumen.unduhAbstrak');

//Route Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

//Route Infografis
Route::get('/infografis', [InfografisController::class, 'index'])->name('infografis.index');
Route::get('/infografis/{slug}', [InfografisController::class, 'show'])->name('infografis.show');

// Route Rating Website
Route::post('/', [SurveyController::class, 'store'])->name('survey.store');

// API endpoint Rating
Route::get('/api/rating', [SurveyController::class, 'getRatingDistribution'])->name('rating.distribution');
Route::get('/api/rating-average', [SurveyController::class, 'getAverageRating'])->name('rating.average');
Route::get('/api/rating-summary', [SurveyController::class, 'getRatingSummary'])->name('rating.summary');
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/metadata/api', [ApiController::class, 'index']);
});

// Route::get('/check-data', function () {
//     try {
//         // Ambil semua data dari model
//         $data = DokumenHukum::all();

//         // Jika data kosong
//         if ($data->isEmpty()) {
//             return response()->json([
//                 'message' => 'Tidak ada data ditemukan.',
//             ], 200);
//         }

//         // Tampilkan data sebagai HTML sederhana
//         return response()->make('<pre>' . print_r($data->toArray(), true) . '</pre>');
//     } catch (\Exception $e) {
//         // Jika terjadi error
//         return response()->json([
//             'error' => 'Terjadi masalah saat memuat data.',
//             'message' => $e->getMessage(),
//         ], 500);
//     }
// });
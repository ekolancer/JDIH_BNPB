<?php

use App\Models\DokumenHukum;
use App\Livewire\DokumenList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfografisController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/list-doc', function () {
    return view('dokumen.list-doc');
})->name('list-doc');
Route::get('/about', [DashboardController::class, 'about'])->name('about');
Route::get('/visi-misi', [DashboardController::class, 'visiMisi'])->name('visiMisi');
Route::get('/SOTK', [DashboardController::class, 'struktur'])->name('struktur');

// Route Dokumen Hukum
Route::get('/dokumen/all', [DokumenController::class, 'index'])->name('dokumen.index');
Route::get('/dokumen/{tipeDok}', [DokumenController::class, 'filterByType'])
    ->where('tipeDok', 'peraturan|monografi|artikel|putusan')
    ->name('dokumen.filter');
Route::get('dokumen/{tipeDok}/{slug}', [DokumenController::class, 'show'])
    ->where('tipeDok', 'Peraturan|Monografi|Artikel|Putusan')
    ->name('dokumen.detail');
Route::get('/dokumen/{slug}/download-lampiran', [DokumenController::class, 'unduhLampiran'])->name('dokumen.unduhLampiran');
Route::get('/dokumen/{slug}/download-abstrak', [DokumenController::class, 'unduhAbstrak'])->name('dokumen.unduhAbstrak');

//Route Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}/detail', [BeritaController::class, 'show'])->name('berita.show');

//Route Infografis
Route::get('/infografis', [InfografisController::class, 'index'])->name('infografis.index');
Route::get('/infografis/{slug}/detail', [InfografisController::class, 'show'])->name('infografis.show');

// Route Rating Website
Route::post('/', [SurveyController::class, 'store'])->name('survey.store');

// API endpoint Rating
Route::get('/api/rating', [SurveyController::class, 'getRatingDistribution'])->name('rating.distribution');
Route::get('/api/rating-average', [SurveyController::class, 'getAverageRating'])->name('rating.average');
Route::get('/api/rating-summary', [SurveyController::class, 'getRatingSummary'])->name('rating.summary');

Route::get('/check-data', function () {
    try {
        // Ambil semua data dari model
        $data = DokumenHukum::all();

        // Jika data kosong
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data ditemukan.',
            ], 200);
        }

        // Tampilkan data sebagai HTML sederhana
        return response()->make('<pre>' . print_r($data->toArray(), true) . '</pre>');
    } catch (\Exception $e) {
        // Jika terjadi error
        return response()->json([
            'error' => 'Terjadi masalah saat memuat data.',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Route 404 Page
Route::fallback(function () {
    return response()->view('404', [], 404);
});

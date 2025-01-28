<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\MonografController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\PutusanController;
use App\Http\Controllers\SurveyController;
use App\Models\DokumenHukum;
use League\Csv\Query\Row;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/search/list', [DashboardController::class, 'search'])->name('dashboard.search');
Route::get('/about', [DashboardController::class, 'about'])->name('about');
Route::get('/visi-misi', [DashboardController::class, 'visiMisi'])->name('visiMisi');
Route::get('/SOTK', [DashboardController::class, 'struktur'])->name('struktur');

//Route Peraturan
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{slug}/detail', [PeraturanController::class, 'show'])->name('peraturan.show');

//Route Monograf
Route::get('/monograf', [MonografController::class, 'index'])->name('monograf.index');
Route::get('/monograf/{slug}/detail', [MonografController::class, 'show'])->name('monograf.show');

//Route Artikel
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}/detail', [ArtikelController::class, 'show'])->name('artikel.show');

//Route Putusan
Route::get('/putusan', [PutusanController::class, 'index'])->name('putusan.index');
Route::get('/putusan/{slug}/detail', [PutusanController::class, 'show'])->name('putusan.show');

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

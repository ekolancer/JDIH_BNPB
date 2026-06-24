<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Endpoint Rating Website
|--------------------------------------------------------------------------
| Endpoint JSON untuk grafik/distribusi rating pada halaman utama.
| Route API memakai prefix kosong dari bootstrap/app.php agar URL publik
| tetap kompatibel dengan /api/rating* yang sudah digunakan frontend.
*/
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/api/rating', [SurveyController::class, 'getRatingDistribution'])->name('api.rating.distribution');
    Route::get('/api/rating-average', [SurveyController::class, 'getAverageRating'])->name('api.rating.average');
    Route::get('/api/rating-summary', [SurveyController::class, 'getRatingSummary'])->name('api.rating.summary');
});

/*
|--------------------------------------------------------------------------
| Endpoint Metadata Dokumen
|--------------------------------------------------------------------------
| GET /metadata/api mengembalikan metadata dokumen hukum untuk sinkronisasi
| eksternal. Dibatasi throttle 60 request per menit.
*/
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/metadata/api', [ApiController::class, 'index'])->name('api.metadata');
});

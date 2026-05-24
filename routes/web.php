<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\ProfilPageController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
| GET / menampilkan dashboard publik JDIH, termasuk ringkasan dokumen,
| berita, infografis, statistik pengunjung, dan widget rating.
| POST / dipakai form rating di halaman utama.
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

Route::post('/', [SurveyController::class, 'store'])->middleware(['throttle:5,1', 'turnstile'])->name('survey.store');

/*
|--------------------------------------------------------------------------
| Daftar Dokumen Hukum
|--------------------------------------------------------------------------
| GET /dokumen menampilkan semua dokumen hukum.
| GET /dokumen/{tipeDok} menampilkan daftar dokumen berdasarkan tipe dokumen.
|
| Catatan audit:
| - Sebelumnya ada route /dokumen dan /dokumen/{tipeDok?} yang tumpang tindih.
|   Keduanya digabung agar tidak double dan agar route('list-doc', ['tipeDok' => ...])
|   menghasilkan path /dokumen/{tipeDok}, bukan query string.
*/
Route::get('/dokumen/{tipeDok?}', function ($tipeDok = null) {
    return view('dokumen.list-doc', compact('tipeDok'));
})->name('list-doc');

/*
|--------------------------------------------------------------------------
| Halaman Profil
|--------------------------------------------------------------------------
| GET /profil/{slug} menampilkan halaman profil dinamis dari tabel
| profil_pages. Slug "faq" diperlakukan khusus untuk menampilkan daftar FAQ
| dari tabel faqs pada view overview yang sama.
*/
Route::get('/profil/{slug}', [ProfilPageController::class, 'show'])->name('profil.show');

/*
|--------------------------------------------------------------------------
| Detail, Pratinjau, dan Unduhan Dokumen
|--------------------------------------------------------------------------
| GET /dokumen/{tipeDok}/{slug} menampilkan detail dokumen hukum.
| GET /dokumen/view/{dokumen:slug} membuka file dokumen langsung di browser.
| GET /download-lampiran/{slug} mengunduh lampiran utama dokumen.
| GET /download-abstrak/{slug} mengunduh file abstrak dokumen.
|
| Catatan audit:
| - Target controller dan method tersedia di DokumenController.
| - View dokumen.detail tersedia.
| - Route detail dibatasi hanya untuk tipe Peraturan, Monografi, Artikel,
|   dan Putusan agar tidak bentrok dengan route dokumen lain.
*/
Route::get('dokumen/{tipeDok}/{slug}', [DokumenController::class, 'show'])
    ->where('tipeDok', 'Peraturan|Monografi|Artikel|Putusan')
    ->name('dokumen.detail');
Route::get('/download-lampiran/{slug}', [DokumenController::class, 'unduhLampiran'])->name('dokumen.unduhLampiran');
Route::get('/dokumen/view/{dokumen:slug}', [DokumenController::class, 'fileView'])->name('dokumen.view');
Route::get('/download-abstrak/{slug}', [DokumenController::class, 'unduhAbstrak'])->name('dokumen.unduhAbstrak');

/*
|--------------------------------------------------------------------------
| Berita
|--------------------------------------------------------------------------
| GET /berita menampilkan daftar berita aktif.
| GET /berita/{slug} menampilkan detail berita dan menaikkan view_count.
|
| Catatan audit:
| - Target controller, method, dan view berita.list/berita.detail tersedia.
*/
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

/*
|--------------------------------------------------------------------------
| Infografis
|--------------------------------------------------------------------------
| GET /infografis menampilkan daftar infografis aktif.
| GET /infografis/{slug} menampilkan detail infografis dan menaikkan view_count.
|
| Catatan audit:
| - Target controller, method, dan view infografis.list/infografis.detail tersedia.
*/
Route::get('/infografis', [InfografisController::class, 'index'])->name('infografis.index');
Route::get('/infografis/{slug}', [InfografisController::class, 'show'])->name('infografis.show');

/*
|--------------------------------------------------------------------------
| Endpoint Rating Website
|--------------------------------------------------------------------------
| Endpoint JSON untuk grafik/distribusi rating pada halaman utama.
| GET /api/rating mengembalikan jumlah rating per nilai 1-5.
| GET /api/rating-average mengembalikan rata-rata rating.
| GET /api/rating-summary mengembalikan rata-rata dan total rating.
*/
Route::get('/api/rating', [SurveyController::class, 'getRatingDistribution'])->middleware('throttle:30,1')->name('rating.distribution');
Route::get('/api/rating-average', [SurveyController::class, 'getAverageRating'])->middleware('throttle:30,1')->name('rating.average');
Route::get('/api/rating-summary', [SurveyController::class, 'getRatingSummary'])->middleware('throttle:30,1')->name('rating.summary');

/*
|--------------------------------------------------------------------------
| Endpoint Metadata Dokumen
|--------------------------------------------------------------------------
| GET /metadata/api mengembalikan metadata dokumen hukum untuk sinkronisasi
| eksternal. Dibatasi throttle 60 request per menit.
|
| Catatan audit:
| - Target ApiController@index tersedia.
| - Route ini sebelumnya belum memiliki nama; ditambahkan metadata.api agar
|   mudah dirujuk oleh kode atau dokumentasi.
*/
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/metadata/api', [ApiController::class, 'index'])->name('metadata.api');
});

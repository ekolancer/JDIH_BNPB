<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\ProfilPageController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

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
Route::get('/dokumen/{tipeDok?}', [DokumenController::class, 'index'])->name('list-doc');

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
Route::get('/dokumen/view/{dokumen:slug}', [DokumenController::class, 'fileView'])->name('dokumen.view');
Route::get('dokumen/{tipeDok}/{slug}', [DokumenController::class, 'show'])->name('dokumen.detail');
Route::get('/download-lampiran/{slug}', [DokumenController::class, 'unduhLampiran'])->name('dokumen.unduhLampiran');
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

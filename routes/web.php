<?php

use App\Http\Controllers\ArtikelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonografController;
use App\Http\Controllers\PeraturanController;
use App\Http\Controllers\PutusanController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

//Route Peraturan
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{slug}/detail', [PeraturanController::class, 'show'])->name('peraturan.show');
Route::get('/storage/{lampiran}', [PeraturanController::class, 'download'])->name('peraturan.download');

//Route Monograf
Route::get('/monograf', [MonografController::class, 'index'])->name('monograf.index');
Route::get('/monograf/{slug}/detail', [MonografController::class, 'show'])->name('monograf.show');

//Route Artikel
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}/detail', [ArtikelController::class, 'show'])->name('artikel.show');

//Route Putusan
Route::get('/putusan', [PutusanController::class, 'index'])->name('putusan.index');
Route::get('/putusan/{slug}/detail', [PutusanController::class, 'show'])->name('putusan.show');
// Route::get('/storage/{lampiran}', [PutusanController::class, 'download'])->name('putusan.download');
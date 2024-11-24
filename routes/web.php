<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonografController;
use App\Http\Controllers\PeraturanController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

//Route Peraturan
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{judul}', [PeraturanController::class, 'show'])->name('peraturan.show');

//Route Monograf
Route::get('/monograf', [MonografController::class, 'index'])->name('monograf.index');
Route::get('/monograf/{judul}', [MonografController::class, 'show'])->name('monograf.show');

//Route Artikel
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{judul}', [PeraturanController::class, 'show'])->name('peraturan.show');

//Route Putusan
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{judul}', [PeraturanController::class, 'show'])->name('peraturan.show');
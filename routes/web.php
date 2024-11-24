<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeraturanController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/peraturan', [PeraturanController::class, 'index'])->name('peraturan.index');
Route::get('/peraturan/{judul}', [PeraturanController::class, 'show'])->name('peraturan.show');
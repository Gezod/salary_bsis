<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); // Atau redirect jika kamu mau langsung ke login
});

// Route Breeze (Login/Register/etc)
require __DIR__.'/auth.php';

// Semua route di bawah ini hanya bisa diakses setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource presensi
    Route::resource('presensi', PresensiController::class);

    // Import dari Excel
    Route::post('/presensi/import', [PresensiController::class, 'import'])->name('presensi.import');
});

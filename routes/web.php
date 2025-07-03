<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Api\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Breeze (Login/Register/etc)
require __DIR__ . '/auth.php';

// Semua route di bawah ini hanya bisa diakses setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource presensi
    Route::resource('presensi', PresensiController::class);

    // Import dari Excel
    Route::post('/presensi/import', [PresensiController::class, 'import'])->name('presensi.import');

    Route::get('/absensi/import', [ImportController::class, 'create'])->name('absensi.import.form');
    Route::post('/absensi/import', [ImportController::class, 'store'])->name('absensi.import');

    /* Tabel harian & filter */
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');

    /* Rekap bulanan denda */
    Route::get('/absensi/rekap', [AttendanceController::class, 'recap'])->name('absensi.recap');
    Route::get('/absensi/evaluate-all', [AttendanceController::class, 'reevaluateAll']);

    // Input Manual Absensi
    Route::get('/absensi/manual', [AttendanceController::class, 'manual'])->name('absensi.manual');
    Route::post('/absensi/manual', [AttendanceController::class, 'manualStore'])->name('absensi.manual.store');

    // Denda
    Route::get('/absensi/denda', [AttendanceController::class, 'denda'])->name('absensi.denda');
    Route::put('/absensi/denda', [AttendanceController::class, 'dendaUpdate'])->name('absensi.denda.update');
});
Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('api.employees.search');

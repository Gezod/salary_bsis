<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\OvertimeController;

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

    // API untuk karyawan
     // Employee Management Routes
    Route::get('/absensi/role', [AttendanceController::class, 'role'])->name('absensi.role');
    Route::post('/absensi/role', [AttendanceController::class, 'roleStore'])->name('absensi.role.store');
    Route::get('/absensi/role/{id}/edit', [AttendanceController::class, 'roleEdit'])->name('absensi.role.edit');
    Route::put('/absensi/role/{id}', [AttendanceController::class, 'roleUpdate'])->name('absensi.role.update');
    Route::delete('/absensi/role/{id}', [AttendanceController::class, 'roleDestroy'])->name('absensi.role.destroy');

    // Overtime Management Routes
    Route::get('/overtime', [OvertimeController::class, 'overview'])->name('overtime.overview');
    Route::get('/overtime/records', [OvertimeController::class, 'index'])->name('overtime.index');
    Route::get('/overtime/settings', [OvertimeController::class, 'settings'])->name('overtime.settings');
    Route::put('/overtime/settings', [OvertimeController::class, 'updateSettings'])->name('overtime.settings.update');
    Route::get('/overtime/recap', [OvertimeController::class, 'recap'])->name('overtime.recap');
    Route::get('/overtime/recalculate', [OvertimeController::class, 'recalculateAll'])->name('overtime.recalculate');
});
Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('api.employees.search');

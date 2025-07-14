<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayrollController;

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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Presensi Routes
    Route::resource('presensi', PresensiController::class);
    Route::post('/presensi/import', [PresensiController::class, 'import'])->name('presensi.import');

    // Absensi Routes
    Route::prefix('absensi')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('absensi.index');
        Route::get('/import', [ImportController::class, 'create'])->name('absensi.import.form');
        Route::post('/import', [ImportController::class, 'store'])->name('absensi.import');
        Route::get('/rekap', [AttendanceController::class, 'recap'])->name('absensi.recap');
        Route::get('/evaluate-all', [AttendanceController::class, 'reevaluateAll']);
        Route::get('/manual', [AttendanceController::class, 'manual'])->name('absensi.manual');
        Route::post('/manual', [AttendanceController::class, 'manualStore'])->name('absensi.manual.store');
        Route::get('/denda', [AttendanceController::class, 'denda'])->name('absensi.denda');
        Route::put('/denda', [AttendanceController::class, 'dendaUpdate'])->name('absensi.denda.update');

        // Role Management
        Route::get('/role', [AttendanceController::class, 'role'])->name('absensi.role');
        Route::post('/role', [AttendanceController::class, 'roleStore'])->name('absensi.role.store');
        Route::get('/role/{id}/edit', [AttendanceController::class, 'roleEdit'])->name('absensi.role.edit');
        Route::put('/role/{id}', [AttendanceController::class, 'roleUpdate'])->name('absensi.role.update');
        Route::delete('/role/{id}', [AttendanceController::class, 'roleDestroy'])->name('absensi.role.destroy');
    });

    // Overtime Routes
    Route::prefix('overtime')->group(function () {
        Route::get('/', [OvertimeController::class, 'overview'])->name('overtime.overview');
        Route::get('/records', [OvertimeController::class, 'index'])->name('overtime.index');
        Route::get('/settings', [OvertimeController::class, 'settings'])->name('overtime.settings');
        Route::put('/settings', [OvertimeController::class, 'updateSettings'])->name('overtime.settings.update');
        Route::get('/recap', [OvertimeController::class, 'recap'])->name('overtime.recap');
        Route::get('/recalculate', [OvertimeController::class, 'recalculateAll'])->name('overtime.recalculate');
    });

    // Payroll Routes
    Route::prefix('payroll')->group(function () {
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
        Route::get('/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::put('/payroll/{id}/payment', [PayrollController::class, 'updatePayment'])->name('payroll.update.payment');
        Route::get('/payroll/{id}/recalculate', [PayrollController::class, 'recalculate'])->name('payroll.recalculate');
        Route::get('/payroll/{id}/download', [PayrollController::class, 'downloadIndividualPdf'])->name('payroll.download.individual');
        Route::get('/payroll/settings/salary', [PayrollController::class, 'settings'])->name('payroll.settings');
        Route::post('/payroll/settings/salary', [PayrollController::class, 'updateSalary'])->name('payroll.update.salary');
        Route::post('/payroll/settings/bank', [PayrollController::class, 'updateEmployeeBankDetails'])->name('payroll.update.bank');
        Route::get('/payroll/export/pdf', [PayrollController::class, 'exportPdf'])->name('payroll.export.pdf');
    });
});

// API Routes
Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('api.employees.search');

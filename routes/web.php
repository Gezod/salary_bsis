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
        Route::get('/{id}/details', [AttendanceController::class, 'show'])->name('absensi.show');
        Route::get('/{id}/edit', [AttendanceController::class, 'edit'])->name('absensi.edit');
        Route::post('/{id}/update', [AttendanceController::class, 'update'])->name('absensi.update');
        Route::post('/{id}/overtime-status', [AttendanceController::class, 'updateOvertimeStatus'])->name('absensi.overtime-status');
        Route::get('/{id}/details', [AttendanceController::class, 'show'])->name('absensi.show');
        Route::get('/{id}/edit', [AttendanceController::class, 'edit'])->name('absensi.edit');
        Route::post('/{id}/update', [AttendanceController::class, 'update'])->name('absensi.update');
        Route::get('/import', [ImportController::class, 'create'])->name('absensi.import.form');
        Route::post('/import', [ImportController::class, 'store'])->name('absensi.import');
        Route::get('/rekap', [AttendanceController::class, 'recap'])->name('absensi.recap');
        Route::get('/late-recap', [AttendanceController::class, 'lateRecap'])->name('absensi.late-recap');
        Route::delete('/destroy', [AttendanceController::class, 'destroy'])->name('destroyAbsensi');
        Route::get('/evaluate-all', [AttendanceController::class, 'reevaluateAll']);
        Route::get('/manual', [AttendanceController::class, 'manual'])->name('absensi.manual');
        Route::post('/manual', [AttendanceController::class, 'manualStore'])->name('absensi.manual.store');
        Route::get('/absensi/{id}/row-data', [AttendanceController::class, 'getRowData']);

        // Half Day Manual Routes
        Route::get('/half-day-manual', [AttendanceController::class, 'halfDayManual'])->name('absensi.half-day-manual');
        Route::post('/half-day-manual', [AttendanceController::class, 'halfDayManualStore'])->name('absensi.half-day-manual.store');

        // Half Day Manual Routes
        Route::get('/half-day-manual', [AttendanceController::class, 'halfDayManual'])->name('absensi.half-day-manual');
        Route::post('/half-day-manual', [AttendanceController::class, 'halfDayManualStore'])->name('absensi.half-day-manual.store');

        Route::get('/denda', [AttendanceController::class, 'denda'])->name('absensi.denda');
        Route::put('/denda', [AttendanceController::class, 'dendaUpdate'])->name('absensi.denda.update');
        Route::get('/denda/individual', [AttendanceController::class, 'dendaIndividual'])->name('absensi.denda.individual');
        Route::get('/denda/employee/{id}', [AttendanceController::class, 'dendaEmployeeDetail'])->name('absensi.denda.employee.detail');
        Route::get('/denda/export/individual/{id}', [AttendanceController::class, 'dendaExportIndividualPdf'])->name('absensi.denda.export.individual');
        Route::get('/denda/export/all', [AttendanceController::class, 'dendaExportAllPdf'])->name('absensi.denda.export.all');

        // Leave Routes
        Route::get('/leave', [AttendanceController::class, 'leaveIndex'])->name('absensi.leave.index');
        Route::get('/leave/create', [AttendanceController::class, 'leaveCreate'])->name('absensi.leave.create');
        Route::post('/leave', [AttendanceController::class, 'leaveStore'])->name('absensi.leave.store');
        Route::post('leave/store', [AttendanceController::class, 'leaveStore'])->name('absensi.leavestore');
        Route::post('leave/{id}/update-status', [AttendanceController::class, 'leaveUpdateStatus'])->name('absensi.update.status');
        Route::get('/leave/{id}', [AttendanceController::class, 'leaveShow'])->name('absensi.leaveshow');

        // Work Time Change Routes
        Route::get('/work-time-change', [AttendanceController::class, 'workTimeChangeIndex'])->name('absensi.work_time_change.index');
        Route::get('/work-time-change/create', [AttendanceController::class, 'workTimeChangeCreate'])->name('absensi.work_time_change.create');
        Route::post('/work-time-change', [AttendanceController::class, 'workTimeChangeStore'])->name('absensi.work_time_change.store');

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
        Route::get('/', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
        Route::get('/{id}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::put('/{id}/payment', [PayrollController::class, 'updatePayment'])->name('payroll.update.payment');
        Route::get('/{id}/recalculate', [PayrollController::class, 'recalculate'])->name('payroll.recalculate');
        Route::get('/{id}/download', [PayrollController::class, 'downloadIndividualPdf'])->name('payroll.download.individual');
        Route::get('/settings/salary', [PayrollController::class, 'settings'])->name('payroll.settings');
        Route::post('/settings/salary', [PayrollController::class, 'updateSalary'])->name('payroll.update.salary');
        Route::post('/settings/bank', [PayrollController::class, 'updateEmployeeBankDetails'])->name('payroll.update.bank');
        Route::get('/export/pdf', [PayrollController::class, 'exportPdf'])->name('payroll.export.pdf');
    });
});

// API Routes
Route::get('/api/employees/search', [EmployeeController::class, 'search'])->name('api.employees.search');

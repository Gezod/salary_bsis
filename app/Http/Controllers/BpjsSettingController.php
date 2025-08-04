<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\BpjsSetting;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BpjsSettingController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::orderBy('nama')->get();
            $bpjsSettings = BpjsSetting::with('employee')->get()->keyBy('employee_id');

            Log::info('BPJS Settings loaded', [
                'employees_count' => $employees->count(),
                'bpjs_settings_count' => $bpjsSettings->count()
            ]);

            return view('bpjs.settings', compact('employees', 'bpjsSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading BPJS settings: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data pengaturan BPJS: ' . $e->getMessage());
        }
    }

    public function updateBpjs(Request $request)
    {
        try {
            // Get existing BPJS setting for this employee to handle unique validation properly
            $existingBpjsSetting = BpjsSetting::where('employee_id', $request->employee_id)->first();

            // Dynamic unique validation rule
            $uniqueRule = 'required|string|max:50|unique:bpjs_settings,bpjs_number';
            if ($existingBpjsSetting) {
                $uniqueRule .= ',' . $existingBpjsSetting->id;
            }

            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'bpjs_number' => $uniqueRule,
                'bpjs_monthly_amount' => 'required|integer|min:0|max:1000000',
                'bpjs_weekly_amount' => 'required|integer|min:0|max:250000',
                'is_active' => 'nullable|boolean', // Changed to nullable
                'notes' => 'nullable|string|max:500'
            ], [
                'employee_id.required' => 'Karyawan/Staff harus dipilih',
                'employee_id.exists' => 'Karyawan/Staff tidak ditemukan',
                'bpjs_number.required' => 'Nomor BPJS harus diisi',
                'bpjs_number.unique' => 'Nomor BPJS sudah terdaftar untuk karyawan lain',
                'bpjs_monthly_amount.required' => 'Jumlah BPJS bulanan harus diisi',
                'bpjs_monthly_amount.integer' => 'Jumlah BPJS bulanan harus berupa angka',
                'bpjs_monthly_amount.min' => 'Jumlah BPJS bulanan tidak boleh negatif',
                'bpjs_monthly_amount.max' => 'Jumlah BPJS bulanan terlalu besar',
                'bpjs_weekly_amount.required' => 'Jumlah BPJS mingguan harus diisi',
                'bpjs_weekly_amount.integer' => 'Jumlah BPJS mingguan harus berupa angka',
                'bpjs_weekly_amount.min' => 'Jumlah BPJS mingguan tidak boleh negatif',
                'bpjs_weekly_amount.max' => 'Jumlah BPJS mingguan terlalu besar',
                'notes.max' => 'Catatan terlalu panjang (maksimal 500 karakter)'
            ]);

            $employee = Employee::findOrFail($request->employee_id);

            // Handle checkbox properly - if not present, set to false
            $isActive = $request->has('is_active') ? (bool) $request->is_active : false;

            $bpjsSetting = BpjsSetting::updateOrCreate(
                ['employee_id' => $request->employee_id],
                [
                    'bpjs_number' => $request->bpjs_number,
                    'bpjs_monthly_amount' => $request->bpjs_monthly_amount,
                    'bpjs_weekly_amount' => $request->bpjs_weekly_amount,
                    'is_active' => $isActive,
                    'notes' => $request->notes,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]
            );

            Log::info('BPJS setting updated successfully', [
                'employee_id' => $employee->id,
                'employee_name' => $employee->nama,
                'bpjs_number' => $request->bpjs_number,
                'monthly_amount' => $request->bpjs_monthly_amount,
                'weekly_amount' => $request->bpjs_weekly_amount,
                'is_active' => $isActive,
                'action' => $bpjsSetting->wasRecentlyCreated ? 'created' : 'updated',
                'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ]);

            $actionText = $bpjsSetting->wasRecentlyCreated ? 'ditambahkan' : 'diperbarui';

            return redirect()->route('bpjs.settings')
                ->with('success', "Pengaturan BPJS untuk {$employee->nama} berhasil {$actionText}!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('BPJS validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali.');

        } catch (\Exception $e) {
            Log::error('Error updating BPJS setting', [
                'error' => $e->getMessage(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data BPJS: ' . $e->getMessage());
        }
    }

    public function show($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $bpjsSetting = BpjsSetting::where('employee_id', $employeeId)->first();

            return response()->json([
                'success' => true,
                'employee' => $employee,
                'bpjs_setting' => $bpjsSetting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEmployeeBpjs($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $bpjsSetting = BpjsSetting::where('employee_id', $employeeId)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'employee' => $employee,
                    'bpjs_setting' => $bpjsSetting
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $bpjsSetting = BpjsSetting::findOrFail($id);
            $employeeName = $bpjsSetting->employee->nama;

            $bpjsSetting->delete();

            Log::info('BPJS setting deleted', [
                'employee_name' => $employeeName,
                'bpjs_setting_id' => $id,
                'deleted_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ]);

            return redirect()->route('bpjs.settings')
                ->with('success', "Pengaturan BPJS untuk {$employeeName} berhasil dihapus!");

        } catch (\Exception $e) {
            Log::error('Error deleting BPJS setting: ' . $e->getMessage());

            return back()->with('error', 'Gagal menghapus pengaturan BPJS: ' . $e->getMessage());
        }
    }
}

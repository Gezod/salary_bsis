<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\BpjsSetting;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        return $this->updateBpjs($request);
    }

    public function updateBpjs(Request $request)
    {
        try {
            // Clean the monthly amount from formatting
            if ($request->has('bpjs_monthly_amount')) {
                $cleanAmount = (int) preg_replace('/[^\d]/', '', $request->bpjs_monthly_amount);
                $request->merge(['bpjs_monthly_amount' => $cleanAmount]);
            }

            // Get existing BPJS setting for this employee to handle unique validation properly
            $existingBpjsSetting = BpjsSetting::where('employee_id', $request->employee_id)->first();

            // Dynamic unique validation rule for bpjs_number
            $bpjsNumberRule = [
                'required',
                'string',
                'max:50',
                Rule::unique('bpjs_settings', 'bpjs_number')->ignore($existingBpjsSetting ? $existingBpjsSetting->id : null)
            ];

            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'bpjs_number' => $bpjsNumberRule,
                'bpjs_monthly_amount' => 'required|integer|min:0|max:10000000',
                'is_active' => 'nullable|boolean',
                'notes' => 'nullable|string|max:500'
            ], [
                'employee_id.required' => 'Karyawan/Staff harus dipilih',
                'employee_id.exists' => 'Karyawan/Staff tidak ditemukan',
                'bpjs_number.required' => 'Nomor BPJS harus diisi',
                'bpjs_number.unique' => 'Nomor BPJS sudah terdaftar untuk karyawan lain',
                'bpjs_number.max' => 'Nomor BPJS terlalu panjang (maksimal 50 karakter)',
                'bpjs_monthly_amount.required' => 'Jumlah BPJS bulanan harus diisi',
                'bpjs_monthly_amount.integer' => 'Jumlah BPJS bulanan harus berupa angka',
                'bpjs_monthly_amount.min' => 'Jumlah BPJS bulanan tidak boleh negatif',
                'bpjs_monthly_amount.max' => 'Jumlah BPJS bulanan terlalu besar (maksimal Rp 10.000.000)',
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
                'is_active' => $isActive,
                'action' => $bpjsSetting->wasRecentlyCreated ? 'created' : 'updated',
                'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString()
            ]);

            $actionText = $bpjsSetting->wasRecentlyCreated ? 'ditambahkan' : 'diperbarui';

            return response()->json([
                'success' => true,
                'message' => "Pengaturan BPJS untuk {$employee->nama} berhasil {$actionText}!",
                'redirect' => route('bpjs.settings')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('BPJS validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating BPJS setting', [
                'error' => $e->getMessage(),
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data BPJS: ' . $e->getMessage()
            ], 500);
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

    public function edit($employeeId)
    {
        return $this->getEmployeeBpjs($employeeId);
    }

    public function update(Request $request, $id)
    {
        try {
            $bpjsSetting = BpjsSetting::findOrFail($id);
            $request->merge(['employee_id' => $bpjsSetting->employee_id]);

            return $this->updateBpjs($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data BPJS: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
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

    // Alias method for backward compatibility
    public function delete($id)
    {
        return $this->destroy($id);
    }
}

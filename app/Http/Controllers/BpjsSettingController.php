<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\BpjsSetting;

class BpjsSettingController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('nama')->get();
        $bpjsSettings = BpjsSetting::with('employee')->get()->keyBy('employee_id');

        return view('bpjs.settings', compact('employees', 'bpjsSettings'));
    }

    public function updateBpjs(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bpjs_number' => 'required|string|max:50',
            'bpjs_monthly_amount' => 'required|integer|min:0',
            'bpjs_weekly_amount' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $employee = Employee::findOrFail($request->employee_id);

            BpjsSetting::updateOrCreate(
                ['employee_id' => $request->employee_id],
                [
                    'bpjs_number' => $request->bpjs_number,
                    'bpjs_monthly_amount' => $request->bpjs_monthly_amount,
                    'bpjs_weekly_amount' => $request->bpjs_weekly_amount,
                    'is_active' => $request->boolean('is_active'),
                    'notes' => $request->notes
                ]
            );

            return redirect()->route('bpjs.settings')
                ->with('success', "Pengaturan BPJS untuk {$employee->nama} berhasil diperbarui!");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}

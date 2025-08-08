<?php

namespace App\Http\Controllers;

use App\Models\BpjsPremium;
use App\Models\Employee;
use App\Models\BpjsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BpjsPremiumController extends Controller
{
    public function index(Request $request)
    {
        // Get employees with active BPJS
        $employeesWithBpjs = Employee::whereHas('bpjsSetting', function ($query) {
            $query->where('is_active', true);
        })->with('bpjsSetting')->get();

        // Get premiums with filters
        $premiumsQuery = BpjsPremium::with(['employee', 'employee.bpjsSetting']);

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $premiumsQuery->where('year', $year)->where('month', $month);
        }

        if ($request->filled('employee_name')) {
            $premiumsQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->employee_name . '%');
            });
        }

        $premiums = $premiumsQuery->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('bpjs.premiums', compact('employeesWithBpjs', 'premiums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'premium_amount' => 'required|integer|min:0|max:2000000',
            'notes' => 'nullable|string|max:500'
        ], [
            'employee_id.required' => 'Karyawan/Staff harus dipilih',
            'employee_id.exists' => 'Karyawan/Staff tidak valid',
            'month.required' => 'Bulan harus dipilih',
            'month.regex' => 'Format bulan tidak valid',
            'premium_amount.required' => 'Jumlah premi harus diisi',
            'premium_amount.integer' => 'Jumlah premi harus berupa angka',
            'premium_amount.min' => 'Jumlah premi minimal Rp 0',
            'premium_amount.max' => 'Jumlah premi maksimal Rp 2.000.000',
            'notes.max' => 'Catatan maksimal 500 karakter'
        ]);

        try {
            [$year, $month] = explode('-', $request->month);

            // Check if premium already exists for this employee and month
            $existingPremium = BpjsPremium::where('employee_id', $request->employee_id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existingPremium) {
                return redirect()->back()->with('error', 'Premi BPJS untuk karyawan ini pada bulan tersebut sudah ada.');
            }

            // Verify employee has active BPJS
            $bpjsSetting = BpjsSetting::where('employee_id', $request->employee_id)
                ->where('is_active', true)
                ->first();

            if (!$bpjsSetting) {
                return redirect()->back()->with('error', 'Karyawan/Staff tidak memiliki BPJS aktif.');
            }

            BpjsPremium::create([
                'employee_id' => $request->employee_id,
                'month' => $month,
                'year' => $year,
                'premium_amount' => $request->premium_amount,
                'notes' => $request->notes
            ]);

            return redirect()->back()->with('success', 'Premi BPJS berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $premium = BpjsPremium::with(['employee', 'employee.bpjsSetting'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'premium' => [
                    'id' => $premium->id,
                    'premium_amount' => $premium->premium_amount,
                    'period_name' => $premium->period_name,
                    'notes' => $premium->notes,
                    'cash_payment' => $premium->cash_payment
                ],
                'employee' => [
                    'id' => $premium->employee->id,
                    'nama' => $premium->employee->nama,
                    'nip' => $premium->employee->nip,
                    'departemen' => $premium->employee->departemen
                ],
                'bpjs_setting' => $premium->employee->bpjsSetting ? [
                    'bpjs_number' => $premium->employee->bpjsSetting->bpjs_number,
                    'bpjs_monthly_amount' => $premium->employee->bpjsSetting->bpjs_monthly_amount,
                    'formatted_bpjs_monthly_amount' => $premium->employee->bpjsSetting->formatted_bpjs_monthly_amount
                ] : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $premium = BpjsPremium::findOrFail($id);

        $request->validate([
            'premium_amount' => 'required|integer|min:0|max:2000000',
            'notes' => 'nullable|string|max:500'
        ], [
            'premium_amount.required' => 'Jumlah premi harus diisi',
            'premium_amount.integer' => 'Jumlah premi harus berupa angka',
            'premium_amount.min' => 'Jumlah premi minimal Rp 0',
            'premium_amount.max' => 'Jumlah premi maksimal Rp 2.000.000',
            'notes.max' => 'Catatan maksimal 500 karakter'
        ]);

        try {
            $premium->update([
                'premium_amount' => $request->premium_amount,
                'notes' => $request->notes
            ]);

            return redirect()->back()->with('success', 'Premi BPJS berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $premium = BpjsPremium::findOrFail($id);
            $employeeName = $premium->employee->nama;
            $period = $premium->period_name;

            $premium->delete();

            return redirect()->back()->with('success', "Premi BPJS untuk {$employeeName} periode {$period} berhasil dihapus.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getEmployeeBpjs($employeeId)
    {
        try {
            $employee = Employee::with('bpjsSetting')->findOrFail($employeeId);

            if (!$employee->bpjsSetting || !$employee->bpjsSetting->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan/Staff tidak memiliki BPJS aktif'
                ]);
            }

            return response()->json([
                'success' => true,
                'employee' => [
                    'id' => $employee->id,
                    'nama' => $employee->nama,
                    'nip' => $employee->nip,
                    'departemen' => $employee->departemen
                ],
                'bpjs_setting' => [
                    'bpjs_number' => $employee->bpjsSetting->bpjs_number,
                    'bpjs_monthly_amount' => $employee->bpjsSetting->bpjs_monthly_amount,
                    'formatted_bpjs_monthly_amount' => $employee->bpjsSetting->formatted_bpjs_monthly_amount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}

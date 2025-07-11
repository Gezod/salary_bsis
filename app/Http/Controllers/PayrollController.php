<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use App\Services\PayrollService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderBy('employee_id');

        // Apply filters
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->where('year', $year)->where('month', $month);
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('departemen', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->paginate(20)->withQueryString();

        return view('payroll.index', compact('payrolls'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m'
        ]);

        [$year, $month] = explode('-', $request->month);

        $generated = PayrollService::generatePayrollForAllEmployees($month, $year);

        return redirect()->route('payroll.index')
            ->with('success', "Berhasil generate {$generated} data payroll untuk " .
                   Carbon::create($year, $month, 1)->translatedFormat('F Y'));
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('payroll.show', compact('payroll'));
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'payment_date' => 'required|date',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500'
        ]);

        $payroll = Payroll::findOrFail($id);

        $data = [
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
            'status' => 'paid'
        ];

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if exists
            if ($payroll->payment_proof) {
                Storage::disk('public')->delete($payroll->payment_proof);
            }

            $file = $request->file('payment_proof');
            $filename = 'payment_proof_' . $payroll->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            $data['payment_proof'] = $path;
        }

        $payroll->update($data);

        return redirect()->route('payroll.show', $id)
            ->with('success', 'Data pembayaran berhasil diperbarui!');
    }

    public function recalculate($id)
    {
        $payroll = PayrollService::recalculatePayroll($id);

        return redirect()->route('payroll.show', $id)
            ->with('success', 'Payroll berhasil dihitung ulang!');
    }

    public function settings()
    {
        $employees = Employee::orderBy('nama')->get();
        return view('payroll.settings', compact('employees'));
    }

    public function updateSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'daily_salary' => 'required|integer|min:0'
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $employee->update(['daily_salary' => $request->daily_salary]);

        return redirect()->route('payroll.settings')
            ->with('success', "Gaji harian {$employee->nama} berhasil diperbarui!");
    }

    public function exportPdf(Request $request)
    {
        $query = Payroll::with('employee');

        // Apply same filters as index
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->where('year', $year)->where('month', $month);
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('departemen', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('employee_id')->get();

        $pdf = Pdf::loadView('payroll.pdf', compact('payrolls', 'request'));

        $filename = 'payroll_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($filename);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyPayroll;
use App\Models\Employee;
use App\Models\BpjsSetting;
use App\Models\BpjsPremium;
use App\Services\WeeklyPayrollService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WeeklyPayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = WeeklyPayroll::with(['employee', 'employee.bpjsSetting'])
            ->orderByDesc('start_date')
            ->orderBy('employee_id');

        // Apply date range filter with proper date parsing
        if ($request->filled('date_range')) {
            try {
                // Handle different date formats
                $dateRange = trim($request->date_range);

                if (strpos($dateRange, ' - ') !== false) {
                    [$startDateStr, $endDateStr] = explode(' - ', $dateRange);
                    $startDateStr = trim($startDateStr);
                    $endDateStr = trim($endDateStr);

                    // Parse dates with multiple format support
                    $startDate = $this->parseDate($startDateStr);
                    $endDate = $this->parseDate($endDateStr);

                    if ($startDate && $endDate) {
                        $query->whereBetween('start_date', [
                            $startDate->format('Y-m-d'),
                            $endDate->format('Y-m-d')
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't break the page
                Log::warning('Date parsing error in weekly payroll filter', [
                    'date_range' => $request->date_range,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('departemen', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $weeklyPayrolls = $query->paginate(20)->withQueryString();

        return view('weekly-payroll.index', compact('weeklyPayrolls'));
    }

    /**
     * Parse date string with multiple format support
     */
    private function parseDate($dateStr)
    {
        $formats = [
            'd/m/Y',    // 25/06/2025
            'Y-m-d',    // 2025-06-25
            'd-m-Y',    // 25-06-2025
            'd.m.Y',    // 25.06.2025
            'm/d/Y',    // 06/25/2025 (US format)
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateStr);
                if ($date && $date->format($format) === $dateStr) {
                    return $date;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Fallback: try Carbon's automatic parsing
        try {
            return Carbon::parse($dateStr);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $generated = WeeklyPayrollService::generateWeeklyPayrollForAllEmployees($startDate, $endDate);

        return redirect()->route('weekly-payroll.index')
            ->with('success', "Berhasil generate {$generated} data payroll mingguan untuk periode " .
                $startDate->format('d M') . ' - ' . $endDate->format('d M Y'));
    }

    public function show($id)
    {
        $weeklyPayroll = WeeklyPayroll::with(['employee', 'employee.bpjsSetting'])->findOrFail($id);
        return view('weekly-payroll.show', compact('weeklyPayroll'));
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bca,mandiri,bri,bni,cimb,danamon,permata,btn,bjb,mega,maybank,ocbc,panin,uob,hsbc,citibank,standard_chartered,commonwealth,dbs,bank_jatim,bank_jateng,bank_dki,bank_kalbar,bank_kalsel,bank_kaltim,bank_lampung,bank_riau,bank_sumsel,bank_sumut,bank_sulsel,bank_sulut,bank_papua,bank_maluku,bank_ntb,bank_ntt,bank_bengkulu,bank_jambi,bank_aceh',
            'payment_date' => 'required|date',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500'
        ]);

        $weeklyPayroll = WeeklyPayroll::findOrFail($id);

        $data = [
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
            'status' => 'paid'
        ];

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if exists
            if ($weeklyPayroll->payment_proof) {
                Storage::disk('public')->delete($weeklyPayroll->payment_proof);
            }

            $file = $request->file('payment_proof');
            $filename = 'weekly_payment_proof_' . $weeklyPayroll->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            $data['payment_proof'] = $path;
        }

        $weeklyPayroll->update($data);

        return redirect()->route('weekly-payroll.show', $id)
            ->with('success', 'Data pembayaran berhasil diperbarui!');
    }

    public function recalculate($id)
    {
        $weeklyPayroll = WeeklyPayrollService::recalculateWeeklyPayroll($id);

        return redirect()->route('weekly-payroll.show', $id)
            ->with('success', 'Payroll mingguan berhasil dihitung ulang!');
    }

    public function exportPdf(Request $request)
    {
        $query = WeeklyPayroll::with(['employee', 'employee.bpjsSetting']);

        if ($request->filled('date_range')) {
            try {
                $dateRange = trim($request->date_range);

                if (strpos($dateRange, ' - ') !== false) {
                    [$startDateStr, $endDateStr] = explode(' - ', $dateRange);
                    $startDateStr = trim($startDateStr);
                    $endDateStr = trim($endDateStr);

                    $startDate = $this->parseDate($startDateStr);
                    $endDate = $this->parseDate($endDateStr);

                    if ($startDate && $endDate) {
                        $query->whereBetween('start_date', [
                            $startDate->format('Y-m-d'),
                            $endDate->format('Y-m-d')
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Date parsing error in weekly payroll PDF export', [
                    'date_range' => $request->date_range,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('departemen', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $weeklyPayrolls = $query->orderBy('employee_id')->get();

        $pdf = Pdf::loadView('weekly-payroll.pdf', compact('weeklyPayrolls', 'request'));

        $filename = 'weekly_payroll_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadIndividualPdf($id)
    {
        $weeklyPayroll = WeeklyPayroll::with(['employee', 'employee.bpjsSetting'])->findOrFail($id);

        if ($weeklyPayroll->status !== 'paid') {
            return redirect()->back()->with('error', 'Slip gaji mingguan hanya bisa didownload setelah pembayaran selesai.');
        }

        // Check if employee has BPJS and add BPJS allowance info to weekly payroll
        if ($weeklyPayroll->employee->bpjsSetting && $weeklyPayroll->employee->bpjsSetting->is_active) {
            // Get BPJS premium for current month (if exists)
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $bpjsPremium = BpjsPremium::where('employee_id', $weeklyPayroll->employee->id)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->first();

            $weeklyPayroll->bpjs_premium = $bpjsPremium;
        }

        $pdf = Pdf::loadView('weekly-payroll.individual', compact('weeklyPayroll'))
            ->setPaper('a4', 'portrait');

        $filename = 'slip_gaji_mingguan_' . str_replace(' ', '_', strtolower($weeklyPayroll->employee->nama)) . '_' . $weeklyPayroll->start_date->format('d_m_Y') . '.pdf';

        return $pdf->download($filename);
    }
}

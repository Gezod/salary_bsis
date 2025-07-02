<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use App\Services\AttendanceService;

class AttendanceController extends Controller
{
    public function index(Request $r)
    {
        $query = Attendance::with('employee')
            ->orderByDesc('tanggal')
            ->orderBy(Employee::select('nama')
                ->whereColumn('employees.id', 'attendances.employee_id'))
            ->when($r->filled('date'), function ($q) use ($r) {
                $q->whereDate('tanggal', Carbon::parse($r->input('date')));
            })
            ->when($r->filled('employee'), function ($q) use ($r) {
                $q->whereHas('employee', fn($e) =>
                $e->where('nama', 'like', '%' . $r->employee . '%'));
            });

        $rows = $query->paginate(50)->withQueryString();

        // Tambahkan evaluasi otomatis
        foreach ($rows as $a) {
            // Jalankan ulang evaluasi jika belum ada total_fine
            if (is_null($a->total_fine) || $a->total_fine === 0) {
                AttendanceService::evaluate($a);
                $a->refresh(); // Pastikan nilainya diperbarui
            }
        }

        return view('absensi.index', compact('rows'));
    }

    public function recap(Request $r)
    {
        $month = $r->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        $data = Attendance::with('employee')
            ->selectRaw('employee_id, SUM(total_fine) as fine')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->groupBy('employee_id')
            ->orderByDesc('fine')
            ->get();

        return view('absensi.recap', compact('data', 'month'));
    }
    public function reevaluateAll()
    {
        $attendances = \App\Models\Attendance::with('employee')->get();

        foreach ($attendances as $attendance) {
            if ($attendance instanceof \App\Models\Attendance && $attendance->employee) {
                \App\Services\AttendanceService::evaluate($attendance);
            }
        }

        return redirect()->back()->with('success', 'Semua absensi berhasil dievaluasi ulang.');
    }
     public function manual()
    {
        return view('absensi.manual');
    }

    public function manualStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'scan1' => 'nullable|date_format:H:i',
            'scan2' => 'nullable|date_format:H:i',
            'scan3' => 'nullable|date_format:H:i',
            'scan4' => 'nullable|date_format:H:i',
            'scan5' => 'nullable|date_format:H:i',
            'late_minutes' => 'nullable|integer|min:0',
            'early_leave_minutes' => 'nullable|integer|min:0',
            'overtime_minutes' => 'nullable|integer|min:0',
        ]);

        // Check if attendance already exists for this employee and date
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['tanggal' => 'Data absensi untuk karyawan ini pada tanggal tersebut sudah ada.']);
        }

        $tanggal = Carbon::parse($request->tanggal);

        // Prepare scan data
        $scanData = [];
        foreach (['scan1', 'scan2', 'scan3', 'scan4', 'scan5'] as $scan) {
            if ($request->filled($scan)) {
                $scanData[$scan] = $tanggal->copy()->setTimeFromTimeString($request->input($scan));
            }
        }

        // Create attendance record
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'tanggal' => $tanggal,
            'late_minutes' => $request->input('late_minutes', 0),
            'early_leave_minutes' => $request->input('early_leave_minutes', 0),
            'overtime_minutes' => $request->input('overtime_minutes', 0),
            'excess_break_minutes' => 0,
            'invalid_break' => false,
            'late_fine' => 0,
            'break_fine' => 0,
            'absence_fine' => 0,
            'total_fine' => 0,
            ...$scanData
        ]);

        // Evaluate attendance using AttendanceService
        if (class_exists('App\Services\AttendanceService')) {
            AttendanceService::evaluate($attendance);
        }

        return redirect()->route('absensi.manual')
            ->with('success', 'Data absensi berhasil ditambahkan dan dievaluasi.');
    }
}

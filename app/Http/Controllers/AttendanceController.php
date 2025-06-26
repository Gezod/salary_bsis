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
}

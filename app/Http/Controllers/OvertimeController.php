<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRecord;
use App\Models\OvertimeSetting;
use App\Models\Employee;
use App\Services\OvertimeService;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $query = OvertimeRecord::with('employee')
            ->orderByDesc('tanggal')
            ->orderBy('id');

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('tanggal', Carbon::parse($request->date));
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

        $records = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = OvertimeService::getOvertimeStats();

        return view('overtime.index', compact('records', 'stats'));
    }

    public function overview()
    {
        $stats = OvertimeService::getOvertimeStats();
        $recentRecords = OvertimeRecord::with('employee')
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        return view('overtime.overview', compact('stats', 'recentRecords'));
    }

    public function settings()
    {
        $settings = OvertimeSetting::current();
        return view('overtime.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'staff_rate' => 'required|integer|min:0',
            'karyawan_rate' => 'required|integer|min:0',
            'minimum_minutes' => 'required|integer|min:0'
        ]);

        $settings = OvertimeSetting::current();
        $settings->update($request->only(['staff_rate', 'karyawan_rate', 'minimum_minutes']));

        return redirect()->route('overtime.settings')
            ->with('success', 'Pengaturan lembur berhasil diperbarui!');
    }

    public function recap(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $records = OvertimeRecord::with('employee')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->get();

        $departmentStats = [
            'staff' => [
                'count' => $records->where('employee.departemen', 'staff')->count(),
                'total_pay' => $records->where('employee.departemen', 'staff')->sum('overtime_pay'),
                'total_minutes' => $records->where('employee.departemen', 'staff')->sum('overtime_minutes')
            ],
            'karyawan' => [
                'count' => $records->where('employee.departemen', 'karyawan')->count(),
                'total_pay' => $records->where('employee.departemen', 'karyawan')->sum('overtime_pay'),
                'total_minutes' => $records->where('employee.departemen', 'karyawan')->sum('overtime_minutes')
            ]
        ];

        $topEmployees = $records->groupBy('employee_id')
            ->map(function($group) {
                return [
                    'employee' => $group->first()->employee,
                    'total_minutes' => $group->sum('overtime_minutes'),
                    'total_pay' => $group->sum('overtime_pay')
                ];
            })
            ->sortByDesc('total_minutes')
            ->take(5);

        return view('overtime.recap', compact('records', 'departmentStats', 'topEmployees', 'month'));
    }

    public function recalculateAll()
    {
        $processed = OvertimeService::recalculateAll();

        return redirect()->back()
            ->with('success', "Berhasil menghitung ulang {$processed} data lembur!");
    }
}

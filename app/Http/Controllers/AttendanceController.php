<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

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

    public function import()
    {
        return view('absensi.import');
    }

    public function importStore(Request $request)
    {
        // Implementation for import functionality
        return redirect()->route('absensi.import')
            ->with('success', 'Data berhasil diimport.');
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
     public function denda()
    {
        $penalties = config('penalties');
        return view('absensi.denda', compact('penalties'));
    }

    public function dendaUpdate(Request $request)
    {
        $request->validate([
            'staff.late.*.2' => 'required|integer|min:0',
            'staff.late_break' => 'required|integer|min:0',
            'staff.missing_checkin' => 'required|integer|min:0',
            'staff.missing_checkout' => 'required|integer|min:0',
            'staff.absent_break_once' => 'required|integer|min:0',
            'staff.absent_twice' => 'required|integer|min:0',
            'staff.late_base' => 'required|integer|min:0',
            'karyawan.late.*.2' => 'required|integer|min:0',
            'karyawan.late_break' => 'required|integer|min:0',
            'karyawan.missing_checkin' => 'required|integer|min:0',
            'karyawan.missing_checkout' => 'required|integer|min:0',
            'karyawan.absent_break_once' => 'required|integer|min:0',
            'karyawan.absent_twice' => 'required|integer|min:0',
            'karyawan.late_base' => 'required|integer|min:0',
        ]);

        try {
            // Build new penalties configuration
            $newPenalties = [
                'staff' => [
                    'late' => [
                        [1, 15, (int)$request->input('staff.late.0.2')],
                        [16, 30, (int)$request->input('staff.late.1.2')],
                        [31, 45, (int)$request->input('staff.late.2.2')],
                        [46, 60, (int)$request->input('staff.late.3.2')],
                        ['>', 60, function($m) use ($request) {
                            return (int)$request->input('staff.late_base') + ($m - 60);
                        }]
                    ],
                    'late_break' => (int)$request->input('staff.late_break'),
                    'missing_checkin' => (int)$request->input('staff.missing_checkin'),
                    'missing_checkout' => (int)$request->input('staff.missing_checkout'),
                    'absent_break_once' => (int)$request->input('staff.absent_break_once'),
                    'absent_twice' => (int)$request->input('staff.absent_twice'),
                ],
                'karyawan' => [
                    'late' => [
                        [1, 15, (int)$request->input('karyawan.late.0.2')],
                        [16, 30, (int)$request->input('karyawan.late.1.2')],
                        [31, 45, (int)$request->input('karyawan.late.2.2')],
                        [46, 60, (int)$request->input('karyawan.late.3.2')],
                        ['>', 60, function($m) use ($request) {
                            return (int)$request->input('karyawan.late_base') + ($m - 60);
                        }]
                    ],
                    'late_break' => (int)$request->input('karyawan.late_break'),
                    'missing_checkin' => (int)$request->input('karyawan.missing_checkin'),
                    'missing_checkout' => (int)$request->input('karyawan.missing_checkout'),
                    'absent_break_once' => (int)$request->input('karyawan.absent_break_once'),
                    'absent_twice' => (int)$request->input('karyawan.absent_twice'),
                ]
            ];

            // Generate PHP config file content
            $configContent = "<?php\n\nreturn " . $this->arrayToPhpString($newPenalties) . ";\n";

            // Write to config file
            $configPath = config_path('penalties.php');
            File::put($configPath, $configContent);

            // Clear config cache
            if (function_exists('config_clear')) {
               Artisan::call('config:clear');
            }

            return redirect()->route('absensi.denda')
                ->with('success', 'Pengaturan denda berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('absensi.denda')
                ->with('error', 'Gagal memperbarui pengaturan denda: ' . $e->getMessage());
        }
    }

    // Employee Management Methods
    public function role(Request $request)
    {
        $query = Employee::query();

        // Search functionality - menggunakan logic yang sama dengan API
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('pin', 'like', "%{$search}%");
            });
        }

        // Filter by departemen
        if ($request->filled('departemen')) {
            $query->where('departemen', $request->departemen);
        }

        // Filter by jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', 'like', "%{$request->jabatan}%");
        }

        $employees = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('absensi.role', compact('employees'));
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:20|unique:employees,pin',
            'nip' => 'required|string|max:50|unique:employees,nip',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|in:staff,karyawan',
            'kantor' => 'required|string|max:100',
        ]);

        Employee::create([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
        ]);

        return redirect()->route('absensi.role')
            ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function roleEdit($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'success' => true,
            'employee' => $employee
        ]);
    }

    public function roleUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'pin' => 'required|string|max:20|unique:employees,pin,' . $id,
            'nip' => 'required|string|max:50|unique:employees,nip,' . $id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|in:staff,karyawan',
            'kantor' => 'required|string|max:100',
        ]);

        $employee->update([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
        ]);

        return redirect()->route('absensi.role')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function roleDestroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete related attendance records first
        Attendance::where('employee_id', $id)->delete();

        // Then delete the employee
        $employee->delete();

        return redirect()->route('absensi.role')
            ->with('success', 'Karyawan dan semua data absensi terkait berhasil dihapus!');
    }

    private function arrayToPhpString($array, $indent = 0)
    {
        $spaces = str_repeat('    ', $indent);
        $result = "[\n";

        foreach ($array as $key => $value) {
            $result .= $spaces . '    ';

            if (is_string($key)) {
                $result .= "'{$key}' => ";
            }

            if (is_array($value)) {
                if (isset($value[0]) && $value[0] === '>') {
                    // Special handling for closure
                    $baseValue = $indent === 2 ?
                        ($key === 'staff' ? 12000 : 10000) :
                        (strpos(json_encode($array), '"staff"') !== false ? 12000 : 10000);
                    $result .= "['>', 60, fn(\$m) => {$baseValue} + (\$m - 60)]";
                } else {
                    $result .= $this->arrayToPhpString($value, $indent + 1);
                }
            } elseif (is_string($value)) {
                $result .= "'{$value}'";
            } else {
                $result .= $value;
            }

            $result .= ",\n";
        }

        $result .= $spaces . ']';
        return $result;
    }
}

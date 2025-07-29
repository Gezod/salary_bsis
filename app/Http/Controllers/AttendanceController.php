<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\WorkTimeChange;
use Carbon\Carbon;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $r)
    {
        $query = Attendance::query()
            ->join('employees', 'employees.id', '=', 'attendances.employee_id')
            ->with('employee')
            ->orderBy('attendances.tanggal')
            ->orderBy('employees.nama')
            ->select('attendances.*')
            ->when($r->filled('date'), function ($q) use ($r) {
                $q->whereDate('attendances.tanggal', Carbon::parse($r->input('date')));
            })
            ->when($r->filled('employee'), function ($q) use ($r) {
                $q->where('employees.nama', 'like', '%' . $r->employee . '%');
            });

        $rows = $query->paginate(50)->withQueryString();

        // Evaluasi otomatis jika belum ada total_fine
        foreach ($rows as $a) {
            if (is_null($a->total_fine) || $a->total_fine === 0) {
                AttendanceService::evaluate($a);
                $a->refresh();
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

    public function lateRecap(Request $r)
    {
        $month = $r->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        // Get late attendance data
        $lateData = Attendance::with('employee')
            ->selectRaw('employee_id, COUNT(*) as late_count, SUM(late_minutes) as total_late_minutes')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->where('late_minutes', '>', 0)
            ->groupBy('employee_id')
            ->orderByDesc('total_late_minutes')
            ->get();

        // Get department statistics
        $departmentStats = [
            'staff' => [
                'count' => 0,
                'total_minutes' => 0,
                'total_occurrences' => 0
            ],
            'karyawan' => [
                'count' => 0,
                'total_minutes' => 0,
                'total_occurrences' => 0
            ]
        ];

        foreach ($lateData as $data) {
            $dept = $data->employee->departemen;
            if (isset($departmentStats[$dept])) {
                $departmentStats[$dept]['count']++;
                $departmentStats[$dept]['total_minutes'] += $data->total_late_minutes;
                $departmentStats[$dept]['total_occurrences'] += $data->late_count;
            }
        }

        return view('absensi.late-recap', compact('lateData', 'departmentStats', 'month'));
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
            'is_half_day' => false,
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

    // Half Day Manual Entry
    public function halfDayManual()
    {
        return view('absensi.half-day-manual');
    }

    public function halfDayManualStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'half_day_type' => 'required|in:shift_1,shift_2',
            'scan1' => 'nullable|date_format:H:i',
            'scan2' => 'nullable|date_format:H:i',
            'scan3' => 'nullable|date_format:H:i',
            'scan4' => 'nullable|date_format:H:i',
            'half_day_notes' => 'nullable|string|max:500',
        ]);

        // Check if attendance already exists for this employee and date
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['tanggal' => 'Data absensi untuk karyawan ini pada tanggal tersebut sudah ada.']);
        }

        $tanggal = Carbon::parse($request->tanggal);

        // Prepare scan data berdasarkan jenis shift
        $scanData = [];

        if ($request->half_day_type === 'shift_1') {
            // Shift 1: menggunakan scan1 dan scan2
            if ($request->filled('scan1')) {
                $scanData['scan1'] = $tanggal->copy()->setTimeFromTimeString($request->input('scan1'));
            }
            if ($request->filled('scan2')) {
                $scanData['scan2'] = $tanggal->copy()->setTimeFromTimeString($request->input('scan2'));
            }
        } else {
            // Shift 2: menggunakan scan3 dan scan4
            if ($request->filled('scan3')) {
                $scanData['scan3'] = $tanggal->copy()->setTimeFromTimeString($request->input('scan3'));
            }
            if ($request->filled('scan4')) {
                $scanData['scan4'] = $tanggal->copy()->setTimeFromTimeString($request->input('scan4'));
            }
        }

        // Create attendance record
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'tanggal' => $tanggal,
            'is_half_day' => true,
            'half_day_type' => $request->half_day_type,
            'half_day_notes' => $request->half_day_notes,
            'late_minutes' => 0,
            'early_leave_minutes' => 0,
            'overtime_minutes' => 0,
            'overtime_status' => 'pending',
            'excess_break_minutes' => 0,
            'invalid_break' => false,
            'late_fine' => 0,
            'break_fine' => 0,
            'absence_fine' => 0,
            'total_fine' => 0,
            ...$scanData
        ]);

        return redirect()->route('absensi.half-day-manual')
            ->with('success', 'Data absensi setengah hari berhasil ditambahkan.');
    }

    // CRUD Methods for Attendance
    public function show($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return response()->json([
            'success' => true,
            'html' => view('absensi.partials.details', compact('attendance'))->render()
        ]);
    }

    public function edit($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return response()->json([
            'success' => true,
            'html' => view('absensi.partials.edit', compact('attendance'))->render()
        ]);
    }

    public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    $request->validate([
        'scan1' => 'nullable|date_format:H:i',
        'scan2' => 'nullable|date_format:H:i',
        'scan3' => 'nullable|date_format:H:i',
        'scan4' => 'nullable|date_format:H:i',
        'scan5' => 'nullable|date_format:H:i',
        'is_half_day' => 'boolean',
        'half_day_type' => 'nullable|required_if:is_half_day,true|in:shift_1,shift_2',
        'half_day_notes' => 'nullable|string|max:500',
        'overtime_status' => 'nullable|in:pending,approved,rejected',
        'overtime_notes' => 'nullable|string|max:500',
    ]);

    $tanggal = $attendance->tanggal;

    // Update half day info first
    $attendance->is_half_day = $request->boolean('is_half_day');
    $attendance->half_day_type = $attendance->is_half_day ? $request->half_day_type : null;
    $attendance->half_day_notes = $request->half_day_notes;
    $attendance->overtime_status = $request->overtime_status ?? 'pending';
    $attendance->overtime_notes = $request->overtime_notes;

    // Update scan times based on half day type
    foreach (['scan1', 'scan2', 'scan3', 'scan4', 'scan5'] as $scan) {
        if ($request->filled($scan)) {
            // Only allow relevant scans for half day
            if ($attendance->is_half_day) {
                if ($attendance->half_day_type === 'shift_1' && in_array($scan, ['scan3', 'scan4', 'scan5'])) {
                    continue; // Skip irrelevant scans for shift 1
                }
                if ($attendance->half_day_type === 'shift_2' && in_array($scan, ['scan1', 'scan2', 'scan5'])) {
                    continue; // Skip irrelevant scans for shift 2
                }
            }
            $attendance->$scan = $tanggal->copy()->setTimeFromTimeString($request->input($scan));
        } elseif ($request->has($scan)) {
            // Clear irrelevant scans when half day is selected
            if (!$attendance->is_half_day ||
                ($attendance->half_day_type === 'shift_1' && !in_array($scan, ['scan3', 'scan4', 'scan5'])) ||
                ($attendance->half_day_type === 'shift_2' && !in_array($scan, ['scan1', 'scan2', 'scan5']))) {
                $attendance->$scan = null;
            }
        }
    }

    Log::debug('Attendance update data', [
        'is_half_day' => $attendance->is_half_day,
        'half_day_type' => $attendance->half_day_type,
        'scans' => [
            'scan1' => $attendance->scan1?->format('H:i'),
            'scan2' => $attendance->scan2?->format('H:i'),
            'scan3' => $attendance->scan3?->format('H:i'),
            'scan4' => $attendance->scan4?->format('H:i'),
            'scan5' => $attendance->scan5?->format('H:i'),
        ],
        'request_data' => $request->all()
    ]);

    $attendance->save();

    // Re-evaluate attendance
    AttendanceService::evaluate($attendance);

    return response()->json(['success' => true]);
}

    public function updateOvertimeStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $attendance = Attendance::findOrFail($id);

            $attendance->update([
                'overtime_status' => $validated['status'],
                'overtime_notes' => $validated['notes']
            ]);

            // Log untuk debugging
            Log::info('Overtime status updated', [
                'id' => $id,
                'status' => $validated['status'],
                'notes' => $validated['notes']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status lembur berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating overtime status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRowData($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);

        return response()->json([
            'success' => true,
            'overtime_html' => view('absensi.partials.overtime_cell', compact('attendance'))->render()
        ]);
    }

    // Leave Management
    public function leaveIndex(Request $request)
    {
        $query = Leave::with('employee')->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->paginate(20)->withQueryString();

        // Count statistics
        $stats = [
            'total' => Leave::count(),
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
        ];

        return view('absensi.leave.index', compact('leaves', 'stats'));
    }

    public function leaveCreate()
    {
        $employees = Employee::orderBy('nama')->get();
        return view('absensi.leave.create', compact('employees'));
    }

    public function leaveStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'alasan_izin' => 'required|string',
            'bukti_foto' => 'nullable|image|max:2048',
            'tanggal_izin' => 'required|date'
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $bukti_foto = null;
        if ($request->hasFile('bukti_foto')) {
            $bukti_foto = $request->file('bukti_foto')->store('leave_photos', 'public');
        }

        Leave::create([
            'employee_id' => $request->employee_id,
            'nama' => $employee->nama,
            'departemen' => $employee->departemen,
            'jabatan' => $employee->jabatan,
            'alasan_izin' => $request->alasan_izin,
            'bukti_foto' => $bukti_foto,
            'tanggal_izin' => $request->tanggal_izin,
            'status' => 'pending' // Changed from 'approved' to 'pending'
        ]);

        return redirect()->route('absensi.leave.index')
            ->with('success', 'Data izin berhasil ditambahkan dan menunggu persetujuan.');
    }

    public function leaveUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'approval_notes' => 'nullable|string|max:500'
        ]);

        try {
            $leave = Leave::findOrFail($id);

            $leave->update([
                'status' => $request->status,
                'approval_notes' => $request->approval_notes,
                'approved_at' => $request->status === 'approved' ? now() : null,
                'approved_by' => auth()->user()->name ?? 'Admin'
            ]);

            $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';

            return response()->json([
                'success' => true,
                'message' => "Izin berhasil {$statusText}.",
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating leave status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status izin.'
            ], 500);
        }
    }

    public function leaveShow($id)
    {
        $leave = Leave::with('employee')->findOrFail($id);
        return response()->json([
            'success' => true,
            'leave' => $leave
        ]);
    }

    // Work Time Change Management
    public function workTimeChangeIndex()
    {
        $workTimeChanges = WorkTimeChange::with(['employee', 'leave'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('absensi.work_time_change.index', compact('workTimeChanges'));
    }

    public function workTimeChangeCreate()
    {
        $leaves = Leave::with('employee')->where('status', 'approved')->get();
        return view('absensi.work_time_change.create', compact('leaves'));
    }

    public function workTimeChangeStore(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|exists:leaves,id',
            'tanggal' => 'required|date',
            'jam_masuk_baru' => 'nullable|date_format:H:i',
            'jam_pulang_baru' => 'nullable|date_format:H:i',
            'jam_istirahat_mulai' => 'nullable|date_format:H:i',
            'jam_istirahat_selesai' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string'
        ]);

        $leave = Leave::findOrFail($request->leave_id);

        WorkTimeChange::create([
            'leave_id' => $request->leave_id,
            'employee_id' => $leave->employee_id,
            'tanggal' => $request->tanggal,
            'jam_masuk_baru' => $request->jam_masuk_baru,
            'jam_pulang_baru' => $request->jam_pulang_baru,
            'jam_istirahat_mulai' => $request->jam_istirahat_mulai,
            'jam_istirahat_selesai' => $request->jam_istirahat_selesai,
            'keterangan' => $request->keterangan,
            'status' => 'approved'
        ]);

        return redirect()->route('absensi.work_time_change.index')
            ->with('success', 'Pergantian jam kerja berhasil ditambahkan.');
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
                        ['>', 60, function ($m) use ($request) {
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
                        ['>', 60, function ($m) use ($request) {
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

    // Individual Penalty View
    public function dendaIndividual(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        // Get penalty data per employee
        $penaltyData = Attendance::with('employee')
            ->selectRaw('
                employee_id,
                COUNT(*) as total_days,
                SUM(late_minutes) as total_late_minutes,
                COUNT(CASE WHEN late_minutes > 0 THEN 1 END) as late_days,
                SUM(late_fine) as total_late_fine,
                SUM(break_fine) as total_break_fine,
                SUM(absence_fine) as total_absence_fine,
                SUM(total_fine) as total_penalty,
                AVG(late_minutes) as avg_late_minutes
            ')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->groupBy('employee_id')
            ->having('total_penalty', '>', 0)
            ->orderByDesc('total_penalty')
            ->get();

        // Get department statistics
        $departmentStats = [
            'staff' => [
                'total_employees' => 0,
                'total_penalty' => 0,
                'avg_penalty' => 0,
                'total_late_days' => 0
            ],
            'karyawan' => [
                'total_employees' => 0,
                'total_penalty' => 0,
                'avg_penalty' => 0,
                'total_late_days' => 0
            ]
        ];

        foreach ($penaltyData as $data) {
            $dept = $data->employee->departemen;
            if (isset($departmentStats[$dept])) {
                $departmentStats[$dept]['total_employees']++;
                $departmentStats[$dept]['total_penalty'] += $data->total_penalty;
                $departmentStats[$dept]['total_late_days'] += $data->late_days;
            }
        }

        // Calculate averages
        foreach ($departmentStats as $dept => $stats) {
            if ($stats['total_employees'] > 0) {
                $departmentStats[$dept]['avg_penalty'] = $stats['total_penalty'] / $stats['total_employees'];
            }
        }

        return view('absensi.denda-individual', compact('penaltyData', 'departmentStats', 'month'));
    }

    // Individual Employee Penalty Detail
    public function dendaEmployeeDetail(Request $request, $employeeId)
    {
        $month = $request->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        $employee = Employee::findOrFail($employeeId);

        // Get detailed attendance data for the employee
        $attendanceData = Attendance::where('employee_id', $employeeId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->orderBy('tanggal')
            ->get();

        // Calculate summary
        $summary = [
            'total_days' => $attendanceData->count(),
            'late_days' => $attendanceData->where('late_minutes', '>', 0)->count(),
            'total_late_minutes' => $attendanceData->sum('late_minutes'),
            'total_late_fine' => $attendanceData->sum('late_fine'),
            'total_break_fine' => $attendanceData->sum('break_fine'),
            'total_absence_fine' => $attendanceData->sum('absence_fine'),
            'total_penalty' => $attendanceData->sum('total_fine'),
            'avg_late_minutes' => $attendanceData->where('late_minutes', '>', 0)->avg('late_minutes') ?? 0
        ];

        return view('absensi.denda-employee-detail', compact('employee', 'attendanceData', 'summary', 'month'));
    }

    // Export Individual PDF
    public function dendaExportIndividualPdf(Request $request, $employeeId)
    {
        $month = $request->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        $employee = Employee::findOrFail($employeeId);

        // Get detailed attendance data for the employee
        $attendanceData = Attendance::where('employee_id', $employeeId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->orderBy('tanggal')
            ->get();

        // Calculate summary
        $summary = [
            'total_days' => $attendanceData->count(),
            'late_days' => $attendanceData->where('late_minutes', '>', 0)->count(),
            'total_late_minutes' => $attendanceData->sum('late_minutes'),
            'total_late_fine' => $attendanceData->sum('late_fine'),
            'total_break_fine' => $attendanceData->sum('break_fine'),
            'total_absence_fine' => $attendanceData->sum('absence_fine'),
            'total_penalty' => $attendanceData->sum('total_fine'),
            'avg_late_minutes' => $attendanceData->where('late_minutes', '>', 0)->avg('late_minutes') ?? 0
        ];

        $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('absensi.pdf.denda-individual', compact('employee', 'attendanceData', 'summary', 'month', 'monthName'));

        return $pdf->download("Laporan_Denda_{$employee->nama}_{$month}.pdf");
    }

    // Export All Penalties PDF
    public function dendaExportAllPdf(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            abort(400, 'Format bulan tidak valid. Gunakan format YYYY-MM.');
        }

        [$year, $mon] = explode('-', $month);

        // Get penalty data per employee
        $penaltyData = Attendance::with('employee')
            ->selectRaw('
                employee_id,
                COUNT(*) as total_days,
                SUM(late_minutes) as total_late_minutes,
                COUNT(CASE WHEN late_minutes > 0 THEN 1 END) as late_days,
                SUM(late_fine) as total_late_fine,
                SUM(break_fine) as total_break_fine,
                SUM(absence_fine) as total_absence_fine,
                SUM(total_fine) as total_penalty,
                AVG(late_minutes) as avg_late_minutes
            ')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $mon)
            ->groupBy('employee_id')
            ->having('total_penalty', '>', 0)
            ->orderByDesc('total_penalty')
            ->get();

        // Get department statistics
        $departmentStats = [
            'staff' => [
                'total_employees' => 0,
                'total_penalty' => 0,
                'avg_penalty' => 0,
                'total_late_days' => 0
            ],
            'karyawan' => [
                'total_employees' => 0,
                'total_penalty' => 0,
                'avg_penalty' => 0,
                'total_late_days' => 0
            ]
        ];

        foreach ($penaltyData as $data) {
            $dept = $data->employee->departemen;
            if (isset($departmentStats[$dept])) {
                $departmentStats[$dept]['total_employees']++;
                $departmentStats[$dept]['total_penalty'] += $data->total_penalty;
                $departmentStats[$dept]['total_late_days'] += $data->late_days;
            }
        }

        // Calculate averages
        foreach ($departmentStats as $dept => $stats) {
            if ($stats['total_employees'] > 0) {
                $departmentStats[$dept]['avg_penalty'] = $stats['total_penalty'] / $stats['total_employees'];
            }
        }

        $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('absensi.pdf.denda-all', compact('penaltyData', 'departmentStats', 'month', 'monthName'));

        return $pdf->download("Laporan_Denda_Semua_Karyawan_{$month}.pdf");
    }

    // Employee Management Methods
    public function role(Request $request)
    {
        $query = Employee::query();

        // Search functionality - menggunakan logic yang sama dengan API
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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

        // Check for contract expiring soon notifications
        $expiringContracts = Employee::whereNotNull('tanggal_end_kontrak')
            ->whereRaw('tanggal_end_kontrak <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
            ->whereRaw('tanggal_end_kontrak >= CURDATE()')
            ->count();

        return view('absensi.role', compact('employees', 'expiringContracts'));
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
            'tanggal_start_kontrak' => 'nullable|date',
            'tanggal_end_kontrak' => 'nullable|date|after:tanggal_start_kontrak',
        ]);

        Employee::create([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
            'tanggal_start_kontrak' => $request->tanggal_start_kontrak,
            'tanggal_end_kontrak' => $request->tanggal_end_kontrak,
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
            'tanggal_start_kontrak' => 'nullable|date',
            'tanggal_end_kontrak' => 'nullable|date|after:tanggal_start_kontrak',
        ]);

        $employee->update([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
            'tanggal_start_kontrak' => $request->tanggal_start_kontrak,
            'tanggal_end_kontrak' => $request->tanggal_end_kontrak,
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
        $spaces = str_repeat(' ', $indent);
        $result = "[\n";

        foreach ($array as $key => $value) {
            $result .= $spaces . ' ';
            if (is_string($key)) {
                $result .= "'{$key}' => ";
            }

            if (is_array($value)) {
                if (isset($value[0]) && $value[0] === '>') {
                    // Special handling for closure
                    $baseValue = $indent === 2 ? ($key === 'staff' ? 12000 : 10000) : (strpos(json_encode($array), '"staff"') !== false ? 12000 : 10000);
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

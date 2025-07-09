<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\OvertimeRecord;
use App\Models\OvertimeSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OvertimeService
{
    public static function calculateOvertime(Attendance $attendance): void
    {
        // Skip if no scan4 (checkout time)
        if (!$attendance->scan4) {
            return;
        }

        $settings = OvertimeSetting::current();
        $employee = $attendance->employee;

        // Determine expected checkout time based on day
        $dayOfWeek = $attendance->tanggal->format('l'); // Monday, Tuesday, etc.
        $isJumat = $dayOfWeek === 'Friday';
        $expectedOutTime = $isJumat ? '16:00' : '16:30';

        // Create expected checkout datetime
        $expectedOut = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $expectedOutTime);
        $actualOut = $attendance->scan4;

        // Calculate overtime minutes (only if checkout is AFTER expected time)
        $overtimeMinutes = 0;
        if ($actualOut->gt($expectedOut)) {
            $overtimeMinutes = $actualOut->diffInMinutes($expectedOut);
        }

        // Calculate overtime pay only if meets minimum threshold
        $overtimePay = 0;
        if ($overtimeMinutes >= $settings->minimum_minutes) {
            $rate = $employee->departemen === 'staff' ? $settings->staff_rate : $settings->karyawan_rate;
            $hours = $overtimeMinutes / 60; // Convert minutes to hours
            $overtimePay = round($rate * $hours);
        }

        // Update or create overtime record
        OvertimeRecord::updateOrCreate(
            [
                'employee_id' => $attendance->employee_id,
                'tanggal' => $attendance->tanggal
            ],
            [
                'overtime_minutes' => $overtimeMinutes,
                'overtime_pay' => $overtimePay,
                'scan4' => $actualOut,
                'expected_out' => $expectedOut,
                'status' => 'approved'
            ]
        );

        // Update attendance record with overtime minutes
        $attendance->update([
            'overtime_minutes' => $overtimeMinutes
        ]);

        Log::info("Overtime calculated for {$employee->nama}: {$overtimeMinutes} minutes, Rp {$overtimePay}", [
            'employee_id' => $employee->id,
            'date' => $attendance->tanggal->format('Y-m-d'),
            'expected_out' => $expectedOut->format('H:i'),
            'actual_out' => $actualOut->format('H:i'),
            'day_of_week' => $dayOfWeek,
            'is_friday' => $isJumat
        ]);
    }

    public static function getOvertimeStats($startDate = null, $endDate = null)
    {
        // Get all records with valid employees
        $query = OvertimeRecord::with('employee')->whereHas('employee');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $records = $query->get();

        // Use separate queries for more accurate counting
        $staffQuery = OvertimeRecord::with('employee')
            ->whereHas('employee', function($q) {
                $q->where('departemen', 'staff');
            });

        $karyawanQuery = OvertimeRecord::with('employee')
            ->whereHas('employee', function($q) {
                $q->where('departemen', 'karyawan');
            });

        if ($startDate) {
            $staffQuery->whereDate('tanggal', '>=', $startDate);
            $karyawanQuery->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $staffQuery->whereDate('tanggal', '<=', $endDate);
            $karyawanQuery->whereDate('tanggal', '<=', $endDate);
        }

        $staffCount = $staffQuery->count();
        $karyawanCount = $karyawanQuery->count();

        return [
            'total_records' => $records->count(),
            'total_minutes' => $records->sum('overtime_minutes'),
            'total_pay' => $records->sum('overtime_pay'),
            'staff_count' => $staffCount,
            'karyawan_count' => $karyawanCount,
            'avg_overtime_minutes' => $records->count() > 0 ? round($records->avg('overtime_minutes')) : 0
        ];
    }

    /**
     * Recalculate overtime for all attendance records
     */
    public static function recalculateAll()
    {
        $attendances = Attendance::with('employee')
            ->whereNotNull('scan4')
            ->get();

        $processed = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->employee) {
                self::calculateOvertime($attendance);
                $processed++;
            }
        }

        Log::info("Recalculated overtime for {$processed} attendance records");
        return $processed;
    }
}

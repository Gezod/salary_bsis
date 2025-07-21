<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    /**
     * Evaluasi absensi termasuk deteksi shift setengah hari dan penerapan denda
     */
    public static function evaluate(Attendance $attendance)
    {
        $employee = $attendance->employee;
        if (!$employee) {
            Log::warning('Employee not found for attendance ID: ' . $attendance->id);
            return;
        }

        $date = $attendance->tanggal;
        $dayOfWeek = $date->format('l'); // Monday, Tuesday, etc.
        $isFriday = $dayOfWeek === 'Friday';

        // Reset values
        $attendance->late_minutes = 0;
        $attendance->early_leave_minutes = 0;
        $attendance->excess_break_minutes = 0;
        $attendance->invalid_break = false;
        $attendance->is_half_day = false;
        $attendance->half_day_type = null;
        $attendance->late_fine = 0;
        $attendance->break_fine = 0;
        $attendance->absence_fine = 0;
        $attendance->overtime_minutes = 0;
        $attendance->overtime_status = 'pending';

        // Detect half day shifts first
        self::detectHalfDayShift($attendance, $isFriday);

        // Apply penalties and calculations based on attendance type
        if ($attendance->is_half_day) {
            // Setengah hari: TIDAK ADA DENDA
            Log::info('Half day detected - no penalties applied', [
                'employee' => $employee->nama,
                'date' => $date->format('Y-m-d'),
                'type' => $attendance->half_day_type
            ]);
        } else {
            // Full day: Apply all penalties and calculations
            self::calculateLateness($attendance, $isFriday);
            self::calculateBreakViolations($attendance, $isFriday);
            self::calculateAbsenceFines($attendance);
            self::calculateOvertime($attendance, $isFriday);
        }

        // Calculate total fine
        $attendance->total_fine = $attendance->late_fine + $attendance->break_fine + $attendance->absence_fine;

        $attendance->save();

        Log::info('Attendance evaluated', [
            'employee' => $employee->nama,
            'date' => $date->format('Y-m-d'),
            'status' => $attendance->is_half_day ? 'Setengah Hari' : 'Full Harian',
            'half_day_type' => $attendance->half_day_type,
            'total_fine' => $attendance->total_fine,
            'overtime_minutes' => $attendance->overtime_minutes,
            'overtime_status' => $attendance->overtime_status
        ]);
    }

    /**
     * Detect half day shift berdasarkan pola scan
     */
    private static function detectHalfDayShift(Attendance $attendance, bool $isFriday)
    {
        $scan1 = $attendance->scan1;
        $scan2 = $attendance->scan2;
        $scan3 = $attendance->scan3;
        $scan4 = $attendance->scan4;

        if (!$scan1) {
            return; // No check-in, can't determine shift
        }

        if ($isFriday) {
            // Hari Jumat
            $expectedCheckIn = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 07:00');
            $shift1End = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 11:00');
            $breakEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 13:00');
            $shift2End = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 16:00');

            // Deteksi shift 1 (masuk 07:00, pulang 11:00, tidak ada data lagi)
            if (
                $scan1->format('H:i') >= '07:00' && $scan1->format('H:i') <= '07:30' &&
                $scan2 && $scan2->format('H:i') >= '11:00' && $scan2->format('H:i') <= '11:30' &&
                !$scan3 && !$scan4
            ) {
                $attendance->is_half_day = true;
                $attendance->half_day_type = 'shift_1';
                return;
            }

            // Deteksi shift 2 (masuk setelah istirahat 13:00, pulang 16:00)
            if (
                $scan1->format('H:i') >= '13:00' && $scan1->format('H:i') <= '13:30' &&
                $scan2 && $scan2->format('H:i') >= '16:00' && $scan2->format('H:i') <= '16:30' &&
                !$scan3 && !$scan4
            ) {
                $attendance->is_half_day = true;
                $attendance->half_day_type = 'shift_2';
                return;
            }

            // Deteksi lembur Jumat (masuk 07:00, pulang 16:00, tidak ada scan istirahat)
            if (
                $scan1->format('H:i') >= '07:00' && $scan1->format('H:i') <= '07:30' &&
                $scan2 && $scan2->format('H:i') >= '16:00' && $scan2->format('H:i') <= '17:00' &&
                !$scan3 && !$scan4
            ) {
                // Ini adalah lembur 1 jam tanpa denda (Full Harian dengan lembur)
                $attendance->overtime_minutes = 60; // 1 jam lembur
                $attendance->overtime_status = 'approved'; // Auto approve untuk Jumat
                $attendance->is_half_day = false;
                return;
            }
        } else {
            // Hari Senin-Kamis, Sabtu
            $expectedCheckIn = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 07:30');
            $shift1End = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 11:30');
            $breakEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 13:00');
            $shift2End = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 16:30');

            // Deteksi shift 1 (masuk 07:30, pulang 11:30, tidak ada data lagi)
            if (
                $scan1->format('H:i') >= '07:30' && $scan1->format('H:i') <= '08:00' &&
                $scan2 && $scan2->format('H:i') >= '11:30' && $scan2->format('H:i') <= '12:00' &&
                !$scan3 && !$scan4
            ) {
                $attendance->is_half_day = true;
                $attendance->half_day_type = 'shift_1';
                return;
            }

            // Deteksi shift 2 (masuk setelah istirahat 13:00, pulang 16:30)
            if (
                $scan1->format('H:i') >= '13:00' && $scan1->format('H:i') <= '13:30' &&
                $scan2 && $scan2->format('H:i') >= '16:30' && $scan2->format('H:i') <= '17:00' &&
                !$scan3 && !$scan4
            ) {
                $attendance->is_half_day = true;
                $attendance->half_day_type = 'shift_2';
                return;
            }
        }

        // Jika tidak memenuhi kriteria setengah hari, maka Full Harian
        $attendance->is_half_day = false;

        // Cek apakah ada lembur untuk Full Harian
        if ($scan1 && $scan2 && !$scan3 && !$scan4) {
            // Kemungkinan lembur: masuk pagi, pulang sore tanpa scan istirahat
            self::detectOvertimeWithoutBreak($attendance, $isFriday);
        } elseif ($scan1 && $scan2 && $scan3 && $scan4) {
            // Full day normal, cek lembur berdasarkan jam pulang
            self::calculateOvertime($attendance, $isFriday);
        } elseif ($scan1 && $scan2 && $scan3 && !$scan4) {
            // Ada scan istirahat tapi tidak ada scan pulang
            // Bisa jadi lembur atau lupa scan pulang
        }
    }

    /**
     * Deteksi lembur tanpa scan istirahat (untuk Full Harian)
     */
    private static function detectOvertimeWithoutBreak(Attendance $attendance, bool $isFriday)
    {
        $scan1 = $attendance->scan1;
        $scan2 = $attendance->scan2;

        if (!$scan1 || !$scan2) return;

        $expectedEndTime = $isFriday ? '16:00' : '16:30';
        $expectedEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $expectedEndTime);

        // Jika scan2 (pulang) lebih dari jam normal, anggap sebagai lembur
        if ($scan2->gt($expectedEnd)) {
            $overtimeMinutes = $scan2->diffInMinutes($expectedEnd);
            $attendance->overtime_minutes = $overtimeMinutes;
            $attendance->overtime_status = 'pending'; // Perlu approval

            Log::info('Overtime detected without break scans', [
                'employee' => $attendance->employee->nama,
                'date' => $attendance->tanggal->format('Y-m-d'),
                'overtime_minutes' => $overtimeMinutes,
                'scan1' => $scan1->format('H:i'),
                'scan2' => $scan2->format('H:i')
            ]);
        } else {
            // Jika jarak scan2 dan scan1 > 8 jam, kemungkinan lembur 1 jam
            $workDuration = $scan2->diffInMinutes($scan1);
            $expectedWorkMinutes = $isFriday ? 540 : 540; // 9 jam termasuk istirahat 1 jam

            if ($workDuration > $expectedWorkMinutes) {
                $attendance->overtime_minutes = 60; // Default 1 jam lembur
                $attendance->overtime_status = 'pending';

                Log::info('Overtime detected based on work duration', [
                    'employee' => $attendance->employee->nama,
                    'date' => $attendance->tanggal->format('Y-m-d'),
                    'work_duration' => $workDuration,
                    'overtime_minutes' => 60
                ]);
            }
        }
    }

    /**
     * Calculate lateness (hanya untuk Full Harian)
     */
    private static function calculateLateness(Attendance $attendance, bool $isFriday)
    {
        if (!$attendance->scan1) {
            return;
        }

        $expectedTime = $isFriday ? '07:00' : '07:30';
        $expectedCheckIn = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $expectedTime);
        $actualCheckIn = $attendance->scan1;

        if ($actualCheckIn->gt($expectedCheckIn)) {
            $attendance->late_minutes = $actualCheckIn->diffInMinutes($expectedCheckIn);
            $department = $attendance->employee->departemen;

            if (is_string($department)) {
                $attendance->late_fine = self::calculateLateFine($attendance->late_minutes, $department);
            } else {
                Log::warning("Department kosong atau bukan string untuk employee ID: {$attendance->employee_id}");
                $attendance->late_fine = 0;
            }
        }
    }

    /**
     * Calculate break violations (hanya untuk Full Harian)
     */
    private static function calculateBreakViolations(Attendance $attendance, bool $isFriday)
    {
        $scan2 = $attendance->scan2;
        $scan3 = $attendance->scan3;

        // Expected break times
        $expectedBreakStart = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 12:00');
        $expectedBreakEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 13:00');

        $penalties = config('penalties');
        $dept = $attendance->employee->departemen ?? null;

        // Cek validitas departemen dan konfigurasi penalty-nya
        if (!$dept || !isset($penalties[$dept])) {
            // Tangani jika departemen tidak valid atau belum ada di config
            $attendance->break_fine = 0;
            $attendance->invalid_break = false;
            return;
        }

        // Check for missing break scans
        if (!$scan2 && !$scan3) {
            // Missing both break scans
            $attendance->break_fine = $penalties[$dept]['absent_twice'] ?? 0;
            $attendance->invalid_break = true;
        } elseif (!$scan2 || !$scan3) {
            // Missing one break scan
            $attendance->break_fine = $penalties[$dept]['absent_break_once'] ?? 0;
            $attendance->invalid_break = true;
        } elseif ($scan2 && $scan3) {
            // Both scans present, check for late break
            if ($scan2->gt($expectedBreakStart)) {
                $attendance->break_fine = $penalties[$dept]['late_break'] ?? 0;
            }

            // Check for excess break time
            $breakDuration = $scan3->diffInMinutes($scan2);
            if ($breakDuration > 60) {
                $attendance->excess_break_minutes = $breakDuration - 60;
            }
        }
    }

    /**
     * Calculate absence fines (hanya untuk Full Harian)
     */
    private static function calculateAbsenceFines(Attendance $attendance)
    {
        $penalties = config('penalties');
        $dept = $attendance->employee->departemen;

        if (!$attendance->scan1) {
            $attendance->absence_fine += $penalties[$dept]['missing_checkin'];
        }

        if (!$attendance->scan4) {
            $dept = $attendance->employee->departemen ?? null;

            if ($dept && isset($penalties[$dept]['missing_checkout'])) {
                $attendance->absence_fine += $penalties[$dept]['missing_checkout'];
            } else {
                // Fallback aman jika departemen atau key tidak ditemukan
                $attendance->absence_fine += 0;
            }
        }
    }

    /**
     * Calculate overtime (untuk Full Harian)
     */
    private static function calculateOvertime(Attendance $attendance, bool $isFriday)
    {
        // Skip jika sudah ada overtime dari deteksi sebelumnya
        if ($attendance->overtime_minutes > 0) {
            return;
        }

        if (!$attendance->scan4) {
            return;
        }

        $expectedEndTime = $isFriday ? '16:00' : '16:30';
        $expectedEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $expectedEndTime);
        $actualEnd = $attendance->scan4;

        if ($actualEnd->gt($expectedEnd)) {
            $attendance->overtime_minutes = $actualEnd->diffInMinutes($expectedEnd);
            $attendance->overtime_status = 'pending'; // Perlu approval
        }
    }

    /**
     * Calculate late fine based on minutes and department
     */
    private static function calculateLateFine(int $minutes, string $department)
    {
        $penalties = config('penalties');
        $latePenalties = $penalties[$department]['late'];

        foreach ($latePenalties as $range) {
            if ($range[0] === '>') {
                if ($minutes > $range[1]) {
                    return is_callable($range[2]) ? $range[2]($minutes) : $range[2];
                }
            } else {
                if ($minutes >= $range[0] && $minutes <= $range[1]) {
                    return $range[2];
                }
            }
        }

        return 0;
    }
}

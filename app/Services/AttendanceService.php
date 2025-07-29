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
        $attendance->late_fine = 0;
        $attendance->break_fine = 0;
        $attendance->absence_fine = 0;
        $attendance->overtime_minutes = 0;
        $attendance->overtime_status = 'pending';

        // Detect half day shifts first - hanya jika belum di-set manual
        if (!$attendance->is_half_day) {
            self::detectHalfDayShift($attendance, $isFriday);
        } else {
            // Jika sudah set half day manual, pastikan type-nya ada
            if (empty($attendance->half_day_type)) {
                Log::warning('Manual half day attendance missing type', ['attendance' => $attendance->id]);
                // Beri default value jika diperlukan
                $attendance->half_day_type = 'shift_1';
            }
        }

        if ($attendance->is_half_day) {
            if ($attendance->half_day_type === 'shift_1') {
                $attendance->scan3 = null;
                $attendance->scan4 = null;
                $attendance->scan5 = null;
            } elseif ($attendance->half_day_type === 'shift_2') {
                $attendance->scan1 = null;
                $attendance->scan2 = null;
                $attendance->scan5 = null;
            }
        }
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
            // Pastikan reset nilai half_day_type jika bukan half day
            $attendance->half_day_type = null;
            self::calculateLateness($attendance, $isFriday);
            self::calculateBreakViolations($attendance, $isFriday);
            self::calculateAbsenceFines($attendance);
            self::calculateOvertime($attendance, $isFriday);
        }

        // Calculate total fine
        $attendance->total_fine = $attendance->late_fine + $attendance->break_fine + $attendance->absence_fine;

        Log::debug('Final attendance data before save', [
            'is_half_day' => $attendance->is_half_day,
            'half_day_type' => $attendance->half_day_type,
            'late_minutes' => $attendance->late_minutes,
            'overtime_status' => $attendance->overtime_status
        ]);

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
        // Jika sudah di-set manual, skip deteksi otomatis
        if ($attendance->is_half_day && $attendance->half_day_type) {
            return;
        }

        $scan1 = $attendance->scan1;
        $scan2 = $attendance->scan2;
        $scan3 = $attendance->scan3;
        $scan4 = $attendance->scan4;

        // Logika baru: deteksi berdasarkan pola scan yang ada
        $hasEarlyScans = $scan1 && $scan2; // scan1 dan scan2 ada
        $hasLateScans = $scan3 && $scan4;  // scan3 dan scan4 ada
        $onlyEarlyScans = $hasEarlyScans && !$scan3 && !$scan4; // hanya scan1 dan scan2
        $onlyLateScans = $hasLateScans && !$scan1 && !$scan2;   // hanya scan3 dan scan4

        if ($isFriday) {
            // Hari Jumat
            // Shift 1: hanya scan1 (07:00) dan scan2 (11:00)
            if ($onlyEarlyScans) {
                $scan1Time = $scan1->format('H:i');
                $scan2Time = $scan2->format('H:i');

                // Toleransi ±30 menit untuk jam masuk dan pulang
                if (
                    $scan1Time >= '06:30' && $scan1Time <= '07:30' &&
                    $scan2Time >= '10:30' && $scan2Time <= '11:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_1';
                    return;
                }
            }

            // Shift 2: hanya scan3 (12:30) dan scan4 (16:00)
            if ($onlyLateScans) {
                $scan3Time = $scan3->format('H:i');
                $scan4Time = $scan4->format('H:i');

                if (
                    $scan3Time >= '12:00' && $scan3Time <= '13:00' &&
                    $scan4Time >= '15:30' && $scan4Time <= '16:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_2';
                    return;
                }
            }
        } else {
            // Hari Senin-Kamis, Sabtu
            // Shift 1: hanya scan1 (07:00) dan scan2 (11:00)
            if ($onlyEarlyScans) {
                $scan1Time = $scan1->format('H:i');
                $scan2Time = $scan2->format('H:i');

                if (
                    $scan1Time >= '06:30' && $scan1Time <= '07:30' &&
                    $scan2Time >= '10:30' && $scan2Time <= '11:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_1';
                    return;
                }
            }

            // Shift 2: hanya scan3 (12:00) dan scan4 (16:30)
            if ($onlyLateScans) {
                $scan3Time = $scan3->format('H:i');
                $scan4Time = $scan4->format('H:i');

                if (
                    $scan3Time >= '11:30' && $scan3Time <= '12:30' &&
                    $scan4Time >= '16:00' && $scan4Time <= '17:00'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_2';
                    return;
                }
            }
        }

        // Jika tidak memenuhi kriteria setengah hari, maka Full Harian
        $attendance->is_half_day = false;
        $attendance->half_day_type = null;

        // Cek lembur untuk full day
        if ($hasEarlyScans && $hasLateScans) {
            // Full day normal, cek lembur berdasarkan jam pulang
            self::calculateOvertime($attendance, $isFriday);
        } elseif ($hasEarlyScans && !$hasLateScans) {
            // Kemungkinan lembur: masuk pagi, pulang sore tanpa scan istirahat
            self::detectOvertimeWithoutBreak($attendance, $isFriday);
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
            $attendance->overtime_status = $isFriday ? 'approved' : 'pending'; // Auto approve untuk Jumat

            Log::info('Overtime detected without break scans', [
                'employee' => $attendance->employee->nama,
                'date' => $attendance->tanggal->format('Y-m-d'),
                'overtime_minutes' => $overtimeMinutes,
                'scan1' => $scan1->format('H:i'),
                'scan2' => $scan2->format('H:i')
            ]);
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

        $expectedTime = $isFriday ? '07:00' : '07:00'; // Ubah ke 07:00 untuk semua hari
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
            $attendance->absence_fine += $penalties[$dept]['missing_checkin'] ?? 0;
        }

        if (!$attendance->scan4) {
            $attendance->absence_fine += $penalties[$dept]['missing_checkout'] ?? 0;
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

        // Cek apakah key tersedia untuk department tsb
        if (!isset($penalties[$department]['late'])) {
            return 0; // fallback kalau key tidak ditemukan
        }

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

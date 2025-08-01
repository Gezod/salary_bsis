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

        // Debug info
        Log::info('Evaluating attendance', [
            'id' => $attendance->id,
            'employee' => $employee->nama,
            'date' => $date->format('Y-m-d'),
            'day' => $dayOfWeek,
            'is_friday' => $isFriday,
            'is_half_day_before' => $attendance->is_half_day,
            'scans' => [
                'scan1' => optional($attendance->scan1)->format('H:i'),
                'scan2' => optional($attendance->scan2)->format('H:i'),
                'scan3' => optional($attendance->scan3)->format('H:i'),
                'scan4' => optional($attendance->scan4)->format('H:i'),
            ]
        ]);

        // Detect half day shifts hanya jika belum di-set manual
        if (!$attendance->is_half_day) {
            self::detectHalfDayShift($attendance, $isFriday);
        }

        // PENTING: Hanya skip penalty jika benar-benar half day yang valid
        if ($attendance->is_half_day && $attendance->half_day_type) {
            Log::info('Half day detected - no penalties applied', [
                'employee' => $employee->nama,
                'date' => $date->format('Y-m-d'),
                'type' => $attendance->half_day_type
            ]);

            // Clean up scans for half day
            if ($attendance->half_day_type === 'shift_1') {
                $attendance->scan3 = null;
                $attendance->scan4 = null;
                $attendance->scan5 = null;
            } elseif ($attendance->half_day_type === 'shift_2') {
                $attendance->scan1 = null;
                $attendance->scan2 = null;
                $attendance->scan5 = null;
            }
        } else {
            // Full day atau tidak memenuhi kriteria half day: Apply penalties
            $attendance->is_half_day = false;
            $attendance->half_day_type = null;

            Log::info('Full day detected - applying penalties', [
                'employee' => $employee->nama,
                'date' => $date->format('Y-m-d'),
                'day' => $dayOfWeek,
                'is_friday' => $isFriday
            ]);

            self::calculateLateness($attendance, $isFriday);
            self::calculateBreakViolations($attendance, $isFriday);
            self::calculateAbsenceFines($attendance);
            self::calculateOvertime($attendance, $isFriday);
        }

        // Calculate total fine
        $attendance->total_fine = $attendance->late_fine + $attendance->break_fine + $attendance->absence_fine;

        Log::info('Final attendance evaluation', [
            'employee' => $employee->nama,
            'department' => $employee->departemen,
            'date' => $date->format('Y-m-d'),
            'day' => $dayOfWeek,
            'is_friday' => $isFriday,
            'is_half_day' => $attendance->is_half_day,
            'half_day_type' => $attendance->half_day_type,
            'late_minutes' => $attendance->late_minutes,
            'late_fine' => $attendance->late_fine,
            'break_fine' => $attendance->break_fine,
            'absence_fine' => $attendance->absence_fine,
            'total_fine' => $attendance->total_fine,
            'overtime_minutes' => $attendance->overtime_minutes
        ]);

        $attendance->save();
    }

    /**
     * Detect half day shift dengan kriteria yang lebih ketat
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

        // Logika deteksi half day yang lebih ketat
        $hasEarlyScans = $scan1 && $scan2;
        $hasLateScans = $scan3 && $scan4;
        $onlyEarlyScans = $hasEarlyScans && !$scan3 && !$scan4;
        $onlyLateScans = $hasLateScans && !$scan1 && !$scan2;

        Log::debug('Half day detection', [
            'hasEarlyScans' => $hasEarlyScans,
            'hasLateScans' => $hasLateScans,
            'onlyEarlyScans' => $onlyEarlyScans,
            'onlyLateScans' => $onlyLateScans,
            'isFriday' => $isFriday
        ]);

        // KRITERIA YANG LEBIH KETAT UNTUK HALF DAY
        if ($isFriday) {
            // Shift 1 Jumat: scan1 (06:30-07:30) dan scan2 (10:30-11:30)
            if ($onlyEarlyScans) {
                $scan1Time = $scan1->format('H:i');
                $scan2Time = $scan2->format('H:i');

                if (
                    $scan1Time >= '06:30' && $scan1Time <= '07:30' &&
                    $scan2Time >= '10:30' && $scan2Time <= '11:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_1';
                    Log::info('Friday shift 1 half day detected', [
                        'scan1' => $scan1Time,
                        'scan2' => $scan2Time
                    ]);
                    return;
                }
            }

            // Shift 2 Jumat: scan3 (12:00-13:00) dan scan4 (15:30-16:30)
            if ($onlyLateScans) {
                $scan3Time = $scan3->format('H:i');
                $scan4Time = $scan4->format('H:i');

                if (
                    $scan3Time >= '12:00' && $scan3Time <= '13:00' &&
                    $scan4Time >= '15:30' && $scan4Time <= '16:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_2';
                    Log::info('Friday shift 2 half day detected', [
                        'scan3' => $scan3Time,
                        'scan4' => $scan4Time
                    ]);
                    return;
                }
            }
        } else {
            // Senin-Kamis, Sabtu
            // Shift 1: scan1 (06:30-07:30) dan scan2 (10:30-11:30)
            if ($onlyEarlyScans) {
                $scan1Time = $scan1->format('H:i');
                $scan2Time = $scan2->format('H:i');

                if (
                    $scan1Time >= '06:30' && $scan1Time <= '07:30' &&
                    $scan2Time >= '10:30' && $scan2Time <= '11:30'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_1';
                    Log::info('Weekday shift 1 half day detected', [
                        'scan1' => $scan1Time,
                        'scan2' => $scan2Time
                    ]);
                    return;
                }
            }

            // Shift 2: scan3 (11:30-12:30) dan scan4 (16:00-17:00)
            if ($onlyLateScans) {
                $scan3Time = $scan3->format('H:i');
                $scan4Time = $scan4->format('H:i');

                if (
                    $scan3Time >= '11:30' && $scan3Time <= '12:30' &&
                    $scan4Time >= '16:00' && $scan4Time <= '17:00'
                ) {
                    $attendance->is_half_day = true;
                    $attendance->half_day_type = 'shift_2';
                    Log::info('Weekday shift 2 half day detected', [
                        'scan3' => $scan3Time,
                        'scan4' => $scan4Time
                    ]);
                    return;
                }
            }
        }

        // Jika tidak memenuhi kriteria half day yang ketat, anggap full day
        $attendance->is_half_day = false;
        $attendance->half_day_type = null;

        Log::info('Full day determined - does not meet half day criteria', [
            'employee' => $attendance->employee->nama,
            'date' => $attendance->tanggal->format('Y-m-d')
        ]);
    }

    /**
     * Calculate lateness dengan waktu masuk yang benar per hari
     */
    private static function calculateLateness(Attendance $attendance, bool $isFriday)
    {
        if (!$attendance->scan1) {
            return;
        }

        // Waktu masuk yang benar berdasarkan hari
        // Jumat: 07:00, Hari lain: 07:30
        $officialStartTime = $isFriday ? '07:00' : '07:30';

        $officialStart = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $officialStartTime);
        $actualCheckIn = $attendance->scan1;

        // Jika scan masuk melebihi waktu resmi masuk
        if ($actualCheckIn->gt($officialStart)) {
            $attendance->late_minutes = $actualCheckIn->diffInMinutes($officialStart);
            $department = $attendance->employee->departemen;

            if (is_string($department) && in_array($department, ['staff', 'karyawan'])) {
                $attendance->late_fine = self::calculateLateFine($attendance->late_minutes, $department);
            } else {
                Log::warning("Department tidak valid untuk employee ID: {$attendance->employee_id}, dept: {$department}");
                $attendance->late_fine = 0;
            }

            Log::info('Keterlambatan terdeteksi', [
                'employee' => $attendance->employee->nama,
                'department' => $department,
                'date' => $attendance->tanggal->format('Y-m-d'),
                'day' => $attendance->tanggal->format('l'),
                'is_friday' => $isFriday,
                'waktu_resmi_masuk' => $officialStartTime,
                'waktu_masuk_actual' => $actualCheckIn->format('H:i'),
                'keterlambatan' => $attendance->late_minutes . ' menit',
                'denda' => $attendance->late_fine
            ]);
        } else {
            $attendance->late_minutes = 0;
            $attendance->late_fine = 0;

            Log::info('Tepat waktu', [
                'employee' => $attendance->employee->nama,
                'date' => $attendance->tanggal->format('Y-m-d'),
                'day' => $attendance->tanggal->format('l'),
                'is_friday' => $isFriday,
                'waktu_resmi_masuk' => $officialStartTime,
                'waktu_masuk_actual' => $actualCheckIn->format('H:i')
            ]);
        }
    }

    /**
     * Calculate break violations (hanya untuk Full Harian)
     */
    private static function calculateBreakViolations(Attendance $attendance, bool $isFriday)
    {
        $scan2 = $attendance->scan2;
        $scan3 = $attendance->scan3;
        $penalties = config('penalties');
        $dept = $attendance->employee->departemen ?? 'karyawan';

        if (!in_array($dept, ['staff', 'karyawan'])) {
            Log::error("Invalid department: {$dept}");
            $dept = 'karyawan'; // Default to karyawan if invalid
        }

        // Reset nilai sebelumnya
        $attendance->break_fine = 0;
        $attendance->invalid_break = false;

        // Tidak ada scan istirahat sama sekali
        if (!$scan2 && !$scan3) {
            $attendance->break_fine = $penalties[$dept]['absent_twice'] ?? 0;
            $attendance->invalid_break = true;
            Log::info('Break penalty: tidak absen istirahat 2x', [
                'employee' => $attendance->employee->nama,
                'fine' => $attendance->break_fine
            ]);
        }
        // Hanya salah satu scan istirahat yang ada
        elseif (!$scan2 || !$scan3) {
            $attendance->break_fine = $penalties[$dept]['absent_break_once'] ?? 0;
            $attendance->invalid_break = true;
            Log::info('Break penalty: tidak absen istirahat 1x', [
                'employee' => $attendance->employee->nama,
                'fine' => $attendance->break_fine
            ]);
        }
        // Kedua scan ada, tapi telat istirahat
        elseif ($scan2 && $scan3) {
            $expectedBreakStart = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 12:00');
            if ($scan2->gt($expectedBreakStart)) {
                $attendance->break_fine = $penalties[$dept]['late_break'] ?? 0;
                Log::info('Break penalty: telat istirahat', [
                    'employee' => $attendance->employee->nama,
                    'expected' => '12:00',
                    'actual' => $scan2->format('H:i'),
                    'fine' => $attendance->break_fine
                ]);
            }
        }
    }

    /**
     * Calculate absence fines (hanya untuk Full Harian)
     */
    private static function calculateAbsenceFines(Attendance $attendance)
    {
        $penalties = config('penalties');
        $dept = $attendance->employee->departemen ?? 'karyawan';

        if (!in_array($dept, ['staff', 'karyawan'])) {
            Log::error("Invalid department: {$dept}");
            $dept = 'karyawan'; // Default to karyawan if invalid
        }

        $totalAbsenceFine = 0;

        // Denda tidak absen masuk (scan1)
        if (!$attendance->scan1) {
            $fine = $penalties[$dept]['missing_checkin'] ?? 0;
            $totalAbsenceFine += $fine;
            Log::info('Missing check-in fine applied', [
                'employee' => $attendance->employee->nama,
                'department' => $dept,
                'fine' => $fine
            ]);
        }

        // Denda tidak absen pulang (scan4)
        if (!$attendance->scan4) {
            $fine = $penalties[$dept]['missing_checkout'] ?? 0;
            $totalAbsenceFine += $fine;
            Log::info('Missing check-out fine applied', [
                'employee' => $attendance->employee->nama,
                'department' => $dept,
                'fine' => $fine
            ]);
        }

        $attendance->absence_fine = $totalAbsenceFine;
    }

    /**
     * Calculate overtime (untuk Full Harian)
     */
    private static function calculateOvertime(Attendance $attendance, bool $isFriday)
    {
        if (!$attendance->scan4) {
            return;
        }

        $expectedEndTime = $isFriday ? '16:00' : '16:30';
        $expectedEnd = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' ' . $expectedEndTime);
        $actualEnd = $attendance->scan4;

        if ($actualEnd->gt($expectedEnd)) {
            $attendance->overtime_minutes = $actualEnd->diffInMinutes($expectedEnd);
            $attendance->overtime_status = 'pending';

            Log::info('Overtime calculated', [
                'employee' => $attendance->employee->nama,
                'expected_end' => $expectedEnd->format('H:i'),
                'actual_end' => $actualEnd->format('H:i'),
                'overtime_minutes' => $attendance->overtime_minutes
            ]);
        }
    }

    /**
     * Calculate late fine based on minutes and department
     */
    private static function calculateLateFine(int $minutes, string $department)
{
    $penalties = config('penalties');

    // Debug logging
    Log::debug('Calculating late fine', [
        'minutes' => $minutes,
        'department' => $department,
        'penalties_config' => $penalties[$department] ?? null
    ]);

    if (!isset($penalties[$department]['late'])) {
        Log::warning("Late penalties not found for department: {$department}");
        return 0;
    }

    $latePenalties = $penalties[$department]['late'];

    foreach ($latePenalties as $range) {
        if ($range[0] === '>') {
            if ($minutes > $range[1]) {
                if (is_callable($range[2])) {
                    $fine = $range[2]($minutes);
                    Log::info("Late fine calculated (>60 min)", [
                        'minutes' => $minutes,
                        'department' => $department,
                        'fine' => $fine
                    ]);
                    return round($fine);
                } else {
                    return $range[2];
                }
            }
        } else {
            if ($minutes >= $range[0] && $minutes <= $range[1]) {
                $rate = $range[2];
                $fine = $minutes * $rate;

                Log::info("Late fine calculated (proportional)", [
                    'minutes' => $minutes,
                    'rate' => $rate,
                    'range' => "{$range[0]}-{$range[1]}",
                    'department' => $department,
                    'fine' => $fine
                ]);

                return round($fine);
            }
        }
    }

    return 0;
}

    /**
     * Get penalty description based on attendance data
     */
    public static function getPenaltyDescription(Attendance $attendance): array
    {
        if ($attendance->is_half_day) {
            return [
                'late' => [],
                'break' => [],
                'absence' => [],
                'total_text' => 'Bebas denda (setengah hari)'
            ];
        }

        $penalties = [];
        $dept = $attendance->employee->departemen ?? 'karyawan';

        // Late penalties
        if ($attendance->late_minutes > 0) {
            $penalties['late'][] = "Terlambat {$attendance->late_minutes} menit";
        }

        // Break penalties
        if ($attendance->break_fine > 0) {
            if (!$attendance->scan2 && !$attendance->scan3) {
                $penalties['break'][] = "Tidak absen istirahat 2x";
            } elseif (!$attendance->scan2 || !$attendance->scan3) {
                $penalties['break'][] = "Tidak absen istirahat 1x";
            } elseif ($attendance->scan2 && $attendance->scan3) {
                $expectedBreakStart = Carbon::parse($attendance->tanggal->format('Y-m-d') . ' 12:00');
                if ($attendance->scan2->gt($expectedBreakStart)) {
                    $penalties['break'][] = "Telat istirahat";
                }
            }
        }

        // Absence penalties
        if ($attendance->absence_fine > 0) {
            if (!$attendance->scan1) {
                $penalties['absence'][] = "Lupa absen masuk";
            }
            if (!$attendance->scan4) {
                $penalties['absence'][] = "Lupa absen pulang";
            }
        }

        $totalText = [];
        if ($attendance->late_fine > 0) $totalText[] = "Denda telat: Rp " . number_format($attendance->late_fine, 0, ',', '.');
        if ($attendance->break_fine > 0) $totalText[] = "Denda istirahat: Rp " . number_format($attendance->break_fine, 0, ',', '.');
        if ($attendance->absence_fine > 0) $totalText[] = "Denda absen: Rp " . number_format($attendance->absence_fine, 0, ',', '.');

        return [
            'late' => $penalties['late'] ?? [],
            'break' => $penalties['break'] ?? [],
            'absence' => $penalties['absence'] ?? [],
            'total_text' => empty($totalText) ? 'Tidak ada denda' : implode(', ', $totalText)
        ];
    }

    /**
     * Get detailed penalty calculation for display
     */
    public static function getDetailedPenaltyCalculation(Attendance $attendance): array
    {
        if ($attendance->is_half_day) {
            return [
                'is_half_day' => true,
                'late_calculation' => null,
                'break_calculation' => null,
                'absence_calculation' => null,
                'total_penalty' => 0
            ];
        }

        $dept = $attendance->employee->departemen ?? 'karyawan';
        $penalties = config('penalties');

        $calculation = [
            'is_half_day' => false,
            'late_calculation' => null,
            'break_calculation' => null,
            'absence_calculation' => null,
            'total_penalty' => $attendance->total_fine
        ];

        // Late calculation
        if ($attendance->late_minutes > 0 && $attendance->late_fine > 0) {
            $rate = round($attendance->late_fine / $attendance->late_minutes, 2);
            $calculation['late_calculation'] = [
                'minutes' => $attendance->late_minutes,
                'rate' => $rate,
                'fine' => $attendance->late_fine,
                'formula' => "{$attendance->late_minutes} menit × Rp " . number_format($rate, 0, ',', '.') . "/menit"
            ];
        }

        // Break calculation
        if ($attendance->break_fine > 0) {
            $breakType = '';
            if (!$attendance->scan2 && !$attendance->scan3) {
                $breakType = 'Tidak absen istirahat 2x';
            } elseif (!$attendance->scan2 || !$attendance->scan3) {
                $breakType = 'Tidak absen istirahat 1x';
            } else {
                $breakType = 'Telat istirahat';
            }

            $calculation['break_calculation'] = [
                'type' => $breakType,
                'fine' => $attendance->break_fine
            ];
        }

        // Absence calculation
        if ($attendance->absence_fine > 0) {
            $absenceTypes = [];
            if (!$attendance->scan1) {
                $fine = $penalties[$dept]['missing_checkin'] ?? 0;
                $absenceTypes[] = "Lupa absen masuk (Rp " . number_format($fine, 0, ',', '.') . ")";
            }
            if (!$attendance->scan4) {
                $fine = $penalties[$dept]['missing_checkout'] ?? 0;
                $absenceTypes[] = "Lupa absen pulang (Rp " . number_format($fine, 0, ',', '.') . ")";
            }

            $calculation['absence_calculation'] = [
                'types' => $absenceTypes,
                'fine' => $attendance->absence_fine
            ];
        }

        return $calculation;
    }

    private static function validatePenaltyConfig()
    {
        $penalties = config('penalties');

        if (!$penalties) {
            $errorMsg = 'Penalty configuration not found!';
            Log::error($errorMsg);
            throw new \RuntimeException($errorMsg);
        }

        // Validasi struktur dasar
        if (!isset($penalties['staff'])) {
            $errorMsg = 'Staff penalties not configured!';
            Log::error($errorMsg);
            throw new \RuntimeException($errorMsg);
        }

        if (!isset($penalties['karyawan'])) {
            $errorMsg = 'Karyawan penalties not configured!';
            Log::error($errorMsg);
            throw new \RuntimeException($errorMsg);
        }

        return $penalties;
    }
}

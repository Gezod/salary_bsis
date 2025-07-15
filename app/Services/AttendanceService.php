<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\OvertimeService;

class AttendanceService
{
    public static function evaluate(Attendance $a): void
    {
        $hari = $a->tanggal->format('l'); // 'Monday', 'Tuesday', etc.
        $isJumat = $hari === 'Friday';

        // Jam kerja
        $start = '07:30';
        $end = $isJumat ? '16:00' : '16:30';

        // Jam istirahat
        $breakStart = '11:00';
        $breakEnd = $isJumat ? '12:30' : '12:00';

        // Check for half-day (only scan1 and scan2)
        $isHalfDay = $a->scan1 && $a->scan2 && !$a->scan3 && !$a->scan4 && !$a->scan5;
        $a->is_half_day = $isHalfDay;

        // Helper konversi waktu
        $time = fn($hms) => Carbon::parse($a->tanggal->format('Y-m-d') . ' ' . $hms)
            ->setTimezone(config('app.timezone', 'Asia/Jakarta'));

        // Hitung keterlambatan (kecuali half day)
        if ($a->scan1 instanceof Carbon && !$isHalfDay) {
            $expectedTime = $time($start);
            $actualTime = $a->scan1->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));
            $a->late_minutes = max(0, $expectedTime->diffInMinutes($actualTime, false));
        } else {
            $a->late_minutes = 0;
        }

        // Hitung pulang cepat dan lembur (kecuali half day)
        if ($a->scan4 instanceof Carbon && !$isHalfDay) {
            $expectedOut = $time($end);
            $actualOut = $a->scan4->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));

            $a->early_leave_minutes = max(0, $expectedOut->diffInMinutes($actualOut, false) * -1);
            $a->overtime_minutes = max(0, $actualOut->diffInMinutes($expectedOut, false));
        } else {
            $a->early_leave_minutes = 0;
            $a->overtime_minutes = 0;
        }

        // Evaluasi istirahat (kecuali half day)
        if ($a->scan2 instanceof Carbon && $a->scan3 instanceof Carbon && !$isHalfDay) {
            $breakOut = $a->scan2->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));
            $breakIn  = $a->scan3->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));

            $actualDuration = $breakIn->diffInMinutes($breakOut);
            $allowedDuration = Carbon::parse($breakEnd)->diffInMinutes(Carbon::parse($breakStart));

            $a->excess_break_minutes = max(0, $actualDuration - $allowedDuration);
            $a->invalid_break = $breakOut->lt($time($breakStart)) || $breakIn->gt($time($breakEnd));
        } else {
            $a->excess_break_minutes = 0;
            $a->invalid_break = false;
        }

        // Hitung total denda (tidak ada denda untuk half day)
        if ($isHalfDay) {
            $a->late_fine = 0;
            $a->break_fine = 0;
            $a->absence_fine = 0;
            $a->total_fine = 0;
        } else {
            self::computeFine($a);
        }

        $a->save();
    }

    private static function computeFine(Attendance $a): void
    {
        $role = strtolower($a->employee->departemen); // pakai departemen sebagai acuan role
        $rule = config("penalties.{$role}");

        if (!$rule) {
            $a->late_fine = 0;
            $a->break_fine = 0;
            $a->absence_fine = 0;
            $a->total_fine = 0;
            return;
        }

        // Denda telat
        $lateFine = 0;
        if ($a->late_minutes > 0) {
            foreach ($rule['late'] as $range) {
                if ($range[0] === '>' && $a->late_minutes > $range[1]) {
                    $lateFine = $range[2]($a->late_minutes);
                    break;
                }
                if ($a->late_minutes >= $range[0] && $a->late_minutes <= $range[1]) {
                    $lateFine = $range[2];
                    break;
                }
            }
        }

        // Denda istirahat
        $breakFine = $a->excess_break_minutes > 0 ? $rule['late_break'] : 0;

        // Denda absen
        $absenceFine = 0;
        $missing = collect([
            'scan1' => $rule['missing_checkin'],
            'scan2' => $rule['absent_break_once'],
            'scan3' => $rule['absent_break_once'],
            'scan4' => $rule['missing_checkout'],
        ])->filter(fn($val, $key) => is_null($a->$key));

        if ($missing->has('scan2') && $missing->has('scan3')) {
            $absenceFine += $rule['absent_twice'];
            $missing = $missing->except(['scan2', 'scan3']);
        }

        $absenceFine += $missing->sum();

        // Total semua
        $a->late_fine = $lateFine;
        $a->break_fine = $breakFine;
        $a->absence_fine = $absenceFine;
        $a->total_fine = $lateFine + $breakFine + $absenceFine;

        // Hitung lembur
        OvertimeService::calculateOvertime($a);
        // Simpan perubahan
         $a->save();
    }
}

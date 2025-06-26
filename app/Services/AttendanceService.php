<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    public static function evaluate(Attendance $a): void
    {
        // Cek hari
        $hari     = $a->tanggal->format('l'); // pakai format biasa, bukan translated
        $isJumat  = $hari === 'Friday';

        [$start, $end] = $isJumat
            ? ['07:00', '16:00']
            : ['07:30', '16:30'];

        $breakStart = '11:00';
        $breakEnd   = $isJumat ? '12:30' : '12:00';

        // Format waktu referensi
        $time = fn($hms) => Carbon::parse($a->tanggal->format('Y-m-d') . ' ' . $hms)->setTimezone(config('app.timezone', 'Asia/Jakarta'));

        // Telat masuk
        if ($a->scan1 instanceof Carbon) {
            $expectedTime = $time($start);
            $actualTime = $a->scan1->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));

            $diff = $expectedTime->diffInMinutes($actualTime, false);
            Log::info("== Evaluasi Telat Masuk ==");
            Log::info("Tanggal      : {$a->tanggal->toDateString()}");
            Log::info("Hari         : {$hari}");
            Log::info("Batas Masuk  : {$expectedTime->format('H:i:s')}");
            Log::info("Jam Masuk    : {$actualTime->format('H:i:s')}");
            Log::info("Terlambat    : {$diff} menit");

            $a->late_minutes = max(0, $diff);
        }

        // Pulang cepat dan lembur
        if ($a->scan4 instanceof Carbon) {
            $expectedOut = $time($end);
            $actualOut = $a->scan4->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));

            $a->early_leave_minutes = max(0, $expectedOut->diffInMinutes($actualOut, false) * -1);
            $a->overtime_minutes    = max(0, $actualOut->diffInMinutes($expectedOut, false));
        }

        // Evaluasi istirahat
        if ($a->scan2 instanceof Carbon && $a->scan3 instanceof Carbon) {
            $breakOut = $a->scan2->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));
            $breakIn  = $a->scan3->copy()->setTimezone(config('app.timezone', 'Asia/Jakarta'));

            $actual  = $breakIn->diffInMinutes($breakOut);
            $allowed = Carbon::parse($breakEnd)->diffInMinutes(Carbon::parse($breakStart));

            $a->excess_break_minutes = max(0, $actual - $allowed);
            $a->invalid_break = $breakOut->lt($time($breakStart)) || $breakIn->gt($time($breakEnd));
        }

        // Hitung denda
        self::computeFine($a);
        $a->save();
    }

    private static function computeFine(Attendance $a): void
    {
        $role = $a->employee->role;
        $rule = config("penalties.{$role}");

        // Denda telat
        $lateFine = 0;
        if ($a->late_minutes > 0) {
            foreach ($rule['late'] as $range) {
                [$min, $max, $fine] = $range;
                if ($min === '>' && $a->late_minutes > $max) {
                    $lateFine = $fine($a->late_minutes);
                    break;
                }
                if ($a->late_minutes >= $min && $a->late_minutes <= $max) {
                    $lateFine = $fine;
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

        // Simpan total denda
        $a->late_fine    = $lateFine;
        $a->break_fine   = $breakFine;
        $a->absence_fine = $absenceFine;
        $a->total_fine   = $lateFine + $breakFine + $absenceFine;
    }
}

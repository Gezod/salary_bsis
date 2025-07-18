<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tanggal',
        'scan1',
        'scan2',
        'scan3',
        'scan4',
        'scan5',
        'late_minutes',
        'early_leave_minutes',
        'excess_break_minutes',
        'overtime_minutes',
        'invalid_break',
        'is_half_day',
        'half_day_type',
        'half_day_notes',
        'late_fine',
        'break_fine',
        'absence_fine',
        'total_fine'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'scan1' => 'datetime',
        'scan2' => 'datetime',
        'scan3' => 'datetime',
        'scan4' => 'datetime',
        'scan5' => 'datetime',
        'invalid_break' => 'boolean',
        'is_half_day' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedScan($scanField)
    {
        if ($this->$scanField) {
            return $this->$scanField->format('H:i');
        }
        return null;
    }

    /**
     * Get attendance status text
     */
    public function getStatusTextAttribute()
    {
        if ($this->is_half_day) {
            return $this->half_day_type_text;
        }

        return 'Full Harian';
    }

    /**
     * Get half day type text
     */
    public function getHalfDayTypeTextAttribute()
    {
        if (!$this->is_half_day) {
            return null;
        }

        switch ($this->half_day_type) {
            case 'shift_1':
                return 'Setengah Hari (Shift 1)';
            case 'shift_2':
                return 'Setengah Hari (Shift 2)';
            default:
                return 'Setengah Hari';
        }
    }

    /**
     * Get working hours
     */
    public function getWorkingHoursAttribute()
    {
        if ($this->is_half_day) {
            return 4; // 4 jam untuk setengah hari
        }
        return 8; // Full day
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_half_day) {
            return 'bg-info';
        }

        if ($this->late_minutes > 0) {
            return 'bg-warning';
        }

        if (!$this->scan1) {
            return 'bg-danger';
        }

        return 'bg-success';
    }

    /**
     * Get detailed status with penalties info
     */
    public function getDetailedStatusAttribute()
    {
        if ($this->is_half_day) {
            return [
                'type' => 'half_day',
                'text' => $this->half_day_type_text,
                'badge' => 'bg-info',
                'penalties' => 'Tidak ada denda'
            ];
        }

        $status = 'Full Harian';
        $penalties = [];

        if (!$this->scan1) {
            $status = 'Tidak Hadir';
            $penalties[] = 'Tidak absen masuk';
        } elseif ($this->late_minutes > 0) {
            $penalties[] = "Terlambat {$this->late_minutes} menit";
        }

        if ($this->invalid_break) {
            $penalties[] = 'Pelanggaran istirahat';
        }

        if ($this->overtime_minutes > 0) {
            $penalties[] = "Lembur {$this->overtime_minutes} menit";
        }

        return [
            'type' => 'full_day',
            'text' => $status,
            'badge' => $this->status_badge,
            'penalties' => empty($penalties) ? 'Tidak ada pelanggaran' : implode(', ', $penalties)
        ];
    }

    /**
     * Get formatted total fine
     */
    public function getFormattedTotalFineAttribute()
    {
        if ($this->total_fine > 0) {
            return 'Rp ' . number_format($this->total_fine, 0, ',', '.');
        }
        return '-';
    }

    /**
     * Check if attendance has overtime
     */
    public function hasOvertime()
    {
        return $this->overtime_minutes > 0;
    }

    /**
     * Get overtime text
     */
    public function getOvertimeTextAttribute()
    {
        if ($this->overtime_minutes > 0) {
            $hours = floor($this->overtime_minutes / 60);
            $minutes = $this->overtime_minutes % 60;

            if ($hours > 0) {
                return $minutes > 0 ? "{$hours}j {$minutes}m" : "{$hours} jam";
            }
            return "{$minutes} menit";
        }
        return null;
    }
}

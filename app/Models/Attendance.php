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
        'overtime_status',
        'overtime_notes',
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
        'overtime_status' => 'string',
    ];

    protected $appends = [
        'status_text',
        'half_day_type_text',
        'working_hours',
        'status_badge',
        'detailed_status',
        'formatted_total_fine',
        'overtime_text',
        'overtime_status_badge',
        'formatted_overtime_status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedScan($scanField)
    {
        return $this->{$scanField} ? Carbon::parse($this->{$scanField})->format('H:i') : null;
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
        if (!$this->is_half_day) return null;

        return match ($this->half_day_type) {
            'shift_1' => 'Setengah Hari (Shift 1)',
            'shift_2' => 'Setengah Hari (Shift 2)',
            default => 'Setengah Hari'
        };
    }

    /**
     * Get working hours
     */
    public function getWorkingHoursAttribute()
    {
        return $this->is_half_day ? 4 : 8;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_half_day) return 'bg-info';
        if ($this->late_minutes > 0) return 'bg-warning';
        if (!$this->scan1) return 'bg-danger';
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
        return $this->total_fine > 0
            ? 'Rp ' . number_format($this->total_fine, 0, ',', '.')
            : '-';
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
        if (!$this->hasOvertime()) return null;

        $hours = floor($this->overtime_minutes / 60);
        $minutes = $this->overtime_minutes % 60;

        $text = $hours > 0
            ? ($minutes > 0 ? "{$hours}j {$minutes}m" : "{$hours} jam")
            : "{$minutes} menit";

        return $text . " ({$this->overtime_status})";
    }

    /**
     * Get overtime status badge
     */
    public function getOvertimeStatusBadgeAttribute()
    {
        return match ($this->overtime_status ?? 'pending') {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-warning'
        };
    }

    /**
     * Get formatted overtime status
     */
    public function getFormattedOvertimeStatusAttribute()
    {
        return match ($this->overtime_status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Menunggu'
        };
    }

    /**
     * Scope for half day attendances
     */
    public function scopeHalfDay($query, $type = null)
    {
        $query->where('is_half_day', true);

        if ($type) {
            $query->where('half_day_type', $type);
        }

        return $query;
    }

    /**
     * Scope for full day attendances
     */
    public function scopeFullDay($query)
    {
        return $query->where('is_half_day', false);
    }

    /**
     * Scope for attendances with fines
     */
    public function scopeWithFines($query)
    {
        return $query->where('total_fine', '>', 0);
    }

    /**
     * Scope for attendances with overtime
     */
    public function scopeWithOvertime($query, $status = null)
    {
        $query->where('overtime_minutes', '>', 0);

        if ($status) {
            $query->where('overtime_status', $status);
        }

        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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
        'overtime_minutes',
        'excess_break_minutes',
        'invalid_break',
        'late_fine',
        'break_fine',
        'absence_fine',
        'total_fine',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'scan1' => 'datetime',
        'scan2' => 'datetime',
        'scan3' => 'datetime',
        'scan4' => 'datetime',
        'scan5' => 'datetime',
        'invalid_break' => 'boolean',
    ];

    /**
     * Relasi ke Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Format waktu fallback, aman jika null
     */
    public function getFormattedScan($column)
    {
        $value = $this->{$column};
        return $value instanceof Carbon ? $value->format('H:i:s') : '-';
    }

    /**
     * Total pelanggaran dalam menit (null-safe)
     */
    public function getTotalViolationMinutesAttribute()
    {
        return
            ($this->late_minutes ?? 0) +
            ($this->early_leave_minutes ?? 0) +
            ($this->excess_break_minutes ?? 0);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OvertimeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tanggal',
        'overtime_minutes',
        'overtime_pay',
        'scan4',
        'expected_out',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'scan4' => 'datetime',
        'expected_out' => 'datetime',
        'overtime_minutes' => 'integer',
        'overtime_pay' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedOvertimePayAttribute()
    {
        $pay = (int) ($this->overtime_pay ?? 0);
        return 'Rp ' . number_format($pay, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = (int) ($this->overtime_minutes ?? 0);
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return "{$hours}j {$mins}m";
    }

    public function getFormattedScan4Attribute()
    {
        return $this->scan4 ? $this->scan4->format('H:i') : '-';
    }

    public function getFormattedExpectedOutAttribute()
    {
        return $this->expected_out ? $this->expected_out->format('H:i') : '-';
    }

    public function getFormattedDateWithDayAttribute()
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $dayName = $days[$this->tanggal->format('l')] ?? $this->tanggal->format('l');
        return $dayName . ', ' . $this->tanggal->format('d M Y');
    }
}

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
        return 'Rp ' . number_format($this->overtime_pay, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->overtime_minutes / 60);
        $minutes = $this->overtime_minutes % 60;
        return "{$hours}j {$minutes}m";
    }

    public function getFormattedScan4Attribute()
    {
        return $this->scan4 ? $this->scan4->format('H:i') : '-';
    }

    public function getFormattedExpectedOutAttribute()
    {
        return $this->expected_out ? $this->expected_out->format('H:i') : '-';
    }
}

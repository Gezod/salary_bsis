<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpjsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'bpjs_number',
        'bpjs_monthly_amount',
        'bpjs_weekly_amount',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'bpjs_monthly_amount' => 'integer',
        'bpjs_weekly_amount' => 'integer',
        'is_active' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedBpjsMonthlyAmountAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_monthly_amount, 0, ',', '.');
    }

    public function getFormattedBpjsWeeklyAmountAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_weekly_amount, 0, ',', '.');
    }
}

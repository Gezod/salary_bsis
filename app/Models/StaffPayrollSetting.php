<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayrollSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'monthly_salary',
        'daily_meal_allowance',
        'meal_allowance_notes'
    ];

    protected $casts = [
        'monthly_salary' => 'integer',
        'daily_meal_allowance' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedMonthlySalaryAttribute()
    {
        return 'Rp ' . number_format($this->monthly_salary, 0, ',', '.');
    }

    public function getFormattedDailyMealAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->daily_meal_allowance, 0, ',', '.');
    }

    public function getEstimatedMonthlyMealAllowanceAttribute()
    {
        // Estimasi 26 hari kerja per bulan (6 hari kerja x 4.33 minggu)
        return $this->daily_meal_allowance * 26;
    }

    public function getFormattedEstimatedMonthlyMealAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->estimated_monthly_meal_allowance, 0, ',', '.');
    }

    public function getEstimatedGrossSalaryAttribute()
    {
        return $this->monthly_salary + $this->estimated_monthly_meal_allowance;
    }

    public function getFormattedEstimatedGrossSalaryAttribute()
    {
        return 'Rp ' . number_format($this->estimated_gross_salary, 0, ',', '.');
    }
}

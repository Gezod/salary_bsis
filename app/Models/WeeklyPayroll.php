<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklyPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'working_days',
        'present_days',
        'basic_salary',
        'overtime_pay',
        'meal_allowance',
        'total_fines',
        'bpjs_deduction',
        'bpjs_allowance',
        'bpjs_cash_payment',
        'gross_salary',
        'net_salary',
        'payment_method',
        'payment_date',
        'payment_proof',
        'notes',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'basic_salary' => 'integer',
        'overtime_pay' => 'integer',
        'meal_allowance' => 'integer',
        'total_fines' => 'integer',
        'bpjs_deduction' => 'integer',
        'bpjs_allowance' => 'integer',
        'bpjs_cash_payment' => 'integer',
        'gross_salary' => 'integer',
        'net_salary' => 'integer',
        'working_days' => 'integer',
        'present_days' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFormattedBasicSalaryAttribute()
    {
        return 'Rp ' . number_format($this->basic_salary, 0, ',', '.');
    }

    public function getFormattedOvertimePayAttribute()
    {
        return 'Rp ' . number_format($this->overtime_pay, 0, ',', '.');
    }

    public function getFormattedMealAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->meal_allowance, 0, ',', '.');
    }

    public function getFormattedTotalFinesAttribute()
    {
        return 'Rp ' . number_format($this->total_fines, 0, ',', '.');
    }

    public function getFormattedBpjsDeductionAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_deduction, 0, ',', '.');
    }

    public function getFormattedBpjsAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_allowance ?? 0, 0, ',', '.');
    }

    public function getFormattedBpjsCashPaymentAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_cash_payment ?? 0, 0, ',', '.');
    }

    public function getFormattedGrossSalaryAttribute()
    {
        return 'Rp ' . number_format($this->gross_salary, 0, ',', '.');
    }

    public function getFormattedNetSalaryAttribute()
    {
        return 'Rp ' . number_format($this->net_salary, 0, ',', '.');
    }

    public function getPeriodNameAttribute()
    {
        return $this->start_date->format('d M') . ' - ' . $this->end_date->format('d M Y');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getPaymentMethodBadgeAttribute()
    {
        $badges = [
            'cash' => 'bg-success',
            'transfer' => 'bg-primary'
        ];

        return $badges[$this->payment_method] ?? 'bg-secondary';
    }

    /**
     * Check if this is end of month period (within last 7 days of month)
     */
    public function isEndOfMonthPeriod()
    {
        $endOfMonth = $this->end_date->copy()->endOfMonth();
        $daysFromEndOfMonth = $this->end_date->diffInDays($endOfMonth);

        return $daysFromEndOfMonth <= 7;
    }

    /**
     * Get BPJS setting for this employee
     */
    public function getBpjsSettingAttribute()
    {
        return \App\Models\BpjsSetting::where('employee_id', $this->employee_id)->first();
    }

    /**
     * Get BPJS premium for the month of end_date
     */
    public function getBpjsPremiumForPeriodAttribute()
    {
        $month = $this->end_date->month;
        $year = $this->end_date->year;

        return \App\Models\BpjsPremium::where('employee_id', $this->employee_id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
    }

    /**
     * Check if employee has active BPJS
     */
    public function hasActiveBpjs()
    {
        return $this->bpjs_setting && $this->bpjs_setting->is_active;
    }

    /**
     * Check if has BPJS premium for this period's month
     */
    public function hasBpjsPremiumForPeriod()
    {
        return $this->bpjs_premium_for_period !== null;
    }

    /**
     * Check if needs cash payment for BPJS
     */
    public function needsBpjsCashPayment()
    {
        return ($this->bpjs_cash_payment ?? 0) > 0;
    }

    /**
     * Check if should show BPJS information (end of month with active BPJS)
     */
    public function shouldShowBpjsInfo()
    {
        return $this->isEndOfMonthPeriod() && $this->hasActiveBpjs();
    }
}

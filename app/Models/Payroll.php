<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
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
        return 'Rp ' . number_format($this->bpjs_allowance, 0, ',', '.');
    }

    public function getFormattedBpjsCashPaymentAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_cash_payment, 0, ',', '.');
    }

    public function getFormattedGrossSalaryAttribute()
    {
        return 'Rp ' . number_format($this->gross_salary, 0, ',', '.');
    }

    public function getFormattedNetSalaryAttribute()
    {
        return 'Rp ' . number_format($this->net_salary, 0, ',', '.');
    }

    public function getMonthNameAttribute()
    {
        return Carbon::create($this->year, $this->month, 1)->translatedFormat('F Y');
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
     * Get BPJS setting for this employee
     */
    public function getBpjsSettingAttribute()
    {
        return \App\Models\BpjsSetting::where('employee_id', $this->employee_id)->first();
    }

    /**
     * Get BPJS premium for this period
     */
    public function getBpjsPremiumAttribute()
    {
        return \App\Models\BpjsPremium::where('employee_id', $this->employee_id)
            ->where('month', $this->month)
            ->where('year', $this->year)
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
     * Check if has BPJS premium for this period
     */
    public function hasBpjsPremium()
    {
        return $this->bpjs_premium !== null;
    }

    /**
     * Check if needs cash payment for BPJS (always false now)
     */
    public function needsBpjsCashPayment()
    {
        return false; // Tidak ada pembayaran tunai lagi
    }

    /**
     * Get total BPJS premium for this period
     */
    public function getTotalBpjsPremiumAttribute()
    {
        if ($this->bpjs_premium) {
            return $this->bpjs_premium->premium_amount;
        }

        return $this->bpjs_allowance ?? 0;
    }
}

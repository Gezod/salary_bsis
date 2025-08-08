<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class BpjsPremium extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'premium_amount',
        'notes'
    ];

    protected $casts = [
        'premium_amount' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get formatted premium amount
     */
    public function getFormattedPremiumAmountAttribute()
    {
        return 'Rp ' . number_format($this->premium_amount, 0, ',', '.');
    }

    /**
     * Get period name
     */
    public function getPeriodNameAttribute()
    {
        return Carbon::create($this->year, $this->month, 1)->translatedFormat('F Y');
    }

    /**
     * Get formatted created at
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : '-';
    }

    /**
     * Calculate cash payment needed (premium - bpjs allowance)
     */
    public function getCashPaymentAttribute()
    {
        $bpjsSetting = BpjsSetting::where('employee_id', $this->employee_id)
            ->where('is_active', true)
            ->first();

        if (!$bpjsSetting) {
            return $this->premium_amount;
        }

        $cashPayment = $this->premium_amount - $bpjsSetting->bpjs_monthly_amount;
        return max(0, $cashPayment);
    }

    /**
     * Get formatted cash payment
     */
    public function getFormattedCashPaymentAttribute()
    {
        return 'Rp ' . number_format($this->cash_payment, 0, ',', '.');
    }

    /**
     * Check if needs cash payment
     */
    public function needsCashPayment()
    {
        return $this->cash_payment > 0;
    }

    /**
     * Get BPJS allowance amount
     */
    public function getBpjsAllowanceAttribute()
    {
        $bpjsSetting = BpjsSetting::where('employee_id', $this->employee_id)
            ->where('is_active', true)
            ->first();

        return $bpjsSetting ? $bpjsSetting->bpjs_monthly_amount : 0;
    }

    /**
     * Get formatted BPJS allowance
     */
    public function getFormattedBpjsAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_allowance, 0, ',', '.');
    }
}

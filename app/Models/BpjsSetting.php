<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
        'is_active' => 'boolean',
        'bpjs_monthly_amount' => 'integer',
        'bpjs_weekly_amount' => 'integer',
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
     * Get formatted monthly amount
     */
    public function getFormattedBpjsMonthlyAmountAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_monthly_amount, 0, ',', '.');
    }

    /**
     * Get formatted weekly amount
     */
    public function getFormattedBpjsWeeklyAmountAttribute()
    {
        return 'Rp ' . number_format($this->bpjs_weekly_amount, 0, ',', '.');
    }

    /**
     * Get formatted updated at
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : '-';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return $this->is_active ? 'check-circle' : 'x-circle';
    }

    /**
     * Scope for active BPJS
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive BPJS
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if BPJS is active
     */
    public function isActive()
    {
        return $this->is_active;
    }
}

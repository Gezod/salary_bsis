<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'nip',
        'nama',
        'jabatan',
        'departemen',
        'kantor',
        'role',
        'tanggal_start_kontrak',
        'tanggal_end_kontrak',
        'tanggal_mulai_kontrak_awal',
        'daily_salary',
        'meal_allowance',
        'bank_name',
        'account_number'
    ];

    protected $casts = [
        'tanggal_start_kontrak' => 'date',
        'tanggal_end_kontrak' => 'date',
        'tanggal_mulai_kontrak_awal' => 'date'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function workTimeChanges()
    {
        return $this->hasMany(WorkTimeChange::class);
    }

    /**
     * Check if contract is expiring soon (within 7 days)
     */
    public function isContractExpiringSoon()
    {
        if (!$this->tanggal_end_kontrak) {
            return false;
        }

        $daysUntilExpiry = Carbon::now()->diffInDays($this->tanggal_end_kontrak, false);
        return $daysUntilExpiry <= 7 && $daysUntilExpiry >= 0;
    }

    /**
     * Get contract days remaining as attribute
     */
    public function getContractDaysRemainingAttribute()
    {
        if (!$this->tanggal_end_kontrak) {
            return null;
        }

        return Carbon::now()->diffInDays($this->tanggal_end_kontrak, false);
    }

    /**
     * Get formatted contract period
     */
    public function getContractPeriodAttribute()
    {
        if (!$this->tanggal_start_kontrak || !$this->tanggal_end_kontrak) {
            return 'Tidak ada data kontrak';
        }

        return $this->tanggal_start_kontrak->format('d M Y') . ' - ' . $this->tanggal_end_kontrak->format('d M Y');
    }

    /**
     * Calculate work duration from initial contract start date
     */
    public function getWorkDurationAttribute()
    {
        if (!$this->tanggal_mulai_kontrak_awal) {
            return 'Belum ada data';
        }

        $startDate = Carbon::parse($this->tanggal_mulai_kontrak_awal);
        $endDate = Carbon::now();

        $years = $startDate->diffInYears($endDate);
        $months = $startDate->copy()->addYears($years)->diffInMonths($endDate);
        $days = $startDate->copy()->addYears($years)->addMonths($months)->diffInDays($endDate);

        $duration = [];

        if ($years > 0) {
            $duration[] = $years . ' tahun';
        }

        if ($months > 0) {
            $duration[] = $months . ' bulan';
        }

        if ($days > 0) {
            $duration[] = $days . ' hari';
        }

        return !empty($duration) ? implode(', ', $duration) : '0 hari';
    }

    /**
     * Get work duration in detailed format
     */
    public function getDetailedWorkDurationAttribute()
    {
        if (!$this->tanggal_mulai_kontrak_awal) {
            return [
                'years' => 0,
                'months' => 0,
                'days' => 0,
                'total_days' => 0,
                'formatted' => 'Belum ada data'
            ];
        }

        $startDate = Carbon::parse($this->tanggal_mulai_kontrak_awal);
        $endDate = Carbon::now();

        $years = $startDate->diffInYears($endDate);
        $months = $startDate->copy()->addYears($years)->diffInMonths($endDate);
        $days = $startDate->copy()->addYears($years)->addMonths($months)->diffInDays($endDate);
        $totalDays = $startDate->diffInDays($endDate);

        return [
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'total_days' => $totalDays,
            'formatted' => $this->work_duration
        ];
    }

    /**
     * Check if contract is active
     */
    public function isContractActive()
    {
        if (!$this->tanggal_start_kontrak || !$this->tanggal_end_kontrak) {
            return true; // Assume active if no contract dates
        }

        $now = Carbon::now();
        return $now->between($this->tanggal_start_kontrak, $this->tanggal_end_kontrak);
    }

    /**
     * Check if contract has expired
     */
    public function isContractExpired()
    {
        if (!$this->tanggal_end_kontrak) {
            return false;
        }

        return Carbon::now()->gt($this->tanggal_end_kontrak);
    }

    /**
     * Get contract status
     */
    public function getContractStatusAttribute()
    {
        if (!$this->tanggal_end_kontrak) {
            return 'Tidak ada kontrak';
        }

        if ($this->isContractExpired()) {
            return 'Kontrak berakhir';
        }

        if ($this->isContractExpiringSoon()) {
            return 'Akan berakhir';
        }

        return 'Aktif';
    }
}

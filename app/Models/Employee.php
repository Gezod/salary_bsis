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
        'daily_salary',
        'bank_name',
        'account_number',
        'role',
        'tanggal_start_kontrak',
        'tanggal_end_kontrak'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'daily_salary' => 'integer',
        'tanggal_start_kontrak' => 'date',
        'tanggal_end_kontrak' => 'date'
    ];

    // Relasi ke tabel attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi ke tabel payroll
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Relasi ke tabel lembur
    public function overtimeRecords()
    {
        return $this->hasMany(OvertimeRecord::class);
    }

    // Relasi ke tabel cuti
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    // Relasi ke tabel perubahan jam kerja
    public function workTimeChanges()
    {
        return $this->hasMany(WorkTimeChange::class);
    }

    /**
     * Cek apakah kontrak akan segera berakhir (dalam 7 hari)
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
     * Dapatkan sisa hari kontrak sebagai atribut
     */
    public function getContractDaysRemainingAttribute()
    {
        if (!$this->tanggal_end_kontrak) {
            return null;
        }

        return Carbon::now()->diffInDays($this->tanggal_end_kontrak, false);
    }

    /**
     * Format periode kontrak
     */
    public function getContractPeriodAttribute()
    {
        if (!$this->tanggal_start_kontrak || !$this->tanggal_end_kontrak) {
            return 'Tidak ada data kontrak';
        }

        return $this->tanggal_start_kontrak->format('d M Y') . ' - ' . $this->tanggal_end_kontrak->format('d M Y');
    }

    /**
     * Cek apakah kontrak sedang aktif
     */
    public function isContractActive()
    {
        if (!$this->tanggal_start_kontrak || !$this->tanggal_end_kontrak) {
            return true; // Dianggap aktif jika tidak ada tanggal kontrak
        }

        $now = Carbon::now();
        return $now->between($this->tanggal_start_kontrak, $this->tanggal_end_kontrak);
    }

    /**
     * Cek apakah kontrak sudah berakhir
     */
    public function isContractExpired()
    {
        if (!$this->tanggal_end_kontrak) {
            return false;
        }

        return Carbon::now()->gt($this->tanggal_end_kontrak);
    }

    /**
     * Dapatkan status kontrak
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

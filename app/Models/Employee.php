<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'daily_salary'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'daily_salary' => 'integer'
    ];

    /**
     * Relationship with Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relationship with Payroll records
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Relationship with Overtime records
     */
    public function overtimeRecords()
    {
        return $this->hasMany(OvertimeRecord::class);
    }
}

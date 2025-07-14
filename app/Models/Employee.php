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
        'daily_salary',
        'bank_name',
        'account_number',
        'daily_salary'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'daily_salary' => 'integer'
    ];

    // Relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relationship with Payroll
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Relationship with Overtime Records
    public function overtimeRecords()
    {
        return $this->hasMany(OvertimeRecord::class);
    }
}

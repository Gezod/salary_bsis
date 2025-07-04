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
        'kantor'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scope for searching employees
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('pin', 'like', "%{$search}%");
        });
    }

    // Scope for filtering by department
    public function scopeByDepartment($query, $department)
    {
        return $query->where('departemen', $department);
    }

    // Get role display name
    public function getRoleDisplayAttribute()
    {
        return ucfirst($this->departemen);
    }

    // Check if employee is staff
    public function isStaff()
    {
        return $this->departemen === 'staff';
    }

    // Check if employee is karyawan
    public function isKaryawan()
    {
        return $this->departemen === 'karyawan';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

    /**
     * Set default role = karyawan jika tidak ada
     */
    protected $attributes = [
        'role' => 'karyawan',
    ];

    /**
     * Relasi ke attendance
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

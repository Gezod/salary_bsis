<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'nama',
        'departemen',
        'jabatan',
        'alasan_izin',
        'bukti_foto',
        'tanggal_izin',
        'status'
    ];

    protected $casts = [
        'tanggal_izin' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workTimeChanges()
    {
        return $this->hasMany(WorkTimeChange::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkTimeChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_id',
        'employee_id',
        'tanggal',
        'jam_masuk_baru',
        'jam_pulang_baru',
        'jam_istirahat_mulai',
        'jam_istirahat_selesai',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

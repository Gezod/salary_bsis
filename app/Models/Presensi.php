<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $fillable = [
        'username',
        'tanggal_presensi',
        'jam_in',
        'jam_awal_isti',
        'jam_akhir_isti',
        'jam_pulang',
        'jam_denda',
        'jam_lembur',
    ];

    protected $casts = [
        'tanggal_presensi' => 'date',
        'jam_in' => 'datetime:H:i',
        'jam_awal_isti' => 'datetime:H:i',
        'jam_akhir_isti' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
    ];
}

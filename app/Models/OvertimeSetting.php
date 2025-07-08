<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_rate',
        'karyawan_rate',
        'minimum_minutes'
    ];

    protected $casts = [
        'staff_rate' => 'integer',
        'karyawan_rate' => 'integer',
        'minimum_minutes' => 'integer'
    ];

    public static function current()
    {
        return self::first() ?? self::create([
            'staff_rate' => 10000,
            'karyawan_rate' => 15000,
            'minimum_minutes' => 30
        ]);
    }
}

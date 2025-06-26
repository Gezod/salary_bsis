<?php

return [
    'staff' => [
        'late' => [
            [1, 15, 3000],                   // 1–15 menit = 3000
            [16, 30, 6000],                  // 16–30 menit = 6000
            [31, 45, 8000],                  // 31–45 menit = 8000
            [46, 60, 12000],                 // 46–60 menit = 12000
            ['>', 60, fn($m) => 12000 + ($m - 60)] // >60 menit = 12000 + sisa menit
        ],
        'late_break' => 1500,               // Telat istirahat
        'missing_checkin' => 9000,          // Lupa absen masuk
        'missing_checkout' => 3000,         // Lupa absen pulang
        'absent_break_once' => 2000,        // 1x absen istirahat
        'absent_twice' => 3000,             // 2x absen istirahat
    ],

    'karyawan' => [
        'late' => [
            [1, 15, 2000],                   // 1–15 menit = 2000
            [16, 30, 4000],                  // 16–30 menit = 4000
            [31, 45, 6000],                  // 31–45 menit = 6000
            [46, 60, 10000],                 // 46–60 menit = 10000
            ['>', 60, fn($m) => 10000 + ($m - 60)] // >60 menit = 10000 + sisa menit
        ],
        'late_break' => 1000,               // Telat istirahat
        'missing_checkin' => 6000,          // Lupa absen masuk
        'missing_checkout' => 2000,         // Lupa absen pulang
        'absent_break_once' => 1500,        // 1x absen istirahat
        'absent_twice' => 2000,             // 2x absen istirahat
    ],
];

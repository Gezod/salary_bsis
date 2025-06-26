<?php

return [
    'staff' => [
        'late' => [
            // Format: [min, max, fine] atau ['>', batas, fungsi per menit]
            [1, 15, 5000],                 // 1–15 menit: denda 5.000
            [16, 30, 10000],               // 16–30 menit: denda 10.000
            ['>', 30, fn($m) => $m * 1000] // >30 menit: 1.000/menit
        ],
        'late_break' => 10000,           // Jika istirahat berlebihan
        'missing_checkin' => 20000,      // Scan1 kosong
        'missing_checkout' => 20000,     // Scan4 kosong
        'absent_break_once' => 5000,     // Scan2 atau scan3 kosong
        'absent_twice' => 15000,         // Scan2 & scan3 kosong
    ],

    'manager' => [
        'late' => [
            [1, 10, 10000],
            [11, 30, 20000],
            ['>', 30, fn($m) => $m * 2000]
        ],
        'late_break' => 20000,
        'missing_checkin' => 30000,
        'missing_checkout' => 30000,
        'absent_break_once' => 10000,
        'absent_twice' => 25000,
    ],

    // ✅ Tambahan untuk role karyawan
    'karyawan' => [
        'late' => [
            [1, 15, 5000],
            [16, 30, 10000],
            ['>', 30, fn($m) => $m * 1000]
        ],
        'late_break' => 10000,
        'missing_checkin' => 20000,
        'missing_checkout' => 20000,
        'absent_break_once' => 5000,
        'absent_twice' => 15000,
    ],
];

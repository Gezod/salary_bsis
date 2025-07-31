<?php

return [
    'staff' => [
        'late' => [
            [1, 15, 200],  // Rp200 per menit untuk 1-15 menit
            [16, 30, 200], // Rp200 per menit untuk 16-30 menit
            [31, 45, 200], // Rp200 per menit untuk 31-45 menit
            [46, 60, 200], // Rp200 per menit untuk 46-60 menit
            ['>', 60, fn($m) => 12000 + (($m - 60) * 200)] // Rp12.000 + Rp200 per menit setelah 60 menit
        ],
        'late_break' => 1500,
        'missing_checkin' => 9000,
        'missing_checkout' => 3000,
        'absent_break_once' => 2000,
        'absent_twice' => 3000,
    ],

    'karyawan' => [
        'late' => [
            [1, 15, 133.33],  // ~Rp133.33 per menit untuk 1-15 menit (Rp2000/15)
            [16, 30, 133.33], // ~Rp133.33 per menit untuk 16-30 menit
            [31, 45, 133.33], // ~Rp133.33 per menit untuk 31-45 menit
            [46, 60, 166.67], // ~Rp166.67 per menit untuk 46-60 menit (Rp10000/60)
            ['>', 60, fn($m) => 10000 + (($m - 60) * 166.67)] // Rp10.000 + Rp166.67 per menit setelah 60 menit
        ],
        'late_break' => 1000,
        'missing_checkin' => 6000,
        'missing_checkout' => 2000,
        'absent_break_once' => 1500,
        'absent_twice' => 2000,
    ],
];

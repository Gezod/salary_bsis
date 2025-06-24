<?php

namespace App\Imports;

use App\Models\Presensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PresensiImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Presensi::create([
                'username' => $row['username'],
                'tanggal_presensi' => Carbon::parse($row['tanggal_presensi']),
                'jam_in' => $row['jam_in'],
                'jam_awal_isti' => $row['jam_awal_isti'],
                'jam_akhir_isti' => $row['jam_akhir_isti'],
                'jam_pulang' => $row['jam_pulang'],
            ]);
        }
    }
}

<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AttendancesImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 3; // Data dimulai dari baris ke-3
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Ambil data dari indeks berdasarkan urutan kolom
                $pin     = $row[0];
                $nip     = $row[1];
                $nama    = $row[2];
                $tanggal = $row[6];
                $scan1   = $row[7] ?? null;
                $scan2   = $row[8] ?? null;
                $scan3   = $row[9] ?? null;
                $scan4   = $row[10] ?? null;
                $scan5   = $row[11] ?? null;

                // Pastikan tanggal valid
                $tgl = Carbon::createFromFormat('d-m-Y', $tanggal);
                $parse = fn($jam) => $jam ? Carbon::createFromFormat('d-m-Y H:i:s', "$tanggal $jam") : null;

                $employee = Employee::updateOrCreate(
                    ['pin' => $pin],
                    ['nama' => $nama, 'nip' => $nip]
                );

                $att = Attendance::updateOrCreate(
                    ['employee_id' => $employee->id, 'tanggal' => $tgl],
                    [
                        'scan1' => $parse($scan1),
                        'scan2' => $parse($scan2),
                        'scan3' => $parse($scan3),
                        'scan4' => $parse($scan4),
                        'scan5' => $parse($scan5),
                    ]
                );

                AttendanceService::evaluate($att);

                Log::info("✅ Data absensi tersimpan: {$nama} - {$tanggal}");

            } catch (\Throwable $e) {
                Log::error("❌ Gagal import baris: " . $e->getMessage());
            }
        }
    }
}

<?php

namespace App\Imports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PresensiImport implements ToModel
{
    public function model(array $row)
    {
        $tanggal = $this->parseExcelDate($row[1]);

        // Lewati baris jika tanggal presensi tidak valid
        if (is_null($tanggal)) {
            Log::warning("Baris dilewati karena tanggal_presensi kosong atau invalid: " . json_encode($row));
            return null;
        }

        return new Presensi([
            'username'         => $row[0],
            'tanggal_presensi' => $tanggal,
            'jam_in'           => $this->parseExcelDate($row[2]),
            'jam_awal_isti'    => $this->parseExcelDate($row[3]),
            'jam_akhir_isti'   => $this->parseExcelDate($row[4]),
            'jam_pulang'       => $this->parseExcelDate($row[5]),
        ]);
    }

    private function parseExcelDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            } elseif (!empty($value)) {
                return Carbon::parse($value);
            }
            // Jika kosong, return null
            return null;
        } catch (\Exception $e) {
            Log::error("Gagal parsing tanggal: {$value} - " . $e->getMessage());
            return null;
        }
    }

    private function parseNumber($value)
    {
        return is_numeric($value) ? floor((float) $value) : null;
    }
}

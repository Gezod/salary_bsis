<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendancesImport;
use Illuminate\Support\Facades\Log;


class ImportController extends Controller
{
    /**
     * Form upload (GET /absensi/import)
     */
    public function create()
    {
        return view('absensi.import');
    }

    /**
     * Proses unggah & impor Excel (POST /absensi/import)
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:10240', // max 10 MB
        ]);

        try {
            Excel::import(new AttendancesImport, $request->file('file'));
            return redirect()
                ->route('absensi.index')
                ->with('success', 'Import berhasil! Data telah diproses.');
        } catch (\Exception $e) {
            Log::error('Gagal impor absensi: ' . $e->getMessage());
            return back()->withErrors(['msg' => 'Gagal impor file: ' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;

class KaryawanController extends Controller
{
    public function role(Request $request)
    {
        $query = Employee::query();

        // Search functionality - menggunakan logic yang sama dengan API
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('pin', 'like', "%{$search}%");
            });
        }

        // Filter by departemen
        if ($request->filled('departemen')) {
            $query->where('departemen', $request->departemen);
        }

        // Filter by jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', 'like', "%{$request->jabatan}%");
        }

        $employees = $query->orderBy('nama')->paginate(20)->withQueryString();

        // Check for contract expiring soon notifications
        $expiringContracts = Employee::whereNotNull('tanggal_end_kontrak')
            ->whereRaw('tanggal_end_kontrak <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
            ->whereRaw('tanggal_end_kontrak >= CURDATE()')
            ->count();

        return view('absensi.role', compact('employees', 'expiringContracts'));
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:20|unique:employees,pin',
            'nip' => 'required|string|max:50|unique:employees,nip',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|in:staff,karyawan',
            'kantor' => 'required|string|max:100',
            'tanggal_start_kontrak' => 'nullable|date',
            'tanggal_end_kontrak' => 'nullable|date|after:tanggal_start_kontrak',
            'tanggal_mulai_kontrak_awal' => 'nullable|date|before_or_equal:tanggal_start_kontrak',
        ]);

        Employee::create([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
            'tanggal_start_kontrak' => $request->tanggal_start_kontrak,
            'tanggal_end_kontrak' => $request->tanggal_end_kontrak,
            'tanggal_mulai_kontrak_awal' => $request->tanggal_mulai_kontrak_awal,
        ]);

        return redirect()->route('absensi.role')
            ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function roleEdit($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json([
            'success' => true,
            'employee' => $employee
        ]);
    }

    public function roleUpdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'pin' => 'required|string|max:20|unique:employees,pin,' . $id,
            'nip' => 'required|string|max:50|unique:employees,nip,' . $id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|in:staff,karyawan',
            'kantor' => 'required|string|max:100',
            'tanggal_start_kontrak' => 'nullable|date',
            'tanggal_end_kontrak' => 'nullable|date|after:tanggal_start_kontrak',
            'tanggal_mulai_kontrak_awal' => 'nullable|date|before_or_equal:tanggal_start_kontrak',
        ]);

        $employee->update([
            'pin' => $request->pin,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'kantor' => $request->kantor,
            'tanggal_start_kontrak' => $request->tanggal_start_kontrak,
            'tanggal_end_kontrak' => $request->tanggal_end_kontrak,
            'tanggal_mulai_kontrak_awal' => $request->tanggal_mulai_kontrak_awal,
        ]);

        return redirect()->route('absensi.role')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function roleDestroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete related attendance records first
        Attendance::where('employee_id', $id)->delete();

        // Then delete the employee
        $employee->delete();

        return redirect()->route('absensi.role')
            ->with('success', 'Karyawan dan semua data absensi terkait berhasil dihapus!');
    }

    /**
     * Show employee details with work duration
     */
    public function roleShow($id)
    {
        $employee = Employee::findOrFail($id);
        $workDuration = $employee->detailed_work_duration;

        return response()->json([
            'success' => true,
            'employee' => $employee,
            'work_duration' => $workDuration
        ]);
    }
}

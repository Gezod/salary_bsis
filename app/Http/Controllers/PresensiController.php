<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PresensiImport;


class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Presensi::query();

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal_presensi', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal_presensi', $request->year);
        }

        $presensis = $query->orderBy('tanggal_presensi', 'desc')->paginate(10);

        return view('presensi.index', compact('presensis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presensi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'tanggal_presensi' => 'required|date',
            'jam_in' => 'required',
            'jam_awal_isti' => 'nullable',
            'jam_akhir_isti' => 'nullable',
            'jam_pulang' => 'required',
        ]);

        Presensi::create($request->all());

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        return view('presensi.edit', compact('presensi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jam_denda' => 'nullable|integer|min:0',
            'jam_lembur' => 'nullable|integer|min:0',
        ]);

        $presensi = Presensi::findOrFail($id);
        $presensi->update([
            'jam_denda' => $request->jam_denda,
            'jam_lembur' => $request->jam_lembur,
        ]);

        return redirect()->route('presensi.index')->with('success', 'Denda dan lembur berhasil disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PresensiImport, $request->file('file'));

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil diimpor.');
    }
}

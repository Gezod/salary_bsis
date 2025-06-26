@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Rekap Denda Bulan {{ \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y') }}</h1>

<form method="GET" class="mb-4">
    <label for="month" class="mr-2">Pilih Bulan:</label>
    <input type="month" name="month" value="{{ $month }}" class="border p-1 rounded">
    <button class="bg-blue-600 text-white px-3 py-1 rounded">Tampilkan</button>
</form>

<table class="table-auto w-full text-sm border">
    <thead class="bg-gray-200">
        <tr>
            <th>Nama</th>
            <th>Departemen</th>
            <th>Total Denda (Rp)</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($data as $d)
        <tr>
            <td>{{ $d->employee->nama }}</td>
            <td>{{ $d->employee->departemen }}</td>
            <td class="text-right">{{ number_format($d->fine, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection

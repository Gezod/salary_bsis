@extends('layouts.app')

@section('title', 'Data Presensi')

@section('content')
<div class="flex min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    {{-- SIDEBAR --}}
    <aside class="w-64 min-h-screen bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-100 border-r border-gray-300 dark:border-gray-700">
        <div class="p-6 text-center border-b border-gray-300 dark:border-gray-600">
            <h2 class="text-lg font-semibold">Presensi Karyawan</h2>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('presensi.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700 {{ request()->routeIs('presensi.index') ? 'bg-gray-300 dark:bg-gray-700 font-bold' : '' }}">
                📋 Data Presensi
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                📊 Rekap Bulanan
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                ⚙️ Pengaturan
            </a>
        </nav>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 p-6 overflow-x-auto">
        <h3 class="text-2xl font-bold mb-6">Data Presensi</h3>

        {{-- Form Pencarian Username, Bulan, Tahun --}}
        <form method="GET" action="{{ route('presensi.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
            {{-- Username --}}
            <div>
                <label for="search" class="block mb-1 text-sm font-medium">Username</label>
                <input type="text" name="search" id="search"
                       class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                       placeholder="Cari username..." value="{{ request('search') }}">
            </div>

            {{-- Bulan --}}
            <div>
                <label for="month" class="block mb-1 text-sm font-medium">Bulan</label>
                <select name="month" id="month"
                        class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div>
                <label for="year" class="block mb-1 text-sm font-medium">Tahun</label>
                <select name="year" id="year"
                        class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                    <option value="">-- Semua Tahun --</option>
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Tombol Cari --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-transparent">Cari</label>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    🔍 Cari
                </button>
            </div>
        </form>

        {{-- Form Upload Excel --}}
        <form action="{{ route('presensi.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-4 mb-6">
            @csrf
            <input type="file" name="file"
                   class="block w-64 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500"
                   required accept=".xlsx,.xls" />
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                Upload Excel
            </button>
        </form>

        {{-- Tabel Data Presensi --}}
        <div class="table-responsive">
            <table class="table-auto w-full text-sm text-center border-collapse">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="border border-white px-4 py-2">Username</th>
                        <th class="border border-white px-4 py-2">Tanggal</th>
                        <th class="border border-white px-4 py-2">Jam In</th>
                        <th class="border border-white px-4 py-2">Istirahat Mulai</th>
                        <th class="border border-white px-4 py-2">Istirahat Selesai</th>
                        <th class="border border-white px-4 py-2">Jam Pulang</th>
                        <th class="border border-white px-4 py-2">Denda</th>
                        <th class="border border-white px-4 py-2">Lembur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensis as $data)
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <td class="border border-white px-4 py-2">{{ $data->username }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->tanggal_presensi->format('Y-m-d') }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_in->format('H:i') }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_awal_isti?->format('H:i') }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_akhir_isti?->format('H:i') }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_pulang->format('H:i') }}</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_denda ?? '-' }} menit</td>
                            <td class="border border-white px-4 py-2">{{ $data->jam_lembur ?? '-' }} menit</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-white px-4 py-3 text-center text-red-500 font-semibold">
                                Belum ada data presensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $presensis->appends(request()->query())->links() }}
        </div>
    </main>
</div>
@endsection

@push('scripts')
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
@endif
@endpush

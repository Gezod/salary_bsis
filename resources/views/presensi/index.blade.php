@extends('layouts.app')

@section('title', 'Data Presensi')

@section('content')
    <h3>Data Presensi</h3>

    {{-- Form Pencarian Username --}}
    <form method="GET" action="{{ route('presensi.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Cari username..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">Cari</button>
        </div>
    </form>

    {{-- Form Upload Excel --}}
    <form action="{{ route('presensi.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">Upload Excel</button>
            </div>
        </div>
    </form>

    {{-- Tombol Tambah Presensi Manual --}}
    <a href="{{ route('presensi.create') }}" class="btn btn-primary mb-3">+ Tambah Presensi</a>

    {{-- Tabel Data Presensi --}}
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Username</th>
                <th>Tanggal</th>
                <th>Jam In</th>
                <th>Jam Istirahat Mulai</th>
                <th>Jam Istirahat Selesai</th>
                <th>Jam Pulang</th>
                <th>Denda</th>
                <th>Lembur</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensis as $data)
                <tr>
                    <td>{{ $data->username }}</td>
                    <td>{{ $data->tanggal_presensi->format('Y-m-d') }}</td>
                    <td>{{ $data->jam_in->format('H:i') }}</td>
                    <td>{{ $data->jam_awal_isti?->format('H:i') }}</td>
                    <td>{{ $data->jam_akhir_isti?->format('H:i') }}</td>
                    <td>{{ $data->jam_pulang->format('H:i') }}</td>
                    <td>{{ $data->jam_denda ?? '-' }} menit</td>
                    <td>{{ $data->jam_lembur ?? '-' }} menit</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data presensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $presensis->appends(request()->query())->links() }}
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

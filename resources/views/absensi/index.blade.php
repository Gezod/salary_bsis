@extends('layouts.app')

@section('content')
    <style>
        ::placeholder {
            color: #cbd5e1 !important;
            opacity: 1;
        }

        .nav-link.active-link {
            background-color: rgba(255, 255, 255, 0.15);
            font-weight: bold;
            border-radius: 0.35rem;
        }
    </style>

    <div class="container-fluid bg-dark text-white min-vh-100 px-0">
        <div class="row g-0">
            {{-- Sidebar --}}
            <nav class="col-md-2 d-none d-md-block sidebar border-end" style="background-color: #374151;">
                <div class="position-sticky pt-3 text-white">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah" class="img-fluid rounded-circle" style="max-width: 100px;">
                        <h6 class="mt-2">Bank Sampah</h6>
                    </div>
                    <ul class="nav flex-column px-2">
    <li class="nav-item mb-2">
        <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active-link text-white' : 'text-white' }}"
            href="{{ route('absensi.index') }}">
            <i class="bi bi-calendar-check me-2"></i> Absensi Harian
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link {{ request()->routeIs('absensi.recap') ? 'active-link text-white' : 'text-white' }}"
            href="{{ route('absensi.recap') }}">
            <i class="bi bi-file-earmark-text me-2"></i> Rekap Denda
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('absensi.import') ? 'active-link text-white' : 'text-white' }}"
            href="{{ route('absensi.import') }}">
            <i class="bi bi-upload me-2"></i> Import Absensi
        </a>
    </li>
</ul>

                </div>
            </nav>

            {{-- Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                <nav class="navbar navbar-expand-lg" style="background-color: #1e293b;">
                    <div class="container-fluid">
                        <span class="navbar-brand fw-semibold text-white">Sistem Absensi</span>
                        <div class="ms-auto d-flex align-items-center">
                            <span class="me-3 text-light">👋 Halo, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </div>
                    </div>
                </nav>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                    <h1 class="h4 text-white">Data Absensi Harian</h1>
                </div>

                {{-- Filter Form --}}
                <form method="GET" class="row g-2 mb-4">
                    <div class="col-md-3">
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="form-control bg-dark text-white border-secondary" placeholder="Tanggal">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="employee" value="{{ request('employee') }}"
                            class="form-control bg-dark text-white border-secondary" placeholder="Cari nama karyawan...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-info w-100">Filter</button>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-striped table-sm align-middle text-nowrap">
                        <thead class="text-white text-center" style="background-color: #4b5563;">
                            <tr>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Istirahat Mulai</th>
                                <th>Istirahat Selesai</th>
                                <th>Pulang</th>
                                <th>Telat (mnt)</th>
                                <th class="text-end">Denda (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $a)
                                @php
                                    $employee = $a->employee;
                                    $isLate = ($a->late_minutes ?? 0) > 0;
                                    $noCheckIn = !$a->scan1;
                                    $noCheckOut = !$a->scan4;
                                @endphp
                                <tr>
                                    <td class="text-white">{{ $employee->nama ?? '-' }}</td>
                                    <td class="text-white">{{ $employee->departemen ?? '-' }}</td>
                                    <td class="text-white">{{ $a->tanggal->format('d-m-Y') }}</td>

                                    {{-- Masuk --}}
                                    <td class="{{ $isLate || $noCheckIn ? 'bg-warning text-dark' : 'text-white' }}">
                                        {{ $a->getFormattedScan('scan1') }}
                                    </td>

                                    {{-- Istirahat Mulai --}}
                                    <td class="text-white">
                                        {{ $a->getFormattedScan('scan2') }}
                                    </td>

                                    {{-- Istirahat Selesai --}}
                                    <td class="text-white">
                                        {{ $a->getFormattedScan('scan3') }}
                                    </td>

                                    {{-- Pulang --}}
                                    <td class="{{ $noCheckOut ? 'bg-warning text-dark' : 'text-white' }}">
                                        {{ $a->getFormattedScan('scan4') }}
                                    </td>

                                    {{-- Telat --}}
                                    <td class="text-center {{ $isLate ? 'bg-warning text-dark' : 'text-white' }}">
                                        {{ $a->late_minutes ?? '-' }}
                                    </td>

                                    {{-- Denda --}}
                                    <td class="text-end text-white">
                                        {{ $a->total_fine ? number_format($a->total_fine, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($rows->hasPages())
                    <div class="mt-4">
                        {{ $rows->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection

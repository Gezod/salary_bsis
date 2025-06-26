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
                <h1 class="h5 text-white">
                    Rekap Denda Bulan {{ \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y') }}
                </h1>
            </div>

            {{-- Filter --}}
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-3">
                    <input type="month" name="month" value="{{ $month }}"
                        class="form-control bg-dark text-white border-secondary" placeholder="Pilih Bulan">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-info w-100">Tampilkan</button>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-striped table-sm align-middle text-nowrap">
                    <thead class="text-white text-center" style="background-color: #4b5563;">
                        <tr>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th class="text-end">Total Denda (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $d)
                            <tr>
                                <td class="text-white">{{ $d->employee->nama ?? '-' }}</td>
                                <td class="text-white">{{ $d->employee->departemen ?? '-' }}</td>
                                <td class="text-end text-white">{{ number_format($d->fine, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Tidak ada data rekap bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
@endsection

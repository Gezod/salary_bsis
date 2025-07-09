@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style_index.css') }}" rel="stylesheet">

<div class="container-fluid min-vh-100 px-0">
    <div class="row g-0">
        {{-- Sidebar --}}
        <nav class="col-md-2 sidebar">
            <div class="position-sticky pt-4 px-3">
                <div class="logo-container text-center">
                    <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                        alt="Bank Sampah" class="img-fluid sidebar-logo mb-3">
                    <small class="text-muted">Sistem Lembur</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active-link" href="{{ route('overtime.overview') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <span>Overview</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.index') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <span>Data Lembur</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.settings') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.recap') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <span>Rekap</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('absensi.index') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-arrow-left"></i>
                                </div>
                                <span>Kembali ke Absensi</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="col-md-10 ms-sm-auto px-md-4">
            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg sticky-top">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3">
                            <i class="bi bi-speedometer2 text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Overview Lembur</span>
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="me-4 text-light">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="py-4 animate-fade-in">
                {{-- Page Header --}}
                <div class="mb-4">
                    <h1 class="page-title mb-2">Dashboard Lembur</h1>
                    <p class="text-muted mb-0">Pantau statistik dan aktivitas lembur karyawan</p>
                </div>

                {{-- Statistics Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Total Lembur</h6>
                                        <h3 class="mb-0">{{ $stats['total_records'] }} hari</h3>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="bi bi-calendar-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-gradient-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Total Jam Lembur</h6>
                                        <h3 class="mb-0">
                                            @php
                                                $hours = floor($stats['total_minutes'] / 60);
                                                $minutes = $stats['total_minutes'] % 60;
                                            @endphp
                                            {{ $hours }}j {{ $minutes }}m
                                        </h3>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="bi bi-clock fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-gradient-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Total Uang Lembur</h6>
                                        <h3 class="mb-0">Rp {{ number_format($stats['total_pay'], 0, ',', '.') }}</h3>
                                    </div>
                                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                        <i class="bi bi-currency-dollar fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- Department Statistics --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-people me-2"></i>Statistik Departemen
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($stats['total_records'] > 0)
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-primary bg-opacity-15 rounded border border-primary border-opacity-25">
                                                <h4 class="text-light mb-1">{{ $stats['staff_count'] }}</h4>
                                                <small class="text-white">Staff Lembur</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-success bg-opacity-15 rounded border border-success border-opacity-25">
                                                <h4 class="text-light mb-1">{{ $stats['karyawan_count'] }}</h4>
                                                <small class="text-white">Karyawan Lembur</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <small class="text-primary">
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    {{ $stats['staff_count'] }} Staff
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-success">
                                                    <i class="bi bi-person-check me-1"></i>
                                                    {{ $stats['karyawan_count'] }} Karyawan
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Rata-rata lembur:
                                            @php
                                                $avgHours = floor($stats['avg_overtime_minutes'] / 60);
                                                $avgMins = $stats['avg_overtime_minutes'] % 60;
                                            @endphp
                                            {{ $avgHours }}j {{ $avgMins }}m
                                        </small>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-people display-6 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada data statistik departemen</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Recent Overtime --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-clock-history me-2"></i>Lembur Terbaru
                                </h5>
                            </div>
                            <div class="card-body">
                                @forelse($recentRecords as $record)
                                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-dark bg-opacity-25 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 35px; height: 35px;">
                                                <span class="text-white fw-bold small">
                                                    {{ substr($record->employee->nama, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-white fw-semibold small">{{ $record->employee->nama }}</div>
                                                <small class="text-muted">{{ $record->formatted_date_with_day }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-white fw-semibold small">{{ $record->formatted_duration }}</div>
                                            <small class="text-success">{{ $record->formatted_overtime_pay }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-clock display-6 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada data lembur</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

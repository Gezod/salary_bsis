@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_recap.css') }}" rel="stylesheet">
    <div class="container-fluid min-vh-100 px-0">
        <div class="row g-0">
            {{-- Enhanced Sidebar --}}
            <nav class="col-md-2 sidebar">
                <div class="position-sticky pt-4 px-3">
                    <div class="logo-container text-center">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah" class="img-fluid sidebar-logo mb-3">
                        <small class="text-muted">Sistem Absensi</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active-link' : '' }}"
                                href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <span>Absensi Harian</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.recap') ? 'active-link' : '' }}"
                                href="{{ route('absensi.recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <span>Rekap Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.late-recap') ? 'active-link' : '' }}"
                                href="{{ route('absensi.late-recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Rekap Keterlambatan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.import') ? 'active-link' : '' }}"
                                href="{{ route('absensi.import') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-upload"></i>
                                    </div>
                                    <span>Import Absensi</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.leave.*') ? 'active-link' : '' }}"
                                href="{{ route('absensi.leave.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span>Rekap Manual Izin</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.work_time_change.*') ? 'active-link' : '' }}"
                                href="{{ route('absensi.work_time_change.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Pergantian Jam Kerja</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.manual') ? 'active-link' : '' }}"
                                href="{{ route('absensi.manual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <span>Manual Presensi</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.denda') ? 'active-link' : '' }}"
                                href="{{ route('absensi.denda') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <span>Pengaturan Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.role') ? 'active-link' : '' }}"
                                href="{{ route('absensi.role') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Kelola Karyawan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('overtime.*') ? 'active-link' : '' }}"
                                href="{{ route('overtime.overview') }}">
                                <div class="d-flex align-items-center">
                                    <div class="overtime-icon-wrapper me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <span>Sistem Lembur</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active-link' : '' }}"
                                href="{{ route('payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <span>Sistem Payroll</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Enhanced Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                {{-- Enhanced Navbar --}}
                <nav class="navbar navbar-expand-lg sticky-top">
                    <div class="container-fluid">
                        <button class="btn btn-primary me-3 d-md-none" type="button" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Sistem Absensi</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="theme-toggle me-3" onclick="toggleTheme()">
                                <i class="bi" id="theme-icon"></i>
                            </div>
                            <div class="me-4 user-info">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="page-title mb-2">Rekap Keterlambatan Karyawan</h1>
                            <p class="text-muted mb-0">
                                Laporan keterlambatan bulan
                                {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">{{ count($lateData) }}</div>
                            <small class="text-muted">Karyawan Terlambat</small>
                        </div>
                    </div>

                    {{-- Summary Cards --}}
                    <div class="summary-cards">
                        <div class="summary-card total">
                            <div class="icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3 class="text-white mb-1">{{ count($lateData) }}</h3>
                            <p class="text-muted mb-0 small">Karyawan Terlambat</p>
                        </div>
                        <div class="summary-card amount">
                            <div class="icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h3 class="text-white mb-1">
                                @php
                                    $totalMinutes = $lateData->sum('total_late_minutes');
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp
                                {{ $hours }}j {{ $minutes }}m
                            </h3>
                            <p class="text-muted mb-0 small">Total Durasi Terlambat</p>
                        </div>
                        <div class="summary-card employees">
                            <div class="icon">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h3 class="text-white mb-1">{{ $lateData->sum('late_count') }}</h3>
                            <p class="text-muted mb-0 small">Total Kejadian Terlambat</p>
                        </div>
                    </div>

                    {{-- Department Statistics --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-people me-2"></i>Statistik Staff
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div
                                                class="text-center p-3 bg-primary bg-opacity-15 rounded border border-primary border-opacity-25">
                                                <h4 class="text-light mb-1">{{ $departmentStats['staff']['count'] }}</h4>
                                                <small class="text-white">Staff Terlambat</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div
                                                class="text-center p-3 bg-warning bg-opacity-15 rounded border border-warning border-opacity-25">
                                                <h4 class="text-light mb-1">
                                                    {{ $departmentStats['staff']['total_occurrences'] }}</h4>
                                                <small class="text-white">Total Kejadian</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        @php
                                            $staffHours = floor($departmentStats['staff']['total_minutes'] / 60);
                                            $staffMins = $departmentStats['staff']['total_minutes'] % 60;
                                        @endphp
                                        <h6 class="text-white">Total Durasi: {{ $staffHours }}j {{ $staffMins }}m
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-person-check me-2"></i>Statistik Karyawan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div
                                                class="text-center p-3 bg-success bg-opacity-15 rounded border border-success border-opacity-25">
                                                <h4 class="text-light mb-1">{{ $departmentStats['karyawan']['count'] }}
                                                </h4>
                                                <small class="text-white">Karyawan Terlambat</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div
                                                class="text-center p-3 bg-warning bg-opacity-15 rounded border border-warning border-opacity-25">
                                                <h4 class="text-light mb-1">
                                                    {{ $departmentStats['karyawan']['total_occurrences'] }}</h4>
                                                <small class="text-white">Total Kejadian</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        @php
                                            $karyawanHours = floor($departmentStats['karyawan']['total_minutes'] / 60);
                                            $karyawanMins = $departmentStats['karyawan']['total_minutes'] % 60;
                                        @endphp
                                        <h6 class="text-white">Total Durasi: {{ $karyawanHours }}j {{ $karyawanMins }}m
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Filter Section --}}
                    <div class="filter-section">
                        <h5 class="text-white mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Periode
                        </h5>
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Pilih Bulan</label>
                                <input type="month" name="month" value="{{ $month }}" class="form-control">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Enhanced Table --}}
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Nama Karyawan</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th><i class="bi bi-exclamation-triangle me-2"></i>Jumlah Terlambat</th>
                                        <th><i class="bi bi-clock-history me-2"></i>Total Durasi</th>
                                        <th><i class="bi bi-calculator me-2"></i>Rata-rata per Kejadian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lateData as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($data->employee->nama ?? 'N', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">
                                                            {{ $data->employee->nama ?? '-' }}</div>
                                                        <small class="text-muted">ID:
                                                            {{ $data->employee->id ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($data->employee->departemen ?? '-') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $data->late_count }}
                                                    kali</span>
                                            </td>
                                            <td class="text-white">
                                                @php
                                                    $hours = floor($data->total_late_minutes / 60);
                                                    $minutes = $data->total_late_minutes % 60;
                                                @endphp
                                                {{ $hours }}j {{ $minutes }}m
                                            </td>
                                            <td class="text-white">
                                                @php
                                                    $avgMinutes = round($data->total_late_minutes / $data->late_count);
                                                @endphp
                                                {{ $avgMinutes }} menit
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-check-circle display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada keterlambatan</h5>
                                                    <p>Semua karyawan tepat waktu pada bulan ini</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Toggle sidebar
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                this.classList.remove('show');
            });
        });
    </script>
@endsection

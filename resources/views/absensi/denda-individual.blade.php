@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_index.css') }}" rel="stylesheet">

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
                            <a class="nav-link {{ request()->routeIs('absensi.denda.individual') ? 'active-link' : '' }}"
                                href="{{ route('absensi.denda.individual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-richtext-fill"></i>
                                    </div>
                                    <span>Denda Individu</span>
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
                                <i class="bi bi-speedometer2"></i>
                            </div>
                            <span class="navbar-brand fw-bold mb-0">Sistem Denda</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="theme-toggle" onclick="toggleTheme()">
                                <i class="bi bi-moon-fill"></i>
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
                            <h1 class="page-title mb-2">Laporan Denda Individual</h1>
                            <p class="mb-0 text-muted">
                                Laporan denda per karyawan bulan
                                {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold fs-4 text-primary">{{ count($penaltyData) }}</div>
                            <small class="text-muted">Total Karyawan</small>
                        </div>
                    </div>

                    {{-- Department Statistics --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Staff</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-primary">{{ $departmentStats['staff']['total_employees'] }}
                                            </h4>
                                            <small class="text-muted">Total Karyawan</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-danger">Rp
                                                {{ number_format($departmentStats['staff']['total_penalty'], 0, ',', '.') }}
                                            </h4>
                                            <small class="text-muted">Total Denda</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <span
                                                class="text-warning">{{ $departmentStats['staff']['total_late_days'] }}</span>
                                            <small class="d-block text-muted">Hari Terlambat</small>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-info">Rp
                                                {{ number_format($departmentStats['staff']['avg_penalty'], 0, ',', '.') }}</span>
                                            <small class="d-block text-muted">Rata-rata Denda</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Karyawan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h4 class="text-success">{{ $departmentStats['karyawan']['total_employees'] }}
                                            </h4>
                                            <small class="text-muted">Total Karyawan</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-danger">Rp
                                                {{ number_format($departmentStats['karyawan']['total_penalty'], 0, ',', '.') }}
                                            </h4>
                                            <small class="text-muted">Total Denda</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <span
                                                class="text-warning">{{ $departmentStats['karyawan']['total_late_days'] }}</span>
                                            <small class="d-block text-muted">Hari Terlambat</small>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-info">Rp
                                                {{ number_format($departmentStats['karyawan']['avg_penalty'], 0, ',', '.') }}</span>
                                            <small class="d-block text-muted">Rata-rata Denda</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Filter Section --}}
                    <div class="filter-section">
                        <h5 class="mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Data
                        </h5>
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label small">Pilih Bulan</label>
                                        <input type="month" name="month" value="{{ $month }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search me-2"></i>Filter
                                        </button>
                                    </div>
                                    <div class="col-md-3 ms-auto">
                                        <a href="{{ route('absensi.denda.export-all-pdf', ['month' => $month]) }}"
                                            class="btn btn-danger w-100">
                                            <i class="bi bi-file-pdf me-2"></i>Export PDF
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Table --}}
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Nama Karyawan</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th class="text-center"><i class="bi bi-calendar-check me-2"></i>Total Hari</th>
                                        <th class="text-center"><i class="bi bi-clock-history me-2"></i>Hari Terlambat
                                        </th>
                                        <th class="text-center"><i class="bi bi-stopwatch me-2"></i>Total Menit Telat</th>
                                        <th class="text-center"><i class="bi bi-graph-up me-2"></i>Rata-rata Telat</th>
                                        <th class="text-end"><i class="bi bi-exclamation-triangle me-2"></i>Denda Telat
                                        </th>
                                        <th class="text-end"><i class="bi bi-cup-hot me-2"></i>Denda Istirahat</th>
                                        <th class="text-end"><i class="bi bi-person-x me-2"></i>Denda Absen</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-2"></i>Total Denda</th>
                                        <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penaltyData as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-{{ $data->employee->departemen === 'staff' ? 'primary' : 'success' }} rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($data->employee->nama ?? 'N', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $data->employee->nama ?? '-' }}</div>
                                                        <small class="text-muted">ID:
                                                            {{ $data->employee->id ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $data->employee->departemen === 'staff' ? 'primary' : 'success' }}">
                                                    {{ ucfirst($data->employee->departemen ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $data->total_days }}</td>
                                            <td class="text-center">
                                                @if ($data->late_days > 0)
                                                    <span class="badge bg-warning">{{ $data->late_days }}</span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($data->total_late_minutes > 0)
                                                    <span class="text-danger">{{ $data->total_late_minutes }} menit</span>
                                                    <small class="d-block text-muted">
                                                        @
                                                        Rp{{ number_format($data->total_late_fine > 0 ? round($data->total_late_fine / $data->total_late_minutes) : 0, 0, ',', '.') }}/menit
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($data->avg_late_minutes > 0)
                                                    <span
                                                        class="text-warning">{{ number_format($data->avg_late_minutes, 1) }}
                                                        menit</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($data->total_late_fine > 0)
                                                    <span class="text-danger">Rp
                                                        {{ number_format($data->total_late_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($data->total_break_fine > 0)
                                                    <span class="text-warning">Rp
                                                        {{ number_format($data->total_break_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($data->total_absence_fine > 0)
                                                    <span class="text-info">Rp
                                                        {{ number_format($data->total_absence_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($data->total_penalty > 0)
                                                    <span class="fw-bold text-danger">
                                                        Rp {{ number_format($data->total_penalty, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('absensi.denda.employee-detail', ['employee' => $data->employee_id, 'month' => $month]) }}"
                                                        class="btn btn-outline-info" title="Detail">
                                                        <i class="bi bi-eye fs-3"></i>
                                                    </a>
                                                    <a href="{{ route('absensi.denda.export-individual-pdf', ['employee' => $data->employee_id, 'month' => $month]) }}"
                                                        class="btn btn-outline-danger" title="Export PDF">
                                                        <i class="bi bi-file-pdf fs-3"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data denda untuk bulan yang dipilih</p>
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

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            // Update toggle icon based on current theme
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }

            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                this.classList.remove('show');
            });
        });

        // Theme toggle function
        function toggleTheme() {
            const currentTheme = document.body.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update toggle icon
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }
    </script>
@endsection

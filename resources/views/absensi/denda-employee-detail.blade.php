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
                            <span class="navbar-brand fw-bold mb-0">Detail Denda Karyawan</span>
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
                            <h1 class="page-title mb-2">Detail Denda - {{ $employee->nama }}</h1>
                            <p class="mb-0 text-muted">
                                Periode: {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('absensi.denda.individual', ['month' => $month]) }}"
                                class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <a href="{{ route('absensi.denda.export-individual-pdf', ['employee' => $employee->id, 'month' => $month]) }}"
                                class="btn btn-danger">
                                <i class="bi bi-file-pdf me-2"></i>Export PDF
                            </a>
                        </div>
                    </div>

                    {{-- Employee Info Card --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="mb-3 d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-primary"></i>
                                        Informasi Karyawan
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td class="fw-semibold">Nama:</td>
                                                    <td>{{ $employee->nama }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">NIP:</td>
                                                    <td>{{ $employee->nip }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">PIN:</td>
                                                    <td>{{ $employee->pin }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td class="fw-semibold">Departemen:</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $employee->departemen === 'staff' ? 'primary' : 'success' }}">
                                                            {{ ucfirst($employee->departemen) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Jabatan:</td>
                                                    <td>{{ $employee->jabatan }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Kantor:</td>
                                                    <td>{{ $employee->kantor }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 border-start">
                                    <div class="text-center">
                                        <div class="bg-{{ $employee->departemen === 'staff' ? 'primary' : 'success' }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                            style="width: 80px; height: 80px;">
                                            <span class="text-white fw-bold fs-2">
                                                {{ substr($employee->nama, 0, 1) }}
                                            </span>
                                        </div>
                                        <h3 class="text-danger">Rp
                                            {{ number_format($summary['total_penalty'], 0, ',', '.') }}</h3>
                                        <small class="text-muted">Total Denda Bulan Ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Statistics --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h4 class="text-primary">{{ $summary['total_days'] }}</h4>
                                    <small class="text-muted">Total Hari Kerja</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h4 class="text-warning">{{ $summary['late_days'] }}</h4>
                                    <small class="text-muted">Hari Terlambat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <h4 class="text-danger">{{ $summary['total_late_minutes'] }}</h4>
                                    <small class="text-muted">Total Menit Telat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h4 class="text-info">{{ number_format($summary['avg_late_minutes'], 1) }}</h4>
                                    <small class="text-muted">Rata-rata Telat/Hari</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Penalty Breakdown --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Denda Keterlambatan</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="text-danger">Rp
                                        {{ number_format($summary['total_late_fine'], 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="bi bi-cup-hot me-2"></i>Denda Istirahat</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="text-warning">Rp
                                        {{ number_format($summary['total_break_fine'], 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-person-x me-2"></i>Denda Absensi</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h4 class="text-info">Rp
                                        {{ number_format($summary['total_absence_fine'], 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Attendance Table --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 d-flex align-items-center">
                                <i class="bi bi-calendar-week me-2"></i>
                                Detail Absensi Harian
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                        <th><i class="bi bi-calendar3 me-2"></i>Hari</th>
                                        <th><i class="bi bi-info-circle me-2"></i>Status</th>
                                        <th><i class="bi bi-door-open me-2"></i>Masuk</th>
                                        <th><i class="bi bi-door-closed me-2"></i>Pulang</th>
                                        <th class="text-center"><i class="bi bi-stopwatch me-2"></i>Telat (menit)</th>
                                        <th class="text-end"><i class="bi bi-exclamation-triangle me-2"></i>Denda Telat
                                        </th>
                                        <th class="text-end"><i class="bi bi-cup-hot me-2"></i>Denda Istirahat</th>
                                        <th class="text-end"><i class="bi bi-person-x me-2"></i>Denda Absen</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-2"></i>Total Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendanceData as $attendance)
                                        <tr>
                                            <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->tanggal->translatedFormat('l') }}</td>
                                            <td>
                                                @if ($attendance->is_half_day)
                                                    <span class="badge bg-info">Setengah Hari</span>
                                                @elseif(!$attendance->scan1)
                                                    <span class="badge bg-danger">Tidak Hadir</span>
                                                @elseif($attendance->late_minutes > 0)
                                                    <span class="badge bg-warning">Terlambat</span>
                                                @else
                                                    <span class="badge bg-success">Tepat Waktu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->scan1)
                                                    <span class="@if ($attendance->late_minutes > 0) text-danger @endif">
                                                        {{ $attendance->scan1->format('H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->scan4)
                                                    {{ $attendance->scan4->format('H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($attendance->late_minutes > 0)
                                                    <span class="badge bg-danger">{{ $attendance->late_minutes }}</span>
                                                    <small class="d-block">
                                                        @
                                                        Rp{{ number_format($attendance->late_fine > 0 ? round($attendance->late_fine / $attendance->late_minutes) : 0, 0, ',', '.') }}/menit
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($attendance->late_fine > 0)
                                                    <span class="text-danger">Rp
                                                        {{ number_format($attendance->late_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($attendance->break_fine > 0)
                                                    <span class="text-warning">Rp
                                                        {{ number_format($attendance->break_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($attendance->absence_fine > 0)
                                                    <span class="text-info">Rp
                                                        {{ number_format($attendance->absence_fine, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($attendance->total_fine > 0)
                                                    <span class="fw-bold text-danger">
                                                        Rp {{ number_format($attendance->total_fine, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data absensi untuk periode yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($attendanceData->count() > 0)
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="6">TOTAL</th>
                                            <th class="text-end">Rp
                                                {{ number_format($summary['total_late_fine'], 0, ',', '.') }}</th>
                                            <th class="text-end">Rp
                                                {{ number_format($summary['total_break_fine'], 0, ',', '.') }}</th>
                                            <th class="text-end">Rp
                                                {{ number_format($summary['total_absence_fine'], 0, ',', '.') }}</th>
                                            <th class="text-end fw-bold text-danger">Rp
                                                {{ number_format($summary['total_penalty'], 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                @endif
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

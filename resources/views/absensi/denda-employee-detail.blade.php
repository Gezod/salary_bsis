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
                            <a class="nav-link" href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <span>Absensi Harian</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.denda') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <span>Pengaturan Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.denda.individual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                    <span>Denda Individual</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.role') }}">
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

            {{-- Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                {{-- Navbar --}}
                <nav class="navbar navbar-expand-lg sticky-top">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-person-circle text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Detail Denda - {{ $employee->nama }}</span>
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
                            <h1 class="page-title mb-2">Detail Denda Karyawan</h1>
                            <p class="text-muted mb-0">{{ $employee->nama }} - {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('absensi.denda.individual', ['month' => $month]) }}" class="btn btn-outline-info">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <a href="{{ route('absensi.denda.export.individual', ['id' => $employee->id, 'month' => $month]) }}" class="btn btn-danger">
                                <i class="bi bi-file-pdf me-2"></i>Export PDF
                            </a>
                        </div>
                    </div>

                    {{-- Employee Info Card --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                            style="width: 80px; height: 80px;">
                                            <span class="text-white fw-bold fs-2">
                                                {{ substr($employee->nama, 0, 1) }}
                                            </span>
                                        </div>
                                        <h5 class="text-white">{{ $employee->nama }}</h5>
                                        <span class="badge {{ $employee->departemen === 'staff' ? 'bg-primary' : 'bg-success' }}">
                                            {{ ucfirst($employee->departemen) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-white fw-bold">NIP</label>
                                                <div class="text-white">{{ $employee->nip }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-white fw-bold">Jabatan</label>
                                                <div class="text-white">{{ $employee->jabatan }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-white fw-bold">PIN</label>
                                                <div class="text-white">{{ $employee->pin }}</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-white fw-bold">Kantor</label>
                                                <div class="text-white">{{ $employee->kantor }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Statistics --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info">
                                <div class="card-body text-center">
                                    <div class="fw-bold fs-3 text-white">{{ $summary['total_days'] }}</div>
                                    <div class="text-white">Total Hari Kerja</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning">
                                <div class="card-body text-center">
                                    <div class="fw-bold fs-3 text-dark">{{ $summary['late_days'] }}</div>
                                    <div class="text-dark">Hari Terlambat</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary">
                                <div class="card-body text-center">
                                    <div class="fw-bold fs-3 text-white">{{ number_format($summary['total_late_minutes']) }}</div>
                                    <div class="text-white">Total Menit Terlambat</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger">
                                <div class="card-body text-center">
                                    <div class="fw-bold fs-3 text-white">Rp {{ number_format($summary['total_penalty']) }}</div>
                                    <div class="text-white">Total Denda</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Penalty Breakdown --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-white mb-3">
                                        <i class="bi bi-clock me-2 text-warning"></i>Denda Keterlambatan
                                    </h6>
                                    <div class="fw-bold fs-4 text-warning">Rp {{ number_format($summary['total_late_fine']) }}</div>
                                    <small class="text-muted">Rata-rata: {{ number_format($summary['avg_late_minutes'], 1) }} menit/hari</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-white mb-3">
                                        <i class="bi bi-pause-circle me-2 text-info"></i>Denda Istirahat
                                    </h6>
                                    <div class="fw-bold fs-4 text-info">Rp {{ number_format($summary['total_break_fine']) }}</div>
                                    <small class="text-muted">Pelanggaran istirahat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-white mb-3">
                                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Denda Absensi
                                    </h6>
                                    <div class="fw-bold fs-4 text-danger">Rp {{ number_format($summary['total_absence_fine']) }}</div>
                                    <small class="text-muted">Lupa absen masuk/pulang</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Attendance Table --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-white mb-0">
                                <i class="bi bi-calendar-check me-2"></i>Detail Absensi Harian
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                        <th><i class="bi bi-clock me-2"></i>Scan Masuk</th>
                                        <th><i class="bi bi-clock-history me-2"></i>Scan Pulang</th>
                                        <th><i class="bi bi-stopwatch me-2"></i>Terlambat</th>
                                        <th><i class="bi bi-currency-dollar me-2"></i>Denda Terlambat</th>
                                        <th><i class="bi bi-pause-circle me-2"></i>Denda Istirahat</th>
                                        <th><i class="bi bi-exclamation-triangle me-2"></i>Denda Absensi</th>
                                        <th><i class="bi bi-cash-stack me-2"></i>Total Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($attendanceData as $attendance)
                                        <tr class="{{ $attendance->total_fine > 0 ? 'table-warning' : '' }}">
                                            <td class="text-white">{{ $attendance->tanggal->format('d M Y') }}</td>
                                            <td class="text-white">
                                                {{ $attendance->scan1 ? $attendance->scan1->format('H:i') : '-' }}
                                            </td>
                                            <td class="text-white">
                                                {{ $attendance->scan4 ? $attendance->scan4->format('H:i') : '-' }}
                                            </td>
                                            <td>
                                                @if($attendance->late_minutes > 0)
                                                    <span class="badge bg-warning text-dark">{{ $attendance->late_minutes }} menit</span>
                                                @else
                                                    <span class="text-success">Tepat waktu</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if($attendance->late_fine > 0)
                                                    <span class="text-warning">Rp {{ number_format($attendance->late_fine) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if($attendance->break_fine > 0)
                                                    <span class="text-info">Rp {{ number_format($attendance->break_fine) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if($attendance->absence_fine > 0)
                                                    <span class="text-danger">Rp {{ number_format($attendance->absence_fine) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance->total_fine > 0)
                                                    <span class="badge bg-danger">Rp {{ number_format($attendance->total_fine) }}</span>
                                                @else
                                                    <span class="text-success">Rp 0</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data absensi</h5>
                                                    <p>Tidak ada data absensi untuk bulan ini</p>
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

    <script>
        function toggleTheme() {
            const currentTheme = document.body.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }

        // Initialize theme on load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        });
    </script>
@endsection

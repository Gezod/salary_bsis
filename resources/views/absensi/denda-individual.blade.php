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
                            <a class="nav-link active-link" href="{{ route('absensi.denda.individual') }}">
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
                                <i class="bi bi-person-lines-fill text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Laporan Denda Individual</span>
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
                            <h1 class="page-title mb-2">Laporan Denda Individual</h1>
                            <p class="text-muted mb-0">Lihat detail denda per karyawan untuk bulan {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- Month Filter --}}
                            <form method="GET" class="d-flex align-items-center">
                                <input type="month" name="month" value="{{ $month }}" class="form-control me-2" onchange="this.form.submit()">
                            </form>

                            {{-- Export All PDF --}}
                            <a href="{{ route('absensi.denda.export.all', ['month' => $month]) }}" class="btn btn-danger">
                                <i class="bi bi-file-pdf me-2"></i>Export All PDF
                            </a>
                        </div>
                    </div>

                    {{-- Department Statistics --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-white">
                                        <i class="bi bi-person-badge me-2"></i>Staff
                                    </h5>
                                    <div class="row text-white">
                                        <div class="col-6">
                                            <div class="fw-bold fs-4">{{ $departmentStats['staff']['total_employees'] }}</div>
                                            <small>Karyawan Terdenda</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold fs-4">Rp {{ number_format($departmentStats['staff']['total_penalty']) }}</div>
                                            <small>Total Denda</small>
                                        </div>
                                    </div>
                                    <hr class="text-white">
                                    <div class="row text-white">
                                        <div class="col-6">
                                            <div class="fw-bold">Rp {{ number_format($departmentStats['staff']['avg_penalty']) }}</div>
                                            <small>Rata-rata Denda</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold">{{ $departmentStats['staff']['total_late_days'] }}</div>
                                            <small>Total Hari Terlambat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success">
                                <div class="card-body">
                                    <h5 class="card-title text-white">
                                        <i class="bi bi-people me-2"></i>Karyawan
                                    </h5>
                                    <div class="row text-white">
                                        <div class="col-6">
                                            <div class="fw-bold fs-4">{{ $departmentStats['karyawan']['total_employees'] }}</div>
                                            <small>Karyawan Terdenda</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold fs-4">Rp {{ number_format($departmentStats['karyawan']['total_penalty']) }}</div>
                                            <small>Total Denda</small>
                                        </div>
                                    </div>
                                    <hr class="text-white">
                                    <div class="row text-white">
                                        <div class="col-6">
                                            <div class="fw-bold">Rp {{ number_format($departmentStats['karyawan']['avg_penalty']) }}</div>
                                            <small>Rata-rata Denda</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="fw-bold">{{ $departmentStats['karyawan']['total_late_days'] }}</div>
                                            <small>Total Hari Terlambat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Enhanced Table --}}
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Karyawan</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th><i class="bi bi-calendar-check me-2"></i>Total Hari</th>
                                        <th><i class="bi bi-clock me-2"></i>Hari Terlambat</th>
                                        <th><i class="bi bi-stopwatch me-2"></i>Total Menit Terlambat</th>
                                        <th><i class="bi bi-currency-dollar me-2"></i>Denda Terlambat</th>
                                        <th><i class="bi bi-pause-circle me-2"></i>Denda Istirahat</th>
                                        <th><i class="bi bi-exclamation-triangle me-2"></i>Denda Absensi</th>
                                        <th><i class="bi bi-cash-stack me-2"></i>Total Denda</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penaltyData as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($data->employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $data->employee->nama }}</div>
                                                        <small class="text-muted">{{ $data->employee->nip }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $data->employee->departemen === 'staff' ? 'bg-primary' : 'bg-success' }}">
                                                    {{ ucfirst($data->employee->departemen) }}
                                                </span>
                                            </td>
                                            <td class="text-white">{{ $data->total_days }}</td>
                                            <td class="text-white">
                                                <span class="badge bg-warning text-dark">{{ $data->late_days }}</span>
                                            </td>
                                            <td class="text-white">{{ number_format($data->total_late_minutes) }} menit</td>
                                            <td class="text-white">Rp {{ number_format($data->total_late_fine) }}</td>
                                            <td class="text-white">Rp {{ number_format($data->total_break_fine) }}</td>
                                            <td class="text-white">Rp {{ number_format($data->total_absence_fine) }}</td>
                                            <td>
                                                <span class="badge bg-danger fs-6">
                                                    Rp {{ number_format($data->total_penalty) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('absensi.denda.employee.detail', ['id' => $data->employee_id, 'month' => $month]) }}"
                                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('absensi.denda.export.individual', ['id' => $data->employee_id, 'month' => $month]) }}"
                                                       class="btn btn-sm btn-outline-danger" title="Export PDF">
                                                        <i class="bi bi-file-pdf"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data denda</h5>
                                                    <p>Tidak ada karyawan yang terkena denda pada bulan ini</p>
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

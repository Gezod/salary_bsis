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
                        <a class="nav-link {{ request()->routeIs('absensi.half-day-manual') ? 'active-link' : '' }}"
                           href="{{ route('absensi.half-day-manual') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <span>Absensi Setengah Hari</span>
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
                            <i class="bi bi-speedometer2 text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Sistem Absensi</span>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title mb-2">Data Absensi Harian</h1>
                        <p class="text-muted mb-0">Kelola dan pantau kehadiran karyawan secara real-time</p>
                    </div>
                    <div class="stats-card">
                        <div class="fw-bold text-white fs-4">{{ $rows->total() }}</div>
                        <small class="text-muted">Total Records</small>
                    </div>
                </div>

                {{-- Enhanced Filter Section --}}
                <div class="filter-section">
                    <h5 class="text-white mb-3 d-flex align-items-center">
                        <i class="bi bi-funnel me-2 text-primary"></i>
                        Filter Data
                    </h5>
                    <div class="row g-3 align-items-end">
                        <form method="GET" class="col-md-12 d-flex flex-wrap gap-3 justify-items-center">
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Tanggal</label>
                                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Nama Karyawan</label>
                                <input type="text" name="employee" value="{{ request('employee') }}"
                                       class="form-control" placeholder="Cari nama karyawan...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100" style="margin-top: 33px;">
                                    <i class="bi bi-search me-2"></i>Filter
                                </button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('destroyAbsensi') }}" class="col-md-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 mt-4">
                                <i class="bi bi-trash me-2"></i>Hapus seluruh data
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Enhanced Table --}}
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-person me-2"></i>Nama</th>
                                    <th><i class="bi bi-building me-2"></i>Departemen</th>
                                    <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                    <th><i class="bi bi-info-circle me-2"></i>Status</th>
                                    <th><i class="bi bi-clock me-2"></i>Masuk</th>
                                    <th><i class="bi bi-pause-circle me-2"></i>Istirahat Mulai</th>
                                    <th><i class="bi bi-play-circle me-2"></i>Istirahat Selesai</th>
                                    <th><i class="bi bi-door-open me-2"></i>Pulang</th>
                                    <th><i class="bi bi-clock-history me-2"></i>Lembur</th>
                                    <th><i class="bi bi-exclamation-triangle me-2"></i>Telat</th>
                                    <th><i class="bi bi-currency-dollar me-2"></i>Denda</th>
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <span class="text-white fw-bold">
                                                        {{ substr($employee->nama ?? 'N', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-white fw-semibold">{{ $employee->nama ?? '-' }}</div>
                                                    <small class="text-muted">ID: {{ $employee->id ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $employee->departemen ?? '-' }}</span>
                                        </td>
                                        <td class="text-white">{{ $a->tanggal->format('d M Y') }}</td>
                                        <td>
                                            @php $detailedStatus = $a->detailed_status; @endphp
                                            <span class="badge {{ $detailedStatus['badge'] }}">
                                                {{ $detailedStatus['text'] }}
                                            </span>
                                            @if($a->is_half_day)
                                                <small class="d-block text-muted mt-1">{{ $detailedStatus['penalties'] }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($noCheckIn)
                                                <span class="status-badge status-absent">Tidak Hadir</span>
                                            @elseif($isLate)
                                                <span class="status-badge status-late">{{ $a->getFormattedScan('scan1') }}</span>
                                            @else
                                                <span class="status-badge status-present">{{ $a->getFormattedScan('scan1') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-white">{{ $a->getFormattedScan('scan2') ?: '-' }}</td>
                                        <td class="text-white">{{ $a->getFormattedScan('scan3') ?: '-' }}</td>
                                        <td>
                                            @if ($noCheckOut)
                                                <span class="status-badge status-absent">Belum Pulang</span>
                                            @else
                                                <span class="text-white">{{ $a->getFormattedScan('scan4') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($a->hasOvertime())
                                                <span class="badge bg-primary">{{ $a->overtime_text }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($isLate)
                                                <span class="badge bg-warning text-dark">{{ $a->late_minutes }} mnt</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($a->total_fine)
                                                <span class="text-warning fw-bold">
                                                    {{ $a->formatted_total_fine }}
                                                </span>
                                                @if(!$a->is_half_day)
                                                    <small class="d-block text-muted">{{ $detailedStatus['penalties'] }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">
                                                    @if($a->is_half_day)
                                                        Bebas Denda
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                <h5>Tidak ada data</h5>
                                                <p>Belum ada data absensi untuk filter yang dipilih</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Enhanced Pagination --}}
                @if ($rows->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $rows->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

{{-- Add Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.createElement('div');
    sidebarOverlay.className = 'sidebar-overlay';
    document.body.appendChild(sidebarOverlay);

    // Toggle sidebar
    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
    });

    // Tutup sidebar ketika overlay diklik
    sidebarOverlay.addEventListener('click', function () {
        sidebar.classList.remove('show');
        this.classList.remove('show');
    });
});
</script>
@endsection

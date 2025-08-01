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
                                <i class="bi bi-speedometer2"></i>
                            </div>
                            <span class="navbar-brand fw-bold mb-0">Sistem Absensi</span>
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
                            <h1 class="page-title mb-2">Data Absensi Harian</h1>
                            <p class="mb-0 text-muted">Kelola dan pantau kehadiran karyawan secara real-time</p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold fs-4 text-primary">{{ $rows->total() }}</div>
                            <small class="text-muted">Total Records</small>
                        </div>
                    </div>

                    {{-- Enhanced Filter Section --}}
                    <div class="filter-section">
                        <h5 class="mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Data
                        </h5>
                        <div class="row g-3 align-items-end">
                            <form method="GET" class="col-md-12 d-flex flex-wrap gap-3 justify-items-center">
                                <div class="col-md-4">
                                    <label class="form-label small">Tanggal</label>
                                    <input type="date" name="date" value="{{ request('date') }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Nama Karyawan</label>
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
                                        <th><i class="bi bi-calendar3 me-2"></i>Hari</th>
                                        <th><i class="bi bi-info-circle me-2"></i>Status</th>
                                        <th><i class="bi bi-clock me-2"></i>Masuk</th>
                                        <th><i class="bi bi-pause-circle me-2"></i>Istirahat Mulai</th>
                                        <th><i class="bi bi-play-circle me-2"></i>Istirahat Selesai</th>
                                        <th><i class="bi bi-door-open me-2"></i>Pulang</th>
                                        <th><i class="bi bi-clock-history me-2"></i>Lembur</th>
                                        <th><i class="bi bi-stopwatch me-2"></i>Telat (mnt)</th>
                                        <th style="min-width: 300px;"><i class="bi bi-currency-dollar me-2"></i>Detail
                                            Denda & Perhitungan</th>
                                        <th><i class="bi bi-cash-stack me-2"></i>Total</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rows as $a)
                                        @php
                                            $employee = $a->employee;
                                            $isLate = ($a->late_minutes ?? 0) > 0;
                                            $noCheckIn = !$a->scan1;
                                            $noCheckOut = !$a->scan4;
                                            $penaltyDetails = $a->penalty_types;
                                            $penaltyBreakdown = $a->penalty_breakdown;
                                            $detailedCalculation = $a->detailed_penalty_calculation;
                                            $lateFineBreakdown = $a->late_fine_breakdown;
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
                                                        <div class="fw-semibold">{{ $employee->nama ?? '-' }}</div>
                                                        <small class="text-muted">ID: {{ $employee->id ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $employee->departemen === 'staff' ? 'primary' : 'success' }}">
                                                    {{ ucfirst($employee->departemen ?? '-') }}
                                                </span>
                                            </td>
                                            <td>{{ $a->tanggal->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $a->tanggal->translatedFormat('D') }}</span>
                                                @if($a->tanggal->format('l') === 'Friday')
                                                    <small class="d-block text-info">07:00</small>
                                                @else
                                                    <small class="d-block text-info">07:30</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php $detailedStatus = $a->detailed_status; @endphp
                                                <span class="badge {{ $detailedStatus['badge'] }}">
                                                    {{ $detailedStatus['text'] }}
                                                </span>
                                                @if ($a->is_half_day)
                                                    <small class="d-block mt-1 text-info">Bebas Denda</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($noCheckIn)
                                                    <span class="status-badge status-absent">Tidak Hadir</span>
                                                @elseif($isLate)
                                                    <span
                                                        class="status-badge status-late">{{ $a->getFormattedScan('scan1') }}</span>
                                                @else
                                                    <span
                                                        class="status-badge status-present">{{ $a->getFormattedScan('scan1') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $a->getFormattedScan('scan2') ?: '-' }}</td>
                                            <td>{{ $a->getFormattedScan('scan3') ?: '-' }}</td>
                                            <td>
                                                @if ($noCheckOut)
                                                    <span class="status-badge status-absent"> - </span>
                                                @else
                                                    <span>{{ $a->getFormattedScan('scan4') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($a->hasOvertime())
                                                    <div>
                                                        <span class="badge {{ $a->overtime_status_badge }}">
                                                            {{ $a->overtime_text }}
                                                        </span>
                                                        @if ($a->overtime_status === 'pending')
                                                            <div class="btn-group btn-group-sm mt-1" role="group">
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    onclick="updateOvertimeStatus({{ $a->id }}, 'approved')">
                                                                    <i class="bi bi-check fs-2"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="updateOvertimeStatus({{ $a->id }}, 'rejected')">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($isLate)
                                                    <span
                                                        class="badge bg-warning text-dark fw-bold">{{ $a->late_minutes }}
                                                        mnt</span>
                                                    @if($lateFineBreakdown)
                                                        <small class="d-block text-warning mt-1">
                                                            @ Rp{{ number_format($lateFineBreakdown['rate'], 0, ',', '.') }}/mnt
                                                        </small>
                                                        <small class="d-block text-muted">
                                                            {{ $lateFineBreakdown['range_text'] }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="penalty-column">
                                                @if ($a->is_half_day)
                                                    <div class="penalty-free">
                                                        <strong class="text-info">Bebas Denda</strong>
                                                        <small class="d-block text-info">(Setengah Hari)</small>
                                                    </div>
                                                @else
                                                    <div class="penalty-details-wrapper">
                                                        {{-- Denda Telat dengan Perhitungan Detail --}}
                                                        @if ($a->late_fine > 0 && $lateFineBreakdown)
                                                            <div class="penalty-item penalty-late">
                                                                <strong class="penalty-label text-danger">Denda Telat:</strong>
                                                                <strong class="penalty-value text-danger">
                                                                    Rp {{ number_format($a->late_fine, 0, ',', '.') }}
                                                                </strong>
                                                                <small class="penalty-desc text-danger">
                                                                    {{ $lateFineBreakdown['range_text'] }}
                                                                </small>
                                                                <small class="penalty-formula text-danger">
                                                                    {{ $lateFineBreakdown['calculation_text'] }}
                                                                </small>
                                                                @if(isset($lateFineBreakdown['base_fine']))
                                                                    <small class="penalty-formula text-danger">
                                                                        Base: Rp{{ number_format($lateFineBreakdown['base_fine'], 0, ',', '.') }}
                                                                        + Extra: Rp{{ number_format($lateFineBreakdown['extra_fine'], 0, ',', '.') }}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        {{-- Denda Istirahat --}}
                                                        @if ($a->break_fine > 0)
                                                            <div class="penalty-item penalty-break">
                                                                <strong class="penalty-label text-warning">Denda Istirahat:</strong>
                                                                <strong class="penalty-value text-warning">
                                                                    Rp {{ number_format($a->break_fine, 0, ',', '.') }}
                                                                </strong>
                                                                @if (!$a->scan2 && !$a->scan3)
                                                                    <small class="penalty-desc text-warning">(2x tidak absen istirahat)</small>
                                                                @elseif (!$a->scan2 || !$a->scan3)
                                                                    <small class="penalty-desc text-warning">(1x tidak absen istirahat)</small>
                                                                @else
                                                                    <small class="penalty-desc text-warning">(telat istirahat)</small>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        {{-- Denda Absen --}}
                                                        @if ($a->absence_fine > 0)
                                                            <div class="penalty-item penalty-absence">
                                                                <strong class="penalty-label text-info">Denda Absen:</strong>
                                                                <strong class="penalty-value text-info">
                                                                    Rp {{ number_format($a->absence_fine, 0, ',', '.') }}
                                                                </strong>
                                                                <small class="penalty-desc text-info">
                                                                    @if (!$a->scan1 && !$a->scan4)
                                                                        (lupa absen masuk & pulang)
                                                                    @elseif (!$a->scan1)
                                                                        (lupa absen masuk)
                                                                    @elseif (!$a->scan4)
                                                                        (lupa absen pulang)
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        @endif

                                                        {{-- Total Perhitungan --}}
                                                        @if ($a->total_fine > 0)
                                                            <div class="penalty-total">
                                                                <hr class="my-2">
                                                                <strong class="text-danger">Total:
                                                                    @if($a->late_fine > 0)
                                                                        Rp{{ number_format($a->late_fine, 0, ',', '.') }}
                                                                    @endif
                                                                    @if($a->late_fine > 0 && ($a->break_fine > 0 || $a->absence_fine > 0))
                                                                        +
                                                                    @endif
                                                                    @if($a->break_fine > 0)
                                                                        Rp{{ number_format($a->break_fine, 0, ',', '.') }}
                                                                    @endif
                                                                    @if($a->break_fine > 0 && $a->absence_fine > 0)
                                                                        +
                                                                    @endif
                                                                    @if($a->absence_fine > 0)
                                                                        Rp{{ number_format($a->absence_fine, 0, ',', '.') }}
                                                                    @endif
                                                                </strong>
                                                                <small class="d-block text-danger">
                                                                    = Rp{{ number_format($a->total_fine, 0, ',', '.') }}
                                                                </small>
                                                            </div>
                                                        @else
                                                            <div class="penalty-free">
                                                                <strong class="text-success">Tidak Ada Denda</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($a->total_fine > 0)
                                                    <span class="total-fine-amount">
                                                        {{ $a->formatted_total_fine }}
                                                    </span>
                                                @else
                                                    <span class="total-fine-free">
                                                        @if ($a->is_half_day)
                                                            <strong class="text-info">Bebas</strong>
                                                        @else
                                                            <strong class="text-success">Rp 0</strong>
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info btn-sm"
                                                        onclick="showDetails({{ $a->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal">
                                                        <i class="bi bi-eye fs-4"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                                        onclick="editAttendance({{ $a->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="bi bi-pencil fs-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5 class="text-muted">Tidak ada data</h5>
                                                    <p class="text-muted">Belum ada data absensi untuk filter yang dipilih
                                                    </p>
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

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Detail Absensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Absensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAttendanceForm">
                    <div class="modal-body" id="editContent">
                        <!-- Content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
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

            // Tutup sidebar ketika overlay diklik
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

        // Show attendance details
        function showDetails(id) {
            fetch(`/absensi/${id}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('detailContent').innerHTML = data.html;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memuat detail',
                            background: window.swalTheme?.background || '#ffffff',
                            color: window.swalTheme?.color || '#1e293b'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan',
                        background: window.swalTheme?.background || '#ffffff',
                        color: window.swalTheme?.color || '#1e293b'
                    });
                });
        }

        // Edit attendance
        function editAttendance(id) {
            fetch(`/absensi/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('editContent').innerHTML = data.html;
                        document.getElementById('editAttendanceForm').action = `/absensi/${id}/update`;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memuat data',
                            background: window.swalTheme?.background || '#ffffff',
                            color: window.swalTheme?.color || '#1e293b'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan',
                        background: window.swalTheme?.background || '#ffffff',
                        color: window.swalTheme?.color || '#1e293b'
                    });
                });
        }

        // Update overtime status
        function updateOvertimeStatus(id, status, event) {
            const confirmText = status === 'approved' ? 'menyetujui' : 'menolak';
            const iconType = status === 'approved' ? 'question' : 'warning';
            const confirmButtonText = status === 'approved' ? 'Ya, Setujui!' : 'Ya, Tolak!';
            const confirmButtonColor = status === 'approved' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: `Konfirmasi ${status === 'approved' ? 'Persetujuan' : 'Penolakan'}`,
                text: `Apakah Anda yakin ingin ${confirmText} lembur ini?`,
                icon: iconType,
                input: 'textarea',
                inputLabel: 'Catatan (opsional)',
                inputPlaceholder: 'Masukkan catatan jika diperlukan...',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Batal',
                background: window.swalTheme?.background || '#ffffff',
                color: window.swalTheme?.color || '#1e293b',
                customClass: {
                    input: 'swal2-textarea-dark'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengupdate status lembur',
                        allowOutsideClick: false,
                        background: window.swalTheme?.background || '#ffffff',
                        color: window.swalTheme?.color || '#1e293b',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/absensi/${id}/overtime-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                status: status,
                                notes: result.value || ''
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    background: window.swalTheme?.background || '#ffffff',
                                    color: window.swalTheme?.color || '#1e293b',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Gagal mengupdate status',
                                    background: window.swalTheme?.background || '#ffffff',
                                    color: window.swalTheme?.color || '#1e293b'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan',
                                background: window.swalTheme?.background || '#ffffff',
                                color: window.swalTheme?.color || '#1e293b'
                            });
                        });
                }
            });
        }

        // Handle edit form submission
        document.getElementById('editAttendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Sedang menyimpan perubahan',
                allowOutsideClick: false,
                background: window.swalTheme?.background || '#ffffff',
                color: window.swalTheme?.color || '#1e293b',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(this);
            const actionUrl = this.action;

            fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Perubahan berhasil disimpan',
                            background: window.swalTheme?.background || '#ffffff',
                            color: window.swalTheme?.color || '#1e293b',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal menyimpan perubahan',
                            background: window.swalTheme?.background || '#ffffff',
                            color: window.swalTheme?.color || '#1e293b'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan',
                        background: window.swalTheme?.background || '#ffffff',
                        color: window.swalTheme?.color || '#1e293b'
                    });
                });
        });
    </script>

    <style>
        /* Enhanced penalty display styling */
        .penalty-column {
            min-width: 350px;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .penalty-details-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .penalty-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            padding: 6px 10px;
            border-left: 3px solid transparent;
            margin-bottom: 3px;
        }

        .penalty-item.penalty-late {
            border-left-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .penalty-item.penalty-break {
            border-left-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }

        .penalty-item.penalty-absence {
            border-left-color: #17a2b8;
            background: rgba(23, 162, 184, 0.1);
        }

        .penalty-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700 !important;
            margin-bottom: 2px;
        }

        .penalty-value {
            display: block;
            font-size: 0.8rem;
            font-weight: 700 !important;
        }

        .penalty-desc {
            display: block;
            font-size: 0.65rem;
            opacity: 0.9;
            font-style: italic;
            margin-top: 1px;
        }

        .penalty-formula {
            display: block;
            font-size: 0.65rem;
            opacity: 0.8;
            font-weight: 600;
            margin-top: 1px;
        }

        .penalty-total {
            background: rgba(220, 53, 69, 0.15);
            border-radius: 4px;
            padding: 6px 8px;
            margin-top: 4px;
            text-align: center;
        }

        .penalty-free {
            text-align: center;
            padding: 8px;
            background: rgba(40, 167, 69, 0.1);
            border-radius: 4px;
            border-left: 3px solid #28a745;
        }

        .total-fine-amount {
            font-weight: 700 !important;
            font-size: 0.9rem !important;
            color: #dc3545 !important;
        }

        .total-fine-free {
            font-weight: 700 !important;
            font-size: 0.9rem !important;
        }

        .swal2-textarea-dark {
            background-color: var(--bg-color) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 0.5rem;
        }

        .swal2-textarea-dark::placeholder {
            color: var(--text-muted) !important;
        }

        /* Dark theme adjustments */
        [data-theme="dark"] .penalty-item.penalty-late {
            background: rgba(220, 53, 69, 0.2);
        }

        [data-theme="dark"] .penalty-item.penalty-break {
            background: rgba(255, 193, 7, 0.2);
        }

        [data-theme="dark"] .penalty-item.penalty-absence {
            background: rgba(23, 162, 184, 0.2);
        }

        [data-theme="dark"] .penalty-free {
            background: rgba(40, 167, 69, 0.2);
        }

        [data-theme="dark"] .penalty-total {
            background: rgba(220, 53, 69, 0.25);
        }
    </style>
@endsection

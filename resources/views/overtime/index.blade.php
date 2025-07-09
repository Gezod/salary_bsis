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
                    <small class="text-muted">Sistem Lembur</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('overtime.overview') ? 'active-link' : '' }}"
                            href="{{ route('overtime.overview') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <span>Overview</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('overtime.index') ? 'active-link' : '' }}"
                            href="{{ route('overtime.index') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <span>Data Lembur</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('overtime.settings') ? 'active-link' : '' }}"
                            href="{{ route('overtime.settings') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('overtime.recap') ? 'active-link' : '' }}"
                            href="{{ route('overtime.recap') }}">
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
                            <i class="bi bi-clock text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Sistem Lembur</span>
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
                        <h1 class="page-title mb-2">Data Lembur</h1>
                        <p class="text-muted mb-0">Kelola dan pantau lembur karyawan secara real-time</p>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">{{ $records->total() }}</div>
                            <small class="text-muted">Total Records</small>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('overtime.recalculate') }}" class="btn btn-warning btn-sm shadow-sm">
                                <i class="bi bi-arrow-clockwise me-2"></i>Hitung Ulang Semua
                            </a>
                        </div>
                        <div class="alert alert-info py-2 px-3 mb-0 small">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Jam Pulang:</strong> Senin-Kamis & Sabtu = 16:30 | Jumat = 16:00
                        </div>
                    </div>
                </div>

                {{-- Enhanced Filter Section --}}
                <div class="filter-section">
                    <h5 class="text-white mb-3 d-flex align-items-center">
                        <i class="bi bi-funnel me-2 text-primary"></i>
                        Filter Data
                    </h5>
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted small">Tanggal</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Nama Karyawan</label>
                            <input type="text" name="employee" value="{{ request('employee') }}"
                                class="form-control" placeholder="Cari nama karyawan...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small">Departemen</label>
                            <select name="department" class="form-control">
                                <option value="">Semua Departemen</option>
                                <option value="staff" {{ request('department') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="karyawan" {{ request('department') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Filter
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
                                    <th><i class="bi bi-person me-2"></i>Karyawan</th>
                                    <th><i class="bi bi-calendar me-2"></i>Tanggal & Hari</th>
                                    <th><i class="bi bi-clock me-2"></i>Jam Pulang</th>
                                    <th><i class="bi bi-clock-history me-2"></i>Durasi Lembur</th>
                                    <th><i class="bi bi-currency-dollar me-2"></i>Uang Lembur</th>
                                    <th><i class="bi bi-check-circle me-2"></i>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records as $record)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <span class="text-white fw-bold">
                                                        {{ substr($record->employee->nama, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-white fw-semibold">{{ $record->employee->nama }}</div>
                                                    <small class="text-muted">{{ ucfirst($record->employee->departemen) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-white">{{ $record->formatted_date_with_day }}</td>
                                        <td class="text-white">{{ $record->formatted_scan4 }}</td>
                                        <td class="text-white">{{ $record->formatted_duration }}</td>
                                        <td class="text-success fw-bold">{{ $record->formatted_overtime_pay }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                <h5>Tidak ada data</h5>
                                                <p>Belum ada data lembur untuk filter yang dipilih</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Enhanced Pagination --}}
                @if ($records->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $records->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
</script>
@endsection

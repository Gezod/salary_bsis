@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --sidebar-bg: #334155;
            --border-color: #475569;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        ::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #475569 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border: none !important;
        }

        .nav-link {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            color: #cbd5e1 !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(4px);
        }

        .nav-link.active-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .navbar {
            background: linear-gradient(90deg, var(--card-bg) 0%, #334155 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .form-control {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(30, 41, 59, 0.9) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-outline-info {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .table-dark {
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .table-dark thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .table-dark tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .table-dark tbody tr:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: scale(1.01);
        }

        .table-dark tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            min-width: 80px;
        }

        .status-late {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #92400e;
        }

        .status-absent {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: #991b1b;
        }

        .status-present {
            background: linear-gradient(135deg, #34d399, #10b981);
            color: #065f46;
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(30, 64, 175, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.2);
        }

        .page-title {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2rem;
        }

        .text-muted {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 50;
            font-size: 1rem;
        }

        .filter-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .pagination .page-link {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            border-radius: 0.5rem;
            margin: 0 0.25rem;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            margin-right: 1rem;
        }

    </style>

    <div class="container-fluid min-vh-100 px-0">
        <div class="row g-0">
            {{-- Enhanced Sidebar --}}
            <nav class="col-md-2 d-none d-md-block sidebar">
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
                    </ul>
                </div>
            </nav>

            {{-- Enhanced Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                {{-- Enhanced Navbar --}}
                <nav class="navbar navbar-expand-lg sticky-top">
                    <div class="container-fluid">
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
                            {{-- <div class="col-md-3">
                                <label class="form-label text-muted small">Departemen</label>
                                <select name="department" class="form-control">
                                    <option value="">Semua Departemen</option>
                                    <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT
                                    </option>
                                    <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR
                                    </option>
                                    <option value="Finance" {{ request('department') == 'Finance' ? 'selected' : '' }}>
                                        Finance</option>
                                </select>
                            </div> --}}
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
                                        <th><i class="bi bi-person me-2"></i>Nama</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                        <th><i class="bi bi-clock me-2"></i>Masuk</th>
                                        <th><i class="bi bi-pause-circle me-2"></i>Istirahat Mulai</th>
                                        <th><i class="bi bi-play-circle me-2"></i>Istirahat Selesai</th>
                                        <th><i class="bi bi-door-open me-2"></i>Pulang</th>
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
                                                        <div class="text-white fw-semibold">{{ $employee->nama ?? '-' }}
                                                        </div>
                                                        <small class="text-muted">ID: {{ $employee->id ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $employee->departemen ?? '-' }}</span>
                                            </td>
                                            <td class="text-white">{{ $a->tanggal->format('d M Y') }}</td>
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
                                                @if ($isLate)
                                                    <span class="badge bg-warning text-dark">{{ $a->late_minutes }}
                                                        mnt</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($a->total_fine)
                                                    <span class="text-warning fw-bold">
                                                        Rp {{ number_format($a->total_fine, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
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
@endsection

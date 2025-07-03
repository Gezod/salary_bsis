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

        .form-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
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

        .form-label {
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .penalty-table {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            overflow: hidden;
        }

        .penalty-table th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .penalty-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: white;
            vertical-align: middle;
        }

        .penalty-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .penalty-input {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.5rem !important;
            color: white !important;
            padding: 0.5rem 0.75rem;
            width: 120px;
        }

        .penalty-input:focus {
            background: rgba(30, 41, 59, 0.9) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
        }

        .role-tab {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid var(--border-color);
            color: #cbd5e1;
            border-radius: 0.75rem 0.75rem 0 0;
            padding: 1rem 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
        }

        .role-tab.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-color: var(--primary-color);
        }

        .role-tab:hover:not(.active) {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--primary-color);
        }

        .tab-content {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0 1rem 1rem 1rem;
            padding: 2rem;
        }

        /* Sidebar Responsive */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            main {
                margin-left: 0 !important;
            }
        }

        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0) !important;
            }

            main {
                margin-left: 250px;
            }
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        .time-range-cell {
            min-width: 120px;
            font-weight: 500;
            color: #0f172a !important;
        }

        .time-range-cell small {
            display: block;
            font-size: 0.75rem;
            color: #0f172a;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .time-range-cell {
                min-width: 100px;
            }

            .time-range-cell small {
                display: none;
            }
        }
    </style>

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
                                <i class="bi bi-currency-dollar text-white"></i>
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
                            <h1 class="page-title mb-2">Pengaturan Denda</h1>
                            <p class="text-muted mb-0">Kelola dan atur nilai denda untuk berbagai pelanggaran absensi</p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">
                                <i class="bi bi-gear text-warning"></i>
                            </div>
                            <small class="text-muted">Settings</small>
                        </div>
                    </div>

                    {{-- Notifications --}}
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Error!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Role Tabs --}}
                    <div class="mb-4">
                        <div class="d-flex">
                            <div class="role-tab active" data-role="staff">
                                <i class="bi bi-person-badge me-2"></i>Staff
                            </div>
                            <div class="role-tab" data-role="karyawan">
                                <i class="bi bi-people me-2"></i>Karyawan
                            </div>
                        </div>
                    </div>

                    {{-- Form Section --}}
                    <form method="POST" action="{{ route('absensi.denda.update') }}" id="dendaForm">
                        @csrf
                        @method('PUT')

                        {{-- Staff Tab Content --}}
                        <div class="tab-content" id="staff-content">
                            <h5 class="text-white mb-4 d-flex align-items-center">
                                <i class="bi bi-person-badge me-2 text-primary"></i>
                                Pengaturan Denda Staff
                            </h5>

                            {{-- Late Penalties --}}
                            <div class="mb-4">
                                <h6 class="text-white mb-3">
                                    <i class="bi bi-clock me-2 text-warning"></i>
                                    Denda Keterlambatan
                                </h6>
                                <div class="table-responsive">
                                    <table class="table penalty-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Rentang Waktu</th>
                                                <th>Denda (Rp)</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="time-range-cell">
                                                    1 - 15 menit
                                                    <small>≤ 15 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="staff[late][0][2]"
                                                        value="{{ $penalties['staff']['late'][0][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat ringan</td>
                                            </tr>
                                            <tr>
                                                <td class="time-range-cell">
                                                    16 - 30 menit
                                                    <small>≤ 30 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="staff[late][1][2]"
                                                        value="{{ $penalties['staff']['late'][1][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat sedang</td>
                                            </tr>
                                            <tr>
                                                <td class="time-range-cell">
                                                    31 - 45 menit
                                                    <small>≤ 45 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="staff[late][2][2]"
                                                        value="{{ $penalties['staff']['late'][2][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat berat</td>
                                            </tr>
                                            <tr>
                                                <td class="time-range-cell">
                                                    46 - 60 menit
                                                    <small>≤ 60 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="staff[late][3][2]"
                                                        value="{{ $penalties['staff']['late'][3][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat sangat berat</td>
                                            </tr>
                                            <tr>
                                                <td class="time-range-cell">
                                                    >60 menit
                                                    <small>>60 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="staff[late_base]" value="12000"
                                                        class="penalty-input" min="0">
                                                    <small class="text-muted d-block mt-1">+ per menit</small>
                                                </td>
                                                <td class="text-muted">Base + per menit tambahan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Other Penalties --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-pause-circle me-2 text-info"></i>
                                                Denda Istirahat
                                            </h6>
                                            <div class="mb-3">
                                                <label class="form-label small">Telat Istirahat</label>
                                                <input type="number" name="staff[late_break]"
                                                    value="{{ $penalties['staff']['late_break'] }}" class="form-control"
                                                    min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">1x Tidak Absen Istirahat</label>
                                                <input type="number" name="staff[absent_break_once]"
                                                    value="{{ $penalties['staff']['absent_break_once'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">2x Tidak Absen Istirahat</label>
                                                <input type="number" name="staff[absent_twice]"
                                                    value="{{ $penalties['staff']['absent_twice'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                                                Denda Absensi
                                            </h6>
                                            <div class="mb-3">
                                                <label class="form-label small">Lupa Absen Masuk</label>
                                                <input type="number" name="staff[missing_checkin]"
                                                    value="{{ $penalties['staff']['missing_checkin'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Lupa Absen Pulang</label>
                                                <input type="number" name="staff[missing_checkout]"
                                                    value="{{ $penalties['staff']['missing_checkout'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Karyawan Tab Content --}}
                        <div class="tab-content" id="karyawan-content" style="display: none;">
                            <h5 class="text-white mb-4 d-flex align-items-center">
                                <i class="bi bi-people me-2 text-primary"></i>
                                Pengaturan Denda Karyawan
                            </h5>

                            {{-- Late Penalties --}}
                            <div class="mb-4">
                                <h6 class="text-white mb-3">
                                    <i class="bi bi-clock me-2 text-warning"></i>
                                    Denda Keterlambatan
                                </h6>
                                <div class="table-responsive">
                                    <table class="table penalty-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Rentang Waktu</th>
                                                <th>Denda (Rp)</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="time-range-cell">
                                                    1 - 15 menit
                                                    <small>≤ 15 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="karyawan[late][0][2]"
                                                        value="{{ $penalties['karyawan']['late'][0][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat ringan</td>
                                            </tr>
                                            <tr>
                                                <td class="time-range-cell">
                                                    16 - 30 menit
                                                    <small>≤ 30 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="karyawan[late][1][2]"
                                                        value="{{ $penalties['karyawan']['late'][1][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat sedang</td>
                                            </tr>
                                            <tr>
                                                 <td class="time-range-cell">
                                                    31 - 45 menit
                                                    <small>≤ 45 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="karyawan[late][2][2]"
                                                        value="{{ $penalties['karyawan']['late'][2][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat berat</td>
                                            </tr>
                                            <tr>
                                                 <td class="time-range-cell">
                                                    46 - 60 menit
                                                    <small>≤ 60 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="karyawan[late][3][2]"
                                                        value="{{ $penalties['karyawan']['late'][3][2] }}"
                                                        class="penalty-input" min="0">
                                                </td>
                                                <td class="text-muted">Terlambat sangat berat</td>
                                            </tr>
                                            <tr>
                                                 <td class="time-range-cell">
                                                    >60 menit
                                                    <small>>60 menit</small>
                                                </td>
                                                <td>
                                                    <input type="number" name="karyawan[late_base]" value="10000"
                                                        class="penalty-input" min="0">
                                                    <small class="text-muted d-block mt-1">+ per menit</small>
                                                </td>
                                                <td class="text-muted">Base + per menit tambahan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Other Penalties --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-pause-circle me-2 text-info"></i>
                                                Denda Istirahat
                                            </h6>
                                            <div class="mb-3">
                                                <label class="form-label small">Telat Istirahat</label>
                                                <input type="number" name="karyawan[late_break]"
                                                    value="{{ $penalties['karyawan']['late_break'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">1x Tidak Absen Istirahat</label>
                                                <input type="number" name="karyawan[absent_break_once]"
                                                    value="{{ $penalties['karyawan']['absent_break_once'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">2x Tidak Absen Istirahat</label>
                                                <input type="number" name="karyawan[absent_twice]"
                                                    value="{{ $penalties['karyawan']['absent_twice'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                                                Denda Absensi
                                            </h6>
                                            <div class="mb-3">
                                                <label class="form-label small">Lupa Absen Masuk</label>
                                                <input type="number" name="karyawan[missing_checkin]"
                                                    value="{{ $penalties['karyawan']['missing_checkin'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Lupa Absen Pulang</label>
                                                <input type="number" name="karyawan[missing_checkout]"
                                                    value="{{ $penalties['karyawan']['missing_checkout'] }}"
                                                    class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Simpan Pengaturan Denda
                            </button>
                            <button type="button" class="btn btn-outline-info btn-lg ms-3" onclick="resetForm()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset ke Default
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // Tambahkan di script Anda
        function formatTimeRangeCells() {
            const timeRanges = document.querySelectorAll('.time-range-cell');

            timeRanges.forEach(cell => {
                const mainText = cell.textContent.trim();
                const minutes = mainText.match(/\d+/g);

                if (minutes && minutes.length >= 2) {
                    cell.innerHTML = `
                ${mainText}
                <small>${minutes[0]} - ${minutes[1]} menit</small>
            `;
                } else if (mainText.includes('>')) {
                    const min = mainText.match(/\d+/)[0];
                    cell.innerHTML = `
                ${mainText}
                <small>> ${min} menit</small>
            `;
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Toggle sidebar for mobile
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

            // Role tab switching
            const roleTabs = document.querySelectorAll('.role-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            roleTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const role = this.dataset.role;

                    // Remove active class from all tabs
                    roleTabs.forEach(t => t.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.style.display = 'none';
                    });

                    // Show selected tab content
                    document.getElementById(role + '-content').style.display = 'block';
                });
            });
        });

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset semua pengaturan ke nilai default?')) {
                // Reset to default values
                const defaultValues = {
                    staff: {
                        late: [3000, 6000, 8000, 12000],
                        late_break: 1500,
                        missing_checkin: 9000,
                        missing_checkout: 3000,
                        absent_break_once: 2000,
                        absent_twice: 3000,
                        late_base: 12000
                    },
                    karyawan: {
                        late: [2000, 4000, 6000, 10000],
                        late_break: 1000,
                        missing_checkin: 6000,
                        missing_checkout: 2000,
                        absent_break_once: 1500,
                        absent_twice: 2000,
                        late_base: 10000
                    }
                };

                // Reset staff values
                for (let i = 0; i < 4; i++) {
                    document.querySelector(`input[name="staff[late][${i}][2]"]`).value = defaultValues.staff.late[i];
                }
                document.querySelector('input[name="staff[late_break]"]').value = defaultValues.staff.late_break;
                document.querySelector('input[name="staff[missing_checkin]"]').value = defaultValues.staff.missing_checkin;
                document.querySelector('input[name="staff[missing_checkout]"]').value = defaultValues.staff
                    .missing_checkout;
                document.querySelector('input[name="staff[absent_break_once]"]').value = defaultValues.staff
                    .absent_break_once;
                document.querySelector('input[name="staff[absent_twice]"]').value = defaultValues.staff.absent_twice;
                document.querySelector('input[name="staff[late_base]"]').value = defaultValues.staff.late_base;

                // Reset karyawan values
                for (let i = 0; i < 4; i++) {
                    document.querySelector(`input[name="karyawan[late][${i}][2]"]`).value = defaultValues.karyawan.late[i];
                }
                document.querySelector('input[name="karyawan[late_break]"]').value = defaultValues.karyawan.late_break;
                document.querySelector('input[name="karyawan[missing_checkin]"]').value = defaultValues.karyawan
                    .missing_checkin;
                document.querySelector('input[name="karyawan[missing_checkout]"]').value = defaultValues.karyawan
                    .missing_checkout;
                document.querySelector('input[name="karyawan[absent_break_once]"]').value = defaultValues.karyawan
                    .absent_break_once;
                document.querySelector('input[name="karyawan[absent_twice]"]').value = defaultValues.karyawan.absent_twice;
                document.querySelector('input[name="karyawan[late_base]"]').value = defaultValues.karyawan.late_base;
            }
        }
    </script>
@endsection

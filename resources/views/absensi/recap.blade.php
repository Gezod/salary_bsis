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
        .text-muted{
             background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 50;
            font-size: 1rem;
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

        .filter-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.2);
        }

        .summary-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .summary-card.total .icon {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .summary-card.amount .icon {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
        }

        .summary-card.employees .icon {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
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

        .currency-highlight {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: #f59e0b;
            font-weight: 600;
            display: inline-block;
        }
    </style>

    <div class="container-fluid min-vh-100 px-0">
        <div class="row g-0">
            {{-- Enhanced Sidebar --}}
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="position-sticky pt-4 px-3">
                    <div class="logo-container text-center">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah" class="img-fluid rounded-circle mb-3" style="max-width: 80px; border: 3px solid rgba(255,255,255,0.2);">
                        <h6 class="text-white fw-bold mb-0">Bank Sampah</h6>
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
                                <i class="bi bi-file-earmark-text text-white"></i>
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
                            <h1 class="page-title mb-2">Rekap Denda Karyawan</h1>
                            <p class="text-muted mb-0">
                                Laporan denda bulan {{ \Carbon\Carbon::parse($month.'-01')->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">{{ count($data) }}</div>
                            <small class="text-muted">Total Karyawan</small>
                        </div>
                    </div>

                    {{-- Summary Cards --}}
                    <div class="summary-cards">
                        <div class="summary-card total">
                            <div class="icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3 class="text-white mb-1">{{ count($data) }}</h3>
                            <p class="text-muted mb-0 small">Total Karyawan</p>
                        </div>
                        <div class="summary-card amount">
                            <div class="icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h3 class="text-white mb-1">
                                Rp {{ number_format($data->sum('fine'), 0, ',', '.') }}
                            </h3>
                            <p class="text-muted mb-0 small">Total Denda</p>
                        </div>
                        <div class="summary-card employees">
                            <div class="icon">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h3 class="text-white mb-1">{{ $data->where('fine', '>', 0)->count() }}</h3>
                            <p class="text-muted mb-0 small">Karyawan Terkena Denda</p>
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
                                <input type="month" name="month" value="{{ $month }}"
                                    class="form-control">
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
                                        <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>Total Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($d->employee->nama ?? 'N', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $d->employee->nama ?? '-' }}</div>
                                                        <small class="text-muted">ID: {{ $d->employee->id ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $d->employee->departemen ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                @if($d->fine > 0)
                                                    <span class="currency-highlight">
                                                        Rp {{ number_format($d->fine, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data rekap denda untuk bulan yang dipilih</p>
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
@endsection

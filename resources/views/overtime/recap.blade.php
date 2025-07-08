@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style_index.css') }}" rel="stylesheet">

<div class="container-fluid min-vh-100 px-0">
    <div class="row g-0">
        {{-- Sidebar --}}
        <nav class="col-md-2 sidebar">
            <div class="position-sticky pt-4 px-3">
                <div class="logo-container text-center">
                    <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                        alt="Bank Sampah" class="img-fluid sidebar-logo mb-3">
                    <small class="text-muted">Sistem Lembur</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.overview') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <span>Overview</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.index') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <span>Data Lembur</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.settings') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active-link" href="{{ route('overtime.recap') }}">
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

        {{-- Main Content --}}
        <main class="col-md-10 ms-sm-auto px-md-4">
            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg sticky-top">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper me-3">
                            <i class="bi bi-calendar-check text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Rekap Lembur</span>
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
                        <h1 class="page-title mb-2">Rekap Lembur Bulanan</h1>
                        <p class="text-muted mb-0">Laporan dan analisis lembur per bulan</p>
                    </div>
                    <form method="GET" class="d-flex">
                        <input type="month" name="month" value="{{ $month }}" class="form-control me-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div class="row g-4">
                    {{-- Department Statistics --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-people me-2"></i>Berdasarkan Departemen
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-primary bg-opacity-10 rounded mb-3">
                                            <span class="text-white fw-semibold">Staff</span>
                                            <div class="text-end">
                                                <div class="text-white fw-bold">{{ $departmentStats['staff']['count'] }} orang</div>
                                                <small class="text-muted">
                                                    Rp {{ number_format($departmentStats['staff']['total_pay'], 0, ',', '.') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-success bg-opacity-10 rounded">
                                            <span class="text-white fw-semibold">Karyawan</span>
                                            <div class="text-end">
                                                <div class="text-white fw-bold">{{ $departmentStats['karyawan']['count'] }} orang</div>
                                                <small class="text-muted">
                                                    Rp {{ number_format($departmentStats['karyawan']['total_pay'], 0, ',', '.') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-secondary">
                                <div class="text-center">
                                    <h5 class="text-white">Total Keseluruhan</h5>
                                    <h4 class="text-success">
                                        Rp {{ number_format($departmentStats['staff']['total_pay'] + $departmentStats['karyawan']['total_pay'], 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Top Employees --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-trophy me-2"></i>Top 5 Lembur Terbanyak
                                </h5>
                            </div>
                            <div class="card-body">
                                @forelse($topEmployees as $index => $data)
                                    <div class="d-flex align-items-center justify-between mb-3 p-2 bg-dark bg-opacity-25 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 30px; height: 30px;">
                                                <span class="text-dark fw-bold small">#{{ $index + 1 }}</span>
                                            </div>
                                            <div>
                                                <div class="text-white fw-semibold small">{{ $data['employee']->nama }}</div>
                                                <small class="text-muted">{{ ucfirst($data['employee']->departemen) }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $hours = floor($data['total_minutes'] / 60);
                                                $minutes = $data['total_minutes'] % 60;
                                            @endphp
                                            <div class="text-white fw-semibold small">{{ $hours }}j {{ $minutes }}m</div>
                                            <small class="text-success">Rp {{ number_format($data['total_pay'], 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-trophy display-6 d-block mb-2"></i>
                                        <p class="mb-0">Belum ada data lembur bulan ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detailed Records --}}
                @if($records->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-list-ul me-2"></i>Detail Lembur Bulan {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Karyawan</th>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Uang Lembur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $record)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 35px; height: 35px;">
                                                        <span class="text-white fw-bold small">
                                                            {{ substr($record->employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $record->employee->nama }}</div>
                                                        <small class="text-muted">{{ ucfirst($record->employee->departemen) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-white">{{ $record->tanggal->format('d M Y') }}</td>
                                            <td class="text-white">{{ $record->formatted_duration }}</td>
                                            <td class="text-success fw-bold">{{ $record->formatted_overtime_pay }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection

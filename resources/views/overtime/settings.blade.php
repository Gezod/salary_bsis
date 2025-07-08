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
                        <a class="nav-link active-link" href="{{ route('overtime.settings') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('overtime.recap') }}">
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
                            <i class="bi bi-gear text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Pengaturan Lembur</span>
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
                <div class="mb-4">
                    <h1 class="page-title mb-2">Pengaturan Tarif Lembur</h1>
                    <p class="text-muted mb-0">Kelola tarif dan aturan perhitungan lembur</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-gear me-2"></i>Konfigurasi Tarif
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('overtime.settings.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="form-label text-muted">Tarif Lembur Staff (per jam)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="staff_rate" value="{{ $settings->staff_rate }}"
                                                class="form-control @error('staff_rate') is-invalid @enderror" required>
                                        </div>
                                        @error('staff_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Tarif per jam untuk staff yang bekerja lembur</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-muted">Tarif Lembur Karyawan (per jam)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="karyawan_rate" value="{{ $settings->karyawan_rate }}"
                                                class="form-control @error('karyawan_rate') is-invalid @enderror" required>
                                        </div>
                                        @error('karyawan_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Tarif per jam untuk karyawan yang bekerja lembur</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-muted">Minimum Waktu Lembur (menit)</label>
                                        <input type="number" name="minimum_minutes" value="{{ $settings->minimum_minutes }}"
                                            class="form-control @error('minimum_minutes') is-invalid @enderror" required>
                                        @error('minimum_minutes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Waktu minimum untuk mendapatkan uang lembur</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-2"></i>Simpan Pengaturan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-calculator me-2"></i>Contoh Perhitungan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-white">Staff lembur 1 jam 5 menit (65 menit):</h6>
                                    <p class="text-success mb-0">
                                        @php
                                            $staffExample = 65 >= $settings->minimum_minutes ? round(($settings->staff_rate * 65) / 60) : 0;
                                        @endphp
                                        Rp {{ number_format($staffExample, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-white">Karyawan lembur 45 menit:</h6>
                                    <p class="text-success mb-0">
                                        @php
                                            $karyawanExample = 45 >= $settings->minimum_minutes ? round(($settings->karyawan_rate * 45) / 60) : 0;
                                        @endphp
                                        Rp {{ number_format($karyawanExample, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-white">Staff lembur 25 menit:</h6>
                                    <p class="text-muted mb-0">
                                        @if(25 < $settings->minimum_minutes)
                                            Rp 0 (dibawah minimum)
                                        @else
                                            @php
                                                $staffShort = round(($settings->staff_rate * 25) / 60);
                                            @endphp
                                            Rp {{ number_format($staffShort, 0, ',', '.') }}
                                        @endif
                                    </p>
                                </div>

                                <hr class="border-secondary">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Perhitungan: (tarif_per_jam × menit_lembur) ÷ 60<br>
                                    Jam pulang: Senin-Kamis & Sabtu = 16:30, Jumat = 16:00
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

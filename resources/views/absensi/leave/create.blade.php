@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_manual.css') }}" rel="stylesheet">

    <div class="container-fluid min-vh-100 px-0">
        <div class="row g-0">
            {{-- Sidebar --}}
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
                            <a class="nav-link active-link" href="{{ route('absensi.leave.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span>Rekap Manual Izin</span>
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
                                <i class="bi bi-file-earmark-medical text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Tambah Izin</span>
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
                            <h1 class="page-title mb-2">Tambah Data Izin</h1>
                            <p class="text-muted mb-0">Input data izin karyawan secara manual</p>
                        </div>
                    </div>

                    {{-- Notifications --}}
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

                    {{-- Form Section --}}
                    <div class="form-section">
                        <form method="POST" action="{{ route('absensi.leave.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-person me-2"></i>Pilih Karyawan
                                    </label>
                                    <select name="employee_id" class="form-control" required>
                                        <option value="">Pilih Karyawan</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->nama }} - {{ $employee->departemen }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar me-2"></i>Tanggal Izin
                                    </label>
                                    <input type="date" name="tanggal_izin" class="form-control"
                                        value="{{ old('tanggal_izin', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-chat-text me-2"></i>Alasan Izin
                                </label>
                                <textarea name="alasan_izin" class="form-control" rows="4" placeholder="Masukkan alasan izin..." required>{{ old('alasan_izin') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-image me-2"></i>Bukti Foto (Opsional)
                                </label>
                                <input type="file" name="bukti_foto" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save me-2"></i>Simpan Data Izin
                                </button>
                                <a href="{{ route('absensi.leave.index') }}" class="btn btn-outline-info btn-lg ms-3">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
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

            // Update toggle icon
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }
    </script>
@endsection

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
                            <a class="nav-link" href="{{ route('absensi.leave.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span>Rekap Manual Izin</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active-link" href="{{ route('absensi.work_time_change.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Pergantian Jam Kerja</span>
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
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Tambah Pergantian Jam</span>
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
                            <h1 class="page-title mb-2">Tambah Pergantian Jam Kerja</h1>
                            <p class="text-muted mb-0">Input pergantian jam kerja berdasarkan izin</p>
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
                        <form method="POST" action="{{ route('absensi.work_time_change.store') }}">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-file-earmark-medical me-2"></i>Data Izin
                                    </label>
                                    <select name="leave_id" class="form-control" required>
                                        <option value="">Pilih Data Izin</option>
                                        @foreach ($leaves as $leave)
                                            <option value="{{ $leave->id }}"
                                                {{ old('leave_id') == $leave->id ? 'selected' : '' }}>
                                                {{ $leave->nama }} - {{ $leave->tanggal_izin->format('d M Y') }}
                                                ({{ $leave->alasan_izin }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar me-2"></i>Tanggal Pergantian
                                    </label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-clock me-2"></i>Jam Masuk Baru
                                    </label>
                                    <input type="time" name="jam_masuk_baru" class="form-control"
                                        value="{{ old('jam_masuk_baru') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-clock me-2"></i>Jam Pulang Baru
                                    </label>
                                    <input type="time" name="jam_pulang_baru" class="form-control"
                                        value="{{ old('jam_pulang_baru') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-pause-circle me-2"></i>Jam Istirahat Mulai
                                    </label>
                                    <input type="time" name="jam_istirahat_mulai" class="form-control"
                                        value="{{ old('jam_istirahat_mulai') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-play-circle me-2"></i>Jam Istirahat Selesai
                                    </label>
                                    <input type="time" name="jam_istirahat_selesai" class="form-control"
                                        value="{{ old('jam_istirahat_selesai') }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-chat-text me-2"></i>Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control" rows="4" placeholder="Masukkan keterangan pergantian jam...">{{ old('keterangan') }}</textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save me-2"></i>Simpan Pergantian Jam
                                </button>
                                <a href="{{ route('absensi.work_time_change.index') }}"
                                    class="btn btn-outline-info btn-lg ms-3">
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

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
                            <a class="nav-link {{ request()->routeIs('absensi.recap') ? 'active-link' : '' }}"
                                href="{{ route('absensi.recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="fine-icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <span>Rekap Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.denda') ? 'active-link' : '' }}"
                                href="{{ route('absensi.denda') }}">
                                <div class="d-flex align-items-center">
                                    <div class="fine-icon-wrapper me-3">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <span>Pengaturan Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.denda.individual') ? 'active-link' : '' }}"
                                href="{{ route('absensi.denda.individual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="fine-icon-wrapper me-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <span>Denda Individu</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active-link' : '' }}"
                                href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="back-icon-wrapper me-3"> <!-- Ganti class disini -->
                                        <i class="bi bi-arrow-bar-left"></i>
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
                                <i class="bi bi-speedometer2"></i>
                            </div>
                            <span class="navbar-brand fw-bold mb-0">Pengaturan Denda</span>
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
                            <h1 class="page-title mb-2">Pengaturan Denda</h1>
                            <p class="mb-0 text-muted">Kelola tarif denda berdasarkan departemen dan jenis pelanggaran</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" onclick="recalculateAllFines()">
                                <i class="bi bi-calculator me-2"></i>Hitung Ulang Semua Denda
                            </button>
                        </div>
                    </div>

                    {{-- Current Penalty Info --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tarif Denda Saat Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">STAFF</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="bi bi-clock me-2 text-danger"></i><strong>Keterlambatan:</strong></li>
                                                <li class="ms-4">• 1-15 menit: <span class="text-danger">Rp {{ number_format($penalties['staff']['late'][0][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 16-30 menit: <span class="text-danger">Rp {{ number_format($penalties['staff']['late'][1][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 31-45 menit: <span class="text-danger">Rp {{ number_format($penalties['staff']['late'][2][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 46-60 menit: <span class="text-danger">Rp {{ number_format($penalties['staff']['late'][3][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• >60 menit: <span class="text-danger">Rp 12.000 + (menit - 60)</span></li>
                                                <hr class="my-2">
                                                <li><i class="bi bi-cup-hot me-2 text-warning"></i><strong>Telat istirahat:</strong> <span class="text-warning">Rp {{ number_format($penalties['staff']['late_break'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-person-x me-2 text-info"></i><strong>Lupa absen masuk:</strong> <span class="text-info">Rp {{ number_format($penalties['staff']['missing_checkin'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-person-dash me-2 text-info"></i><strong>Lupa absen pulang:</strong> <span class="text-info">Rp {{ number_format($penalties['staff']['missing_checkout'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-pause-circle me-2 text-secondary"></i><strong>1x absen istirahat:</strong> <span class="text-secondary">Rp {{ number_format($penalties['staff']['absent_break_once'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-pause-circle-fill me-2 text-secondary"></i><strong>2x absen istirahat:</strong> <span class="text-secondary">Rp {{ number_format($penalties['staff']['absent_twice'], 0, ',', '.') }}</span></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-success mb-3">KARYAWAN</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="bi bi-clock me-2 text-danger"></i><strong>Keterlambatan:</strong></li>
                                                <li class="ms-4">• 1-15 menit: <span class="text-danger">Rp {{ number_format($penalties['karyawan']['late'][0][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 16-30 menit: <span class="text-danger">Rp {{ number_format($penalties['karyawan']['late'][1][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 31-45 menit: <span class="text-danger">Rp {{ number_format($penalties['karyawan']['late'][2][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• 46-60 menit: <span class="text-danger">Rp {{ number_format($penalties['karyawan']['late'][3][2], 0, ',', '.') }}</span></li>
                                                <li class="ms-4">• >60 menit: <span class="text-danger">Rp 10.000 + (menit - 60)</span></li>
                                                <hr class="my-2">
                                                <li><i class="bi bi-cup-hot me-2 text-warning"></i><strong>Telat istirahat:</strong> <span class="text-warning">Rp {{ number_format($penalties['karyawan']['late_break'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-person-x me-2 text-info"></i><strong>Lupa absen masuk:</strong> <span class="text-info">Rp {{ number_format($penalties['karyawan']['missing_checkin'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-person-dash me-2 text-info"></i><strong>Lupa absen pulang:</strong> <span class="text-info">Rp {{ number_format($penalties['karyawan']['missing_checkout'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-pause-circle me-2 text-secondary"></i><strong>1x absen istirahat:</strong> <span class="text-secondary">Rp {{ number_format($penalties['karyawan']['absent_break_once'], 0, ',', '.') }}</span></li>
                                                <li><i class="bi bi-pause-circle-fill me-2 text-secondary"></i><strong>2x absen istirahat:</strong> <span class="text-secondary">Rp {{ number_format($penalties['karyawan']['absent_twice'], 0, ',', '.') }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('absensi.denda.update') }}">
                        @csrf
                        <div class="row">
                            {{-- Staff Penalties --}}
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Denda Staff</h5>
                                    </div>
                                    <div class="card-body">
                                        {{-- Late Penalties --}}
                                        <h6 class="mb-3 d-flex align-items-center">
                                            <i class="bi bi-clock-history me-2 text-primary"></i>
                                            Denda Keterlambatan
                                        </h6>
                                        @foreach($penalties['staff']['late'] as $index => $range)
                                            @if($range[0] !== '>')
                                                <div class="mb-3">
                                                    <label class="form-label small">
                                                        {{ $range[0] }}-{{ $range[1] }} menit
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number"
                                                               name="staff[late][{{ $index }}][2]"
                                                               value="{{ $range[2] }}"
                                                               class="form-control"
                                                               min="0"
                                                               required>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        <div class="mb-3">
                                            <label class="form-label small">Base denda untuk > 60 menit</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[late_base]"
                                                       value="12000"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                            <small class="text-muted">Denda akan ditambah setiap menit kelebihan</small>
                                        </div>

                                        <hr>

                                        {{-- Other Penalties --}}
                                        <h6 class="mb-3 d-flex align-items-center">
                                            <i class="bi bi-exclamation-circle me-2 text-primary"></i>
                                            Denda Lainnya
                                        </h6>

                                        <div class="mb-3">
                                            <label class="form-label small">Telat Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[late_break]"
                                                       value="{{ $penalties['staff']['late_break'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">Lupa Absen Masuk</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[missing_checkin]"
                                                       value="{{ $penalties['staff']['missing_checkin'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">Lupa Absen Pulang</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[missing_checkout]"
                                                       value="{{ $penalties['staff']['missing_checkout'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">1x Tidak Absen Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[absent_break_once]"
                                                       value="{{ $penalties['staff']['absent_break_once'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">2x Tidak Absen Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="staff[absent_twice]"
                                                       value="{{ $penalties['staff']['absent_twice'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Karyawan Penalties --}}
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Denda Karyawan</h5>
                                    </div>
                                    <div class="card-body">
                                        {{-- Late Penalties --}}
                                        <h6 class="mb-3 d-flex align-items-center">
                                            <i class="bi bi-clock-history me-2 text-success"></i>
                                            Denda Keterlambatan
                                        </h6>
                                        @foreach($penalties['karyawan']['late'] as $index => $range)
                                            @if($range[0] !== '>')
                                                <div class="mb-3">
                                                    <label class="form-label small">
                                                        {{ $range[0] }}-{{ $range[1] }} menit
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number"
                                                               name="karyawan[late][{{ $index }}][2]"
                                                               value="{{ $range[2] }}"
                                                               class="form-control"
                                                               min="0"
                                                               required>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        <div class="mb-3">
                                            <label class="form-label small">Base denda untuk > 60 menit</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[late_base]"
                                                       value="10000"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                            <small class="text-muted">Denda akan ditambah setiap menit kelebihan</small>
                                        </div>

                                        <hr>

                                        {{-- Other Penalties --}}
                                        <h6 class="mb-3 d-flex align-items-center">
                                            <i class="bi bi-exclamation-circle me-2 text-success"></i>
                                            Denda Lainnya
                                        </h6>

                                        <div class="mb-3">
                                            <label class="form-label small">Telat Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[late_break]"
                                                       value="{{ $penalties['karyawan']['late_break'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">Lupa Absen Masuk</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[missing_checkin]"
                                                       value="{{ $penalties['karyawan']['missing_checkin'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">Lupa Absen Pulang</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[missing_checkout]"
                                                       value="{{ $penalties['karyawan']['missing_checkout'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">1x Tidak Absen Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[absent_break_once]"
                                                       value="{{ $penalties['karyawan']['absent_break_once'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small">2x Tidak Absen Istirahat</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number"
                                                       name="karyawan[absent_twice]"
                                                       value="{{ $penalties['karyawan']['absent_twice'] }}"
                                                       class="form-control"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </main>
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

            // Close sidebar when overlay is clicked
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

        function recalculateAllFines() {
            Swal.fire({
                title: 'Konfirmasi Hitung Ulang',
                text: 'Apakah Anda yakin ingin menghitung ulang semua denda? Proses ini akan memakan waktu beberapa menit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hitung Ulang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghitung Ulang...',
                        text: 'Sedang memproses semua data absensi',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route("absensi.reevaluate-all") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Semua denda berhasil dihitung ulang',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghitung ulang'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem'
                        });
                    });
                }
            });
        }
    </script>
@endsection

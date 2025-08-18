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
                        <small class="text-muted">Sistem Payroll</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <span>Payroll Bulanan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('weekly-payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-calendar-week"></i>
                                    </div>
                                    <span>Payroll Mingguan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bpjs.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <span>Pengaturan BPJS</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link active" href="{{ route('bpjs.premiums') }}">
                            <div class="d-flex align-items-center">
                                <div class="cash-icon-wrapper me-3">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <span>Premi BPJS</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="back-icon-wrapper me-3">
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
                                <i class="bi bi-person-badge text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Detail Payroll Mingguan</span>
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
                            <h1 class="page-title mb-2">{{ $weeklyPayroll->employee->nama }}</h1>
                            <p class="text-muted mb-0">Payroll Mingguan {{ $weeklyPayroll->period_name }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            @if ($weeklyPayroll->status === 'paid')
                                <a href="{{ route('weekly-payroll.download.individual', $weeklyPayroll->id) }}" class="btn btn-success">
                                    <i class="bi bi-download me-2"></i>Download Slip Gaji
                                </a>
                            @endif
                            <a href="{{ route('weekly-payroll.recalculate', $weeklyPayroll->id) }}" class="btn btn-warning">
                                <i class="bi bi-arrow-clockwise me-2"></i>Hitung Ulang
                            </a>
                            <a href="{{ route('weekly-payroll.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        {{-- Employee Info --}}
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-person me-2"></i>Informasi Karyawan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                            style="width: 80px; height: 80px;">
                                            <span class="text-white fw-bold fs-2">
                                                {{ substr($weeklyPayroll->employee->nama, 0, 1) }}
                                            </span>
                                        </div>
                                        <h5 class="text-white">{{ $weeklyPayroll->employee->nama }}</h5>
                                        <span
                                            class="badge {{ $weeklyPayroll->employee->departemen == 'staff' ? 'bg-info' : 'bg-secondary' }}">{{ ucfirst($weeklyPayroll->employee->departemen) }}</span>
                                    </div>
                                    <hr class="border-secondary">
                                    <div class="row g-2 text-sm">
                                        <div class="col-6 text-muted">NIP:</div>
                                        <div class="col-6 text-white">{{ $weeklyPayroll->employee->nip }}</div>
                                        <div class="col-6 text-muted">Jabatan:</div>
                                        <div class="col-6 text-white">{{ $weeklyPayroll->employee->jabatan }}</div>
                                        <div class="col-6 text-muted">Kantor:</div>
                                        <div class="col-6 text-white">{{ $weeklyPayroll->employee->kantor }}</div>
                                        @if($weeklyPayroll->employee->departemen == 'karyawan')
                                        <div class="col-6 text-muted">Gaji Harian:</div>
                                        <div class="col-6 text-white">Rp
                                            {{ number_format($weeklyPayroll->employee->daily_salary ?? 0, 0, ',', '.') }}</div>
                                        <div class="col-6 text-muted">Uang Makan:</div>
                                        <div class="col-6 text-info">Rp
                                            {{ number_format($weeklyPayroll->employee->meal_allowance ?? 0, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            BPJS hanya dipotong dari gaji bulanan, tidak dari gaji mingguan.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payroll Details --}}
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-calculator me-2"></i>Rincian Payroll Mingguan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="text-white mb-3">Kehadiran & Periode</h6>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6 text-muted">Periode:</div>
                                                <div class="col-6 text-white">{{ $weeklyPayroll->period_name }}</div>
                                                <div class="col-6 text-muted">Hari Kerja:</div>
                                                <div class="col-6 text-white">{{ $weeklyPayroll->working_days }} hari</div>
                                                <div class="col-6 text-muted">Hadir:</div>
                                                <div class="col-6 text-white">{{ $weeklyPayroll->present_days }} hari</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-white mb-3">Perhitungan</h6>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6 text-muted">Gaji Pokok:</div>
                                                <div class="col-6 text-white">{{ $weeklyPayroll->formatted_basic_salary }}</div>
                                                <div class="col-6 text-muted">Lembur:</div>
                                                <div class="col-6 text-success">{{ $weeklyPayroll->formatted_overtime_pay }}
                                                </div>
                                                <div class="col-6 text-muted">Uang Makan:</div>
                                                <div class="col-6 text-info">{{ $weeklyPayroll->formatted_meal_allowance }}
                                                </div>
                                                @if($weeklyPayroll->employee->departemen != 'staff')
                                                <div class="col-6 text-muted">Denda:</div>
                                                <div class="col-6 text-warning">{{ $weeklyPayroll->formatted_total_fines }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-secondary">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <h6 class="text-white">Gaji Kotor:</h6>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-white">{{ $weeklyPayroll->formatted_gross_salary }}</h6>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-white">Total Gaji Bersih:</h5>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-success">{{ $weeklyPayroll->formatted_net_salary }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($weeklyPayroll->status === 'paid')
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="text-muted small">Metode Pembayaran</label>
                                                <div>
                                                    <span class="badge {{ $weeklyPayroll->payment_method_badge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $weeklyPayroll->payment_method)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-muted small">Tanggal Pembayaran</label>
                                                <div class="text-white">{{ $weeklyPayroll->payment_date->format('d M Y') }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-muted small">Catatan</label>
                                                <div class="text-white">{{ $weeklyPayroll->notes ?: '-' }}</div>
                                            </div>
                                            @if ($weeklyPayroll->payment_proof)
                                                <div class="col-12">
                                                    <label class="text-muted small">Bukti Pembayaran</label>
                                                    <div>
                                                        <img src="{{ Storage::url($weeklyPayroll->payment_proof) }}"
                                                            alt="Payment Proof" class="img-thumbnail"
                                                            style="max-width: 300px;">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('weekly-payroll.update.payment', $weeklyPayroll->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label text-white">Metode Pembayaran</label>
                                                    <select name="payment_method" class="form-control" required
                                                        id="paymentMethod">
                                                        <option value="">Pilih Metode</option>
                                                        <option value="cash">Cash</option>
                                                        <optgroup label="Bank Nasional">
                                                            <option value="bca">BCA</option>
                                                            <option value="mandiri">Bank Mandiri</option>
                                                            <option value="bri">BRI</option>
                                                            <option value="bni">BNI</option>
                                                            <option value="cimb">CIMB Niaga</option>
                                                            <option value="danamon">Bank Danamon</option>
                                                            <option value="permata">Bank Permata</option>
                                                            <option value="btn">BTN</option>
                                                            <option value="mega">Bank Mega</option>
                                                            <option value="maybank">Maybank</option>
                                                            <option value="ocbc">OCBC NISP</option>
                                                            <option value="panin">Panin Bank</option>
                                                        </optgroup>
                                                        <optgroup label="Bank Daerah">
                                                            <option value="bjb">BJB</option>
                                                            <option value="bank_jatim">Bank Jatim</option>
                                                            <option value="bank_jateng">Bank Jateng</option>
                                                            <option value="bank_dki">Bank DKI</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-white">Tanggal Pembayaran</label>
                                                    <input type="date" name="payment_date" class="form-control"
                                                        value="{{ now()->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-white">Bukti Pembayaran</label>
                                                    <input type="file" name="payment_proof" class="form-control"
                                                        accept="image/*">
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="bi bi-check-lg me-2"></i>Bayar
                                                    </button>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-white">Catatan</label>
                                                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan pembayaran..."></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
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

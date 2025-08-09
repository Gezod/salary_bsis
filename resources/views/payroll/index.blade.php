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
                            <a class="nav-link active-link" href="{{ route('payroll.index') }}">
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
                            <a class="nav-link" href="{{ route('payroll.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <span>Pengaturan Karyawan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payroll.staff.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Pengaturan Staff</span>
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
                                <i class="bi bi-cash-stack text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Payroll Bulanan</span>
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
                            <h1 class="page-title mb-2">Data Payroll Bulanan</h1>
                            <p class="text-muted mb-0">Kelola dan pantau penggajian karyawan dan staff bulanan dengan sistem BPJS terintegrasi</p>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="stats-card">
                                <div class="fw-bold text-white fs-4">{{ $payrolls->total() }}</div>
                                <small class="text-muted">Total Records</small>
                            </div>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#generateModal">
                                <i class="bi bi-plus-circle me-2"></i>Generate Payroll
                            </button>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Filter Section --}}
                    <div class="filter-section">
                        <h5 class="text-white mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Data
                        </h5>
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Bulan & Tahun</label>
                                <input type="month" name="month" value="{{ request('month') }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Nama Karyawan/Staff</label>
                                <input type="text" name="employee" value="{{ request('employee') }}"
                                    class="form-control" placeholder="Cari nama...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Departemen</label>
                                <select name="department" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="staff" {{ request('department') == 'staff' ? 'selected' : '' }}>Staff
                                    </option>
                                    <option value="karyawan" {{ request('department') == 'karyawan' ? 'selected' : '' }}>
                                        Karyawan</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Filter
                                </button>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger w-100" onclick="exportPdf()">
                                    <i class="bi bi-file-pdf me-2"></i>PDF
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Karyawan/Staff</th>
                                        <th><i class="bi bi-calendar me-2"></i>Periode</th>
                                        <th><i class="bi bi-calendar-check me-2"></i>Kehadiran</th>
                                        <th><i class="bi bi-cash me-2"></i>Gaji Pokok</th>
                                        <th><i class="bi bi-clock me-2"></i>Lembur</th>
                                        <th><i class="bi bi-cup-hot me-2"></i>Uang Makan</th>
                                        <th><i class="bi bi-exclamation-triangle me-2"></i>Denda</th>
                                        <th><i class="bi bi-shield-check me-2"></i>BPJS</th>
                                        <th><i class="bi bi-currency-dollar me-2"></i>Total</th>
                                        <th><i class="bi bi-check-circle me-2"></i>Status</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payrolls as $payroll)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($payroll->employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $payroll->employee->nama }}
                                                        </div>
                                                        <small class="badge {{ $payroll->employee->departemen == 'staff' ? 'bg-info' : 'bg-secondary' }}">
                                                            {{ ucfirst($payroll->employee->departemen) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-white">{{ $payroll->month_name }}</td>
                                            <td class="text-white">
                                                {{ $payroll->present_days }}/{{ $payroll->working_days }} hari
                                                @if($payroll->employee->departemen == 'staff')
                                                    <small class="text-muted d-block">(Fixed Monthly)</small>
                                                @endif
                                            </td>
                                            <td class="text-white">{{ $payroll->formatted_basic_salary }}</td>
                                            <td class="text-success">{{ $payroll->formatted_overtime_pay }}</td>
                                            <td class="text-info">
                                                {{ $payroll->formatted_meal_allowance }}
                                                @if($payroll->employee->departemen == 'staff')
                                                    <small class="text-muted d-block">({{ $payroll->present_days }} hari hadir)</small>
                                                @endif
                                            </td>
                                            <td class="text-warning">
                                                @if($payroll->employee->departemen == 'staff')
                                                    <span class="text-muted">-</span>
                                                @else
                                                    {{ $payroll->formatted_total_fines }}
                                                @endif
                                            </td>
                                            <td class="text-danger">
                                                @if($payroll->bpjs_deduction > 0)
                                                    {{ $payroll->formatted_bpjs_deduction }}
                                                    @if($payroll->bpjs_setting)
                                                        <small class="text-muted d-block">{{ $payroll->bpjs_setting->bpjs_number }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-white fw-bold">{{ $payroll->formatted_net_salary }}</td>
                                            <td>
                                                <span class="badge {{ $payroll->status_badge }}">
                                                    {{ ucfirst($payroll->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payroll.show', $payroll->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye fs-2"></i>
                                                    </a>
                                                    <a href="{{ route('payroll.recalculate', $payroll->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="bi bi-arrow-clockwise fs-2"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data payroll bulanan untuk filter yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($payrolls->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payrolls->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    {{-- Generate Modal --}}
    <div class="modal fade" id="generateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-white">Generate Payroll Bulanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('payroll.generate') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-white">Bulan & Tahun</label>
                            <input type="month" name="month" class="form-control" required>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Sistem akan menghitung payroll untuk semua karyawan dan staff.
                            <br><strong>Staff:</strong> Gaji tetap bulanan dengan uang makan berdasarkan kehadiran.
                            <br><strong>BPJS:</strong> Akan dipotong otomatis sesuai pengaturan bulanan.
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function exportPdf() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            window.open(`{{ route('payroll.export.pdf') }}?${params.toString()}`, '_blank');
        }

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

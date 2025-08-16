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
                            <a class="nav-link active-link" href="{{ route('weekly-payroll.index') }}">
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
                                <i class="bi bi-calendar-week text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Payroll Mingguan</span>
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
                            <h1 class="page-title mb-2">Data Payroll Mingguan</h1>
                            <p class="text-muted mb-0">Kelola dan pantau penggajian karyawan mingguan dengan rentang tanggal fleksibel</p>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="stats-card">
                                <div class="fw-bold text-white fs-4">{{ $weeklyPayrolls->total() }}</div>
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

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Info Alert --}}
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading" style="color:#405551 !important;"><i class="bi bi-info-circle me-2" ></i>Informasi Payroll Mingguan:</h5>
                        <hr>
                        <ul class="mb-0">
                            <li><strong>Payroll Mingguan:</strong> Hanya untuk karyawan (bukan staff)</li>
                            <li><strong>BPJS:</strong> Hanya dipotong di gaji mingguan akhir bulan <span class="badge bg-warning text-dark">TANGGAL 28-31</span></li>
                            <li><strong>Total Premi BPJS:</strong> Langsung mengurangi pendapatan kotor untuk menghasilkan gaji bersih</li>
                            <li><strong>Rentang Tanggal:</strong> Dapat disesuaikan sesuai kebutuhan (tidak harus 7 hari)</li>
                        </ul>
                    </div>

                    {{-- Filter Section --}}
                    <div class="filter-section">
                        <h5 class="text-white mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Data
                        </h5>
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Rentang Tanggal</label>
                                <input type="text" name="date_range" value="{{ request('date_range') }}" class="form-control" id="dateRange" placeholder="Pilih rentang tanggal" autocomplete="off">
                                <small class="form-text text-muted">Format: DD/MM/YYYY - DD/MM/YYYY</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Nama Karyawan</label>
                                <input type="text" name="employee" value="{{ request('employee') }}"
                                    class="form-control" placeholder="Cari nama...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small">Departemen</label>
                                <select name="department" class="form-control">
                                    <option value="">Semua</option>
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
                                        <th><i class="bi bi-person me-2"></i>Karyawan</th>
                                        <th><i class="bi bi-calendar-range me-2"></i>Periode</th>
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
                                    @forelse ($weeklyPayrolls as $payroll)
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
                                                        <small class="badge bg-secondary">
                                                            {{ ucfirst($payroll->employee->departemen) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-white">
                                                {{ $payroll->period_name }}
                                                <small class="text-muted d-block">
                                                    ({{ $payroll->start_date->diffInDays($payroll->end_date) + 1 }} hari kalender)
                                                </small>
                                                @if($payroll->isEndOfMonthPeriod())
                                                    <small class="badge bg-warning text-dark">Akhir Bulan ({{ $payroll->end_date->day }})</small>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                {{ $payroll->present_days }}/{{ $payroll->working_days }} hari
                                                @if($payroll->isEndOfMonthPeriod())
                                                    <small class="text-warning d-block">Termasuk BPJS</small>
                                                @else
                                                    <small class="text-success d-block">Tanpa BPJS</small>
                                                @endif
                                            </td>
                                            <td class="text-white">{{ $payroll->formatted_basic_salary }}</td>
                                            <td class="text-success">{{ $payroll->formatted_overtime_pay }}</td>
                                            <td class="text-info">{{ $payroll->formatted_meal_allowance }}</td>
                                            <td class="text-warning">{{ $payroll->formatted_total_fines }}</td>
                                            <td class="text-danger">
                                                @if($payroll->bpjs_deduction > 0)
                                                    {{ $payroll->formatted_bpjs_deduction }}
                                                    <small class="text-warning d-block">Total Premi</small>
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
                                                    <a href="{{ route('weekly-payroll.show', $payroll->id) }}"
                                                        class="btn btn-sm btn-info" title="Detail">
                                                        <i class="bi bi-eye fs-4"></i>
                                                    </a>
                                                    <a href="{{ route('weekly-payroll.recalculate', $payroll->id) }}"
                                                        class="btn btn-sm btn-warning" title="Hitung Ulang">
                                                        <i class="bi bi-arrow-clockwise fs-4"></i>
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
                                                    <p>Belum ada data payroll mingguan untuk filter yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($weeklyPayrolls->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $weeklyPayrolls->links('pagination::bootstrap-5') }}
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
                    <h5 class="modal-title text-white">Generate Payroll Mingguan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('weekly-payroll.generate') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan Penting:</strong>
                            <br>• Sistem akan menghitung payroll hanya untuk <strong>karyawan</strong> (bukan staff)
                            <br>• <strong>BPJS dipotong</strong> jika periode berakhir di tanggal <span class="badge bg-warning text-dark">28-31</span>
                            <br>• <strong>Total Premi BPJS</strong> langsung mengurangi pendapatan kotor
                            <br>• Rentang tanggal dapat disesuaikan kebutuhan
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

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- jQuery and Date Range Picker JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(function() {
            // Initialize daterangepicker with proper format handling
            $('#dateRange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom',
                    weekLabel: 'M',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    firstDay: 1
                },
                autoUpdateInput: false,
                showDropdowns: true,
                showWeekNumbers: true,
                ranges: {
                   'Hari Ini': [moment(), moment()],
                   'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                   '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                   'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                   'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Handle form submission to clear invalid date range
            $('form').on('submit', function(e) {
                const dateRange = $('#dateRange').val();
                if (dateRange && !dateRange.match(/^\d{2}\/\d{2}\/\d{4} - \d{2}\/\d{2}\/\d{4}$/)) {
                    $('#dateRange').val('');
                }
            });
        });

        function exportPdf() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            // Handle date range properly
            const dateRange = formData.get('date_range');
            if (dateRange && dateRange.trim()) {
                params.append('date_range', dateRange);
            }

            // Add other form fields
            for (let [key, value] of formData.entries()) {
                if (key !== 'date_range' && value.trim()) {
                    params.append(key, value);
                }
            }

            window.open(`{{ route('weekly-payroll.export.pdf') }}?${params.toString()}`, '_blank');
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

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        });
    </script>
@endsection

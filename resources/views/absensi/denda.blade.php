@extends('layouts.app')

@section('content')
<link href="{{ asset('css/style_denda.css') }}" rel="stylesheet">

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
                            <a class="nav-link {{ request()->routeIs('absensi.late-recap') ? 'active-link' : '' }}"
                                href="{{ route('absensi.late-recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Rekap Keterlambatan</span>
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
                            <a class="nav-link {{ request()->routeIs('absensi.leave.*') ? 'active-link' : '' }}"
                                href="{{ route('absensi.leave.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span>Rekap Manual Izin</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.work_time_change.*') ? 'active-link' : '' }}"
                                href="{{ route('absensi.work_time_change.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Pergantian Jam Kerja</span>
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
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.role') ? 'active-link' : '' }}"
                                href="{{ route('absensi.role') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Kelola karyawan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('overtime.*') ? 'active-link' : '' }}"
                                href="{{ route('overtime.overview') }}">
                                <div class="d-flex align-items-center">
                                    <div class="overtime-icon-wrapper me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <span>Sistem Lembur</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active-link' : '' }}"
                                href="{{ route('payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <span>Sistem Payroll</span>
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
                                <i class="bi bi-person-badge me-2"></i>staff
                            </div>
                            <div class="role-tab" data-role="karyawan">
                                <i class="bi bi-people me-2"></i>karyawan
                            </div>
                        </div>
                    </div>

                    {{-- Form Section --}}
                    <form method="POST" action="{{ route('absensi.denda.update') }}" id="dendaForm">
                        @csrf
                        @method('PUT')

                        {{-- staff Tab Content --}}
                        <div class="tab-content" id="staff-content">
                            <h5 class="text-white mb-4 d-flex align-items-center">
                                <i class="bi bi-person-badge me-2 text-primary"></i>
                                Pengaturan Denda staff
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

                        {{-- karyawan Tab Content --}}
                        <div class="tab-content" id="karyawan-content" style="display: none;">
                            <h5 class="text-white mb-4 d-flex align-items-center">
                                <i class="bi bi-people me-2 text-primary"></i>
                                Pengaturan Denda karyawan
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

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

                        {{-- Contract expiring notification --}}
                        @if (isset($expiringContracts) && $expiringContracts > 0)
                            <div class="contract-notification">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                <small class="text-warning">{{ $expiringContracts }} kontrak akan berakhir</small>
                            </div>
                        @endif
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
                                <div class="d-flex align-items-center position-relative">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Kelola Karyawan</span>
                                    @if (isset($expiringContracts) && $expiringContracts > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $expiringContracts }}
                                            <span class="visually-hidden">contracts expiring</span>
                                        </span>
                                    @endif
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
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Sistem Absensi</span>
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
                    {{-- Contract Expiring Alert --}}
                    @if (isset($expiringContracts) && $expiringContracts > 0)
                        <div class="alert alert-warning d-flex align-items-center mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Peringatan!</strong> Ada {{ $expiringContracts }} karyawan yang kontraknya akan
                                berakhir dalam 7 hari ke depan.
                                Silakan periksa dan perpanjang kontrak jika diperlukan.
                            </div>
                        </div>
                    @endif

                    {{-- Page Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="page-title mb-2">Kelola Karyawan & Staff</h1>
                            <p class="text-muted mb-0">Tambah, edit, dan kelola data karyawan dan staff</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="stats-card">
                                <div class="fw-bold text-white fs-4">{{ $employees->total() }}</div>
                                <small class="text-muted">Total Karyawan</small>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addEmployeeModal">
                                <i class="bi bi-person-plus me-2"></i>Tambah Karyawan
                            </button>
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

                    {{-- Enhanced Filter Section --}}
                    <div class="filter-section">
                        <h5 class="text-white mb-3 d-flex align-items-center">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Filter Data
                        </h5>
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Nama/NIP/PIN</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Cari nama, NIP, atau PIN...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Departemen</label>
                                <select name="departemen" class="form-control">
                                    <option value="">Semua Departemen</option>
                                    <option value="staff" {{ request('departemen') == 'staff' ? 'selected' : '' }}>Staff
                                    </option>
                                    <option value="karyawan" {{ request('departemen') == 'karyawan' ? 'selected' : '' }}>
                                        Karyawan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Jabatan</label>
                                <input type="text" name="jabatan" value="{{ request('jabatan') }}"
                                    class="form-control" placeholder="Filter berdasarkan jabatan...">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Filter
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
                                        <th><i class="bi bi-person me-2"></i>Karyawan</th>
                                        <th><i class="bi bi-credit-card me-2"></i>PIN/NIP</th>
                                        <th><i class="bi bi-briefcase me-2"></i>Jabatan</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th><i class="bi bi-geo-alt me-2"></i>Kantor</th>
                                        <th><i class="bi bi-calendar-range me-2"></i>Kontrak</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr class="{{ $employee->isContractExpiringSoon() ? 'table-danger' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($employee->nama ?? 'N', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="{{ $employee->isContractExpiringSoon() ? 'text-dark' : 'text-white' }} fw-semibold">
                                                            {{ $employee->nama ?? '-' }}
                                                        </div>
                                                        <small
                                                            class=" {{ $employee->isContractExpiringSoon() ? 'text-dark' : 'text-muted ' }} fw-bold ">ID:
                                                            {{ $employee->id }}</small>
                                                        @if ($employee->isContractExpiringSoon())
                                                            <small class="text-danger d-block">
                                                                <i class="bi bi-exclamation-triangle"></i>
                                                                Kontrak berakhir {{ $employee->contract_days_remaining }}
                                                                hari lagi
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-white">
                                                    <div
                                                        class="{{ $employee->isContractExpiringSoon() ? 'text-dark' : 'text-white' }}">
                                                        <strong>PIN:</strong> {{ $employee->pin ?? '-' }}
                                                    </div>
                                                    <div
                                                        class="{{ $employee->isContractExpiringSoon() ? 'text-dark' : 'text-white' }}">
                                                        <strong>NIP:</strong> {{ $employee->nip ?? '-' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $employee->jabatan ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $employee->departemen == 'staff' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($employee->departemen ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="text-white">{{ $employee->kantor ?? '-' }}</td>
                                            <td class="text-white">
                                                @if ($employee->tanggal_start_kontrak && $employee->tanggal_end_kontrak)
                                                    <div class="small">
                                                        <div><strong>Mulai:</strong>
                                                            {{ $employee->tanggal_start_kontrak->format('d M Y') }}</div>
                                                        <div><strong>Berakhir:</strong>
                                                            {{ $employee->tanggal_end_kontrak->format('d M Y') }}</div>
                                                    </div>
                                                @else
                                                    <div class="text-muted">-</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        onclick="editEmployee({{ $employee->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteEmployee({{ $employee->id }}, '{{ $employee->nama }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-people display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data karyawan</h5>
                                                    <p>Belum ada data karyawan untuk filter yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Enhanced Pagination --}}
                    @if ($employees->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $employees->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    {{-- Add Employee Modal --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                    <h5 class="modal-title text-white" id="addEmployeeModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Tambah Karyawan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('absensi.role.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">PIN</label>
                                <input type="text" name="pin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">NIP</label>
                                <input type="text" name="nip" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Departemen</label>
                                <select name="departemen" class="form-control" required>
                                    <option value="">Pilih Departemen</option>
                                    <option value="staff">Staff</option>
                                    <option value="karyawan">Karyawan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white">Kantor</label>
                                <input type="text" name="kantor" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Mulai Kontrak</label>
                                <input type="date" name="tanggal_start_kontrak" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Berakhir Kontrak</label>
                                <input type="date" name="tanggal_end_kontrak" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Karyawan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Employee Modal --}}
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                    <h5 class="modal-title text-white" id="editEmployeeModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Data Karyawan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">PIN</label>
                                <input type="text" name="pin" id="edit_pin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">NIP</label>
                                <input type="text" name="nip" id="edit_nip" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white">Nama Lengkap</label>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Jabatan</label>
                                <input type="text" name="jabatan" id="edit_jabatan" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Departemen</label>
                                <select name="departemen" id="edit_departemen" class="form-control" required>
                                    <option value="">Pilih Departemen</option>
                                    <option value="staff">Staff</option>
                                    <option value="karyawan">Karyawan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white">Kantor</label>
                                <input type="text" name="kantor" id="edit_kantor" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Mulai Kontrak</label>
                                <input type="date" name="tanggal_start_kontrak" id="edit_tanggal_start_kontrak"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Tanggal Berakhir Kontrak</label>
                                <input type="date" name="tanggal_end_kontrak" id="edit_tanggal_end_kontrak"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Karyawan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form method="POST" id="deleteEmployeeForm" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Toggle sidebar
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

        // Edit Employee Function
        function editEmployee(id) {
            fetch(`/absensi/role/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const employee = data.employee;
                        document.getElementById('edit_pin').value = employee.pin || '';
                        document.getElementById('edit_nip').value = employee.nip || '';
                        document.getElementById('edit_nama').value = employee.nama || '';
                        document.getElementById('edit_jabatan').value = employee.jabatan || '';
                        document.getElementById('edit_departemen').value = employee.departemen || '';
                        document.getElementById('edit_kantor').value = employee.kantor || '';
                        document.getElementById('edit_tanggal_start_kontrak').value = employee.tanggal_start_kontrak ||
                            '';
                        document.getElementById('edit_tanggal_end_kontrak').value = employee.tanggal_end_kontrak || '';

                        document.getElementById('editEmployeeForm').action = `/absensi/role/${id}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data karyawan');
                });
        }

        // Delete Employee Function
        function deleteEmployee(id, nama) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus karyawan "${nama}"?\n\nPerhatian: Semua data absensi terkait akan ikut terhapus!`
                )) {
                const form = document.getElementById('deleteEmployeeForm');
                form.action = `/absensi/role/${id}`;
                form.submit();
            }
        }
    </script>

    <style>
        /* Untuk teks hitam pada baris table-danger */
        .table-dark tr.table-danger td {
            color: #000 !important;
            /* Warna teks hitam */
        }

        /* Untuk teks secondary (class text-muted) */
        .table-dark tr.table-danger .text-muted {
            color: #555 !important;
            /* Warna abu-abu gelap */
        }

        /* Untuk badge agar tetap terlihat */
        .table-dark tr.table-danger .badge {
            color: #fff !important;
        }

        /* Untuk border dan background */
        .table-dark tr.table-danger {
            background-color: rgba(255, 82, 82, 0.2) !important;
            border-left: 3px solid #ff5252 !important;
        }

        .contract-notification {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-top: 1rem;
            text-align: center;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .table-danger {
            background-color: rgb(255, 82, 82) !important;
            border-left: 3px solid #ff5252 !important;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-header,
        .modal-footer {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }
    </style>
@endsection

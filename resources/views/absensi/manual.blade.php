@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_manual.css') }}" rel="stylesheet">

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
                                    <span>Kelola Karyawan</span>
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
                                <i class="bi bi-clipboard-check text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Sistem Absensi</span>
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
                            <h1 class="page-title mb-2">Input Absensi Manual</h1>
                            <p class="text-muted mb-0">Tambahkan data absensi karyawan secara manual</p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">
                                <i class="bi bi-clipboard-check text-primary"></i>
                            </div>
                            <small class="text-muted">Manual Input</small>
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

                    {{-- Form Section --}}
                    <div class="form-section">
                        <h5 class="text-white mb-4 d-flex align-items-center">
                            <i class="bi bi-person-plus me-2 text-primary"></i>
                            Data Karyawan & Absensi
                        </h5>

                        <form method="POST" action="{{ route('absensi.manual.store') }}" id="manualForm">
                            @csrf

                            {{-- Employee Information --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-search me-2"></i>Cari Karyawan
                                    </label>
                                    <div class="employee-search">
                                        <input type="text" id="employeeSearch" class="form-control"
                                            placeholder="Ketik nama atau NIP karyawan..." autocomplete="off">
                                        <div class="employee-dropdown" id="employeeDropdown"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar me-2"></i>Tanggal Absensi
                                    </label>
                                    <input type="date" name="tanggal" class="form-control"
                                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            {{-- Hidden Employee Data --}}
                            <input type="hidden" name="employee_id" id="selectedEmployeeId"
                                value="{{ old('employee_id') }}">

                            {{-- Employee Details Display --}}
                            <div class="row mb-4" id="employeeDetails" style="display: none;">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-person-check me-2 text-success"></i>
                                                Data Karyawan Terpilih
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted">PIN</small>
                                                    <div class="text-white fw-semibold" id="displayPIN">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">NIP</small>
                                                    <div class="text-white fw-semibold" id="displayNIP">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Nama</small>
                                                    <div class="text-white fw-semibold" id="displayNama">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Departemen</small>
                                                    <div class="text-white fw-semibold" id="displayDepartemen">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Scan Times --}}
                            <div class="mb-4">
                                <h6 class="text-white mb-3">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    Waktu Scan Absensi
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 1 (Masuk)</label>
                                        <input type="time" name="scan1" class="form-control"
                                            value="{{ old('scan1') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 2 (Istirahat)</label>
                                        <input type="time" name="scan2" class="form-control"
                                            value="{{ old('scan2') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 3 (Kembali)</label>
                                        <input type="time" name="scan3" class="form-control"
                                            value="{{ old('scan3') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 4 (Pulang)</label>
                                        <input type="time" name="scan4" class="form-control"
                                            value="{{ old('scan4') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 5 (Opsional)</label>
                                        <input type="time" name="scan5" class="form-control"
                                            value="{{ old('scan5') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Additional Information --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-info-circle me-2 text-info"></i>
                                                Informasi Tambahan
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small">Terlambat (menit)</label>
                                                    <input type="number" name="late_minutes" class="form-control"
                                                        value="{{ old('late_minutes', 0) }}" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Pulang Cepat (menit)</label>
                                                    <input type="number" name="early_leave_minutes" class="form-control"
                                                        value="{{ old('early_leave_minutes', 0) }}" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Lembur (menit)</label>
                                                    <input type="number" name="overtime_minutes" class="form-control"
                                                        value="{{ old('overtime_minutes', 0) }}" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-save me-2"></i>Simpan Data Absensi
                                </button>
                                <a href="{{ route('absensi.index') }}" class="btn btn-outline-info btn-lg ms-3">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Sidebar Overlay for Mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

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

            // Employee search functionality
            const employeeSearch = document.getElementById('employeeSearch');
            const employeeDropdown = document.getElementById('employeeDropdown');
            const employeeDetails = document.getElementById('employeeDetails');
            let searchTimeout;

            employeeSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    employeeDropdown.classList.remove('show');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    searchEmployees(query);
                }, 300);
            });

            function searchEmployees(query) {
                // Show loading state
                employeeDropdown.innerHTML = '<div class="employee-item text-muted">Mencari...</div>';
                employeeDropdown.classList.add('show');

                fetch(`/api/employees/search?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        displayEmployeeResults(data);
                    })
                    .catch(error => {
                        console.error('Error searching employees:', error);
                        employeeDropdown.innerHTML =
                            '<div class="employee-item text-danger">Error mencari karyawan</div>';
                    });
            }

            function displayEmployeeResults(employees) {
                if (employees.length === 0) {
                    employeeDropdown.innerHTML =
                        '<div class="employee-item text-muted">Tidak ada karyawan ditemukan</div>';
                } else {
                    employeeDropdown.innerHTML = employees.map(emp => `
                        <div class="employee-item" onclick="selectEmployee(${emp.id}, '${emp.pin || ''}', '${emp.nip || ''}', '${emp.nama || ''}', '${emp.jabatan || ''}', '${emp.departemen || ''}', '${emp.kantor || ''}')">
                            <div class="text-white fw-semibold">${emp.nama || 'Nama tidak tersedia'}</div>
                            <small class="text-muted">NIP: ${emp.nip || '-'} | ${emp.departemen || 'Departemen tidak tersedia'}</small>
                        </div>
                    `).join('');
                }
                employeeDropdown.classList.add('show');
            }

            // Make selectEmployee function global
            window.selectEmployee = function(id, pin, nip, nama, jabatan, departemen, kantor) {
                document.getElementById('selectedEmployeeId').value = id;
                document.getElementById('employeeSearch').value = nama;

                // Display employee details
                document.getElementById('displayPIN').textContent = pin || '-';
                document.getElementById('displayNIP').textContent = nip || '-';
                document.getElementById('displayNama').textContent = nama || '-';
                document.getElementById('displayDepartemen').textContent = departemen || '-';

                employeeDetails.style.display = 'block';
                employeeDropdown.classList.remove('show');
            };

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.employee-search')) {
                    employeeDropdown.classList.remove('show');
                }
            });

            // Form validation
            document.getElementById('manualForm').addEventListener('submit', function(e) {
                const employeeId = document.getElementById('selectedEmployeeId').value;
                if (!employeeId) {
                    e.preventDefault();
                    alert('Silakan pilih karyawan terlebih dahulu');
                    return false;
                }
            });
        });

        // Theme toggle functionality
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (theme === 'dark') {
                icon.classList.remove('bi-moon');
                icon.classList.add('bi-sun');
            } else {
                icon.classList.remove('bi-sun');
                icon.classList.add('bi-moon');
            }
        }

        // Check for saved theme preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            // Rest of your existing DOMContentLoaded code...
        });
    </script>
@endsection

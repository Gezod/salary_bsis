@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --sidebar-bg: #334155;
            --border-color: #475569;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        ::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #475569 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border: none !important;
        }

        .nav-link {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            color: #cbd5e1 !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(4px);
        }

        .nav-link.active-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .navbar {
            background: linear-gradient(90deg, var(--card-bg) 0%, #334155 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .form-control {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(30, 41, 59, 0.9) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-outline-info {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(30, 64, 175, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.2);
        }

        .page-title {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2rem;
        }

        .text-muted {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 50;
            font-size: 1rem;
        }

        .form-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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

        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            margin-right: 1rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .employee-search {
            position: relative;
        }

        .employee-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .employee-dropdown.show {
            display: block;
        }

        .employee-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .employee-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .employee-item:last-child {
            border-bottom: none;
        }

        /* Sidebar Responsive */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        /* Untuk mobile, sidebar disembunyikan */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Tambahkan overlay ketika sidebar terbuka */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            /* Sesuaikan margin main content */
            main {
                margin-left: 0 !important;
            }
        }

        /* Untuk desktop, sidebar selalu terlihat */
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0) !important;
            }

            main {
                margin-left: 250px;
            }
        }

        /* Custom scrollbar untuk sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>

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
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Error!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
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
                            <input type="hidden" name="employee_id" id="selectedEmployeeId" value="{{ old('employee_id') }}">

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
                                        <input type="time" name="scan1" class="form-control" value="{{ old('scan1') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 2 (Istirahat)</label>
                                        <input type="time" name="scan2" class="form-control" value="{{ old('scan2') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 3 (Kembali)</label>
                                        <input type="time" name="scan3" class="form-control" value="{{ old('scan3') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 4 (Pulang)</label>
                                        <input type="time" name="scan4" class="form-control" value="{{ old('scan4') }}">
                                    </div>
                                    <div class="col-md-4 col-lg-2">
                                        <label class="form-label small">Scan 5 (Opsional)</label>
                                        <input type="time" name="scan5" class="form-control" value="{{ old('scan5') }}">
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
                        employeeDropdown.innerHTML = '<div class="employee-item text-danger">Error mencari karyawan</div>';
                    });
            }

            function displayEmployeeResults(employees) {
                if (employees.length === 0) {
                    employeeDropdown.innerHTML = '<div class="employee-item text-muted">Tidak ada karyawan ditemukan</div>';
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
    </script>
@endsection

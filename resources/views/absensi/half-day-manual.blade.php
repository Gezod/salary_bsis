@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_import.css') }}" rel="stylesheet">

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
                            <a class="nav-link active-link" href="{{ route('absensi.half-day-manual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Absensi Setengah Hari</span>
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
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Absensi Setengah Hari</span>
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
                            <h1 class="page-title mb-2">Input Absensi Setengah Hari</h1>
                            <p class="text-muted mb-0">Tambahkan data absensi setengah hari secara manual</p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">
                                <i class="bi bi-clock-history text-primary"></i>
                            </div>
                            <small class="text-muted">Half Day</small>
                        </div>
                    </div>

                    {{-- Information Card --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="text-white mb-3">
                                <i class="bi bi-info-circle me-2 text-info"></i>
                                Aturan Absensi Setengah Hari
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="text-white">Senin - Kamis, Sabtu:</h6>
                                    <ul class="text-muted small">
                                        <li><strong>Shift 1 (Pagi):</strong> Scan 1 (07:00) dan Scan 2 (11:00) saja</li>
                                        <li><strong>Shift 2 (Siang):</strong> Scan 3 (12:00) dan Scan 4 (16:30) saja</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-white">Jumat:</h6>
                                    <ul class="text-muted small">
                                        <li><strong>Shift 1 (Pagi):</strong> Scan 1 (07:00) dan Scan 2 (11:00) saja</li>
                                        <li><strong>Shift 2 (Siang):</strong> Scan 3 (12:30) dan Scan 4 (16:00) saja</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Penting:</strong> Setengah hari bebas denda dan hanya menggunakan 2 scan sesuai shift yang dipilih.
                            </div>
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
                            Data Absensi Setengah Hari
                        </h5>
                        <form method="POST" action="{{ route('absensi.half-day-manual.store') }}" id="halfDayForm">
                            @csrf

                            {{-- Employee Selection --}}
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
                                                    <small class="text-muted">Nama</small>
                                                    <div class="text-white fw-semibold" id="displayNama">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">NIP</small>
                                                    <div class="text-white fw-semibold" id="displayNIP">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Departemen</small>
                                                    <div class="text-white fw-semibold" id="displayDepartemen">-</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Jabatan</small>
                                                    <div class="text-white fw-semibold" id="displayJabatan">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Half Day Type Selection --}}
                            <div class="mb-4">
                                <h6 class="text-white mb-3">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    Jenis Setengah Hari
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="shift1" value="shift_1" required>
                                            <label class="form-check-label text-white" for="shift1">
                                                <strong>Shift 1 (Pagi)</strong>
                                                <small class="d-block text-muted">Hanya gunakan Scan 1 dan Scan 2</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="half_day_type"
                                                id="shift2" value="shift_2" required>
                                            <label class="form-check-label text-white" for="shift2">
                                                <strong>Shift 2 (Siang)</strong>
                                                <small class="d-block text-muted">Hanya gunakan Scan 3 dan Scan 4</small>
                                            </label>
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
                                    <div class="col-md-3" id="scan1Group">
                                        <label class="form-label small">Scan 1 (Masuk Shift 1)</label>
                                        <input type="time" name="scan1" class="form-control"
                                            value="{{ old('scan1') }}">
                                        <small class="text-muted">Hanya untuk Shift 1</small>
                                    </div>
                                    <div class="col-md-3" id="scan2Group">
                                        <label class="form-label small">Scan 2 (Pulang Shift 1)</label>
                                        <input type="time" name="scan2" class="form-control"
                                            value="{{ old('scan2') }}">
                                        <small class="text-muted">Hanya untuk Shift 1</small>
                                    </div>
                                    <div class="col-md-3" id="scan3Group">
                                        <label class="form-label small">Scan 3 (Masuk Shift 2)</label>
                                        <input type="time" name="scan3" class="form-control"
                                            value="{{ old('scan3') }}">
                                        <small class="text-muted">Hanya untuk Shift 2</small>
                                    </div>
                                    <div class="col-md-3" id="scan4Group">
                                        <label class="form-label small">Scan 4 (Pulang Shift 2)</label>
                                        <input type="time" name="scan4" class="form-control"
                                            value="{{ old('scan4') }}">
                                        <small class="text-muted">Hanya untuk Shift 2</small>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Petunjuk:</strong> Isi hanya waktu scan sesuai shift yang dipilih. Jangan isi scan yang tidak relevan.
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-chat-text me-2"></i>Catatan
                                </label>
                                <textarea name="half_day_notes" class="form-control" rows="3"
                                    placeholder="Tambahkan catatan jika diperlukan...">{{ old('half_day_notes') }}</textarea>
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-save me-2"></i>Simpan Absensi Setengah Hari
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

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                employeeDropdown.innerHTML = '<div class="employee-item text-muted">Mencari...</div>';
                employeeDropdown.classList.add('show');

                fetch(`/api/employees/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displayEmployeeResults(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
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
                <div class="employee-item" onclick="selectEmployee(${emp.id}, '${emp.nama}', '${emp.nip}', '${emp.departemen}', '${emp.jabatan}')">
                    <div class="text-white fw-semibold">${emp.nama}</div>
                    <small class="text-muted">NIP: ${emp.nip} | ${emp.departemen}</small>
                </div>
            `).join('');
                }
                employeeDropdown.classList.add('show');
            }

            // Make selectEmployee function global
            window.selectEmployee = function(id, nama, nip, departemen, jabatan) {
                document.getElementById('selectedEmployeeId').value = id;
                document.getElementById('employeeSearch').value = nama;

                // Display employee details
                document.getElementById('displayNama').textContent = nama;
                document.getElementById('displayNIP').textContent = nip;
                document.getElementById('displayDepartemen').textContent = departemen;
                document.getElementById('displayJabatan').textContent = jabatan;

                employeeDetails.style.display = 'block';
                employeeDropdown.classList.remove('show');
            };

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.employee-search')) {
                    employeeDropdown.classList.remove('show');
                }
            });

            // Half day type change handler
            const halfDayRadios = document.querySelectorAll('input[name="half_day_type"]');
            halfDayRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateScanFieldsVisibility(this.value);
                });
            });

            function updateScanFieldsVisibility(type) {
                const scan1Group = document.getElementById('scan1Group');
                const scan2Group = document.getElementById('scan2Group');
                const scan3Group = document.getElementById('scan3Group');
                const scan4Group = document.getElementById('scan4Group');

                const scan1Field = document.querySelector('input[name="scan1"]');
                const scan2Field = document.querySelector('input[name="scan2"]');
                const scan3Field = document.querySelector('input[name="scan3"]');
                const scan4Field = document.querySelector('input[name="scan4"]');

                if (type === 'shift_1') {
                    // Show and enable scan1 and scan2, hide scan3 and scan4
                    scan1Group.style.display = 'block';
                    scan2Group.style.display = 'block';
                    scan3Group.style.display = 'none';
                    scan4Group.style.display = 'none';

                    scan1Field.required = true;
                    scan2Field.required = true;
                    scan3Field.required = false;
                    scan4Field.required = false;

                    // Clear values for hidden fields
                    scan3Field.value = '';
                    scan4Field.value = '';
                } else if (type === 'shift_2') {
                    // Show and enable scan3 and scan4, hide scan1 and scan2
                    scan1Group.style.display = 'none';
                    scan2Group.style.display = 'none';
                    scan3Group.style.display = 'block';
                    scan4Group.style.display = 'block';

                    scan1Field.required = false;
                    scan2Field.required = false;
                    scan3Field.required = true;
                    scan4Field.required = true;

                    // Clear values for hidden fields
                    scan1Field.value = '';
                    scan2Field.value = '';
                }
            }

            // Form validation
            document.getElementById('halfDayForm').addEventListener('submit', function(e) {
                const employeeId = document.getElementById('selectedEmployeeId').value;
                const halfDayType = document.querySelector('input[name="half_day_type"]:checked');

                if (!employeeId) {
                    e.preventDefault();
                    alert('Silakan pilih karyawan terlebih dahulu');
                    return false;
                }

                if (!halfDayType) {
                    e.preventDefault();
                    alert('Silakan pilih jenis setengah hari');
                    return false;
                }

                // Validate scan times based on selected shift
                if (halfDayType.value === 'shift_1') {
                    const scan1 = document.querySelector('input[name="scan1"]').value;
                    const scan2 = document.querySelector('input[name="scan2"]').value;
                    if (!scan1 || !scan2) {
                        e.preventDefault();
                        alert('Untuk Shift 1, harap isi Scan 1 dan Scan 2');
                        return false;
                    }
                } else if (halfDayType.value === 'shift_2') {
                    const scan3 = document.querySelector('input[name="scan3"]').value;
                    const scan4 = document.querySelector('input[name="scan4"]').value;
                    if (!scan3 || !scan4) {
                        e.preventDefault();
                        alert('Untuk Shift 2, harap isi Scan 3 dan Scan 4');
                        return false;
                    }
                }
            });
        });

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

    <style>
        .employee-search {
            position: relative;
        }

        .employee-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
        }

        .employee-dropdown.show {
            display: block;
        }

        .employee-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #334155;
        }

        .employee-item:hover {
            background-color: #334155;
        }

        .employee-item:last-child {
            border-bottom: none;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

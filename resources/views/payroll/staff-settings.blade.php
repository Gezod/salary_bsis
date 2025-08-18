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
                            <a class="nav-link " href="{{ route('payroll.index') }}">
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
                            <a class="nav-link active-link" href="{{ route('payroll.staff.settings') }}">
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
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Pengaturan Gaji Staff</span>
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
                    <div class="mb-4">
                        <h1 class="page-title mb-2">Pengaturan Gaji Bulanan Staff</h1>
                        <p class="text-muted mb-0">Kelola gaji bulanan dan uang makan harian untuk staff</p>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Uang makan hanya diberikan pada hari masuk kerja. Cuti/libur tidak mendapat uang makan.
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
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Update Staff Salary Form --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-cash me-2"></i>Update Gaji Bulanan & Uang Makan Harian Staff
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('payroll.staff.update.salary') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Pilih Staff</label>
                                        <select name="employee_id" class="form-control" required id="staffSelect">
                                            <option value="">Pilih Staff</option>
                                            @foreach ($staffEmployees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->nama }} - {{ $employee->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Gaji Bulanan (Rp)</label>
                                        <input type="number" name="monthly_salary" class="form-control" min="0"
                                            required placeholder="5000000">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Uang Makan Harian (Rp)</label>
                                        <input type="number" name="daily_meal_allowance" class="form-control" min="0"
                                            required placeholder="25000">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Catatan Uang Makan</label>
                                        <input type="text" name="meal_allowance_notes" class="form-control"
                                            placeholder="Keterangan uang makan (opsional)">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-check-lg me-2"></i>Update
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <hr class="border-secondary my-4">

                            <form method="POST" action="{{ route('payroll.update.bank') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-white">Pilih Staff</label>
                                        <select name="employee_id" class="form-control" required>
                                            <option value="">Pilih Staff</option>
                                            @foreach ($staffEmployees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->nama }} - {{ $employee->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Nama Bank</label>
                                        <select name="bank_name" class="form-control" required>
                                            <option value="">Pilih Bank</option>
                                            <optgroup label="Bank Nasional">
                                                <option value="BCA">BCA</option>
                                                <option value="Bank Mandiri">Bank Mandiri</option>
                                                <option value="BRI">BRI</option>
                                                <option value="BNI">BNI</option>
                                                <option value="CIMB Niaga">CIMB Niaga</option>
                                                <option value="Bank Danamon">Bank Danamon</option>
                                                <option value="Bank Permata">Bank Permata</option>
                                                <option value="BTN">BTN</option>
                                                <option value="Bank Mega">Bank Mega</option>
                                                <option value="Maybank">Maybank</option>
                                                <option value="OCBC NISP">OCBC NISP</option>
                                                <option value="Panin Bank">Panin Bank</option>
                                            </optgroup>
                                            <optgroup label="Bank Daerah">
                                                <option value="BJB">BJB</option>
                                                <option value="Bank Jatim">Bank Jatim</option>
                                                <option value="Bank Jateng">Bank Jateng</option>
                                                <option value="Bank DKI">Bank DKI</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Nomor Rekening</label>
                                        <input type="text" name="account_number" class="form-control"
                                            placeholder="1234567890" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-bank me-2"></i>Update Bank
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Current Staff Salaries --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-list me-2"></i>Daftar Gaji Staff
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Nama</th>
                                        <th><i class="bi bi-briefcase me-2"></i>Jabatan</th>
                                        <th><i class="bi bi-cash me-2"></i>Gaji Bulanan</th>
                                        <th><i class="bi bi-cup-hot me-2"></i>Uang Makan Harian</th>
                                        <th><i class="bi bi-calculator me-2"></i>Estimasi Total</th>
                                        <th><i class="bi bi-sticky me-2"></i>Catatan</th>
                                        <th><i class="bi bi-bank me-2"></i>Data Bank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffEmployees as $employee)
                                        @php
                                            $setting = $staffSettings->get($employee->id);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $employee->nama }}</div>
                                                        <small class="text-muted">{{ $employee->nip }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-white">{{ $employee->jabatan }}</td>
                                            <td class="text-white">
                                                @if ($setting)
                                                    {{ $setting->formatted_monthly_salary }}
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                            <td class="text-info">
                                                @if ($setting)
                                                    {{ $setting->formatted_daily_meal_allowance }}
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                            <td class="text-success fw-bold">
                                                @if ($setting)
                                                    {{ $setting->formatted_estimated_gross_salary }}
                                                    <small class="text-muted d-block">
                                                        (Gaji + {{ $setting->formatted_estimated_monthly_meal_allowance }} uang makan)
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($setting && $setting->meal_allowance_notes)
                                                    <small>{{ $setting->meal_allowance_notes }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($employee->bank_name && $employee->account_number)
                                                    <div class="small">
                                                        <strong>{{ $employee->bank_name }}</strong><br>
                                                        <span class="text-muted">{{ $employee->account_number }}</span>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

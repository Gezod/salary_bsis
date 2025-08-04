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
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <span>Payroll Bulanan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('weekly-payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-calendar-week"></i>
                                    </div>
                                    <span>Payroll Mingguan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payroll.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <span>Pengaturan Karyawan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payroll.staff.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Pengaturan Staff</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active-link" href="{{ route('bpjs.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <span>Pengaturan BPJS</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
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
                                <i class="bi bi-shield-check text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Pengaturan BPJS</span>
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
                        <h1 class="page-title mb-2">Pengaturan BPJS Karyawan & Staff</h1>
                        <p class="text-muted mb-0">Kelola nomor BPJS dan iuran BPJS bulanan/mingguan untuk semua karyawan dan staff</p>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> BPJS akan dipotong otomatis dari gaji bulanan dan mingguan sesuai dengan pengaturan yang ditetapkan.
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

                    {{-- Update BPJS Form --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-shield-check me-2"></i>Update Pengaturan BPJS
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('bpjs.update') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Pilih Karyawan/Staff</label>
                                        <select name="employee_id" class="form-control" required id="employeeSelect">
                                            <option value="">Pilih Karyawan/Staff</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->nama }} - {{ ucfirst($employee->departemen) }} ({{ $employee->jabatan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Nomor BPJS</label>
                                        <input type="text" name="bpjs_number" class="form-control"
                                            required placeholder="0001234567890">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">BPJS Bulanan (Rp)</label>
                                        <input type="number" name="bpjs_monthly_amount" class="form-control" min="0"
                                            required placeholder="50000">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">BPJS Mingguan (Rp)</label>
                                        <input type="number" name="bpjs_weekly_amount" class="form-control" min="0"
                                            required placeholder="12500">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Status</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                                            <label class="form-check-label text-white" for="isActive">
                                                Aktif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-check-lg me-2"></i>Update
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white">Catatan</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan tentang BPJS (opsional)"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Current BPJS Settings --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-list me-2"></i>Daftar Pengaturan BPJS
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-2"></i>Nama</th>
                                        <th><i class="bi bi-briefcase me-2"></i>Jabatan & Dept</th>
                                        <th><i class="bi bi-card-text me-2"></i>No. BPJS</th>
                                        <th><i class="bi bi-calendar-month me-2"></i>BPJS Bulanan</th>
                                        <th><i class="bi bi-calendar-week me-2"></i>BPJS Mingguan</th>
                                        <th><i class="bi bi-toggle-on me-2"></i>Status</th>
                                        <th><i class="bi bi-sticky me-2"></i>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        @php
                                            $bpjsSetting = $bpjsSettings->get($employee->id);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-{{ $employee->departemen == 'staff' ? 'info' : 'secondary' }} rounded-circle d-flex align-items-center justify-content-center me-3"
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
                                            <td class="text-white">
                                                {{ $employee->jabatan }}
                                                <br><small class="badge {{ $employee->departemen == 'staff' ? 'bg-info' : 'bg-secondary' }}">{{ ucfirst($employee->departemen) }}</small>
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting)
                                                    <strong>{{ $bpjsSetting->bpjs_number }}</strong>
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting)
                                                    {{ $bpjsSetting->formatted_bpjs_monthly_amount }}
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting)
                                                    {{ $bpjsSetting->formatted_bpjs_weekly_amount }}
                                                @else
                                                    <span class="badge bg-warning">Belum diset</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($bpjsSetting)
                                                    <span class="badge {{ $bpjsSetting->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $bpjsSetting->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting && $bpjsSetting->notes)
                                                    <small>{{ $bpjsSetting->notes }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
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

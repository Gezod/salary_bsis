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
                                <span>Data Payroll</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('payroll.settings') }}">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <span>Pengaturan Gaji</span>
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
                            <i class="bi bi-person-badge text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Detail Payroll</span>
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
                        <h1 class="page-title mb-2">{{ $payroll->employee->nama }}</h1>
                        <p class="text-muted mb-0">Payroll {{ $payroll->month_name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('payroll.recalculate', $payroll->id) }}" class="btn btn-warning">
                            <i class="bi bi-arrow-clockwise me-2"></i>Hitung Ulang
                        </a>
                        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>

                @if(session('success'))
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
                                            {{ substr($payroll->employee->nama, 0, 1) }}
                                        </span>
                                    </div>
                                    <h5 class="text-white">{{ $payroll->employee->nama }}</h5>
                                    <span class="badge bg-secondary">{{ ucfirst($payroll->employee->departemen) }}</span>
                                </div>
                                <hr class="border-secondary">
                                <div class="row g-2 text-sm">
                                    <div class="col-6 text-muted">NIP:</div>
                                    <div class="col-6 text-white">{{ $payroll->employee->nip }}</div>
                                    <div class="col-6 text-muted">Jabatan:</div>
                                    <div class="col-6 text-white">{{ $payroll->employee->jabatan }}</div>
                                    <div class="col-6 text-muted">Kantor:</div>
                                    <div class="col-6 text-white">{{ $payroll->employee->kantor }}</div>
                                    <div class="col-6 text-muted">Gaji Harian:</div>
                                    <div class="col-6 text-white">Rp {{ number_format($payroll->employee->daily_salary ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payroll Details --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="bi bi-calculator me-2"></i>Rincian Payroll
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="text-white mb-3">Kehadiran</h6>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6 text-muted">Hari Kerja:</div>
                                            <div class="col-6 text-white">{{ $payroll->working_days }} hari</div>
                                            <div class="col-6 text-muted">Hadir:</div>
                                            <div class="col-6 text-white">{{ $payroll->present_days }} hari</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-white mb-3">Perhitungan</h6>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6 text-muted">Gaji Pokok:</div>
                                            <div class="col-6 text-white">{{ $payroll->formatted_basic_salary }}</div>
                                            <div class="col-6 text-muted">Lembur:</div>
                                            <div class="col-6 text-success">{{ $payroll->formatted_overtime_pay }}</div>
                                            <div class="col-6 text-muted">Denda:</div>
                                            <div class="col-6 text-warning">{{ $payroll->formatted_total_fines }}</div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-secondary">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <h6 class="text-white">Gaji Kotor:</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-white">{{ $payroll->formatted_gross_salary }}</h6>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-white">Total Gaji Bersih:</h5>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-success">{{ $payroll->formatted_net_salary }}</h5>
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
                                @if($payroll->status === 'paid')
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="text-muted small">Metode Pembayaran</label>
                                            <div>
                                                <span class="badge {{ $payroll->payment_method_badge }}">
                                                    {{ ucfirst($payroll->payment_method) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-muted small">Tanggal Pembayaran</label>
                                            <div class="text-white">{{ $payroll->payment_date->format('d M Y') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small">Catatan</label>
                                            <div class="text-white">{{ $payroll->notes ?: '-' }}</div>
                                        </div>
                                        @if($payroll->payment_proof)
                                            <div class="col-12">
                                                <label class="text-muted small">Bukti Pembayaran</label>
                                                <div>
                                                    <img src="{{ Storage::url($payroll->payment_proof) }}"
                                                         alt="Payment Proof" class="img-thumbnail" style="max-width: 300px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('payroll.update.payment', $payroll->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label text-white">Metode Pembayaran</label>
                                                <select name="payment_method" class="form-control" required>
                                                    <option value="">Pilih Metode</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-white">Tanggal Pembayaran</label>
                                                <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label text-white">Bukti Pembayaran</label>
                                                <input type="file" name="payment_proof" class="form-control" accept="image/*">
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
@endsection

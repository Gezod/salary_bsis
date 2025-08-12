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
                            <i class="bi bi-credit-card text-white"></i>
                        </div>
                        <span class="navbar-brand fw-bold text-white mb-0">Premi BPJS</span>
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
                        <h1 class="page-title mb-2">Pengaturan Premi BPJS</h1>
                        <p class="text-muted mb-0">Kelola premi BPJS bulanan untuk karyawan/staff</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPremiumModal">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Premi BPJS
                    </button>
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

                {{-- Info Card --}}
                <div class="alert alert-info mb-4">
                    <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Cara Kerja Premi BPJS:</h5>
                    <hr>
                    <ul class="mb-0">
                        <li><strong>BPJS Tunjangan:</strong> Dibayar oleh kantor dan dikurangi dari gaji saat cetak slip</li>
                        <li><strong>Premi BPJS:</strong> Total biaya BPJS yang harus dibayar untuk bulan tersebut</li>
                        <li><strong>Kelebihan Premi:</strong> Jika premi > tunjangan, karyawan bayar selisihnya dengan cash</li>
                        <li><strong>Contoh:</strong> Premi Rp 70.000, Tunjangan Rp 50.000 → Karyawan bayar cash Rp 20.000</li>
                    </ul>
                </div>

                {{-- Filter Section --}}
                <div class="filter-section">
                    <h5 class="text-white mb-3 d-flex align-items-center">
                        <i class="bi bi-funnel me-2 text-primary"></i>
                        Filter Data
                    </h5>
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Bulan & Tahun</label>
                            <input type="month" name="month" value="{{ request('month') }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Nama Karyawan/Staff</label>
                            <input type="text" name="employee_name" value="{{ request('employee_name') }}" class="form-control" placeholder="Cari nama...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Filter
                            </button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('bpjs.premiums') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Premiums Table --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-list-ul me-2"></i>Daftar Premi BPJS
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No. BPJS</th>
                                        <th>Periode</th>
                                        <th>Tunjangan BPJS</th>
                                        <th>Premi BPJS</th>
                                        <th>Cash Payment</th>
                                        <th>Catatan</th>
                                        <th>Tanggal Input</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($premiums as $index => $premium)
                                        <tr>
                                            <td>{{ $premiums->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 35px; height: 35px;">
                                                        <span class="text-white fw-bold small">
                                                            {{ substr($premium->employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $premium->employee->nama }}</div>
                                                        <small class="text-muted">{{ $premium->employee->nip }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($premium->employee->bpjsSetting)
                                                    {{ $premium->employee->bpjsSetting->bpjs_number }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $premium->period_name }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">
                                                    {{ $premium->formatted_bpjs_allowance }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-warning">
                                                    {{ $premium->formatted_premium_amount }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($premium->needsCashPayment())
                                                    <span class="fw-semibold text-danger">
                                                        {{ $premium->formatted_cash_payment }}
                                                    </span>
                                                    <small class="d-block text-danger">
                                                        <i class="bi bi-exclamation-triangle"></i> Hutang
                                                    </small>
                                                @else
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle"></i> Lunas
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($premium->notes)
                                                    <div class="text-truncate" style="max-width: 150px;" title="{{ $premium->notes }}">
                                                        {{ $premium->notes }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $premium->formatted_created_at }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-warning" onclick="editPremium({{ $premium->id }})" title="Edit Premi">
                                                        <i class="bi bi-pencil fs-4"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('bpjs.premiums.destroy', $premium->id) }}" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus premi BPJS untuk {{ $premium->employee->nama }} periode {{ $premium->period_name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Premi">
                                                            <i class="bi bi-trash fs-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted fs-1"></i>
                                                <p class="text-muted mt-2">Tidak ada data premi BPJS</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($premiums->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $premiums->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- Add Premium Modal --}}
<div class="modal fade" id="addPremiumModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="premiumModalTitle">Tambah Premi BPJS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('bpjs.premiums.store') }}" id="premiumForm">
                @csrf
                <input type="hidden" id="premium_id" name="premium_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white">Pilih Karyawan/Staff</label>
                        <select id="employee_select" name="employee_id" class="form-control" required>
                            <option value="">Pilih Karyawan/Staff</option>
                            @foreach ($employeesWithBpjs as $employee)
                                <option value="{{ $employee->id }}"
                                    data-bpjs-number="{{ $employee->bpjsSetting->bpjs_number }}"
                                    data-bpjs-amount="{{ $employee->bpjsSetting->bpjs_monthly_amount }}">
                                    {{ $employee->nama }} - {{ ucfirst($employee->departemen) }} ({{ $employee->nip }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="bpjsInfo" class="alert alert-secondary d-none mb-3">
                        <small>
                            <strong>No. BPJS:</strong> <span id="displayBpjsNumber">-</span><br>
                            <strong>Tunjangan BPJS:</strong> <span id="displayBpjsAmount">-</span>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Bulan & Tahun</label>
                        <input type="month" id="month" name="month" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Jumlah Premi BPJS</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="premium_amount" name="premium_amount" class="form-control"
                                min="0" max="2000000" placeholder="0" required>
                        </div>
                        <small class="form-text text-muted">Total biaya BPJS yang harus dibayar</small>
                    </div>

                    <div id="paymentCalculation" class="alert alert-warning d-none mb-3">
                        <strong>Perhitungan Pembayaran:</strong><br>
                        <small>
                            Premi: <span id="calcPremium">Rp 0</span><br>
                            Tunjangan: <span id="calcAllowance">Rp 0</span><br>
                            <hr class="my-2">
                            <strong>Cash Payment: <span id="calcCashPayment">Rp 0</span></strong>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Catatan</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"
                            placeholder="Catatan tambahan (opsional)" maxlength="500"></textarea>
                        <small class="form-text text-muted">Maksimal 500 karakter</small>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Premium Modal --}}
<div class="modal fade" id="editPremiumModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Edit Premi BPJS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editPremiumForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white">Karyawan/Staff</label>
                        <input type="text" id="edit_employee_name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Periode</label>
                        <input type="text" id="edit_period" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Jumlah Premi BPJS</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="edit_premium_amount" name="premium_amount" class="form-control"
                                min="0" max="2000000" required>
                        </div>
                    </div>

                    <div id="editPaymentCalculation" class="alert alert-warning d-none mb-3">
                        <strong>Perhitungan Pembayaran:</strong><br>
                        <small>
                            Premi: <span id="editCalcPremium">Rp 0</span><br>
                            Tunjangan: <span id="editCalcAllowance">Rp 0</span><br>
                            <hr class="my-2">
                            <strong>Cash Payment: <span id="editCalcCashPayment">Rp 0</span></strong>
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Catatan</label>
                        <textarea id="edit_notes" name="notes" class="form-control" rows="3" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    const toggleIcon = document.querySelector('.theme-toggle i');
    if (toggleIcon) {
        toggleIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
}

// Employee selection change handler
document.getElementById('employee_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const bpjsInfo = document.getElementById('bpjsInfo');

    if (this.value) {
        const bpjsNumber = selectedOption.dataset.bpjsNumber;
        const bpjsAmount = selectedOption.dataset.bpjsAmount;

        document.getElementById('displayBpjsNumber').textContent = bpjsNumber;
        document.getElementById('displayBpjsAmount').textContent = 'Rp ' + parseInt(bpjsAmount).toLocaleString('id-ID');

        bpjsInfo.classList.remove('d-none');
        calculatePayment();
    } else {
        bpjsInfo.classList.add('d-none');
        document.getElementById('paymentCalculation').classList.add('d-none');
    }
});

// Premium amount change handler
document.getElementById('premium_amount').addEventListener('input', calculatePayment);

function calculatePayment() {
    const employeeSelect = document.getElementById('employee_select');
    const premiumAmount = parseInt(document.getElementById('premium_amount').value) || 0;

    if (employeeSelect.value && premiumAmount > 0) {
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        const bpjsAmount = parseInt(selectedOption.dataset.bpjsAmount) || 0;
        const cashPayment = Math.max(0, premiumAmount - bpjsAmount);

        document.getElementById('calcPremium').textContent = 'Rp ' + premiumAmount.toLocaleString('id-ID');
        document.getElementById('calcAllowance').textContent = 'Rp ' + bpjsAmount.toLocaleString('id-ID');
        document.getElementById('calcCashPayment').textContent = 'Rp ' + cashPayment.toLocaleString('id-ID');

        document.getElementById('paymentCalculation').classList.remove('d-none');
    } else {
        document.getElementById('paymentCalculation').classList.add('d-none');
    }
}

function editPremium(premiumId) {
    // Fetch premium data and show edit modal
    fetch(`/bpjs/premiums/${premiumId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const premium = data.premium;
                const employee = data.employee;
                const bpjsSetting = data.bpjs_setting;

                // Set form action
                document.getElementById('editPremiumForm').action = `/bpjs/premiums/${premiumId}`;

                // Fill form fields
                document.getElementById('edit_employee_name').value = `${employee.nama} (${employee.nip})`;
                document.getElementById('edit_period').value = premium.period_name;
                document.getElementById('edit_premium_amount').value = premium.premium_amount;
                document.getElementById('edit_notes').value = premium.notes || '';

                // Store BPJS amount for calculation
                document.getElementById('edit_premium_amount').dataset.bpjsAmount = bpjsSetting ? bpjsSetting.bpjs_monthly_amount : 0;

                // Calculate payment
                calculateEditPayment();

                // Show modal
                new bootstrap.Modal(document.getElementById('editPremiumModal')).show();
            } else {
                alert('Gagal memuat data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data');
        });
}

// Edit premium amount change handler
document.getElementById('edit_premium_amount').addEventListener('input', calculateEditPayment);

function calculateEditPayment() {
    const premiumAmount = parseInt(document.getElementById('edit_premium_amount').value) || 0;
    const bpjsAmount = parseInt(document.getElementById('edit_premium_amount').dataset.bpjsAmount) || 0;

    if (premiumAmount > 0) {
        const cashPayment = Math.max(0, premiumAmount - bpjsAmount);

        document.getElementById('editCalcPremium').textContent = 'Rp ' + premiumAmount.toLocaleString('id-ID');
        document.getElementById('editCalcAllowance').textContent = 'Rp ' + bpjsAmount.toLocaleString('id-ID');
        document.getElementById('editCalcCashPayment').textContent = 'Rp ' + cashPayment.toLocaleString('id-ID');

        document.getElementById('editPaymentCalculation').classList.remove('d-none');
    } else {
        document.getElementById('editPaymentCalculation').classList.add('d-none');
    }
}

// Reset modal when closed
document.getElementById('addPremiumModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('premiumForm').reset();
    document.getElementById('bpjsInfo').classList.add('d-none');
    document.getElementById('paymentCalculation').classList.add('d-none');
});
</script>

@endsection

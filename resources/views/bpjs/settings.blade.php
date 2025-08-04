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

                    {{-- Success Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Error Messages --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Terdapat kesalahan dalam input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Statistics Cards --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Karyawan</h6>
                                            <h3 class="mb-0">{{ $employees->count() }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-people fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">BPJS Aktif</h6>
                                            <h3 class="mb-0">{{ $bpjsSettings->where('is_active', true)->count() }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-shield-check fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Belum Diatur</h6>
                                            <h3 class="mb-0">{{ $employees->count() - $bpjsSettings->count() }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-exclamation-triangle fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">BPJS Nonaktif</h6>
                                            <h3 class="mb-0">{{ $bpjsSettings->where('is_active', false)->count() }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-shield-x fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Update BPJS Form --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-shield-check me-2"></i>Update Pengaturan BPJS
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('bpjs.update') }}" id="bpjsForm">
                                @csrf
                                <!-- Hidden input for checkbox handling -->
                                <input type="hidden" name="is_active" value="0" id="hiddenIsActive">

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-white">Pilih Karyawan/Staff <span class="text-danger">*</span></label>
                                        <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required id="employeeSelect">
                                            <option value="">Pilih Karyawan/Staff</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->nama }} - {{ ucfirst($employee->departemen) }} ({{ $employee->jabatan }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Nomor BPJS <span class="text-danger">*</span></label>
                                        <input type="text" name="bpjs_number" class="form-control @error('bpjs_number') is-invalid @enderror"
                                            required placeholder="0001234567890" value="{{ old('bpjs_number') }}" maxlength="50" id="bpjsNumberInput">
                                        @error('bpjs_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Contoh: 0001234567890</small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">BPJS Bulanan (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="bpjs_monthly_amount" class="form-control @error('bpjs_monthly_amount') is-invalid @enderror"
                                            min="0" max="1000000" required placeholder="50000" value="{{ old('bpjs_monthly_amount') }}" id="monthlyInput">
                                        @error('bpjs_monthly_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Maks: Rp 1.000.000</small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">BPJS Mingguan (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="bpjs_weekly_amount" class="form-control @error('bpjs_weekly_amount') is-invalid @enderror"
                                            min="0" max="250000" required placeholder="12500" value="{{ old('bpjs_weekly_amount') }}" id="weeklyInput">
                                        @error('bpjs_weekly_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Maks: Rp 250.000</small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-white">Status</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label text-white" for="isActive">
                                                Aktif
                                            </label>
                                        </div>
                                        <small class="text-muted">Centang jika BPJS aktif</small>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                            <i class="bi bi-check-lg me-2"></i>Update
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-white">Catatan</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                            rows="2" placeholder="Catatan tambahan tentang BPJS (opsional)" maxlength="500" id="notesInput">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Maksimal 500 karakter</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Current BPJS Settings Table --}}
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-list me-2"></i>Daftar Pengaturan BPJS
                            </h5>
                            <div class="text-white">
                                <small>Total: {{ $employees->count() }} karyawan | Sudah diatur: {{ $bpjsSettings->count() }}</small>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-hash me-2"></i>No</th>
                                        <th><i class="bi bi-person me-2"></i>Nama</th>
                                        <th><i class="bi bi-briefcase me-2"></i>Jabatan & Dept</th>
                                        <th><i class="bi bi-card-text me-2"></i>No. BPJS</th>
                                        <th><i class="bi bi-calendar-month me-2"></i>BPJS Bulanan</th>
                                        <th><i class="bi bi-calendar-week me-2"></i>BPJS Mingguan</th>
                                        <th><i class="bi bi-toggle-on me-2"></i>Status</th>
                                        <th><i class="bi bi-sticky me-2"></i>Catatan</th>
                                        <th><i class="bi bi-calendar me-2"></i>Terakhir Update</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $index => $employee)
                                        @php
                                            $bpjsSetting = $bpjsSettings->get($employee->id);
                                        @endphp
                                        <tr class="{{ $bpjsSetting ? ($bpjsSetting->is_active ? 'table-success' : 'table-warning') : 'table-danger' }}">
                                            <td class="text-white fw-bold">{{ $index + 1 }}</td>
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
                                                        <small class="text-muted">{{ $employee->nip ?: 'Tidak ada NIP' }}</small>
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
                                                        <i class="bi bi-{{ $bpjsSetting->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                                        {{ $bpjsSetting->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-dash-circle me-1"></i>Belum diatur
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting && $bpjsSetting->notes)
                                                    <small title="{{ $bpjsSetting->notes }}">
                                                        {{ Str::limit($bpjsSetting->notes, 30) }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                @if ($bpjsSetting)
                                                    <small>{{ $bpjsSetting->formatted_updated_at }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="editEmployee({{ $employee->id }})" title="Edit BPJS">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @if ($bpjsSetting)
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                                        onclick="deleteEmployee({{ $bpjsSetting->id }}, '{{ $employee->nama }}')" title="Hapus BPJS">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Tidak ada data karyawan ditemukan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($employees->count() > 0)
                            <div class="card-footer text-muted">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <i class="bi bi-info-circle me-2"></i>
                                            Warna tabel: <span class="badge bg-success">Hijau = BPJS Aktif</span>,
                                            <span class="badge bg-warning">Kuning = BPJS Nonaktif</span>,
                                            <span class="badge bg-danger">Merah = Belum Diatur</span>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small>Data terakhir dimuat: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <p>Apakah Anda yakin ingin menghapus pengaturan BPJS untuk <strong id="employeeName"></strong>?</p>
                    <p class="text-warning"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Edit employee BPJS
        function editEmployee(employeeId) {
            // Reset form first
            resetForm();

            // Show loading state
            showLoadingState(true);

            // Fetch employee BPJS data via AJAX
            fetch(`/bpjs/employee/${employeeId}/data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    showLoadingState(false);

                    if (data.success) {
                        const employee = data.data.employee;
                        const bpjsSetting = data.data.bpjs_setting;

                        // Populate form fields
                        document.getElementById('employeeSelect').value = employee.id;

                        if (bpjsSetting) {
                            document.getElementById('bpjsNumberInput').value = bpjsSetting.bpjs_number || '';
                            document.getElementById('monthlyInput').value = bpjsSetting.bpjs_monthly_amount || '';
                            document.getElementById('weeklyInput').value = bpjsSetting.bpjs_weekly_amount || '';

                            // Handle checkbox properly
                            const isActiveCheckbox = document.getElementById('isActive');
                            const hiddenIsActive = document.getElementById('hiddenIsActive');

                            if (bpjsSetting.is_active) {
                                isActiveCheckbox.checked = true;
                                hiddenIsActive.value = '1';
                            } else {
                                isActiveCheckbox.checked = false;
                                hiddenIsActive.value = '0';
                            }

                            document.getElementById('notesInput').value = bpjsSetting.notes || '';
                        } else {
                            // Reset form for new entry
                            document.getElementById('bpjsNumberInput').value = '';
                            document.getElementById('monthlyInput').value = '';
                            document.getElementById('weeklyInput').value = '';
                            document.getElementById('isActive').checked = true;
                            document.getElementById('hiddenIsActive').value = '1';
                            document.getElementById('notesInput').value = '';
                        }

                        // Scroll to form smoothly
                        document.getElementById('bpjsForm').scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Focus on BPJS number input after a short delay
                        setTimeout(() => {
                            document.getElementById('bpjsNumberInput').focus();
                        }, 500);

                        // Show success notification
                        showNotification('Data karyawan berhasil dimuat', 'success');

                    } else {
                        showNotification('Gagal mengambil data karyawan: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showLoadingState(false);
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat mengambil data karyawan', 'error');
                });
        }

        // Delete employee BPJS
        function deleteEmployee(bpjsId, employeeName) {
            document.getElementById('employeeName').textContent = employeeName;
            document.getElementById('deleteForm').action = `/bpjs/${bpjsId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Helper function to reset form
        function resetForm() {
            document.getElementById('employeeSelect').value = '';
            document.getElementById('bpjsNumberInput').value = '';
            document.getElementById('monthlyInput').value = '';
            document.getElementById('weeklyInput').value = '';
            document.getElementById('isActive').checked = true;
            document.getElementById('hiddenIsActive').value = '1';
            document.getElementById('notesInput').value = '';

            // Remove any validation errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }

        // Helper function to show loading state
        function showLoadingState(isLoading) {
            const submitBtn = document.getElementById('submitBtn');
            if (isLoading) {
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memuat...';
                submitBtn.disabled = true;
            } else {
                submitBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Update';
                submitBtn.disabled = false;
            }
        }

        // Helper function to show notifications
        function showNotification(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' :
                              type === 'error' ? 'alert-danger' : 'alert-info';
            const iconClass = type === 'success' ? 'bi-check-circle' :
                             type === 'error' ? 'bi-exclamation-triangle' : 'bi-info-circle';

            const notification = document.createElement('div');
            notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi ${iconClass} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                if (notification && notification.parentNode) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 150);
                }
            }, 3000);
        }

        // Form validation and auto-fill
        document.addEventListener('DOMContentLoaded', function() {
            const employeeSelect = document.getElementById('employeeSelect');
            const bpjsForm = document.getElementById('bpjsForm');
            const submitBtn = document.getElementById('submitBtn');
            const isActiveCheckbox = document.getElementById('isActive');
            const hiddenIsActive = document.getElementById('hiddenIsActive');

            // Handle checkbox change
            if (isActiveCheckbox && hiddenIsActive) {
                isActiveCheckbox.addEventListener('change', function() {
                    hiddenIsActive.value = this.checked ? '1' : '0';
                });
            }

            // Auto-calculate weekly amount when monthly amount changes
            const monthlyInput = document.getElementById('monthlyInput');
            const weeklyInput = document.getElementById('weeklyInput');

            if (monthlyInput && weeklyInput) {
                monthlyInput.addEventListener('input', function() {
                    const monthlyAmount = parseInt(this.value) || 0;
                    const weeklyAmount = Math.round(monthlyAmount / 4);
                    weeklyInput.value = weeklyAmount;
                });
            }

            // Auto-populate form when employee is selected
            if (employeeSelect) {
                employeeSelect.addEventListener('change', function() {
                    const employeeId = this.value;
                    if (employeeId) {
                        editEmployee(employeeId);
                    } else {
                        resetForm();
                    }
                });
            }

            // Form submission with loading state
            if (bpjsForm && submitBtn) {
                bpjsForm.addEventListener('submit', function(e) {
                    // Prevent double submission
                    if (submitBtn.disabled) {
                        e.preventDefault();
                        return false;
                    }

                    showLoadingState(true);

                    // Re-enable after 5 seconds as failsafe
                    setTimeout(() => {
                        showLoadingState(false);
                    }, 5000);
                });
            }

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert && alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 150);
                    }
                }, 5000);
            });

            // Initialize checkbox state
            if (isActiveCheckbox && hiddenIsActive) {
                hiddenIsActive.value = isActiveCheckbox.checked ? '1' : '0';
            }
        });

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

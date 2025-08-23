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
                            <a class="nav-link active" href="{{ route('bpjs.settings') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <span>Pengaturan BPJS</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bpjs.premiums') }}">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="page-title mb-2">Pengaturan BPJS Karyawan/Staff</h1>
                            <p class="text-muted mb-0">Kelola data BPJS untuk perhitungan payroll bulanan</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBpjsModal">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Pengaturan BPJS
                        </button>
                    </div>

                    {{-- Alert Messages --}}
                    <div id="alertContainer">
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
                    </div>

                    {{-- BPJS Settings Table --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-list-ul me-2"></i>Daftar Pengaturan BPJS
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan/Staff</th>
                                            <th>Departemen</th>
                                            <th>No. BPJS</th>
                                            <th>BPJS Bulanan</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                            <th>Terakhir Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employees as $index => $employee)
                                            @php
                                                $bpjsSetting = $bpjsSettings->get($employee->id);
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                            style="width: 35px; height: 35px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($employee->nama, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $employee->nama }}</div>
                                                            <small class="text-muted">{{ $employee->nip }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge {{ $employee->departemen == 'staff' ? 'bg-info' : 'bg-secondary' }}">
                                                        {{ ucfirst($employee->departemen) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($bpjsSetting)
                                                        {{ $bpjsSetting->bpjs_number }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bpjsSetting)
                                                        <span class="fw-semibold text-info">
                                                            {{ $bpjsSetting->formatted_bpjs_monthly_amount }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bpjsSetting)
                                                        <span class="badge {{ $bpjsSetting->status_badge_class }}">
                                                            <i class="bi bi-{{ $bpjsSetting->status_icon }} me-1"></i>
                                                            {{ $bpjsSetting->status_text }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-exclamation-circle me-1"></i>
                                                            Belum Diatur
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bpjsSetting && $bpjsSetting->notes)
                                                        <div class="text-truncate" style="max-width: 150px;"
                                                            title="{{ $bpjsSetting->notes }}">
                                                            {{ $bpjsSetting->notes }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bpjsSetting)
                                                        <small class="text-muted">{{ $bpjsSetting->formatted_updated_at }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-warning"
                                                            onclick="editBpjs({{ $employee->id }})"
                                                            title="Edit BPJS">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        @if ($bpjsSetting)
                                                            <form method="POST" action="{{ route('bpjs.delete', $bpjsSetting->id) }}"
                                                                class="d-inline"
                                                                onsubmit="return confirm('Yakin ingin menghapus pengaturan BPJS untuk {{ $employee->nama }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    title="Hapus BPJS">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <i class="bi bi-inbox text-muted fs-1"></i>
                                                    <p class="text-muted mt-2">Tidak ada data karyawan/staff</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Add/Edit BPJS Modal --}}
    <div class="modal fade" id="addBpjsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-white" id="bpjsModalTitle">Tambah Pengaturan BPJS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="bpjsForm">
                    @csrf
                    <input type="hidden" id="currentEmployeeId" name="employee_id">
                    <div class="modal-body">
                        {{-- Employee Select --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Pilih Karyawan/Staff <span class="text-danger">*</span></label>
                            <select id="employee_select" name="employee_id" class="form-control" required>
                                <option value="">Pilih Karyawan/Staff</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" data-department="{{ $employee->departemen }}">
                                        {{ $employee->nama }} - {{ ucfirst($employee->departemen) }} ({{ $employee->nip }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="employee_id_error"></div>
                        </div>

                        {{-- BPJS Number --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Nomor BPJS <span class="text-danger">*</span></label>
                            <input type="text" id="bpjs_number" name="bpjs_number" class="form-control"
                                placeholder="Masukkan nomor BPJS" required maxlength="50">
                            <div class="invalid-feedback" id="bpjs_number_error"></div>
                        </div>

                        {{-- Monthly Amount --}}
                        <div class="mb-3">
                            <label class="form-label text-white">BPJS Bulanan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="bpjs_monthly_amount" name="bpjs_monthly_amount"
                                    class="form-control" placeholder="0" required>
                            </div>
                            <small class="form-text text-muted">
                                BPJS dipotong dari gaji bulanan saja, tidak ada potongan di gaji mingguan
                            </small>
                            <div class="invalid-feedback" id="bpjs_monthly_amount_error"></div>
                        </div>

                        {{-- Active Status --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" checked>
                                <label class="form-check-label text-white" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Centang untuk mengaktifkan potongan BPJS
                            </small>
                            <div class="invalid-feedback" id="is_active_error"></div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Catatan</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3"
                                placeholder="Catatan tambahan (opsional)" maxlength="500"></textarea>
                            <small class="form-text text-muted">Maksimal 500 karakter</small>
                            <div class="invalid-feedback" id="notes_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables
        let isEditMode = false;
        let currentEmployeeId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initializeTheme();
            setupFormValidation();
        });

        // Theme functions
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

        function initializeTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }

        // Form validation setup
        function setupFormValidation() {
            const monthlyAmountInput = document.getElementById('bpjs_monthly_amount');

            // Format number input
            monthlyAmountInput.addEventListener('input', function() {
                let value = this.value.replace(/[^\d]/g, '');
                if (value.length > 0) {
                    // Format as Indonesian Rupiah (without Rp symbol)
                    let formattedValue = parseInt(value).toLocaleString('id-ID');
                    this.value = formattedValue;
                }
            });
        }

        // Edit BPJS function
        function editBpjs(employeeId) {
            isEditMode = true;
            currentEmployeeId = employeeId;

            // Show loading state
            showLoadingModal();

            // Reset form first
            resetForm();

            // Update modal title
            document.getElementById('bpjsModalTitle').innerText = 'Edit Pengaturan BPJS';
            document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>Perbarui';

            // Fetch employee data
            fetch(`{{ url('/bpjs/employee') }}/${employeeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Set employee data
                        const employeeSelect = document.getElementById('employee_select');
                        employeeSelect.value = employeeId;
                        employeeSelect.disabled = true; // Disable employee selection in edit mode

                        // Set hidden field
                        document.getElementById('currentEmployeeId').value = employeeId;

                        // Set BPJS data if exists
                        if (data.bpjs_setting) {
                            document.getElementById('bpjs_number').value = data.bpjs_setting.bpjs_number || '';

                            // Format monthly amount
                            const monthlyAmount = data.bpjs_setting.bpjs_monthly_amount || 0;
                            document.getElementById('bpjs_monthly_amount').value = monthlyAmount.toLocaleString('id-ID');

                            document.getElementById('is_active').checked = Boolean(data.bpjs_setting.is_active);
                            document.getElementById('notes').value = data.bpjs_setting.notes || '';
                        }

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('addBpjsModal'));
                        modal.show();
                    } else {
                        showAlert('danger', 'Gagal mengambil data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Terjadi kesalahan saat mengambil data: ' + error.message);
                })
                .finally(() => {
                    hideLoadingModal();
                });
        }

        // Form submission
        document.getElementById('bpjsForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('loadingSpinner');

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');

            // Clear previous errors
            clearFormErrors();

            // Prepare form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Get employee ID (either from select or hidden field in edit mode)
            const employeeId = isEditMode ?
                document.getElementById('currentEmployeeId').value :
                document.getElementById('employee_select').value;

            formData.append('employee_id', employeeId);
            formData.append('bpjs_number', document.getElementById('bpjs_number').value);

            // Clean and prepare monthly amount
            const monthlyAmountRaw = document.getElementById('bpjs_monthly_amount').value.replace(/[^\d]/g, '');
            formData.append('bpjs_monthly_amount', monthlyAmountRaw);

            formData.append('is_active', document.getElementById('is_active').checked ? '1' : '0');
            formData.append('notes', document.getElementById('notes').value);

            // Submit form
            fetch('{{ route("bpjs.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('addBpjsModal')).hide();

                    // Show success message
                    showAlert('success', data.message);

                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        displayFormErrors(data.errors);
                    } else {
                        showAlert('danger', data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
            })
            .finally(() => {
                // Hide loading
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });

        // Reset modal when closed
        document.getElementById('addBpjsModal').addEventListener('hidden.bs.modal', function() {
            resetForm();
        });

        // Helper functions
        function resetForm() {
            isEditMode = false;
            currentEmployeeId = null;

            document.getElementById('bpjsForm').reset();
            document.getElementById('bpjsModalTitle').innerText = 'Tambah Pengaturan BPJS';
            document.getElementById('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>Simpan';
            document.getElementById('employee_select').disabled = false;
            document.getElementById('currentEmployeeId').value = '';
            document.getElementById('is_active').checked = true; // Default to active

            clearFormErrors();
        }

        function clearFormErrors() {
            const errorElements = document.querySelectorAll('.invalid-feedback');
            errorElements.forEach(element => {
                element.textContent = '';
            });

            const inputElements = document.querySelectorAll('.form-control, .form-check-input');
            inputElements.forEach(element => {
                element.classList.remove('is-invalid');
            });
        }

        function displayFormErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(field + '_error');
                const inputElement = document.getElementById(field) || document.querySelector(`[name="${field}"]`);

                if (errorElement && inputElement) {
                    errorElement.textContent = errors[field][0];
                    inputElement.classList.add('is-invalid');
                }
            });
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            alertContainer.innerHTML = alertHtml;

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 5000);
        }

        function showLoadingModal() {
            // You can add a loading overlay here if needed
        }

        function hideLoadingModal() {
            // Hide loading overlay if implemented
        }

        // Add CSRF token to meta if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.getElementsByTagName('head')[0].appendChild(meta);
        }
    </script>
@endsection

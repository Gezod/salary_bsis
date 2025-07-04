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
                            <h1 class="page-title mb-2">Kelola Karyawan & Staff</h1>
                            <p class="text-muted mb-0">Tambah, edit, dan kelola data karyawan dan staff</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="stats-card">
                                <div class="fw-bold text-white fs-4">{{ $employees->total() }}</div>
                                <small class="text-muted">Total Karyawan</small>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                <i class="bi bi-person-plus me-2"></i>Tambah Karyawan
                            </button>
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
                                    <option value="staff" {{ request('departemen') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="karyawan" {{ request('departemen') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
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
                                        <th><i class="bi bi-calendar me-2"></i>Bergabung</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($employee->nama ?? 'N', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $employee->nama ?? '-' }}</div>
                                                        <small class="text-muted">ID: {{ $employee->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-white">
                                                    <div><strong>PIN:</strong> {{ $employee->pin ?? '-' }}</div>
                                                    <div><strong>NIP:</strong> {{ $employee->nip ?? '-' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $employee->jabatan ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $employee->departemen == 'staff' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($employee->departemen ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="text-white">{{ $employee->kantor ?? '-' }}</td>
                                            <td class="text-white">
                                                {{ $employee->created_at ? $employee->created_at->format('d M Y') : '-' }}
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
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                    <h5 class="modal-title text-white" id="addEmployeeModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Tambah Karyawan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                    <h5 class="modal-title text-white" id="editEmployeeModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Data Karyawan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            // Tutup sidebar ketika overlay diklik
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                this.classList.remove('show');
            });
        });

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
            if (confirm(`Apakah Anda yakin ingin menghapus karyawan "${nama}"?\n\nPerhatian: Semua data absensi terkait akan ikut terhapus!`)) {
                const form = document.getElementById('deleteEmployeeForm');
                form.action = `/absensi/role/${id}`;
                form.submit();
            }
        }
    </script>

    <style>
        .modal-content {
            border-radius: 1rem;
        }

        .modal-header, .modal-footer {
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
    </style>
@endsection

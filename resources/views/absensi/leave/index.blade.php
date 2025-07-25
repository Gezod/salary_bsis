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
                            <a class="nav-link" href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <span>Absensi Harian</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active-link" href="{{ route('absensi.leave.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span>Rekap Manual Izin</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.work_time_change.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Pergantian Jam Kerja</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.manual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <span>Manual Presensi</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.role') }}">
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

            {{-- Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                {{-- Navbar --}}
                <nav class="navbar navbar-expand-lg sticky-top">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-file-earmark-medical text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Rekap Manual Izin</span>
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
                            <h1 class="page-title mb-2">Rekap Manual Izin</h1>
                            <p class="text-muted mb-0">Kelola data izin karyawan</p>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- Filter Status --}}
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-funnel me-2"></i>Filter Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('absensi.leave.index') }}">Semua Status</a></li>
                                    <li><a class="dropdown-item" href="{{ route('absensi.leave.index', ['status' => 'pending']) }}">Menunggu</a></li>
                                    <li><a class="dropdown-item" href="{{ route('absensi.leave.index', ['status' => 'approved']) }}">Disetujui</a></li>
                                    <li><a class="dropdown-item" href="{{ route('absensi.leave.index', ['status' => 'rejected']) }}">Ditolak</a></li>
                                </ul>
                            </div>

                            {{-- Statistics Cards --}}
                            <div class="stats-card bg-warning">
                                <div class="fw-bold text-dark fs-5">{{ $stats['pending'] }}</div>
                                <small class="text-dark">Menunggu</small>
                            </div>
                            <div class="stats-card bg-success">
                                <div class="fw-bold text-white fs-5">{{ $stats['approved'] }}</div>
                                <small class="text-light">Disetujui</small>
                            </div>
                            <div class="stats-card bg-danger">
                                <div class="fw-bold text-white fs-5">{{ $stats['rejected'] }}</div>
                                <small class="text-light">Ditolak</small>
                            </div>

                            <a href="{{ route('absensi.leave.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Izin
                            </a>
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

                    {{-- Enhanced Table --}}
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-hash me-2"></i>ID</th>
                                        <th><i class="bi bi-person me-2"></i>Nama</th>
                                        <th><i class="bi bi-building me-2"></i>Departemen</th>
                                        <th><i class="bi bi-briefcase me-2"></i>Jabatan</th>
                                        <th><i class="bi bi-calendar me-2"></i>Tanggal Izin</th>
                                        <th><i class="bi bi-chat-text me-2"></i>Alasan Izin</th>
                                        <th><i class="bi bi-image me-2"></i>Bukti Foto</th>
                                        <th><i class="bi bi-check-circle me-2"></i>Status</th>
                                        <th><i class="bi bi-gear me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($leaves as $leave)
                                        <tr>
                                            <td class="text-white">{{ $leave->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($leave->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $leave->nama }}</div>
                                                        <small class="text-muted">{{ $leave->employee->nip ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($leave->departemen) }}</span>
                                            </td>
                                            <td class="text-white">{{ $leave->jabatan }}</td>
                                            <td class="text-white">{{ $leave->tanggal_izin->format('d M Y') }}</td>
                                            <td class="text-white">
                                                <div style="max-width: 200px;">
                                                    {{ Str::limit($leave->alasan_izin, 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($leave->bukti_foto)
                                                    <a href="{{ Storage::url($leave->bukti_foto) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($leave->status === 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock me-1"></i>Menunggu
                                                    </span>
                                                @elseif ($leave->status === 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Disetujui
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                            onclick="viewLeave({{ $leave->id }})"
                                                            title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    @if ($leave->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="updateStatus({{ $leave->id }}, 'approved')"
                                                                title="Setujui">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="updateStatus({{ $leave->id }}, 'rejected')"
                                                                title="Tolak">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data izin yang tercatat</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($leaves->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $leaves->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-file-earmark-medical me-2"></i>Detail Izin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Update Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-check-square me-2"></i>Update Status Izin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        <input type="hidden" id="leaveId" name="leave_id">
                        <input type="hidden" id="statusAction" name="status">

                        <div class="alert" id="statusAlert" style="display: none;"></div>

                        <div class="mb-3">
                            <label class="form-label text-white">Status</label>
                            <div id="statusDisplay" class="form-control bg-secondary text-white"></div>
                        </div>

                        <div class="mb-3">
                            <label for="approvalNotes" class="form-label text-white">Catatan (Opsional)</label>
                            <textarea class="form-control bg-secondary text-white border-secondary"
                                      id="approvalNotes" name="approval_notes" rows="3"
                                      placeholder="Masukkan catatan approval..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>Batal
                    </button>
                    <button type="button" class="btn" id="confirmStatusBtn" onclick="confirmStatusUpdate()">
                        <i class="bi bi-check me-2"></i>Konfirmasi
                    </button>
                </div>
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

        function viewLeave(id) {
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));

            fetch(`/absensi/leave/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const leave = data.leave;
                        const statusBadge = leave.status === 'pending' ?
                            '<span class="badge bg-warning text-dark">Menunggu</span>' :
                            leave.status === 'approved' ?
                            '<span class="badge bg-success">Disetujui</span>' :
                            '<span class="badge bg-danger">Ditolak</span>';

                        document.getElementById('detailContent').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Nama Karyawan</label>
                                        <div class="text-white">${leave.nama}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Departemen</label>
                                        <div class="text-white">${leave.departemen}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Jabatan</label>
                                        <div class="text-white">${leave.jabatan}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Tanggal Izin</label>
                                        <div class="text-white">${new Date(leave.tanggal_izin).toLocaleDateString('id-ID', {
                                            weekday: 'long',
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        })}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Status</label>
                                        <div>${statusBadge}</div>
                                    </div>
                                    ${leave.approved_by ? `
                                    <div class="mb-3">
                                        <label class="form-label text-white fw-bold">Disetujui Oleh</label>
                                        <div class="text-white">${leave.approved_by}</div>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white fw-bold">Alasan Izin</label>
                                <div class="bg-secondary p-3 rounded text-white">${leave.alasan_izin}</div>
                            </div>
                            ${leave.approval_notes ? `
                            <div class="mb-3">
                                <label class="form-label text-white fw-bold">Catatan Approval</label>
                                <div class="bg-secondary p-3 rounded text-white">${leave.approval_notes}</div>
                            </div>
                            ` : ''}
                            ${leave.bukti_foto ? `
                            <div class="mb-3">
                                <label class="form-label text-white fw-bold">Bukti Foto</label>
                                <div class="mt-2">
                                    <img src="/storage/${leave.bukti_foto}" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                            ` : ''}
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailContent').innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
                });

            modal.show();
        }

        function updateStatus(id, status) {
            document.getElementById('leaveId').value = id;
            document.getElementById('statusAction').value = status;

            const statusText = status === 'approved' ? 'Menyetujui' : 'Menolak';
            const statusClass = status === 'approved' ? 'btn-success' : 'btn-danger';
            const statusIcon = status === 'approved' ? 'bi-check' : 'bi-x';

            document.getElementById('statusDisplay').textContent = statusText + ' izin';
            document.getElementById('confirmStatusBtn').className = `btn ${statusClass}`;
            document.getElementById('confirmStatusBtn').innerHTML = `<i class="bi ${statusIcon} me-2"></i>Konfirmasi`;

            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        function confirmStatusUpdate() {
            const form = document.getElementById('statusForm');
            const formData = new FormData(form);
            const id = document.getElementById('leaveId').value;
            const status = document.getElementById('statusAction').value;

            const submitData = {
                status: status,
                approval_notes: document.getElementById('approvalNotes').value
            };

            document.getElementById('confirmStatusBtn').disabled = true;
            document.getElementById('confirmStatusBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            fetch(`/absensi/leave/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(submitData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal and reload page
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();

                    // Show success message
                    const alertHtml = `
                        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Berhasil!</strong> ${data.message}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    // Add alert to page
                    const alertContainer = document.querySelector('.animate-fade-in');
                    alertContainer.insertAdjacentHTML('afterbegin', alertHtml);

                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error in modal
                    document.getElementById('statusAlert').className = 'alert alert-danger';
                    document.getElementById('statusAlert').textContent = data.message || 'Terjadi kesalahan';
                    document.getElementById('statusAlert').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('statusAlert').className = 'alert alert-danger';
                document.getElementById('statusAlert').textContent = 'Terjadi kesalahan jaringan';
                document.getElementById('statusAlert').style.display = 'block';
            })
            .finally(() => {
                document.getElementById('confirmStatusBtn').disabled = false;
                const status = document.getElementById('statusAction').value;
                const statusClass = status === 'approved' ? 'btn-success' : 'btn-danger';
                const statusIcon = status === 'approved' ? 'bi-check' : 'bi-x';
                document.getElementById('confirmStatusBtn').className = `btn ${statusClass}`;
                document.getElementById('confirmStatusBtn').innerHTML = `<i class="bi ${statusIcon} me-2"></i>Konfirmasi`;
            });
        }

        // Initialize theme on load
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

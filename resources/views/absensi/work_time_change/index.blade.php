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
                        <small class="text-muted">Sistem Absensi</small>
                    </div>

                    <ul class="nav flex-column">    
                        <li class="nav-item">
                            <a class="nav-link active-link" href="{{ route('absensi.work_time_change.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Pergantian Jam Kerja</span>
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

            {{-- Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4">
                {{-- Navbar --}}
                <nav class="navbar navbar-expand-lg sticky-top">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-clock-history text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Pergantian Jam Kerja</span>
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
                            <h1 class="page-title mb-2">Pergantian Jam Kerja</h1>
                            <p class="text-muted mb-0">Data pergantian jam kerja berdasarkan izin</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="stats-card">
                                <div class="fw-bold text-white fs-4">{{ $workTimeChanges->total() }}</div>
                                <small class="text-muted">Total Pergantian</small>
                            </div>
                            <a href="{{ route('absensi.work_time_change.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Pergantian
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
                                        <th><i class="bi bi-person me-2"></i>Karyawan</th>
                                        <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                        <th><i class="bi bi-clock me-2"></i>Jam Masuk Baru</th>
                                        <th><i class="bi bi-clock me-2"></i>Jam Pulang Baru</th>
                                        <th><i class="bi bi-pause-circle me-2"></i>Istirahat</th>
                                        <th><i class="bi bi-chat-text me-2"></i>Keterangan</th>
                                        <th><i class="bi bi-check-circle me-2"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($workTimeChanges as $change)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-white fw-bold">
                                                            {{ substr($change->employee->nama, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-semibold">{{ $change->employee->nama }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $change->employee->departemen }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-white">{{ $change->tanggal->format('d M Y') }}</td>
                                            <td class="text-white">{{ $change->jam_masuk_baru ?? '-' }}</td>
                                            <td class="text-white">{{ $change->jam_pulang_baru ?? '-' }}</td>
                                            <td class="text-white">
                                                @if ($change->jam_istirahat_mulai && $change->jam_istirahat_selesai)
                                                    {{ $change->jam_istirahat_mulai }} -
                                                    {{ $change->jam_istirahat_selesai }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-white">
                                                <div style="max-width: 200px;">
                                                    {{ Str::limit($change->keterangan ?? '-', 50) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($change->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                                    <h5>Tidak ada data</h5>
                                                    <p>Belum ada data pergantian jam kerja</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($workTimeChanges->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $workTimeChanges->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.createElement('div');
            sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(sidebarOverlay);

            // Toggle sidebar
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
@endsection

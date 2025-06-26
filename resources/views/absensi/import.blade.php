@extends('layouts.app')

@section('content')
<style>
    .nav-link.active-link {
        background-color: rgba(255, 255, 255, 0.15);
        font-weight: bold;
        border-radius: 0.35rem;
    }
</style>

<div class="container-fluid bg-dark text-white min-vh-100 px-0">
    <div class="row g-0">
        {{-- Sidebar --}}
        <nav class="col-md-2 d-none d-md-block sidebar border-end" style="background-color: #374151;">
            <div class="position-sticky pt-3 text-white">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                        alt="Bank Sampah" class="img-fluid rounded-circle" style="max-width: 100px;">
                    <h6 class="mt-2">Bank Sampah</h6>
                </div>
                <ul class="nav flex-column px-2">
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active-link text-white' : 'text-white' }}"
                            href="{{ route('absensi.index') }}">
                            <i class="bi bi-calendar-check me-2"></i> Absensi Harian
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('absensi.recap') ? 'active-link text-white' : 'text-white' }}"
                            href="{{ route('absensi.recap') }}">
                            <i class="bi bi-file-earmark-text me-2"></i> Rekap Denda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('absensi.import') ? 'active-link text-white' : 'text-white' }}"
                            href="{{ route('absensi.import') }}">
                            <i class="bi bi-upload me-2"></i> Import Absensi
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="col-md-10 ms-sm-auto px-md-4">
            <nav class="navbar navbar-expand-lg" style="background-color: #1e293b;">
                <div class="container-fluid">
                    <span class="navbar-brand fw-semibold text-white">Sistem Absensi</span>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3 text-light">👋 Halo, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="mt-4 mb-3">
                <h1 class="h5 text-white">Upload Absensi Excel</h1>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form Upload --}}
            <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File Excel (.xlsx)</label>
                    <input type="file" name="file" id="file" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <button type="submit" class="btn btn-outline-info">Import</button>
            </form>
        </main>
    </div>
</div>
@endsection

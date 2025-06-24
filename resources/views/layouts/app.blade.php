<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  {{-- Bootstrap & Google Fonts --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

  <style>
    body {
      font-size: 0.9rem;
    }

    .sidebar {
      width: 220px;
      position: fixed;
      top: 56px;
      left: 0;
      height: 100%;
      background-color: #2c2f33; /* ✨ Warna hitam halus */
      padding-top: 1rem;
      border-right: 1px solid #444;
    }

    .sidebar .nav-link {
      color: #ffffff;
      transition: background-color 0.3s ease;
    }

    .sidebar .nav-link:hover {
      background-color: #3a3d40; /* Hover sedikit lebih terang */
      border-radius: 4px;
    }

    .content-wrapper {
      margin-left: 220px;
      padding: 1.5rem;
      margin-top: 56px;
    }
  </style>

  @stack('styles')
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}" alt="Logo" height="40" class="me-2">
      </a>
    </div>
  </nav>

  {{-- SIDEBAR --}}
  <div class="sidebar">
    <ul class="nav flex-column px-3">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('presensi.index') }}">📋 Data Presensi</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">📊 Rekap Bulanan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">⚙️ Pengaturan</a>
      </li>
    </ul>
  </div>

  {{-- KONTEN --}}
  <div class="content-wrapper">
    @yield('content')
  </div>

  {{-- Script --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')

</body>
</html>

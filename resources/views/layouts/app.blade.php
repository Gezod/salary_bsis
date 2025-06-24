<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @stack('styles')
  <style>
    body { font-size: 0.9rem; }
    .sidebar {
      width: 220px;
      position: fixed;
      top: 56px;
      left: 0;
      height: 100%;
      background-color: #f8f9fa;
      padding-top: 1rem;
    }
    .content-wrapper {
      margin-left: 220px;
      padding: 1.5rem;
      margin-top: 56px;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Sistem Presensi</a>
    </div>
  </nav>

  {{-- SIDEBAR --}}
  <div class="sidebar border-end">
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

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>

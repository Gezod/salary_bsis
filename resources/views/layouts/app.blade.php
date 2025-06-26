<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Tambahan style jika perlu --}}
    <style>
    html, body {
        margin: 0;
        padding: 0;
        background-color: #0f172a; /* dark slate */
    }
    .sidebar {
        min-height: 100vh;
    }
    .nav-link.active-link {
        background-color: rgba(0, 0, 0, 0.2);
        font-weight: bold;
        color: #fff !important;
        border-radius: 0.25rem;
    }
    .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.1);
        color: #fff !important;
    }
    </style>
</head>
<body>
    <div class="container-fluid">
        @yield('content')
    </div>

    {{-- Bootstrap JS (opsional jika pakai dropdown, sidebar collapse, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

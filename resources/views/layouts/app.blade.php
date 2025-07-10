<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSIS Absensi</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png"
        href="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Tambahan style jika perlu --}}
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            /* dark slate */
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

        .active-link {
            background-color: rgba(220, 53, 69, 0.2) !important;
            color: #7FFFD4 !important;
            border-left: 3px solid #7FFFD4;
        }

        .active-link .icon-wrapper i {
            color: #7FFFD4 !important;
        }

        /* Warna hijau untuk teks menu aktif */
        .active-link span {
            color: #28a745 !important;
            /* Warna hijau Bootstrap */
        }

        /* Warna ikon hijau saat aktif */
        .active-link .icon-wrapper i {
            color: #28a745 !important;
        }

        /* Opsional: tambahkan efek hover */
        .nav-link:hover span {
            color: #28a745 !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover .icon-wrapper i {
            color: #28a745 !important;
            transition: color 0.3s ease;
        }

        /* Warna default teks menu */
        .sidebar .nav-link .menu-text {
            color: #adb5bd;
            /* Warna abu-abu Bootstrap */
            transition: color 0.3s ease;
        }

        /* Warna teks menu saat aktif */
        .sidebar .nav-link.active-link .menu-text {
            color: #00FFFF !important;
            /* Warna merah */
            font-weight: 500;
        }

        /* Warna teks menu saat hover */
        .sidebar .nav-link:hover .menu-text {
            color: #00FFFF !important;
        }

        /* Warna ikon saat aktif */
        .sidebar .nav-link.active-link .icon-wrapper i {
            color: #00FFFF !important;
        }
        
    </style>
</head>

<body>
    <div class="container-fluid">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

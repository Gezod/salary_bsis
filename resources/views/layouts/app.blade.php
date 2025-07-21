<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BSIS Absensi</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}">

    {{-- CSS Libraries --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Custom Styles --}}
    <style>
        :root {
            --primary-color: #0f172a; /* dark slate */
            --active-color: #00FFFF;
            --hover-color: #28a745;
            --text-color: #adb5bd;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--primary-color);
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--primary-color);
        }

        /* Navigation Links */
        .nav-link {
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
            color: white;
        }

        .nav-link.active-link {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--active-color) !important;
            border-left: 3px solid var(--active-color);
            font-weight: 500;
        }

        .active-link .icon-wrapper i,
        .active-link .menu-text {
            color: var(--active-color) !important;
        }

        .nav-link:hover .menu-text,
        .nav-link:hover .icon-wrapper i {
            color: var(--hover-color) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    @yield('content')

    {{-- JavaScript Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Your custom scripts would be loaded in child views --}}
</body>

</html>

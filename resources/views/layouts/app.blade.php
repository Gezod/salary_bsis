<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <title>BSIS Absensi</title>

    {{-- Custom Styles --}}
    <style>
        :root {
            --primary-color: #3b82f6;
            /* blue */
            --primary-dark: #1e40af;
            --active-color: #00FFFF;
            --hover-color: #28a745;
            --text-color: #64748b;
            --bg-color: #ffffff;
            --card-bg: #f8fafc;
            --sidebar-bg: #e2e8f0;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
        }

        [data-theme="dark"] {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --active-color: #00FFFF;
            --hover-color: #28a745;
            --text-color: #adb5bd;
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --sidebar-bg: #334155;
            --border-color: #475569;
            --text-primary: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--bg-color) 0%, var(--card-bg) 100%);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, var(--card-bg) 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            border: none !important;
            transition: all 0.3s ease;
        }

        /* Navigation Links */
        .nav-link {
            color: var(--text-color);
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color) !important;
            transform: translateX(4px);
        }

        .nav-link.active-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .navbar {
            background: linear-gradient(90deg, var(--card-bg) 0%, var(--sidebar-bg) 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .theme-toggle {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 1rem;
        }

        .theme-toggle:hover {
            background: var(--primary-color);
            color: white;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            margin-right: 1rem;
        }

        .icon-wrapper i {
            color: white !important;
        }

        .overtime-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            margin-right: 1rem;
        }

        .overtime-icon-wrapper i {
            color: white !important;
        }

        .logo-container {
            background: rgba(59, 130, 246, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }
        }

        /* Light theme specific styles */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .form-control {
            background: var(--bg-color) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.75rem !important;
            color: var(--text-primary) !important;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: var(--bg-color) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-1px);
        }

        .btn {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .table-dark {
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-dark thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            color: white !important;
        }

        .table-dark tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            background: var(--card-bg);
        }

        .table-dark tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
            transform: scale(1.01);
        }

        .table-dark tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
            color: var(--text-primary) !important;
        }

        .text-white {
            color: var(--text-primary) !important;
        }

        .text-muted {
            color: var(--text-color) !important;
        }

        .page-title {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2rem;
        }

        .filter-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(30, 64, 175, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.2);
        }

        /* Dark theme overrides */
        [data-theme="dark"] .table-dark tbody tr {
            background: var(--card-bg);
        }

        [data-theme="dark"] .table-dark tbody tr:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        [data-theme="dark"] .navbar-brand {
            color: white !important;
        }

        [data-theme="light"] .navbar-brand {
            color: var(--text-primary) !important;
        }

        .modal-content {
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color) !important;
        }

        .modal-title {
            color: var(--text-primary) !important;
        }

        .btn-close-white {
            filter: var(--text-primary)==#ffffff ? invert(1): invert(0);
        }

        /* Basic theme setup - detailed styles are in style_index.css */
        body {
            transition: all 0.3s ease;
        }
    </style>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body data-theme="light">
    @yield('content')

    {{-- JavaScript Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Your custom scripts would be loaded in child views --}}
    <script>
        // Theme toggle functionality
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update toggle button icon
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }

            // Update SweetAlert2 theme
            updateSweetAlertTheme(newTheme);
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            // Update toggle icon based on current theme
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }

            // Initialize SweetAlert2 theme
            updateSweetAlertTheme(savedTheme);
        });

        // Update SweetAlert2 theme
        function updateSweetAlertTheme(theme) {
            const isDark = theme === 'dark';
            window.swalTheme = {
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#ffffff' : '#1e293b',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6c757d'
            };
        }
    </script>
</body>


</html>

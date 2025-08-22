@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/style_index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_recover.css') }}" rel="stylesheet">

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
                            <a class="nav-link" href="{{ route('absensi.import') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-upload"></i>
                                    </div>
                                    <span>Import Absensi</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.leave.index') }}">
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
                            <a class="nav-link" href="{{ route('absensi.half-day-manual') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Absensi Setengah Hari</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.late-recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="late-icon-wrapper me-3">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span>Rekap Keterlambatan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="fine-icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <span>Rekap Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('absensi.role') }}">
                                <div class="d-flex align-items-center">
                                    <div class="employee-icon-wrapper me-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <span>Kelola Karyawan</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('overtime.overview') }}">
                                <div class="d-flex align-items-center">
                                    <div class="overtime-icon-wrapper me-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <span>Sistem Lembur</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payroll.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="cash-icon-wrapper me-3">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <span>Sistem Payroll</span>
                                </div>
                            </a>
                        </li>
                        {{-- New Buku Panduan Menu --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('panduan.*') ? 'active-link' : '' }}"
                                href="{{ route('panduan.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="guide-icon-wrapper me-3">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <span>Buku Panduan</span>
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
                                <i class="bi bi-book"></i>
                            </div>
                            <span class="navbar-brand fw-bold mb-0">Buku Panduan BSIS ABSENSI</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="theme-toggle" onclick="toggleTheme()">
                                <i class="bi bi-moon-fill"></i>
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
                            <h1 class="page-title mb-2">Buku Panduan BSIS ABSENSI</h1>
                            <p class="mb-0 text-muted">Panduan lengkap penggunaan sistem absensi Bank Sampah Induk Surabaya</p>
                        </div>
                        <div class="pdf-controls">
                            <button type="button" class="btn btn-primary me-2" onclick="downloadPDF()">
                                <i class="bi bi-download me-2"></i>Download PDF
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="printPDF()">
                                <i class="bi bi-printer me-2"></i>Print
                            </button>
                        </div>
                    </div>

                    {{-- Video Panduan Section --}}
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-play-btn me-2"></i>Video Panduan</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Berikut adalah link Google Drive yang berisi video panduan penggunaan sistem:
                            </div>

                            <div class="drive-link-container p-4 border rounded bg-light">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Google_Drive_icon_%282020%29.svg/1200px-Google_Drive_icon_%282020%29.svg.png"
                                         alt="Google Drive" class="drive-icon me-3" style="width: 40px;">
                                    <div>
                                        <h5 class="mb-0">Video Panduan BSIS ABSENSI</h5>
                                        <small class="text-muted">Google Drive Folder</small>
                                    </div>
                                </div>

                                <p class="mb-3">Folder ini berisi kumpulan video panduan penggunaan sistem absensi BSIS. Silakan klik link di bawah untuk mengaksesnya:</p>

                                <a href="https://drive.google.com/drive/folders/17QI6lvAcTAOYz2UDyr6crldlVf50tkC4?usp=sharing"
                                   target="_blank"
                                   class="btn btn-success mb-3">
                                    <i class="bi bi-google me-2"></i>Buka di Google Drive
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- PDF Viewer Section --}}
                    <div class="pdf-viewer-container">
                        <div class="pdf-toolbar">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pdf-info">
                                    <i class="bi bi-file-pdf text-danger me-2"></i>
                                    <span class="fw-semibold">Buku-Panduan-BSIS-ABSENSI.pdf</span>
                                </div>
                                <div class="pdf-controls-inline">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="zoomOut()">
                                        <i class="bi bi-zoom-out"></i>
                                    </button>
                                    <span class="zoom-level mx-2" id="zoomLevel">100%</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="zoomIn()">
                                        <i class="bi bi-zoom-in"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetZoom()">
                                        <i class="bi bi-aspect-ratio"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pdf-content">
                            <iframe
                                id="pdfViewer"
                                src="{{ route('panduan.view-pdf') }}"
                                type="application/pdf"
                                width="100%"
                                height="800px"
                                style="border: none; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                <div class="pdf-fallback">
                                    <div class="text-center py-5">
                                        <i class="bi bi-exclamation-triangle display-4 text-warning mb-3"></i>
                                        <h5>Browser tidak mendukung tampilan PDF</h5>
                                        <p class="text-muted mb-4">Browser Anda tidak dapat menampilkan PDF secara langsung.</p>
                                        <a href="{{ route('panduan.view-pdf') }}" class="btn btn-primary" target="_blank">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>Buka PDF di Tab Baru
                                        </a>
                                        <a href="{{ route('panduan.download-pdf') }}" class="btn btn-success ms-2">
                                            <i class="bi bi-download me-2"></i>Download PDF
                                        </a>
                                    </div>
                                </div>
                            </iframe>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentZoom = 100;

        document.addEventListener('DOMContentLoaded', function() {
            // Load saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.setAttribute('data-theme', savedTheme);

            // Update toggle icon based on current theme
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }

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

            // Check if PDF loaded successfully
            checkPDFLoad();
        });

        // Theme toggle function
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

        // PDF Control Functions
        function downloadPDF() {
            window.open('{{ route("panduan.download-pdf") }}', '_blank');
        }

        function printPDF() {
            const iframe = document.getElementById('pdfViewer');
            if (iframe) {
                try {
                    iframe.contentWindow.print();
                } catch (e) {
                    // Fallback: open PDF in new window for printing
                    window.open('{{ route("panduan.view-pdf") }}', '_blank');
                }
            }
        }

        function zoomIn() {
            currentZoom += 25;
            if (currentZoom > 200) currentZoom = 200;
            updateZoom();
        }

        function zoomOut() {
            currentZoom -= 25;
            if (currentZoom < 50) currentZoom = 50;
            updateZoom();
        }

        function resetZoom() {
            currentZoom = 100;
            updateZoom();
        }

        function updateZoom() {
            document.getElementById('zoomLevel').textContent = currentZoom + '%';
            const iframe = document.getElementById('pdfViewer');
            if (iframe) {
                iframe.style.transform = `scale(${currentZoom / 100})`;
                iframe.style.transformOrigin = 'top left';

                // Adjust container height based on zoom
                const newHeight = (800 * currentZoom / 100) + 'px';
                iframe.style.height = newHeight;
            }
        }

        function checkPDFLoad() {
            const iframe = document.getElementById('pdfViewer');

            iframe.addEventListener('load', function() {
                console.log('PDF loaded successfully');
            });

            iframe.addEventListener('error', function() {
                console.error('Error loading PDF');
                showPDFError();
            });

            // Check if PDF exists after a delay
            setTimeout(function() {
                fetch('{{ route("panduan.view-pdf") }}', { method: 'HEAD' })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('PDF not found');
                        }
                    })
                    .catch(error => {
                        showPDFError();
                    });
            }, 2000);
        }

        function showPDFError() {
            Swal.fire({
                icon: 'warning',
                title: 'File PDF Tidak Ditemukan',
                text: 'File "Buku-Panduan-BSIS-ABSENSI.pdf" tidak ditemukan di server. Silakan hubungi administrator.',
                confirmButtonText: 'OK',
                background: window.swalTheme?.background || '#ffffff',
                color: window.swalTheme?.color || '#1e293b'
            });
        }
    </script>

    <style>
        /* PDF Viewer Styling */
        .pdf-viewer-container {
            background: var(--bs-body-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pdf-toolbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pdf-info {
            display: flex;
            align-items: center;
        }

        .pdf-controls-inline {
            display: flex;
            align-items: center;
        }

        .pdf-controls-inline .btn {
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .pdf-controls-inline .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .zoom-level {
            color: white;
            font-weight: 600;
            min-width: 50px;
            text-align: center;
        }

        .pdf-content {
            padding: 20px;
            background: #f8f9fa;
            min-height: 800px;
        }

        [data-theme="dark"] .pdf-content {
            background: #1a1d23;
        }

        .pdf-fallback {
            background: var(--bs-body-bg);
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        [data-theme="dark"] .pdf-fallback {
            border-color: #495057;
        }

        /* Video Panduan Section */
        .drive-link-container {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border-radius: 8px;
        }

        [data-theme="dark"] .drive-link-container {
            background: linear-gradient(to right, #2d3748, #4a5568);
            color: #e2e8f0;
        }

        .drive-icon {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Quick Guide Cards */
        .quick-guide {
            margin-top: 2rem;
        }

        .guide-card {
            background: var(--bs-body-bg);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .guide-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .guide-card h5 {
            color: var(--bs-body-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .guide-card p {
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* PDF Controls */
        .pdf-controls {
            display: flex;
            gap: 0.5rem;
        }

        .pdf-controls .btn {
            white-space: nowrap;
        }

        /* Guide Icon Wrapper */
        .guide-icon-wrapper {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .pdf-toolbar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .pdf-controls-inline {
                justify-content: center;
            }

            .pdf-controls {
                flex-direction: column;
                gap: 0.5rem;
            }

            #pdfViewer {
                height: 600px !important;
            }

            .guide-card {
                margin-bottom: 1rem;
            }
        }

        /* Dark theme specific adjustments */
        [data-theme="dark"] .guide-card {
            background: #2d3748;
            border-color: #4a5568;
        }

        [data-theme="dark"] .guide-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        /* Animation */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

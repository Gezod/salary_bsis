@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --sidebar-bg: #334155;
            --border-color: #475569;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .text-muted{
             background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 50;
            font-size: 1rem;
        }

        ::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #475569 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            border: none !important;
        }

        .nav-link {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            color: #cbd5e1 !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(4px);
        }

        .nav-link.active-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .navbar {
            background: linear-gradient(90deg, var(--card-bg) 0%, #334155 100%) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .form-control {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(30, 41, 59, 0.9) !important;
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-outline-info {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-info:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .page-title {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 2rem;
        }

        .upload-section {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background: rgba(30, 41, 59, 0.3);
        }

        .file-upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
        }

        .file-upload-area.dragover {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.02);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
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

        .upload-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            margin: 0 auto 1rem;
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .file-info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-top: 1rem;
            color: #3b82f6;
            font-size: 0.875rem;
        }
    </style>

    <div class="container-fluid min-vh-100 px-0">
        <div class="row g-0">
            {{-- Enhanced Sidebar --}}
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="position-sticky pt-4 px-3">
                    <div class="logo-container text-center">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah" class="img-fluid rounded-circle mb-3" style="max-width: 80px; border: 3px solid rgba(255,255,255,0.2);">
                        <h6 class="text-white fw-bold mb-0">Bank Sampah</h6>
                        <small class="text-muted">Sistem Absensi</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.index') ? 'active-link' : '' }}"
                                href="{{ route('absensi.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <span>Absensi Harian</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.recap') ? 'active-link' : '' }}"
                                href="{{ route('absensi.recap') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <span>Rekap Denda</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('absensi.import') ? 'active-link' : '' }}"
                                href="{{ route('absensi.import') }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper me-3">
                                        <i class="bi bi-upload"></i>
                                    </div>
                                    <span>Import Absensi</span>
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
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-upload text-white"></i>
                            </div>
                            <span class="navbar-brand fw-bold text-white mb-0">Sistem Absensi</span>
                        </div>
                        <div class="ms-auto d-flex align-items-center">
                            <div class="me-4 text-light">
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
                            <h1 class="page-title mb-2">Import Data Absensi</h1>
                            <p class="text-muted mb-0">Upload file Excel untuk mengimpor data absensi secara batch</p>
                        </div>
                        <div class="stats-card">
                            <div class="fw-bold text-white fs-4">
                                <i class="bi bi-file-earmark-excel text-success"></i>
                            </div>
                            <small class="text-muted">Excel Import</small>
                        </div>
                    </div>

                    {{-- Notifications --}}
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                            <div>
                                <strong>Error!</strong> {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    {{-- Upload Section --}}
                    <div class="upload-section">
                        <h5 class="text-white mb-4 d-flex align-items-center">
                            <i class="bi bi-cloud-upload me-2 text-primary"></i>
                            Upload File Excel
                        </h5>

                        <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <div class="file-upload-area" id="fileUploadArea">
                                <div class="upload-icon">
                                    <i class="bi bi-file-earmark-excel text-white fs-1"></i>
                                </div>
                                <h6 class="text-white mb-2">Drag & Drop file Excel di sini</h6>
                                <p class="text-muted mb-3">atau klik untuk memilih file</p>
                                <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required style="display: none;">
                                <button type="button" class="btn btn-outline-info" onclick="document.getElementById('file').click()">
                                    <i class="bi bi-folder2-open me-2"></i>Pilih File
                                </button>
                                <div class="file-info mt-3" id="fileInfo" style="display: none;">
                                    <i class="bi bi-file-earmark-check me-2"></i>
                                    <span id="fileName"></span>
                                    <small class="d-block mt-1" id="fileSize"></small>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-info-circle me-2 text-info"></i>
                                                Format File Excel
                                            </h6>
                                            <ul class="text-muted mb-0 small">
                                                <li>Format file: .xlsx atau .xls</li>
                                                <li>Maksimal ukuran: 10MB</li>
                                                <li>Kolom yang diperlukan: Nama, Departemen, Tanggal, Jam</li>
                                                <li>Pastikan format tanggal: DD/MM/YYYY</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-white mb-3">
                                                <i class="bi bi-download me-2 text-success"></i>
                                                Template Excel
                                            </h6>
                                            <p class="text-muted small mb-3">Download template untuk memudahkan proses import</p>
                                            <a href="#" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-download me-2"></i>Download Template
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                    <i class="bi bi-cloud-upload me-2"></i>Import Data Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Add Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // File upload functionality
        const fileInput = document.getElementById('file');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                displayFileInfo(file);
            }
        });

        // Handle drag and drop
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                if (file.type.includes('sheet') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
                    fileInput.files = files;
                    displayFileInfo(file);
                } else {
                    alert('Please select a valid Excel file (.xlsx or .xls)');
                }
            }
        });

        function displayFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = `Size: ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            fileInfo.style.display = 'block';
            submitBtn.disabled = false;
        }

        // Click to select file
        fileUploadArea.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                fileInput.click();
            }
        });
    </script>
@endsection

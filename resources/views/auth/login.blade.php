<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

     <title>BSIS Absensi</title>
      <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images\Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            min-height: 100vh;
        }

        ::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 1;
        }

        .logo-wrapper {
            width: 100%;
            max-width: 300px;
            /* maksimal lebar */
            margin: 0 auto 1.5rem;
            background: none;
            border: none;
            border-radius: 0;
            padding: 0;
            display: block;
            backdrop-filter: none;
        }

        .logo-wrapper img {
            width: 100%;
            height: auto;
            border: none;
            border-radius: 0;
            object-fit: contain;
            display: block;
        }

        .login-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .login-form {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-input:focus {
            background: rgba(30, 41, 59, 0.9) !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-1px);
            outline: none;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: 0.375rem;
            background: rgba(30, 41, 59, 0.8);
            margin-right: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-input:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .checkbox-label {
            color: #cbd5e1;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .forgot-password {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .register-link p {
            color: #94a3b8;
            font-size: 0.875rem;
            margin: 0;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #60a5fa;
            text-decoration: underline;
        }

        .error-message {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .success-message {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .input-icon .form-input {
            padding-left: 2.75rem;
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

        @media (max-width: 640px) {
            .login-container {
                padding: 1rem;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-form {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card animate-fade-in">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah Logo">
                    </div>
                    <h1 class="login-title">Bank Sampah</h1>
                    <p class="login-subtitle">Sistem Absensi Karyawan</p>
                </div>
            </div>

            <!-- Form -->
            <div class="login-form">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="success-message">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Email Address
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input id="email" class="form-input" type="email" name="email"
                                value="{{ old('email') }}" required autofocus autocomplete="username"
                                placeholder="Enter your email address">
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-2"></i>Password
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input id="password" class="form-input" type="password" name="password" required
                                autocomplete="current-password" placeholder="Enter your password">
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-wrapper">
                        <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                        <label for="remember_me" class="checkbox-label">
                            Remember me for 30 days
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In to Dashboard
                    </button>

                    <!-- Forgot Password -->
                    <div style="text-align: center; margin-top: 1rem;">
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                <i class="bi bi-question-circle me-1"></i>
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <!-- Register Link -->
                    <div class="register-link">
                        <p>
                            Belum punya akun?
                            <a href="{{ route('register') }}">
                                Daftar terlebih dahulu
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

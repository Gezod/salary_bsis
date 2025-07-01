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

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .register-header::before {
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
    max-width: 300px; /* maksimal lebar */
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


        .register-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .register-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .register-form {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(30, 41, 59, 0.8);
            border: 2px dashed var(--border-color);
            border-radius: 0.75rem;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .file-input-label:hover {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .file-input-label.has-file {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-style: solid;
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

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .login-link p {
            color: #94a3b8;
            font-size: 0.875rem;
            margin: 0;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak .strength-fill {
            width: 33%;
            background: var(--danger-color);
        }

        .strength-medium .strength-fill {
            width: 66%;
            background: var(--warning-color);
        }

        .strength-strong .strength-fill {
            width: 100%;
            background: var(--success-color);
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
            .register-container {
                padding: 1rem;
            }

            .register-header {
                padding: 2rem 1.5rem;
            }

            .register-form {
                padding: 2rem 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card animate-fade-in">
            <!-- Header -->
            <div class="register-header">
                <div class="logo-container">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
                            alt="Bank Sampah Logo">
                    </div>
                    <h1 class="register-title">Create Account</h1>
                    <p class="register-subtitle">Join Bank Sampah Attendance System</p>
                </div>
            </div>

            <!-- Form -->
            <div class="register-form">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-2"></i>Full Name
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input id="name" class="form-input" type="text" name="name"
                                value="{{ old('name') }}" required autofocus autocomplete="name"
                                placeholder="Enter your full name">
                        </div>
                        @error('name')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Email Address
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input id="email" class="form-input" type="email" name="email"
                                value="{{ old('email') }}" required autocomplete="username"
                                placeholder="Enter your email address">
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Fields -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-2"></i>Password
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-lock"></i>
                                <input id="password" class="form-input" type="password" name="password" required
                                    autocomplete="new-password" placeholder="Create password">
                            </div>
                            <div class="password-strength" id="passwordStrength" style="display: none;">
                                <div class="strength-bar">
                                    <div class="strength-fill"></div>
                                </div>
                                <span class="strength-text"></span>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-shield-check me-2"></i>Confirm Password
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-shield-check"></i>
                                <input id="password_confirmation" class="form-input" type="password"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm password">
                            </div>
                            @error('password_confirmation')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>



                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-person-plus me-2"></i>
                        Create My Account
                    </button>

                    <!-- Login Link -->
                    <div class="login-link">
                        <p>
                            Already have an account?
                            <a href="{{ route('login') }}">
                                Sign in here
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function(e) {
            const password = e.target.value;

            if (password.length === 0) {
                strengthIndicator.style.display = 'none';
                return;
            }

            strengthIndicator.style.display = 'block';

            let strength = 0;
            let strengthText = '';

            // Check password criteria
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength indicator
            strengthIndicator.className = 'password-strength';

            if (strength <= 2) {
                strengthIndicator.classList.add('strength-weak');
                strengthText = 'Weak password';
            } else if (strength <= 3) {
                strengthIndicator.classList.add('strength-medium');
                strengthText = 'Medium strength';
            } else {
                strengthIndicator.classList.add('strength-strong');
                strengthText = 'Strong password';
            }

            strengthIndicator.querySelector('.strength-text').textContent = strengthText;
        });


    </script>
</body>

</html>

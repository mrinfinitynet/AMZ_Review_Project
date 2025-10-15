<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - {{ $settings['site_title'] ?? 'Review Pro' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --dark-card-hover: #334155;
            --dark-text: #e2e8f0;
            --dark-text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            height: 100vh;
            padding: 0;
            margin: 0;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
            z-index: 0;
        }

        @keyframes moveBackground {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.1;
            z-index: 0;
            animation: float 15s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: 10%;
            animation-delay: 5s;
        }

        .floating-element:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            right: 20%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-50px) rotate(180deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease-out;
            width: 100%;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .logo-icon i {
            font-size: 36px;
            color: white;
        }

        .login-title {
            color: var(--dark-text);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: var(--dark-text-muted);
            font-size: 15px;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            color: var(--dark-text);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            font-size: 18px;
            color: var(--primary);
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            background: var(--dark-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 12px !important;
            padding: 14px 20px !important;
            padding-left: 50px !important;
            color: var(--dark-text) !important;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
            background: var(--dark-bg) !important;
        }

        .form-control::placeholder {
            color: var(--dark-text-muted);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--dark-text-muted);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            background: var(--dark-bg);
            cursor: pointer;
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: var(--dark-text-muted);
            font-size: 14px;
            cursor: pointer;
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 15px;
            color: var(--dark-text-muted);
            font-size: 13px;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: var(--dark-text-muted);
            font-size: 13px;
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .footer-text a:hover {
            color: var(--secondary);
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 14px 20px;
            color: var(--danger);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s;
        }

        .alert-custom i {
            font-size: 20px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--success);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 40px 30px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                @if(isset($settings['logo']) && $settings['logo'])
                    <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['site_title'] ?? 'Review Pro' }}" style="max-height: 70px; width: auto; margin-bottom: 20px;">
                @else
                    <div class="logo-icon">
                        <i class="mdi mdi-star"></i>
                    </div>
                @endif
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to your {{ $settings['site_title'] ?? 'Review Pro' }} account</p>
            </div>

            <!-- Alert Messages -->
            @if (session()->has("error"))
                <div class="alert-custom">
                    <i class="mdi mdi-alert-circle"></i>
                    <span>{{ session()->get("error") }}</span>
                </div>
            @endif

            @if (session()->has("success"))
                <div class="alert-custom alert-success">
                    <i class="mdi mdi-check-circle"></i>
                    <span>{{ session()->get("success") }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('loginSubmit') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="mdi mdi-email"></i>
                        Email Address
                    </label>
                    <div class="input-wrapper">
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            placeholder="Enter your email"
                            value="admin@domain.com"
                            required
                            autofocus
                        />
                        <i class="mdi mdi-email-outline input-icon"></i>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="mdi mdi-lock"></i>
                        Password
                    </label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            placeholder="Enter your password"
                            value="00000000"
                            required
                        />
                        <i class="mdi mdi-lock-outline input-icon"></i>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="rememberMe"
                    />
                    <label class="form-check-label" for="rememberMe">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="mdi mdi-login"></i>
                    <span>Sign In</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="footer-text">
                <p>&copy; 2025 {{ $settings['site_title'] ?? 'Review Pro' }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Toastr Configuration
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Review Pro</title>
    <meta name="description" content="@yield('description', 'Review Pro - Amazon Review Management Bot')">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
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
            background: var(--dark-bg);
            color: var(--dark-text);
            line-height: 1.6;
            padding-top: 80px; /* Space for fixed navbar */
        }

        /* Navigation */
        .navbar {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(20px);
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-text) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 28px;
            color: var(--primary);
        }

        .btn-back {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary) !important;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--primary);
            color: white !important;
            transform: translateY(-2px);
        }

        /* Page Content */
        .page-header {
            background: var(--dark-card);
            border-bottom: 1px solid var(--border-color);
            padding: 60px 0 40px;
            text-align: center;
        }

        .page-title {
            font-size: 42px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--dark-text), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .page-subtitle {
            font-size: 18px;
            color: var(--dark-text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .page-content {
            padding: 60px 0;
            max-width: 900px;
            margin: 0 auto;
        }

        .page-content h2 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-text);
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .page-content h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark-text);
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .page-content p {
            color: var(--dark-text-muted);
            margin-bottom: 20px;
            font-size: 16px;
        }

        .page-content ul, .page-content ol {
            color: var(--dark-text-muted);
            margin-bottom: 20px;
            padding-left: 30px;
        }

        .page-content li {
            margin-bottom: 10px;
        }

        .page-content strong {
            color: var(--dark-text);
        }

        .page-content a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-content a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        .info-box {
            background: var(--dark-card);
            border-left: 4px solid var(--primary);
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .info-box h4 {
            color: var(--primary);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            background: var(--dark-bg);
            border-top: 1px solid var(--border-color);
            padding: 40px 0 20px;
            margin-top: 80px;
        }

        .footer-text {
            color: var(--dark-text-muted);
            text-align: center;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 32px;
            }

            .page-content h2 {
                font-size: 26px;
            }

            .page-content h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="mdi mdi-star"></i>
                Review Pro
            </a>
            <a href="/" class="btn-back ms-auto">
                <i class="mdi mdi-arrow-left"></i> Back to Home
            </a>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">@yield('title')</h1>
            <p class="page-subtitle">@yield('subtitle')</p>
        </div>
    </section>

    <!-- Page Content -->
    <section class="page-content">
        <div class="container">
            @yield('content')
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">&copy; 2025 Review Pro. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

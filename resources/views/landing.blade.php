<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Review Pro - Amazon Review Management Bot</title>
    <meta name="description" content="Automate and streamline your Amazon review campaigns with Review Pro. Powerful, efficient, and easy to use.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--dark-bg);
            color: var(--dark-text);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(20px);
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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

        .nav-link {
            color: var(--dark-text-muted) !important;
            font-weight: 500;
            padding: 8px 20px !important;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(99, 102, 241, 0.1);
        }

        .btn-login {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary) !important;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary);
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-get-started {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white !important;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-get-started:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 100px 0;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 30s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.1;
            animation: float 20s ease-in-out infinite;
        }

        .shape1 {
            width: 400px;
            height: 400px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape2 {
            width: 300px;
            height: 300px;
            bottom: 10%;
            right: 15%;
            animation-delay: 5s;
        }

        .shape3 {
            width: 200px;
            height: 200px;
            top: 60%;
            left: 60%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-80px) rotate(180deg);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 25px;
            background: linear-gradient(135deg, var(--dark-text), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 20px;
            color: var(--dark-text-muted);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-image {
            position: relative;
            z-index: 1;
        }

        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-color);
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: var(--dark-card);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .stat-card {
            text-align: center;
            padding: 30px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--dark-text-muted);
            font-size: 16px;
            font-weight: 500;
        }

        /* Features Section */
        .features-section {
            padding: 120px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-title h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--dark-text);
        }

        .section-title p {
            font-size: 18px;
            color: var(--dark-text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.2);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .feature-icon i {
            font-size: 32px;
            color: white;
        }

        .feature-card h4 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .feature-card p {
            color: var(--dark-text-muted);
            line-height: 1.7;
            margin: 0;
        }

        /* Pricing Section */
        .pricing-section {
            padding: 120px 0;
            background: var(--dark-card);
        }

        .pricing-card {
            background: var(--dark-bg);
            border: 2px solid var(--border-color);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
        }

        .pricing-card.featured {
            border-color: var(--primary);
            transform: scale(1.05);
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.3);
        }

        .pricing-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 8px 25px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pricing-card h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .pricing-price {
            font-size: 48px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .pricing-price span {
            font-size: 18px;
            color: var(--dark-text-muted);
            font-weight: 500;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .pricing-features li {
            padding: 12px 0;
            color: var(--dark-text-muted);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pricing-features li i {
            color: var(--success);
            font-size: 20px;
        }

        .btn-pricing {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-pricing.primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
        }

        .btn-pricing.secondary {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--dark-text);
        }

        .btn-pricing:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        /* CTA Section */
        .cta-section {
            padding: 120px 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="white" fill-opacity="0.1"/></svg>');
            background-size: 50px 50px;
        }

        .cta-content {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .cta-content h2 {
            font-size: 42px;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
        }

        .cta-content p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
        }

        .btn-cta {
            background: white;
            color: var(--primary) !important;
            padding: 18px 50px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 18px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        /* Footer */
        .footer {
            background: var(--dark-bg);
            border-top: 1px solid var(--border-color);
            padding: 60px 0 30px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo i {
            font-size: 28px;
            color: var(--primary);
        }

        .footer-text {
            color: var(--dark-text-muted);
            line-height: 1.7;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--dark-text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 20px;
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--dark-text-muted);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 36px;
            }

            .hero-subtitle {
                font-size: 18px;
            }

            .section-title h2 {
                font-size: 32px;
            }

            .pricing-card.featured {
                transform: scale(1);
                margin-bottom: 30px;
            }

            .cta-content h2 {
                font-size: 32px;
            }
        }

        /* Scroll to top button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.4);
        }

        .scroll-top.visible {
            opacity: 1;
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="mdi mdi-star"></i>
                Review Pro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-get-started" href="#pricing">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-shapes">
            <div class="shape shape1"></div>
            <div class="shape shape2"></div>
            <div class="shape shape3"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <h1 class="hero-title">Automate Your Amazon Review Management</h1>
                        <p class="hero-subtitle">
                            Streamline your review campaigns, manage multiple accounts, and boost your Amazon presence with our powerful automation bot.
                        </p>
                        <div class="hero-buttons">
                            <a href="#pricing" class="btn btn-get-started">
                                <i class="mdi mdi-rocket-launch"></i> Start Free Trial
                            </a>
                            <a href="#features" class="btn btn-login">
                                <i class="mdi mdi-play-circle"></i> Watch Demo
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-image">
                        <img src="https://via.placeholder.com/600x400/1e293b/6366f1?text=Dashboard+Preview" alt="Dashboard Preview">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Reviews Managed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                </div>
                <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Powerful Features for Your Success</h2>
                <p>Everything you need to manage and automate your Amazon review campaigns efficiently</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-robot"></i>
                        </div>
                        <h4>Smart Automation</h4>
                        <p>Automate review posting with intelligent scheduling and account rotation for maximum efficiency.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-account-multiple"></i>
                        </div>
                        <h4>Multi-Account Management</h4>
                        <p>Manage unlimited Amazon accounts from a single dashboard with role-based access control.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-chart-line"></i>
                        </div>
                        <h4>Real-Time Analytics</h4>
                        <p>Track campaign performance with detailed analytics and insights in real-time.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-shield-check"></i>
                        </div>
                        <h4>Secure & Reliable</h4>
                        <p>Bank-level encryption and secure data storage to protect your sensitive information.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-folder-multiple"></i>
                        </div>
                        <h4>Project Management</h4>
                        <p>Organize campaigns into projects with custom workflows and team collaboration tools.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="mdi mdi-clock-fast"></i>
                        </div>
                        <h4>Time-Saving</h4>
                        <p>Save hours of manual work with automated review submissions and status tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Simple, Transparent Pricing</h2>
                <p>Choose the perfect plan for your business needs</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="pricing-card">
                        <h3>Starter</h3>
                        <div class="pricing-price">
                            $49<span>/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="mdi mdi-check-circle"></i> Up to 10 accounts</li>
                            <li><i class="mdi mdi-check-circle"></i> 100 reviews/month</li>
                            <li><i class="mdi mdi-check-circle"></i> Basic analytics</li>
                            <li><i class="mdi mdi-check-circle"></i> Email support</li>
                            <li><i class="mdi mdi-check-circle"></i> 5 projects</li>
                        </ul>
                        <button class="btn btn-pricing secondary">Choose Plan</button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing-card featured">
                        <div class="pricing-badge">Most Popular</div>
                        <h3>Professional</h3>
                        <div class="pricing-price">
                            $99<span>/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="mdi mdi-check-circle"></i> Up to 50 accounts</li>
                            <li><i class="mdi mdi-check-circle"></i> 500 reviews/month</li>
                            <li><i class="mdi mdi-check-circle"></i> Advanced analytics</li>
                            <li><i class="mdi mdi-check-circle"></i> Priority support</li>
                            <li><i class="mdi mdi-check-circle"></i> Unlimited projects</li>
                            <li><i class="mdi mdi-check-circle"></i> API access</li>
                        </ul>
                        <button class="btn btn-pricing primary">Choose Plan</button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing-card">
                        <h3>Enterprise</h3>
                        <div class="pricing-price">
                            $299<span>/month</span>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="mdi mdi-check-circle"></i> Unlimited accounts</li>
                            <li><i class="mdi mdi-check-circle"></i> Unlimited reviews</li>
                            <li><i class="mdi mdi-check-circle"></i> Custom analytics</li>
                            <li><i class="mdi mdi-check-circle"></i> 24/7 phone support</li>
                            <li><i class="mdi mdi-check-circle"></i> Unlimited projects</li>
                            <li><i class="mdi mdi-check-circle"></i> Dedicated account manager</li>
                        </ul>
                        <button class="btn btn-pricing secondary">Contact Sales</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="fade-up">
                <h2>Ready to Transform Your Review Management?</h2>
                <p>Join hundreds of businesses already using Review Pro to streamline their Amazon campaigns</p>
                <a href="{{ route('login') }}" class="btn btn-cta">
                    <i class="mdi mdi-rocket-launch"></i> Start Your Free Trial
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-logo">
                        <i class="mdi mdi-star"></i>
                        Review Pro
                    </div>
                    <p class="footer-text">
                        The ultimate Amazon review management solution. Automate, manage, and scale your review campaigns with ease.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="mdi mdi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="mdi mdi-twitter"></i></a>
                        <a href="#" class="social-link"><i class="mdi mdi-linkedin"></i></a>
                        <a href="#" class="social-link"><i class="mdi mdi-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Product</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Company</h5>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Support</h5>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Status</a></li>
                        <li><a href="#">Terms</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="footer-title">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 Review Pro. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-top" id="scrollTop">
        <i class="mdi mdi-chevron-up"></i>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
                $('#scrollTop').addClass('visible');
            } else {
                $('.navbar').removeClass('scrolled');
                $('#scrollTop').removeClass('visible');
            }
        });

        // Smooth scroll to top
        $('#scrollTop').click(function() {
            $('html, body').animate({scrollTop: 0}, 600);
        });

        // Smooth scroll for anchor links
        $('a[href^="#"]').click(function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    </script>
</body>
</html>

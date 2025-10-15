<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Primary Meta Tags -->
    <title>Amazon Review Management | Amazon Agent | Mr Infinity - Review Pro</title>
    <meta name="title" content="Amazon Review Management | Amazon Agent | Mr Infinity - Review Pro">
    <meta name="description" content="Professional Amazon Review Management by Mr Infinity. Expert Amazon Agent services for automated review campaigns, multi-account management, and analytics. Trusted by 500+ sellers.">
    <meta name="keywords" content="Amazon Review, Amazon Agent, Mr Infinity, Amazon Review Management, Amazon Review Bot, Amazon Marketing Agent, Review Automation, Amazon Seller Tools, Amazon Review Campaign, Professional Amazon Agent">
    <meta name="author" content="Mr Infinity">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Amazon Review Management | Amazon Agent | Mr Infinity">
    <meta property="og:description" content="Professional Amazon Review Management by Mr Infinity. Expert Amazon Agent services for automated review campaigns and seller success.">
    <meta property="og:image" content="{{ asset('images/landing/hero-banner.png') }}">
    <meta property="og:site_name" content="Review Pro - Mr Infinity">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="Amazon Review Management | Amazon Agent | Mr Infinity">
    <meta name="twitter:description" content="Professional Amazon Review Management by Mr Infinity. Expert Amazon Agent services for automated review campaigns.">
    <meta name="twitter:image" content="{{ asset('images/landing/hero-banner.png') }}">

    <!-- Favicon (will be dynamic) -->
    <link rel="icon" type="image/x-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Review Pro - Amazon Review Management",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web",
        "offers": {
            "@type": "AggregateOffer",
            "lowPrice": "49",
            "highPrice": "299",
            "priceCurrency": "USD"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "ratingCount": "500"
        },
        "description": "Professional Amazon Review Management by Mr Infinity. Expert Amazon Agent services for automated review campaigns.",
        "author": {
            "@type": "Organization",
            "name": "Mr Infinity"
        }
    }
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
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
            padding: 15px;
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

        /* Contact Modal Styles */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 25px 30px;
        }

        .modal-title {
            color: var(--dark-text);
            font-weight: 700;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title i {
            color: var(--primary);
            font-size: 28px;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.6;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-subtitle {
            color: var(--dark-text-muted);
            margin-bottom: 25px;
            font-size: 16px;
        }

        .modal-subtitle strong {
            color: var(--primary);
        }

        .contact-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-option {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: var(--dark-bg);
            border: 2px solid var(--border-color);
            border-radius: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-option::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .contact-option:hover::before {
            transform: scaleY(1);
        }

        .contact-option:hover {
            border-color: var(--primary);
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(99, 102, 241, 0.2);
        }

        .contact-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-option.whatsapp .contact-icon {
            background: linear-gradient(135deg, #25D366, #128C7E);
        }

        .contact-option.telegram .contact-icon {
            background: linear-gradient(135deg, #0088cc, #229ED9);
        }

        .contact-option.email .contact-icon {
            background: linear-gradient(135deg, #EA4335, #D23F31);
        }

        .contact-option.facebook .contact-icon {
            background: linear-gradient(135deg, #1877F2, #4267B2);
        }

        .contact-icon i {
            font-size: 28px;
            color: white;
        }

        .contact-text {
            flex: 1;
        }

        .contact-text h6 {
            color: var(--dark-text);
            font-weight: 700;
            font-size: 16px;
            margin: 0 0 5px 0;
        }

        .contact-text p {
            color: var(--dark-text-muted);
            font-size: 14px;
            margin: 0;
        }

        .contact-option > i:last-child {
            color: var(--dark-text-muted);
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .contact-option:hover > i:last-child {
            color: var(--primary);
            transform: translateX(3px);
        }

        @media (max-width: 768px) {
            .contact-icon {
                width: 50px;
                height: 50px;
            }

            .contact-icon i {
                font-size: 24px;
            }

            .contact-text h6 {
                font-size: 15px;
            }

            .contact-text p {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#" style="display: flex; align-items: center; gap: 12px;">
                @if(isset($settings['logo']) && $settings['logo'])
                    <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['site_title'] ?? 'Review Pro' }}" style="max-height: 40px; width: auto;">
                    <span>{{ $settings['site_title'] ?? 'Review Pro' }}</span>
                @else
                    <i class="mdi mdi-star"></i>
                    <span>{{ $settings['site_title'] ?? 'Review Pro' }}</span>
                @endif
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
                        <a class="btn btn-get-started" href="https://wa.me/{{ isset($settings['whatsapp_number']) ? preg_replace('/\D/', '', $settings['whatsapp_number']) : '' }}?text={{ urlencode('Hi! I would like to know more about Review Pro.') }}" target="_blank">
                            <i class="mdi mdi-whatsapp"></i> Contact Us
                        </a>
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
                            <a href="https://wa.me/{{ isset($settings['whatsapp_number']) ? preg_replace('/\D/', '', $settings['whatsapp_number']) : '' }}?text={{ urlencode('Hi! I would like to get started with Review Pro.') }}" target="_blank" class="btn btn-get-started">
                                <i class="mdi mdi-whatsapp"></i> Contact Us on WhatsApp
                            </a>
                            <a href="#pricing" class="btn btn-login">
                                <i class="mdi mdi-currency-usd"></i> View Pricing
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-image">
                        <img src="{{ asset('images/landing/hero-banner.png') }}"
                             alt="Review Pro Dashboard Preview"
                             onerror="this.src='https://via.placeholder.com/600x400/1e293b/6366f1?text=Dashboard+Preview'">
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
                            <img src="{{ asset('images/dashboard/icons/automation-icon.png') }}" alt="Smart Automation">
                        </div>
                        <h4>Smart Automation</h4>
                        <p>Automate review posting with intelligent scheduling and account rotation for maximum efficiency.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="{{ asset('images/dashboard/icons/multi-account-icon.png') }}" alt="Multi-Account Management">
                        </div>
                        <h4>Multi-Account Management</h4>
                        <p>Manage unlimited Amazon accounts from a single dashboard with role-based access control.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="{{ asset('images/dashboard/icons/analytics-icon.png') }}" alt="Real-Time Analytics">
                        </div>
                        <h4>Real-Time Analytics</h4>
                        <p>Track campaign performance with detailed analytics and insights in real-time.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="{{ asset('images/dashboard/icons/security-icon.png') }}" alt="Secure & Reliable">
                        </div>
                        <h4>Secure & Reliable</h4>
                        <p>Bank-level encryption and secure data storage to protect your sensitive information.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="{{ asset('images/dashboard/icons/projects-icon.png') }}" alt="Project Management">
                        </div>
                        <h4>Project Management</h4>
                        <p>Organize campaigns into projects with custom workflows and team collaboration tools.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <img src="{{ asset('images/dashboard/icons/time-saving-icon.png') }}" alt="Time-Saving">
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
                @forelse($packages as $index => $package)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="pricing-card {{ $package->is_popular ? 'featured' : '' }}">
                            @if($package->is_popular)
                                <div class="pricing-badge">Most Popular</div>
                            @endif
                            <h3>{{ $package->name }}</h3>
                            <div class="pricing-price">
                                ${{ number_format($package->price, 0) }}<span>/{{ $package->duration }}</span>
                            </div>
                            <ul class="pricing-features">
                                @if(is_array($package->features))
                                    @foreach($package->features as $feature)
                                        <li><i class="mdi mdi-check-circle"></i> {{ $feature }}</li>
                                    @endforeach
                                @endif
                            </ul>
                            <button class="btn btn-pricing {{ $package->is_popular ? 'primary' : 'secondary' }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#contactModal"
                                    data-package="{{ $package->name }}"
                                    data-price="{{ $package->price }}"
                                    data-features="{{ is_array($package->features) ? implode('|', $package->features) : '' }}">
                                {{ $package->is_popular ? 'Choose Plan' : 'Choose Plan' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5" style="color: var(--dark-text-muted);">
                            <i class="mdi mdi-package-variant" style="font-size: 64px; opacity: 0.3;"></i>
                            <p class="mt-3">No packages available at the moment. Please check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Access Key Section -->
    <section class="access-key-section" style="padding: 100px 0; background: var(--dark-bg); border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8" data-aos="fade-up">
                    <div style="background: var(--dark-card); border: 2px solid var(--border-color); border-radius: 24px; padding: 50px 40px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary), var(--secondary));"></div>

                        <div style="text-align: center; margin-bottom: 40px;">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);">
                                <i class="mdi mdi-key-variant" style="font-size: 40px; color: white;"></i>
                            </div>
                            <h2 style="font-size: 32px; font-weight: 800; color: var(--dark-text); margin-bottom: 15px;">
                                Already a Customer?
                            </h2>
                            <p style="color: var(--dark-text-muted); font-size: 16px; margin: 0;">
                                Enter your access key to view your project status
                            </p>
                        </div>

                        <!-- Error/Success Messages -->
                        @if(session('error'))
                            <div style="background: rgba(239, 68, 68, 0.15); border: 2px solid var(--danger); border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; animation: shake 0.5s;">
                                <i class="mdi mdi-alert-circle" style="font-size: 24px; color: var(--danger);"></i>
                                <div style="flex: 1;">
                                    <strong style="color: var(--danger); font-weight: 700; display: block; margin-bottom: 4px;">Invalid Access Key</strong>
                                    <span style="color: var(--danger); font-size: 14px;">{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div style="background: rgba(16, 185, 129, 0.15); border: 2px solid var(--success); border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                                <i class="mdi mdi-check-circle" style="font-size: 24px; color: var(--success);"></i>
                                <div style="flex: 1;">
                                    <strong style="color: var(--success); font-weight: 700; display: block; margin-bottom: 4px;">Success!</strong>
                                    <span style="color: var(--success); font-size: 14px;">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('user.verify') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label style="color: var(--dark-text); font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                                    <i class="mdi mdi-key" style="color: var(--primary); font-size: 20px;"></i>
                                    Access Key
                                </label>
                                <input
                                    type="text"
                                    name="access_key"
                                    placeholder="XXXX-XXXX-XXXX"
                                    required
                                    style="width: 100%; padding: 16px 20px; background: var(--dark-bg); border: 2px solid var(--border-color); border-radius: 12px; color: var(--dark-text); font-size: 18px; font-family: monospace; text-transform: uppercase; letter-spacing: 2px; text-align: center; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                                    onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'"
                                />
                                <small style="display: block; margin-top: 10px; color: var(--dark-text-muted); text-align: center;">
                                    <i class="mdi mdi-information-outline"></i> Enter the 12-character key provided after purchase
                                </small>
                            </div>

                            <button type="submit" style="width: 100%; padding: 18px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; border-radius: 12px; color: white; font-size: 18px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3); display: flex; align-items: center; justify-content: center; gap: 10px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 40px rgba(99, 102, 241, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(99, 102, 241, 0.3)'">
                                <i class="mdi mdi-login"></i>
                                <span>Access Dashboard</span>
                            </button>
                        </form>

                        <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--border-color);">
                            <p style="color: var(--dark-text-muted); font-size: 14px; margin: 0;">
                                Don't have an access key? <a href="#pricing" style="color: var(--primary); font-weight: 600; text-decoration: none;">Purchase a package</a>
                            </p>
                        </div>
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
                <a href="https://wa.me/{{ isset($settings['whatsapp_number']) ? preg_replace('/\D/', '', $settings['whatsapp_number']) : '' }}?text={{ urlencode('Hi! I\'m ready to transform my review management with Review Pro. Can we discuss the best plan for me?') }}" target="_blank" class="btn btn-cta">
                    <i class="mdi mdi-whatsapp"></i> Contact Us on WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="footer-logo" style="display: flex; align-items: center; gap: 12px;">
                        @if(isset($settings['logo']) && $settings['logo'])
                            <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['site_title'] ?? 'Review Pro' }}" style="max-height: 35px; width: auto;">
                            <span>{{ $settings['site_title'] ?? 'Review Pro' }}</span>
                        @else
                            <i class="mdi mdi-star"></i>
                            <span>{{ $settings['site_title'] ?? 'Review Pro' }}</span>
                        @endif
                    </div>
                    <p class="footer-text">
                        The ultimate Amazon review management solution. Automate, manage, and scale your review campaigns with ease.
                    </p>
                    <div class="social-links">
                        <a href="{{ $settings['facebook_url'] ?? '#' }}" class="social-link" target="_blank"><i class="mdi mdi-facebook"></i></a>
                        <a href="https://wa.me/{{ isset($settings['whatsapp_number']) ? preg_replace('/\D/', '', $settings['whatsapp_number']) : '' }}" class="social-link" target="_blank"><i class="mdi mdi-whatsapp"></i></a>
                        <a href="https://t.me/{{ isset($settings['telegram_username']) ? str_replace('@', '', $settings['telegram_username']) : '' }}" class="social-link" target="_blank"><i class="mdi mdi-telegram"></i></a>
                        <a href="mailto:{{ $settings['contact_email'] ?? '#' }}" class="social-link"><i class="mdi mdi-email"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="{{ route('page.about') }}">About Us</a></li>
                        <li><a href="https://wa.me/{{ isset($settings['whatsapp_number']) ? preg_replace('/\D/', '', $settings['whatsapp_number']) : '' }}" target="_blank">Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="footer-title">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('page.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('page.tos') }}">Terms of Service</a></li>
                        <li><a href="{{ route('page.cookies') }}">Cookie Policy</a></li>
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

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="mdi mdi-account-heart"></i>
                        <span id="modalPackageName">Contact Us</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-subtitle">Choose your preferred contact method to get started with <strong id="modalPackageNameInline">this package</strong>:</p>

                    <div class="contact-options">
                        <a href="#" id="whatsappLink" class="contact-option whatsapp">
                            <div class="contact-icon">
                                <i class="mdi mdi-whatsapp"></i>
                            </div>
                            <div class="contact-text">
                                <h6>WhatsApp</h6>
                                <p>Chat with us instantly</p>
                            </div>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>

                        <a href="#" id="telegramLink" class="contact-option telegram">
                            <div class="contact-icon">
                                <i class="mdi mdi-telegram"></i>
                            </div>
                            <div class="contact-text">
                                <h6>Telegram</h6>
                                <p>Message us on Telegram</p>
                            </div>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>

                        <a href="#" id="emailLink" class="contact-option email">
                            <div class="contact-icon">
                                <i class="mdi mdi-email"></i>
                            </div>
                            <div class="contact-text">
                                <h6>Email</h6>
                                <p>Send us an email</p>
                            </div>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>

                        <a href="#" id="facebookLink" class="contact-option facebook">
                            <div class="contact-icon">
                                <i class="mdi mdi-facebook"></i>
                            </div>
                            <div class="contact-text">
                                <h6>Facebook</h6>
                                <p>Connect on Facebook</p>
                            </div>
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

        // Contact Modal Handler
        const contactModal = document.getElementById('contactModal');

        // Contact information from backend
        const contactInfo = {
            whatsapp: '{{ $settings["whatsapp_number"] ?? "" }}',
            telegram: '{{ $settings["telegram_username"] ?? "" }}',
            email: '{{ $settings["contact_email"] ?? "" }}',
            facebook: '{{ $settings["facebook_url"] ?? "" }}'
        };

        contactModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const button = event.relatedTarget;

            // Extract info from data-* attributes
            const packageName = button.getAttribute('data-package');
            const packagePrice = button.getAttribute('data-price');
            const packageFeatures = button.getAttribute('data-features');

            // Update modal title and subtitle
            document.getElementById('modalPackageName').textContent = `Get ${packageName} Plan`;
            document.getElementById('modalPackageNameInline').textContent = `${packageName} Plan ($${packagePrice}/month)`;

            // Build simple message with package name and price
            let message = `Hi! I want to enroll in your ${packageName} package with $${packagePrice} price.`;

            const encodedMessage = encodeURIComponent(message);

            // Update WhatsApp link
            if (contactInfo.whatsapp) {
                const whatsappLink = `https://wa.me/${contactInfo.whatsapp.replace(/\D/g, '')}?text=${encodedMessage}`;
                document.getElementById('whatsappLink').href = whatsappLink;
            }

            // Update Telegram link
            if (contactInfo.telegram) {
                const telegramUsername = contactInfo.telegram.replace('@', '');
                const telegramLink = `https://t.me/${telegramUsername}?text=${encodedMessage}`;
                document.getElementById('telegramLink').href = telegramLink;
            }

            // Update Email link
            if (contactInfo.email) {
                const emailSubject = `Order Request: ${packageName} Plan`;
                const emailLink = `mailto:${contactInfo.email}?subject=${encodeURIComponent(emailSubject)}&body=${encodedMessage}`;
                document.getElementById('emailLink').href = emailLink;
            }

            // Update Facebook link (Facebook doesn't support pre-filled messages via URL)
            if (contactInfo.facebook) {
                document.getElementById('facebookLink').href = contactInfo.facebook;
            }
        });
    </script>
</body>
</html>

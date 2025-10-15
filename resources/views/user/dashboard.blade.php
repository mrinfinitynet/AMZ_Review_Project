<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Projects - {{ $settings['site_title'] ?? 'Review Pro' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

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
            background: var(--dark-bg);
            color: var(--dark-text);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: rgba(30, 41, 59, 0.98) !important;
            backdrop-filter: blur(20px);
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-text) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 26px;
            color: var(--primary);
        }

        .access-key-badge {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-family: monospace;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 15px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .access-key-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .access-key-badge i {
            font-size: 16px;
        }

        .btn-logout {
            background: var(--danger);
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
            color: white;
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 60px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="1" fill="white" fill-opacity="0.1"/></svg>');
            background-size: 50px 50px;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
        }

        .page-header h1 {
            color: white;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
        }

        /* Stats Inline */
        .stats-inline {
            display: flex;
            gap: 30px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .stat-item i {
            font-size: 28px;
            color: white;
        }

        .stat-text {
            text-align: left;
        }

        .stat-text .number {
            font-size: 24px;
            font-weight: 800;
            color: white;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-text .label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Main Content */
        .main-content {
            padding: 40px 0 80px;
        }

        /* Card */
        .card {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }

        .card-body {
            padding: 25px;
        }

        /* Search Section */
        .search-section {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .search-input-wrapper {
            position: relative;
            max-width: 500px;
        }

        .search-input-wrapper input {
            width: 100%;
            padding: 14px 50px 14px 45px;
            background: var(--dark-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            color: var(--dark-text);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-input-wrapper input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-input-wrapper .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--primary);
        }

        .search-input-wrapper .clear-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--dark-text-muted);
            cursor: pointer;
            display: none;
            transition: all 0.3s ease;
        }

        .search-input-wrapper .clear-icon:hover {
            color: var(--danger);
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            background: transparent;
            border: none;
            color: var(--dark-text-muted);
            padding: 15px 25px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            background: transparent;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .nav-tabs .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Project Card */
        .project-card-item {
            transition: all 0.3s ease;
        }

        /* Book Cover */
        .book-cover-container {
            width: 80px;
            height: 100px;
            background: var(--dark-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .book-cover-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
        }

        .book-cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        /* Project Title */
        .project-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 6px;
        }

        .project-title a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .project-title a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .project-subtitle {
            font-size: 13px;
            color: var(--dark-text-muted);
            font-family: monospace;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-box {
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box h4 {
            margin: 0 0 4px 0;
            font-size: 20px;
            font-weight: 800;
        }

        .stat-box small {
            font-size: 11px;
            color: var(--dark-text-muted);
        }

        /* Progress Bar */
        .progress-section {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            background: rgba(99, 102, 241, 0.1);
        }

        .progress-section .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-section .progress-label {
            font-size: 13px;
            font-weight: 700;
            color: white;
        }

        .progress-section .progress-percentage {
            padding: 4px 10px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-size: 11px;
            font-weight: 700;
        }

        .progress {
            height: 6px;
            background: var(--dark-bg);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--success);
            transition: width 0.6s ease;
        }

        /* Reviews Container - Hidden by default */
        .reviews-container {
            display: none;
            margin-top: 15px;
        }

        .reviews-container.show {
            display: block;
        }

        /* Review Item */
        .review-item {
            background: var(--dark-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
        }

        .review-item:last-child {
            margin-bottom: 0;
        }

        /* Expand Button */
        .btn-expand-reviews {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-expand-reviews:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .btn-expand-reviews i {
            transition: transform 0.3s ease;
        }

        .btn-expand-reviews.expanded i {
            transform: rotate(180deg);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .review-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--dark-text);
        }

        .review-description {
            font-size: 13px;
            color: var(--dark-text-muted);
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .review-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 13px;
        }

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.2);
            color: var(--info);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        /* Stars */
        .stars {
            color: #f59e0b;
            font-size: 14px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
        }

        .empty-state i {
            font-size: 64px;
            color: var(--dark-text-muted);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: var(--dark-text);
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--dark-text-muted);
            font-size: 15px;
        }

        /* Footer */
        footer {
            background: var(--dark-card);
            border-top: 1px solid var(--border-color);
            padding: 30px 0;
            margin-top: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 24px;
            }

            .stats-inline {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Tooltip */
        .tooltip-copy {
            position: relative;
        }

        .tooltip-copy .tooltiptext {
            visibility: hidden;
            background-color: var(--success);
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 5px 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            white-space: nowrap;
        }

        .tooltip-copy .tooltiptext.show {
            visibility: visible;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/">
                @if(isset($settings['logo']) && $settings['logo'])
                    <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['site_title'] ?? 'Review Pro' }}" style="max-height: 30px; width: auto;">
                @else
                    <i class="mdi mdi-star"></i>
                    <span>{{ $settings['site_title'] ?? 'Review Pro' }}</span>
                @endif
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="access-key-badge tooltip-copy" onclick="copyAccessKey()">
                    <i class="mdi mdi-key-variant"></i>
                    <span id="accessKeyText">{{ $client->key }}</span>
                    <span class="tooltiptext" id="copyTooltip">Copied!</span>
                </div>
                <a href="{{ route('user.logout') }}" class="btn-logout">
                    <i class="mdi mdi-logout"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1><i class="mdi mdi-account-circle"></i> Welcome, {{ $client->name }}!</h1>
                <p>Track and manage your Amazon review projects in real-time</p>

                <div class="stats-inline">
                    <div class="stat-item">
                        <i class="mdi mdi-folder-multiple"></i>
                        <div class="stat-text">
                            <div class="number">{{ $stats['total_projects'] }}</div>
                            <div class="label">Total</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="mdi mdi-clock-outline"></i>
                        <div class="stat-text">
                            <div class="number">{{ $stats['total_pending'] }}</div>
                            <div class="label">Pending</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="mdi mdi-check-circle"></i>
                        <div class="stat-text">
                            <div class="number">{{ $stats['total_completed'] }}</div>
                            <div class="label">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Search Section -->
            <div class="search-section">
                <div class="search-input-wrapper">
                    <i class="mdi mdi-magnify search-icon"></i>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search by book title, ASIN, or project ID..."
                        autocomplete="off"
                    />
                    <i class="mdi mdi-close-circle clear-icon" id="clearSearch"></i>
                </div>
                <div id="searchResults" style="margin-top: 12px; color: var(--dark-text-muted); font-size: 14px;"></div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        <i class="mdi mdi-clock-outline"></i> Pending
                        <span class="badge" style="background: var(--warning);">{{ $stats['total_pending'] }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                        <i class="mdi mdi-check-circle"></i> Completed
                        <span class="badge" style="background: var(--success);">{{ $stats['total_completed'] }}</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div class="row" id="pendingProjectsContainer">
                        @forelse($pendingProjects as $project)
                            @php
                                $bookAsin = $project['book_asin'] ?? 'N/A';
                                $reviewLink = $project['review_link'] ?? '#';
                                $reviews = $project['reviews'];

                                // Count review statuses
                                $approvedCount = $reviews->where('status', 'approved')->count();
                                $rejectedCount = $reviews->where('status', 'rejected')->count();
                                $pendingCount = $reviews->where('status', 'pending')->count();
                                $totalReviews = $reviews->count();
                                $successRate = $totalReviews > 0 ? round(($approvedCount / $totalReviews) * 100, 1) : 0;
                            @endphp
                            <div class="col-12 col-lg-6 mb-4 project-card-item"
                                 data-search="{{ strtolower($project['project_id']) }} {{ strtolower($bookAsin) }} {{ strtolower($reviews->first()->review_title ?? '') }}">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- Header -->
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="book-cover-container" data-asin="{{ $bookAsin }}">
                                                <div class="book-cover-loading text-center">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <img class="book-cover-image" src="" alt="Book Cover">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="project-title">
                                                    @if($bookAsin !== 'N/A')
                                                        <a href="https://www.amazon.com/dp/{{ $bookAsin }}"
                                                           target="_blank"
                                                           class="book-title-text"
                                                           data-asin="{{ $bookAsin }}">
                                                            Loading title...
                                                        </a>
                                                    @else
                                                        <span class="book-title-text" data-asin="{{ $bookAsin }}">
                                                            Project #{{ $project['project_id'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="project-subtitle">ASIN: {{ $bookAsin }}</div>
                                                <div style="font-size: 11px; color: var(--dark-text-muted); margin-top: 4px;">
                                                    Project #{{ $project['project_id'] }} • {{ $reviews->count() }} review(s) • {{ $project['created_at']->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="border-color: var(--border-color); margin: 20px 0;">

                                        <!-- Stats -->
                                        <div class="stats-grid">
                                            <div class="stat-box" style="background: rgba(16, 185, 129, 0.1);">
                                                <h4 style="color: var(--success);">{{ $approvedCount }}</h4>
                                                <small>Approved</small>
                                            </div>
                                            <div class="stat-box" style="background: rgba(239, 68, 68, 0.1);">
                                                <h4 style="color: var(--danger);">{{ $rejectedCount }}</h4>
                                                <small>Rejected</small>
                                            </div>
                                            <div class="stat-box" style="background: rgba(245, 158, 11, 0.1);">
                                                <h4 style="color: var(--warning);">{{ $pendingCount }}</h4>
                                                <small>Pending</small>
                                            </div>
                                        </div>

                                        <!-- Success Rate Progress Bar -->
                                        @if($totalReviews > 0)
                                            <div class="progress-section">
                                                <div class="progress-header">
                                                    <span class="progress-label">Success Rate:</span>
                                                    <span class="progress-percentage">{{ $successRate }}%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ $successRate }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Expand Reviews Button -->
                                        <button class="btn-expand-reviews" onclick="toggleReviews(this)">
                                            <i class="mdi mdi-chevron-down"></i>
                                            <span>View All Reviews ({{ $reviews->count() }})</span>
                                        </button>

                                        <!-- Reviews Container (Hidden by default) -->
                                        <div class="reviews-container">
                                            @foreach($reviews as $index => $review)
                                                <div class="review-item">
                                                    <div class="review-header">
                                                        <div class="review-title">
                                                            <i class="mdi mdi-file-document"></i>
                                                            #{{ $index + 1 }} {{ Str::limit($review->review_title ?? 'Untitled', 40) }}
                                                        </div>
                                                    </div>
                                                    <div class="review-description">
                                                        {{ Str::limit($review->review_description ?? 'No description', 100) }}
                                                    </div>
                                                    <div class="review-footer">
                                                        <span>
                                                            <i class="mdi mdi-account"></i>
                                                            @php
                                                                $account = \App\Models\AmazonReviewAccount::find($review->account_id);
                                                            @endphp
                                                            {{ $account->account_email ?? 'N/A' }}
                                                        </span>
                                                        <span class="stars">
                                                            @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                                                <i class="mdi mdi-star"></i>
                                                            @endfor
                                                        </span>
                                                        <span>
                                                            @if($review->status === 'pending')
                                                                <span class="badge badge-warning">
                                                                    <i class="mdi mdi-clock-outline"></i> Pending
                                                                </span>
                                                            @elseif($review->status === 'approved')
                                                                <span class="badge badge-success">
                                                                    <i class="mdi mdi-check-circle"></i> Approved
                                                                </span>
                                                            @elseif($review->status === 'rejected')
                                                                <span class="badge badge-danger">
                                                                    <i class="mdi mdi-close-circle"></i> Rejected
                                                                </span>
                                                            @else
                                                                <span class="badge badge-info">{{ ucfirst($review->status) }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($reviewLink !== '#')
                                                <a href="{{ $reviewLink }}" target="_blank" class="btn btn-primary w-100 mt-3">
                                                    <i class="mdi mdi-open-in-new"></i> View Product on Amazon
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="mdi mdi-folder-open-outline"></i>
                                    <h4>No Pending Projects</h4>
                                    <p>All your projects have been completed!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div id="noPendingResults" class="empty-state" style="display: none;">
                        <i class="mdi mdi-magnify"></i>
                        <h4>No Results Found</h4>
                        <p>Try searching with different keywords</p>
                    </div>
                </div>

                <!-- Completed Tab -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <div class="row" id="completedProjectsContainer">
                        @forelse($completedProjects as $project)
                            @php
                                $bookAsin = $project['book_asin'] ?? 'N/A';
                                $reviewLink = $project['review_link'] ?? '#';
                                $reviews = $project['reviews'];

                                // Count review statuses
                                $approvedCount = $reviews->where('status', 'approved')->count();
                                $rejectedCount = $reviews->where('status', 'rejected')->count();
                                $deletedCount = $reviews->where('status', 'delete')->count();
                                $totalReviews = $reviews->count();
                                $successRate = $totalReviews > 0 ? round(($approvedCount / $totalReviews) * 100, 1) : 0;
                            @endphp
                            <div class="col-12 col-lg-6 mb-4 project-card-item"
                                 data-search="{{ strtolower($project['project_id']) }} {{ strtolower($bookAsin) }} {{ strtolower($reviews->first()->review_title ?? '') }}">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <!-- Header -->
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="book-cover-container" data-asin="{{ $bookAsin }}">
                                                <div class="book-cover-loading text-center">
                                                    <div class="spinner-border spinner-border-sm text-success" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <img class="book-cover-image" src="" alt="Book Cover">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="project-title">
                                                    @if($bookAsin !== 'N/A')
                                                        <a href="https://www.amazon.com/dp/{{ $bookAsin }}"
                                                           target="_blank"
                                                           class="book-title-text"
                                                           data-asin="{{ $bookAsin }}"
                                                           style="color: var(--success);">
                                                            Loading title...
                                                        </a>
                                                    @else
                                                        <span class="book-title-text" data-asin="{{ $bookAsin }}" style="color: var(--success);">
                                                            Project #{{ $project['project_id'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="project-subtitle">ASIN: {{ $bookAsin }}</div>
                                                <div style="font-size: 11px; color: var(--dark-text-muted); margin-top: 4px;">
                                                    Project #{{ $project['project_id'] }} • {{ $reviews->count() }} review(s) • Completed {{ $project['updated_at']->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="border-color: var(--border-color); margin: 20px 0;">

                                        <!-- Stats -->
                                        <div class="stats-grid">
                                            <div class="stat-box" style="background: rgba(16, 185, 129, 0.1);">
                                                <h4 style="color: var(--success);">{{ $approvedCount }}</h4>
                                                <small>Approved</small>
                                            </div>
                                            <div class="stat-box" style="background: rgba(239, 68, 68, 0.1);">
                                                <h4 style="color: var(--danger);">{{ $rejectedCount }}</h4>
                                                <small>Rejected</small>
                                            </div>
                                            <div class="stat-box" style="background: rgba(148, 163, 184, 0.1);">
                                                <h4 class="text-muted">{{ $deletedCount }}</h4>
                                                <small>Deleted</small>
                                            </div>
                                        </div>

                                        <!-- Success Rate Progress Bar -->
                                        @if($totalReviews > 0)
                                            <div class="progress-section">
                                                <div class="progress-header">
                                                    <span class="progress-label">Success Rate:</span>
                                                    <span class="progress-percentage">{{ $successRate }}%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ $successRate }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Expand Reviews Button -->
                                        <button class="btn-expand-reviews" onclick="toggleReviews(this)">
                                            <i class="mdi mdi-chevron-down"></i>
                                            <span>View All Reviews ({{ $reviews->count() }})</span>
                                        </button>

                                        <!-- Reviews Container (Hidden by default) -->
                                        <div class="reviews-container">
                                            @foreach($reviews as $index => $review)
                                                <div class="review-item">
                                                    <div class="review-header">
                                                        <div class="review-title">
                                                            <i class="mdi mdi-file-document"></i>
                                                            #{{ $index + 1 }} {{ Str::limit($review->review_title ?? 'Untitled', 40) }}
                                                        </div>
                                                    </div>
                                                    <div class="review-description">
                                                        {{ Str::limit($review->review_description ?? 'No description', 100) }}
                                                    </div>
                                                    <div class="review-footer">
                                                        <span>
                                                            <i class="mdi mdi-account"></i>
                                                            @php
                                                                $account = \App\Models\AmazonReviewAccount::find($review->account_id);
                                                            @endphp
                                                            {{ $account->account_email ?? 'N/A' }}
                                                        </span>
                                                        <span class="stars">
                                                            @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                                                <i class="mdi mdi-star"></i>
                                                            @endfor
                                                        </span>
                                                        <span>
                                                            @if($review->status === 'approved')
                                                                <span class="badge badge-success">
                                                                    <i class="mdi mdi-check-circle"></i> Approved
                                                                </span>
                                                            @elseif($review->status === 'rejected')
                                                                <span class="badge badge-danger">
                                                                    <i class="mdi mdi-close-circle"></i> Rejected
                                                                </span>
                                                            @else
                                                                <span class="badge badge-info">{{ ucfirst($review->status) }}</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($reviewLink !== '#')
                                                <a href="{{ $reviewLink }}" target="_blank" class="btn btn-primary w-100 mt-3">
                                                    <i class="mdi mdi-open-in-new"></i> View Product on Amazon
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="mdi mdi-check-circle-outline"></i>
                                    <h4>No Completed Projects Yet</h4>
                                    <p>Your completed projects will appear here</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div id="noCompletedResults" class="empty-state" style="display: none;">
                        <i class="mdi mdi-magnify"></i>
                        <h4>No Results Found</h4>
                        <p>Try searching with different keywords</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div style="text-align: center; color: var(--dark-text-muted); font-size: 14px;">
                <p style="margin: 0;">&copy; 2025 {{ $settings['site_title'] ?? 'Review Pro' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Copy access key to clipboard
        function copyAccessKey() {
            const keyText = document.getElementById('accessKeyText').textContent;
            navigator.clipboard.writeText(keyText).then(function() {
                const tooltip = document.getElementById('copyTooltip');
                tooltip.classList.add('show');
                setTimeout(() => {
                    tooltip.classList.remove('show');
                }, 2000);
            });
        }

        // Load all book covers on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllBookCovers();
        });

        function loadAllBookCovers() {
            const bookContainers = document.querySelectorAll('.book-cover-container');

            bookContainers.forEach(container => {
                const asin = container.dataset.asin;
                if (asin && asin !== 'N/A') {
                    loadBookCover(asin, container);
                }
            });
        }

        function loadBookCover(asin, container, forceRefresh = false) {
            const loading = container.querySelector('.book-cover-loading');
            const img = container.querySelector('.book-cover-image');

            // Show retry indicator if this is a retry
            if (forceRefresh) {
                loading.innerHTML = '<div class="spinner-border spinner-border-sm text-warning" role="status"></div>';
                loading.style.display = 'flex';
            }

            fetch('{{ route("book.fetchData") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    asin: asin,
                    force_refresh: forceRefresh
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.image) {
                    img.src = data.image;
                    img.style.display = 'block';
                    loading.style.display = 'none';
                } else if (data.should_retry && !forceRefresh) {
                    // Retry with force refresh if image/title missing
                    console.log('Retrying fetch for ASIN:', asin);
                    setTimeout(() => {
                        loadBookCover(asin, container, true);
                    }, 1000);
                    return;
                } else if (!data.success) {
                    // Show error with reload button
                    showReloadButton(asin, container, data.error);
                } else {
                    // Show placeholder
                    loading.innerHTML = '<i class="mdi mdi-book" style="font-size: 32px; color: var(--dark-text-muted);"></i>';
                }

                // Update title if available
                if (data.success && data.title) {
                    const titleElement = document.querySelector(`.book-title-text[data-asin="${asin}"]`);
                    if (titleElement) {
                        titleElement.textContent = data.title.substring(0, 50) + (data.title.length > 50 ? '...' : '');
                    }
                } else if (data.success) {
                    const titleElement = document.querySelector(`.book-title-text[data-asin="${asin}"]`);
                    if (titleElement) {
                        titleElement.textContent = 'ASIN: ' + asin;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading book cover:', error);
                showReloadButton(asin, container, 'Network error');
            });
        }

        function showReloadButton(asin, container, errorMessage) {
            const loading = container.querySelector('.book-cover-loading');
            loading.innerHTML = `
                <button class="btn btn-sm btn-outline-primary"
                        onclick="reloadBook('${asin}', this.closest('.book-cover-container'))"
                        style="font-size: 10px; padding: 4px 8px;"
                        title="${errorMessage || 'Failed to load'}">
                    <i class="mdi mdi-refresh"></i> Reload
                </button>
            `;
            loading.style.paddingTop = '35px';

            // Also update title to show error
            const titleElement = document.querySelector(`.book-title-text[data-asin="${asin}"]`);
            if (titleElement) {
                titleElement.innerHTML = '<span style="color: var(--danger);">Failed to load - Click reload</span>';
            }
        }

        function reloadBook(asin, container) {
            // Clear and reload
            loadBookCover(asin, container, true);
        }

        // Toggle reviews visibility
        function toggleReviews(button) {
            const container = button.nextElementSibling;
            const icon = button.querySelector('i');
            const span = button.querySelector('span');

            if (container.classList.contains('show')) {
                container.classList.remove('show');
                button.classList.remove('expanded');
                span.textContent = `View All Reviews (${container.querySelectorAll('.review-item').length})`;
            } else {
                container.classList.add('show');
                button.classList.add('expanded');
                span.textContent = 'Hide Reviews';
            }
        }

        $(document).ready(function() {
            // Search functionality
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();

                if (searchTerm.length > 0) {
                    $('#clearSearch').show();
                } else {
                    $('#clearSearch').hide();
                }

                filterProjects(searchTerm);
            });

            // Clear search
            $('#clearSearch').click(function() {
                $('#searchInput').val('');
                $(this).hide();
                filterProjects('');
            });

            // Filter projects function
            function filterProjects(searchTerm) {
                const activeTab = $('.nav-link.active').attr('id');
                const isPending = activeTab === 'pending-tab';
                const $container = isPending ? $('#pendingProjectsContainer') : $('#completedProjectsContainer');
                const $noResults = isPending ? $('#noPendingResults') : $('#noCompletedResults');

                let visibleCount = 0;

                $container.find('.project-card-item').each(function() {
                    const $card = $(this);
                    const searchText = $card.data('search');

                    if (searchTerm === '' || searchText.includes(searchTerm)) {
                        $card.show();
                        visibleCount++;
                    } else {
                        $card.hide();
                    }
                });

                // Show/hide no results
                if (visibleCount === 0 && searchTerm !== '') {
                    $container.hide();
                    $noResults.show();
                    $('#searchResults').text(`No projects found for "${$('#searchInput').val()}"`);
                } else if (searchTerm !== '') {
                    $container.show();
                    $noResults.hide();
                    $('#searchResults').text(`Found ${visibleCount} project(s) matching "${$('#searchInput').val()}"`);
                } else {
                    $container.show();
                    $noResults.hide();
                    $('#searchResults').text('');
                }
            }

            // Reset search when switching tabs
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                $('#searchInput').val('');
                $('#clearSearch').hide();
                $('#searchResults').text('');
                filterProjects('');
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $settings['site_title'] ?? 'Review Pro' }} - Admin Dashboard</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset($settings['favicon'] ?? 'favicon.ico') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
            --dark-sidebar: #1e293b;
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
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--dark-sidebar);
            border-right: 1px solid var(--border-color);
            padding: 20px 0;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-logo {
            padding: 0 20px 30px;
            text-align: center;
        }

        .sidebar-logo h4 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }

        .user-profile {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 5px;
        }

        .user-email {
            font-size: 13px;
            color: var(--dark-text-muted);
        }

        .nav-menu {
            list-style: none;
            padding: 0 15px;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--dark-text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link:hover {
            background: var(--dark-card-hover);
            color: var(--dark-text);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link i {
            font-size: 20px;
            margin-right: 12px;
        }

        .nav-category {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--dark-text-muted);
            padding: 20px 15px 10px;
            letter-spacing: 1px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 30px;
        }

        /* Top Header */
        .top-header {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px 30px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-text);
        }

        .page-subtitle {
            color: var(--dark-text-muted);
            font-size: 14px;
            margin-top: 5px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--dark-text);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            background: var(--dark-card-hover);
        }

        /* Cards */
        .card {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .card-body {
            padding: 25px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 20px;
        }

        /* Stats Cards */
        .stat-card {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--dark-text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .stat-change {
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Button Styles */
        .btn-primary {
            background: var(--primary);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Table Styles */
        .table {
            color: var(--dark-text);
            background: transparent;
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(99, 102, 241, 0.1);
        }

        .table thead th {
            border-bottom: 2px solid var(--border-color);
            border-top: none;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1.2px;
            padding: 18px 15px;
            background: transparent;
        }

        .table tbody {
            background: transparent;
        }

        .table tbody td {
            border-bottom: 1px solid var(--border-color);
            border-top: none;
            padding: 18px 15px;
            vertical-align: middle;
            color: var(--dark-text);
            font-size: 14px;
        }

        .table tbody tr {
            background: transparent;
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background: var(--dark-card-hover);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Table inside card */
        .card .table-responsive {
            background: transparent;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: rgba(255, 255, 255, 0.02);
        }

        .table-bordered {
            border: 1px solid var(--border-color);
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid var(--border-color);
        }

        /* Table Text Colors */
        .table .text-muted {
            color: var(--dark-text-muted) !important;
        }

        .table .text-black,
        .table .text-dark {
            color: var(--dark-text) !important;
        }

        .table .text-warning {
            color: var(--warning) !important;
        }

        .table strong {
            color: var(--dark-text);
            font-weight: 600;
        }

        /* Table Actions */
        .table .btn {
            font-size: 12px;
            padding: 6px 12px;
        }

        .table .btn-sm {
            padding: 4px 10px;
            font-size: 11px;
        }

        /* Badge Styles */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Fix all white backgrounds */
        .bg-white,
        .bg-light {
            background: var(--dark-card) !important;
            color: var(--dark-text) !important;
        }

        /* Grid Styles */
        .grid {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .grid-body {
            padding: 25px;
            background: var(--dark-card) !important;
            border-radius: 12px;
        }

        .grid-title {
            color: var(--dark-text);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .form-control,
        .form-select,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        textarea,
        select {
            background: var(--dark-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--dark-text) !important;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus,
        input:focus,
        textarea:focus,
        select:focus {
            background: var(--dark-bg) !important;
            border-color: var(--primary) !important;
            color: var(--dark-text) !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25) !important;
            outline: none;
        }

        .form-control::placeholder,
        input::placeholder,
        textarea::placeholder {
            color: var(--dark-text-muted);
        }

        .form-label,
        label {
            color: var(--dark-text);
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* Button Variations */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s;
            border: none;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }

        .btn-outline-success {
            background: transparent;
            border: 1px solid var(--success);
            color: var(--success);
        }

        .btn-outline-success:hover {
            background: var(--success);
            color: white;
        }

        .btn-outline-danger {
            background: transparent;
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        .btn-outline-danger:hover {
            background: var(--danger);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-rounded {
            border-radius: 20px;
        }

        /* Item Wrapper & Demo Wrapper */
        .item-wrapper {
            margin-bottom: 20px;
        }

        .demo-wrapper {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 8px;
        }

        /* Text Colors */
        .text-black {
            color: var(--dark-text) !important;
        }

        .text-muted {
            color: var(--dark-text-muted) !important;
        }

        .text-gray {
            color: var(--dark-text-muted) !important;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-text);
        }

        p {
            color: var(--dark-text);
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
            border-left: 4px solid var(--info);
        }

        /* Modal Styles */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            color: var(--dark-text);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .modal-title {
            color: var(--dark-text);
        }

        .btn-close {
            filter: invert(1);
        }

        /* Pagination */
        .pagination {
            gap: 5px;
        }

        .page-link {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            color: var(--dark-text);
            border-radius: 6px;
            padding: 8px 12px;
        }

        .page-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }

        /* DataTables Override */
        .dataTables_wrapper {
            color: var(--dark-text);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--dark-text);
        }

        div.dataTables_wrapper div.dataTables_filter input {
            background: var(--dark-bg);
            border: 1px solid var(--border-color);
            color: var(--dark-text);
        }

        /* Force override all table backgrounds */
        table.dataTable,
        table.dataTable thead th,
        table.dataTable tbody td,
        table.dataTable tbody tr {
            background: transparent !important;
            background-color: transparent !important;
        }

        table.dataTable tbody tr:hover {
            background: var(--dark-card-hover) !important;
        }

        /* Override Bootstrap table styles */
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        .table > :not(caption) > * > * {
            background-color: transparent !important;
        }

        /* Loading State */
        .loading {
            color: var(--dark-text-muted);
            text-align: center;
            padding: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .mdi-spin {
            animation: spin 1s linear infinite;
        }

        /* Links */
        a {
            color: var(--primary);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-dark);
        }

        /* Fix Bootstrap Default Styles */
        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .col, [class*="col-"] {
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
    @stack('css')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            @if(isset($settings['logo']) && $settings['logo'])
                <img src="{{ asset($settings['logo']) }}" alt="{{ $settings['site_title'] ?? 'Review Pro' }}" style="max-height: 40px; width: auto; margin-bottom: 10px;">
            @else
                <h4><i class="mdi mdi-star"></i> {{ $settings['site_title'] ?? 'Review Pro' }}</h4>
            @endif
        </div>

        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-email">{{ auth()->user()->email }}</div>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard.index') }}" class="nav-link {{ Route::is('admin.dashboard.index') ? 'active' : '' }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    Dashboard
                </a>
            </li>

            @if(auth()->user()->role == 'admin')
                <div class="nav-category">Client Selection</div>

                <li class="nav-item">
                    <div style="padding: 0 15px;">
                        <select id="client-selector" class="form-select" style="font-size: 14px;">
                            <option value="">Select Client...</option>
                            @if(isset($activeClients))
                                @foreach($activeClients as $client)
                                    <option value="{{ $client->code }}" {{ (request()->get('type') == $client->code) ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.clients.index') }}" class="nav-link {{ Route::is('admin.clients.*') ? 'active' : '' }}">
                        <i class="mdi mdi-cog"></i>
                        Manage Clients
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.frontend.index') }}" class="nav-link {{ Route::is('admin.frontend.*') ? 'active' : '' }}">
                        <i class="mdi mdi-web"></i>
                        Frontend
                    </a>
                </li>

                <div class="nav-category">Review Management</div>

                <li class="nav-item">
                    <a href="#" id="nav-accounts" class="nav-link {{ Route::is('admin.review.accounts*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-multiple"></i>
                        Accounts
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" id="nav-projects-pending" class="nav-link {{ Route::is('admin.review.projectsPending') || Route::is('admin.review.projects') ? 'active' : '' }}">
                        <i class="mdi mdi-folder-open"></i>
                        Pending Projects
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" id="nav-projects-archive" class="nav-link {{ Route::is('admin.review.projectsArchive') ? 'active' : '' }}">
                        <i class="mdi mdi-archive"></i>
                        Archive Projects
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" id="nav-submit" class="nav-link {{ Route::is('admin.review.submit') ? 'active' : '' }}">
                        <i class="mdi mdi-send"></i>
                        Start Posting
                    </a>
                </li>
            @endif

            <div class="nav-category">Account</div>

            <li class="nav-item">
                <a href="{{ route('logoutSubmit') }}" class="nav-link">
                    <i class="mdi mdi-logout"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('master')
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

        // Client Selector Navigation
        $(document).ready(function() {
            const $clientSelector = $('#client-selector');

            // Update navigation links when client is selected
            function updateNavLinks() {
                const selectedClient = $clientSelector.val();

                if (selectedClient) {
                    $('#nav-accounts').attr('href', '/admin/review/accounts?type=' + selectedClient);
                    $('#nav-projects-pending').attr('href', '/admin/review/projects-pending?type=' + selectedClient);
                    $('#nav-projects-archive').attr('href', '/admin/review/projects-archive?type=' + selectedClient);
                    $('#nav-submit').attr('href', '/admin/review/submit?type=' + selectedClient);
                } else {
                    $('#nav-accounts').attr('href', '#');
                    $('#nav-projects-pending').attr('href', '#');
                    $('#nav-projects-archive').attr('href', '#');
                    $('#nav-submit').attr('href', '#');
                }
            }

            // Initialize on page load
            updateNavLinks();

            // Update on client selection change
            $clientSelector.on('change', function() {
                updateNavLinks();

                // Auto-navigate to accounts page with selected client
                const selectedClient = $(this).val();
                if (selectedClient) {
                    toastr.info('Switched to ' + $(this).find('option:selected').text());
                }
            });

            // Prevent navigation if no client selected
            $('#nav-accounts, #nav-projects-pending, #nav-projects-archive, #nav-submit').on('click', function(e) {
                if (!$clientSelector.val()) {
                    e.preventDefault();
                    toastr.warning('Please select a client first');
                }
            });
        });
    </script>

    @stack('js')
</body>
</html>

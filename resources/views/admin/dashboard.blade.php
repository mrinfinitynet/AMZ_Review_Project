@extends('admin.partials.master')

@section('master')
<!-- Page Header -->
<div class="top-header">
    <div class="page-title">
        <h2>Dashboard</h2>
        <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.dashboard.index') }}" class="btn-icon" data-bs-toggle="tooltip" title="Refresh">
            <i class="mdi mdi-refresh"></i>
        </a>
    </div>
</div>

<!-- Simple Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                <i class="mdi mdi-account-multiple"></i>
            </div>
            <div class="stat-value">{{ $stats['totalAccounts'] }}</div>
            <div class="stat-label">Accounts</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: var(--secondary);">
                <i class="mdi mdi-briefcase"></i>
            </div>
            <div class="stat-value">{{ $stats['totalProjects'] }}</div>
            <div class="stat-label">Projects</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="mdi mdi-clock-outline"></i>
            </div>
            <div class="stat-value">{{ $stats['pendingReviews'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="mdi mdi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['completedReviews'] }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Quick Actions</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.review.accounts', ['type' => 'Client1']) }}" class="btn btn-primary w-100">
                            <i class="mdi mdi-account-plus"></i> Accounts
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.review.projects', ['type' => 'Client1']) }}" class="btn btn-primary w-100">
                            <i class="mdi mdi-briefcase-plus"></i> Projects
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.review.submit', ['type' => 'Client1']) }}" class="btn btn-primary w-100">
                            <i class="mdi mdi-send"></i> Start Posting
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.dashboard.index') }}" class="btn btn-primary w-100">
                            <i class="mdi mdi-refresh"></i> Refresh
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


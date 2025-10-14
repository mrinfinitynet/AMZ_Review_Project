@extends('admin.partials.master')

@push('css')
    <style>
        /* Force dark theme for all table elements */
        table,
        table thead,
        table thead th,
        table tbody,
        table tbody tr,
        table tbody td {
            background: transparent !important;
            background-color: transparent !important;
            color: var(--dark-text) !important;
        }
    </style>
@endpush

@section('master')
    <!-- Page Header -->
    <div class="top-header">
        <div class="page-title">
            <h2>Pending Projects</h2>
            <p class="page-subtitle">Projects with pending reviews - {{ $type }}</p>
        </div>
    </div>

    <!-- Action Buttons Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-success btn-sm" href="{{ route("admin.review.projectsAdd", ["type" => $type]) }}">
                                <i class="mdi mdi-plus-circle"></i> Add Project
                            </a>

                            <div class="vr" style="height: 30px; background: var(--border-color);"></div>

                            <a class="btn btn-primary btn-sm" href="{{ route("admin.review.projectsPending", ["type" => "My"]) }}">
                                <i class="mdi mdi-folder"></i> My
                            </a>
                            <a class="btn btn-primary btn-sm" href="{{ route("admin.review.projectsPending", ["type" => "Client1"]) }}">
                                <i class="mdi mdi-folder"></i> Client1
                            </a>
                            <a class="btn btn-primary btn-sm" href="{{ route("admin.review.projectsPending", ["type" => "Client2"]) }}">
                                <i class="mdi mdi-folder"></i> Client2
                            </a>
                        </div>

                        <a class="btn btn-info btn-sm" href="{{ route("admin.review.projectsArchive", ["type" => $type]) }}">
                            <i class="mdi mdi-archive"></i> View Archive Projects
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-grow-1" style="max-width: 400px;">
                            <input type="text"
                                   id="asin-search"
                                   class="form-control form-control-sm"
                                   placeholder="Search by ASIN..."
                                   style="background: var(--dark-card); color: var(--dark-text); border: 1px solid var(--border-color);">
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="searchByAsin()">
                            <i class="mdi mdi-magnify"></i> Search
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="clearSearch()">
                            <i class="mdi mdi-close"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Load book covers on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAllBookCovers();
    });

    function loadAllBookCovers() {
        const bookContainers = document.querySelectorAll('.book-cover-container');

        bookContainers.forEach(container => {
            const asin = container.dataset.asin;
            if (asin) {
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
            loading.style.display = 'block';
        }

        fetch('{{ route("admin.review.fetchBookData") }}', {
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
                }, 1000); // Wait 1 second before retry
                return;
            } else if (!data.success) {
                // Show error with reload button
                showReloadButton(asin, container, data.error);
            } else {
                // Show placeholder or default image
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
                    style="font-size: 10px; padding: 2px 6px;"
                    title="${errorMessage || 'Failed to load'}">
                <i class="mdi mdi-refresh"></i>
            </button>
        `;
        loading.style.paddingTop = '35px';

        // Also update title to show error
        const titleElement = document.querySelector(`.book-title-text[data-asin="${asin}"]`);
        if (titleElement) {
            titleElement.innerHTML = '<span class="text-danger">Failed to load - Click reload</span>';
        }
    }

    function reloadBook(asin, container) {
        // Clear the specific cache and reload
        loadBookCover(asin, container, true);
    }

    function searchByAsin() {
        const asin = document.getElementById('asin-search').value.trim().toUpperCase();
        const projectCards = document.querySelectorAll('.project-card');
        let noResultsMessage = document.getElementById('no-results-message');

        if (!asin) {
            clearSearch();
            return;
        }

        let visibleCount = 0;
        projectCards.forEach(card => {
            const cardAsin = card.dataset.asin || '';
            if (cardAsin.includes(asin)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            if (!noResultsMessage) {
                noResultsMessage = document.createElement('div');
                noResultsMessage.id = 'no-results-message';
                noResultsMessage.className = 'col-12';
                noResultsMessage.innerHTML = `
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="mdi mdi-book-search-outline" style="font-size: 64px; color: var(--dark-text-muted);"></i>
                            <h4 class="mt-3">No Book Found</h4>
                            <p class="text-muted">No projects found with ASIN containing "<strong>${asin}</strong>"</p>
                            <button onclick="clearSearch()" class="btn btn-primary mt-3">
                                <i class="mdi mdi-close"></i> Clear Search
                            </button>
                        </div>
                    </div>
                `;
                document.querySelector('.row').appendChild(noResultsMessage);
            }
        } else {
            if (noResultsMessage) {
                noResultsMessage.remove();
            }
        }
    }

    function clearSearch() {
        document.getElementById('asin-search').value = '';
        const projectCards = document.querySelectorAll('.project-card');
        projectCards.forEach(card => {
            card.style.display = 'block';
        });

        const noResultsMessage = document.getElementById('no-results-message');
        if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }

    // Allow search on Enter key
    document.getElementById('asin-search')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchByAsin();
        }
    });
    </script>

    <!-- Projects Grid -->
    <div class="row">
        @forelse ($results as $result)
            <div class="col-12 col-lg-6 mb-4 project-card" data-asin="{{ strtoupper($result['book_asin'] ?? '') }}">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex gap-3 mb-3">
                            @if(isset($result["book_asin"]) && $result["book_asin"])
                                <!-- Book Cover -->
                                <div class="flex-shrink-0">
                                    <div class="book-cover-container" data-asin="{{ $result['book_asin'] }}" style="width: 80px; height: 100px; background: var(--dark-bg); border: 1px solid var(--border-color); border-radius: 4px; overflow: hidden; position: relative;">
                                        <div class="book-cover-loading text-center" style="padding-top: 35px;">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <img class="book-cover-image" src="" alt="Book Cover" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            @endif

                            <!-- Project Info -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="mdi mdi-folder-open text-primary"></i>
                                            @if(isset($result["book_asin"]) && $result["book_asin"])
                                                <a href="https://www.amazon.com/dp/{{ $result['book_asin'] }}"
                                                   target="_blank"
                                                   class="text-decoration-none"
                                                   style="color: inherit;">
                                                    Project #{{ $result['project_id'] ?? 0 }}
                                                </a>
                                            @else
                                                Project #{{ $result['project_id'] ?? 0 }}
                                            @endif
                                        </h5>
                                        @if(isset($result["book_asin"]) && $result["book_asin"])
                                            <small class="text-muted book-title-text" data-asin="{{ $result['book_asin'] }}">
                                                Loading title...
                                            </small>
                                        @endif
                                    </div>
                                    <a href="{{ route("admin.review.stopReview", ["project_id" => $result["project_id"]]) }}"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to stop this project?')">
                                        <i class="mdi mdi-stop-circle"></i> Stop Project
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr style="border-color: var(--border-color);">

                        <!-- Project Stats -->
                        @php
                            $totalReviews = count($result['reviews']);
                            $approvedCount = collect($result['reviews'])->where('status', 'approved')->count();
                            $rejectedCount = collect($result['reviews'])->where('status', 'rejected')->count();
                            $deletedCount = collect($result['reviews'])->where('status', 'delete')->count();
                        @endphp

                        <div class="row mb-3">
                            <div class="col-4 text-center">
                                <div class="p-2 rounded" style="background: rgba(16, 185, 129, 0.1);">
                                    <h4 class="mb-0" style="color: var(--success);">{{ $approvedCount }}</h4>
                                    <small class="text-muted">Approved</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-2 rounded" style="background: rgba(239, 68, 68, 0.1);">
                                    <h4 class="mb-0" style="color: var(--danger);">{{ $rejectedCount }}</h4>
                                    <small class="text-muted">Rejected</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-2 rounded" style="background: rgba(148, 163, 184, 0.1);">
                                    <h4 class="mb-0 text-muted">{{ $deletedCount }}</h4>
                                    <small class="text-muted">Deleted</small>
                                </div>
                            </div>
                        </div>

                        <!-- Success Rate -->
                        @if($totalReviews > 0)
                            @php
                                $successRate = round(($approvedCount / $totalReviews) * 100, 1);
                            @endphp
                            <div class="mb-3 p-2 rounded" style="background: rgba(99, 102, 241, 0.1);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small" style="color: white;"><strong>Success Rate:</strong></span>
                                    <span class="badge" style="background: var(--primary); color: white;">
                                        {{ $successRate }}%
                                    </span>
                                </div>
                                <div class="progress mt-2" style="height: 6px; background: var(--dark-bg);">
                                    <div class="progress-bar" style="background: var(--success); width: {{ $successRate }}%"></div>
                                </div>
                            </div>
                        @endif

                        <hr style="border-color: var(--border-color);">

                        @foreach ($result['reviews'] as $index => $review)
                            <div class="mb-3 p-3 rounded" style="background: var(--dark-bg); border: 1px solid var(--border-color);">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold" style="font-size: 14px; color: var(--dark-text);">
                                            <i class="mdi mdi-file-document"></i>
                                            #{{ $index+1 }} {{ Str::limit($review["review_title"] ?? 'Untitled', 40) }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1 ms-2">
                                        @if ($review["status"] === "pending")
                                            <a href="{{ route("admin.review.startReview", ["review_id" => $review["id"]]) }}"
                                               class="btn btn-sm btn-success"
                                               title="Start Review">
                                                <i class="mdi mdi-play-circle"></i>
                                            </a>
                                        @else
                                            <a href="{{ route("admin.review.startReview", ["review_id" => $review["id"]]) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Retry Review">
                                                <i class="mdi mdi-refresh"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="text-muted mb-2 small" style="font-size: 13px;">
                                    {{ Str::limit($review["review_description"] ?? 'No description', 100) }}
                                </p>

                                <!-- Footer Details -->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="small">
                                        <i class="mdi mdi-account"></i>
                                        <strong>Account:</strong> {{ $review["account_id"] ?? 'N/A' }}
                                    </span>
                                    <span class="small">
                                        <strong>Rating:</strong>
                                        @for($i = 0; $i < $review["rating"]; $i++)
                                            <i class="mdi mdi-star text-warning"></i>
                                        @endfor
                                        @for($i = $review["rating"]; $i < 5; $i++)
                                            <i class="mdi mdi-star-outline text-muted"></i>
                                        @endfor
                                    </span>
                                    <span>
                                        @if($review["status"] === 'pending')
                                            <span class="badge badge-warning">
                                                <i class="mdi mdi-clock-outline"></i> Pending
                                            </span>
                                        @elseif($review["status"] === 'approved')
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check-circle"></i> Approved
                                            </span>
                                        @elseif($review["status"] === 'rejected')
                                            <span class="badge badge-danger">
                                                <i class="mdi mdi-close-circle"></i> Rejected
                                            </span>
                                        @else
                                            <span class="badge badge-info">{{ ucfirst($review["status"]) }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-folder-open-outline" style="font-size: 64px; color: var(--dark-text-muted);"></i>
                        <h4 class="mt-3">No Pending Projects</h4>
                        <p class="text-muted">There are no projects with pending reviews for {{ $type }}</p>
                        <a href="{{ route("admin.review.projectsAdd", ["type" => $type]) }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-plus-circle"></i> Add New Project
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

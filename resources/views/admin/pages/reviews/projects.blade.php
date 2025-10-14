@extends('admin.partials.master')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css" />
@endpush
@section('master')
<div class="row">
    <div class="col-12 mb-4">
        <div class="grid-body bg-light">
            <h2 class="grid-title">Add Projects for {{ $type }}</h2>

            <div class="item-wrapper">
                <div class="demo-wrapper">
                    <a class="btn btn-success btn-sm has-icon" href="{{ route("admin.review.projectsAdd", ["type" => $type]) }}">
                        <i class="mdi mdi-account-plus-outline"></i>Add Project
                    </a>
                    <a class="btn btn-primary btn-sm has-icon" href="{{ route("admin.review.projects", ["type" => "My"]) }}">
                        <i class="mdi mdi-account-plus-outline"></i> My
                    </a>
                    <a class="btn btn-primary btn-sm has-icon" href="{{ route("admin.review.projects", ["type" => "Client1"]) }}">
                        <i class="mdi mdi-account-plus-outline"></i>Client1
                    </a>
                    <a class="btn btn-primary btn-sm has-icon" href="{{ route("admin.review.projects", ["type" => "Client2"]) }}">
                        <i class="mdi mdi-account-plus-outline"></i>Client2
                    </a>
                </div>
            </div>
        </div>
    </div>

    @foreach ($results as $result)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">
                        Project #{{ $result['project_id'] ?? 0 }}

                        <a href="{{ route("admin.review.stopReview", ["project_id" => $result["project_id"]]) }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-play-circle"></i> Stop Post
                        </a>
                    </h5>
                    <hr />

                    @foreach ($result['reviews'] as $index => $review)
                        <div class="mb-4 p-4 bg-white shadow-sm rounded border">
                            <!-- Header with title + action buttons -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <a href="{{$review["review_link"]}}" class="fw-bold mb-0 text-primary text-truncate pe-3" style="max-width: 55%;">
                                    #{{ $index+1 ?? 0 }} {{ $review["review_title"] ?? 'Untitled' }}
                                </a>
                                <div class="btn-group flex-wrap gap-1">
                                    @if ($review["status"] === "pending")
                                        <a href="{{ route("admin.review.startReview", ["review_id" => $review["id"]]) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-play-circle"></i> Start
                                        </a>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    @else
                                        <a href="{{ route("admin.review.startReview", ["review_id" => $review["id"]]) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-play-circle"></i> Start
                                        </a>
                                    @endif
                                    
                                </div>
                            </div>

                            <!-- Review description -->
                            <p class="text-muted mb-3 text-truncate" style="max-height: 3.6em; line-height: 1.8em; overflow: hidden;">
                                {{ $review["review_description"] ?? 'No description available.' }}
                            </p>

                            <!-- Footer details -->
                            <div class="d-flex justify-content-between align-items-center small flex-wrap">
                                <span><strong>Account:</strong> {{ $review["account_id"] ?? 'N/A' }}</span>
                                <span>
                                    <strong>Rating:</strong>
                                    {!! str_repeat('⭐', $review["rating"] ?? 0) . str_repeat('☆', 5 - ($review["rating"] ?? 0)) !!}
                                </span>
                                <span class="badge bg-{{ ($review["status"] ?? '') === 'approved' ? 'success' : 'warning text-light' }}">
                                    {{ ucfirst($review["status"] ?? '') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
    

</div>
@endsection

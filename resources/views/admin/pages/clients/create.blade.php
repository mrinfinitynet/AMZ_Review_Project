@extends('admin.partials.master')

@section('master')
<!-- Page Header -->
<div class="top-header">
    <div class="page-title">
        <h2>Add New Client</h2>
        <p class="page-subtitle">Create a new client for your review management</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Back to Clients
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Client Information</h5>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle"></i> Please fix the following errors:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.clients.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Client Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="e.g., Client One, John's Reviews"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Display name for the client</small>
                    </div>

                    <div class="mb-3">
                        <label for="code" class="form-label">Client Code <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               id="code"
                               name="code"
                               value="{{ old('code') }}"
                               placeholder="e.g., Client1, john_reviews"
                               pattern="[A-Za-z0-9_-]+"
                               required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Unique identifier (letters, numbers, dash, underscore only)</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Optional description about this client">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               id="sort_order"
                               name="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Lower numbers appear first (0 = highest priority)</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <small class="form-text text-muted">Only active clients will appear in the system</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-check"></i> Create Client
                        </button>
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-close"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

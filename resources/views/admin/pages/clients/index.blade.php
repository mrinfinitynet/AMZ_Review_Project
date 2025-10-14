@extends('admin.partials.master')

@section('master')
<!-- Page Header -->
<div class="top-header">
    <div class="page-title">
        <h2>Client Management</h2>
        <p class="page-subtitle">Manage all your review clients dynamically</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus-circle"></i> Add New Client
        </a>
    </div>
</div>

<!-- Clients Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">All Clients</h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 20%;">Name</th>
                                <th style="width: 15%;">Code</th>
                                <th style="width: 30%;">Description</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Sort Order</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td><strong>#{{ $client->id }}</strong></td>
                                    <td>{{ $client->name }}</td>
                                    <td><code>{{ $client->code }}</code></td>
                                    <td>{{ $client->description ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.clients.toggleStatus', $client) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @if($client->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                            <button type="submit" class="btn btn-link btn-sm p-0"
                                                    style="vertical-align: baseline;"
                                                    onclick="return confirm('Toggle client status?')">
                                                <i class="mdi mdi-sync"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $client->sort_order }}</td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.clients.edit', $client) }}"
                                               class="btn btn-outline-primary btn-sm btn-rounded">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.clients.destroy', $client) }}"
                                                  method="POST"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm btn-rounded">
                                                    <i class="mdi mdi-delete"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="mdi mdi-account-off"></i> No clients found. Add your first client to get started!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

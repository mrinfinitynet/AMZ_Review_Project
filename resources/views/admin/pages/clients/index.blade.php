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
                                <th style="width: 15%;">Name</th>
                                <th style="width: 10%;">Code</th>
                                <th style="width: 20%;">Access Key</th>
                                <th style="width: 15%;">Description</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 8%;">Sort</th>
                                <th style="width: 17%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td><strong>#{{ $client->id }}</strong></td>
                                    <td>{{ $client->name }}</td>
                                    <td><code>{{ $client->code }}</code></td>
                                    <td>
                                        @if($client->key)
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <code style="font-family: monospace; color: var(--primary); font-weight: 600;">{{ $client->key }}</code>
                                                <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $client->key }}')" title="Copy Key">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                            <div style="margin-top: 8px; font-size: 12px; color: var(--dark-text-muted);">
                                                @if($client->access_count > 0)
                                                    <i class="mdi mdi-counter"></i> {{ $client->access_count }} accesses
                                                @endif
                                                @if($client->last_accessed_at)
                                                    <br><i class="mdi mdi-clock-outline"></i> Last: {{ $client->last_accessed_at->diffForHumans() }}
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted"><i class="mdi mdi-key-remove"></i> No key generated</span>
                                        @endif
                                    </td>
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
                                        <div class="actions" style="display: flex; flex-direction: column; gap: 5px;">
                                            <div>
                                                <a href="{{ route('admin.clients.edit', $client) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.clients.destroy', $client) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div>
                                                @if($client->key)
                                                    <form action="{{ route('admin.clients.generateKey', $client) }}"
                                                          method="POST"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Regenerate access key? The old key will no longer work!');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                                            <i class="mdi mdi-key-change"></i> Regenerate
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.clients.removeKey', $client) }}"
                                                          method="POST"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Remove access key? Users will not be able to access with this key!');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                            <i class="mdi mdi-key-remove"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.clients.generateKey', $client) }}"
                                                          method="POST"
                                                          style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <i class="mdi mdi-key-plus"></i> Generate Key
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
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

@push('js')
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Access key copied to clipboard!');
    }, function() {
        toastr.error('Failed to copy');
    });
}
</script>
@endpush
@endsection

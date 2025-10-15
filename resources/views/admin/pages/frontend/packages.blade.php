@extends('admin.partials.master')

@section('master')
<div class="top-header">
    <div class="page-title">
        <h2>Package Management</h2>
        <p class="page-subtitle">Create and manage pricing packages for your landing page</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.frontend.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="mdi mdi-arrow-left"></i> Back to Settings
        </a>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">
            <i class="mdi mdi-plus"></i> Add Package
        </button>
    </div>
</div>

<!-- Packages List -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">All Packages</h5>

        @if($packages->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Features</th>
                            <th>Popular</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td><strong>{{ $package->order }}</strong></td>
                                <td><strong>{{ $package->name }}</strong></td>
                                <td><span class="badge badge-success">${{ number_format($package->price, 2) }}</span></td>
                                <td>{{ ucfirst($package->duration) }}</td>
                                <td>
                                    @if(is_array($package->features))
                                        <span class="badge badge-info">{{ count($package->features) }} features</span>
                                    @endif
                                </td>
                                <td>
                                    @if($package->is_popular)
                                        <i class="mdi mdi-star text-warning"></i> Yes
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $package->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $package->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="btn btn-sm btn-info" onclick="editPackage({{ $package->id }})">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.frontend.deletePackage', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this package?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5" style="color: var(--dark-text-muted);">
                <i class="mdi mdi-package-variant" style="font-size: 64px; opacity: 0.3;"></i>
                <p class="mt-3">No packages created yet</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                    <i class="mdi mdi-plus"></i> Create Your First Package
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.frontend.storePackage') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Package Name</label>
                            <input type="text" class="form-control" name="name" placeholder="e.g., Starter Plan" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="order" value="0" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" class="form-control" name="price" placeholder="49.00" step="0.01" min="0" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration</label>
                            <select class="form-select" name="duration" required>
                                <option value="month">Per Month</option>
                                <option value="year">Per Year</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" rows="6" placeholder="Up to 100 reviews per month&#10;5 Amazon accounts&#10;Basic analytics&#10;Email support" required></textarea>
                            <small class="text-muted">Each line will be displayed as a separate feature</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_popular" id="is_popular_add">
                                <label class="form-check-label" for="is_popular_add">
                                    Mark as "Most Popular"
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active_add" checked>
                                <label class="form-check-label" for="is_active_add">
                                    Active (visible on landing page)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Create Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPackageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Package Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="order" id="edit_order" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" class="form-control" name="price" id="edit_price" step="0.01" min="0" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration</label>
                            <select class="form-select" name="duration" id="edit_duration" required>
                                <option value="month">Per Month</option>
                                <option value="year">Per Year</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" id="edit_features" rows="6" required></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_popular" id="edit_is_popular">
                                <label class="form-check-label" for="edit_is_popular">
                                    Mark as "Most Popular"
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" id="edit_is_active">
                                <label class="form-check-label" for="edit_is_active">
                                    Active (visible on landing page)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const packages = @json($packages);

function editPackage(id) {
    const package = packages.find(p => p.id === id);
    if (!package) return;

    // Set form action
    document.getElementById('editPackageForm').action = `/admin/frontend/packages/${id}`;

    // Fill form fields
    document.getElementById('edit_name').value = package.name;
    document.getElementById('edit_price').value = package.price;
    document.getElementById('edit_duration').value = package.duration;
    document.getElementById('edit_order').value = package.order;
    document.getElementById('edit_features').value = Array.isArray(package.features) ? package.features.join('\n') : package.features;
    document.getElementById('edit_is_popular').checked = package.is_popular;
    document.getElementById('edit_is_active').checked = package.is_active;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editPackageModal'));
    modal.show();
}
</script>
@endsection

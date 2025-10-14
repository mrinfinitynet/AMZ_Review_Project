@extends('admin.partials.master')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css" />
    <style>
        /* Force dark theme for all table elements */
        table,
        table thead,
        table thead th,
        table tbody,
        table tbody tr,
        table tbody td,
        .table,
        .table thead,
        .table thead th,
        .table tbody,
        .table tbody tr,
        .table tbody td {
            background: transparent !important;
            background-color: transparent !important;
            color: var(--dark-text) !important;
        }

        /* Override DataTables specific styles */
        .dataTables_wrapper .table,
        .dataTables_wrapper table {
            background: transparent !important;
        }

        .dataTables_wrapper tbody tr {
            background: transparent !important;
        }

        .dataTables_wrapper tbody td {
            background: transparent !important;
            color: var(--dark-text) !important;
        }

        /* Hover state */
        table tbody tr:hover,
        .table tbody tr:hover {
            background: var(--dark-card-hover) !important;
        }
    </style>
@endpush

@section('master')
    <!-- Page Header -->
    <div class="top-header">
        <div class="page-title">
            <h2>Account Management</h2>
            <p class="page-subtitle">Managing accounts for {{ $type }}</p>
        </div>
    </div>

    <!-- Action Buttons Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <a class="btn btn-success btn-sm" href="{{ route("admin.review.accountsAdd", ["type" => $type]) }}">
                            <i class="mdi mdi-account-plus"></i> Add Account
                        </a>

                        <div class="vr" style="height: 30px; background: var(--border-color);"></div>

                        <a class="btn btn-primary btn-sm" href="{{ route("admin.review.accounts", ["type" => "My"]) }}">
                            <i class="mdi mdi-account"></i> My
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route("admin.review.accounts", ["type" => "Client1"]) }}">
                            <i class="mdi mdi-account"></i> Client1
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route("admin.review.accounts", ["type" => "Client2"]) }}">
                            <i class="mdi mdi-account"></i> Client2
                        </a>

                        <div class="vr" style="height: 30px; background: var(--border-color);"></div>

                        @if($lastProject && $addToCart)
                            <button id="startAddToCart" class="btn btn-info btn-sm">
                                <i class="mdi mdi-cart-plus"></i>
                                <span class="startAddToCart">Add To Cart</span>
                                | Next ID: <span id="nextAccount">{{($lastProject->account_id - $addToCart) + 1}}/{{$lastProject->account_id}}</span>
                            </button>
                        @else
                            <button id="startAddToCart" class="btn btn-info btn-sm" disabled>
                                <i class="mdi mdi-cart-plus"></i>
                                <span class="startAddToCart">Add To Cart</span>
                                | Next ID: <span id="nextAccount">N/A</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">All Accounts - {{ $type }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" id="all-review">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th style="width: 30%;">Name</th>
                                    <th style="width: 35%;">Last Checking</th>
                                    <th style="width: 25%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="accounts-table-body">
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="loading">
                                            <i class="mdi mdi-loading mdi-spin"></i> Loading accounts...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(()=>{
        $("#startAddToCart").click(function(){
            if($("#startAddToCart .startAddToCart").text() == "Add To Cart"){
                $("#startAddToCart .startAddToCart").text("Running...");
                $("#startAddToCart").prop('disabled', true);
                runEvent();
            }else{
                $("#startAddToCart .startAddToCart").text("Add To Cart");
                $("#startAddToCart").prop('disabled', false);
            }
        });

        // ADD TO CART
        const runEvent = () => {
            accounts();

            $.ajax({
                url: '/admin/review/accounts-add-cart',
                type: 'GET',
                data: {
                    'type': '{{ $type }}'
                },
                success: function (data) {
                    if ($("#startAddToCart .startAddToCart").text().trim() === "Running...") {
                        if(!data.status){
                            $("#startAddToCart .startAddToCart").text("Add To Cart");
                            $("#startAddToCart").prop('disabled', false);
                            toastr.error(data.msg);
                        }else{
                            runEvent();
                        }
                    }

                    // show ids
                    $("#nextAccount").text(data.count);
                },
                error: function(){
                    runEvent();
                }
            });
        }

        // accounts
        const accounts = () => {
            $("#accounts-table-body").html(`
                <tr>
                    <td colspan="4" class="text-center">
                        <div class="loading">
                            <i class="mdi mdi-loading mdi-spin"></i> Loading accounts...
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                url: '/admin/review/accounts',
                type: 'POST',
                data: {
                    'type': '{{ $type }}'
                },
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                success: function (data) {
                    let tbody = $("#accounts-table-body");
                    tbody.empty();

                    if(data.length === 0) {
                        tbody.html(`
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="mdi mdi-account-off"></i> No accounts found
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    data.forEach((item, index) => {
                        let lastChecking = item.last_checking ?
                            `<span class="badge badge-info">${item.last_checking}</span>` :
                            `<span class="text-muted">Not checked yet</span>`;

                        let row = `
                            <tr>
                                <td><strong>#${item.account_id}</strong></td>
                                <td>${item.account_name}</td>
                                <td>${lastChecking}</td>
                                <td>
                                    <div class="actions">
                                        <a href="/admin/review/accounts-edit/${item.id}?type={{ $type }}" class="btn btn-outline-primary btn-sm btn-rounded">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <a href="/admin/review/check-account/${item.account_id}" class="btn btn-outline-success btn-sm btn-rounded">
                                            <i class="mdi mdi-check-circle"></i> Check
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });

                    toastr.success(`Loaded ${data.length} accounts`);
                },
                error: function() {
                    $("#accounts-table-body").html(`
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                <i class="mdi mdi-alert-circle"></i> Error loading accounts
                            </td>
                        </tr>
                    `);
                    toastr.error('Failed to load accounts');
                }
            });
        }

        // Load accounts on page load
        accounts();
    });
</script>
@endpush

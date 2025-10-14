@extends('admin.partials.master')
@section('master')
<div class="col-12 equel-grid">
    <div class="grid">
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>There were some problems with your input:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="grid-header">Add New Accounts</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="form-group">
                        <label for="inputQuota1">Account Name</label>
                        <input type="text" name="account_name" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Account ID</label>
                        <input type="number" name="account_id" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Account Email</label>
                        <input type="email" name="account_email" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Account Password</label>
                        <input type="text" name="account_password" class="form-control custom-input" />
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Confirmed</button>
                    <a type="submit" class="btn btn-sm btn-danger" href="{{ route("admin.review.accounts", ["type" => $type]) }}">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
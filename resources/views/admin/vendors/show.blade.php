@extends('admin.layouts.app')
@section('admin-title','Show Vendor')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6"><div class="page-header-left"><h3>Vendor Details</h3></div></div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendor</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $vendor->name }}</dd>
                        <dt class="col-sm-3">Company</dt><dd class="col-sm-9">{{ $vendor->company }}</dd>
                        <dt class="col-sm-3">Contact Name</dt><dd class="col-sm-9">{{ $vendor->contact_name }}</dd>
                        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $vendor->email }}</dd>
                        <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $vendor->phone }}</dd>
                        <dt class="col-sm-3">Address Line 1</dt><dd class="col-sm-9">{{ $vendor->address_line_1 }}</dd>
                        <dt class="col-sm-3">Address Line 2</dt><dd class="col-sm-9">{{ $vendor->address_line_2 }}</dd>
                        <dt class="col-sm-3">City</dt><dd class="col-sm-9">{{ $vendor->city }}</dd>
                        <dt class="col-sm-3">State</dt><dd class="col-sm-9">{{ $vendor->state }}</dd>
                        <dt class="col-sm-3">Postal Code</dt><dd class="col-sm-9">{{ $vendor->postal_code }}</dd>
                        <dt class="col-sm-3">Country</dt><dd class="col-sm-9">{{ $vendor->country }}</dd>
                        <dt class="col-sm-3">Website</dt><dd class="col-sm-9">
                            @if($vendor->website)
                                <a href="{{ $vendor->website }}" target="_blank">{{ $vendor->website }}</a>
                            @else
                                -
                            @endif
                        </dd>
                        <dt class="col-sm-3">Status</dt><dd class="col-sm-9">
                            <span class="badge bg-{{ $vendor->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </dd>
                        <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $vendor->notes ?: '-' }}</dd>
                        <dt class="col-sm-3">Created At</dt><dd class="col-sm-9">{{ $vendor->created_at }}</dd>
                        <dt class="col-sm-3">Updated At</dt><dd class="col-sm-9">{{ $vendor->updated_at }}</dd>
                    </dl>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vendors.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                        <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
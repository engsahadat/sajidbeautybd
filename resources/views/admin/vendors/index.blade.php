@extends('admin.layouts.app')
@section('admin-title','Vendor List')
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Vendor List') }}</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">{{ __('Vendor') }}</li>
                        <li class="breadcrumb-item active">{{ __('Vendor List') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<!-- Container-fluid Ends-->

<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ route('vendors.index') }}" method="GET">
                            <div class="d-flex">
                                <div class="form-group me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search vendors..." name="q" value="{{ request('q') }}">
                                </div>
                                <div class="form-group me-2 mb-2">
                                    <select name="status" class="form-select">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="active" {{ request('status')==='active'?'selected':'' }}>{{ __('Active') }}</option>
                                        <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary mb-2" href="{{ route('vendors.index') }}">{{ __('Reset') }}</a>
                            </div>
                        </form>
                        <a href="{{ route('vendors.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Vendor') }}</a>
                    </div>
                    @if(session('message'))
                        <div class="alert alert-success m-3">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger m-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive table-desi">
                            <table class="table table-category " id="editableTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sl') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Company') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('City') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Option') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($vendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td data-field="name">{{ $vendor->name }}</td>
                                            <td data-field="company">{{ $vendor->company }}</td>
                                            <td data-field="email">{{ $vendor->email }}</td>
                                            <td data-field="phone">{{ $vendor->phone }}</td>
                                            <td data-field="city">{{ $vendor->city }}</td>
                                            <td class="{{ $vendor->status == 'active' ? 'order-success' : 'order-cancle' }}" data-field="status">
                                                <span>{{ $vendor->status }}</span>
                                            </td>
                                            <td data-field="created_at">{{ $vendor->created_at }}</td>
                                            <td>
                                                <a href="{{ route('vendors.show', $vendor->id) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                                <a href="{{ route('vendors.edit', $vendor->id) }}">
                                                    <i class="fa fa-edit" title="Edit"></i>
                                                </a>
                                                <a href="{{ route('vendors.destroy', $vendor->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this vendor? This action cannot be undone.')) { document.getElementById('delete-form-{{ $vendor->id }}').submit(); }">
                                                    <i class="fa fa-trash" title="Delete"></i>
                                                </a>
                                                <form id="delete-form-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>   
                                    @empty
                                        <tr><td colspan="9" class="text-center text-muted">{{ __('No vendors found.') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination Links --}}
                        @if($vendors->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="mb-0 text-muted">
                                    {{ __('Showing') }} {{ $vendors->firstItem() }} to {{ $vendors->lastItem() }} {{ __('of') }} {{ $vendors->total() }} {{ __('results') }}
                                    @if(request('q'))
                                        {{ __('for') }} "<strong>{{ request('q') }}</strong>"
                                    @endif
                                </p>
                            </div>
                            <div>
                                {{ $vendors->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

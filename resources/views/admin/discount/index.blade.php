@extends('admin.layouts.app')
@section('admin-title','Discount List')
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Discount List') }}</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">{{ __('Discount') }}</li>
                        <li class="breadcrumb-item active">{{ __('Discount List') }}</li>
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
                        <form class="search-form" action="{{ route('discounts.index') }}" method="GET">
                            <div class="d-flex">
                                <div class="form-group me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search discounts..." name="search" value="{{ request('search') }}">
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary mb-2" href="{{ route('discounts.index') }}">{{ __('Reset') }}</a>
                            </div>
                        </form>
                        <a href="{{ route('discounts.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Discount') }}</a>
                    </div>
                    @if(session('message'))
                        <div class="alert alert-success m-3">{{ session('message') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger m-3">{{ session('error') }}</div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive table-desi">
                            <table class="table table-category " id="editableTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Sl') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Value') }}</th>
                                        <th>{{ __('Start Date') }}</th>
                                        <th>{{ __('End Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Option') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($discounts as $discount)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $discount->image_url }}" data-field="image" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>

                                            <td data-field="title">{{ $discount->title }}</td>

                                            <td data-field="type">{{ ucfirst($discount->type) }}</td>

                                            <td data-field="value">{{ $discount->value }}</td>

                                            <td data-field="start_date">{{ isset($discount->start_date) ? $discount->start_date->format('Y-m-d H:i') : '-' }}</td>

                                            <td data-field="end_date">{{ isset($discount->end_date) ? $discount->end_date->format('Y-m-d H:i') : '-' }}</td>

                                            <td class="{{ $discount->status == 'active' ? 'order-success' : 'order-cancle' }}" data-field="status">
                                                <span>{{ ucfirst($discount->status) }}</span>
                                            </td>

                                            <td data-field="created_at">{{ $discount->created_at }}</td>

                                            <td>
                                                <a href="{{ route('discounts.edit', $discount->id) }}" class="text-primary"><i class="fa fa-edit" title="Edit"></i></a>
                                                <a href="{{ route('discounts.destroy', $discount->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this discount?')) { document.getElementById('delete-form-{{ $discount->id }}').submit(); }">
                                                    <i class="fa fa-trash" title="Delete"></i>
                                                </a>
                                                <form id="delete-form-{{ $discount->id }}" action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>   
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination Links --}}
                        @if($discounts->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="mb-0 text-muted">
                                    {{ __('Showing') }} {{ $discounts->firstItem() }} to {{ $discounts->lastItem() }} {{ __('of') }} {{ $discounts->total() }} {{ __('results') }}
                                    @if(request('search'))
                                        {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                    @endif
                                </p>
                            </div>
                            <div>
                                {{ $discounts->appends(request()->query())->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('admin-scripts')
@endpush

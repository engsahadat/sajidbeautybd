@extends('admin.layouts.app')
@section('admin-title','Category List')
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Category List') }}</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">{{ __('Category') }}</li>
                        <li class="breadcrumb-item active">{{ __('Category List') }}</li>
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
                        <form class="search-form" action="{{ route('categories.index') }}" method="GET">
                            <div class="d-flex">
                                <div class="form-group me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search categories..." name="search" value="{{ request('search') }}">
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary mb-2" href="{{ route('categories.index') }}">{{ __('Reset') }}</a>
                            </div>
                        </form>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Category') }}</a>
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
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Sort order') }}</th>
                                        <th>{{ __('Is Featured') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Option') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $category->image_url }}" data-field="image" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>

                                            <td data-field="name">{{ $category->name }}</td>

                                            <td data-field="slug">{{ $category->slug }}</td>

                                            <td data-field="sort-order">{{ $category->sort_order }}</td>

                                            <td class="{{ $category->is_active == true ? 'order-success' : 'order-cancle' }}" data-field="status">
                                                <span>{{ $category->is_active == true ? 'Yes' : 'No' }}</span>
                                            </td>
                                            <td class="{{ $category->status == 'active' ? 'order-success' : 'order-cancle' }}" data-field="status">
                                                <span>{{ $category->status }}</span>
                                            </td>

                                            <td data-field="name">{{ $category->created_at }}</td>

                                            <td>
                                                <a href="{{ route('categories.show', $category->id) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                                <a href="{{ route('categories.edit', $category->id) }}">
                                                    <i class="fa fa-edit" title="Edit"></i>
                                                </a>
                                                <a href="{{ route('categories.destroy', $category->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this category? This action cannot be undone.')) { document.getElementById('delete-form-{{ $category->id }}').submit(); }">
                                                    <i class="fa fa-trash" title="Delete"></i>
                                                </a>
                                                <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
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
                        @if($categories->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="mb-0 text-muted">
                                    {{ __('Showing') }} {{ $categories->firstItem() }} to {{ $categories->lastItem() }} {{ __('of') }} {{ $categories->total() }} {{ __('results') }}
                                    @if(request('search'))
                                        {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                    @endif
                                </p>
                            </div>
                            <div>
                                {{ $categories->appends(request()->query())->links() }}
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
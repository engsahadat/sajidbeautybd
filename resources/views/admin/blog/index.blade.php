@extends('admin.layouts.app')
@section('admin-title','Blog List')
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Blog List') }}</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">{{ __('Blog') }}</li>
                        <li class="breadcrumb-item active">{{ __('Blog List') }}</li>
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
                        <form action="{{ route('blogs.index') }}" method="GET">
                            <div class="d-flex">
                                <div class="form-group me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search blogs..." name="search" value="{{ request('search') }}">
                                </div>
                                <div class="form-group me-2 mb-2">
                                    <select name="status" class="form-select">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="active" {{ request('status')==='active'?'selected':'' }}>{{ __('Active') }}</option>
                                        <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary mb-2" href="{{ route('blogs.index') }}">{{ __('Reset') }}</a>
                            </div>
                        </form>
                        <a href="{{ route('blogs.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Blog') }}</a>
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
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Sort order') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Option') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($blogs as $blog)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $blog->image_url }}" data-field="image" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td data-field="title">{{ $blog->title }}</td>
                                            <td data-field="slug">{{ $blog->slug }}</td>
                                            <td data-field="author">{{ optional($blog->author)->first_name ?? 'N/A' }}</td>
                                            <td data-field="sort-order">{{ $blog->sort_order }}</td>
                                            <td class="{{ $blog->status == 'active' ? 'order-success' : 'order-cancle' }}" data-field="status">
                                                <span>{{ $blog->status }}</span>
                                            </td>
                                            <td data-field="created_at">{{ $blog->created_at }}</td>
                                            <td>
                                                <a href="{{ route('blogs.show', $blog->id) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                                <a href="{{ route('blogs.edit', $blog->id) }}">
                                                    <i class="fa fa-edit" title="Edit"></i>
                                                </a>
                                                <a href="{{ route('blogs.destroy', $blog->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this blog? This action cannot be undone.')) { document.getElementById('delete-form-{{ $blog->id }}').submit(); }">
                                                    <i class="fa fa-trash" title="Delete"></i>
                                                </a>
                                                <form id="delete-form-{{ $blog->id }}" action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>   
                                    @empty
                                        <tr><td colspan="9" class="text-center text-muted">{{ __('No blogs found.') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination Links --}}
                        @if($blogs->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="mb-0 text-muted">
                                    {{ __('Showing') }} {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} {{ __('of') }} {{ $blogs->total() }} {{ __('results') }}
                                    @if(request('search'))
                                        {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                    @endif
                                </p>
                            </div>
                            <div>
                                {{ $blogs->appends(request()->query())->links() }}
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

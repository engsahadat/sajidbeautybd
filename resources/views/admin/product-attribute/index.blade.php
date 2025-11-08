@extends('admin.layouts.app')
@section('admin-title','Product Attributes - ' . $product->name)
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Product Attributes') }}</h3>
                        <p class="text-muted mb-0">Product: <strong>{{ $product->name }}</strong></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ol class="breadcrumb pull-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i data-feather="home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Attributes') }}</li>
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
                        <form class="search-form" action="{{ route('products.attributes.index', $product) }}" method="GET">
                            <div class="d-flex">
                                <div class="me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search attributes..." name="search" value="{{ request('search') }}">
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary me-2 mb-2" href="{{ route('products.attributes.index', $product) }}">{{ __('Reset') }}</a>
                                <a class="btn btn-secondary mb-2" href="{{ route('products.index') }}">
                                    <i class="fa fa-arrow-left"></i> {{ __('Back to Products') }}
                                </a>
                            </div>
                        </form>
                        <a href="{{ route('products.attributes.create', $product) }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Attribute') }}</a>
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
                        @if($attributes->isEmpty() && !request('search'))
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> {{ __('No attributes found. Click "Add Attribute" to create one.') }}
                            </div>
                        @else
                            <div class="table-responsive table-desi">
                                <table class="table table-category" id="editableTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sl') }}</th>
                                            <th>{{ __('Attribute Name') }}</th>
                                            <th>{{ __('Attribute Value') }}</th>
                                            <th>{{ __('Group') }}</th>
                                            <th>{{ __('Sort Order') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Option') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($attributes as $attribute)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $attribute->attribute_name }}</strong></td>
                                                <td>{{ $attribute->attribute_value }}</td>
                                                <td>
                                                    @if($attribute->attribute_group)
                                                        <span class="badge bg-info">{{ $attribute->attribute_group }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $attribute->sort_order }}</td>
                                                <td>{{ $attribute->created_at->format('Y-m-d') }}</td>

                                                <td>
                                                    <a href="{{ route('products.attributes.show', [$product, $attribute]) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                                    <a href="{{ route('products.attributes.edit', [$product, $attribute]) }}">
                                                        <i class="fa fa-edit" title="Edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.attributes.destroy', [$product, $attribute]) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this attribute?')) { document.getElementById('delete-form-{{ $attribute->id }}').submit(); }">
                                                        <i class="fa fa-trash" title="Delete"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $attribute->id }}" action="{{ route('products.attributes.destroy', [$product, $attribute]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>   
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    {{ __('No attributes found') }}
                                                    @if(request('search'))
                                                        {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Pagination Links --}}
                            @if($attributes->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <p class="mb-0 text-muted">
                                        {{ __('Showing') }} {{ $attributes->firstItem() }} to {{ $attributes->lastItem() }} {{ __('of') }} {{ $attributes->total() }} {{ __('results') }}
                                        @if(request('search'))
                                            {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    {{ $attributes->appends(request()->query())->links() }}
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

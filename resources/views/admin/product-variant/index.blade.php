@extends('admin.layouts.app')
@section('admin-title','Product Variants - ' . $product->name)
@section('admin-content')
<!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>{{ __('Product Variants') }}</h3>
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
                        <li class="breadcrumb-item active">{{ __('Variants') }}</li>
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
                        <form class="search-form" action="{{ route('products.variants.index', $product) }}" method="GET">
                            <div class="d-flex">
                                <div class="me-2 mb-2">
                                    <input class="form-control" type="search" placeholder="Search variants..." name="search" value="{{ request('search') }}">
                                </div>
                                <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                                <a class="btn btn-secondary me-2 mb-2" href="{{ route('products.variants.index', $product) }}">{{ __('Reset') }}</a>
                                <a class="btn btn-secondary mb-2" href="{{ route('products.index') }}">
                                    <i class="fa fa-arrow-left"></i> {{ __('Back to Products') }}
                                </a>
                            </div>
                        </form>
                        <a href="{{ route('products.variants.create', $product) }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Variant') }}</a>
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
                        @if($variants->isEmpty() && !request('search'))
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> {{ __('No variants found. Click "Add Variant" to create one.') }}
                            </div>
                        @else
                            <div class="table-responsive table-desi">
                                <table class="table table-category" id="editableTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sl') }}</th>
                                            <th>{{ __('Image') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('SKU') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Stock') }}</th>
                                            <th>{{ __('Sort Order') }}</th>
                                            <th>{{ __('Default') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Option') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($variants as $variant)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if($variant->image)
                                                        <img src="{{ asset($variant->image) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('images/default-image.png') }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                </td>

                                                <td>{{ $variant->name }}</td>
                                                <td>{{ $variant->value }}</td>
                                                <td>{{ $variant->sku ?? '-' }}</td>
                                                <td>{{ $variant->price ? '৳' . number_format($variant->price, 2) : '-' }}</td>
                                                <td>
                                                    <span class="badge {{ $variant->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $variant->stock_quantity }}
                                                    </span>
                                                </td>
                                                <td>{{ $variant->sort_order }}</td>
                                                <td class="{{ $variant->is_default ? 'order-success' : 'order-cancle' }}">
                                                    <span>{{ $variant->is_default ? 'Yes' : 'No' }}</span>
                                                </td>
                                                <td>{{ $variant->created_at->format('Y-m-d') }}</td>

                                                <td>
                                                    <a href="{{ route('products.variants.show', [$product, $variant]) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                                    <a href="{{ route('products.variants.edit', [$product, $variant]) }}">
                                                        <i class="fa fa-edit" title="Edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.variants.destroy', [$product, $variant]) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this variant?')) { document.getElementById('delete-form-{{ $variant->id }}').submit(); }">
                                                        <i class="fa fa-trash" title="Delete"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $variant->id }}" action="{{ route('products.variants.destroy', [$product, $variant]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>   
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-muted">
                                                    {{ __('No variants found') }}
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
                            @if($variants->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <p class="mb-0 text-muted">
                                        {{ __('Showing') }} {{ $variants->firstItem() }} to {{ $variants->lastItem() }} {{ __('of') }} {{ $variants->total() }} {{ __('results') }}
                                        @if(request('search'))
                                            {{ __('for') }} "<strong>{{ request('search') }}</strong>"
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    {{ $variants->appends(request()->query())->links() }}
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

@extends('admin.layouts.app')
@section('admin-title','Product List')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Product List') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">{{ __('Product') }}</li>
                    <li class="breadcrumb-item active">{{ __('Product List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <form class="" action="{{ route('products.index') }}" method="GET">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="form-group me-2 mb-2">
                                <input class="form-control" type="search" placeholder="Search products..." name="search" value="{{ request('search') }}">
                            </div>
                            <div class="form-group me-2 mb-2">
                                <select name="category_id" class="form-select">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group me-2 mb-2">
                                <select name="brand_id" class="form-select">
                                    <option value="">{{ __('All Brands') }}</option>
                                    @foreach($brands as $br)
                                        <option value="{{ $br->id }}" {{ request('brand_id') == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                            <a class="btn btn-secondary mb-2" href="{{ route('products.index') }}">{{ __('Reset') }}</a>
                        </div>
                    </form>
                    <a href="{{ route('products.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Product') }}</a>
                </div>
                @if(session('message'))
                    <div class="alert alert-success m-3">{{ session('message') }}</div>
                @endif
                <div class="card-body">
                    <div class="table-responsive table-desi">
                        <table class="table table-category" id="editableTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Sale Price') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Brand') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Option') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" title="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/default-image.png') }}';">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <td>{{ number_format($product->sale_price, 2) }}</td>
                                        <td>{{ optional($product->category)->name }}</td>
                                        <td>{{ optional($product->brand)->name }}</td>
                                        <td class="{{ $product->is_active == true ? 'order-success' : 'order-cancle' }}">
                                            <span>{{ $product->is_active == true ? 'active' : 'inactive' }}</span>
                                        </td>
                                        <td>{{ $product->created_at }}</td>
                                        <td class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('products.reviews.view', ['product' => $product->id]) }}" title="Reviews"><i class="fa fa-comments"></i></a>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-success"><i class="fa fa-edit" title="Edit"></i></a>
                                            <a href="{{ route('products.destroy', $product->id) }}" class="text-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $product->id }}').submit();"><i class="fa fa-trash" title="Delete"></i></a>
                                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center text-muted">{{ __('No products found.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($products->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0 text-muted">
                                {{ __('Showing') }} {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} {{ __('results') }}
                            </p>
                        </div>
                        <div>
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
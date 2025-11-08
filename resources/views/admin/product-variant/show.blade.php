@extends('admin.layouts.app')
@section('admin-title','View Variant - ' . $product->name)
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Variant Details') }}</h3>
                    <p class="text-muted mb-0">Product: <strong>{{ $product->name }}</strong></p>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.variants.index', $product) }}">{{ __('Variants') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('View') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Variant Name') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Variant Value') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->value }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('SKU') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->sku ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Price') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->price ? '৳' . number_format($variant->price, 2) : 'Uses product price' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Stock Quantity') }}:</strong></div>
                        <div class="col-md-8">
                            <span class="badge {{ $variant->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $variant->stock_quantity }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Sort Order') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->sort_order }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Default Variant') }}:</strong></div>
                        <div class="col-md-8">
                            <span class="badge {{ $variant->is_default ? 'bg-success' : 'bg-secondary' }}">
                                {{ $variant->is_default ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                    @if($variant->image)
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Image') }}:</strong></div>
                        <div class="col-md-8">
                            <img src="{{ asset($variant->image) }}" alt="Variant" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Created At') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>{{ __('Updated At') }}:</strong></div>
                        <div class="col-md-8">{{ $variant->updated_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.variants.index', $product) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('Back to Variants') }}
                        </a>
                        <a href="{{ route('products.variants.edit', [$product, $variant]) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> {{ __('Edit Variant') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

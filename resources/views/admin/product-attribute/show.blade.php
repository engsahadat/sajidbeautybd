@extends('admin.layouts.app')
@section('admin-title','Attribute Details - ' . $product->name)
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Attribute Details') }}</h3>
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
                    <li class="breadcrumb-item"><a href="{{ route('products.attributes.index', $product) }}">{{ __('Attributes') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('View Attribute') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Attribute Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Attribute Name:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $attribute->attribute_name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Attribute Value:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $attribute->attribute_value }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Attribute Group:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            @if($attribute->attribute_group)
                                <span class="badge bg-info">{{ $attribute->attribute_group }}</span>
                            @else
                                <span class="text-muted">{{ __('Not grouped') }}</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Sort Order:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $attribute->sort_order }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Created At:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $attribute->created_at->format('F d, Y h:i A') }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>{{ __('Updated At:') }}</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $attribute->updated_at->format('F d, Y h:i A') }}
                        </div>
                    </div>
                    <hr>
                    <div class="mt-4">
                        <a href="{{ route('products.attributes.edit', [$product, $attribute]) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> {{ __('Edit Attribute') }}
                        </a>
                        <a href="{{ route('products.attributes.index', $product) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('Back to List') }}
                        </a>
                        <form action="{{ route('products.attributes.destroy', [$product, $attribute]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attribute?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash"></i> {{ __('Delete Attribute') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

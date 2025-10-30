@extends('admin.layouts.app')
@section('admin-title','Brand Details')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Brand Details') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">{{ __('Brands') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Details') }}</li>
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
                    <dl class="row">
                        <dt class="col-sm-3">{{ __('Logo') }}</dt>
                        <dd class="col-sm-9"><img src="{{ $brand->logo_url }}" alt="Logo" style="max-width: 100px; max-height: 100px; object-fit: cover;"></dd>
                        <dt class="col-sm-3">{{ __('Name') }}</dt>
                        <dd class="col-sm-9">{{ $brand->name }}</dd>
                        <dt class="col-sm-3">{{ __('Slug') }}</dt>
                        <dd class="col-sm-9">{{ $brand->slug }}</dd>
                        <dt class="col-sm-3">{{ __('Description') }}</dt>
                        <dd class="col-sm-9">{{ $brand->description }}</dd>
                        <dt class="col-sm-3">{{ __('Website') }}</dt>
                        <dd class="col-sm-9">@if($brand->website)<a target="_blank" href="{{ $brand->website }}">{{ $brand->website }}</a>@endif</dd>
                        <dt class="col-sm-3">{{ __('Sort Order') }}</dt>
                        <dd class="col-sm-9">{{ $brand->sort_order  }}</dd>
                        <dt class="col-sm-3">{{ __('Meta Title') }}</dt>
                        <dd class="col-sm-9">{{ $brand->meta_title }}</dd>
                        <dt class="col-sm-3">{{ __('Meta Description') }}</dt>
                        <dd class="col-sm-9">{{ $brand->meta_description }}</dd>
                        <dt class="col-sm-3">{{ __('Status') }}</dt>
                        <dd class="col-sm-9"><span class="badge {{ $brand->status == 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $brand->status }}</span></dd>
                    </dl>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

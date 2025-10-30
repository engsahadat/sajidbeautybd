@extends('admin.layouts.app')
@section('admin-title','Product Details')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Product Details') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Details') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="card"><div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">{{ __('Image') }}</dt><dd class="col-sm-9"><img src="{{ $product->image_url }}" alt="Image" style="max-width: 100px; max-height: 100px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/default-image.png') }}';"></dd>
                    <dt class="col-sm-3">{{ __('Name') }}</dt><dd class="col-sm-9">{{ $product->name }}</dd>
                    <dt class="col-sm-3">{{ __('SKU') }}</dt><dd class="col-sm-9">{{ $product->sku }}</dd>
                    <dt class="col-sm-3">{{ __('Slug') }}</dt><dd class="col-sm-9">{{ $product->slug }}</dd>
                    <dt class="col-sm-3">{{ __('Price') }}</dt><dd class="col-sm-9">{{ number_format($product->price, 2) }}</dd>
                    <dt class="col-sm-3">{{ __('Sale Price') }}</dt><dd class="col-sm-9">{{ number_format($product->sale_price, 2) }}</dd>
                    <dt class="col-sm-3">{{ __('Stock Quantity') }}</dt><dd class="col-sm-9">{{ $product->stock_quantity }}</dd>
                    <dt class="col-sm-3">{{ __('Manage Stock') }}</dt><dd class="col-sm-9"><span class="badge {{ $product->manage_stock ? 'bg-info' : 'bg-warning' }}">{{ $product->manage_stock ? 'Yes' : 'No' }}</span></dd>
                    <dt class="col-sm-3">{{ __('Stock Status') }}</dt><dd class="col-sm-9">
                        <span class="badge {{ $product->stock_status == 'in_stock' ? 'bg-success' : ($product->stock_status == 'out_of_stock' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                        </span>
                    </dd>
                    @if($product->weight)
                        <dt class="col-sm-3">{{ __('Weight') }}</dt><dd class="col-sm-9">{{ $product->weight }} kg</dd>
                    @endif
                    @if($product->dimensions)
                        <dt class="col-sm-3">{{ __('Dimensions') }}</dt><dd class="col-sm-9">{{ $product->dimensions }}</dd>
                    @endif
                    <dt class="col-sm-3">{{ __('Category') }}</dt><dd class="col-sm-9">{{ optional($product->category)->name }}</dd>
                    <dt class="col-sm-3">{{ __('Brand') }}</dt><dd class="col-sm-9">{{ optional($product->brand)->name }}</dd>
                    <dt class="col-sm-3">{{ __('Status') }}</dt><dd class="col-sm-9"><span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></dd>
                    <dt class="col-sm-3">{{ __('Featured') }}</dt><dd class="col-sm-9"><span class="badge {{ $product->is_featured ? 'bg-primary' : 'bg-light text-dark' }}">{{ $product->is_featured ? 'Yes' : 'No' }}</span></dd>
                    <dt class="col-sm-3">{{ __('Sort Order') }}</dt><dd class="col-sm-9">{{ $product->sort_order }}</dd>
                    @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th>')
                    <dt class="col-sm-3">{{ __('Description') }}</dt>
                    <dd class="col-sm-9">
                        @if($product->description)
                            {!! strip_tags($product->description, $allowedTags) !!}
                        @else
                            <span class="text-muted">{{ __('No description available') }}</span>
                        @endif
                    </dd>
                    <dt class="col-sm-3">{{ __('Short Description') }}</dt>
                    <dd class="col-sm-9">
                        @if($product->short_description)
                            {!! strip_tags($product->short_description, $allowedTags) !!}
                        @else
                            <span class="text-muted">{{ __('No short description available') }}</span>
                        @endif
                    </dd>
                    @if($product->highlight)
                        <dt class="col-sm-3">{{ __('Highlight') }}</dt>
                        <dd class="col-sm-9">{!! strip_tags($product->highlight, $allowedTags) !!}</dd>
                    @endif
                    @if($product->skin_concern)
                        <dt class="col-sm-3">{{ __('Skin Concern') }}</dt>
                        <dd class="col-sm-9">{!! strip_tags($product->skin_concern, $allowedTags) !!}</dd>
                    @endif
                    @if($product->skin_type)
                        <dt class="col-sm-3">{{ __('Skin Type') }}</dt>
                        <dd class="col-sm-9">{!! strip_tags($product->skin_type, $allowedTags) !!}</dd>
                    @endif
                    @if($product->remark)
                        <dt class="col-sm-3">{{ __('Remark') }}</dt>
                        <dd class="col-sm-9">{!! strip_tags($product->remark, $allowedTags) !!}</dd>
                    @endif
                    @if($product->country_of_origin)
                        <dt class="col-sm-3">{{ __('Country of Origin') }}</dt>
                        <dd class="col-sm-9">{!! strip_tags($product->country_of_origin, $allowedTags) !!}</dd>
                    @endif
                    @if($product->meta_title)
                        <dt class="col-sm-3">{{ __('Meta Title') }}</dt><dd class="col-sm-9">{{ $product->meta_title }}</dd>
                    @endif
                    @if($product->meta_description)
                        <dt class="col-sm-3">{{ __('Meta Description') }}</dt><dd class="col-sm-9">{{ $product->meta_description }}</dd>
                    @endif
                    <dt class="col-sm-3">{{ __('Created At') }}</dt><dd class="col-sm-9">{{ $product->created_at->format('M d, Y h:i A') }}</dd>
                    <dt class="col-sm-3">{{ __('Updated At') }}</dt><dd class="col-sm-9">{{ $product->updated_at->format('M d, Y h:i A') }}</dd>
                    @php($gallery = $product->gallery_urls)
                    @if(!empty($gallery))
                        <dt class="col-sm-3">{{ __('Gallery') }}</dt>
                        <dd class="col-sm-9">
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($gallery as $url)
                                    <img src="{{ $url }}" alt="Gallery" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @endforeach
                            </div>
                        </dd>
                    @endif
                </dl>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> {{ __('Edit') }}</a>
                </div>
            </div></div>
        </div>
    </div>
</div>
@endsection
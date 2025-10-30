@extends('admin.layouts.app')
@section('admin-title','Show Category')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6"><div class="page-header-left"><h3>Category Details</h3></div></div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Category</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Image</dt><dd class="col-sm-9"><img src="{{ $category->image_url }}" alt="Image" style="max-width: 100px; max-height: 100px; object-fit: cover;"
                            onerror="this.onerror=null;this.src='{{ asset('images/default-image.png') }}';"></dd>
                        <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $category->name }}</dd>
                        <dt class="col-sm-3">Slug</dt><dd class="col-sm-9">{{ $category->slug }}</dd>
                        <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $category->description }}</dd>
                        <dt class="col-sm-3">Sort Order</dt><dd class="col-sm-9">{{ $category->sort_order }}</dd>
                        <dt class="col-sm-3">Meta Title</dt><dd class="col-sm-9">{{ $category->meta_title }}</dd>
                        <dt class="col-sm-3">Meta Description</dt><dd class="col-sm-9">{{ $category->meta_description }}</dd>
                        <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $category->status }}</dd>
                        <dt class="col-sm-3">Crated At</dt><dd class="col-sm-9">{{ $category->created_at }}</dd>
                        <dt class="col-sm-3">Updated At</dt><dd class="col-sm-9">{{ $category->created_at }}</dd>
                    </dl>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
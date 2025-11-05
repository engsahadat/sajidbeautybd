@extends('admin.layouts.app')
@section('admin-title','Show Blog')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Blog Details') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">{{ __('Blog') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Details') }}</li>
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
                        <dt class="col-sm-3">{{ __('Image') }}</dt>
                        <dd class="col-sm-9">
                            <img src="{{ $blog->image_url }}" alt="Image" style="max-width: 200px; max-height: 200px; object-fit: cover;"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-blog.png') }}';">
                        </dd>
                        <dt class="col-sm-3">{{ __('Title') }}</dt>
                        <dd class="col-sm-9">{{ $blog->title }}</dd>
                        <dt class="col-sm-3">{{ __('Slug') }}</dt>
                        <dd class="col-sm-9">{{ $blog->slug }}</dd>
                        <dt class="col-sm-3">{{ __('Description') }}</dt>
                        <dd class="col-sm-9">
                            @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote>')
                            @if($blog->description)
                                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                                    {!! strip_tags($blog->description, $allowedTags) !!}
                                </div>
                            @else
                                -
                            @endif
                        </dd>
                        <dt class="col-sm-3">{{ __('Content') }}</dt>
                        <dd class="col-sm-9">
                            @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote>')
                            @if($blog->content)
                                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                                    {!! strip_tags($blog->content, $allowedTags) !!}
                                </div>
                            @else
                                -
                            @endif
                        </dd>
                        <dt class="col-sm-3">{{ __('Author') }}</dt>
                        <dd class="col-sm-9">{{ optional($blog->author)->first_name ?? 'N/A' }}</dd>
                        <dt class="col-sm-3">{{ __('Sort Order') }}</dt>
                        <dd class="col-sm-9">{{ $blog->sort_order }}</dd>
                        <dt class="col-sm-3">{{ __('Meta Title') }}</dt>
                        <dd class="col-sm-9">{{ $blog->meta_title ?: '-' }}</dd>
                        <dt class="col-sm-3">{{ __('Meta Description') }}</dt>
                        <dd class="col-sm-9">{{ $blog->meta_description ?: '-' }}</dd>
                        <dt class="col-sm-3">{{ __('Status') }}</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $blog->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </dd>
                        <dt class="col-sm-3">{{ __('Published At') }}</dt>
                        <dd class="col-sm-9">{{ $blog->published_at ? $blog->published_at->format('Y-m-d H:i:s') : '-' }}</dd>
                        <dt class="col-sm-3">{{ __('Created At') }}</dt>
                        <dd class="col-sm-9">{{ $blog->created_at }}</dd>
                        <dt class="col-sm-3">{{ __('Updated At') }}</dt>
                        <dd class="col-sm-9">{{ $blog->updated_at }}</dd>
                    </dl>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
                        <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

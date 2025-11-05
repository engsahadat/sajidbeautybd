@extends('front-end.layouts.app')
@section('title', 'Blog Details')
@section('content')
    <style>
        /* Ensure editor-inserted images fit container */
        .blog-detail img.rich, .blog-detail-contain img.rich { max-width: 100%; height: auto; }
        .blog-detail-contain .rich-content p { margin-bottom: .6rem; }
        .blog-detail-contain .rich-content ul, .blog-detail-contain .rich-content ol { padding-left: 1.2rem; }
    </style>
    <!-- breadcrumb start-->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Blog</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Blog Details') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end-->
    <!--section start-->
    <section class="blog-detail-page section-b-space ratio2_3">
        <div class="container">
            <div class="blog-detail">
                <img class="img-fluid" src="{{ $blog->image_url }}"
                    alt="Elevate Your Space: The Art of Stylish Furnishing!">
                <h3>{{ $blog->title }}</h3>
                <div class="rich-content">
                    @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote>')
                    @if($blog->description)
                        @if($blog->description !== strip_tags($blog->description))
                            {!! strip_tags($blog->description, $allowedTags) !!}
                        @else
                            {!! nl2br(e($blog->description)) !!}
                        @endif
                    @endif
                </div>
                <ul class="post-social">
                    <li>{{ $blog->created_at->format('d M Y h:i A') }}</li>
                    <li>Posted By : {{ $blog->author?->first_name }}</li>
                </ul>
            </div>
            <div class="blog-detail-contain">
                <div class="rich-content">
                    @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote>')
                    @if($blog->content)
                        @if($blog->content !== strip_tags($blog->content))
                            {!! strip_tags($blog->content, $allowedTags) !!}
                        @else
                            {!! nl2br(e($blog->content)) !!}
                        @endif
                    @else
                        <p>{{ __('No content available for this post.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection
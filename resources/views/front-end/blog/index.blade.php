@extends('front-end.layouts.app')
@section('title', 'Blog')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>{{ __('Blog') }}</h2>
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
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="blog-page section-b-space ratio2_3">
        <div class="container">
            <div class="row g-sm-4 g-3">
                <div class="col-lg-12 col-xl-12 col-xxl-12 no-sidebar">
                    <div class="row g-4">
                        @forelse ($blogs as $blog)
                            <div class="col-sm-6 col-xxl-4">
                            <div class="blog-box sticky-blog-box">
                                <div class="blog-image">
                                    <div class="blog-label-tag">
                                        <i class="ri-pushpin-fill"></i>
                                    </div>
                                    <a href="{{ route('front.blog.show',$blog->id) }}">
                                        <img src="{{ $blog->image_url }}" alt="Elevate Your Space: The Art of Stylish Furnishing!">
                                    </a>
                                </div>
                                <div class="blog-contain">
                                    <a href="{{ route('front.blog.show',$blog->id) }}">
                                        <h3>{{ $blog->title }}</h3>
                                    </a>
                                    <div class="blog-excerpt">
                                        @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a>')
                                        @if($blog->description)
                                            @if($blog->description !== strip_tags($blog->description))
                                                {!! strip_tags(Str::limit($blog->description, 220), $allowedTags) !!}
                                            @else
                                                {{ Str::limit($blog->description, 220) }}
                                            @endif
                                        @endif
                                    </div>
                                    <div class="blog-label">
                                        <span class="time">
                                            <i class="ri-time-line"></i>
                                            <span>{{ $blog->created_at->format('d M Y') }}</span>
                                        </span>
                                        <span class="super">
                                            <i class="ri-user-line"></i>
                                            <span>{{ $blog->author?->first_name }}</span>
                                        </span>
                                    </div>
                                    <a class="blog-button" href="{{ route('front.blog.show',$blog->id) }}">{{ __('Read More') }} <i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div>
                        </div>
                        @empty
                            <h3 class="text-center">Blog not Found..!</h3>
                        @endforelse
                    </div>
                    {{-- <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item">
                                        <a class="page-link" href="#!" aria-label="Previous">
                                            <span>
                                                <i class="ri-arrow-left-s-line"></i>
                                            </span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#!">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#!">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#!">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#!" aria-label="Next">
                                            <span>
                                                <i class="ri-arrow-right-s-line"></i>
                                            </span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->

@endsection
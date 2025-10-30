@extends('front-end.layouts.app')
@section('title', 'All Category')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>All Category</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">All Category</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->
    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="collection-content col-xl-12 col-lg-12">
                        <div class="page-main-content">
                            <button class="filter-btn btn mt-0">
                                <i class="ri-filter-fill"></i>
                                Filter
                            </button>
                            <div class="collection-product-wrapper">
                                <div class="product-top-filter mt-0">
                                    <div class="product-filter-content w-100">
                                        <div class="collection-grid-view">
                                            <ul>
                                                <li class="product-2-layout-view grid-icon">
                                                    <img src="../assets/images/inner-page/icon/2.png" alt="sort"
                                                        class=" ">
                                                </li>
                                                <li class="product-3-layout-view grid-icon active">
                                                    <img src="../assets/images/inner-page/icon/3.png" alt="sort"
                                                        class=" ">
                                                </li>
                                                <li class="product-4-layout-view grid-icon">
                                                    <img src="../assets/images/inner-page/icon/4.png" alt="sort"
                                                        class=" ">
                                                </li>
                                                <li class="list-layout-view list-icon">
                                                    <img src="../assets/images/inner-page/icon/list.png" alt="sort"
                                                        class=" ">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-wrapper-grid">
                                    <div class="row g-3 g-sm-4">
                                        @foreach($categories as $category)
                                            <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                                                <div class="basic-product theme-product-1">
                                                    <div class="overflow-hidden">
                                                        <div class="img-wrapper">
                                                            <a href="{{ route('home.category', $category->id) }}">
                                                                <img src="{{ $category->image_url }}" class="w-100 img-fluid" alt="{{ $category->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="product-detail text-center py-2">
                                                            <a class="product-title" href="{{ route('home.category', $category->id) }}">{{ $category->name }}</a>
                                                            {{-- @if(!empty($category->description))
                                                                <div class="small text-muted">{{ Str::limit(strip_tags($category->description), 60) }}</div>
                                                            @endif --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if(isset($categories) && method_exists($categories, 'hasPages') && $categories->hasPages())
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <nav aria-label="Category pagination">
                                                <div class="pagination-wrapper text-center">
                                                    {{ $categories->appends(request()->query())->links('pagination.custom') }}
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
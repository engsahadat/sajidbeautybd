@extends('front-end.layouts.app')
@section('title', 'All Brand')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>All Brand</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">All Brand</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <!-- Search Bar -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <form method="GET" action="{{ route('home.all-brand') }}" class="row g-3 align-items-end">
                                    <div class="col-md-10">
                                        <label for="search" class="form-label small text-muted">Search Brands</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               placeholder="Search by brand name..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" style="background-color: #EC8951; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#d97438'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#EC8951'; this.style.transform='translateY(0)';">
                                            <i class="ri-search-line"></i> Search
                                        </button>
                                    </div>
                                    @if(request('search'))
                                        <div class="col-12">
                                            <a href="{{ route('home.all-brand') }}" style="background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#5a6268'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#6c757d'; this.style.transform='translateY(0)';">
                                                <i class="ri-close-line"></i> Clear Search
                                            </a>
                                            <span class="ms-3 text-muted">
                                                Found <strong>{{ $brands->total() }}</strong> brands for "<strong>{{ request('search') }}</strong>"
                                            </span>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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
                                                    <img src="../assets/images/inner-page/icon/2.png" alt="sort" class=" ">
                                                </li>
                                                <li class="product-3-layout-view grid-icon active">
                                                    <img src="../assets/images/inner-page/icon/3.png" alt="sort" class=" ">
                                                </li>
                                                <li class="product-4-layout-view grid-icon">
                                                    <img src="../assets/images/inner-page/icon/4.png" alt="sort" class=" ">
                                                </li>
                                                <li class="list-layout-view list-icon">
                                                    <img src="../assets/images/inner-page/icon/list.png" alt="sort" class=" ">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-wrapper-grid">
                                    <div class="row g-3 g-sm-4">
                                        @foreach($brands as $brand)
                                            <div class="col-xl-3 col-lg-4 col-6 col-grid-box">
                                                <div class="basic-product theme-product-1">
                                                    <div class="overflow-hidden">
                                                        <div class="img-wrapper">
                                                            <a href="{{ route('home.brand', $brand->id) }}">
                                                                <img src="{{ $brand->logo_url }}" class="w-100 img-fluid" alt="{{ $brand->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="product-detail text-center py-2">
                                                            <a class="product-title" href="{{ route('home.brand', $brand->id) }}">{{ $brand->name }}</a>
                                                            {{-- @if(!empty($brand->description))
                                                                <div class="small text-muted">{{ Str::limit(strip_tags($brand->description), 60) }}</div>
                                                            @endif --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if(isset($brands) && method_exists($brands, 'hasPages') && $brands->hasPages())
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <nav aria-label="Brand pagination">
                                                <div class="pagination-wrapper text-center">
                                                    {{ $brands->appends(request()->query())->links('pagination.custom') }}
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
    <!-- section End -->
@endsection
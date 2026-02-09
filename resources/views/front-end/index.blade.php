@php
    use App\Models\Setting;
@endphp
@extends('front-end.layouts.app')
@section('title', 'Home')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Responsive Slider Styles */
    .home-slider {
        width: 100%;
        overflow: hidden;
    }
    
    .home-slider .home {
        display: block;
        position: relative;
        width: 100%;
        overflow: hidden;
        background-color: #f8f8f8;
    }
    
    .home-slider .bg-img {
        width: 100%;
        display: block;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Desktop - cover to fill width and maintain proper height */
    @media (min-width: 1200px) {
        /* .home-slider .home {
            height: 500px;
        } */
        .home-slider .bg-img {
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    }
    
    /* Tablet - adjust height */
    @media (min-width: 768px) and (max-width: 1199px) {
        .home-slider .home {
            height: 400px;
        }
        .home-slider .bg-img {
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    }
    
    /* Mobile landscape */
    @media (min-width: 576px) and (max-width: 767px) {
        .home-slider .home {
            height: auto;
            min-height: 250px;
        }
        .home-slider .bg-img {
            height: auto;
            max-width: 100%;
            object-fit: contain;
            object-position: center;
        }
    }
    
    /* Mobile portrait - show full image */
    @media (max-width: 575px) {
        .home-slider .home {
            height: auto;
            min-height: 200px;
        }
        .home-slider .bg-img {
            height: auto;
            max-width: 100%;
            object-fit: contain;
            object-position: center;
        }
    }
    
    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        color: #495057;
        
    }
    /* .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    } */
    .select2-results__option {
      display: flex;
    }
</style>
@endpush

@section('content')
    <!-- Home slider -->
    <section class="p-0">
        <div class="slide-1 home-slider">
            @if(isset($sliderImages) && $sliderImages->count() > 0)
                @foreach($sliderImages as $slide)
                    @foreach($slide->image_urls as $imageUrl)
                        <div>
                            <a href="javascript:void(0)" class="home">
                                <img src="{{ $imageUrl }}" alt="{{ $slide->title }}" class="bg-img blur-up lazyload">
                            </a>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
    </section>
    <!-- Home slider end -->
    
    <!-- Delivery days notice -->
    @php
        $deliveryDays = Setting::get('delivery_days');
    @endphp
    @if($deliveryDays)
    <section class="bg-light py-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h3><i class="ri-truck-line me-1"></i>
                        Delivery within <strong>{{ (int)$deliveryDays }}</strong> day{{ (int)$deliveryDays > 1 ? 's' : '' }} across Bangladesh
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    <!-- collection banner -->
    {{-- <section class="pb-0 banner-section">
        <div class="container">
            <div class="row partition2">
                @if(isset($bannerImages) && $bannerImages->count() > 0)
                    @php
                        $bannerCount = 0;
                        $maxBanners = 2;
                    @endphp

                    @foreach($bannerImages as $banner)
                        @if(isset($banner->image_urls) && is_array($banner->image_urls))
                            @foreach($banner->image_urls as $imageUrl)
                                @if($bannerCount < $maxBanners)
                                    <div class="col-md-6">
                                        <a href="javascript:void(0)" class="collection-banner">
                                            <img 
                                                src="{{ $imageUrl }}" 
                                                class="img-fluid blur-up lazyload" 
                                                alt="{{ $banner->title ?? 'Banner Image' }}">
                                        </a>
                                    </div>
                                    @php $bannerCount++; @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </section> --}}

    <!-- collection banner end -->
    <!-- Paragraph-->
    <div class="title1 section-t-space">
        <h2 class="title-inner1">Latest Drops</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="product-para">
                    <p class="text-center">Discover the latest in beauty—skincare, makeup, haircare, and fragrance. Welcome to our 'Latest Drops' edit, showcasing new arrivals and cult favorites to refresh your routine.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Paragraph end -->
    <!-- Product slider -->
    <section class="section-b-space pt-0 ratio_asos">
        <div class="container">
            <!-- Filter and Sort Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="{{ route('home') }}" class="row g-3 align-items-end">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label small text-muted">Search Products</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Search by name..." value="{{ request('search') }}">
                                </div>

                                <!-- Category Filter -->
                                <div class="col-md-3">
                                    <label for="category" class="form-label small text-muted">Category</label>
                                    <select class="form-select" id="category" name="category_id">
                                        <option value="">All Categories</option>
                                        @if(isset($categories))
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- Brand Filter -->
                                <div class="col-md-3">
                                    <label for="brand" class="form-label small text-muted">Brand</label>
                                    <select class="form-select" id="brand" name="brand_id">
                                        <option value="">All Brands</option>
                                        @php
                                            $brands = \App\Models\Brand::where('is_active', 1)
                                                ->where('status', 'active')
                                                ->orderBy('name', 'asc')
                                                ->get();
                                        @endphp
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort -->
                                <div class="col-md-2">
                                    <label for="sort" class="form-label small text-muted">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-12">
                                    <div style="display: flex; gap: 15px; align-items: center;">
                                        <button type="submit" style="background-color: #EC8951; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#d97438'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#EC8951'; this.style.transform='translateY(0)';">
                                            <i class="ri-filter-line"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('home') }}" style="background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#5a6268'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#6c757d'; this.style.transform='translateY(0)';">
                                            <i class="ri-refresh-line"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                @foreach($products as $product)
                    <div>
                        <div class="basic-product theme-product-1">
                            <div class="overflow-hidden">
                                <div class="img-wrapper">
                                    @php
                                        $onSale = ($product->sale_price && $product->sale_price > 0);
                                        $ribbonText = $product->is_featured ? 'Featured' : ($onSale ? 'Sale' : 'New');
                                    @endphp

                                    <div class="ribbon"><span>{{ $ribbonText }}</span></div>
                                    <a href="{{ route('home.product.details', $product->id) }}">
                                        <img src="{{ $product->image_url }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
                                    </a>

                    @if(($product->reviews_count ?? 0) > 0 && ($product->reviews_avg_rating ?? $product->average_rating ?? 0) > 0)
                        <div class="rating-label">
                            <i class="ri-star-fill"></i>
                            <span>{{ number_format($product->reviews_avg_rating ?? $product->average_rating, 1) }}</span>
                        </div>
                    @endif                                    <div class="cart-info">
                                        <a href="#!" onclick="toggleWishlist({{ $product->id }})" title="Add to Wishlist" class="wishlist-icon wishlist-btn-{{ $product->id }}">
                                            <i class="ri-heart-line wishlist-icon-{{ $product->id }}"></i>
                                        </a>
                                        <button onclick="addToCart({{ $product->id }})" title="Add to cart">
                                            <i class="ri-shopping-cart-line"></i>
                                        </button>
                                        <a href="{{ route('home.product.details', $product->id) }}" title="Quick View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <button onclick="toggleCompare({{ $product->id }})" title="Add to Compare" class="compare-btn-{{ $product->id }}">
                                            <i class="ri-refresh-line compare-icon-{{ $product->id }}"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="product-detail">
                                    <div>
                                        <h6>{{ $product->category->name ?? 'Category' }}</h6>
                                        <a href="{{ route('home.product.details', $product->id) }}">
                                            <h5 class="product-title">{{ Str::limit($product->name, 50) }}</h5>
                                        </a>
                                        @php
                                            $priceHtml = '';
                                            if ($onSale && ($product->price ?? 0) > 0) {
                                                $discount = (($product->price - $product->sale_price) / $product->price) * 100;
                                                $priceHtml = '৳ '.number_format($product->sale_price, 2).' <del>৳'.number_format($product->price, 2).'</del> <span class="discounted-price">'.round($discount).'% Off</span>';
                                            } else {
                                                $priceHtml = '৳ '.number_format($product->price, 2);
                                            }
                                        @endphp
                                        <h4 class="price">{!! $priceHtml !!}</h4>
                                    </div>

                                    @php
                                        $offerItems = [];
                                        if ($onSale && $product->price > 0) {
                                            $discount = (($product->price - $product->sale_price) / $product->price) * 100;
                                            $offerItems[] = '<li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>Limited Time Offer: '.round($discount).'% off</li>';
                                        }
                                        if (($product->stock_quantity ?? 0) > 0 && ($product->stock_quantity ?? 0) <= 10) {
                                            $offerItems[] = '<li><span class="offer-icon"><i class="ri-time-line"></i></span>Hurry! Only '.(int)$product->stock_quantity.' left in stock</li>';
                                        }
                                        if ($product->is_featured) {
                                            $offerItems[] = '<li><span class="offer-icon"><i class="ri-star-fill"></i></span>Featured Product</li>';
                                        }
                                        if (($product->stock_status === 'in_stock') && ($product->stock_quantity ?? 0) > 50) {
                                            $offerItems[] = '<li><span class="offer-icon"><i class="ri-check-line"></i></span>In Stock - Fast Delivery</li>';
                                        }
                                    @endphp

                                    @if(!empty($offerItems))
                                        <ul class="offer-panel">{!! implode('', $offerItems) !!}</ul>
                                    @endif
                                    
                                    <button onclick="orderNow({{ $product->id }})" class="btn btn-order-now w-100 mt-3">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(isset($products) && $products->hasPages())
                <div class="row mt-4">
                    <div class="col-12">
                        <nav aria-label="Product pagination">
                            <div class="pagination-wrapper text-center">
                                {{ $products->appends(request()->query())->links('pagination.custom') }}
                            </div>
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>


    
    <script>
        const isAuthenticated = @json(auth()->check());
        const loginUrl = "{{ route('login') }}";
        const csrfToken = "{{ csrf_token() }}";
        const checkoutUrl = "{{ route('checkout.show') }}";
        
        function requireAuth(callback) {
            // Allow both authenticated and guest users for wishlist/compare
            return callback();
        }
        
        function orderNow(productId) {
            // Add to cart and redirect to checkout (works for both authenticated and guest users)
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to checkout page
                    window.location.href = checkoutUrl;
                } else {
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to process order', 'error');
            });
        }
        
        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Product added to cart successfully!', 'success');
                    updateCartCount(data.cart_count);

                    // Execute Meta Pixel tracking if provided
                    if (data.pixel_script) {
                        try {
                            const scriptContainer = document.createElement('div');
                            scriptContainer.innerHTML = data.pixel_script;
                            document.body.appendChild(scriptContainer);
                        } catch (e) {
                            console.warn('Meta Pixel tracking failed:', e);
                        }
                    }
                } else {
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add product to cart', 'error');
            });
        }
        function toggleWishlist(productId) {
            requireAuth(() => {
                const icon = document.querySelector(`.wishlist-icon-${productId}`);
                const btn = document.querySelector(`.wishlist-btn-${productId}`);
                
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('_token', csrfToken);
                
                fetch('{{ route('cart.toggleWishlist') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            icon.classList.remove('ri-heart-line');
                            icon.classList.add('ri-heart-fill');
                            btn.style.color = '#e74c3c';
                            showNotification('Added to wishlist!', 'success');
                        } else {
                            icon.classList.remove('ri-heart-fill');
                            icon.classList.add('ri-heart-line');
                            btn.style.color = '';
                            showNotification('Removed from wishlist', 'info');
                        }
                        if (data.wishlist_count !== undefined) {
                            if (typeof setWishlistCount === 'function') {
                                setWishlistCount(data.wishlist_count);
                            }
                        }
                    } else {
                        showNotification(data.message || 'Failed to update wishlist', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to update wishlist', 'error');
                });
            });
        }
        function toggleCompare(productId) {
            requireAuth(() => {
                const icon = document.querySelector(`.compare-icon-${productId}`);
                const btn = document.querySelector(`.compare-btn-${productId}`);
                
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('_token', csrfToken);
                
                fetch('{{ route('cart.toggleCompare') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            icon.classList.remove('ri-refresh-line');
                            icon.classList.add('ri-check-line');
                            btn.style.color = '#27ae60';
                            showNotification('Added to compare list!', 'success');
                        } else {
                            icon.classList.remove('ri-check-line');
                            icon.classList.add('ri-refresh-line');
                            btn.style.color = '';
                            showNotification('Removed from compare list', 'info');
                        }
                        if (data.compare_count !== undefined) {
                            setCompareCount(data.compare_count);
                        }
                    } else {
                        showNotification(data.message || 'Failed to update compare list', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to update compare list', 'error');
                });
            });
        }
        function updateCartCount(count) {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = count;
            });
        }
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="ri-${type === 'success' ? 'check' : type === 'error' ? 'error-warning' : 'information'}-line me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (isAuthenticated) {
                loadUserPreferences();
            }
        });
        function loadUserPreferences() {
            fetch('{{ route('get.wishlist.items') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.wishlist_items) {
                    data.wishlist_items.forEach(item => {
                        const icon = document.querySelector(`.wishlist-icon-${item.product_id}`);
                        const btn = document.querySelector(`.wishlist-btn-${item.product_id}`);
                        if (icon && btn) {
                            icon.classList.remove('ri-heart-line');
                            icon.classList.add('ri-heart-fill');
                            btn.style.color = '#e74c3c';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading wishlist preferences:', error);
            });

            // Load all compare items
            fetch('{{ route('get.compare.items') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.compare_items) {
                    data.compare_items.forEach(item => {
                        const icon = document.querySelector(`.compare-icon-${item.product_id}`);
                        const btn = document.querySelector(`.compare-btn-${item.product_id}`);
                        if (icon && btn) {
                            icon.classList.remove('ri-refresh-line');
                            icon.classList.add('ri-check-line');
                            btn.style.color = '#27ae60';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading compare preferences:', error);
            });
        }
    </script>
    <!-- Product slider end -->
    <!-- full banner -->
    <section class="pt-0">
        @php $middleBanner = $middleImages->first(); @endphp
        @if($middleBanner && $middleBanner->image_urls)
            <a href="javascript:void(0)">
                <img src="{{ $middleBanner->image_urls[0] }}" alt="{{ $middleBanner->title }}" class="img-fluid blur-up lazyload">
            </a>
        @endif
    </section>
    <!-- full banner end -->
     <!-- service layout -->
    <div class="container">
        <section class="service border-section small-section">
            <div class="row">
                @if(isset($serviceImages) && $serviceImages->count() > 0)
                    @foreach($serviceImages as $service)
                        <div class="col-md-4 service-block">
                            <div class="media">
                                @if($service->image_urls && count($service->image_urls) > 0)
                                    <img src="{{ $service->image_urls[0] }}" alt="{{ $service->title }}" style="width: 60px; height: 60px; object-fit: contain;">
                                @endif
                                <div class="media-body">
                                    <h4>{{ $service->title ?? 'free shipping' }}</h4>
                                    <p>{{ $service->subtitle ?? 'free shipping world wide' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>
    </div>
    <!-- service layout  end -->
    <!-- blog section -->
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="title1 section-t-space">
                    <h2 class="title-inner1">Beauty for you</h2>
                </div>
            </div>
        </div>
    </div>
        <style>
            section.blog .classic-effect > div:first-child {
                position: relative;
                width: 100%;
                height: 0;
                padding-top: 66.666%; /* ~3:2 ratio */
                overflow: hidden;
                border-radius: 8px;
            }
            section.blog .classic-effect > div:first-child img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            section.blog .slick-slide { height: auto; }
            
            /* Order Now Button Styling */
            .btn-order-now {
                background-color: #EC8951;
                color: #fff;
                border: none;
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
                border-radius: 4px;
                text-decoration: none;
                display: block;
                text-align: center;
                transition: all 0.3s ease;
            }
            .btn-order-now:hover {
                background-color: #EC8951;
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
            .btn-order-now:active {
                transform: translateY(0);
            }
        </style>
    <section class="blog pt-0 ratio2_3 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="slide-3 no-arrow slick-default-margin">
                        @if(isset($blogs) && $blogs->count() > 0)
                            @foreach($blogs as $blog)
                                <div class="col-md-12">
                                    <a href="{{ route('front.blog.show', $blog->id) }}">
                                        <div class="classic-effect">
                                            <div>
                                                <img src="{{ $blog->image_url }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $blog->title }}">
                                            </div>
                                            <span></span>
                                        </div>
                                    </a>
                                    <div class="blog-details">
                                        <h4>{{ $blog->published_at ? $blog->published_at->format('d F Y') : 'Draft' }}</h4>
                                        <a href="{{ route('front.blog.show', $blog->id) }}">
                                            <p>{{ Str::limit($blog->title, 60) }}</p>
                                        </a>
                                        <hr class="style1">
                                        <h6>by: {{ $blog->author->name ?? 'Admin' }}{{ $blog->description ? ' | ' . Str::limit(strip_tags($blog->description), 30) : '' }}</h6>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- blog section end -->

@endsection

@push('script')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 on Category dropdown
            $('#category').select2({
                placeholder: 'All Categories',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownAutoWidth: false,
                language: {
                    noResults: function() {
                        return 'No categories found';
                    },
                    searching: function() {
                        return 'Searching...';
                    }
                }
            });
            
            // Initialize Select2 on Brand dropdown
            $('#brand').select2({
                placeholder: 'All Brands',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownAutoWidth: false,
                language: {
                    noResults: function() {
                        return 'No brands found';
                    },
                    searching: function() {
                        return 'Searching...';
                    }
                }
            });
        });
    </script>
@endpush
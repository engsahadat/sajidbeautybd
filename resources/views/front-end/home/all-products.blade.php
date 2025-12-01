@extends('front-end.layouts.app')
@section('title', 'All Products')
@section('content')

<!-- Breadcrumb Section -->
<section class="breadcrumb-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="section-b-space ratio_asos">
    <div class="container">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="title1">
                    <h2 class="title-inner1">All Products</h2>
                    <p class="text-center text-muted">Browse our complete collection of beauty products</p>
                </div>
            </div>
        </div>

        <!-- Filter and Sort Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('home.all-products') }}" class="row g-3 align-items-end">
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
                                    @php
                                        $categories = \App\Models\Category::where('is_active', 1)
                                            ->where('status', 'active')
                                            ->orderBy('name', 'asc')
                                            ->get();
                                    @endphp
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
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
                                    <a href="{{ route('home.all-products') }}" style="background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#5a6268'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#6c757d'; this.style.transform='translateY(0)';">
                                        <i class="ri-refresh-line"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Count -->
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted">
                    Showing <strong>{{ $products->firstItem() ?? 0 }}</strong> to 
                    <strong>{{ $products->lastItem() ?? 0 }}</strong> of 
                    <strong>{{ $products->total() }}</strong> products
                </p>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
            @forelse($products as $product)
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
                @endif                                <div class="cart-info">
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
            @empty
                <div class="col-md-12">
                    <h4 class="mt-3">No Products Found</h4>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="row mt-5">
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

<style>
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

<!-- JavaScript Functions -->
<script>
    const isAuthenticated = @json(auth()->check());
    const loginUrl = "{{ route('login') }}";
    const csrfToken = "{{ csrf_token() }}";
    const checkoutUrl = "{{ route('checkout.show') }}";

    function requireAuth(callback) {
        if (!isAuthenticated) {
            window.location.href = loginUrl;
            return false;
        }
        return callback();
    }
    
    // Order Now function - adds to cart and redirects to checkout
    function orderNow(productId) {
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
            } else {
                showNotification(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
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
                        const countElements = document.querySelectorAll('.compare-count');
                        countElements.forEach(el => el.textContent = data.compare_count);
                    }
                } else {
                    showNotification(data.message || 'Failed to update compare list', 'error');
                }
            })
            .catch(error => {
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

    // Load user preferences on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (isAuthenticated) {
            loadUserPreferences();
        }
    });

    function loadUserPreferences() {
        // Load wishlist items
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

        // Load compare items
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

@endsection
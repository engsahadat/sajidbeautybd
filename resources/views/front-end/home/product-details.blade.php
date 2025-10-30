@extends('front-end.layouts.app')
@section('title', 'Product Details')
@section('content')
    <style>
        .product-facts { text-align: left; }
        .product-facts .rich-content { font-size: .95rem; }
        .product-facts .rich-content ul,
        .product-facts .rich-content ol { list-style-type: disc !important;}
        .product-facts .rich-content li { list-style-type: disc !important; margin: 0 0 .4rem !important; }
        .product-facts .rich-content li::marker { content: '' !important; }
        .product-facts .rich-content li::before { content: none !important; display: none !important; }
        .product-facts .rich-content p { margin-bottom: .4rem !important; }
        .product-facts h5 { font-weight: 700; }
        .product-facts h6 { font-weight: 700; color: #6c757d; }
        /* Ensure editor images fit container */
        .accordion-body img, .product-facts .rich-content img { max-width: 100%; height: auto; display: block; margin: 0 auto .5rem; }
    </style>
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>{{ $product->name }}</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">{{ __('Product') }}</li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->
    <!-- section start -->
    <section>
        <div class="collection-wrapper">
            <div class="container">
                <div class="collection-wrapper">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="product-slick">
                                @if($product->gallery_urls && count($product->gallery_urls) > 0)
                                    @foreach($product->gallery_urls as $image)
                                        <div>
                                            <img src="{{ $image }}" alt="{{ $product->name }}" class="w-100 img-fluid blur-up lazyload">
                                        </div>
                                    @endforeach
                                @else
                                    <div>
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 img-fluid blur-up lazyload">
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="slider-nav">
                                        @if($product->gallery_urls && count($product->gallery_urls) > 0)
                                            @foreach($product->gallery_urls as $image)
                                                <div>
                                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="img-fluid blur-up lazyload">
                                                </div>
                                            @endforeach
                                        @else
                                            <div>
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid blur-up lazyload">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="product-page-details product-description-box sticky-details mt-0 mb-4">

                                {{-- <div class="trending-text ">
                                    <img src="../assets/images/product-details/trending.gif" class="img-fluid" alt="">
                                    <h5>Selling fast! 4 people have this in their carts.
                                    </h5>
                                </div> --}}

                                <h2 class="main-title">{{ $product->name }}</h2>
                                <div class="product-rating">
                                    <div class="rating-list">
                                        @php
                                            $averageRating = $product->reviews->avg('rating') ?? 0;
                                            $fullStars = floor($averageRating);
                                            $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        @endphp
                                        
                                        {{-- Full stars --}}
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <i class="ri-star-fill"></i>
                                        @endfor
                                        
                                        {{-- Half star --}}
                                        @if($hasHalfStar)
                                            <i class="ri-star-half-fill"></i>
                                        @endif
                                        
                                        {{-- Empty stars --}}
                                        @for($i = 0; $i < $emptyStars; $i++)
                                            <i class="ri-star-line"></i>
                                        @endfor
                                        
                                        @if($averageRating > 0)
                                            <span class="rating-number">({{ number_format($averageRating, 1) }})</span>
                                        @endif
                                    </div>

                                    <span class="divider">|</span>
                                    <a href="#!">{{ $product->reviews_count ?? 0 }} Reviews</a>
                                </div>

                                <div class="price-text">
                                    @if($product->sale_price && $product->sale_price > 0)
                                        <h3><span class="fw-normal">MRP:</span>
                                            ৳{{ number_format($product->sale_price, 2) }}
                                            <del class="text-muted ms-2">৳{{ number_format($product->price, 2) }}</del>
                                        </h3>
                                        @php($discount = (($product->price - $product->sale_price) / $product->price) * 100)
                                        <span class="text-success">{{ round($discount) }}% Off</span>
                                    @else
                                        <h3><span class="fw-normal">MRP:</span>
                                            ৳{{ number_format($product->price, 2) }}
                                        </h3>
                                    @endif
                                    <span>Inclusive of all taxes</span>
                                </div>

                                {{-- <div class="size-delivery-info flex-wrap">
                                    <a href="#return" data-bs-toggle="modal" class=""><i class="ri-truck-line"></i>
                                        Delivery &amp; Return </a>

                                    <a href="#ask-question" class="" data-bs-toggle="modal"><i
                                            class="ri-questionnaire-line"></i>
                                        Ask a Question </a>

                                </div> --}}


                                <div class="accordion accordion-flush product-accordion" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                Product Description </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6>')
                                                @if($product->description)
                                                    @if($product->description !== strip_tags($product->description))
                                                        {!! strip_tags($product->description, $allowedTags) !!}
                                                    @else
                                                        {!! nl2br(e($product->description)) !!}
                                                    @endif
                                                @elseif($product->short_description)
                                                    @if($product->short_description !== strip_tags($product->short_description))
                                                        {!! strip_tags($product->short_description, $allowedTags) !!}
                                                    @else
                                                        {!! nl2br(e($product->short_description)) !!}
                                                    @endif
                                                @else
                                                    <p>{{ 'No description available for this product.' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                                aria-controls="flush-collapseTwo">
                                                Information 
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body mb-2">
                                                <div class="bordered-box border-0 mt-0 pt-0">
                                                    <h4 class="sub-title">product Info</h4>
                                                    <ul class="shipping-info">
                                                        <li>
                                                            <span>SKU: </span>{{ $product->sku }}
                                                        </li>
                                                        @if($product->brand)
                                                            <li>
                                                                <span>Brand: </span>{{ $product->brand->name }}
                                                            </li>
                                                        @endif
                                                        @if($product->category)
                                                            <li>
                                                                <span>Category: </span>{{ $product->category->name }}
                                                            </li>
                                                        @endif
                                                        @if($product->weight)
                                                            <li>
                                                                <span>Weight: </span>{{ $product->weight }} Gms
                                                            </li>
                                                        @endif
                                                        @if($product->dimensions)
                                                            <li>
                                                                <span>Dimensions: </span>{{ $product->dimensions }}
                                                            </li>
                                                        @endif
                                                        <li><span>Stock Status: </span>
                                                            @if($product->stock_status === 'in_stock')
                                                                <span class="text-success">In Stock</span>
                                                            @elseif($product->stock_status === 'out_of_stock')
                                                                <span class="text-danger">Out of Stock</span>
                                                            @else
                                                                <span class="text-warning">On Backorder</span>
                                                            @endif
                                                        </li>
                                                        {{-- @if($product->manage_stock && $product->stock_quantity > 0)
                                                            <li>
                                                                <span>Quantity: </span>{{ $product->stock_quantity }} Items Left
                                                            </li>
                                                        @endif --}}
                                                    </ul>
                                                </div>
                                                <div class="bordered-box">
                                                    <h4 class="sub-title"> Delivery Details</h4>
                                                    <ul class="delivery-details">
                                                        <li>
                                                            <i class="ri-truck-line"></i> Your order is likely to reach you within {{ (int) (\App\Models\Setting::get('delivery_days', 3)) }} days.
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="dashed-border-box">
                                                    <h4 class="sub-title">Guaranteed Safe Checkout</h4>
                                                    <img class="img-fluid payment-img" alt="" src="{{ asset('images/ssl_pament_icon.jpg') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="product-page-details product-form-box product-right-box d-flex
                                align-items-center flex-column my-0">
                                <div class="product-buttons">
                                    <div class="qty-section">
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus"
                                                        data-type="minus" data-field="">
                                                        <i class="ri-arrow-left-s-line"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" class="form-control input-number"
                                                    value="1">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-right-plus"
                                                        data-type="plus" data-field="">
                                                        <i class="ri-arrow-right-s-line"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-buttons">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($product->stock_status === 'in_stock' && (!$product->manage_stock || $product->stock_quantity > 0))
                                            <button class="btn btn-animation btn-solid hover-solid scroll-button"
                                                type="button" onclick="addToCart({{ $product->id }}); event.stopPropagation(); return false;">
                                                Add To Cart
                                            </button>
                                            <button class="btn btn-solid buy-button" onclick="buyNow({{ $product->id }}); event.stopPropagation(); return false;">Buy Now</button>
                                        @else
                                            <button class="btn btn-animation btn-solid hover-solid scroll-button disabled"
                                                type="button"> Out Of Stock
                                            </button>
                                            <button class="btn btn-solid buy-button disabled">Buy Now</button>
                                        @endif
                                    </div>
                                </div>

                                @if($product->manage_stock && $product->stock_quantity > 0 && $product->stock_quantity <= 10)
                                <div class="left-progressbar w-100">
                                    <h6>Please Hurry Only {{ $product->stock_quantity }} Left In Stock</h6>
                                    <div role="progressbar" class="progress">
                                        <div class="progress-bar" style="width: {{ min(100, ($product->stock_quantity / 10) * 100) }}%;">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="buy-box justify-content-center gap-3">
                                    <a href="#!" onclick="toggleWishlist({{ $product->id }}); event.stopPropagation(); return false;" id="wishlist-btn">
                                        <i class="ri-heart-line" id="wishlist-icon"></i>
                                        <span id="wishlist-text">Add To Wishlist</span>
                                    </a>

                                    <a href="#!" onclick="toggleCompare({{ $product->id }}); event.stopPropagation(); return false;" id="compare-btn" class="add-compare">
                                        <i class="ri-refresh-line" id="compare-icon"></i>
                                        <span id="compare-text">Add To Compare</span>
                                    </a>

                                    <a href="#!" onclick="shareProduct()" id="share-btn">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                    </a>
                                </div>
                                
                                <div class="w-100 mt-2 product-facts">
                                    @php($allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6>')
                                    @if($product->highlight)
                                        <h5 class="mb-2">Highlight</h5>
                                        <div class="rich-content mb-3">
                                            @if($product->highlight !== strip_tags($product->highlight))
                                                {!! strip_tags($product->highlight, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->highlight)) !!}
                                            @endif
                                        </div>
                                    @endif

                                    @if($product->skin_concern)
                                        <h6 class="mb-1">Skin Concern</h6>
                                        <div class="rich-content mb-3">
                                            @if($product->skin_concern !== strip_tags($product->skin_concern))
                                                {!! strip_tags($product->skin_concern, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->skin_concern)) !!}
                                            @endif
                                        </div>
                                    @endif

                                    @if($product->skin_type)
                                        <h6 class="mb-1">Skin Type</h6>
                                        <div class="rich-content mb-3">
                                            @if($product->skin_type !== strip_tags($product->skin_type))
                                                {!! strip_tags($product->skin_type, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->skin_type)) !!}
                                            @endif
                                        </div>
                                    @endif

                                    @if($product->remark)
                                        <h6 class="mb-1">Remark</h6>
                                        <div class="rich-content mb-3">
                                            @if($product->remark !== strip_tags($product->remark))
                                                {!! strip_tags($product->remark, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->remark)) !!}
                                            @endif
                                        </div>
                                    @endif

                                    @if($product->country_of_origin)
                                        <h6 class="mb-1">Country of Origin</h6>
                                        <div class="rich-content mb-3">
                                            @if($product->country_of_origin !== strip_tags($product->country_of_origin))
                                                {!! strip_tags($product->country_of_origin, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->country_of_origin)) !!}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->
    <script>
        const isAuthenticated = @json(auth()->check());
        const loginUrl = "{{ route('login') }}";
        const csrfToken = "{{ csrf_token() }}";
        
        function selectVariant(element, imageUrl) {
            document.querySelectorAll('.image-box.image li').forEach(li => {
                li.classList.remove('active');
            });
            element.closest('li').classList.add('active');
            const mainImages = document.querySelectorAll('.product-slick img, .slider-nav img');
            mainImages.forEach(img => {
                img.src = imageUrl;
            });
            window.selectedVariantId = element.getAttribute('data-variant-id');
        }
        
        // Authentication check function
        function requireAuth(callback) {
            if (!isAuthenticated) {
                window.location.href = loginUrl;
                return; // prevent continuing when not authenticated
            }
            return callback();
        }
        
        // Add to Cart functionality
        function addToCart(productId) {
            console.log('Add to cart clicked for product:', productId);
            
            requireAuth(() => {
                const quantityInput = document.querySelector('input[name="quantity"]');
                const quantity = quantityInput ? quantityInput.value || 1 : 1;
                const variantId = window.selectedVariantId || null;
                
                const requestData = {
                    product_id: productId,
                    quantity: parseInt(quantity)
                };
                
                if (variantId) {
                    requestData.variant_id = variantId;
                }
                
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Product added to cart successfully!', 'success');
                        if (data.cart_count !== undefined) {
                            updateCartCount(data.cart_count);
                        }
                    } else {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to add product to cart', 'error');
                });
            });
        }
        
        // Wishlist functionality
        function toggleWishlist(productId) {
            console.log('Wishlist clicked for product:', productId);
            
            requireAuth(() => {
                const icon = document.getElementById('wishlist-icon');
                const text = document.getElementById('wishlist-text');
                const btn = document.getElementById('wishlist-btn');
                
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
                            text.textContent = 'In Wishlist';
                            btn.style.color = '#e74c3c';
                            showNotification('Added to wishlist!', 'success');
                        } else {
                            icon.classList.remove('ri-heart-fill');
                            icon.classList.add('ri-heart-line');
                            text.textContent = 'Add To Wishlist';
                            btn.style.color = '';
                            showNotification('Removed from wishlist', 'info');
                        }
                        if (data.wishlist_count !== undefined) {
                            setWishlistCount(data.wishlist_count);
                        }
                    } else {
                        showNotification(data.message || 'Failed to update wishlist', 'error');
                    }
                })
                .catch(error => {
                    console.error('Wishlist error:', error);
                    showNotification('Failed to update wishlist', 'error');
                });
            });
        }
        function toggleCompare(productId) {
            requireAuth(() => {
                const icon = document.getElementById('compare-icon');
                const text = document.getElementById('compare-text');
                const btn = document.getElementById('compare-btn');
                
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
                            text.textContent = 'In Compare';
                            btn.style.color = '#27ae60';
                            showNotification('Added to compare list!', 'success');
                        } else {
                            icon.classList.remove('ri-check-line');
                            icon.classList.add('ri-refresh-line');
                            text.textContent = 'Add To Compare';
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
                    console.error('Error:', error);
                    showNotification('Failed to update compare list', 'error');
                });
            });
        }
        function buyNow(productId) {
            requireAuth(() => {
                const quantity = document.querySelector('input[name="quantity"]').value;
                const variantId = window.selectedVariantId || null;
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        variant_id: variantId,
                        quantity: parseInt(quantity || 1, 10)
                    })
                })
                .then(async (response) => {
                    if (response.redirected) {
                        // Likely redirected to login page
                        window.location.href = response.url || loginUrl;
                        return Promise.reject('Redirected');
                    }
                    const ct = response.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        window.location.href = loginUrl;
                        return Promise.reject('Non-JSON response');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = '/checkout';
                    } else {
                        showNotification(data.message || 'Failed to proceed to checkout', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to proceed to checkout', 'error');
                });
            });
        }
        function shareProduct() {
            const productUrl = window.location.href;
            const productTitle = '{{ $product->name }}';
            
            if (navigator.share) {
                navigator.share({
                    title: productTitle,
                    text: 'Check out this amazing product: ' + productTitle,
                    url: productUrl
                }).then(() => {
                    showNotification('Product shared successfully!', 'success');
                }).catch((error) => {
                    console.log('Error sharing:', error);
                    fallbackShare(productUrl, productTitle);
                });
            } else {
                fallbackShare(productUrl, productTitle);
            }
        }
        function fallbackShare(url, title) {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Product URL copied to clipboard!', 'success');
            }).catch(() => {
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showNotification('Product URL copied to clipboard!', 'success');
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
            const firstVariant = document.querySelector('.image-box.image li.active a');
            if (firstVariant && firstVariant.hasAttribute('data-variant-id')) {
                window.selectedVariantId = firstVariant.getAttribute('data-variant-id');
            }
            const quantityInput = document.querySelector('input[name="quantity"]');
            const minusBtn = document.querySelector('.quantity-left-minus');
            const plusBtn = document.querySelector('.quantity-right-plus');
            
            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value) || 1;
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value) || 1;
                const maxStock = {{ $product->manage_stock ? ($product->stock_quantity ?: 999) : 999 }};
                if (currentValue < maxStock) {
                    quantityInput.value = currentValue + 1;
                } else {
                    showNotification('Maximum available quantity is ' + maxStock, 'info');
                }
            });
            
            quantityInput.addEventListener('input', function() {
                let value = parseInt(this.value) || 1;
                const maxStock = {{ $product->manage_stock ? ($product->stock_quantity ?: 999) : 999 }};
                
                if (value < 1) {
                    this.value = 1;
                } else if (value > maxStock) {
                    this.value = maxStock;
                    showNotification('Maximum available quantity is ' + maxStock, 'info');
                }
            });
            
            quantityInput.addEventListener('keypress', function(e) {
                if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                    e.preventDefault();
                }
            });
            if (isAuthenticated) {
                loadUserPreferences();
            }
        });
        function loadUserPreferences() {
            const productId = {{ $product->id }};
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
                    const isInWishlist = data.wishlist_items.some(item => item.product_id == productId);
                    if (isInWishlist) {
                        const icon = document.getElementById('wishlist-icon');
                        const text = document.getElementById('wishlist-text');
                        const btn = document.getElementById('wishlist-btn');
                        
                        icon.classList.remove('ri-heart-line');
                        icon.classList.add('ri-heart-fill');
                        text.textContent = 'In Wishlist';
                        btn.style.color = '#e74c3c';
                    }
                }
            })
            .catch(error => console.error('Error loading wishlist preferences:', error));
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
                    const isInCompare = data.compare_items.some(item => item.product_id == productId);
                    if (isInCompare) {
                        const icon = document.getElementById('compare-icon');
                        const text = document.getElementById('compare-text');
                        const btn = document.getElementById('compare-btn');
                        
                        icon.classList.remove('ri-refresh-line');
                        icon.classList.add('ri-check-line');
                        text.textContent = 'In Compare';
                        btn.style.color = '#27ae60';
                    }
                }
            })
            .catch(error => console.error('Error loading compare preferences:', error));
        }
    </script>
@endsection
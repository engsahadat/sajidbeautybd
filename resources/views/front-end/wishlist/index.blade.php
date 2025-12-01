@extends('front-end.layouts.app')
@section('title', 'My Wishlist')

@push('styles')
<style>
    /* Wishlist Responsive Styles */
    .wishlist-table {
        width: 100%;
    }
    
    /* Desktop View */
    @media (min-width: 992px) {
        .wishlist-desktop {
            display: table;
            width: 100%;
        }
        
        .wishlist-mobile {
            display: none;
        }
        
        .wishlist-desktop .btn-group-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .wishlist-desktop .btn-animation {
            background-color: var(--theme-color) !important;
            color: #fff !important;
            border: 1px solid var(--theme-color) !important;
        }
        
        .wishlist-desktop .btn-animation:hover {
            opacity: 0.9;
        }
        
        .wishlist-desktop .btn-outline-danger {
            background-color: #fff !important;
            color: #dc3545 !important;
            border: 1px solid #dc3545 !important;
        }
        
        .wishlist-desktop .btn-outline-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        
        .wishlist-desktop .btn-sm {
            padding: 8px 12px !important;
            font-size: 12px !important;
            border-radius: 4px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            white-space: nowrap !important;
        }
        
        .wishlist-desktop .btn-sm i {
            margin-right: 0 !important;
            font-size: 14px !important;
        }
    }
    
    /* Mobile & Tablet View */
    @media (max-width: 991px) {
        .wishlist-desktop {
            display: none;
        }
        
        .wishlist-mobile {
            display: block;
        }
        
        .wishlist-item-card {
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
        }
        
        .wishlist-item-image {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .wishlist-item-image img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .wishlist-item-details {
            display: block;
        }
        
        .wishlist-item-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f9f9f9;
        }
        
        .wishlist-item-field:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .wishlist-item-label {
            font-weight: 600;
            color: #333;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .wishlist-item-value {
            font-size: 14px;
            color: #555;
        }
        
        .wishlist-item-value h6 {
            margin-bottom: 3px;
            font-size: 14px;
        }
        
        .wishlist-item-value .price {
            font-weight: 600;
            color: var(--theme-color);
            font-size: 15px;
        }
        
        .wishlist-item-value .badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        .wishlist-actions .btn {
            flex: 1;
            min-width: 120px;
            padding: 8px 10px !important;
            font-size: 12px !important;
            border: 1px solid transparent;
            border-radius: 4px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            white-space: nowrap !important;
        }
        
        .wishlist-actions .btn-animation {
            background-color: var(--theme-color) !important;
            color: #fff !important;
        }
        
        .wishlist-actions .btn-animation:hover {
            opacity: 0.9;
        }
        
        .wishlist-actions .btn-outline-danger {
            background-color: #fff !important;
            color: #dc3545 !important;
            border: 1px solid #dc3545 !important;
        }
        
        .wishlist-actions .btn-outline-danger:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        
        .wishlist-actions .btn i {
            font-size: 13px !important;
            margin-right: 0 !important;
        }
        
        .wishlist-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid #f9f9f9;
            align-items: stretch;
        }
    }
    
    @media (max-width: 576px) {
        .wishlist-item-card {
            padding: 12px;
            margin-bottom: 12px;
        }
        
        .wishlist-item-image img {
            width: 100px;
            height: 100px;
        }
        
        .wishlist-item-field {
            margin-bottom: 10px;
            padding-bottom: 10px;
            font-size: 13px;
        }
        
        .wishlist-item-label {
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .wishlist-item-value {
            font-size: 13px;
        }
        
        .wishlist-item-value h6 {
            font-size: 13px;
        }
        
        .wishlist-item-value .price {
            font-size: 14px;
        }
        
        .wishlist-actions .btn {
            min-width: 100px;
            padding: 6px 8px !important;
            font-size: 11px !important;
        }
        
        .wishlist-actions .btn i {
            font-size: 12px !important;
        }
    }
    
    @media (max-width: 374px) {
        .wishlist-item-image img {
            width: 80px;
            height: 80px;
        }
        
        .wishlist-actions .btn {
            min-width: 80px;
            padding: 4px 6px !important;
            font-size: 10px !important;
        }
        
        .wishlist-actions .btn i {
            display: none;
        }
    }
</style>
@endpush

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>My Wishlist</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Wishlist</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- wishlist section start -->
    <section class="wishlist-section section-b-space">
        <div class="container">
            @if($wishlistItems->count() > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-heart-fill me-2"></i>My Wishlist ({{ $wishlistItems->count() }} items)</h5>
                            </div>
                            <div class="card-body p-0">
                                <!-- Desktop Table View -->
                                <div class="wishlist-desktop table-responsive">
                                    <table class="table cart-table wishlist-table">
                                        <thead>
                                            <tr class="table-head">
                                                <th scope="col">Image</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Stock Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($wishlistItems as $item)
                                                <tr id="wishlist-item-{{ $item->product_id }}">
                                                    <td>
                                                        <a href="{{ route('home.product.details', $item->product->id) }}">
                                                            <img src="{{ $item->product->image_url }}" 
                                                                 class="img-fluid blur-up lazyload" 
                                                                 alt="{{ $item->product->name }}"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('home.product.details', $item->product->id) }}" 
                                                           class="text-decoration-none">
                                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                            @if($item->product->brand)
                                                                <small class="text-muted">{{ $item->product->brand->name }}</small>
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if($item->product->sale_price && $item->product->sale_price > 0)
                                                            <h6 class="price">
                                                                ৳{{ number_format($item->product->sale_price, 2) }}
                                                                <del class="text-muted ms-2">৳{{ number_format($item->product->price, 2) }}</del>
                                                            </h6>
                                                        @else
                                                            <h6 class="price">৳{{ number_format($item->product->price, 2) }}</h6>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->product->stock_status === 'in_stock')
                                                            <span class="badge bg-success">In Stock</span>
                                                        @elseif($item->product->stock_status === 'out_of_stock')
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @else
                                                            <span class="badge bg-warning">On Backorder</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group-vertical gap-2">
                                                            @if($item->product->stock_status === 'in_stock')
                                                                <button class="btn btn-animation btn-sm" 
                                                                        onclick="moveToCart({{ $item->product->id }})">
                                                                    <i class="ri-shopping-cart-line me-1"></i>Move to Cart
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-danger btn-sm" 
                                                                    onclick="removeFromWishlist({{ $item->product->id }})">
                                                                <i class="ri-delete-bin-line me-1"></i>Remove
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Mobile Card View -->
                                <div class="wishlist-mobile px-3 py-3">
                                    @foreach($wishlistItems as $item)
                                        <div class="wishlist-item-card" id="wishlist-item-{{ $item->product_id }}">
                                            <div class="wishlist-item-image">
                                                <a href="{{ route('home.product.details', $item->product->id) }}">
                                                    <img src="{{ $item->product->image_url }}" 
                                                         class="img-fluid blur-up lazyload" 
                                                         alt="{{ $item->product->name }}">
                                                </a>
                                            </div>
                                            
                                            <div class="wishlist-item-details">
                                                <div class="wishlist-item-field">
                                                    <div class="wishlist-item-label">Product Name</div>
                                                    <div class="wishlist-item-value">
                                                        <a href="{{ route('home.product.details', $item->product->id) }}" 
                                                           class="text-decoration-none">
                                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                            @if($item->product->brand)
                                                                <small class="text-muted">{{ $item->product->brand->name }}</small>
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <div class="wishlist-item-field">
                                                    <div class="wishlist-item-label">Price</div>
                                                    <div class="wishlist-item-value">
                                                        @if($item->product->sale_price && $item->product->sale_price > 0)
                                                            <div class="price">
                                                                ৳{{ number_format($item->product->sale_price, 2) }}
                                                                <del class="text-muted ms-2">৳{{ number_format($item->product->price, 2) }}</del>
                                                            </div>
                                                        @else
                                                            <div class="price">৳{{ number_format($item->product->price, 2) }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="wishlist-item-field">
                                                    <div class="wishlist-item-label">Stock Status</div>
                                                    <div class="wishlist-item-value">
                                                        @if($item->product->stock_status === 'in_stock')
                                                            <span class="badge bg-success">In Stock</span>
                                                        @elseif($item->product->stock_status === 'out_of_stock')
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @else
                                                            <span class="badge bg-warning">On Backorder</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="wishlist-actions">
                                                @if($item->product->stock_status === 'in_stock')
                                                    <button class="btn btn-animation btn-sm" 
                                                            onclick="moveToCart({{ $item->product->id }})">
                                                        <i class="ri-shopping-cart-line me-1"></i>Move to Cart
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-danger btn-sm" 
                                                        onclick="removeFromWishlist({{ $item->product->id }})">
                                                    <i class="ri-delete-bin-line me-1"></i>Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm-12">
                        <div class="empty-cart-section text-center">
                            <div class="card">
                                <div class="card-body py-5">
                                    <i class="ri-heart-line" style="font-size: 100px; color: #ddd;"></i>
                                    <h3>Your Wishlist is Empty</h3>
                                    <p class="text-muted">Looks like you haven't added any products to your wishlist yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-animation mt-3">
                                        <i class="ri-shopping-bag-line me-2"></i>Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- wishlist section end -->

    <script>
        function moveToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                    removeFromWishlist(productId, false);
                    showNotification('Product moved to cart successfully!', 'success');
                    // Update cart count
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else {
                    showNotification(data.message || 'Failed to move product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to move product to cart', 'error');
            });
        }

        function removeFromWishlist(productId, showMessage = true) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('cart.toggleWishlist') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    const row = document.getElementById('wishlist-item-' + productId);
                    if (row) {
                        row.remove();
                    }
                    
                    // Update header wishlist count
                    if (data.wishlist_count !== undefined) {
                        setWishlistCount(data.wishlist_count);
                        
                        // Update page title count
                        const headerElement = document.querySelector('.card-header h5');
                        if (headerElement) {
                            headerElement.innerHTML = `<i class="ri-heart-fill me-2"></i>My Wishlist (${data.wishlist_count} items)`;
                        }
                    }
                    
                    // Check if wishlist is empty
                    const remainingItems = document.querySelectorAll('tbody tr').length;
                    if (remainingItems === 0) {
                        location.reload();
                    }
                    
                    if (showMessage) {
                        showNotification('Product removed from wishlist', 'info');
                    }
                } else {
                    showNotification(data.message || 'Failed to remove product from wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to remove product from wishlist', 'error');
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
    </script>
@endsection
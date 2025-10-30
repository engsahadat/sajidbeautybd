@extends('front-end.layouts.app')
@section('title', 'My Wishlist')
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
                    <div class="col-sm-12 table-responsive">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="ri-heart-fill me-2"></i>My Wishlist ({{ $wishlistItems->count() }} items)</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
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
                                                <tr id="wishlist-item-{{ $item->id }}">
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
                                                                        onclick="moveToCart({{ $item->product->id }}, {{ $item->id }})">
                                                                    <i class="ri-shopping-cart-line me-1"></i>Move to Cart
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-danger btn-sm" 
                                                                    onclick="removeFromWishlist({{ $item->product->id }}, {{ $item->id }})">
                                                                <i class="ri-delete-bin-line me-1"></i>Remove
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        function moveToCart(productId, wishlistItemId) {
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
                    removeFromWishlist(productId, wishlistItemId, false);
                    showNotification('Product moved to cart successfully!', 'success');
                } else {
                    showNotification(data.message || 'Failed to move product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to move product to cart', 'error');
            });
        }
        function removeFromWishlist(productId, wishlistItemId, showMessage = true) {
            fetch('{{ route('cart.toggleWishlist') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    action: 'remove'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('wishlist-item-' + wishlistItemId);
                    if (row) {
                        row.remove();
                    }
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
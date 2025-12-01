@extends('front-end.layouts.app')
@section('title', 'Compare Products')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>Compare Products</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Compare</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- compare section start -->
    <section class="compare-section section-b-space">
        <div class="container">
            @if($compareItems->count() > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                <h5 class="mb-1 mb-md-0 d-flex align-items-center gap-2">
                                    <i class="ri-refresh-line"></i>
                                    <span>Product Comparison</span>
                                    <span class="badge bg-primary rounded-pill">{{ $compareItems->count() }}/4</span>
                                </h5>
                                <small class="text-muted">You can compare up to 4 products at a time</small>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table compare-table">
                                        <tbody>
                                            <!-- Product Images Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Product</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-top">
                                                        <div class="compare-remove-btn">
                                                            <button class="btn btn-danger btn-sm" 
                                                                    onclick="removeFromCompare({{ $item->product->id }})"
                                                                    title="Remove from comparison">
                                                                <i class="ri-close-line"></i>
                                                            </button>
                                                        </div>
                                                        <a href="{{ route('home.product.details', $item->product->id) }}">
                                                            <img src="{{ $item->product->image_url }}" 
                                                                 class="img-fluid compare-product-img" 
                                                                 alt="{{ $item->product->name }}"
                                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                                        </a>
                                                        <h6 class="mt-2">
                                                            <a href="{{ route('home.product.details', $item->product->id) }}" 
                                                               class="text-decoration-none">{{ $item->product->name }}</a>
                                                        </h6>
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Price Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Price</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-middle">
                                                        @if($item->product->sale_price && $item->product->sale_price > 0)
                                                            <h6 class="price text-success">
                                                                ৳{{ number_format($item->product->sale_price, 2) }}
                                                            </h6>
                                                            <del class="text-muted">৳{{ number_format($item->product->price, 2) }}</del>
                                                            @php($discount = (($item->product->price - $item->product->sale_price) / $item->product->price) * 100)
                                                            <br><small class="text-success">{{ round($discount) }}% Off</small>
                                                        @else
                                                            <h6 class="price">৳{{ number_format($item->product->price, 2) }}</h6>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Brand Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Brand</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-middle">
                                                        {{ $item->product->brand->name ?? 'N/A' }}
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Category Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Category</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-middle">
                                                        {{ $item->product->category->name ?? 'N/A' }}
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Stock Status Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Stock Status</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-middle">
                                                        @if($item->product->stock_status === 'in_stock')
                                                            <span class="badge bg-success">In Stock</span>
                                                        @elseif($item->product->stock_status === 'out_of_stock')
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @else
                                                            <span class="badge bg-warning">On Backorder</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Description Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Description</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-top">
                                                        <small>{{ Str::limit($item->product->short_description ?? 'No description available', 100) }}</small>
                                                    </td>
                                                @endforeach
                                            </tr>

                                            <!-- Actions Row -->
                                            <tr>
                                                <td class="compare-label"><strong>Actions</strong></td>
                                                @foreach($compareItems as $item)
                                                    <td class="text-center align-middle">
                                                        <div class="compare-actions d-inline-flex flex-column gap-2">
                                                            @if($item->product->stock_status === 'in_stock')
                                                                <button class="btn btn-animation btn-solid btn-sm px-3" 
                                                                        onclick="addToCart({{ $item->product->id }})">
                                                                    <i class="ri-shopping-cart-line me-1"></i>Add to Cart
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-outline-primary btn-sm px-3" 
                                                                    onclick="addToWishlist({{ $item->product->id }})">
                                                                <i class="ri-heart-line me-1"></i>Add to Wishlist
                                                            </button>
                                                            <a href="{{ route('home.product.details', $item->product->id) }}" 
                                                               class="btn btn-outline-secondary btn-sm px-3">
                                                                <i class="ri-eye-line me-1"></i>View Details
                                                            </a>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
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
                                    <i class="ri-refresh-line" style="font-size: 100px; color: #ddd;"></i>
                                    <h3>No Products to Compare</h3>
                                    <p class="text-muted">You haven't added any products to comparison yet. Add up to 4 products to compare their features.</p>
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
    <!-- compare section end -->

    <script>
        // Add to cart function
        function addToCart(productId) {
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
                    showNotification('Product added to cart successfully!', 'success');
                } else {
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                showNotification('Failed to add product to cart', 'error');
            });
        }

        // Add to wishlist function
        function addToWishlist(productId) {
            fetch('{{ route('cart.toggleWishlist') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    action: 'add'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Product added to wishlist successfully!', 'success');
                } else {
                    showNotification(data.message || 'Product might already be in wishlist', 'info');
                }
            })
            .catch(error => {
                showNotification('Failed to add product to wishlist', 'error');
            });
        }

        // Remove from compare function
        function removeFromCompare(productId) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('cart.toggleCompare') }}', {
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
                    // Update header count
                    if (data.compare_count !== undefined) {
                        setCompareCount(data.compare_count);
                    }
                    location.reload(); // Reload to update the comparison table
                } else {
                    showNotification(data.message || 'Failed to remove product from comparison', 'error');
                }
            })
            .catch(error => {
                showNotification('Failed to remove product from comparison', 'error');
            });
        }

        // Notification system
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

    <style>
        /* Layout */
        .compare-table { min-width: 900px; border-collapse: separate; border-spacing: 0; }
        .compare-table tr + tr td { border-top: 1px solid #eef1f4; }
        .compare-table td { position: relative; padding: 16px; vertical-align: middle; background: #fff; }
        
        /* Sticky first column for easier scanning */
        .compare-label { 
            background-color: #f8f9fa; font-weight: 700; vertical-align: middle; min-width: 160px; 
            position: sticky; left: 0; z-index: 2; box-shadow: 4px 0 0 #fff inset; 
        }

        /* Product visuals */
        .compare-product-img { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .compare-remove-btn { position: absolute; top: 8px; right: 8px; z-index: 5; }

        /* Actions */
        .compare-actions .btn { min-width: 150px; }

        /* Card polish */
        .compare-section .card { border: 1px solid #eef1f4; box-shadow: 0 4px 14px rgba(0,0,0,0.04); }
        .compare-section .card-header { background: #fff; border-bottom: 1px solid #eef1f4; }

        /* Responsive */
        @media (max-width: 991.98px) {
            .compare-actions .btn { min-width: 100%; }
        }
        @media (max-width: 767.98px) {
            .compare-table { min-width: 760px; }
            .compare-label { min-width: 140px; }
        }
    </style>
@endsection
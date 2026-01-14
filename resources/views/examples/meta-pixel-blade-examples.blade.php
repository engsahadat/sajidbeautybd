{{-- 
    BLADE VIEW EXAMPLES FOR META PIXEL INTEGRATION
    
    Copy these examples to your actual views and customize as needed.
--}}

{{-- ============================================
    EXAMPLE 1: Product Details Page
    File: resources/views/products/show.blade.php
    ============================================ --}}
@extends('layouts.app')

@section('content')
<div class="product-details">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="price">{{ number_format($product->price, 2) }} BDT</p>
            <p class="description">{{ $product->description }}</p>
            
            <form id="add-to-cart-form" data-product-id="{{ $product->id }}">
                @csrf
                <div class="mb-3">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Track ViewContent event --}}
    {!! pixel_view_content($product) !!}
    
    <script>
    // AJAX Add to Cart with Pixel Tracking
    document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const productId = this.dataset.productId;
        
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Product added to cart!');
                
                // Execute Meta Pixel tracking
                if (data.pixel_script) {
                    const div = document.createElement('div');
                    div.innerHTML = data.pixel_script;
                    document.body.appendChild(div);
                }
                
                // Update cart count in navbar
                if (data.cart_count) {
                    document.querySelector('.cart-count').textContent = data.cart_count;
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
    </script>
@endpush

{{-- ============================================
    EXAMPLE 2: Checkout Page
    File: resources/views/checkout/index.blade.php
    ============================================ --}}
@extends('layouts.app')

@section('content')
<div class="checkout-page">
    <h1>Checkout</h1>
    
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                
                <h3>Billing Information</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Zip Code</label>
                        <input type="text" name="zip" class="form-control">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Proceed to Payment</button>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Order Summary</h5>
                    @foreach($cartItems as $item)
                        <div class="order-item">
                            <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                            <span>{{ number_format($item->price * $item->quantity, 2) }} BDT</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="total">
                        <strong>Total:</strong>
                        <strong>{{ number_format($total, 2) }} BDT</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Track InitiateCheckout event --}}
    {!! pixel_initiate_checkout($cartItems) !!}
@endpush

{{-- ============================================
    EXAMPLE 3: Order Success Page
    File: resources/views/orders/success.blade.php
    ============================================ --}}
@extends('layouts.app')

@section('content')
<div class="order-success text-center py-5">
    <div class="container">
        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
        <h1 class="mt-4">Order Confirmed!</h1>
        <p class="lead">Thank you for your purchase</p>
        
        <div class="card mt-4 mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h5>Order Details</h5>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }} BDT</p>
                <p><strong>Payment Status:</strong> <span class="badge bg-success">{{ ucfirst($order->payment_status) }}</span></p>
                
                @if($order->transaction_id)
                    <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                @endif
                
                <hr>
                
                <h6>Items Ordered:</h6>
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span>{{ number_format($item->price * $item->quantity, 2) }} BDT</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">View Order Details</a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Track Purchase event - MOST IMPORTANT! --}}
    {!! pixel_purchase($order) !!}
@endpush

{{-- ============================================
    EXAMPLE 4: Search Results Page
    File: resources/views/products/search.blade.php
    ============================================ --}}
@extends('layouts.app')

@section('content')
<div class="search-results">
    <h1>Search Results for "{{ $query }}"</h1>
    
    @if($products->isEmpty())
        <p>No products found matching your search.</p>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ number_format($product->price, 2) }} BDT</p>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{ $products->links() }}
    @endif
</div>
@endsection

@push('scripts')
    {{-- Track Search event --}}
    {!! pixel_search($query) !!}
@endpush

{{-- ============================================
    EXAMPLE 5: Category/Collection Page
    File: resources/views/categories/show.blade.php
    ============================================ --}}
@extends('layouts.app')

@section('content')
<div class="category-page">
    <h1>{{ $category->name }}</h1>
    
    @if($category->description)
        <p class="lead">{{ $category->description }}</p>
    @endif
    
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted">{{ number_format($product->price, 2) }} BDT</p>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                            <button class="btn btn-sm btn-primary quick-add" data-product-id="{{ $product->id }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script>
// Quick add to cart with pixel tracking
document.querySelectorAll('.quick-add').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.textContent = 'Added!';
                this.classList.add('btn-success');
                
                // Execute pixel tracking
                if (data.pixel_script) {
                    const div = document.createElement('div');
                    div.innerHTML = data.pixel_script;
                    document.body.appendChild(div);
                }
                
                setTimeout(() => {
                    this.textContent = 'Add to Cart';
                    this.classList.remove('btn-success');
                }, 2000);
            }
        });
    });
});
</script>
@endpush

{{-- ============================================
    EXAMPLE 6: Main Layout with Pixel Base Code
    File: resources/views/layouts/app.blade.php
    
    NOTE: If you want pixel on customer pages but NOT admin,
    create a separate layout or use conditional include
    ============================================ --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
    
    {{-- Meta Pixel Base Code - Loads on every page --}}
    @include('components.meta-pixel')
</head>
<body>
    <div id="app">
        @include('layouts.navigation')
        
        <main class="py-4">
            @yield('content')
        </main>
        
        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

{{-- ============================================
    BONUS: Using Meta Pixel in Vue/React Components
    ============================================ --}}

{{-- 
// Vue 3 Example
export default {
    methods: {
        async addToCart(productId) {
            const response = await fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: 1 })
            });
            
            const data = await response.json();
            
            if (data.success && data.pixel_script) {
                // Execute pixel script
                const div = document.createElement('div');
                div.innerHTML = data.pixel_script;
                document.body.appendChild(div);
            }
        },
        
        trackViewContent(product) {
            // Direct fbq call (if pixel is loaded)
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_ids: [product.id.toString()],
                    content_type: 'product',
                    value: parseFloat(product.price),
                    currency: 'BDT'
                });
            }
        }
    }
}
--}}

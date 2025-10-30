@extends('front-end.layouts.app')
@section('title', 'Category Products')
@section('content')
<div class="py-4 border-bottom bg-light">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-2 small">
				<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ $category->name ?? __('Category') }}</li>
			</ol>
		</nav>
		<h1 class="h4 mb-0">{{ $category->name ?? __('Category Products') }}</h1>
	</div>
</div>
<section class="section-b-space pt-0 ratio_asos">
	<div class="container">
		@if($products->isEmpty())
			<p class="text-center mt-4">{{ __('No products found in this category.') }}</p>
		@else
			<div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
				@foreach($products as $product)
					<div>
						<div class="basic-product theme-product-1">
							<div class="overflow-hidden">
								<div class="img-wrapper">
									<div class="ribbon">
										@if($product->is_featured)
											<span>Featured</span>
										@elseif($product->sale_price && $product->sale_price > 0)
											<span>Sale</span>
										@else
											<span>New</span>
										@endif
									</div>
									<a href="{{ route('home.product.details', $product->id) }}">
										<img src="{{ $product->image_url }}"
											class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
									</a>
									<div class="rating-label"><i class="ri-star-fill"></i><span>4.5</span></div>
									<div class="cart-info">
										<a href="#!" onclick="toggleWishlist({{ $product->id }})" 
										   title="Add to Wishlist" class="wishlist-icon wishlist-btn-{{ $product->id }}">
											<i class="ri-heart-line wishlist-icon-{{ $product->id }}"></i>
										</a>
										<button onclick="addToCart({{ $product->id }})" title="Add to cart">
											<i class="ri-shopping-cart-line"></i>
										</button>
										<a href="{{ route('home.product.details', $product->id) }}" title="Quick View">
											<i class="ri-eye-line"></i>
										</a>
										<button onclick="toggleCompare({{ $product->id }})" 
												title="Add to Compare" class="compare-btn-{{ $product->id }}">
											<i class="ri-refresh-line compare-icon-{{ $product->id }}"></i>
										</button>
									</div>
								</div>
								<div class="product-detail">
									<div>
										<div class="brand-w-color">
											<a class="product-title" href="{{ route('home.product.details', $product->id) }}">{{ $product->name }}</a>
											@if($product->brand)
												<div class="brand-info">
													<small class="text-muted">{{ $product->brand->name }}</small>
												</div>
											@endif
										</div>
										<h6>{{ $product->category->name ?? 'Category' }}</h6>
										<h4 class="price">
											@if($product->sale_price && $product->sale_price > 0)
												৳ {{ number_format($product->sale_price, 2) }}
												<del> ৳{{ number_format($product->price, 2) }} </del>
												@php($discount = (($product->price - $product->sale_price) / $product->price) * 100)
												<span class="discounted-price"> {{ round($discount) }}% Off</span>
											@else
												৳ {{ number_format($product->price, 2) }}
											@endif
										</h4>
									</div>
									<ul class="offer-panel">
										@if($product->sale_price && $product->sale_price > 0)
											@php($discount = (($product->price - $product->sale_price) / $product->price) * 100)
											<li>
												<span class="offer-icon">
													<i class="ri-discount-percent-fill"></i>
												</span>
												Limited Time Offer: {{ round($discount) }}% off
											</li>
										@endif
										
										@if($product->stock_quantity > 0 && $product->stock_quantity <= 10)
											<li><span class="offer-icon"><i class="ri-time-line"></i></span>
												Hurry! Only {{ $product->stock_quantity }} left in stock</li>
										@endif
										
										@if($product->is_featured)
											<li><span class="offer-icon"><i class="ri-star-fill"></i></span>
												Featured Product</li>
										@endif
										
										@if($product->stock_status === 'in_stock' && $product->stock_quantity > 50)
											<li><span class="offer-icon"><i class="ri-check-line"></i></span>
												In Stock - Fast Delivery</li>
										@endif
									</ul>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@endif
	</div>
</section>

<script>
	// Check if user is authenticated
	const isAuthenticated = @json(auth()->check());
	const loginUrl = "{{ route('login') }}";
	const csrfToken = "{{ csrf_token() }}";
	
	// Authentication check function
	function requireAuth(callback) {
		if (!isAuthenticated) {
			if (confirm('You need to login to perform this action. Would you like to login now?')) {
				window.location.href = loginUrl;
			}
			return false;
		}
		return callback();
	}
	
	// Add to Cart functionality
	function addToCart(productId) {
		requireAuth(() => {
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
				console.error('Error:', error);
				showNotification('Failed to add product to cart', 'error');
			});
		});
	}
	
	// Wishlist functionality
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
					// Update header count immediately (central helper handles creation/removal)
					if (data.wishlist_count !== undefined) {
						setWishlistCount(data.wishlist_count);
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
	
	// Compare functionality
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
					// Update header count immediately
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
	
	// Update cart count in header
	function updateCartCount(count) {
		const cartCountElements = document.querySelectorAll('.cart-count');
		cartCountElements.forEach(element => {
			element.textContent = count;
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
	
	// Load user preferences on page load
	document.addEventListener('DOMContentLoaded', function() {
		if (isAuthenticated) {
			loadUserPreferences();
		}
	});
	
	// Load user's wishlist and compare preferences from server
	function loadUserPreferences() {
		// Load all wishlist items
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
@endsection
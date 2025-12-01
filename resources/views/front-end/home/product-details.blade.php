@extends('front-end.layouts.app')
@section('title', 'Product Details')
@section('content')
    @php
        $allowedTags = '<p><br><ul><ol><li><strong><em><b><i><u><a><img><table><thead><tbody><tr><td><th><h1><h2><h3><h4><h5><h6>';
    @endphp
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
        
        /* Variant section styles */
        .variant-selection-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 1px solid #e8ecef;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .variant-selection-box h5 {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #1a1a1a;
            letter-spacing: -0.3px;
            position: relative;
            padding-bottom: 12px;
        }
        .variant-selection-box h5::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #0066ff 0%, #00a2ff 100%);
            border-radius: 2px;
        }
        .variant-group {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }
        .variant-group:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }
        .variant-group-label {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 14px;
            display: block;
            text-transform: capitalize;
        }
        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        /* Rectangle Button Style (default) */
        .variant-btn {
            padding: 12px 24px;
            border: 2px solid #e0e4e8;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
            min-width: 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .variant-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }
        .variant-btn:hover:not(:disabled)::before {
            left: 100%;
        }
        .variant-btn:hover:not(:disabled) {
            border-color: #0066ff;
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,102,255,0.15);
        }
        .variant-btn.active {
            border-color: #0066ff;
            background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(0,102,255,0.4);
            transform: translateY(-1px);
        }
        .variant-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background: #f5f5f5;
            text-decoration: line-through;
            border-color: #ddd;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Circle Button Style */
        .variant-btn.variant-circle {
            border-radius: 50%;
            width: 56px;
            height: 56px;
            min-width: 56px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }
        .variant-btn.variant-circle:hover:not(:disabled) {
            transform: translateY(-2px) scale(1.05);
        }

        /* Image Swatch Style */
        .variant-btn.variant-image {
            padding: 5px;
            width: 70px;
            height: 70px;
            min-width: 70px;
            border-radius: 10px;
            overflow: hidden;
        }
        .variant-btn.variant-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            transition: transform 0.3s ease;
        }
        .variant-btn.variant-image:hover:not(:disabled) img {
            transform: scale(1.1);
        }
        .variant-btn.variant-image.active::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            z-index: 2;
            background: rgba(0,102,255,0.9);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Color Swatch Style */
        .variant-btn.variant-color {
            padding: 0;
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .variant-btn.variant-color::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            right: 3px;
            bottom: 3px;
            border-radius: 50%;
            background: var(--swatch-color, #000);
            transition: all 0.3s ease;
        }
        .variant-btn.variant-color:hover:not(:disabled) {
            transform: translateY(-2px) scale(1.08);
            box-shadow: 0 6px 16px rgba(0,0,0,0.18);
        }
        .variant-btn.variant-color.active {
            border-color: #0066ff;
            border-width: 3px;
        }
        .variant-btn.variant-color.active::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 20px;
            font-weight: 900;
            text-shadow: 0 0 3px rgba(0,0,0,0.8), 0 2px 4px rgba(0,0,0,0.5);
            z-index: 1;
        }

        /* Radio Button Style */
        .variant-radio-wrapper {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            border: 2px solid #e0e4e8;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.25s ease;
            min-width: 120px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .variant-radio-wrapper:hover {
            border-color: #0066ff;
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,102,255,0.12);
        }
        .variant-radio-wrapper.active {
            border-color: #0066ff;
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f2ff 100%);
            box-shadow: 0 4px 12px rgba(0,102,255,0.15);
        }
        .variant-radio-wrapper input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            accent-color: #0066ff;
        }
        .variant-radio-wrapper label {
            margin: 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Dropdown Style */
        .variant-dropdown {
            width: 100%;
            max-width: 100%;
            padding: 12px 18px;
            border: 2px solid #e0e4e8;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%232c3e50' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }
        .variant-dropdown:hover {
            border-color: #0066ff;
            background-color: #f0f7ff;
        }
        .variant-dropdown:focus {
            outline: none;
            border-color: #0066ff;
            box-shadow: 0 0 0 3px rgba(0,102,255,0.1);
            background-color: #f0f7ff;
        }

        .variant-info-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e8ecef;
            border-radius: 10px;
            padding: 16px 20px;
            margin-top: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .variant-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .variant-info-row:last-child {
            margin-bottom: 0;
        }
        .variant-info-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .variant-info-value {
            font-size: 15px;
            color: #1a1a1a;
            font-weight: 700;
        }

        /* Mobile: Move buttons under product images */
        @media (max-width: 991px) {
            /* Hide the buttons from right sidebar on mobile */
            .col-lg-4:last-child .product-buttons {
                display: none !important;
            }
            
            /* Show mobile buttons under product images */
            .mobile-action-buttons {
                display: block !important;
                width: 100%;
                margin-top: 20px;
            }
            
            .mobile-action-buttons .product-buttons {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .mobile-action-buttons .qty-section {
                margin-bottom: 15px;
            }
            
            .mobile-action-buttons .qty-box {
                max-width: 180px;
                margin: 0 auto;
            }
            
            .mobile-action-buttons .d-flex {
                flex-direction: column;
                gap: 10px !important;
                width: 100%;
            }
            
            .mobile-action-buttons .btn {
                width: 100% !important;
                padding: 12px 20px;
                font-size: 15px;
            }
        }
        
        /* Desktop: Hide mobile buttons */
        @media (min-width: 992px) {
            .mobile-action-buttons {
                display: none !important;
            }
        }
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
                            
                            {{-- Mobile Action Buttons - Show under product images on mobile --}}
                            <div class="mobile-action-buttons">
                                <div class="product-buttons">
                                    <div class="qty-section">
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus mobile-qty-minus"
                                                        data-type="minus" data-field="">
                                                        <i class="ri-arrow-left-s-line"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" class="form-control input-number mobile-quantity"
                                                    value="1">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-right-plus mobile-qty-plus"
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
                                    <a href="javascript:void(0)">{{ $product->reviews_count ?? 0 }} Reviews</a>
                                </div>

                                <div class="price-text">
                                    @if($product->sale_price && $product->sale_price > 0)
                                        <h3><span class="fw-normal">MRP:</span>
                                            ৳{{ number_format($product->sale_price, 2) }}
                                            <del class="text-muted ms-2">৳{{ number_format($product->price, 2) }}</del>
                                        </h3>
                                        @php
                                            $discount = (($product->price - $product->sale_price) / max($product->price, 1)) * 100;
                                        @endphp
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
                                                        @if($product->product_type)
                                                            <li>
                                                                <span>Type: </span>{{ ucfirst($product->product_type) }}
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
                                                @if($product->attributes && $product->attributes->isNotEmpty())
                                                <div class="bordered-box">
                                                    <h4 class="sub-title">Specifications</h4>
                                                    @php($grouped = $product->attributes->groupBy('attribute_group'))
                                                    @foreach($grouped as $group => $attrs)
                                                        @if($group)
                                                            <h6 class="mb-2">{{ $group }}</h6>
                                                        @endif
                                                        <div class="table-responsive mb-2">
                                                            <table class="table table-sm mb-0">
                                                                <tbody>
                                                                @foreach($attrs as $attr)
                                                                    <tr>
                                                                        <td class="text-muted" style="width:35%">{{ $attr->attribute_name }}</td>
                                                                        <td>{{ $attr->attribute_value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @endif
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
                                
                                @if($product->hasVariants())
                                    <div class="variant-selection-box w-100">
                                        <h5>Select Option</h5>
                                        @php($variantGroups = $product->variants->groupBy('name'))
                                        @foreach($variantGroups as $variantName => $variants)
                                            @php($displayStyle = $variants->first()->display_style ?? 'rectangle')
                                            <div class="variant-group">
                                                <span class="variant-group-label">{{ $variantName }}</span>
                                                
                                                @if($displayStyle === 'dropdown')
                                                    {{-- Dropdown Style --}}
                                                    <select class="variant-dropdown" 
                                                        data-variant-name="{{ $variantName }}"
                                                        onchange="selectVariantFromDropdown(this)">
                                                        @foreach($variants as $variant)
                                                            <option 
                                                                value="{{ $variant->id }}"
                                                                data-variant-id="{{ $variant->id }}"
                                                                data-variant-name="{{ $variantName }}"
                                                                data-variant-value="{{ $variant->value }}"
                                                                data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                data-variant-stock="{{ $variant->stock_quantity }}"
                                                                data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                {{ $variant->is_default ? 'selected' : '' }}
                                                                {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                                {{ $variant->value }} {{ $variant->stock_quantity <= 0 ? '(Out of stock)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <div class="variant-options">
                                                        @foreach($variants as $variant)
                                                            @if($displayStyle === 'radio')
                                                                {{-- Radio Button Style --}}
                                                                <div class="variant-radio-wrapper {{ $variant->is_default ? 'active' : '' }}"
                                                                    data-variant-id="{{ $variant->id }}"
                                                                    onclick="selectVariantRadio(this)">
                                                                    <input type="radio" 
                                                                        name="variant_{{ $variantName }}" 
                                                                        id="variant_{{ $variant->id }}"
                                                                        value="{{ $variant->id }}"
                                                                        data-variant-name="{{ $variantName }}"
                                                                        data-variant-value="{{ $variant->value }}"
                                                                        data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                        data-variant-stock="{{ $variant->stock_quantity }}"
                                                                        data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                        {{ $variant->is_default ? 'checked' : '' }}
                                                                        {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                                    <label for="variant_{{ $variant->id }}">{{ $variant->value }}</label>
                                                                </div>
                                                            @elseif($displayStyle === 'color')
                                                                {{-- Color Swatch Style --}}
                                                                <button type="button" 
                                                                    class="variant-btn variant-color {{ $variant->is_default ? 'active' : '' }}"
                                                                    style="--swatch-color: {{ $variant->color_code ?? '#000000' }}"
                                                                    data-variant-id="{{ $variant->id }}"
                                                                    data-variant-name="{{ $variantName }}"
                                                                    data-variant-value="{{ $variant->value }}"
                                                                    data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                    data-variant-stock="{{ $variant->stock_quantity }}"
                                                                    data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}
                                                                    onclick="selectVariant(this)"
                                                                    title="{{ $variant->value }}">
                                                                </button>
                                                            @elseif($displayStyle === 'image')
                                                                {{-- Image Swatch Style --}}
                                                                <button type="button" 
                                                                    class="variant-btn variant-image {{ $variant->is_default ? 'active' : '' }}"
                                                                    data-variant-id="{{ $variant->id }}"
                                                                    data-variant-name="{{ $variantName }}"
                                                                    data-variant-value="{{ $variant->value }}"
                                                                    data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                    data-variant-stock="{{ $variant->stock_quantity }}"
                                                                    data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}
                                                                    onclick="selectVariant(this)"
                                                                    title="{{ $variant->value }}">
                                                                    <img src="{{ $variant->swatch_image_url ?? $variant->image_url ?? asset('images/placeholder.png') }}" 
                                                                         alt="{{ $variant->value }}">
                                                                </button>
                                                            @elseif($displayStyle === 'circle')
                                                                {{-- Circle Button Style --}}
                                                                <button type="button" 
                                                                    class="variant-btn variant-circle {{ $variant->is_default ? 'active' : '' }}"
                                                                    data-variant-id="{{ $variant->id }}"
                                                                    data-variant-name="{{ $variantName }}"
                                                                    data-variant-value="{{ $variant->value }}"
                                                                    data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                    data-variant-stock="{{ $variant->stock_quantity }}"
                                                                    data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}
                                                                    onclick="selectVariant(this)">
                                                                    {{ $variant->value }}
                                                                </button>
                                                            @else
                                                                {{-- Rectangle Button Style (default) --}}
                                                                <button type="button" 
                                                                    class="variant-btn {{ $variant->is_default ? 'active' : '' }}"
                                                                    data-variant-id="{{ $variant->id }}"
                                                                    data-variant-name="{{ $variantName }}"
                                                                    data-variant-value="{{ $variant->value }}"
                                                                    data-variant-price="{{ $variant->price ?? $product->effective_price }}"
                                                                    data-variant-stock="{{ $variant->stock_quantity }}"
                                                                    data-variant-sku="{{ $variant->sku ?? $product->sku }}"
                                                                    {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}
                                                                    onclick="selectVariant(this)">
                                                                    {{ $variant->value }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="variant-info-box">
                                            <div class="variant-info-row">
                                                <span class="variant-info-label">SKU:</span>
                                                <span class="variant-info-value" id="variant-sku">{{ optional($product->getDefaultVariant())->sku ?? $product->sku }}</span>
                                            </div>
                                            <div class="variant-info-row">
                                                <span class="variant-info-label">Available:</span>
                                                <span class="variant-info-value" id="variant-stock">{{ optional($product->getDefaultVariant())->stock_quantity ?? $product->stock_quantity }}</span> pieces
                                            </div>
                                        </div>
                                        <input type="hidden" id="selected-variant-id" value="{{ optional($product->getDefaultVariant())->id ?? '' }}">
                                    </div>
                                @endif
                                
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

                                    @php(
                                        $typeLabels = [
                                            'hair' => ['type' => 'Hair Type', 'concern' => 'Hair Concern'],
                                            'face' => ['type' => 'Face Type', 'concern' => 'Face Concern'],
                                            'body' => ['type' => 'Body Skin Type', 'concern' => 'Body Skin Concern'],
                                            'makeup' => ['type' => 'Makeup Type', 'concern' => 'Makeup Focus'],
                                            'fragrance' => ['type' => 'Fragrance Type', 'concern' => 'Fragrance Notes'],
                                            'tools' => ['type' => 'Tool Type', 'concern' => 'Tool Use / Feature'],
                                            'skin' => ['type' => 'Skin Type', 'concern' => 'Skin Concern'],
                                        ]
                                    )
                                    @php($ctx = $typeLabels[$product->product_type ?? 'skin'] ?? $typeLabels['skin'])
                                    @if($product->skin_concern)
                                        <h6 class="mb-1">{{ $ctx['concern'] }}</h6>
                                        <div class="rich-content mb-3">
                                            @if($product->skin_concern !== strip_tags($product->skin_concern))
                                                {!! strip_tags($product->skin_concern, $allowedTags) !!}
                                            @else
                                                {!! nl2br(e($product->skin_concern)) !!}
                                            @endif
                                        </div>
                                    @endif

                                    @if($product->skin_type)
                                        <h6 class="mb-1">{{ $ctx['type'] }}</h6>
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
                                
                                <!-- Customer Reviews Section -->
                                <div class="w-100 mt-3">
                                    <div class="accordion" id="reviewsAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingReviews">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                                                    Customer Reviews ({{ $product->reviews_count ?? 0 }})
                                                </button>
                                            </h2>
                                            <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="headingReviews" data-bs-parent="#reviewsAccordion">
                                                <div class="accordion-body">
                                                    @if(($product->reviews ?? collect())->isEmpty())
                                                        <p class="text-muted mb-3">No reviews yet. Be the first to review this product.</p>
                                                    @else
                                                        <div class="mb-3">
                                                            @foreach($product->reviews as $rev)
                                                                <div class="border rounded p-3 mb-2">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <div>
                                                                            @for($i=1;$i<=5;$i++)
                                                                                <i class="ri-star{{ $i <= (int)$rev->rating ? '-fill text-warning' : '-line text-muted' }}"></i>
                                                                            @endfor
                                                                            <strong class="ms-2">{{ $rev->title }}</strong>
                                                                        </div>
                                                                        <small class="text-muted">{{ $rev->created_at?->format('M d, Y') }}</small>
                                                                    </div>
                                                                    <div class="mt-2">{{ $rev->review }}</div>
                                                                    @if($rev->is_verified_purchase)
                                                                        <span class="badge bg-success mt-2">Verified Purchase</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @auth
                                                    <hr>
                                                    <h5 class="mb-3">Write a Review</h5>
                                                    <form id="review-form" action="{{ route('front.reviews.store', ['product' => $product->id]) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label class="form-label d-block">Your Rating <span class="text-danger">*</span></label>
                                                            <div id="rating-stars" class="d-flex gap-1">
                                                                @for($i=1;$i<=5;$i++)
                                                                    <i class="ri-star-line fs-4 rating-star" data-value="{{ $i }}" style="cursor:pointer;"></i>
                                                                @endfor
                                                            </div>
                                                            <input type="hidden" name="rating" id="rating" value="">
                                                            <div class="text-danger d-none error-message" id="rating-error"></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label">Title</label>
                                                            <input type="text" class="form-control" id="title" name="title" maxlength="100" placeholder="Great product!">
                                                            <div class="text-danger d-none error-message" id="title-error"></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="review" class="form-label">Your Review <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="review" name="review" rows="4" placeholder="Share your experience..." maxlength="2000"></textarea>
                                                            <div class="text-danger d-none error-message" id="review-error"></div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                                    </form>
                                                    @else
                                                        <div class="alert alert-info mt-3">
                                                            Please <a href="{{ route('login') }}">login</a> to write a review.
                                                        </div>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            
            // Get quantity from either desktop or mobile input
            const desktopInput = document.querySelector('.col-lg-4:last-child input[name="quantity"]');
            const mobileInput = document.querySelector('.mobile-quantity');
            const quantityInput = desktopInput || mobileInput;
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
        }
        
        // Wishlist functionality
        function toggleWishlist(productId) {
            
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
        }
        function toggleCompare(productId) {
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
        }
        function buyNow(productId) {
            // Get quantity from either desktop or mobile input
            const desktopInput = document.querySelector('.col-lg-4:last-child input[name="quantity"]');
            const mobileInput = document.querySelector('.mobile-quantity');
            const quantityInput = desktopInput || mobileInput;
            const quantity = quantityInput ? quantityInput.value : 1;
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
            
            // Get all quantity inputs (desktop and mobile)
            const desktopQuantityInput = document.querySelector('.col-lg-4:last-child input[name="quantity"]');
            const mobileQuantityInput = document.querySelector('.mobile-quantity');
            const desktopMinusBtn = document.querySelector('.col-lg-4:last-child .quantity-left-minus');
            const desktopPlusBtn = document.querySelector('.col-lg-4:last-child .quantity-right-plus');
            const mobileMinusBtn = document.querySelector('.mobile-qty-minus');
            const mobilePlusBtn = document.querySelector('.mobile-qty-plus');
            
            const maxStock = {{ $product->manage_stock ? ($product->stock_quantity ?: 999) : 999 }};
            
            // Function to sync quantity inputs
            function syncQuantity(value) {
                if (desktopQuantityInput) desktopQuantityInput.value = value;
                if (mobileQuantityInput) mobileQuantityInput.value = value;
            }
            
            // Desktop minus button
            if (desktopMinusBtn) {
                desktopMinusBtn.addEventListener('click', function() {
                    let currentValue = parseInt(desktopQuantityInput.value) || 1;
                    if (currentValue > 1) {
                        syncQuantity(currentValue - 1);
                    }
                });
            }
            
            // Desktop plus button
            if (desktopPlusBtn) {
                desktopPlusBtn.addEventListener('click', function() {
                    let currentValue = parseInt(desktopQuantityInput.value) || 1;
                    if (currentValue < maxStock) {
                        syncQuantity(currentValue + 1);
                    } else {
                        showNotification('Maximum available quantity is ' + maxStock, 'info');
                    }
                });
            }
            
            // Mobile minus button
            if (mobileMinusBtn) {
                mobileMinusBtn.addEventListener('click', function() {
                    let currentValue = parseInt(mobileQuantityInput.value) || 1;
                    if (currentValue > 1) {
                        syncQuantity(currentValue - 1);
                    }
                });
            }
            
            // Mobile plus button
            if (mobilePlusBtn) {
                mobilePlusBtn.addEventListener('click', function() {
                    let currentValue = parseInt(mobileQuantityInput.value) || 1;
                    if (currentValue < maxStock) {
                        syncQuantity(currentValue + 1);
                    } else {
                        showNotification('Maximum available quantity is ' + maxStock, 'info');
                    }
                });
            }
            
            // Desktop input change
            if (desktopQuantityInput) {
                desktopQuantityInput.addEventListener('input', function() {
                    let value = parseInt(this.value) || 1;
                    if (value < 1) {
                        value = 1;
                    } else if (value > maxStock) {
                        value = maxStock;
                        showNotification('Maximum available quantity is ' + maxStock, 'info');
                    }
                    syncQuantity(value);
                });
                
                desktopQuantityInput.addEventListener('keypress', function(e) {
                    if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
            }
            
            // Mobile input change
            if (mobileQuantityInput) {
                mobileQuantityInput.addEventListener('input', function() {
                    let value = parseInt(this.value) || 1;
                    if (value < 1) {
                        value = 1;
                    } else if (value > maxStock) {
                        value = maxStock;
                        showNotification('Maximum available quantity is ' + maxStock, 'info');
                    }
                    syncQuantity(value);
                });
                
                mobileQuantityInput.addEventListener('keypress', function(e) {
                    if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
            }
            if (isAuthenticated) {
                loadUserPreferences();
            }
        });
        // Review stars interaction
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#rating-stars .rating-star');
            const ratingInput = document.getElementById('rating');
            function paintStars(val){
                stars.forEach((s, idx) => {
                    if (idx < val) { s.classList.add('text-warning'); s.classList.remove('ri-star-line'); s.classList.add('ri-star-fill'); }
                    else { s.classList.remove('text-warning'); s.classList.remove('ri-star-fill'); s.classList.add('ri-star-line'); }
                });
            }
            stars.forEach(star => {
                star.addEventListener('click', function(){
                    const val = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = val;
                    paintStars(val);
                    document.getElementById('rating-error')?.classList.add('d-none');
                });
            });
        });

        // Review submit via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('review-form');
            if (!form) return;
            form.addEventListener('submit', function(e){
                e.preventDefault();
                // clear errors
                document.querySelectorAll('#review-form .error-message').forEach(el=>{ el.classList.add('d-none'); el.textContent='';});
                document.querySelectorAll('#review-form .form-control').forEach(el=> el.classList.remove('is-invalid'));
                const formData = new FormData(form);
                fetch(form.getAttribute('action'), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept':'application/json' },
                    body: formData
                }).then(async (res) => {
                    const ct = res.headers.get('content-type')||'';
                    const isJson = ct.includes('application/json');
                    const data = isJson ? await res.json() : {};
                    if (res.status === 401 && data.redirect) { window.location.href = data.redirect; return; }
                    if (res.ok && data.success) {
                        showNotification(data.message || 'Review submitted', 'success');
                        window.location.reload();
                    } else if (res.status === 422 && data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const id = field.replace(/\./g,'_');
                            const errDiv = document.getElementById(id+'-error');
                            const input = document.getElementById(id);
                            if (errDiv) { errDiv.classList.remove('d-none'); errDiv.textContent = messages[0]; }
                            if (input) { input.classList.add('is-invalid'); }
                        }
                    } else {
                        showNotification(data.message || 'Could not submit review', 'error');
                    }
                }).catch(() => showNotification('Could not submit review', 'error'));
            });
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
        
        // Variant selection handler
        function selectVariant(button) {
            // Remove active class from all variant buttons in the same group
            const groupButtons = button.closest('.variant-options').querySelectorAll('.variant-btn');
            groupButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to selected button
            button.classList.add('active');
            
            // Update variant info display
            updateVariantInfo(button);
        }

        function selectVariantRadio(wrapper) {
            // Remove active class from all radio wrappers in the same group
            const groupWrappers = wrapper.closest('.variant-options').querySelectorAll('.variant-radio-wrapper');
            groupWrappers.forEach(w => w.classList.remove('active'));
            
            // Add active class to selected wrapper
            wrapper.classList.add('active');
            
            // Check the radio input
            const radio = wrapper.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update variant info display
            updateVariantInfo(radio);
        }

        function selectVariantFromDropdown(select) {
            const selectedOption = select.options[select.selectedIndex];
            updateVariantInfo(selectedOption);
        }

        function updateVariantInfo(element) {
            const variantId = element.getAttribute('data-variant-id');
            const variantSku = element.getAttribute('data-variant-sku');
            const variantStock = element.getAttribute('data-variant-stock');
            const variantPrice = element.getAttribute('data-variant-price');
            
            // Update hidden input
            document.getElementById('selected-variant-id').value = variantId;
            
            // Update SKU and stock display
            document.getElementById('variant-sku').textContent = variantSku;
            document.getElementById('variant-stock').textContent = variantStock;
            
            // Update price display if variant has different price
            if (variantPrice) {
                const priceElement = document.querySelector('.price-text h3');
                if (priceElement) {
                    priceElement.innerHTML = '<span class="fw-normal">MRP:</span> ৳' + parseFloat(variantPrice).toFixed(2);
                }
            }
            
            // Store selected variant ID globally for cart
            window.selectedVariantId = variantId;
        }
        
        // Initialize default variant selection on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for active button variant
            const defaultVariantBtn = document.querySelector('.variant-btn.active');
            if (defaultVariantBtn) {
                window.selectedVariantId = defaultVariantBtn.getAttribute('data-variant-id');
            }
            
            // Check for checked radio variant
            const defaultRadio = document.querySelector('input[type="radio"]:checked');
            if (defaultRadio) {
                window.selectedVariantId = defaultRadio.getAttribute('data-variant-id');
            }
            
            // Check for selected dropdown variant
            const defaultDropdown = document.querySelector('.variant-dropdown');
            if (defaultDropdown) {
                const selectedOption = defaultDropdown.options[defaultDropdown.selectedIndex];
                window.selectedVariantId = selectedOption.getAttribute('data-variant-id');
            }
        });
    </script>
@endsection
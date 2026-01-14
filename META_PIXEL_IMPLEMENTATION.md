# Facebook Meta Pixel Implementation Guide

## Overview
This implementation provides a complete, production-ready Facebook Meta Pixel integration for your Laravel e-commerce application. It supports both browser-side tracking and server-side events via Meta's Conversion API.

## Features
✅ **Admin Settings Panel** - Easy configuration through admin interface  
✅ **Browser-Side Tracking** - Standard Meta Pixel JavaScript implementation  
✅ **Server-Side Events** - Meta Conversion API for enhanced tracking  
✅ **Standard E-commerce Events** - ViewContent, AddToCart, Purchase, etc.  
✅ **Privacy Compliant** - User data hashing as per Meta requirements  
✅ **Helper Functions** - Quick and easy event tracking  
✅ **Service Class** - Clean, reusable architecture  

---

## Setup Instructions

### 1. Configure Meta Pixel in Admin Panel

1. Navigate to **Admin → Settings**
2. Scroll to **Facebook Meta Pixel** section
3. Configure the following:
   - **Enable Meta Pixel**: Toggle ON
   - **Meta Pixel ID**: Your pixel ID (e.g., `1234567890`)
   - **Access Token**: (Optional) For server-side events only

**Where to find your Pixel ID:**
- Go to [Facebook Events Manager](https://business.facebook.com/events_manager2)
- Select your pixel
- Copy the Pixel ID from the top

**Where to get Access Token:**
- Go to Events Manager → Settings → Conversions API
- Generate a new access token
- Use this for server-side tracking

### 2. Run Composer Autoload

After setup, regenerate the autoload files:

```bash
composer dump-autoload
```

---

## Usage Examples

### Basic Page Tracking

The base pixel is automatically loaded on all admin pages. It tracks:
- **PageView** - Automatically on every page load

### Product View Tracking

**In your product detail blade view:**

```blade
@extends('layouts.app')

@section('content')
    <!-- Your product details -->
    <div class="product-details">
        <h1>{{ $product->name }}</h1>
        <p>Price: {{ $product->price }} BDT</p>
    </div>
@endsection

@push('scripts')
    {{-- Track ViewContent event --}}
    {!! pixel_view_content($product) !!}
@endpush
```

### Add to Cart Tracking

**In your cart controller:**

```php
public function add(Request $request, Product $product)
{
    // Add to cart logic
    $cartItem = Cart::add($product);
    
    // Track server-side event (optional but recommended)
    track_pixel_event('AddToCart', meta_pixel()->formatProductData($product), [
        'email' => auth()->user()?->email,
        'phone' => auth()->user()?->phone,
    ]);
    
    // Return response with browser-side tracking
    return response()->json([
        'success' => true,
        'pixel_script' => pixel_add_to_cart($product), // Include this in response
    ]);
}
```

**In your JavaScript (for AJAX cart):**

```javascript
// After successful add to cart
fetch('/cart/add', {
    method: 'POST',
    // ... your data
})
.then(response => response.json())
.then(data => {
    if (data.pixel_script) {
        // Execute pixel tracking script
        const script = document.createElement('div');
        script.innerHTML = data.pixel_script;
        document.body.appendChild(script);
    }
});
```

### Checkout Initiation Tracking

**In your checkout page blade:**

```blade
@extends('layouts.app')

@section('content')
    <!-- Checkout form -->
@endsection

@push('scripts')
    {{-- Track InitiateCheckout event --}}
    {!! pixel_initiate_checkout($cartItems) !!}
@endpush
```

### Purchase Tracking

**In your order success/confirmation page:**

```blade
@extends('layouts.app')

@section('content')
    <div class="order-success">
        <h1>Thank You!</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>
@endsection

@push('scripts')
    {{-- Track Purchase event --}}
    {!! pixel_purchase($order) !!}
@endpush
```

**Server-side tracking in OrderController:**

```php
public function confirmOrder(Order $order)
{
    // Mark order as paid
    $order->update(['payment_status' => 'paid']);
    
    // Track purchase via Conversion API
    track_pixel_event('Purchase', meta_pixel()->formatOrderData($order), [
        'email' => $order->email,
        'phone' => $order->phone,
        'first_name' => $order->first_name,
        'last_name' => $order->last_name,
        'city' => $order->city,
        'country' => 'BD',
    ]);
    
    return view('orders.success', compact('order'));
}
```

### Search Tracking

**In your search results page:**

```blade
@extends('layouts.app')

@section('content')
    <h1>Search Results for "{{ $query }}"</h1>
    <!-- Results -->
@endsection

@push('scripts')
    {!! pixel_search($query) !!}
@endpush
```

---

## Available Helper Functions

### Browser-Side Tracking Helpers

These return JavaScript code to be included in your blade views:

```php
// Track product view
pixel_view_content($product);

// Track add to cart
pixel_add_to_cart($product);

// Track checkout initiation
pixel_initiate_checkout($cartItems);

// Track purchase
pixel_purchase($order);

// Track search
pixel_search('search query');
```

### Server-Side Tracking Helper

```php
// Generic server event tracking
track_pixel_event(
    'EventName',           // Event name (e.g., 'Purchase', 'ViewContent')
    ['value' => 100],      // Event data
    ['email' => 'user@example.com']  // User data (automatically hashed)
);
```

### Service Instance

```php
// Get service instance for advanced usage
$metaPixel = meta_pixel();

// Check if enabled
if ($metaPixel->isEnabled()) {
    // Do something
}

// Format data helpers
$productData = $metaPixel->formatProductData($product);
$orderData = $metaPixel->formatOrderData($order);
$cartData = $metaPixel->formatCartData($cartItems);
```

---

## Standard Event Data Formats

### ViewContent Event
```php
[
    'content_ids' => ['123'],        // Product IDs
    'content_type' => 'product',
    'content_name' => 'Product Name',
    'value' => 99.99,                // Product price
    'currency' => 'BDT',
]
```

### AddToCart Event
```php
[
    'content_ids' => ['123'],
    'content_type' => 'product',
    'value' => 99.99,
    'currency' => 'BDT',
]
```

### InitiateCheckout Event
```php
[
    'content_ids' => ['123', '456'], // All cart product IDs
    'content_type' => 'product',
    'value' => 299.99,               // Total cart value
    'currency' => 'BDT',
    'num_items' => 2,                // Cart item count
]
```

### Purchase Event
```php
[
    'content_ids' => ['123', '456'],
    'content_type' => 'product',
    'value' => 299.99,               // Order total
    'currency' => 'BDT',
    'num_items' => 2,
]
```

---

## User Data Privacy & Hashing

All user data sent via Conversion API is automatically hashed according to Meta's requirements:

### Automatically Hashed Fields:
- Email (em)
- Phone (ph)
- First Name (fn)
- Last Name (ln)
- City (ct)
- State (st)
- Country (country)
- Zip Code (zp)

### Not Hashed (as per Meta spec):
- Client IP Address
- Client User Agent
- Facebook Click ID (fbc)
- Facebook Browser ID (fbp)

---

## Testing Your Implementation

### 1. Test Events Manager
- Go to [Facebook Events Manager](https://business.facebook.com/events_manager2)
- Select your pixel
- Check the "Test Events" tab
- Browse your site and verify events appear in real-time

### 2. Meta Pixel Helper Chrome Extension
- Install [Meta Pixel Helper](https://chrome.google.com/webstore/detail/meta-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc)
- Browse your site
- The extension icon will show when pixel fires

### 3. Check Browser Console
- Open browser DevTools
- Look for `fbq` calls in Console
- Network tab should show requests to `facebook.com/tr`

---

## Troubleshooting

### Pixel Not Firing

1. **Check Settings**
   - Admin → Settings → Verify "Enable Meta Pixel" is ON
   - Verify Pixel ID is correct

2. **Check Browser Console**
   - Look for JavaScript errors
   - Verify `fbq` function exists: `typeof fbq` should return `"function"`

3. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

### Server Events Not Sending

1. **Verify Access Token**
   - Access token is required for Conversion API
   - Test token in Meta's [API Test Tool](https://developers.facebook.com/tools/explorer/)

2. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   - Look for "Meta Pixel" log entries

3. **Test API Connection**
   ```php
   // In tinker
   php artisan tinker
   
   meta_pixel()->sendServerEvent('Test', ['test' => true]);
   ```

### Events Not Showing in Events Manager

- Wait 20-30 minutes for data to process
- Check "Test Events" tab for real-time data
- Verify pixel is not in "error" state

---

## Best Practices

### ✅ DO:
- Use both browser-side AND server-side tracking for critical events (Purchase)
- Track purchases server-side to avoid ad-blockers
- Include as much user data as possible (email, phone) for better matching
- Test thoroughly before going to production
- Use the Test Events tab during development

### ❌ DON'T:
- Send user data unencrypted (service handles hashing automatically)
- Track admin actions (pixel is only for customer-facing pages)
- Send duplicate events (avoid double tracking)
- Forget to handle AJAX cart operations

---

## Advanced: Custom Events

For custom events not covered by standard helpers:

```php
// Browser-side custom event
@push('scripts')
<script>
    if (typeof fbq !== 'undefined') {
        fbq('trackCustom', 'MyCustomEvent', {
            custom_param: 'value'
        });
    }
</script>
@endpush

// Server-side custom event
track_pixel_event('MyCustomEvent', [
    'custom_param' => 'value',
], [
    'email' => 'user@example.com',
]);
```

---

## Architecture

### Files Created/Modified:

1. **Service**: `app/Services/MetaPixelService.php`
   - Core pixel functionality
   - API communication
   - Data formatting

2. **Helpers**: `app/Helpers/MetaPixelHelper.php`
   - Quick access functions
   - Auto-loaded via composer

3. **Component**: `resources/views/components/meta-pixel.blade.php`
   - Reusable blade component
   - Handles pixel script rendering

4. **Settings**: `app/Http/Controllers/Admin/SettingController.php`
   - Admin configuration
   - Pixel ID and token management

5. **Layout**: `resources/views/admin/components/header.blade.php`
   - Includes pixel component

---

## Support & Resources

- [Meta Pixel Documentation](https://developers.facebook.com/docs/meta-pixel)
- [Conversion API Documentation](https://developers.facebook.com/docs/marketing-api/conversions-api)
- [Standard Events Reference](https://developers.facebook.com/docs/meta-pixel/reference)
- [Events Manager](https://business.facebook.com/events_manager2)

---

## Changelog

**Version 1.0** - Initial Implementation
- Browser-side pixel tracking
- Server-side Conversion API
- Standard e-commerce events
- Admin settings panel
- Helper functions
- Privacy-compliant user data hashing

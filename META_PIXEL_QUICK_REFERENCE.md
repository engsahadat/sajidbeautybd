# Meta Pixel Quick Reference Card

## Setup Checklist

- [ ] Navigate to Admin → Settings
- [ ] Enable "Meta Pixel" toggle
- [ ] Enter your Pixel ID
- [ ] (Optional) Enter Access Token for server events
- [ ] Save settings
- [ ] Run `composer dump-autoload`
- [ ] Test with Meta Pixel Helper browser extension

---

## Quick Code Snippets

### In Blade Views (Browser-side)

```blade
{{-- Product Detail Page --}}
@push('scripts')
    {!! pixel_view_content($product) !!}
@endpush

{{-- Checkout Page --}}
@push('scripts')
    {!! pixel_initiate_checkout($cartItems) !!}
@endpush

{{-- Order Success Page --}}
@push('scripts')
    {!! pixel_purchase($order) !!}
@endpush

{{-- Search Results --}}
@push('scripts')
    {!! pixel_search($searchQuery) !!}
@endpush
```

### In Controllers (Server-side)

```php
// Track purchase (bypasses ad blockers!)
track_pixel_event('Purchase', 
    meta_pixel()->formatOrderData($order),
    [
        'email' => $order->email,
        'phone' => $order->phone,
        'first_name' => $order->first_name,
        'last_name' => $order->last_name,
        'city' => $order->city,
        'country' => 'BD',
    ]
);

// Track AddToCart
track_pixel_event('AddToCart',
    meta_pixel()->formatProductData($product),
    ['email' => auth()->user()?->email]
);
```

### AJAX Response with Tracking

```php
// In your controller
return response()->json([
    'success' => true,
    'message' => 'Product added',
    'pixel_script' => pixel_add_to_cart($product),
]);
```

```javascript
// In your JavaScript
fetch('/cart/add', {/*...*/})
    .then(res => res.json())
    .then(data => {
        if (data.pixel_script) {
            const div = document.createElement('div');
            div.innerHTML = data.pixel_script;
            document.body.appendChild(div);
        }
    });
```

---

## Standard Events

| Event | When to Use | Helper Function |
|-------|-------------|-----------------|
| **PageView** | Automatic on all pages | N/A (auto) |
| **ViewContent** | Product detail view | `pixel_view_content($product)` |
| **AddToCart** | Item added to cart | `pixel_add_to_cart($product)` |
| **InitiateCheckout** | Checkout page loaded | `pixel_initiate_checkout($cartItems)` |
| **Purchase** | Order completed | `pixel_purchase($order)` |
| **Search** | Search performed | `pixel_search($query)` |

---

## Helper Functions

```php
// Get service instance
$pixel = meta_pixel();

// Check if enabled
if (meta_pixel()->isEnabled()) { }

// Format helpers
$productData = meta_pixel()->formatProductData($product);
$orderData = meta_pixel()->formatOrderData($order);
$cartData = meta_pixel()->formatCartData($cartItems);

// Server-side tracking
track_pixel_event('EventName', $eventData, $userData);

// Browser-side tracking scripts
pixel_view_content($product);
pixel_add_to_cart($product);
pixel_purchase($order);
pixel_initiate_checkout($cartItems);
pixel_search($query);
```

---

## Testing

### 1. Browser Console
```javascript
// Check if pixel loaded
typeof fbq  // Should return "function"

// Manual test
fbq('track', 'ViewContent', {value: 100, currency: 'BDT'});
```

### 2. Meta Tools
- **Pixel Helper Extension**: Shows real-time pixel fires
- **Events Manager**: https://business.facebook.com/events_manager2
- **Test Events Tab**: See events in real-time (20-30 second delay)

### 3. Laravel Logs
```bash
tail -f storage/logs/laravel.log | grep "Meta Pixel"
```

---

## Common Patterns

### Product Page
```blade
@extends('layouts.app')
@section('content')
    <!-- Product details -->
@endsection
@push('scripts')
    {!! pixel_view_content($product) !!}
@endpush
```

### AJAX Cart
```php
// Controller
public function add(Product $product) {
    // Add to cart logic...
    
    track_pixel_event('AddToCart', 
        meta_pixel()->formatProductData($product)
    );
    
    return response()->json([
        'success' => true,
        'pixel_script' => pixel_add_to_cart($product),
    ]);
}
```

### Order Completion
```php
// Controller
public function success(Order $order) {
    // Server-side (recommended)
    track_pixel_event('Purchase', 
        meta_pixel()->formatOrderData($order),
        ['email' => $order->email, 'phone' => $order->phone]
    );
    
    return view('orders.success', compact('order'));
}
```

```blade
{{-- View --}}
@push('scripts')
    {!! pixel_purchase($order) !!}
@endpush
```

---

## Data Formats

### Product Event
```php
[
    'content_ids' => ['123'],
    'content_type' => 'product',
    'value' => 99.99,
    'currency' => 'BDT',
]
```

### Purchase Event
```php
[
    'content_ids' => ['123', '456'],
    'content_type' => 'product',
    'value' => 299.99,
    'currency' => 'BDT',
    'num_items' => 2,
]
```

### User Data (Auto-hashed)
```php
[
    'email' => 'user@example.com',
    'phone' => '01712345678',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'city' => 'Dhaka',
    'country' => 'BD',
]
```

---

## Best Practices

✅ **DO:**
- Track Purchase server-side to avoid ad blockers
- Include user data (email, phone) for better matching
- Test with Pixel Helper extension
- Use both browser + server tracking for critical events

❌ **DON'T:**
- Track admin actions
- Send duplicate events
- Forget to handle AJAX responses
- Skip testing before production

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Pixel not loading | Check Settings → Enable is ON, Pixel ID is correct |
| Events not firing | Check browser console for errors, verify fbq exists |
| Server events failing | Check Access Token, review Laravel logs |
| Duplicate events | Use either browser OR server tracking, not both |

---

## Files Reference

- **Service**: `app/Services/MetaPixelService.php`
- **Helpers**: `app/Helpers/MetaPixelHelper.php`
- **Component**: `resources/views/components/meta-pixel.blade.php`
- **Settings**: Admin → Settings → Facebook Meta Pixel
- **Docs**: `META_PIXEL_IMPLEMENTATION.md`
- **Examples**: `app/Http/Controllers/Examples/MetaPixelExamples.php`

---

## Support Links

- [Meta Pixel Docs](https://developers.facebook.com/docs/meta-pixel)
- [Events Manager](https://business.facebook.com/events_manager2)
- [Conversion API](https://developers.facebook.com/docs/marketing-api/conversions-api)
- [Standard Events](https://developers.facebook.com/docs/meta-pixel/reference)

---

**Version 1.0** | Created: {{ date('Y-m-d') }}

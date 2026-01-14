<?php

/**
 * META PIXEL INTEGRATION EXAMPLES
 * 
 * These are practical examples showing how to integrate Meta Pixel tracking
 * into your existing Laravel controllers and views.
 */

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Example: Product Controller with Meta Pixel Integration
 */
class ProductController extends Controller
{
    /**
     * Show product details page
     * 
     * In the view (product.show.blade.php):
     * @push('scripts')
     *     {!! pixel_view_content($product) !!}
     * @endpush
     */
    public function show(Product $product)
    {
        // Your existing logic
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        // Optional: Track server-side as well
        if (meta_pixel()->isEnabled()) {
            track_pixel_event('ViewContent', meta_pixel()->formatProductData($product), [
                'email' => Auth::user()?->email,
                'phone' => Auth::user()?->phone,
            ]);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Search products
     * 
     * In the view (products.search.blade.php):
     * @push('scripts')
     *     {!! pixel_search($query) !!}
     * @endpush
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(20);

        return view('products.search', compact('products', 'query'));
    }
}

/**
 * Example: Cart Controller with Meta Pixel Integration
 */
class CartController extends Controller
{
    /**
     * Add product to cart (AJAX)
     * 
     * Frontend JavaScript should execute the returned pixel_script:
     * 
     * fetch('/cart/add', {...})
     *   .then(res => res.json())
     *   .then(data => {
     *     if (data.pixel_script) {
     *       const div = document.createElement('div');
     *       div.innerHTML = data.pixel_script;
     *       document.body.appendChild(div);
     *     }
     *   });
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Add to cart logic
        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'price' => $product->price,
        ]);

        // Track server-side event
        if (meta_pixel()->isEnabled()) {
            track_pixel_event('AddToCart', [
                'content_ids' => [(string) $product->id],
                'content_type' => 'product',
                'value' => (float) $product->price * $validated['quantity'],
                'currency' => 'BDT',
            ], [
                'email' => Auth::user()?->email,
                'phone' => Auth::user()?->phone,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => CartItem::where('user_id', Auth::id())->sum('quantity'),
            'pixel_script' => pixel_add_to_cart($product), // Browser-side tracking
        ]);
    }

    /**
     * Show cart page
     * No pixel tracking needed - just display
     */
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }
}

/**
 * Example: Checkout Controller with Meta Pixel Integration
 */
class CheckoutController extends Controller
{
    /**
     * Show checkout page
     * 
     * In the view (checkout.index.blade.php):
     * @push('scripts')
     *     {!! pixel_initiate_checkout($cartItems) !!}
     * @endpush
     */
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Optional: Track server-side
        if (meta_pixel()->isEnabled()) {
            track_pixel_event('InitiateCheckout', meta_pixel()->formatCartData($cartItems), [
                'email' => Auth::user()?->email,
                'phone' => Auth::user()?->phone,
            ]);
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip' => 'nullable|string|max:20',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . time(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'zip' => $validated['zip'],
            'total_amount' => $cartItems->sum(fn($i) => $i->quantity * $i->price),
            'payment_status' => 'pending',
        ]);

        // Add order items
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Clear cart
        $cartItems->each->delete();

        // Redirect to payment
        return redirect()->route('payment.select', $order);
    }
}

/**
 * Example: Order/Payment Completion with Meta Pixel Integration
 */
class OrderController extends Controller
{
    /**
     * Show order success page after payment
     * 
     * In the view (orders.success.blade.php):
     * @push('scripts')
     *     {!! pixel_purchase($order) !!}
     * @endpush
     */
    public function success(Order $order)
    {
        // Verify order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Track purchase server-side (IMPORTANT: Do this!)
        if (meta_pixel()->isEnabled() && $order->payment_status === 'paid') {
            track_pixel_event('Purchase', meta_pixel()->formatOrderData($order), [
                'email' => $order->email,
                'phone' => $order->phone,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'city' => $order->city,
                'zip' => $order->zip,
                'country' => 'BD',
            ]);
        }

        return view('orders.success', compact('order'));
    }

    /**
     * Payment callback (e.g., from bKash)
     */
    public function paymentCallback(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        // Verify payment with payment gateway
        $paymentResult = app(\App\Services\PaymentGateway\BkashService::class)
            ->execute($request->input('paymentID'));

        if ($paymentResult['success']) {
            // Mark order as paid
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $paymentResult['transaction_id'],
            ]);

            // Track purchase (server-side - bypasses ad blockers!)
            if (meta_pixel()->isEnabled()) {
                track_pixel_event('Purchase', meta_pixel()->formatOrderData($order), [
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'city' => $order->city,
                    'country' => 'BD',
                ]);
            }

            return redirect()->route('orders.success', $order);
        }

        return redirect()->route('orders.failed', $order)
            ->with('error', 'Payment failed. Please try again.');
    }
}

/**
 * Example: Admin Order Management
 * (Don't track admin actions!)
 */
class AdminOrderController extends Controller
{
    /**
     * Admin order list - NO pixel tracking
     */
    public function index()
    {
        // No pixel tracking in admin
        $orders = Order::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }
}

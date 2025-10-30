<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\Coupon;
use App\Models\WishlistItem;
use App\Models\Compare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected function getOrCreateCart(): ShoppingCart
    {
        $sessionId = session()->getId();
    $userId = Auth::id();
        $cart = ShoppingCart::forCurrent()->first();
        if (!$cart) {
            $cart = ShoppingCart::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
            ]);
        } else if ($userId && !$cart->user_id) {
            // Attach session cart to user when logged in
            $cart->user_id = $userId;
            $cart->session_id = null;
            $cart->save();
        }
        return $cart->load('items.product');
    }

    public function index()
    {
        $cart = ShoppingCart::forCurrent()->with('items.product')->first();
        return view('front-end.cart.index', [ 'cart' => $cart ]);
    }

    public function add(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add items to cart',
                    'redirect' => route('login')
                ], 401);
            }

            $data = $request->validate([
                'product_id' => ['required','integer','exists:products,id'],
                'quantity' => ['nullable','integer','min:1'],
            ]);
            
            $qty = (int)($data['quantity'] ?? 1);
            $cart = $this->getOrCreateCart();

            $product = Product::findOrFail($data['product_id']);
            $unitPrice = (float)$product->price;

            $item = $cart->items()->where('product_id', $product->id)->first();

            if ($item) {
                $item->quantity += $qty;
                $item->unit_price = $unitPrice; // ensure latest price
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            $cartCount = $cart->items()->sum('quantity');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully',
                    'cart_count' => $cartCount
                ]);
            }

            return back()->with('message','Added to cart');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while adding to cart'
                ], 500);
            }
            return back()->with('error', 'An error occurred while adding to cart');
        }
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => ['required','integer','min:1']]);
        // Ensure item belongs to current cart
        $cart = ShoppingCart::forCurrent()->first();
        if (!$cart || $item->cart_id !== $cart->id) {
            return back()->with('error','Not allowed');
        }
        $item->quantity = (int)$request->input('quantity');
        $item->save();
        return back()->with('message','Cart updated');
    }

    public function remove(CartItem $item)
    {
        $cart = ShoppingCart::forCurrent()->first();
        if (!$cart || $item->cart_id !== $cart->id) {
            return back()->with('error','Not allowed');
        }
        $item->delete();
        return back()->with('message','Item removed');
    }

    public function clear()
    {
        $cart = ShoppingCart::forCurrent()->first();
        if ($cart) {
            $cart->items()->delete();
        }
        return back()->with('message','Cart cleared');
    }

    /** Apply a coupon code to the current cart */
    public function applyCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:50']
        ]);

        $cart = ShoppingCart::forCurrent()->with('items')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error','Your cart is empty.');
        }

        $coupon = Coupon::where('code', $data['code'])->first();
        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }
        if (!$coupon->isActive()) {
            return back()->with('error', 'This coupon is not active.');
        }
        $subtotal = $cart->subtotal();
        if ($coupon->minimum_amount && $subtotal < (float)$coupon->minimum_amount) {
            return back()->with('error', 'Minimum order amount not met for this coupon.');
        }

        // Optionally enforce per-customer limits (skipped for brevity unless there is a pivot)
        $discount = $coupon->discountForTotal($subtotal);
        if ($discount <= 0) {
            return back()->with('error', 'This coupon does not apply to your cart.');
        }

        $cart->coupon()->associate($coupon);
        $cart->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied',
                'coupon' => ['code' => $coupon->code, 'id' => $coupon->id],
                'subtotal' => $cart->subtotal(),
                'discount' => $cart->discount(),
                'total' => $cart->total(),
            ]);
        }

        return back()->with('message', 'Coupon applied!');
    }

    /** Remove applied coupon from the cart */
    public function removeCoupon()
    {
        $cart = ShoppingCart::forCurrent()->first();
        if ($cart && $cart->coupon_id) {
            $cart->coupon()->dissociate();
            $cart->save();
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon removed',
                    'subtotal' => $cart->subtotal(),
                    'discount' => $cart->discount(),
                    'total' => $cart->total(),
                ]);
            }
            return back()->with('message', 'Coupon removed.');
        }
        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'No coupon to remove.'], 400);
        }
        return back()->with('error', 'No coupon to remove.');
    }

    /**
     * Toggle wishlist item
     */
    public function toggleWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage wishlist',
                'redirect' => route('login')
            ], 401);
        }

        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id']
        ]);

        $userId = Auth::id();
        $productId = $data['product_id'];

        try {
            $existingItem = WishlistItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                $existingItem->delete();
                $action = 'removed';
                $message = 'Product removed from wishlist';
            } else {
                WishlistItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
                $action = 'added';
                $message = 'Product added to wishlist';
            }

            $wishlistCount = WishlistItem::where('user_id', $userId)->count();

            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'wishlist_count' => $wishlistCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update wishlist'
            ], 500);
        }
    }

    /**
     * Toggle compare item
     */
    public function toggleCompare(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to manage compare list',
                'redirect' => route('login')
            ], 401);
        }

        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id']
        ]);

        $userId = Auth::id();
        $productId = $data['product_id'];

        try {
            $existingItem = Compare::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingItem) {
                $existingItem->delete();
                $action = 'removed';
                $message = 'Product removed from compare list';
            } else {
                // Limit compare list to 4 items
                $compareCount = Compare::where('user_id', $userId)->count();
                if ($compareCount >= 4) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only compare up to 4 products at a time'
                    ], 400);
                }

                Compare::create([
                    'user_id' => $userId,
                    'product_id' => $productId
                ]);
                $action = 'added';
                $message = 'Product added to compare list';
            }

            $compareCount = Compare::where('user_id', $userId)->count();

            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'compare_count' => $compareCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update compare list'
            ], 500);
        }
    }

    /**
     * Get user preferences for a specific product
     */
    public function getUserPreferences(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to view preferences'
            ], 401);
        }

        $productId = $request->query('product_id');
        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        $userId = Auth::id();

        $inWishlist = WishlistItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        $inCompare = Compare::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
            'in_compare' => $inCompare
        ]);
    }

    /**
     * Display wishlist page
     */
    public function wishlist()
    {
        $wishlistItems = WishlistItem::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('front-end.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Display compare page
     */
    public function compare()
    {
        $compareItems = Compare::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('front-end.compare.index', compact('compareItems'));
    }

    /**
     * Get cart count
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }

        $cart = ShoppingCart::forCurrent()->first();
        $count = $cart ? $cart->items()->sum('quantity') : 0;

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get wishlist count
     */
    public function getWishlistCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }

        $count = WishlistItem::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get compare count
     */
    public function getCompareCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }

        $count = Compare::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get wishlist items for current user
     */
    public function getWishlistItems()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login'
            ], 401);
        }

        $wishlistItems = WishlistItem::where('user_id', Auth::id())
            ->select('product_id')
            ->get();

        return response()->json([
            'success' => true,
            'wishlist_items' => $wishlistItems
        ]);
    }

    /**
     * Get compare items for current user
     */
    public function getCompareItems()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login'
            ], 401);
        }

        $compareItems = Compare::where('user_id', Auth::id())
            ->select('product_id')
            ->get();

        return response()->json([
            'success' => true,
            'compare_items' => $compareItems
        ]);
    }
}

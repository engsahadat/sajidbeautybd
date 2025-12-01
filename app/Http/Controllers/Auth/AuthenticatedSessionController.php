<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CartItem;
use App\Models\Compare;
use App\Models\ShoppingCart;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('front-end.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Get the session ID BEFORE authentication and regeneration
        $oldSessionId = session()->getId();
        
        $request->authenticate();

        // Merge guest cart, wishlist and compare items BEFORE regenerating session
        $this->mergeGuestData($oldSessionId);
        
        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Merge guest cart, wishlist and compare items into user's account
     */
    protected function mergeGuestData($sessionId): void
    {
        $userId = Auth::id();
        
        // Merge cart items
        $guestCart = ShoppingCart::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('items')
            ->first();
        
        if ($guestCart && $guestCart->items->isNotEmpty()) {
            // Get or create user's cart
            $userCart = ShoppingCart::where('user_id', $userId)->first();
            
            if (!$userCart) {
                // If user doesn't have a cart, simply assign the guest cart to the user
                $guestCart->user_id = $userId;
                $guestCart->session_id = null;
                $guestCart->save();
            } else {
                // Merge guest cart items into user's cart
                foreach ($guestCart->items as $guestItem) {
                    $existingItem = $userCart->items()
                        ->where('product_id', $guestItem->product_id)
                        ->where('variant_id', $guestItem->variant_id)
                        ->first();
                    
                    if ($existingItem) {
                        // Update quantity if item already exists
                        $existingItem->quantity += $guestItem->quantity;
                        $existingItem->save();
                    } else {
                        // Move the item to user's cart
                        $guestItem->cart_id = $userCart->id;
                        $guestItem->save();
                    }
                }
                
                // Delete the guest cart
                $guestCart->delete();
            }
        }
        
        // Merge wishlist
        $guestWishlist = session()->get('guest_wishlist', []);
        if (!empty($guestWishlist)) {
            foreach ($guestWishlist as $productId) {
                // Check if not already in wishlist
                $exists = WishlistItem::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->exists();
                
                if (!$exists) {
                    try {
                        WishlistItem::create([
                            'user_id' => $userId,
                            'product_id' => $productId
                        ]);
                    } catch (\Exception $e) {
                        // Skip if product doesn't exist or other error
                        continue;
                    }
                }
            }
            // Clear guest wishlist from session
            session()->forget('guest_wishlist');
        }
        
        // Merge compare list
        $guestCompare = session()->get('guest_compare', []);
        if (!empty($guestCompare)) {
            foreach ($guestCompare as $productId) {
                // Check if not already in compare list
                $exists = Compare::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->exists();
                
                if (!$exists) {
                    // Check limit (max 4 items)
                    $currentCount = Compare::where('user_id', $userId)->count();
                    if ($currentCount < 4) {
                        try {
                            Compare::create([
                                'user_id' => $userId,
                                'product_id' => $productId
                            ]);
                        } catch (\Exception $e) {
                            // Skip if product doesn't exist or other error
                            continue;
                        }
                    }
                }
            }
            // Clear guest compare from session
            session()->forget('guest_compare');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

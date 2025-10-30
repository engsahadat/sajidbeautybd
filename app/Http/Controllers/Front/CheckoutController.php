<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = ShoppingCart::forCurrent()->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error','Your cart is empty.');
        }
        return view('front-end.checkout.index', ['cart' => $cart]);
    }

    public function place(Request $request)
    {
        $cart = ShoppingCart::forCurrent()->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }
            return redirect()->route('cart.index')->with('error','Your cart is empty.');
        }

    $data = $request->validate([
            // Billing
            'billing_first_name' => 'required|string|max:50',
            'billing_last_name' => 'required|string|max:50',
            'billing_address_line_1' => 'required|string|max:191',
            'billing_city' => 'required|string|max:50',
            'billing_postal_code' => 'required|string|max:10',
            'billing_country' => 'required|string|size:2',
            'billing_phone' => 'nullable|string|max:20',
            // Shipping (for simplicity mirror billing; in UI let users copy billing to shipping)
            'shipping_first_name' => 'required|string|max:50',
            'shipping_last_name' => 'required|string|max:50',
            'shipping_address_line_1' => 'required|string|max:191',
            'shipping_city' => 'required|string|max:50',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_country' => 'required|string|size:2',
            'shipping_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,manual,sslcommerz,bkash',
        ]);

    $order = DB::transaction(function() use ($cart, $data) {
        $orderNumber = 'ORD-'.date('ymd').'-'.Str::upper(Str::random(6));
        $subtotal = $cart->subtotal();
        $tax = 0.00; $shipping = 0.00;
        $discount = $cart->discount();
        $total = $cart->total();

            $order = Order::create(array_merge($data, [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'currency' => 'USD',
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'payment_status' => 'pending',
            ]));

            foreach ($cart->items as $ci) {
                $unit = (float) $ci->unit_price;
                $qty = (int) $ci->quantity;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $ci->product_id,
                    'product_name' => $ci->product?->name ?? 'Product',
                    'product_sku' => $ci->product?->sku ?? 'SKU',
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'total_price' => number_format($unit * $qty, 2, '.', ''),
                ]);
            }
            return $order;
        });

        // Branch by payment method
        $method = $data['payment_method'] ?? 'cod';
        if ($method === 'cod') {
            // Record COD as completed and clear cart
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'COD',
                'gateway' => null,
                'transaction_id' => null,
                'amount' => $order->total_amount,
                'currency' => $order->currency ?? 'BDT',
                'status' => 'completed',
                'processed_at' => now(),
            ]);
            $order->refreshPaymentStatus();
            if ($cart->coupon) {
                 try { 
                    $cart->coupon->increment('used_count'); 
                } catch (\Throwable $e) {

                } 
            }
            $cart->items()->delete();
            $redirect = route('checkout.success', $order->order_number);
        } elseif (in_array($method, ['sslcommerz','bkash'])) {
            $redirect = URL::temporarySignedRoute('payment.initiate', now()->addMinutes(15), [
                'order' => $order->id,
                'gateway' => $method,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => $redirect]);
        }
        return redirect($redirect);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('front-end.checkout.success', compact('order'));
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = ShoppingCart::forCurrent()->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
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
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
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
            // Delivery
            'delivery_location' => 'required|in:inside_dhaka,outside_dhaka',
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

        $order = DB::transaction(function () use ($cart, $data) {
            $orderNumber = 'ORD-' . date('ymd') . '-' . Str::upper(Str::random(6));
            $subtotal = $cart->subtotal();
            $tax = 0.00;
            $shipping = 0.00;
            if (isset($data['delivery_location'])) {
                $shipping = $data['delivery_location'] === 'inside_dhaka' ? 60.00 : 120.00;
            }

            $discount = $cart->discount();
            $total = $subtotal - $discount + $shipping + $tax;

            $order = Order::create(array_merge($data, [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'currency' => 'BDT',
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
                    'variant_id' => $ci->variant_id,
                    'variant_details' => $ci->variant_details,
                    'product_name' => $ci->product?->name ?? 'Product',
                    'product_sku' => $ci->product?->sku ?? 'SKU',
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'total_price' => number_format($unit * $qty, 2, '.', ''),
                ]);
                if ($ci->variant_id && $ci->variant) {
                    $ci->variant->decrement('stock_quantity', $qty);
                } elseif ($ci->product && $ci->product->manage_stock) {
                    $ci->product->decrement('stock_quantity', $qty);
                }
            }
            return $order;
        });
        
        $method = $data['payment_method'] ?? 'cod';
        $redirect = null;
        
        if ($method === 'cod') {
            // For COD, keep payment status as pending - will be marked as paid on delivery
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'COD',
                'gateway' => null,
                'transaction_id' => null,
                'amount' => $order->total_amount,
                'currency' => $order->currency ?? 'BDT',
                'status' => 'pending',
                'processed_at' => null,
            ]);
            if ($cart->coupon) {
                try {
                    $cart->coupon->increment('used_count');
                } catch (\Throwable $e) {
                }
            }
            $cart->items()->delete();
            $this->sendOrderNotifications($order);
            $redirect = route('checkout.success', $order->order_number);
        } elseif (in_array($method, ['sslcommerz', 'bkash'])) {
            $redirect = URL::temporarySignedRoute('payment.initiate', now()->addMinutes(15), [
                'gateway' => $method,
                'order' => $order->id,
            ]);
        } else {
            return back()->with('error', 'Invalid payment method selected.');
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

    /**
     * Send order notifications via email and SMS
     * 
     * @param Order $order
     * @return void
     */
    protected function sendOrderNotifications(Order $order): void
    {
        $order->load('items.product', 'user');
        $brevoMail = new \App\Services\BrevoMailService();
        try {
            if ($order->user && $order->user->email) {
                $brevoMail->sendOrderConfirmation($order);
            }
        } catch (\Exception $e) {
            Log::error('Customer email failed:', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }

        // 2. Send email to shop owner
        try {
            $brevoMail->sendOrderNotificationToShop($order);
        } catch (\Exception $e) {
            Log::error('Shop owner email failed:', [
                'order_id' => $order->id,
                'shop_email' => 'sajidbeautybd@gmail.com',
                'error' => $e->getMessage(),
            ]);
        }

        // 3. Send SMS to customer
        try {
            $customerPhone = $order->billing_phone ?? $order->shipping_phone;
            if ($customerPhone) {
                $smsService = new SmsService();
                $smsService->sendOrderPlacedSms($order, $customerPhone);
            }
        } catch (\Exception $e) {
            Log::error('SMS notification failed:', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

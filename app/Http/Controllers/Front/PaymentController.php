<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Services\PaymentGateway\SSLCommerzService;
use App\Services\PaymentGateway\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Initiate payment for a given gateway and order.
     */
    public function initiate(Request $request, string $gateway, Order $order)
    {
        $gateway = strtolower($gateway);
        if (config('payment.demo', true)) {
            return $this->handleDemoPayment($order, $gateway);
        }
        try {
            $result = match($gateway) {
                'sslcommerz' => app(SSLCommerzService::class)->initiate($order),
                'bkash' => app(BkashService::class)->initiate($order),
                default => ['success' => false, 'message' => 'Invalid gateway'],
            };

            if ($result['success'] && isset($result['redirect_url'])) {
                if (isset($result['payment_id']) || isset($result['session_key']) || isset($result['payment_reference'])) {
                    Payment::create([
                        'order_id' => $order->id,
                        'payment_method' => strtoupper($gateway),
                        'gateway' => $gateway,
                        'transaction_id' => $result['payment_id'] ?? $result['session_key'] ?? $result['payment_reference'] ?? null,
                        'amount' => $order->total_amount,
                        'currency' => $order->currency ?? 'BDT',
                        'status' => 'pending',
                        'gateway_response' => json_encode($result),
                    ]);
                }
                return redirect($result['redirect_url']);
            }

            Log::error('Payment initiation failed', ['gateway' => $gateway, 'result' => $result]);
            return redirect()->route('checkout.show')
                ->with('error', $result['message'] ?? 'Payment initiation failed. Please try again.');
        } catch (\Exception $e) {
            Log::error('Payment initiation exception', [
                'gateway' => $gateway,
                'order' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('checkout.show')
                ->with('error', 'Payment system error. Please try again or choose another payment method.');
        }
    }

    /**
     * Gateway callback endpoint.
     */
    public function callback(Request $request, string $gateway)
    {
        $gateway = strtolower($gateway);
        $orderId = $request->input('order_id');

        if (!$orderId) {
            Log::error('Payment callback without order_id', ['gateway' => $gateway, 'data' => $request->all()]);
            return redirect()->route('home')->with('error', 'Invalid payment callback.');
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::error('Payment callback for non-existent order', ['order_id' => $orderId]);
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        try {
            $verified = false;
            $transactionId = null;
            $amount = null;

            switch ($gateway) {
                case 'sslcommerz':
                    $validation = app(SSLCommerzService::class)->validateCallback($request->all());
                    if ($validation['valid']) {
                        $verified = true;
                        $transactionId = $validation['transaction_id'];
                        $amount = $validation['amount'];
                    }
                    break;

                case 'bkash':
                    $paymentId = $request->input('paymentID');
                    $status = $request->input('status');
                    
                    if ($paymentId && $status === 'success') {
                        $execution = app(BkashService::class)->execute($paymentId);
                        if ($execution['success']) {
                            $verified = true;
                            $transactionId = $execution['transaction_id'];
                            $amount = $execution['amount'];
                        }
                    }
                    break;
            }

            if ($verified) {
                $payment = Payment::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'gateway' => $gateway,
                    ],
                    [
                        'payment_method' => strtoupper($gateway),
                        'transaction_id' => $transactionId,
                        'amount' => $amount ?? $order->total_amount,
                        'currency' => $order->currency ?? 'BDT',
                        'status' => 'completed',
                        'gateway_response' => json_encode($request->all()),
                        'processed_at' => now(),
                    ]
                );
                try {
                    $order->refreshPaymentStatus(); 
                } catch (\Throwable $e) {
                    Log::error('Failed to refresh payment status', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
                try {
                    $cart = ShoppingCart::where('user_id', $order->user_id)
                        ->orWhere('session_id', session()->getId())
                        ->first();
                    
                    if ($cart) {
                        if ($cart->coupon) {
                            try { $cart->coupon->increment('used_count'); } catch (\Throwable $e) {}
                        }
                        $cart->items()->delete();
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to clear cart after payment', ['error' => $e->getMessage()]);
                }
                return redirect()->route('checkout.success', $order->order_number)
                    ->with('success', 'Payment completed successfully!');
            }
            Log::warning('Payment verification failed', [
                'gateway' => $gateway,
                'order' => $order->id,
                'data' => $request->all(),
            ]);
            return redirect()->route('checkout.show')
                ->with('error', 'Payment verification failed. Please try again or contact support.');
        } catch (\Exception $e) {
            Log::error('Payment callback exception', [
                'gateway' => $gateway,
                'order' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('checkout.show')
                ->with('error', 'Payment processing error. Please contact support with order number: ' . $order->order_number);
        }
    }

    /**
     * Handle demo mode payment
     */
    protected function handleDemoPayment(Order $order, string $gateway): \Illuminate\Http\RedirectResponse
    {
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => strtoupper($gateway),
            'gateway' => $gateway,
            'transaction_id' => 'DEMO-'.uniqid(),
            'amount' => $order->total_amount,
            'currency' => $order->currency ?? 'BDT',
            'status' => 'completed',
            'processed_at' => now(),
        ]);
        try {
            $order->refreshPaymentStatus(); 
        } catch (\Throwable $e) {
            Log::error('Failed to refresh payment status', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }
        try {
            $cart = ShoppingCart::forCurrent()->first();
            if ($cart) {
                if ($cart->coupon) { try { $cart->coupon->increment('used_count'); } catch (\Throwable $e) {} }
                $cart->items()->delete();
            }
        } catch (\Throwable $e) {
            Log::error('Failed to clear cart after demo payment', ['error' => $e->getMessage()]);
        }
        return redirect()->route('checkout.success', $order->order_number);
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Services\PaymentGateway\SSLCommerzService;
use App\Services\PaymentGateway\BkashService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $order = Order::find($orderId);

        // Handle missing order
        if (!$order) {
            Log::error('Payment callback - Order not found', [
                'gateway' => $gateway,
                'order_id' => $orderId,
                'request' => $request->all(),
            ]);
            return redirect()->route('checkout.show')
                ->with('error', 'Order not found. Please try again.');
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
                    
                    // Handle failure and cancel statuses
                    if ($status === 'failure') {
                        Log::warning('bKash payment failed', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'payment_id' => $paymentId,
                            'request' => $request->all(),
                            'timestamp' => now()->toIso8601String(),
                        ]);
                        return redirect()->route('checkout.show')
                            ->with('error', 'Payment Failed. Your transaction could not be completed. Please try again or choose another payment method.');
                    }
                    
                    if ($status === 'cancel') {
                        Log::info('bKash payment cancelled by user', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'payment_id' => $paymentId,
                            'timestamp' => now()->toIso8601String(),
                        ]);
                        return redirect()->route('checkout.show')
                            ->with('warning', 'Payment Cancelled. You cancelled the payment. You can try again when ready.');
                    }
                    
                    if ($paymentId && $status === 'success') {
                        // PROTECTION LAYER 1: Check if payment already exists and is completed
                        $existingPayment = Payment::where('order_id', $order->id)
                            ->where('gateway', 'bkash')
                            ->where('status', 'completed')
                            ->first();
                        
                        if ($existingPayment) {
                            Log::info('bKash payment already completed - skipping execute API', [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'payment_id' => $paymentId,
                                'existing_transaction_id' => $existingPayment->transaction_id,
                                'timestamp' => now()->toIso8601String(),
                            ]);
                            
                            return redirect()->route('checkout.success', $order->order_number)
                                ->with('success', 'Payment completed successfully!');
                        }
                        
                        // PROTECTION LAYER 2: Database lock to prevent race conditions
                        // Use updateOrCreate with additional check to create a processing lock
                        try {
                            DB::beginTransaction();
                            
                            // Try to get or create payment record with lock
                            $payment = Payment::lockForUpdate()
                                ->where('order_id', $order->id)
                                ->where('gateway', 'bkash')
                                ->first();
                            
                            // If payment exists and is completed, another request already processed it
                            if ($payment && $payment->status === 'completed') {
                                DB::rollBack();
                                Log::info('bKash payment already completed (found during lock check)', [
                                    'order_id' => $order->id,
                                    'payment_id' => $paymentId,
                                    'transaction_id' => $payment->transaction_id,
                                    'timestamp' => now()->toIso8601String(),
                                ]);
                                
                                return redirect()->route('checkout.success', $order->order_number)
                                    ->with('success', 'Payment completed successfully!');
                            }
                            
                            // If payment exists and is processing, another request is handling it
                            if ($payment && $payment->status === 'processing') {
                                DB::rollBack();
                                Log::info('bKash payment already being processed by another request', [
                                    'order_id' => $order->id,
                                    'payment_id' => $paymentId,
                                    'timestamp' => now()->toIso8601String(),
                                ]);
                                
                                return redirect()->route('checkout.success', $order->order_number)
                                    ->with('success', 'Your payment is being processed. Please wait...');
                            }
                            
                            // Mark payment as processing to block concurrent requests
                            if ($payment) {
                                $payment->update(['status' => 'processing']);
                            } else {
                                $payment = Payment::create([
                                    'order_id' => $order->id,
                                    'payment_method' => 'BKASH',
                                    'gateway' => 'bkash',
                                    'transaction_id' => $paymentId,
                                    'amount' => $order->total_amount,
                                    'currency' => $order->currency ?? 'BDT',
                                    'status' => 'processing',
                                ]);
                            }
                            
                            DB::commit();
                            
                            Log::info('bKash payment marked as processing - proceeding to execute', [
                                'order_id' => $order->id,
                                'payment_id' => $paymentId,
                                'timestamp' => now()->toIso8601String(),
                            ]);
                            
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Failed to acquire payment lock', [
                                'order_id' => $order->id,
                                'payment_id' => $paymentId,
                                'error' => $e->getMessage(),
                            ]);
                            
                            return redirect()->route('checkout.show')
                                ->with('error', 'Payment processing error. Please try again.');
                        }
                        
                        // PROTECTION LAYER 3: Execute payment with bKash API
                        // This includes mandatory timeout handling with Query Payment API fallback
                        $execution = app(BkashService::class)->execute($paymentId);
                        
                        if ($execution['success']) {
                            $verified = true;
                            $transactionId = $execution['transaction_id'];
                            $amount = $execution['amount'];
                            $gatewayResponse = $execution;
                            
                            // Check if payment was verified via Query Payment after timeout
                            $verificationMethod = isset($execution['queried_after_timeout']) && $execution['queried_after_timeout'] 
                                ? 'Query Payment API (after timeout)' 
                                : 'Execute Payment API';
                            
                            Log::info('bKash payment executed successfully', [
                                'order_id' => $order->id,
                                'payment_id' => $paymentId,
                                'transaction_id' => $transactionId,
                                'verification_method' => $verificationMethod,
                                'timestamp' => now()->toIso8601String(),
                            ]);
                        } else {
                            // Revert status back to pending on failure
                            try {
                                $payment->update(['status' => 'pending']);
                            } catch (\Exception $e) {
                                Log::error('Failed to revert payment status', [
                                    'order_id' => $order->id,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                            
                            $errorMessage = $execution['message'] ?? 'Payment verification failed. Please contact support.';
                            Log::error('bKash payment execution failed', [
                                'order_id' => $order->id,
                                'order_number' => $order->order_number,
                                'payment_id' => $paymentId,
                                'execution_result' => $execution,
                                'timestamp' => now()->toIso8601String(),
                            ]);
                            
                            return redirect()->route('checkout.show')
                                ->with('error', $errorMessage);
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
                        'gateway_response' => json_encode($gatewayResponse ?? $request->all()),
                        'processed_at' => now(),
                    ]
                );
                try {
                    $order->refreshPaymentStatus(); 
                } catch (\Throwable $e) {
                    Log::error('Failed to refresh payment status', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
                
                // Clear shopping cart after successful payment
                try {
                    // First, try to find cart using session-stored identifiers
                    $cart = null;
                    $cartId = session('pending_order_cart_id');
                    
                    if ($cartId) {
                        $cart = ShoppingCart::find($cartId);
                        Log::info('Found cart from session', ['cart_id' => $cartId]);
                    }
                    
                    // Fallback: Find cart by user_id or session_id
                    if (!$cart) {
                        if ($order->user_id) {
                            $cart = ShoppingCart::where('user_id', $order->user_id)->first();
                        }
                        if (!$cart && session()->getId()) {
                            $cart = ShoppingCart::where('session_id', session()->getId())->first();
                        }
                    }
                    
                    if ($cart) {
                        Log::info('Clearing cart after payment', [
                            'cart_id' => $cart->id,
                            'cart_user_id' => $cart->user_id,
                            'cart_session_id' => $cart->session_id,
                            'order_id' => $order->id,
                            'order_user_id' => $order->user_id,
                        ]);
                        
                        // Increment coupon usage count if applicable
                        if ($cart->coupon) {
                            try { 
                                $cart->coupon->increment('used_count'); 
                            } catch (\Throwable $e) {
                                Log::warning('Failed to increment coupon usage', ['error' => $e->getMessage()]);
                            }
                        }
                        
                        // Delete all cart items
                        $deletedItems = $cart->items()->delete();
                        
                        // Delete the cart itself
                        $cart->delete();
                        
                        // Clear session data
                        session()->forget(['pending_order_cart_id', 'pending_order_cart_user_id', 'pending_order_cart_session_id']);
                        
                        Log::info('Cart cleared successfully', [
                            'order_id' => $order->id,
                            'deleted_items' => $deletedItems,
                        ]);
                    } else {
                        Log::warning('No cart found to clear', [
                            'order_id' => $order->id,
                            'order_user_id' => $order->user_id,
                            'session_cart_id' => session('pending_order_cart_id'),
                            'current_session_id' => session()->getId(),
                            'auth_user_id' => Auth::id(),
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to clear cart after payment', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
                // Send order notifications after successful payment
                $this->sendOrderNotifications($order);
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
        
        // Clear shopping cart after demo payment
        try {
            $cart = ShoppingCart::forCurrent()->first();
            if ($cart) {
                if ($cart->coupon) { 
                    try { 
                        $cart->coupon->increment('used_count'); 
                    } catch (\Throwable $e) {
                        Log::warning('Failed to increment coupon usage', ['error' => $e->getMessage()]);
                    } 
                }
                $cart->items()->delete();
                $cart->delete(); // Delete the cart itself
            }
        } catch (\Throwable $e) {
            Log::error('Failed to clear cart after demo payment', ['error' => $e->getMessage()]);
        }
        $this->sendOrderNotifications($order);
        
        return redirect()->route('checkout.success', $order->order_number);
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
                $sent = $brevoMail->sendOrderConfirmation($order);
            }
        } catch (\Exception $e) {
            Log::error('Customer email failed:', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
        // 2. Send email to shop owner
        try {
            $sent = $brevoMail->sendOrderNotificationToShop($order);
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

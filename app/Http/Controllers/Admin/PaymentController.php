<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentGateway\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Show bKash payment management tools page
     */
    public function bkashTools()
    {
        try {
            $recentPayments = Payment::where('gateway', 'bkash')
                ->with('order')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return view('admin.payments.bkash-tools', compact('recentPayments'));
        } catch (\Exception $e) {
            Log::error('bKash tools page error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('admin.payments.bkash-tools', ['recentPayments' => collect()]);
        }
    }

    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_method' => 'required|string|max:30',
            'gateway' => 'nullable|string|max:30',
            'transaction_id' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'status' => 'required|in:pending,completed,failed,cancelled,refunded',
            'gateway_response' => 'nullable|string',
        ]);

        $data['currency'] = $data['currency'] ?? ($order->currency ?? 'BDT');
        $payment = $order->payments()->create($data);
        $order->refreshPaymentStatus();

        return back()->with('message', 'Payment recorded.');
    }

    public function update(Request $request, Order $order, Payment $payment)
    {
        $data = $request->validate([
            'payment_method' => 'sometimes|required|string|max:30',
            'gateway' => 'nullable|string|max:30',
            'transaction_id' => 'nullable|string|max:100',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'status' => 'sometimes|required|in:pending,completed,failed,cancelled,refunded',
            'gateway_response' => 'nullable|string',
        ]);
        $payment->update($data);
        $order->refreshPaymentStatus();
        return back()->with('message', 'Payment updated.');
    }

    public function destroy(Order $order, Payment $payment)
    {
        $payment->delete();
        $order->refreshPaymentStatus();
        return back()->with('message', 'Payment removed.');
    }

    /**
     * bKash: Verify payment status using paymentID
     */
    public function verifyBkashPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string',
        ]);

        try {
            $bkashService = new BkashService();
            $result = $bkashService->queryPayment($request->payment_id);

            if ($result['success']) {
                // Try to find order by payment_id
                $payment = Payment::where('transaction_id', $request->payment_id)
                    ->orWhere('gateway_response', 'like', '%' . $request->payment_id . '%')
                    ->first();

                $order = $payment ? $payment->order : null;

                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'order' => $order ? [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'payment_status' => $order->payment_status,
                    ] : null,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Payment verification failed',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Admin bKash verify payment exception', [
                'payment_id' => $request->payment_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'System error. Please try again.',
            ], 500);
        }
    }

    /**
     * bKash: Search transaction by trxID
     */
    public function searchBkashTransaction(Request $request)
    {
        $request->validate([
            'trx_id' => 'required|string',
        ]);

        try {
            $bkashService = new BkashService();
            $result = $bkashService->searchTransaction($request->trx_id);

            if ($result['success']) {
                // Try to find order by transaction_id or merchant_invoice
                $payment = Payment::where('transaction_id', $request->trx_id)->first();
                
                if (!$payment && isset($result['merchant_invoice'])) {
                    $order = Order::where('order_number', $result['merchant_invoice'])->first();
                    $payment = $order ? $order->payments()->first() : null;
                }

                $order = $payment ? $payment->order : null;

                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'order' => $order ? [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'payment_status' => $order->payment_status,
                    ] : null,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Transaction not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Admin bKash search transaction exception', [
                'trx_id' => $request->trx_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'System error. Please try again.',
            ], 500);
        }
    }

    /**
     * bKash: Process refund
     */
    public function processBkashRefund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            // Find bKash payment for this order
            $payment = $order->payments()
                ->where('gateway', 'bkash')
                ->where('status', 'completed')
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No completed bKash payment found for this order.',
                ], 400);
            }

            // Extract paymentID and trxID
            $gatewayResponse = json_decode($payment->gateway_response, true);
            $paymentId = $gatewayResponse['paymentID'] ?? $payment->transaction_id;
            
            // Get trxID from payment transaction_id if it's a trxID format
            $trxId = $payment->transaction_id;
            if (isset($gatewayResponse['transaction_id'])) {
                $trxId = $gatewayResponse['transaction_id'];
            }

            $bkashService = new BkashService();
            $result = $bkashService->refund(
                $paymentId,
                $trxId,
                $request->amount,
                $request->reason ?? 'Admin refund request'
            );

            if ($result['success']) {
                // Update payment status
                $payment->update([
                    'status' => 'refunded',
                    'gateway_response' => json_encode(array_merge(
                        json_decode($payment->gateway_response, true) ?? [],
                        [
                            'refund' => $result,
                            'refunded_at' => now()->toIso8601String(),
                        ]
                    )),
                ]);

                // Update order payment status
                $order->refreshPaymentStatus();

                Log::info('Admin processed bKash refund', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'amount' => $request->amount,
                    'refund_trx_id' => $result['refund_trx_id'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Refund processed successfully',
                    'data' => $result,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Refund processing failed',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Admin bKash refund exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'System error. Please try again.',
            ], 500);
        }
    }

    /**
     * bKash: Check refund status
     */
    public function checkBkashRefundStatus(Request $request, Order $order)
    {
        try {
            $payment = $order->payments()
                ->where('gateway', 'bkash')
                ->whereIn('status', ['refunded', 'completed'])
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bKash payment found for this order.',
                ], 404);
            }

            $gatewayResponse = json_decode($payment->gateway_response, true);
            $paymentId = $gatewayResponse['paymentID'] ?? $payment->transaction_id;
            $trxId = $gatewayResponse['transaction_id'] ?? $payment->transaction_id;

            $bkashService = new BkashService();
            $result = $bkashService->queryRefund($paymentId, $trxId);

            return response()->json([
                'success' => $result['success'],
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin bKash refund status check exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'System error. Please try again.',
            ], 500);
        }
    }
}

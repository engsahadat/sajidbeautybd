<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
}

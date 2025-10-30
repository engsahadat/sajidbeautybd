<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::with('user');
        if ($request->filled('search')) {
            $s = $request->input('search');
            $q->where(function($qq) use ($s){
                $qq->where('order_number','like',"%{$s}%")
                   ->orWhere('billing_first_name','like',"%{$s}%")
                   ->orWhere('billing_last_name','like',"%{$s}%");
            });
        }
        if ($request->filled('status')) { $q->where('status', $request->input('status')); }
        if ($request->filled('payment_status')) { $q->where('payment_status', $request->input('payment_status')); }
        $orders = $q->orderByDesc('id')->paginate(15)->appends($request->query());
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product','user','statusHistory');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_refunded',
            'notes' => 'nullable|string',
        ]);
        
        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        // Update timestamps based on status changes
        if ($data['status'] === 'shipped' && $oldStatus !== 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($data['status'] === 'delivered' && $oldStatus !== 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = now();
        }
        
        $order->update($data);
        
        // Record status history if changed
        if ($oldStatus !== $data['status']) {
            $order->statusHistory()->create([
                'status' => $data['status'],
                'comment' => "Order status changed from {$oldStatus} to {$data['status']}",
            ]);
        }
        
        if ($oldPaymentStatus !== $data['payment_status']) {
            $order->statusHistory()->create([
                'status' => $data['status'],
                'comment' => "Payment status changed from {$oldPaymentStatus} to {$data['payment_status']}",
            ]);
        }
        
        return redirect()->route('orders.show', $order)->with('message','Order updated');
    }
}

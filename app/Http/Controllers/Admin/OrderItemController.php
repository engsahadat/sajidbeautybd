<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'product_id' => 'nullable|integer|exists:products,id',
            'product_name' => 'required|string|max:191',
            'product_sku' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $qty = (int)$data['quantity'];
        $unit = (float)$data['unit_price'];
        $data['total_price'] = $qty * $unit;
        $order->items()->create($data);
        $order->recalcTotals();
        return redirect()->route('orders.show', $order)->with('message','Item added');
    }

    public function update(Request $request, Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            return redirect()->back()->with('error','Invalid item');
        }
        $data = $request->validate([
            'product_name' => 'required|string|max:191',
            'product_sku' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $qty = (int)$data['quantity'];
        $unit = (float)$data['unit_price'];
        $data['total_price'] = $qty * $unit;
        $item->update($data);
        $order->recalcTotals();
        return redirect()->route('orders.show', $order)->with('message','Item updated');
    }

    public function destroy(Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            return redirect()->back()->with('error','Invalid item');
        }
        $item->delete();
        $order->recalcTotals();
        return redirect()->route('orders.show', $order)->with('message','Item removed');
    }
}

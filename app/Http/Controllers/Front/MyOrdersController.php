<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class MyOrdersController extends Controller
{
    // List current user's orders
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(10);

        return view('front-end.account.orders.index', compact('orders'));
    }

    // Show a specific order by number, scoped to current user
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        return view('front-end.account.orders.show', compact('order'));
    }
}

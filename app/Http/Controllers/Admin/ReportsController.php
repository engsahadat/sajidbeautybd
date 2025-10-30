<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        // Base orders query
        $ordersQ = Order::query()
            ->whereBetween('created_at', [$fromDate, $toDate]);
        if ($status) { $ordersQ->where('status', $status); }
        if ($paymentStatus) { $ordersQ->where('payment_status', $paymentStatus); }

        $ordersCount = (clone $ordersQ)->count();
        $revenue = (clone $ordersQ)->sum('total_amount');
        $avgOrder = $ordersCount ? ($revenue / $ordersCount) : 0.0;

        // Payments summary
        $paymentsQ = Payment::query()->whereBetween('created_at', [$fromDate, $toDate]);
        $paid = (clone $paymentsQ)->where('status', 'completed')->sum('amount');
        $refunded = (clone $paymentsQ)->where('status', 'refunded')->sum('amount');

        // Daily revenue series (for chart)
        $daily = Order::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Top products
        $topProducts = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(total_price) as sales'))
            ->whereHas('order', function($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->groupBy('product_id')
            ->orderByDesc('sales')
            ->with('product:id,name')
            ->limit(10)
            ->get();

        // Recent orders
        $recentOrders = (clone $ordersQ)->latest()->limit(10)->get(['id','order_number','total_amount','status','payment_status','created_at']);

        return view('admin.reports.index', [
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
            'filters' => [ 'status' => $status, 'payment_status' => $paymentStatus ],
            'ordersCount' => $ordersCount,
            'revenue' => $revenue,
            'avgOrder' => $avgOrder,
            'paid' => (float)$paid,
            'refunded' => (float)$refunded,
            'daily' => $daily,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ]);
    }
}

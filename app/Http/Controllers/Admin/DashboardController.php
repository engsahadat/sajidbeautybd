<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        // Date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $last30Days = Carbon::now()->subDays(30);

        // Today's Stats
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)->sum('total_amount');
        $todayCustomers = User::whereDate('created_at', $today)->where('role', 'customer')->count();
        
        // Yesterday's Stats for comparison
        $yesterdayOrders = Order::whereDate('created_at', $yesterday)->count();
        $yesterdayRevenue = Order::whereDate('created_at', $yesterday)->sum('total_amount');

        // This Month Stats
        $monthOrders = Order::where('created_at', '>=', $thisMonth)->count();
        $monthRevenue = Order::where('created_at', '>=', $thisMonth)->sum('total_amount');
        
        // Last Month Stats for comparison
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_amount');

        // Total Stats
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        // Recent Orders
        $recentOrders = Order::with('user:id,first_name,last_name')
            ->latest()
            ->limit(10)
            ->get();

        // Top Selling Products (Last 30 days)
        $topProducts = OrderItem::select(
                'order_items.product_id',
                DB::raw('products.name as product_name'),
                DB::raw('products.image as product_image'),
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total_price) as revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($q) use ($last30Days) {
                $q->where('created_at', '>=', $last30Days);
            })
            ->groupBy('order_items.product_id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // Order Status Distribution
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $last30Days)
            ->groupBy('status')
            ->get();

        // Revenue Chart Data (Last 30 days)
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', $last30Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent Customers
        $recentCustomers = User::where('role', 'customer')
            ->latest()
            ->limit(5)
            ->get();

        // Calculate percentages
        $ordersGrowth = $yesterdayOrders > 0 ? (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100 : 0;
        $revenueGrowth = $yesterdayRevenue > 0 ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 0;
        $monthOrdersGrowth = $lastMonthOrders > 0 ? (($monthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;
        $monthRevenueGrowth = $lastMonthRevenue > 0 ? (($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'todayCustomers',
            'monthOrders', 'monthRevenue',
            'totalOrders', 'totalRevenue', 'totalCustomers', 'totalProducts',
            'recentOrders', 'topProducts', 'lowStockProducts',
            'ordersByStatus', 'revenueChart', 'recentCustomers',
            'ordersGrowth', 'revenueGrowth', 'monthOrdersGrowth', 'monthRevenueGrowth'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

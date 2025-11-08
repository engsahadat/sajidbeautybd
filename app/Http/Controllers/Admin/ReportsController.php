<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function sales(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $period = $request->query('period', 'daily');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        // Summary metrics
        $totalSales = Order::whereBetween('created_at', [$fromDate, $toDate])->sum('total_amount');
        $totalOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->count();
        $avgOrderValue = $totalOrders ? ($totalSales / $totalOrders) : 0;
        $totalItems = OrderItem::whereHas('order', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->sum('quantity');

        // Sales data by period
        $periodFormat = match($period) {
            'weekly' => 'YEAR(orders.created_at), WEEK(orders.created_at)',
            'monthly' => 'YEAR(orders.created_at), MONTH(orders.created_at)',
            'yearly' => 'YEAR(orders.created_at)',
            default => 'DATE(orders.created_at)',
        };

        $periodDisplay = match($period) {
            'weekly' => DB::raw("CONCAT(YEAR(orders.created_at), '-W', LPAD(WEEK(orders.created_at), 2, '0')) as period"),
            'monthly' => DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as period"),
            'yearly' => DB::raw("YEAR(orders.created_at) as period"),
            default => DB::raw("DATE(orders.created_at) as period"),
        };

        $salesData = Order::select(
                $periodDisplay,
                DB::raw('COUNT(DISTINCT orders.id) as orders'),
                DB::raw('SUM(orders.total_amount) as revenue'),
                DB::raw('AVG(orders.total_amount) as avg_order'),
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as items')
            )
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy(DB::raw($periodFormat))
            ->orderBy('period')
            ->get();

        // Sales by status
        $salesByStatus = Order::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('status')
            ->get();

        // Sales by payment
        $salesByPayment = Payment::select('gateway', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as amount'))
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->where('status', 'completed')
            ->groupBy('gateway')
            ->get();

        return view('admin.reports.sales', compact(
            'from', 'to', 'period', 'fromDate', 'toDate',
            'totalSales', 'totalOrders', 'avgOrderValue', 'totalItems',
            'salesData', 'salesByStatus', 'salesByPayment'
        ))->with([
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
        ]);
    }

    public function products(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $categoryId = $request->query('category');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        // Get categories for filter
        $categories = \App\Models\Category::where('is_active', 1)->orderBy('name')->get(['id', 'name']);

        // Base query
        $query = OrderItem::select(
                'order_items.product_id',
                DB::raw('products.name as product_name'),
                DB::raw('categories.name as category_name'),
                DB::raw('products.stock_quantity as stock'),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('AVG(order_items.unit_price) as avg_price')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', function($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            });

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->groupBy('order_items.product_id', 'products.name', 'categories.name', 'products.stock_quantity')
            ->orderByDesc('revenue')
            ->paginate(50);

        $topProducts = (clone $query)->limit(10)->get();

        // Summary
        $totalProductsSold = OrderItem::whereHas('order', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->sum('quantity');

        $uniqueProducts = OrderItem::whereHas('order', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->distinct('product_id')->count('product_id');

        $totalRevenue = OrderItem::whereHas('order', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->sum('total_price');

        $avgPrice = $totalProductsSold ? ($totalRevenue / $totalProductsSold) : 0;

        return view('admin.reports.products', compact(
            'from', 'to', 'categoryId', 'fromDate', 'toDate',
            'categories', 'products', 'topProducts',
            'totalProductsSold', 'uniqueProducts', 'totalRevenue', 'avgPrice'
        ))->with([
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
        ]);
    }

    public function customers(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        // Customers with orders in period
        $customers = \App\Models\User::select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('COUNT(orders.id) as orders_count'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('AVG(orders.total_amount) as avg_order'),
                DB::raw('MAX(orders.created_at) as last_order')
            )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderByDesc('total_spent')
            ->paginate(50);

        $topCustomers = \App\Models\User::select(
                'users.id',
                'users.first_name',
                'users.last_name',
                DB::raw('SUM(orders.total_amount) as total_spent')
            )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // Summary
        $totalCustomers = \App\Models\User::whereHas('orders', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->count();

        // New customers (first order in period)
        $newCustomers = \App\Models\User::whereHas('orders', function($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        })->whereDoesntHave('orders', function($q) use ($fromDate) {
            $q->where('created_at', '<', $fromDate);
        })->count();

        $returningCustomers = $totalCustomers - $newCustomers;

        $totalCustomerValue = Order::whereBetween('created_at', [$fromDate, $toDate])->sum('total_amount');

        return view('admin.reports.customers', compact(
            'from', 'to', 'fromDate', 'toDate',
            'customers', 'topCustomers',
            'totalCustomers', 'newCustomers', 'returningCustomers', 'totalCustomerValue'
        ))->with([
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
        ]);
    }

    public function payments(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $gateway = $request->query('gateway');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $query = Payment::whereBetween('created_at', [$fromDate, $toDate]);
        if ($gateway) {
            $query->where('gateway', $gateway);
        }

        $payments = (clone $query)->with('order:id,order_number')->latest()->paginate(50);

        // Summary
        $totalPayments = (clone $query)->count();
        $completedAmount = (clone $query)->where('status', 'completed')->sum('amount');
        $refundedAmount = (clone $query)->where('status', 'refunded')->sum('amount');
        $failedAmount = (clone $query)->where('status', 'failed')->sum('amount');

        // By gateway
        $paymentsByGateway = Payment::select(
                'gateway',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = "failed" THEN amount ELSE 0 END) as failed'),
                DB::raw('SUM(CASE WHEN status = "refunded" THEN amount ELSE 0 END) as refunded')
            )
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('gateway')
            ->get();

        return view('admin.reports.payments', compact(
            'from', 'to', 'gateway', 'fromDate', 'toDate',
            'payments', 'totalPayments',
            'completedAmount', 'refundedAmount', 'failedAmount',
            'paymentsByGateway'
        ))->with([
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
        ]);
    }

    public function orders(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $status = $request->query('status');
        $customerSearch = $request->query('customer');
        $productSearch = $request->query('product');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $query = Order::whereBetween('created_at', [$fromDate, $toDate]);
        
        if ($status) {
            $query->where('status', $status);
        }

        // Customer search
        if ($customerSearch) {
            $query->whereHas('user', function($q) use ($customerSearch) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$customerSearch}%")
                  ->orWhere('email', 'LIKE', "%{$customerSearch}%");
            });
        }

        // Product search
        if ($productSearch) {
            $query->whereHas('items', function($q) use ($productSearch) {
                $q->where('product_name', 'LIKE', "%{$productSearch}%")
                  ->orWhere('product_sku', 'LIKE', "%{$productSearch}%");
            });
        }

        $orders = (clone $query)->withCount('items')
            ->with([
                'user:id,first_name,last_name',
                'items:id,order_id,product_name'
            ])
            ->latest()
            ->paginate(50)
            ->appends($request->query());

        // Summary
        $totalOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->count();
        $completedOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', 'delivered')->count();
        $cancelledOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', 'cancelled')->count();
        $pendingOrders = Order::whereBetween('created_at', [$fromDate, $toDate])->where('status', 'pending')->count();

        // By status
        $ordersByStatus = Order::select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('AVG(total_amount) as avg'),
                DB::raw('(COUNT(*) * 100.0 / '.$totalOrders.') as percentage')
            )
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('status')
            ->get();

        // Trends
        $orderTrends = Order::select(
                DB::raw('DATE(created_at) as period'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return view('admin.reports.orders', compact(
            'from', 'to', 'status', 'customerSearch', 'productSearch', 'fromDate', 'toDate',
            'orders', 'totalOrders', 'completedOrders', 'cancelledOrders', 'pendingOrders',
            'ordersByStatus', 'orderTrends'
        ))->with([
            'from' => $fromDate->toDateString(),
            'to' => $toDate->toDateString(),
        ]);
    }

    // Export methods
    public function exportSales(Request $request)
    {
        $format = $request->query('format', 'csv');
        $from = $request->query('from');
        $to = $request->query('to');
        $period = $request->query('period', 'daily');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        // Get data
        $periodFormat = match($period) {
            'weekly' => 'YEAR(orders.created_at), WEEK(orders.created_at)',
            'monthly' => 'YEAR(orders.created_at), MONTH(orders.created_at)',
            'yearly' => 'YEAR(orders.created_at)',
            default => 'DATE(orders.created_at)',
        };

        $periodDisplay = match($period) {
            'weekly' => DB::raw("CONCAT(YEAR(orders.created_at), '-W', LPAD(WEEK(orders.created_at), 2, '0')) as period"),
            'monthly' => DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as period"),
            'yearly' => DB::raw("YEAR(orders.created_at) as period"),
            default => DB::raw("DATE(orders.created_at) as period"),
        };

        $salesData = Order::select(
                $periodDisplay,
                DB::raw('COUNT(DISTINCT orders.id) as orders'),
                DB::raw('SUM(orders.total_amount) as revenue'),
                DB::raw('AVG(orders.total_amount) as avg_order'),
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as items')
            )
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy(DB::raw($periodFormat))
            ->orderBy('period')
            ->get();

        // Convert to array for export
        $data = $salesData->map(function($row) {
            return [
                'period' => $row->period,
                'orders' => $row->orders,
                'revenue' => number_format($row->revenue, 2),
                'avg_order' => number_format($row->avg_order, 2),
                'items' => $row->items
            ];
        });

        $filename = 'sales_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'period' => 'Period',
            'orders' => 'Orders',
            'revenue' => 'Revenue',
            'avg_order' => 'Avg Order',
            'items' => 'Items'
        ]);
    }

    public function exportProducts(Request $request)
    {
        $format = $request->query('format', 'csv');
        $from = $request->query('from');
        $to = $request->query('to');
        $categoryId = $request->query('category');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $query = OrderItem::select(
                'order_items.product_id',
                DB::raw('products.name as product_name'),
                DB::raw('categories.name as category_name'),
                DB::raw('products.stock_quantity as stock'),
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('AVG(order_items.unit_price) as avg_price')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereHas('order', function($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            });

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->groupBy('order_items.product_id', 'products.name', 'categories.name', 'products.stock_quantity')
            ->orderByDesc('revenue')
            ->get();

        // Convert to array for export
        $data = $products->map(function($product) {
            return [
                'product_name' => $product->product_name,
                'category_name' => $product->category_name ?? 'N/A',
                'quantity' => $product->quantity,
                'revenue' => number_format($product->revenue, 2),
                'avg_price' => number_format($product->avg_price, 2),
                'stock' => $product->stock
            ];
        });

        $filename = 'products_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'product_name' => 'Product',
            'category_name' => 'Category',
            'quantity' => 'Quantity Sold',
            'revenue' => 'Revenue',
            'avg_price' => 'Avg Price',
            'stock' => 'Current Stock'
        ]);
    }

    public function exportCustomers(Request $request)
    {
        $format = $request->query('format', 'csv');
        $from = $request->query('from');
        $to = $request->query('to');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $customers = \App\Models\User::select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('COUNT(orders.id) as orders_count'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('AVG(orders.total_amount) as avg_order'),
                DB::raw('MAX(orders.created_at) as last_order')
            )
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderByDesc('total_spent')
            ->get();

        // Convert to array for export
        $data = $customers->map(function($customer) {
            return [
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'orders_count' => $customer->orders_count,
                'total_spent' => number_format($customer->total_spent, 2),
                'avg_order' => number_format($customer->avg_order, 2),
                'last_order' => $customer->last_order
            ];
        });

        $filename = 'customers_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'orders_count' => 'Orders',
            'total_spent' => 'Total Spent',
            'avg_order' => 'Avg Order',
            'last_order' => 'Last Order'
        ]);
    }

    public function exportPayments(Request $request)
    {
        $format = $request->query('format', 'csv');
        $from = $request->query('from');
        $to = $request->query('to');
        $gateway = $request->query('gateway');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $query = Payment::with('order:id,order_number')->whereBetween('created_at', [$fromDate, $toDate]);
        if ($gateway) {
            $query->where('gateway', $gateway);
        }

        $payments = $query->latest()->get();

        $data = $payments->map(function($payment) {
            return [
                'order_number' => $payment->order->order_number ?? 'N/A',
                'transaction_id' => $payment->transaction_id,
                'gateway' => $payment->gateway,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s')
            ];
        });

        $filename = 'payments_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'order_number' => 'Order Number',
            'transaction_id' => 'Transaction ID',
            'gateway' => 'Gateway',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Date'
        ]);
    }

    public function exportOrders(Request $request)
    {
        $format = $request->query('format', 'csv');
        $from = $request->query('from');
        $to = $request->query('to');
        $status = $request->query('status');
        $customerSearch = $request->query('customer');
        $productSearch = $request->query('product');

        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $query = Order::whereBetween('created_at', [$fromDate, $toDate]);
        
        if ($status) {
            $query->where('status', $status);
        }

        if ($customerSearch) {
            $query->whereHas('user', function($q) use ($customerSearch) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$customerSearch}%")
                  ->orWhere('email', 'LIKE', "%{$customerSearch}%");
            });
        }

        if ($productSearch) {
            $query->whereHas('items', function($q) use ($productSearch) {
                $q->where('product_name', 'LIKE', "%{$productSearch}%")
                  ->orWhere('product_sku', 'LIKE', "%{$productSearch}%");
            });
        }

        $orders = $query->with('user:id,first_name,last_name,email')->latest()->get();

        $data = $orders->map(function($order) {
            return [
                'order_number' => $order->order_number,
                'customer' => $order->user ? $order->user->full_name : 'Guest',
                'customer_email' => $order->user ? $order->user->email : '',
                'total_amount' => number_format($order->total_amount, 2),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at->format('Y-m-d H:i:s')
            ];
        });

        $filename = 'orders_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'order_number' => 'Order Number',
            'customer' => 'Customer',
            'customer_email' => 'Email',
            'total_amount' => 'Total',
            'status' => 'Status',
            'payment_status' => 'Payment Status',
            'created_at' => 'Date'
        ]);
    }

    private function generateExport($data, $filename, $format, $headers)
    {
        switch ($format) {
            case 'csv':
                return $this->exportCSV($data, $filename, $headers);
            case 'excel':
                return $this->exportExcel($data, $filename, $headers);
            case 'pdf':
                return $this->exportPDF($data, $filename, $headers);
            default:
                return back()->with('error', 'Invalid export format');
        }
    }

    private function exportCSV($data, $filename, $headers)
    {
        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, array_values($headers));
            
            // Add data
            foreach ($data as $row) {
                $rowData = [];
                foreach (array_keys($headers) as $key) {
                    $rowData[] = $row[$key] ?? '';
                }
                fputcsv($file, $rowData);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    private function exportExcel($data, $filename, $headers)
    {
        // Simple XML-based Excel format (SpreadsheetML)
        $callback = function() use ($data, $headers) {
            echo '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?>';
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
            echo 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
            echo '<Worksheet ss:Name="Sheet1"><Table>';
            
            // Headers
            echo '<Row>';
            foreach ($headers as $header) {
                echo '<Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>';
            }
            echo '</Row>';
            
            // Data
            foreach ($data as $row) {
                echo '<Row>';
                foreach (array_keys($headers) as $key) {
                    $value = $row[$key] ?? '';
                    $type = is_numeric($value) ? 'Number' : 'String';
                    echo '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars($value) . '</Data></Cell>';
                }
                echo '</Row>';
            }
            
            echo '</Table></Worksheet></Workbook>';
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
        ]);
    }

    private function exportPDF($data, $filename, $headers)
    {
        // Create HTML for PDF
        $html = '<!DOCTYPE html><html><head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $html .= '<style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
            h2 { color: #333; margin-bottom: 20px; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 6px; text-align: left; word-wrap: break-word; }
            th { background-color: #4CAF50; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f2f2f2; }
        </style></head><body>';
        $html .= '<h2>' . ucfirst(str_replace('_', ' ', $filename)) . '</h2>';
        $html .= '<table><thead><tr>';
        
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach (array_keys($headers) as $key) {
                $html .= '<td>' . htmlspecialchars($row[$key] ?? '') . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table></body></html>';

        // Generate PDF using dompdf
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download($filename . '.pdf');
    }

    public function stockAlert(Request $request)
    {
        $threshold = $request->query('threshold', 1);
        $category = $request->query('category');
        
        // Get categories for filter
        $categories = \App\Models\Category::where('is_active', 1)->orderBy('name')->get(['id', 'name']);

        // Get products with low stock
        $query = \App\Models\Product::select(
                'products.id',
                'products.name',
                'products.sku',
                'products.stock_quantity',
                'products.price',
                'products.is_active',
                'categories.name as category_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.stock_quantity', '<=', $threshold);

        if ($category) {
            $query->where('products.category_id', $category);
        }

        $lowStockProducts = (clone $query)->orderBy('products.stock_quantity', 'asc')->get();

        // Get products with variants that have low stock
        $lowStockVariants = \App\Models\ProductVariant::select(
                'product_variants.id',
                'product_variants.name as variant_name',
                'product_variants.value as variant_value',
                'product_variants.stock_quantity',
                'product_variants.sku',
                'products.id as product_id',
                'products.name as product_name',
                'categories.name as category_name'
            )
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('product_variants.stock_quantity', '<=', $threshold);

        if ($category) {
            $lowStockVariants->where('products.category_id', $category);
        }

        $lowStockVariants = $lowStockVariants->orderBy('product_variants.stock_quantity', 'asc')->get();

        // Summary statistics
        $totalLowStock = $lowStockProducts->count();
        $totalOutOfStock = \App\Models\Product::where('stock_quantity', '<=', 0)->count();
        $totalVariantsLowStock = \App\Models\ProductVariant::where('stock_quantity', '<=', $threshold)->count();
        $totalVariantsOutOfStock = \App\Models\ProductVariant::where('stock_quantity', '<=', 0)->count();
        
        // Products that need urgent restock (0 stock)
        $urgentProducts = \App\Models\Product::where('stock_quantity', '<=', 0)
            ->where('is_active', 1)
            ->count();

        return view('admin.reports.stock-alert', compact(
            'threshold',
            'category',
            'categories',
            'lowStockProducts',
            'lowStockVariants',
            'totalLowStock',
            'totalOutOfStock',
            'totalVariantsLowStock',
            'totalVariantsOutOfStock',
            'urgentProducts'
        ));
    }

    public function exportStockAlert(Request $request)
    {
        $format = $request->query('format', 'csv');
        $threshold = $request->query('threshold', 1);
        $category = $request->query('category');

        // Get products with low stock
        $query = \App\Models\Product::select(
                'products.name',
                'products.sku',
                'products.stock_quantity',
                'products.price',
                'products.is_active',
                'categories.name as category_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.stock_quantity', '<=', $threshold);

        if ($category) {
            $query->where('products.category_id', $category);
        }

        $products = $query->orderBy('products.stock_quantity', 'asc')->get();

        // Get variants with low stock
        $variants = \App\Models\ProductVariant::select(
                'product_variants.name as variant_name',
                'product_variants.value as variant_value',
                'product_variants.stock_quantity',
                'product_variants.sku',
                'products.name as product_name',
                'categories.name as category_name'
            )
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('product_variants.stock_quantity', '<=', $threshold);

        if ($category) {
            $variants->where('products.category_id', $category);
        }

        $variants = $variants->orderBy('product_variants.stock_quantity', 'asc')->get();

        // Combine products and variants
        $data = collect();

        foreach ($products as $product) {
            $data->push([
                'type' => 'Product',
                'name' => $product->name,
                'variant' => '-',
                'category' => $product->category_name ?? 'N/A',
                'sku' => $product->sku,
                'stock' => $product->stock_quantity,
                'price' => number_format($product->price, 2),
                'status' => $product->is_active ? 'Active' : 'Inactive'
            ]);
        }

        foreach ($variants as $variant) {
            $data->push([
                'type' => 'Variant',
                'name' => $variant->product_name,
                'variant' => $variant->variant_name . ': ' . $variant->variant_value,
                'category' => $variant->category_name ?? 'N/A',
                'sku' => $variant->sku,
                'stock' => $variant->stock_quantity,
                'price' => '-',
                'status' => '-'
            ]);
        }

        $filename = 'stock_alert_report_' . now()->format('Y-m-d_His');
        
        return $this->generateExport($data, $filename, $format, [
            'type' => 'Type',
            'name' => 'Product Name',
            'variant' => 'Variant',
            'category' => 'Category',
            'sku' => 'SKU',
            'stock' => 'Stock',
            'price' => 'Price',
            'status' => 'Status'
        ]);
    }
}

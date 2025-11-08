@extends('admin.layouts.app')
@section('admin-title','Dashboard')
@section('admin-content')

<div class="container-fluid">
  <!-- Welcome Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
      <h2 class="mb-2 fw-bold" style="color: #1a1a2e;">
        <span class="gradient-text">Welcome back, {{ auth()->user()->first_name }}!</span> 
        <span class="wave-hand">👋</span>
      </h2>
      <p class="text-muted mb-0 d-flex align-items-center">
        <i class="ri-store-2-line me-2"></i>
        Here's what's happening with your store today.
      </p>
    </div>
    <div>
      <div class="badge bg-gradient-primary text-white px-4 py-2 rounded-pill shadow-sm">
        <i class="ri-calendar-line me-2"></i> 
        {{ now()->format('l, F d, Y') }}
      </div>
    </div>
  </div>

  <!-- Today's Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
      <div class="card stats-card border-0 shadow-hover h-100 overflow-hidden position-relative">
        <div class="stats-card-gradient bg-gradient-primary"></div>
        <div class="card-body position-relative z-1">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted mb-2 small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Today's Orders</p>
              <h2 class="mb-0 fw-bold" style="color: #1a1a2e;">{{ number_format($todayOrders) }}</h2>
            </div>
            <div class="stats-icon-wrapper bg-primary">
              <i class="ri-shopping-cart-2-line fs-3 text-white"></i>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            @if($ordersGrowth >= 0)
              <span class="badge bg-success-subtle text-success border-0 me-2 px-2 py-1">
                <i class="ri-arrow-up-line"></i> {{ number_format(abs($ordersGrowth), 1) }}%
              </span>
            @else
              <span class="badge bg-danger-subtle text-danger border-0 me-2 px-2 py-1">
                <i class="ri-arrow-down-line"></i> {{ number_format(abs($ordersGrowth), 1) }}%
              </span>
            @endif
            <small class="text-muted">vs yesterday</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card stats-card border-0 shadow-hover h-100 overflow-hidden position-relative">
        <div class="stats-card-gradient bg-gradient-success"></div>
        <div class="card-body position-relative z-1">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted mb-2 small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Today's Revenue</p>
              <h2 class="mb-0 fw-bold" style="color: #1a1a2e;">৳{{ number_format($todayRevenue, 0) }}</h2>
            </div>
            <div class="stats-icon-wrapper bg-success">
              <i class="ri-wallet-3-line fs-3 text-white"></i>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            @if($revenueGrowth >= 0)
              <span class="badge bg-success-subtle text-success border-0 me-2 px-2 py-1">
                <i class="ri-arrow-up-line"></i> {{ number_format(abs($revenueGrowth), 1) }}%
              </span>
            @else
              <span class="badge bg-danger-subtle text-danger border-0 me-2 px-2 py-1">
                <i class="ri-arrow-down-line"></i> {{ number_format(abs($revenueGrowth), 1) }}%
              </span>
            @endif
            <small class="text-muted">vs yesterday</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card stats-card border-0 shadow-hover h-100 overflow-hidden position-relative">
        <div class="stats-card-gradient bg-gradient-info"></div>
        <div class="card-body position-relative z-1">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted mb-2 small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">New Customers</p>
              <h2 class="mb-0 fw-bold" style="color: #1a1a2e;">{{ number_format($todayCustomers) }}</h2>
            </div>
            <div class="stats-icon-wrapper bg-info">
              <i class="ri-user-add-line fs-3 text-white"></i>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            <span class="badge bg-light text-dark border-0 me-2 px-2 py-1">Today</span>
            <small class="text-muted">{{ number_format($totalCustomers) }} total</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6">
      <div class="card stats-card border-0 shadow-hover h-100 overflow-hidden position-relative">
        <div class="stats-card-gradient bg-gradient-warning"></div>
        <div class="card-body position-relative z-1">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <p class="text-muted mb-2 small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Total Products</p>
              <h2 class="mb-0 fw-bold" style="color: #1a1a2e;">{{ number_format($totalProducts) }}</h2>
            </div>
            <div class="stats-icon-wrapper bg-warning">
              <i class="ri-box-3-line fs-3 text-white"></i>
            </div>
          </div>
          <div class="d-flex align-items-center mt-3">
            @if($lowStockProducts->count() > 0)
              <span class="badge bg-danger-subtle text-danger border-0 me-2 px-2 py-1">
                <i class="ri-alert-line"></i> {{ $lowStockProducts->count() }} Low Stock
              </span>
            @else
              <span class="badge bg-success-subtle text-success border-0 me-2 px-2 py-1">
                <i class="ri-checkbox-circle-line"></i> All Stocked
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Monthly Stats & Charts -->
  <div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
      <div class="card modern-card border-0 shadow-hover h-100">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
                <i class="ri-line-chart-line me-2 text-primary"></i>Revenue Overview
              </h5>
              <p class="text-muted small mb-0">Track your sales performance</p>
            </div>
            <div class="btn-group btn-group-sm shadow-sm" role="group">
              <button type="button" class="btn btn-primary">30 Days</button>
            </div>
          </div>
        </div>
        <div class="card-body px-4">
          <div style="height: 320px;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Status -->
    <div class="col-lg-4">
      <div class="card modern-card border-0 shadow-hover h-100">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
            <i class="ri-pie-chart-line me-2 text-success"></i>Order Status
          </h5>
          <p class="text-muted small mb-0">Current order distribution</p>
        </div>
        <div class="card-body px-4">
          <div style="height: 320px;" class="d-flex align-items-center justify-content-center">
            <canvas id="orderStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Monthly Performance & Top Products -->
  <div class="row g-4 mb-4">
    <!-- Monthly Performance -->
    <div class="col-lg-4">
      <div class="card modern-card border-0 shadow-hover h-100">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
            <i class="ri-calendar-check-line me-2 text-info"></i>This Month
          </h5>
          <p class="text-muted small mb-0">Monthly performance metrics</p>
        </div>
        <div class="card-body px-4">
          <div class="mb-4 p-3 bg-light rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted fw-semibold">Orders</span>
              <span class="fw-bold fs-5" style="color: #1a1a2e;">{{ number_format($monthOrders) }}</span>
            </div>
            <div class="progress mb-2" style="height: 8px;">
              <div class="progress-bar bg-primary" style="width: 75%"></div>
            </div>
            <small class="text-muted">
              @if($monthOrdersGrowth >= 0)
                <i class="ri-arrow-up-line text-success"></i> {{ number_format(abs($monthOrdersGrowth), 1) }}% vs last month
              @else
                <i class="ri-arrow-down-line text-danger"></i> {{ number_format(abs($monthOrdersGrowth), 1) }}% vs last month
              @endif
            </small>
          </div>

          <div class="mb-4 p-3 bg-light rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted fw-semibold">Revenue</span>
              <span class="fw-bold fs-5" style="color: #1a1a2e;">৳{{ number_format($monthRevenue, 0) }}</span>
            </div>
            <div class="progress mb-2" style="height: 8px;">
              <div class="progress-bar bg-success" style="width: 65%"></div>
            </div>
            <small class="text-muted">
              @if($monthRevenueGrowth >= 0)
                <i class="ri-arrow-up-line text-success"></i> {{ number_format(abs($monthRevenueGrowth), 1) }}% vs last month
              @else
                <i class="ri-arrow-down-line text-danger"></i> {{ number_format(abs($monthRevenueGrowth), 1) }}% vs last month
              @endif
            </small>
          </div>

          <div class="mb-4 p-3 bg-light rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted fw-semibold">Avg Order Value</span>
              <span class="fw-bold fs-5" style="color: #1a1a2e;">৳{{ $monthOrders > 0 ? number_format($monthRevenue / $monthOrders, 0) : 0 }}</span>
            </div>
            <div class="progress mb-2" style="height: 8px;">
              <div class="progress-bar bg-info" style="width: 55%"></div>
            </div>
          </div>

          <a href="{{ route('admin.reports.index') }}" class="btn btn-primary w-100 rounded-pill shadow-sm">
            <i class="ri-bar-chart-box-line me-2"></i> View Full Reports
          </a>
        </div>
      </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-lg-8">
      <div class="card modern-card border-0 shadow-hover h-100">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
                <i class="ri-fire-line me-2 text-danger"></i>Top Selling Products
              </h5>
              <p class="text-muted small mb-0">Best performers in the last 30 days</p>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="border-0 px-4 py-3 fw-semibold text-muted" style="width: 40%;">Product</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-center">Price</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-center">Sold</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-center">Revenue</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-center">Trend</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topProducts as $product)
                  <tr class="align-middle">
                    <td class="px-4">
                      <div class="d-flex align-items-center">
                        @if($product->product_image && file_exists(public_path('images/products/' . $product->product_image)))
                          <img src="{{ asset('images/products/' . $product->product_image) }}" 
                               alt="{{ $product->product_name }}" 
                               class="rounded-3 shadow-sm me-3" 
                               style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #f0f0f0;">
                        @else
                          <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 2px solid #e0e0e0;">
                            <i class="ri-image-line text-secondary fs-4"></i>
                          </div>
                        @endif
                        <div>
                          <div class="fw-bold mb-1" style="color: #1a1a2e; font-size: 14px;">
                            {{ Str::limit($product->product_name, 35) }}
                          </div>
                          <div class="d-flex align-items-center">
                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11px; font-weight: 500;">
                              <i class="ri-price-tag-3-line"></i> {{ Str::limit($product->category_name ?? 'Uncategorized', 20) }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                      <div class="fw-bold text-primary" style="font-size: 15px;">
                        ৳{{ number_format($product->product_price ?? ($product->revenue / max($product->total_sold, 1)), 0) }}
                      </div>
                      <small class="text-muted">per unit</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-info-subtle text-info px-3 py-2 border-0" style="font-size: 14px;">
                        {{ number_format($product->total_sold) }}
                      </span>
                    </td>
                    <td class="text-center">
                      <div class="fw-bold text-success" style="font-size: 15px;">
                        ৳{{ number_format($product->revenue, 0) }}
                      </div>
                      <small class="text-muted">total</small>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-danger-subtle text-danger px-3 py-2 border-0">
                        <i class="ri-fire-fill"></i> Hot
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                      <i class="ri-shopping-bag-line fs-1 d-block mb-2 opacity-50"></i>
                      No sales data available
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Orders & Low Stock -->
  <div class="row g-4 mb-4">
    <!-- Recent Orders -->
    <div class="col-lg-12">
      <div class="card modern-card border-0 shadow-hover">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
                <i class="ri-file-list-3-line me-2 text-primary"></i>Recent Orders
              </h5>
              <p class="text-muted small mb-0">Latest customer orders</p>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
              <i class="ri-arrow-right-line"></i> View All
            </a>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="border-0 px-4 py-3 fw-semibold text-muted">Order ID</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Customer</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-end">Amount</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Status</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentOrders as $order)
                  <tr class="align-middle">
                    <td class="px-4">
                      <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none fw-bold text-primary">
                        {{ $order->order_number }}
                      </a>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar-circle-sm bg-primary bg-opacity-10 text-primary me-2">
                          {{ strtoupper(substr($order->user ? $order->user->first_name : 'G', 0, 1)) }}
                        </div>
                        <span class="fw-medium">{{ $order->user ? $order->user->full_name : 'Guest' }}</span>
                      </div>
                    </td>
                    <td class="text-end fw-bold" style="color: #1a1a2e;">৳{{ number_format($order->total_amount, 0) }}</td>
                    <td>
                      @if($order->status === 'delivered')
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill border-0">
                          <i class="ri-checkbox-circle-line"></i> {{ ucfirst($order->status) }}
                        </span>
                      @elseif($order->status === 'cancelled')
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill border-0">
                          <i class="ri-close-circle-line"></i> {{ ucfirst($order->status) }}
                        </span>
                      @else
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill border-0">
                          <i class="ri-time-line"></i> {{ ucfirst($order->status) }}
                        </span>
                      @endif
                    </td>
                    <td>
                      <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                      <i class="ri-shopping-cart-line fs-1 d-block mb-2 opacity-50"></i>
                      No recent orders
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Customers -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <div class="card modern-card border-0 shadow-hover">
        <div class="card-header bg-transparent border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1 fw-bold" style="color: #1a1a2e;">
                <i class="ri-user-star-line me-2 text-success"></i>Recent Customers
              </h5>
              <p class="text-muted small mb-0">Latest registered users</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
              <i class="ri-arrow-right-line"></i> View All
            </a>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="border-0 px-4 py-3 fw-semibold text-muted">Name</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Email</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Phone</th>
                  <th class="border-0 py-3 fw-semibold text-muted">Joined</th>
                  <th class="border-0 py-3 fw-semibold text-muted text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentCustomers as $customer)
                  <tr class="align-middle">
                    <td class="px-4">
                      <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-gradient-primary text-white me-3 shadow-sm">
                          {{ strtoupper(substr($customer->first_name, 0, 1)) }}
                        </div>
                        <span class="fw-semibold" style="color: #1a1a2e;">{{ $customer->full_name }}</span>
                      </div>
                    </td>
                    <td>
                      <span class="text-muted">
                        <i class="ri-mail-line me-1"></i>{{ $customer->email }}
                      </span>
                    </td>
                    <td>
                      <span class="text-muted">
                        <i class="ri-phone-line me-1"></i>{{ $customer->phone ?? 'N/A' }}
                      </span>
                    </td>
                    <td>
                      <small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                    </td>
                    <td class="text-end">
                      <a href="{{ route('users.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="ri-eye-line"></i> View
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                      <i class="ri-user-line fs-1 d-block mb-2 opacity-50"></i>
                      No customers yet
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function(){
    // Revenue Chart
    const revenueData = @json($revenueChart);
    const revenueDates = revenueData.map(d => new Date(d.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
    const revenueValues = revenueData.map(d => Number(d.revenue));
    const orderCounts = revenueData.map(d => Number(d.orders));

    const ctx1 = document.getElementById('revenueChart');
    if (ctx1) {
      new Chart(ctx1, {
        type: 'line',
        data: {
          labels: revenueDates,
          datasets: [{
            label: 'Revenue (৳)',
            data: revenueValues,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            fill: true,
            tension: 0.4,
            yAxisID: 'y'
          }, {
            label: 'Orders',
            data: orderCounts,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            fill: true,
            tension: 0.4,
            yAxisID: 'y1'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false,
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              beginAtZero: true
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',
              beginAtZero: true,
              grid: {
                drawOnChartArea: false,
              },
            },
          },
          plugins: {
            legend: {
              display: true,
              position: 'top',
            }
          }
        }
      });
    }

    // Order Status Chart
    const statusData = @json($ordersByStatus);
    const statusLabels = statusData.map(d => d.status.charAt(0).toUpperCase() + d.status.slice(1));
    const statusCounts = statusData.map(d => Number(d.count));
    
    const ctx2 = document.getElementById('orderStatusChart');
    if (ctx2) {
      new Chart(ctx2, {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusCounts,
            backgroundColor: [
              'rgba(255, 206, 86, 0.8)',
              'rgba(54, 162, 235, 0.8)',
              'rgba(153, 102, 255, 0.8)',
              'rgba(75, 192, 192, 0.8)',
              'rgba(255, 99, 132, 0.8)',
              'rgba(255, 159, 64, 0.8)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: true,
              position: 'bottom',
            }
          }
        }
      });
    }
  })();
</script>

<style>
  /* Modern Dashboard Styles */
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  }
  
  /* Gradient Text */
  .gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  /* Wave Hand Animation */
  .wave-hand {
    animation: wave 0.5s ease-in-out 3;
    display: inline-block;
  }
  
  @keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(20deg); }
    75% { transform: rotate(-20deg); }
  }
  
  /* Gradient Backgrounds */
  .bg-gradient-primary {
    background: var(--primary-gradient) !important;
  }
  
  .bg-gradient-success {
    background: var(--success-gradient) !important;
  }
  
  .bg-gradient-info {
    background: var(--info-gradient) !important;
  }
  
  .bg-gradient-warning {
    background: var(--warning-gradient) !important;
  }
  
  /* Stats Cards */
  .stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
  }
  
  .stats-card-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    opacity: 0.8;
  }
  
  .stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
  }
  
  .stats-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* Modern Cards */
  .modern-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #ffffff;
    border-radius: 16px;
  }
  
  .shadow-hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  }
  
  .modern-card:hover {
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1) !important;
  }
  
  /* Avatar Circles */
  .avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
  }
  
  .avatar-circle-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 13px;
  }
  
  /* Table Enhancements */
  .table-hover tbody tr {
    transition: all 0.2s ease;
  }
  
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
  }
  
  .hover-bg-light:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
  }
  
  /* Badge Styles */
  .badge {
    font-weight: 500;
    letter-spacing: 0.3px;
  }
  
  .bg-success-subtle {
    background-color: #d1f4e0 !important;
  }
  
  .bg-danger-subtle {
    background-color: #ffe0e0 !important;
  }
  
  .bg-info-subtle {
    background-color: #d1ecf1 !important;
  }
  
  .text-info {
    color: #0dcaf0 !important;
  }
  
  /* Progress Bars */
  .progress {
    border-radius: 8px;
    background-color: #e9ecef;
  }
  
  .progress-bar {
    border-radius: 8px;
    transition: width 0.6s ease;
  }
  
  /* Button Enhancements */
  .btn {
    transition: all 0.2s ease;
    font-weight: 500;
  }
  
  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  
  .btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }
  
  .btn-outline-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
  }
  
  /* Rounded Pills */
  .rounded-pill {
    border-radius: 50rem !important;
  }
  
  /* Card Header Improvements */
  .card-header.bg-transparent {
    background-color: transparent !important;
  }
  
  /* Smooth Scrolling */
  html {
    scroll-behavior: smooth;
  }
  
  /* Image Shadows */
  img.rounded-3, .rounded-3 {
    border-radius: 12px !important;
  }
  
  /* Custom Scrollbar */
  .table-responsive::-webkit-scrollbar {
    height: 8px;
  }
  
  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  
  .table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
  }
  
  .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
  }
</style>
@endpush

@endsection
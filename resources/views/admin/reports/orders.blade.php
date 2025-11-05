@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Order Report</h3>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
  </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-2">
      <label class="form-label">From</label>
      <input type="date" class="form-control" id="from" value="{{ $from }}">
    </div>
    <div class="col-md-2">
      <label class="form-label">To</label>
      <input type="date" class="form-control" id="to" value="{{ $to }}">
    </div>
    <div class="col-md-2">
      <label class="form-label">Status</label>
      <select class="form-select" id="status">
        <option value="">All</option>
        @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $s)
          <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Customer Name</label>
      <input type="text" class="form-control" id="customer" placeholder="Search customer..." value="{{ $customerSearch ?? '' }}">
    </div>
    <div class="col-md-2">
      <label class="form-label">Product Name</label>
      <input type="text" class="form-control" id="product" placeholder="Search product..." value="{{ $productSearch ?? '' }}">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100" id="apply-filters">Apply Filters</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Total Orders</div>
          <div class="h4 mb-0">{{ number_format($totalOrders) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Completed Orders</div>
          <div class="h4 mb-0 text-success">{{ number_format($completedOrders) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Cancelled Orders</div>
          <div class="h4 mb-0 text-danger">{{ number_format($cancelledOrders) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Pending Orders</div>
          <div class="h4 mb-0 text-warning">{{ number_format($pendingOrders) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Charts -->
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Orders by Status</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="statusChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Order Trends</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="trendsChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Orders by Status Table -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Order Summary by Status</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Status</th>
              <th class="text-end">Count</th>
              <th class="text-end">Total Value</th>
              <th class="text-end">Avg Value</th>
              <th class="text-end">% of Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse($ordersByStatus as $item)
              <tr>
                <td class="text-capitalize">{{ $item->status }}</td>
                <td class="text-end">{{ number_format($item->count) }}</td>
                <td class="text-end">৳ {{ number_format($item->total, 2) }}</td>
                <td class="text-end">৳ {{ number_format($item->avg, 2) }}</td>
                <td class="text-end">{{ number_format($item->percentage, 1) }}%</td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Detailed Orders Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Order Details</h5>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('admin.reports.orders.export') }}?from={{ $from }}&to={{ $to }}&status={{ $status }}&customer={{ $customerSearch ?? '' }}&product={{ $productSearch ?? '' }}&format=csv">
            <i class="bi bi-filetype-csv"></i> Export as CSV
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.orders.export') }}?from={{ $from }}&to={{ $to }}&status={{ $status }}&customer={{ $customerSearch ?? '' }}&product={{ $productSearch ?? '' }}&format=excel">
            <i class="bi bi-file-earmark-excel"></i> Export as Excel
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.orders.export') }}?from={{ $from }}&to={{ $to }}&status={{ $status }}&customer={{ $customerSearch ?? '' }}&product={{ $productSearch ?? '' }}&format=pdf">
            <i class="bi bi-file-earmark-pdf"></i> Export as PDF
          </a></li>
        </ul>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0 table-hover">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Products</th>
              <th>Date</th>
              <th class="text-end">Items</th>
              <th class="text-end">Total</th>
              <th>Status</th>
              <th>Payment</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $order)
              <tr>
                <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                <td>{{ $order->user ? $order->user->full_name : 'Guest' }}</td>
                <td>
                  <small class="text-muted">
                    @if($order->items_count > 0)
                      {{ $order->items->take(2)->pluck('product_name')->join(', ') }}
                      @if($order->items_count > 2)
                        <span class="text-primary">+{{ $order->items_count - 2 }} more</span>
                      @endif
                    @else
                      -
                    @endif
                  </small>
                </td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td class="text-end">{{ $order->items_count }}</td>
                <td class="text-end">৳ {{ number_format($order->total_amount, 2) }}</td>
                <td>
                  <span class="badge bg-light text-dark text-capitalize">{{ $order->status }}</span>
                </td>
                <td>
                  <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'secondary') }}">
                    {{ str_replace('_', ' ', $order->payment_status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="text-center text-muted py-3">No order data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($orders->hasPages())
      <div class="card-footer">
        {{ $orders->links() }}
      </div>
    @endif
  </div>
</div>

@push('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function(){
    document.getElementById('apply-filters').addEventListener('click', function(){
      const from = document.getElementById('from').value;
      const to = document.getElementById('to').value;
      const status = document.getElementById('status').value;
      const customer = document.getElementById('customer').value;
      const product = document.getElementById('product').value;
      const params = new URLSearchParams({ from, to, status, customer, product });
      window.location = `{{ route('admin.reports.orders') }}?` + params.toString();
    });

    // Status Chart
    const statusData = @json($ordersByStatus);
    const statusLabels = statusData.map(d => d.status);
    const statusValues = statusData.map(d => Number(d.count));
    const ctx1 = document.getElementById('statusChart');
    if (ctx1) {
      new Chart(ctx1, {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusValues,
            backgroundColor: [
              'rgba(255, 206, 86, 0.8)',
              'rgba(54, 162, 235, 0.8)',
              'rgba(153, 102, 255, 0.8)',
              'rgba(75, 192, 192, 0.8)',
              'rgba(255, 99, 132, 0.8)',
              'rgba(255, 159, 64, 0.8)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });
    }

    // Trends Chart
    const trendsData = @json($orderTrends);
    const trendsLabels = trendsData.map(d => d.period);
    const trendsValues = trendsData.map(d => Number(d.count));
    const ctx2 = document.getElementById('trendsChart');
    if (ctx2) {
      new Chart(ctx2, {
        type: 'line',
        data: {
          labels: trendsLabels,
          datasets: [{
            label: 'Orders',
            data: trendsValues,
            borderColor: 'rgba(78, 115, 223, 1)',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    }
  })();
</script>
@endpush
@endsection

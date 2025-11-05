@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Sales Report</h3>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
  </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" class="form-control" id="from" value="{{ $from }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" class="form-control" id="to" value="{{ $to }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Period</label>
      <select class="form-select" id="period">
        <option value="daily" @selected($period === 'daily')>Daily</option>
        <option value="weekly" @selected($period === 'weekly')>Weekly</option>
        <option value="monthly" @selected($period === 'monthly')>Monthly</option>
        <option value="yearly" @selected($period === 'yearly')>Yearly</option>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary w-100" id="apply-filters">Apply Filters</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Total Sales</div>
          <div class="h4 mb-0">৳ {{ number_format($totalSales, 2) }}</div>
        </div>
      </div>
    </div>
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
          <div class="text-muted small">Average Order Value</div>
          <div class="h4 mb-0">৳ {{ number_format($avgOrderValue, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Items Sold</div>
          <div class="h4 mb-0">{{ number_format($totalItems) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sales Chart -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Sales Trend ({{ ucfirst($period) }})</h5>
    </div>
    <div class="card-body">
      <div style="height: 350px;">
        <canvas id="salesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Sales by Status -->
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Sales by Order Status</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0">
              <thead>
                <tr>
                  <th>Status</th>
                  <th class="text-end">Orders</th>
                  <th class="text-end">Revenue</th>
                </tr>
              </thead>
              <tbody>
                @forelse($salesByStatus as $item)
                  <tr>
                    <td class="text-capitalize">{{ $item->status }}</td>
                    <td class="text-end">{{ number_format($item->count) }}</td>
                    <td class="text-end">৳ {{ number_format($item->revenue, 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted py-3">No data</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Sales by Payment Method</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0">
              <thead>
                <tr>
                  <th>Payment Method</th>
                  <th class="text-end">Transactions</th>
                  <th class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody>
                @forelse($salesByPayment as $item)
                  <tr>
                    <td class="text-capitalize">{{ $item->gateway ?? 'N/A' }}</td>
                    <td class="text-end">{{ number_format($item->count) }}</td>
                    <td class="text-end">৳ {{ number_format($item->amount, 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted py-3">No data</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Sales Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Sales Details</h5>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('admin.reports.sales.export') }}?from={{ $from }}&to={{ $to }}&period={{ $period }}&format=csv">
            <i class="bi bi-filetype-csv"></i> Export as CSV
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.sales.export') }}?from={{ $from }}&to={{ $to }}&period={{ $period }}&format=excel">
            <i class="bi bi-file-earmark-excel"></i> Export as Excel
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.sales.export') }}?from={{ $from }}&to={{ $to }}&period={{ $period }}&format=pdf">
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
              <th>Period</th>
              <th class="text-end">Orders</th>
              <th class="text-end">Items</th>
              <th class="text-end">Revenue</th>
              <th class="text-end">Avg Order</th>
            </tr>
          </thead>
          <tbody>
            @forelse($salesData as $row)
              <tr>
                <td>{{ $row->period }}</td>
                <td class="text-end">{{ number_format($row->orders) }}</td>
                <td class="text-end">{{ number_format($row->items) }}</td>
                <td class="text-end">৳ {{ number_format($row->revenue, 2) }}</td>
                <td class="text-end">৳ {{ number_format($row->avg_order, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No sales data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@push('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function(){
    document.getElementById('apply-filters').addEventListener('click', function(){
      const from = document.getElementById('from').value;
      const to = document.getElementById('to').value;
      const period = document.getElementById('period').value;
      const params = new URLSearchParams({ from, to, period });
      window.location = `{{ route('admin.reports.sales') }}?` + params.toString();
    });

    const data = @json($salesData);
    const labels = data.map(d => d.period);
    const values = data.map(d => Number(d.revenue));
    const ctx = document.getElementById('salesChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'Revenue',
            data: values,
            backgroundColor: 'rgba(78, 115, 223, 0.8)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
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

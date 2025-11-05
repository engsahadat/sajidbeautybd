@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Customer Report</h3>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
  </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
      <label class="form-label">From</label>
      <input type="date" class="form-control" id="from" value="{{ $from }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">To</label>
      <input type="date" class="form-control" id="to" value="{{ $to }}">
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary w-100" id="apply-filters">Apply Filters</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Total Customers</div>
          <div class="h4 mb-0">{{ number_format($totalCustomers) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">New Customers</div>
          <div class="h4 mb-0">{{ number_format($newCustomers) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Returning Customers</div>
          <div class="h4 mb-0">{{ number_format($returningCustomers) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Total Customer Value</div>
          <div class="h4 mb-0">৳ {{ number_format($totalCustomerValue, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Charts -->
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Top 10 Customers by Revenue</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="topCustomersChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Customer Distribution</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="customerDistChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Customers Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Customer Details</h5>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('admin.reports.customers.export') }}?from={{ $from }}&to={{ $to }}&format=csv">
            <i class="bi bi-filetype-csv"></i> Export as CSV
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.customers.export') }}?from={{ $from }}&to={{ $to }}&format=excel">
            <i class="bi bi-file-earmark-excel"></i> Export as Excel
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.customers.export') }}?from={{ $from }}&to={{ $to }}&format=pdf">
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
              <th>Customer</th>
              <th>Email</th>
              <th class="text-end">Orders</th>
              <th class="text-end">Total Spent</th>
              <th class="text-end">Avg Order</th>
              <th>Last Order</th>
            </tr>
          </thead>
          <tbody>
            @forelse($customers as $customer)
              <tr>
                <td>{{ $customer->full_name }}</td>
                <td>{{ $customer->email }}</td>
                <td class="text-end">{{ number_format($customer->orders_count) }}</td>
                <td class="text-end">৳ {{ number_format($customer->total_spent, 2) }}</td>
                <td class="text-end">৳ {{ number_format($customer->avg_order, 2) }}</td>
                <td>{{ $customer->last_order ? \Carbon\Carbon::parse($customer->last_order)->format('Y-m-d') : 'N/A' }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-3">No customer data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($customers->hasPages())
      <div class="card-footer">
        {{ $customers->links() }}
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
      const params = new URLSearchParams({ from, to });
      window.location = `{{ route('admin.reports.customers') }}?` + params.toString();
    });

    // Top Customers Chart
    const topData = @json($topCustomers);
    const topLabels = topData.map(d => `${d.first_name} ${d.last_name}`);
    const topValues = topData.map(d => Number(d.total_spent));
    const ctx1 = document.getElementById('topCustomersChart');
    if (ctx1) {
      new Chart(ctx1, {
        type: 'bar',
        data: {
          labels: topLabels,
          datasets: [{
            label: 'Total Spent',
            data: topValues,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
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

    // Distribution pie chart
    const distData = {
      new: {{ $newCustomers }},
      returning: {{ $returningCustomers }}
    };
    const ctx2 = document.getElementById('customerDistChart');
    if (ctx2) {
      new Chart(ctx2, {
        type: 'doughnut',
        data: {
          labels: ['New Customers', 'Returning Customers'],
          datasets: [{
            data: [distData.new, distData.returning],
            backgroundColor: ['rgba(255, 99, 132, 0.8)', 'rgba(75, 192, 192, 0.8)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)'],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });
    }
  })();
</script>
@endpush
@endsection

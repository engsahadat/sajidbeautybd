@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Payment Report</h3>
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
      <label class="form-label">Gateway</label>
      <select class="form-select" id="gateway">
        <option value="">All</option>
        @foreach(['sslcommerz','bkash','cod','bank_transfer'] as $g)
          <option value="{{ $g }}" @selected($gateway === $g)>{{ ucfirst(str_replace('_', ' ', $g)) }}</option>
        @endforeach
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
          <div class="text-muted small">Total Payments</div>
          <div class="h4 mb-0">{{ number_format($totalPayments) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Completed</div>
          <div class="h4 mb-0 text-success">৳ {{ number_format($completedAmount, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Refunded</div>
          <div class="h4 mb-0 text-warning">৳ {{ number_format($refundedAmount, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Failed</div>
          <div class="h4 mb-0 text-danger">৳ {{ number_format($failedAmount, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Charts -->
  <div class="row g-3 mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Payments by Gateway</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="gatewayChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">Payment Status Distribution</h5>
        </div>
        <div class="card-body">
          <div style="height: 300px;">
            <canvas id="statusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Payments by Gateway Table -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Payment Summary by Gateway</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Gateway</th>
              <th class="text-end">Count</th>
              <th class="text-end">Completed</th>
              <th class="text-end">Failed</th>
              <th class="text-end">Refunded</th>
            </tr>
          </thead>
          <tbody>
            @forelse($paymentsByGateway as $item)
              <tr>
                <td class="text-capitalize">{{ $item->gateway ?? 'N/A' }}</td>
                <td class="text-end">{{ number_format($item->count) }}</td>
                <td class="text-end">৳ {{ number_format($item->completed, 2) }}</td>
                <td class="text-end">৳ {{ number_format($item->failed, 2) }}</td>
                <td class="text-end">৳ {{ number_format($item->refunded, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Detailed Payments Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Payment Transactions</h5>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('admin.reports.payments.export') }}?from={{ $from }}&to={{ $to }}&gateway={{ $gateway }}&format=csv">
            <i class="bi bi-filetype-csv"></i> Export as CSV
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.payments.export') }}?from={{ $from }}&to={{ $to }}&gateway={{ $gateway }}&format=excel">
            <i class="bi bi-file-earmark-excel"></i> Export as Excel
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.payments.export') }}?from={{ $from }}&to={{ $to }}&gateway={{ $gateway }}&format=pdf">
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
              <th>Transaction ID</th>
              <th>Order #</th>
              <th>Gateway</th>
              <th class="text-end">Amount</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payments as $payment)
              <tr>
                <td><small class="text-monospace">{{ $payment->transaction_id ?? 'N/A' }}</small></td>
                <td><a href="{{ route('orders.show', $payment->order_id) }}">{{ $payment->order->order_number ?? '#'.$payment->order_id }}</a></td>
                <td class="text-capitalize">{{ $payment->gateway ?? 'N/A' }}</td>
                <td class="text-end">৳ {{ number_format($payment->amount, 2) }}</td>
                <td>
                  <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($payment->status) }}
                  </span>
                </td>
                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-3">No payment data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($payments->hasPages())
      <div class="card-footer">
        {{ $payments->links() }}
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
      const gateway = document.getElementById('gateway').value;
      const params = new URLSearchParams({ from, to, gateway });
      window.location = `{{ route('admin.reports.payments') }}?` + params.toString();
    });

    // Gateway Chart
    const gatewayData = @json($paymentsByGateway);
    const gatewayLabels = gatewayData.map(d => d.gateway || 'N/A');
    const gatewayValues = gatewayData.map(d => Number(d.completed));
    const ctx1 = document.getElementById('gatewayChart');
    if (ctx1) {
      new Chart(ctx1, {
        type: 'bar',
        data: {
          labels: gatewayLabels,
          datasets: [{
            label: 'Completed Amount',
            data: gatewayValues,
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

    // Status Distribution
    const statusData = {
      completed: {{ $completedAmount }},
      refunded: {{ $refundedAmount }},
      failed: {{ $failedAmount }}
    };
    const ctx2 = document.getElementById('statusChart');
    if (ctx2) {
      new Chart(ctx2, {
        type: 'pie',
        data: {
          labels: ['Completed', 'Refunded', 'Failed'],
          datasets: [{
            data: [statusData.completed, statusData.refunded, statusData.failed],
            backgroundColor: ['rgba(75, 192, 192, 0.8)', 'rgba(255, 206, 86, 0.8)', 'rgba(255, 99, 132, 0.8)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 206, 86, 1)', 'rgba(255, 99, 132, 1)'],
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

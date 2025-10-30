@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" class="form-control" id="from" value="{{ $from }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" class="form-control" id="to" value="{{ $to }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Order Status</label>
      <select class="form-select" id="status">
        <option value="">All</option>
        @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $s)
          <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Payment Status</label>
      <select class="form-select" id="payment_status">
        <option value="">All</option>
        @foreach(['pending','paid','failed','refunded','partially_refunded'] as $ps)
          <option value="{{ $ps }}" @selected(($filters['payment_status'] ?? '') === $ps)>{{ ucfirst(str_replace('_',' ', $ps)) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" id="apply-filters">Apply Filters</button>
    </div>
  </div>

  <hr class="my-4">

  <div class="row g-3">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Orders</div>
          <div class="h4 mb-0">{{ number_format($ordersCount) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Revenue</div>
          <div class="h4 mb-0">৳ {{ number_format($revenue, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Avg Order Value</div>
          <div class="h4 mb-0">৳ {{ number_format($avgOrder, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Net Paid - Refunded</div>
          <div class="h4 mb-0">৳ {{ number_format(max(0, $paid - $refunded), 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4 g-4">
    <div class="col-lg-7">
      <div class="card h-100">
        <div class="card-header"><h5 class="mb-0">Daily Revenue</h5></div>
        <div class="card-body">
          <div id="daily-chart" style="height: 280px;">
            <canvas id="dailyRevenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card h-100">
        <div class="card-header"><h5 class="mb-0">Top Products</h5></div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0">
              <thead>
                <tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Sales</th></tr>
              </thead>
              <tbody>
                @forelse($topProducts as $tp)
                  <tr>
                    <td>{{ $tp->product->name ?? ('#'.$tp->product_id) }}</td>
                    <td class="text-end">{{ (int)$tp->qty }}</td>
                    <td class="text-end">৳ {{ number_format($tp->sales, 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Recent Orders</h5>
      {{-- Optional: CSV export button can go here later --}}
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0 table-striped">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Date</th>
              <th class="text-end">Total</th>
              <th>Status</th>
              <th>Payment</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $o)
              <tr>
                <td><a href="{{ route('orders.show', $o->id) }}">{{ $o->order_number }}</a></td>
                <td>{{ $o->created_at->format('Y-m-d H:i') }}</td>
                <td class="text-end">৳ {{ number_format($o->total_amount, 2) }}</td>
                <td><span class="badge bg-light text-dark text-capitalize">{{ $o->status }}</span></td>
                <td><span class="badge bg-{{ $o->payment_status === 'paid' ? 'success' : ($o->payment_status === 'failed' ? 'danger' : 'secondary') }}">{{ str_replace('_',' ', $o->payment_status) }}</span></td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">No recent orders</td></tr>
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
    function applyFilters(){
      const from = document.getElementById('from').value;
      const to = document.getElementById('to').value;
      const status = document.getElementById('status').value;
      const ps = document.getElementById('payment_status').value;
      const params = new URLSearchParams({ from, to, status, payment_status: ps });
      window.location = `{{ route('admin.reports.index') }}?` + params.toString();
    }
    document.getElementById('apply-filters').addEventListener('click', applyFilters);

    const data = @json($daily);
    const labels = data.map(d => d.day);
    const values = data.map(d => Number(d.total));
    const ctx = document.getElementById('dailyRevenueChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label: 'Revenue', data: values, borderColor: '#4e73df', backgroundColor:'rgba(78,115,223,.1)', fill: true, tension:.25 }]},
        options: {
          scales: { y: { beginAtZero: true } },
          plugins: { legend: { display: false } }
        }
      });
    }
  })();
</script>
@endpush
@endsection

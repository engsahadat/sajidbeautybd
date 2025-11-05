@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Product Report</h3>
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
      <label class="form-label">Category</label>
      <select class="form-select" id="category">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
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
          <div class="text-muted small">Total Products Sold</div>
          <div class="h4 mb-0">{{ number_format($totalProductsSold) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Unique Products</div>
          <div class="h4 mb-0">{{ number_format($uniqueProducts) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Total Revenue</div>
          <div class="h4 mb-0">৳ {{ number_format($totalRevenue, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="text-muted small">Avg Price per Item</div>
          <div class="h4 mb-0">৳ {{ number_format($avgPrice, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Products Chart -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Top 10 Products by Revenue</h5>
    </div>
    <div class="card-body">
      <div style="height: 350px;">
        <canvas id="productsChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Detailed Product Report Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Product Sales Details</h5>
      <div class="btn-group">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-download"></i> Export
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('admin.reports.products.export') }}?from={{ $from }}&to={{ $to }}&category={{ $categoryId }}&format=csv">
            <i class="bi bi-filetype-csv"></i> Export as CSV
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.products.export') }}?from={{ $from }}&to={{ $to }}&category={{ $categoryId }}&format=excel">
            <i class="bi bi-file-earmark-excel"></i> Export as Excel
          </a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.products.export') }}?from={{ $from }}&to={{ $to }}&category={{ $categoryId }}&format=pdf">
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
              <th>Product</th>
              <th>Category</th>
              <th class="text-end">Quantity Sold</th>
              <th class="text-end">Revenue</th>
              <th class="text-end">Avg Price</th>
              <th class="text-end">Stock</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $item)
              <tr>
                <td>
                  <a href="{{ route('products.edit', $item->product_id) }}">{{ $item->product_name }}</a>
                </td>
                <td>{{ $item->category_name ?? 'N/A' }}</td>
                <td class="text-end">{{ number_format($item->quantity) }}</td>
                <td class="text-end">৳ {{ number_format($item->revenue, 2) }}</td>
                <td class="text-end">৳ {{ number_format($item->avg_price, 2) }}</td>
                <td class="text-end">{{ number_format($item->stock ?? 0) }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-3">No product data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($products->hasPages())
      <div class="card-footer">
        {{ $products->links() }}
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
      const category = document.getElementById('category').value;
      const params = new URLSearchParams({ from, to, category });
      window.location = `{{ route('admin.reports.products') }}?` + params.toString();
    });

    const data = @json($topProducts);
    const labels = data.map(d => d.product_name);
    const values = data.map(d => Number(d.revenue));
    const ctx = document.getElementById('productsChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'horizontalBar',
        data: {
          labels,
          datasets: [{
            label: 'Revenue',
            data: values,
            backgroundColor: 'rgba(28, 200, 138, 0.8)',
            borderColor: 'rgba(28, 200, 138, 1)',
            borderWidth: 1
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { beginAtZero: true }
          }
        }
      });
    }
  })();
</script>
@endpush
@endsection

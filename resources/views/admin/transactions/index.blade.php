@extends('admin.layouts.app')
@section('admin-title','Transactions')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row align-items-center">
      <div class="col-lg-6"><div class="page-header-left"><h3>Transactions</h3></div></div>
      <div class="col-lg-6">
        <ol class="breadcrumb pull-right mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
          <li class="breadcrumb-item active">Transactions</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="card"><div class="card-body">
    <form class="row g-2 mb-3" method="GET">
      <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search txn id or amount"></div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          @foreach(['pending','completed','failed','cancelled','refunded'] as $s)
            <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select name="gateway" class="form-select">
          <option value="">All Gateways</option>
          @foreach(['sslcommerz','bkash','nagad','cod','manual'] as $g)
            <option value="{{ $g }}" @selected(request('gateway')===$g)>{{ strtoupper($g) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary w-100">Filter</button>
      </div>
    </form>

    <div class="table-responsive transactions">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Order</th>
            <th>Method/Gateway</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Txn ID</th>
            <th>Processed</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td>
                @if($p->order)
                  <a href="{{ route('orders.show', $p->order->id) }}">{{ $p->order->order_number }}</a>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>{{ $p->payment_method }} @if($p->gateway) <span class="text-muted">({{ strtoupper($p->gateway) }})</span>@endif</td>
              <td>{{ number_format($p->amount,2) }} {{ $p->currency }}</td>
              <td><span class="badge bg-{{ $p->status==='completed'?'success':($p->status==='pending'?'warning text-dark':($p->status==='refunded'?'secondary':'danger')) }}">{{ ucfirst($p->status) }}</span></td>
              <td class="text-truncate" style="max-width:200px">{{ $p->transaction_id }}</td>
              <td>{{ $p->processed_at ? $p->processed_at->format('M d, Y h:i A') : '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">No transactions found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-2">{{ $payments->links() }}</div>
  </div></div>
</div>
@endsection

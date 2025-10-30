@extends('admin.layouts.app')
@section('admin-title','Orders')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header"><div class="row"><div class="col-lg-6"><div class="page-header-left"><h3>Orders</h3></div></div>
    <div class="col-lg-6"><ol class="breadcrumb pull-right"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li><li class="breadcrumb-item">Orders</li></ol></div></div></div>
</div>
<div class="container-fluid">
  <div class="card"><div class="card-body">
    <form method="GET" action="{{ route('orders.index') }}" class="d-flex flex-wrap gap-2 mb-3">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Order no. or name" style="max-width:220px;">
      <select name="status" class="form-select" style="max-width:180px;">
        <option value="">All Status</option>
        @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $st)
          <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
        @endforeach
      </select>
      <select name="payment_status" class="form-select" style="max-width:200px;">
        <option value="">All Payments</option>
        @foreach(['pending','paid','failed','refunded','partially_refunded'] as $ps)
          <option value="{{ $ps }}" {{ request('payment_status')===$ps?'selected':'' }}>{{ ucwords(str_replace('_',' ',$ps)) }}</option>
        @endforeach
      </select>
      <button class="btn btn-primary"><i class="fa fa-search"></i></button>
      <a class="btn btn-secondary" href="{{ route('orders.index') }}">Reset</a>
    </form>

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Order</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $idx=>$o)
            <tr>
              <td>{{ $orders->firstItem()+$idx }}</td>
              <td>{{ $o->order_number }}</td>
              <td>{{ $o->billing_first_name }} {{ $o->billing_last_name }}</td>
              <td>{{ number_format($o->total_amount,2) }}</td>
              <td><span class="badge bg-info">{{ ucfirst($o->status) }}</span></td>
              <td><span class="badge {{ $o->payment_status==='paid'?'bg-success':'bg-secondary' }}">{{ ucwords(str_replace('_',' ',$o->payment_status)) }}</span></td>
              <td>{{ $o->created_at?->format('Y-m-d H:i') }}</td>
              <td><a href="{{ route('orders.show',$o->id) }}"><i class="fa fa-eye"></i></a></td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted">No orders found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end">{{ $orders->links() }}</div>
  </div></div>
</div>
@endsection

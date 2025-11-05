@extends('admin.layouts.app')
@section('admin-title','Edit Order')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header"><div class="row"><div class="col-lg-6"><div class="page-header-left"><h3>Edit Order</h3></div></div><div class="col-lg-6"><ol class="breadcrumb pull-right"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li><li class="breadcrumb-item active">Edit</li></ol></div></div></div>
</div>
<div class="container-fluid">
  <div class="card"><div class="card-body">
    <form action="{{ route('orders.update', $order->id) }}" method="POST" class="row g-3">
      @csrf @method('PUT')
      <div class="col-md-4">
        <label class="form-label">Order Status</label>
        <select name="status" class="form-select">
          @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $st)
            <option value="{{ $st }}" {{ $order->status===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-select">
          @foreach(['pending','paid','failed','refunded','partially_refunded'] as $ps)
            <option value="{{ $ps }}" {{ $order->payment_status===$ps?'selected':'' }}>{{ ucwords(str_replace('_',' ',$ps)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
      </div>
      <div class="col-12"><button class="btn btn-primary">Save</button></div>
    </form>
  </div></div>
</div>
@endsection

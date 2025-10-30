@extends('admin.layouts.app')
@section('admin-title', 'Order Details')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-lg-6">
        <div class="page-header-left">
          <h3>Order {{ $order->order_number }}</h3>
        </div>
      </div>
      <div class="col-lg-6">
        <ol class="breadcrumb pull-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
          <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
          <li class="breadcrumb-item active">Details</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
    @if(session('message'))
      <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Items</h5>
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $item)
                    <tr>
                      <td>{{ $item->product_name }}</td>
                      <td>{{ $item->product_sku }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>{{ number_format($item->unit_price, 2) }}</td>
                      <td>{{ number_format($item->total_price, 2) }}</td>
                      <td>
                        <form action="{{ route('orders.items.destroy', [$order->id, $item->id]) }}" method="POST"
                          onsubmit="return confirm('Remove item?')">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <form action="{{ route('orders.items.store', $order->id) }}" method="POST" class="row g-2 mt-2">
              @csrf
              <div class="col-md-4">
                <input class="form-control" name="product_name" placeholder="Product name">
              </div>
              <div class="col-md-3">
                <input class="form-control" name="product_sku" placeholder="SKU">
              </div>
              <div class="col-md-2">
                <input class="form-control" name="quantity" type="number" min="1" value="1">
              </div>
              <div class="col-md-2">
                <input class="form-control" name="unit_price" type="number" min="0" step="0.01">
              </div>
              <div class="col-md-1">
                <button class="btn btn-success w-100"><i class="fa fa-plus"></i></button>
              </div>
            </form>
          </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
              <h5 class="mb-3">Payments</h5>
              @php($payments = $order->payments)
              @if($payments->isEmpty())
                <div class="text-muted small">No payments recorded yet.</div>
              @else
                <div class="table-responsive">
                  <table class="table table-sm align-middle">
                    <thead>
                      <tr>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Txn</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($payments as $p)
                        <tr>
                          <td>
                            <form action="{{ route('orders.payments.update', [$order->id, $p->id]) }}" method="POST"
                              class="d-flex gap-1">
                              @csrf @method('PUT')
                              <input name="payment_method" class="form-control form-control-sm" style="max-width:120px"
                                value="{{ $p->payment_method }}">
                              <input name="amount" type="number" step="0.01" class="form-control form-control-sm"
                                style="max-width:110px" value="{{ $p->amount }}">
                              <select name="status" class="form-select form-select-sm" style="max-width:130px">
                                @foreach(['pending', 'completed', 'failed', 'cancelled', 'refunded'] as $s)
                                  <option value="{{ $s }}" {{ $p->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                              </select>
                              <input name="transaction_id" class="form-control form-control-sm" style="max-width:140px"
                                placeholder="Txn ID" value="{{ $p->transaction_id }}">
                              <button class="btn btn-sm btn-outline-primary">Save</button>
                            </form>
                          </td>
                          <td>{{ number_format($p->amount, 2) }} {{ $p->currency }}</td>
                          <td><span
                              class="badge bg-{{ $p->status === 'completed' ? 'success' : ($p->status === 'pending' ? 'warning text-dark' : ($p->status === 'refunded' ? 'secondary' : 'danger')) }}">{{ ucfirst($p->status) }}</span>
                          </td>
                          <td class="text-truncate" style="max-width:160px">{{ $p->transaction_id }}</td>
                          <td>
                            <form action="{{ route('orders.payments.destroy', [$order->id, $p->id]) }}" method="POST"
                              onsubmit="return confirm('Delete payment?')">
                              @csrf @method('DELETE')
                              <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif

              <hr>
              <form action="{{ route('orders.payments.store', $order->id) }}" method="POST" class="row g-2">
                @csrf
                <div class="col-12">
                  <div class="small text-muted">Add payment</div>
                </div>
                <div class="col-6"><input name="payment_method" class="form-control" placeholder="Method (e.g. COD, Manual)"
                    required value="COD"></div>
                <div class="col-6"><input name="amount" type="number" step="0.01" class="form-control" placeholder="Amount"
                    required value="{{ number_format(max(0, $order->total_amount - $order->paidAmount()), 2, '.', '') }}"></div>
                <div class="col-6"><input name="transaction_id" class="form-control" placeholder="Transaction ID"></div>
                <div class="col-3">
                  <select name="status" class="form-select">
                    @foreach(['completed', 'pending', 'failed', 'cancelled', 'refunded'] as $s)
                      <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-3"><button class="btn btn-success w-100">Add</button></div>
              </form>
            </div>
            <div class="mt-3">
              <a href="{{ route('orders.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Summary</h5>
            <div class="d-flex justify-content-between">
              <span>Subtotal</span><strong>{{ number_format($order->subtotal, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <span>Tax</span><strong>{{ number_format($order->tax_amount, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <span>Shipping</span><strong>{{ number_format($order->shipping_amount, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <span>Discount</span><strong>-{{ number_format($order->discount_amount, 2) }}</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <span>Total</span><strong>{{ number_format($order->total_amount, 2) }}</strong>
            </div>
          </div>
        </div>
        <div class="card mt-3">
          <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="row g-2">
              @csrf
              @method('PUT')
              <div class="col-12">
                <label class="form-label">Order Status</label>
                <select name="status" class="form-select">
                  @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'] as $st)
                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                  @endforeach
                </select>
                @if($order->shipped_at)
                  <small class="text-muted">Shipped: {{ $order->shipped_at->format('M d, Y h:i A') }}</small>
                @endif
                @if($order->delivered_at)
                  <small class="text-muted d-block">Delivered: {{ $order->delivered_at->format('M d, Y h:i A') }}</small>
                @endif
              </div>
              <div class="col-12">
                <label class="form-label">Payment Status</label>
                <select name="payment_status" class="form-select">
                  @foreach(['pending', 'paid', 'failed', 'refunded', 'partially_refunded'] as $ps)
                    <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>
                      {{ ucwords(str_replace('_', ' ', $ps)) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control"
                  rows="2">{{ $order->notes }}</textarea></div>
              <div class="col-12"><button class="btn btn-primary w-100">Update Status</button></div>
            </form>
          </div>
        </div>
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="mb-2">Customer</h5>
            <div>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</div>
            <div class="text-muted small">{{ $order->billing_address_line_1 }}, {{ $order->billing_city }},
              {{ $order->billing_postal_code }}, {{ $order->billing_country }}</div>
            @if($order->billing_phone)
              <div class="text-muted small">Phone: {{ $order->billing_phone }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
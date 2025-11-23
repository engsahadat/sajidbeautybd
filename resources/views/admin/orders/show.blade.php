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
    <div class="row mt-3">
      <div class="col-12">
        <div class="d-flex gap-2">
          <a href="{{ route('orders.invoice', $order) }}" class="btn btn-gradient-primary btn-invoice" target="_blank">
            <i data-feather="file-text" class="me-2"></i>
            <span>View Invoice</span>
          </a>
          <a href="{{ route('orders.invoice', ['order' => $order, 'format' => 'pdf']) }}" class="btn btn-gradient-danger btn-invoice">
            <i data-feather="download" class="me-2"></i>
            <span>Download PDF</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.btn-invoice {
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border: none;
  display: inline-flex;
  align-items: center;
  text-decoration: none;
}

.btn-invoice:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-invoice i {
  width: 18px;
  height: 18px;
}

.btn-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-gradient-primary:hover {
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  color: white;
}

.btn-gradient-danger {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
}

.btn-gradient-danger:hover {
  background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
  color: white;
}
</style>

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
                    <th>Variant</th>
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
                      <td>
                        <strong>{{ $item->product_name }}</strong>
                      </td>
                      <td>
                        @if($item->variant_display)
                          <span class="badge bg-info">{{ $item->variant_display }}</span>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td>{{ $item->product_sku }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>৳{{ number_format($item->unit_price, 2) }}</td>
                      <td><strong>৳{{ number_format($item->total_price, 2) }}</strong></td>
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
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between">
              <span>Subtotal</span><strong>৳{{ number_format($order->subtotal, 2) }}</strong>
            </div>
            @if($order->discount_amount > 0)
            <div class="d-flex justify-content-between mt-1 text-success">
              <span>Discount</span><strong>-৳{{ number_format($order->discount_amount, 2) }}</strong>
            </div>
            @endif
            @if($order->shipping_amount > 0)
            <div class="d-flex justify-content-between mt-1">
              <span>Delivery Charge</span><strong>৳{{ number_format($order->shipping_amount, 2) }}</strong>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="d-flex justify-content-between mt-1">
              <span>Tax</span><strong>৳{{ number_format($order->tax_amount, 2) }}</strong>
            </div>
            @endif
            <hr>
            <div class="d-flex justify-content-between">
              <span><strong>Total Amount</strong></span><strong class="text-primary fs-5">৳{{ number_format($order->total_amount, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-2 pt-2 border-top">
              <span>Paid Amount</span><strong class="text-success">৳{{ number_format($order->paidAmount(), 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-1">
              <span>Due Amount</span><strong class="text-danger">৳{{ number_format(max(0, $order->total_amount - $order->paidAmount()), 2) }}</strong>
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
            <h5 class="mb-3">Payment Information</h5>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Payment Method:</span>
              <strong>
                @if($order->payment_method === 'cod')
                  <span class="badge bg-info">Cash on Delivery</span>
                @elseif($order->payment_method === 'bkash')
                  <span class="badge bg-success">bKash</span>
                @elseif($order->payment_method === 'sslcommerz')
                  <span class="badge bg-primary">SSLCommerz</span>
                @else
                  <span class="badge bg-secondary">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                @endif
              </strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Payment Status:</span>
              <strong>
                @if($order->payment_method === 'cod' && $order->payment_status === 'pending')
                  <span class="badge bg-warning text-dark">Pending (COD)</span>
                @elseif($order->payment_method === 'cod' && $order->payment_status === 'paid')
                  <span class="badge bg-success">Paid (COD)</span>
                @else
                  <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning text-dark' : 'danger') }}">
                    {{ ucwords(str_replace('_', ' ', $order->payment_status)) }}
                  </span>
                @endif
              </strong>
            </div>
            @if($order->delivery_location)
            <div class="d-flex justify-content-between mt-2">
              <span class="text-muted">Delivery Location:</span>
              <strong>{{ $order->delivery_location === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}</strong>
            </div>
            @endif
          </div>
        </div>
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="mb-2">Customer Details</h5>
            <div><strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong></div>
            <div class="text-muted small mt-1">{{ $order->billing_address_line_1 }}, {{ $order->billing_city }},
              {{ $order->billing_postal_code }}, {{ $order->billing_country }}</div>
            @if($order->billing_phone)
              <div class="text-muted small mt-1"><i class="fa fa-phone"></i> {{ $order->billing_phone }}</div>
            @endif
            @if($order->user && $order->user->email)
              <div class="text-muted small mt-1"><i class="fa fa-envelope"></i> {{ $order->user->email }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
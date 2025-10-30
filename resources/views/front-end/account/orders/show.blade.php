@extends('front-end.layouts.app')
@section('content')
<section class="section-b-space mt-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Order {{ $order->order_number }}</h2>
      <div class="d-flex gap-2">
        <span class="badge bg-{{ $order->status==='delivered' ? 'success' : ($order->status==='shipped' ? 'primary' : ($order->status==='processing' ? 'info text-dark' : ($order->status==='cancelled' ? 'danger' : 'secondary'))) }}">{{ ucfirst($order->status) }}</span>
        <span class="badge bg-{{ $order->payment_status==='paid' ? 'success' : ($order->payment_status==='pending' ? 'warning text-dark' : ($order->payment_status==='refunded' ? 'secondary' : 'danger')) }}">{{ ucwords(str_replace('_',' ',$order->payment_status)) }}</span>
      </div>
    </div>
    @php
      $steps = ['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
      $orderStatus = strtolower($order->status ?? 'pending');
      $seenCurrent = false;
    @endphp
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3">Order Status</h5>
        <style>
          .order-steps{display:flex;gap:12px;justify-content:space-between}
          .order-step{flex:1;text-align:center}
          .order-step .dot{width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:600}
          .order-step.complete .dot{background:#22c55e;color:#fff}
          .order-step.current .dot{background:#0d6efd;color:#fff}
          .order-step.pending .dot{background:#e5e7eb;color:#6b7280}
          .order-step .label{display:block;margin-top:6px;font-size:.9rem}
          .order-step .bar{height:4px;background:#e5e7eb;margin-top:10px;border-radius:2px;position:relative}
          .order-step.complete .bar,.order-step.current .bar{background:#0d6efd}
        </style>
        <div class="order-steps">
          @foreach($steps as $key=>$label)
            @php
              $state = 'pending';
              if (!$seenCurrent) {
                $state = 'complete';
              }
              if ($key === $orderStatus) {
                $state = 'current';
                $seenCurrent = true;
              }
            @endphp
            <div class="order-step {{ $state }}">
              <div class="dot">{{ $state==='complete' ? '✓' : ($loop->iteration) }}</div>
              <span class="label">{{ $label }}</span>
              @if(!$loop->last)
                <div class="bar"></div>
              @endif
            </div>
          @endforeach
        </div>
        @if($order->shipped_at)
          <div class="small text-muted mt-2">Shipped: {{ $order->shipped_at->format('M d, Y h:i A') }}</div>
        @endif
        @if($order->delivered_at)
          <div class="small text-muted">Delivered: {{ $order->delivered_at->format('M d, Y h:i A') }}</div>
        @endif
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Items</h5>
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Unit</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $it)
                    <tr>
                      <td>{{ $it->product_name }}</td>
                      <td class="text-center">{{ $it->quantity }}</td>
                      <td class="text-end">{{ number_format($it->unit_price,2) }}</td>
                      <td class="text-end">{{ number_format($it->total_price,2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Subtotal</th>
                    <th class="text-end">{{ number_format($order->subtotal,2) }}</th>
                </tr>
                  <tr>
                    <th colspan="3" class="text-end">Shipping</th>
                    <th class="text-end">{{ number_format($order->shipping_amount,2) }}</th>
                </tr>
                  <tr>
                    <th colspan="3" class="text-end">Tax</th>
                    <th class="text-end">{{ number_format($order->tax_amount,2) }}</th>
                </tr>
                  <tr>
                    <th colspan="3" class="text-end">Discount</th>
                    <th class="text-end">-{{ number_format($order->discount_amount,2) }}</th>
                </tr>
                  <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end">{{ number_format($order->total_amount,2) }}</th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card mb-3"><div class="card-body">
          <h6 class="mb-2">Billing Address</h6>
          <div class="small">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</div>
          <div class="text-muted small">{{ $order->billing_address_line_1 }}<br>{{ $order->billing_city }}, {{ $order->billing_postal_code }}<br>{{ $order->billing_country }}</div>
          @if($order->billing_phone)<div class="text-muted small mt-1">Phone: {{ $order->billing_phone }}</div>@endif
        </div></div>
        <div class="card"><div class="card-body">
          <h6 class="mb-2">Shipping Address</h6>
          <div class="small">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</div>
          <div class="text-muted small">{{ $order->shipping_address_line_1 }}<br>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>{{ $order->shipping_country }}</div>
          @if($order->shipping_phone)<div class="text-muted small mt-1">Phone: {{ $order->shipping_phone }}</div>@endif
        </div></div>
      </div>
    </div>
    <div class="mt-4">
      <a href="{{ route('account.orders.index') }}" class="btn btn-secondary">Back to My Orders</a>
    </div>
  </div>
</section>
@endsection

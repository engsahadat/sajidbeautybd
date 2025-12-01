@extends('front-end.layouts.app')
@push('styles')
<style>
  .order-header {
    background-color: #f8f9fa;
    padding: 40px 0;
    margin-bottom: 40px;
  }
  .order-header h2 {
    color: #333;
    font-weight: 600;
  }
  @media (max-width: 768px) {
    .order-header {
      padding: 1.5rem 0;
    }
    .order-header h2 {
      font-size: 1.5rem;
    }
  }
  @media (max-width: 576px) {
    .order-header {
      padding: 1rem 0;
    }
    .order-header h2 {
      font-size: 1.25rem;
    }
  }
</style>
@endpush
@section('content')
<div class="order-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h2 class="mb-0">Order {{ $order->order_number }}</h2>
      <div class="d-flex gap-2">
        <span class="badge bg-{{ $order->status==='delivered' ? 'success' : ($order->status==='shipped' ? 'primary' : ($order->status==='processing' ? 'info text-dark' : ($order->status==='cancelled' ? 'danger' : 'secondary'))) }}">{{ ucfirst($order->status) }}</span>
        <span class="badge bg-{{ $order->payment_status==='paid' ? 'success' : ($order->payment_status==='pending' ? 'warning text-dark' : ($order->payment_status==='refunded' ? 'secondary' : 'danger')) }}">{{ ucwords(str_replace('_',' ',$order->payment_status)) }}</span>
      </div>
    </div>
  </div>
</div>

<section class="section-b-space mt-0">
  <div class="container">
    @php
      $steps = ['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
      $orderStatus = strtolower($order->status ?? 'pending');
      $seenCurrent = false;
    @endphp
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3">Order Status</h5>
        <style>
          .order-steps{display:flex;gap:0;justify-content:space-between;overflow-x:auto;overflow-y:hidden;padding-bottom:8px;align-items:flex-start}
          .order-steps::-webkit-scrollbar{height:6px}
          .order-steps::-webkit-scrollbar-track{background:#f1f1f1;border-radius:3px}
          .order-steps::-webkit-scrollbar-thumb{background:#EC8951;border-radius:3px}
          .order-steps::-webkit-scrollbar-thumb:hover{background:#d97438}
          .order-step{flex:1;text-align:center;min-width:100px;position:relative}
          .order-step .dot{width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:600;position:relative;z-index:2}
          .order-step.complete .dot{background:#22c55e;color:#fff}
          .order-step.current .dot{background:#0d6efd;color:#fff}
          .order-step.pending .dot{background:#e5e7eb;color:#6b7280}
          .order-step .label{display:block;margin-top:6px;font-size:.9rem;white-space:nowrap}
          .order-step .bar{height:4px;background:#e5e7eb;position:absolute;top:14px;left:50%;right:-50%;z-index:1}
          .order-step.complete .bar{background:#0d6efd}
          .order-step.current .bar{background:#0d6efd}
          .order-step:last-child .bar{display:none}
          @media (max-width:768px){
            .order-steps{gap:0}
            .order-step{min-width:80px}
            .order-step .dot{width:24px;height:24px;font-size:.8rem}
            .order-step .label{font-size:.75rem}
            .order-step .bar{top:12px}
          }
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
                      <td>
                        <strong>{{ $it->product_name }}</strong>
                        @if($it->variant_display)
                            <br><small class="text-muted">{{ $it->variant_display }}</small>
                        @endif
                      </td>
                      <td class="text-center">{{ $it->quantity }}</td>
                      <td class="text-end">৳{{ number_format($it->unit_price,2) }}</td>
                      <td class="text-end">৳{{ number_format($it->total_price,2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Subtotal</th>
                    <th class="text-end">৳{{ number_format($order->subtotal,2) }}</th>
                </tr>
                  <tr>
                    <th colspan="3" class="text-end">Shipping</th>
                    <th class="text-end">৳{{ number_format($order->shipping_amount,2) }}</th>
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
      <a href="{{ route('account.orders.index') }}" style="background-color: #EC8951; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#d97438'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#EC8951'; this.style.transform='translateY(0)';">Back to My Orders</a>
    </div>
  </div>
</section>
@endsection

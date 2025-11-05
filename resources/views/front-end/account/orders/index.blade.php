@extends('front-end.layouts.app')
@section('content')
<section class="section-b-space mt-4">
  <div class="container">
    <h2 class="mb-3">My Orders</h2>

    @if($orders->isEmpty())
      <div class="alert alert-info">You have no orders yet.</div>
      <a href="{{ url('/') }}" class="btn btn-primary">Start Shopping</a>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Order</th>
              <th>Date</th>
              <th>Status</th>
              <th>Payment</th>
              <th class="text-end">Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $o)
              <tr>
                <td>{{ $o->order_number }}</td>
                <td>{{ $o->created_at?->format('M d, Y h:i A') }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($o->status) }}</span></td>
                <td><span class="badge {{ $o->payment_status==='paid'?'bg-success':'bg-warning text-dark' }}">{{ ucwords(str_replace('_',' ', $o->payment_status)) }}</span></td>
                <td class="text-end">{{ number_format($o->total_amount,2) }}</td>
                <td class="text-end"><a href="{{ route('account.orders.show', $o->order_number) }}" class="btn btn-sm btn-outline-primary">View</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-end">{{ $orders->links() }}</div>
    @endif
  </div>
</section>
@endsection

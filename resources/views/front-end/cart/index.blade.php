@extends('front-end.layouts.app')
@section('content')
<section class="section-b-space mt-4">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="mb-3">Shopping Cart</h2>
      </div>
      <div class="col-lg-8">
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @php($cart=$cart)
              @if($cart && $cart->items->count())
                @foreach($cart->items as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="{{ $item->product?->image_url }}" onerror="this.onerror=null;this.src='{{ asset('images/default-image.png') }}'" width="60" height="60" style="object-fit:cover;border-radius:6px;"/>
                        <div>
                          <div class="fw-semibold">{{ $item->product?->name }}</div>
                        </div>
                      </div>
                    </td>
                    <td>{{ number_format($item->unit_price,2) }}</td>
                    <td>
                      <form action="{{ route('cart.item.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" style="width:90px;">
                        <button class="btn btn-sm btn-outline-primary">Update</button>
                      </form>
                    </td>
                    <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    <td>
                      <form action="{{ route('cart.item.remove', $item->id) }}" method="POST" onsubmit="return confirm('Remove item?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="5" class="text-center text-muted">Your cart is empty.</td></tr>
              @endif
            </tbody>
          </table>
        </div>
        @if($cart && $cart->items->count())
        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear cart?')" class="mt-2">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">Clear Cart</button>
        </form>
        @endif
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between">
              <span>Subtotal</span>
              <strong id="cart-subtotal">{{ number_format($cart?->subtotal() ?? 0,2) }}</strong>
            </div>
            <div class="mt-3">
              @if(session('message'))
                <div class="alert alert-success py-2 mb-2">{{ session('message') }}</div>
              @endif
              @if(session('error'))
                <div class="alert alert-danger py-2 mb-2">{{ session('error') }}</div>
              @endif
              @if($cart && $cart->coupon)
                <div class="d-flex justify-content-between align-items-center" id="applied-coupon">
                  <div>
                    <span class="text-success">Coupon Applied:</span>
                    <strong id="applied-coupon-code">{{ $cart->coupon->code }}</strong>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-danger" id="remove-coupon-btn" data-url="{{ route('cart.removeCoupon') }}">Remove</button>
                </div>
              @else
                <form id="coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex gap-2">
                  @csrf
                  <input type="text" name="code" id="coupon-code-input" class="form-control" placeholder="Coupon code">
                  <button type="button" class="btn btn-outline-primary" id="apply-coupon-btn">Apply</button>
                </form>
              @endif
            </div>
            @php($discount = $cart?->discount() ?? 0)
            <div class="d-flex justify-content-between mt-2 text-success" id="discount-row" style="display: {{ ($discount ?? 0) > 0 ? 'flex' : 'none' }}">
              <span>Discount</span>
              <strong id="cart-discount">-{{ number_format($discount,2) }}</strong>
            </div>
            <div class="d-flex justify-content-between mt-2">
              <span>Total</span>
              <strong id="cart-total">{{ number_format($cart?->total() ?? 0,2) }}</strong>
            </div>
            <a href="{{ route('checkout.show') }}" class="btn btn-primary w-100 mt-3">Proceed to Checkout</a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const couponForm = document.getElementById('coupon-form');
  const applyBtn = document.getElementById('apply-coupon-btn');
  const codeInput = document.getElementById('coupon-code-input');
  const removeBtn = document.getElementById('remove-coupon-btn');

  function showAlert(message, type='success'){
    const container = document.createElement('div');
    container.className = `alert alert-${type} py-2 mb-2`; 
    container.textContent = message;
    const cardBody = document.querySelector('.card .card-body');
    cardBody.insertBefore(container, cardBody.firstChild);
    setTimeout(()=> container.remove(), 4000);
  }

  if (couponForm) {
    couponForm.addEventListener('submit', function(e){
      e.preventDefault();
      const url = couponForm.action;
      const token = couponForm.querySelector('input[name=_token]').value;
      const code = codeInput.value.trim();
      if (!code) return;
      applyBtn.disabled = true;
      fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json','X-CSRF-TOKEN': token },
        body: new URLSearchParams({ code })
      }).then(async r=>{
        let data; try { data = await r.json(); } catch(_) { data = {}; }
        return { ok: r.ok, status: r.status, data };
      }).then(resp=>{
        applyBtn.disabled = false;
        const data = resp.data || {};
        if (data.success) {
          // update UI
          document.getElementById('cart-subtotal').textContent = Number(data.subtotal).toFixed(2);
          document.getElementById('cart-discount').textContent = '-' + Number(data.discount).toFixed(2);
          document.getElementById('cart-total').textContent = Number(data.total).toFixed(2);
          document.getElementById('discount-row').style.display = 'flex';
          // show applied coupon block
          const appliedHtml = `\n                        <div class="d-flex justify-content-between align-items-center" id="applied-coupon">\n                          <div>\n                            <span class="text-success">Coupon Applied:</span>\n                            <strong id="applied-coupon-code">${data.coupon.code}</strong>\n                          </div>\n                          <button class="btn btn-sm btn-outline-danger" id="remove-coupon-btn" data-url="${'{{ route('cart.removeCoupon') }}'}">Remove</button>\n                        </div>`;
          couponForm.outerHTML = appliedHtml;
          showAlert(data.message, 'success');
        } else {
          let msg = data.message || 'Failed to apply coupon';
          if (data.errors) {
            const first = Object.values(data.errors)[0];
            if (first && first.length) msg = first[0];
          }
          showAlert(msg, 'danger');
        }
      }).catch(err=>{
        applyBtn.disabled = false;
        showAlert('Failed to apply coupon', 'danger');
        console.error(err);
      });
    });
    if (applyBtn) {
      applyBtn.addEventListener('click', function(e){
        e.preventDefault();
        // Trigger the same submit handler
        if (typeof couponForm.requestSubmit === 'function') {
          couponForm.requestSubmit();
        } else {
          couponForm.dispatchEvent(new Event('submit', { cancelable: true }));
        }
      });
    }
  }

  // Delegate remove button because it may be added dynamically
  document.body.addEventListener('click', function(e){
    if (e.target && e.target.id === 'remove-coupon-btn') {
      e.preventDefault();
      const btn = e.target;
      const url = btn.getAttribute('data-url');
      // get CSRF token from a meta tag or existing form
      const tokenEl = document.querySelector('input[name=_token]');
      const token = tokenEl ? tokenEl.value : null;
      btn.disabled = true;
      fetch(url, { method: 'DELETE', headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': token } })
        .then(r=>r.json()).then(data=>{
          btn.disabled = false;
          if (data.success) {
            document.getElementById('cart-subtotal').textContent = Number(data.subtotal).toFixed(2);
            document.getElementById('cart-discount').textContent = '-0.00';
            document.getElementById('cart-total').textContent = Number(data.total).toFixed(2);
            document.getElementById('discount-row').style.display = 'none';
            // restore coupon form
            const formHtml = `\n<form id="coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex gap-2">\n  @csrf\n  <input type="text" name="code" id="coupon-code-input" class="form-control" placeholder="Coupon code" required>\n  <button class="btn btn-outline-primary" id="apply-coupon-btn">Apply</button>\n</form>`;
            const applied = document.getElementById('applied-coupon');
            if (applied) applied.outerHTML = formHtml;
            showAlert(data.message, 'success');
          } else {
            showAlert(data.message || 'Failed to remove coupon', 'danger');
          }
        }).catch(err=>{
          btn.disabled = false;
          showAlert('Failed to remove coupon', 'danger');
          console.error(err);
        });
    }
  });
});
</script>
@endpush
@endsection

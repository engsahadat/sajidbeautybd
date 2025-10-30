@extends('front-end.layouts.app')
@section('content')
<section class="section-b-space mt-4">
  <div class="container">
    <h2 class="mb-3">Checkout</h2>
    <style>
      .payment-options .pay-radio { position: absolute; opacity: 0; pointer-events: none; }
      .payment-options .pay-tile {
        display: block; border: 1px solid #e5e7eb; border-radius: .5rem; padding: .75rem 1rem; background: #fff;
        height: 150px; cursor: pointer; transition: all .2s ease; text-align: center;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
      }
      .payment-options .pay-tile:hover { border-color: #9ca3af; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
  .payment-options .pay-tile .pay-img { width: 100%; max-width: 100%; height: auto; max-height: 90px; object-fit: contain; margin-bottom: .3rem; display: block; }
      .payment-options .pay-tile .pay-name { font-weight: 600; font-size: .95rem; color: #374151; }
      .payment-options .pay-radio:checked + .pay-tile { border-color: #0d6efd; box-shadow: 0 0 0 .2rem rgba(13,110,253,.15); }
  @media (max-width: 576px) { .payment-options .pay-tile { height: 130px; } }
    </style>
    <div class="row g-4">
      <div class="col-lg-8">
        <form id="checkout-form" action="{{ route('checkout.place') }}" method="POST" class="row g-3">
          @csrf
          <div class="col-12">
            <h5>Billing Address</h5>
          </div>
          <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input name="billing_first_name" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input name="billing_last_name" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">Address</label>
            <input name="billing_address_line_1" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">City</label>
            <input name="billing_city" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Postal</label>
            <input name="billing_postal_code" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Country (ISO)</label>
            <input name="billing_country" maxlength="2" class="form-control" value="BD" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="billing_phone" class="form-control">
          </div>

          <div class="col-12 mt-3">
            <h5>Shipping Address</h5>
          </div>
          <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input name="shipping_first_name" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input name="shipping_last_name" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">Address</label>
            <input name="shipping_address_line_1" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">City</label>
            <input name="shipping_city" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Postal</label>
            <input name="shipping_postal_code" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Country (ISO)</label>
            <input name="shipping_country" maxlength="2" class="form-control" value="BD">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="shipping_phone" class="form-control">
          </div>
          <div class="col-12">
            <div class="row payment-options g-3">
              <div class="col-sm-6">
                <input class="pay-radio" type="radio" name="payment_method" id="pm_ssl" value="sslcommerz">
                <label class="pay-tile" for="pm_ssl">
         <img class="pay-img" src="{{ asset('images/pay_sslcommerz.svg') }}" alt="SSLCommerz"
                       onerror="this.style.display='none'">
                  <span class="pay-name">SSLCommerz</span>
                </label>
              </div>
              <div class="col-sm-6">
                <input class="pay-radio" type="radio" name="payment_method" id="pm_bkash" value="bkash">
                <label class="pay-tile" for="pm_bkash">
         <img class="pay-img" src="{{ asset('images/pay_bkash.svg') }}" alt="bKash"
                       onerror="this.style.display='none'">
                  <span class="pay-name">bKash</span>
                </label>
              </div>
              <div class="col-sm-6">
                <input class="pay-radio" type="radio" name="payment_method" id="pm_cod" value="cod" checked>
                <label class="pay-tile" for="pm_cod">
                  <img class="pay-img" src="{{ asset('images/pay_cod.svg') }}" alt="Cash on Delivery" onerror="this.style.display='none'">
                  <span class="pay-name">Cash on Delivery</span>
                </label>
              </div>
            </div>
          </div>

          <div class="col-12"><button id="place-order-btn" class="btn btn-primary">Place Order</button></div>
        </form>
      </div>
      <div class="col-lg-4">
        <div class="card"><div class="card-body">
          <h5 class="mb-3">Order Summary</h5>
          <ul class="list-unstyled">
            @foreach($cart->items as $item)
              <li class="d-flex justify-content-between mb-2">
                <span>{{ $item->product?->name }} x {{ $item->quantity }}</span>
                <span>{{ number_format($item->quantity * $item->unit_price,2) }}</span>
              </li>
            @endforeach
          </ul>
          <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="co-subtotal">{{ number_format($cart->subtotal(),2) }}</strong></div>
          @php($coDiscount = $cart?->discount() ?? 0)
          <div class="d-flex justify-content-between mt-2 text-success" id="co-discount-row" style="display: {{ ($coDiscount ?? 0) > 0 ? 'flex' : 'none' }}">
            <span>Discount</span>
            <strong id="co-discount">-{{ number_format($coDiscount,2) }}</strong>
          </div>
          <div class="mt-3">
            @if($cart && $cart->coupon)
              <div class="d-flex justify-content-between align-items-center" id="co-applied-coupon">
                <div>
                  <span class="text-success">Coupon Applied:</span>
                  <strong id="co-applied-coupon-code">{{ $cart->coupon->code }}</strong>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" id="co-remove-coupon-btn" data-url="{{ route('cart.removeCoupon') }}">Remove</button>
              </div>
            @else
              <form id="co-coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="code" id="co-coupon-code-input" class="form-control" placeholder="Coupon code">
                <button type="button" class="btn btn-outline-primary" id="co-apply-coupon-btn">Apply</button>
              </form>
            @endif
          </div>
          <div class="d-flex justify-content-between mt-2"><span>Total</span><strong id="co-total">{{ number_format($cart->total(),2) }}</strong></div>
        </div></div>
      </div>
    </div>
  </div>
</section>
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const coForm = document.getElementById('co-coupon-form');
  const coApply = document.getElementById('co-apply-coupon-btn');
  const coCode = document.getElementById('co-coupon-code-input');

  function coAlert(message, type='success'){
    const container = document.createElement('div');
    container.className = `alert alert-${type} py-2 mb-2`;
    container.textContent = message;
    const cardBody = document.querySelector('.col-lg-4 .card .card-body');
    cardBody.insertBefore(container, cardBody.firstChild);
    setTimeout(()=> container.remove(), 4000);
  }

  if (coForm) {
    const submitHandler = function(e){
      e.preventDefault();
      const url = coForm.action;
      const token = coForm.querySelector('input[name=_token]').value;
      const code = (coCode?.value || '').trim();
      if (!code) return;
      coApply.disabled = true;
      fetch(url, {
        method: 'POST',
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': token },
        body: new URLSearchParams({ code })
      }).then(async r=>{ let data={}; try{ data=await r.json(); }catch(_){} return { ok:r.ok, data }; })
      .then(resp=>{
        coApply.disabled = false;
        const data = resp.data || {};
        if (data.success) {
          document.getElementById('co-subtotal').textContent = Number(data.subtotal).toFixed(2);
          document.getElementById('co-discount').textContent = '-' + Number(data.discount).toFixed(2);
          document.getElementById('co-total').textContent = Number(data.total).toFixed(2);
          document.getElementById('co-discount-row').style.display = 'flex';
          const appliedHtml = `\n<div class="d-flex justify-content-between align-items-center" id="co-applied-coupon">\n  <div><span class="text-success">Coupon Applied:</span> <strong id="co-applied-coupon-code">${data.coupon.code}</strong></div>\n  <button type="button" class="btn btn-sm btn-outline-danger" id="co-remove-coupon-btn" data-url="${'{{ route('cart.removeCoupon') }}'}">Remove</button>\n</div>`;
          coForm.outerHTML = appliedHtml;
          coAlert(data.message, 'success');
        } else {
          coAlert(data.message || 'Failed to apply coupon', 'danger');
        }
      }).catch(_=>{ coApply.disabled=false; coAlert('Failed to apply coupon','danger'); });
    };
    coForm.addEventListener('submit', submitHandler);
    if (coApply) coApply.addEventListener('click', (e)=>{ e.preventDefault(); coForm.requestSubmit ? coForm.requestSubmit() : coForm.dispatchEvent(new Event('submit', { cancelable: true })); });
  }

  document.body.addEventListener('click', function(e){
    if (e.target && e.target.id === 'co-remove-coupon-btn') {
      e.preventDefault();
      const btn = e.target;
      const url = btn.getAttribute('data-url');
      const tokenEl = document.querySelector('input[name=_token]');
      const token = tokenEl ? tokenEl.value : null;
      btn.disabled = true;
      fetch(url, { method:'DELETE', headers: { 'Accept':'application/json','X-CSRF-TOKEN': token } })
      .then(async r=>{ let data={}; try{ data=await r.json(); }catch(_){} return { ok:r.ok, data }; })
      .then(resp=>{
        btn.disabled = false;
        const data = resp.data || {};
        if (data.success) {
          document.getElementById('co-subtotal').textContent = Number(data.subtotal).toFixed(2);
          document.getElementById('co-discount').textContent = '-0.00';
          document.getElementById('co-total').textContent = Number(data.total).toFixed(2);
          document.getElementById('co-discount-row').style.display = 'none';
          const formHtml = `\n<form id=\"co-coupon-form\" action=\"{{ route('cart.applyCoupon') }}\" method=\"POST\" class=\"d-flex gap-2\">\n  @csrf\n  <input type=\"text\" name=\"code\" id=\"co-coupon-code-input\" class=\"form-control\" placeholder=\"Coupon code\">\n  <button type=\"button\" class=\"btn btn-outline-primary\" id=\"co-apply-coupon-btn\">Apply</button>\n</form>`;
          const applied = document.getElementById('co-applied-coupon');
          if (applied) applied.outerHTML = formHtml;
          coAlert(data.message, 'success');
        } else {
          coAlert(data.message || 'Failed to remove coupon', 'danger');
        }
      }).catch(_=>{ btn.disabled=false; coAlert('Failed to remove coupon','danger'); });
    }
  });

  // Checkout form AJAX submit with inline errors
  const chkForm = document.getElementById('checkout-form');
  const placeBtn = document.getElementById('place-order-btn');

  function clearCheckoutErrors() {
    if (!chkForm) return;
    chkForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    chkForm.querySelectorAll('.invalid-feedback.error-inline').forEach(el => el.remove());
    const topAlert = document.getElementById('checkout-top-alert');
    if (topAlert) topAlert.remove();
  }
  function setCheckoutFieldError(name, message) {
    if (!chkForm) return;
    const field = chkForm.querySelector(`[name="${CSS.escape(name)}"]`);
    if (!field) return;
    field.classList.add('is-invalid');
    const err = document.createElement('div');
    err.className = 'invalid-feedback error-inline';
    err.style.display = 'block';
    err.textContent = Array.isArray(message) ? message[0] : (message || 'Invalid');
    if (field.nextElementSibling) {
      field.parentNode.insertBefore(err, field.nextElementSibling);
    } else {
      field.parentNode.appendChild(err);
    }
  }
  function checkoutTopAlert(msg, type='danger'){
    const container = document.createElement('div');
    container.id = 'checkout-top-alert';
    container.className = `alert alert-${type} mb-3`;
    container.textContent = msg;
    chkForm.parentNode.insertBefore(container, chkForm);
  }

  if (chkForm) {
    chkForm.addEventListener('submit', function(e){
      e.preventDefault();
      clearCheckoutErrors();
      if (placeBtn) placeBtn.disabled = true;
      const fd = new FormData(chkForm);
      fetch(chkForm.action, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: fd
      }).then(async r => { let data={}; try{ data = await r.json(); } catch(_){} return { ok:r.ok, status:r.status, data }; })
      .then(resp => {
        if (placeBtn) placeBtn.disabled = false;
        const { ok, status, data } = resp;
        if (ok && data && data.success && data.redirect) {
          window.location.href = data.redirect;
          return;
        }
        if (status === 422 && data && data.errors) {
          Object.entries(data.errors).forEach(([name, messages]) => setCheckoutFieldError(name, messages));
          const firstMsg = Object.values(data.errors)[0];
          if (firstMsg) checkoutTopAlert(Array.isArray(firstMsg) ? firstMsg[0] : firstMsg);
          return;
        }
        checkoutTopAlert((data && data.message) ? data.message : 'Failed to place order. Please try again.');
      }).catch(err => {
        if (placeBtn) placeBtn.disabled = false;
        console.error(err);
        checkoutTopAlert('Network error. Please try again.');
      });
    });
  }
});
</script>
@endpush
@endsection

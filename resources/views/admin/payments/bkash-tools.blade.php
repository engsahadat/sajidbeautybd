@extends('admin.layouts.app')

@section('title', 'bKash Payment Tools')

@section('admin-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">bKash Payment Management Tools</h4>
    </div>

    <div class="row">
        <!-- Verify Payment by PaymentID -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🔍 Verify Payment Status</h5>
                    <p class="text-muted">Check payment status using bKash Payment ID</p>
                    
                    <form id="verifyPaymentForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Payment ID</label>
                            <input type="text" class="form-control" name="payment_id" 
                                   placeholder="e.g., TR00112AB3C4D5" required>
                            <small class="text-muted">Enter bKash Payment ID (starts with TR)</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-check-circle"></i> Verify Payment
                        </button>
                    </form>

                    <div id="verifyResult" class="mt-3" style="display: none;">
                        <div class="alert" id="verifyAlert"></div>
                        <div id="verifyDetails"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Transaction by TrxID -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🔎 Search Transaction</h5>
                    <p class="text-muted">Find transaction details using bKash Transaction ID</p>
                    
                    <form id="searchTransactionForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Transaction ID (trxID)</label>
                            <input type="text" class="form-control" name="trx_id" 
                                   placeholder="e.g., 9AB1C2D3E4" required>
                            <small class="text-muted">Enter bKash Transaction ID from completed payment</small>
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="mdi mdi-magnify"></i> Search Transaction
                        </button>
                    </form>

                    <div id="searchResult" class="mt-3" style="display: none;">
                        <div class="alert" id="searchAlert"></div>
                        <div id="searchDetails"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Recent bKash Payments -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📋 Recent bKash Payments</h5>
                    
                    <div class="alert alert-info mb-3">
                        <i class="mdi mdi-information"></i> 
                        <strong>Note:</strong> Transaction ID (trxID) is only available for <strong>completed</strong> payments. 
                        Initiated/Pending payments will show "Pending" until customer completes the payment.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Payment ID</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments ?? [] as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $payment->order_id) }}">
                                            {{ $payment->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        <code style="font-size: 11px;">{{ $payment->transaction_id }}</code>
                                    </td>
                                    <td>
                                        @php
                                            $response = json_decode($payment->gateway_response, true);
                                            // Try multiple possible keys for transaction ID
                                            $trxId = $response['trxID'] 
                                                  ?? $response['transaction_id'] 
                                                  ?? $response['transactionId']
                                                  ?? null;
                                            
                                            // Check if payment is completed
                                            $isCompleted = $payment->status === 'completed';
                                        @endphp
                                        
                                        @if($trxId)
                                            <code>{{ $trxId }}</code>
                                        @elseif($isCompleted)
                                            <span class="text-danger">Missing</span>
                                        @else
                                            <span class="text-muted">Pending</span>
                                        @endif
                                    </td>
                                    <td>৳{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($payment->status) {
                                                'completed' => 'success',
                                                'refunded' => 'warning',
                                                'pending' => 'info',
                                                'failed', 'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        @if($payment->status === 'completed')
                                        <button class="btn btn-sm btn-warning" 
                                                onclick="initiateRefund({{ $payment->order_id }}, {{ $payment->amount }})">
                                            <i class="mdi mdi-cash-refund"></i> Refund
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-info" 
                                                onclick="verifyPayment('{{ $payment->transaction_id }}')">
                                            <i class="mdi mdi-refresh"></i> Check
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No recent bKash payments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="refundForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Process bKash Refund</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="refundOrderId" name="order_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Refund Amount (৳)</label>
                        <input type="number" class="form-control" id="refundAmount" 
                               name="amount" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Refund Reason</label>
                        <textarea class="form-control" name="reason" rows="3" 
                                  placeholder="Enter reason for refund"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-cash-refund"></i> Process Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('admin-scripts')
<script>
const adminOrdersUrl = '{{ url("admin/orders") }}';

// Verify Payment
document.getElementById('verifyPaymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const resultDiv = document.getElementById('verifyResult');
    const alertDiv = document.getElementById('verifyAlert');
    const detailsDiv = document.getElementById('verifyDetails');
    
    try {
        const response = await fetch('{{ route("admin.payments.bkash.verify") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                payment_id: formData.get('payment_id')
            })
        });
        
        const result = await response.json();
        resultDiv.style.display = 'block';
        
        if (result.success) {
            alertDiv.className = 'alert alert-success';
            alertDiv.textContent = 'Payment verified successfully!';
            
            let orderLink = '';
            if (result.order) {
                orderLink = `<tr><th>Order:</th><td><a href="${adminOrdersUrl}/${result.order.id}">${result.order.order_number}</a></td></tr>`;
            }
            
            detailsDiv.innerHTML = `
                <table class="table table-sm">
                    <tr><th>Payment ID:</th><td>${result.data.payment_id}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-${result.data.status === 'Completed' ? 'success' : 'warning'}">${result.data.status}</span></td></tr>
                    <tr><th>Transaction ID:</th><td>${result.data.transaction_id || 'N/A'}</td></tr>
                    <tr><th>Amount:</th><td>৳${result.data.amount}</td></tr>
                    <tr><th>Created:</th><td>${result.data.payment_create_time || 'N/A'}</td></tr>
                    ${orderLink}
                </table>
            `;
        } else {
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = result.message;
            detailsDiv.innerHTML = '';
        }
    } catch (error) {
        alertDiv.className = 'alert alert-danger';
        alertDiv.textContent = 'Error: ' + error.message;
        resultDiv.style.display = 'block';
    }
});

// Search Transaction
document.getElementById('searchTransactionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const resultDiv = document.getElementById('searchResult');
    const alertDiv = document.getElementById('searchAlert');
    const detailsDiv = document.getElementById('searchDetails');
    
    try {
        const response = await fetch('{{ route("admin.payments.bkash.search") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                trx_id: formData.get('trx_id')
            })
        });
        
        const result = await response.json();
        resultDiv.style.display = 'block';
        
        if (result.success) {
            alertDiv.className = 'alert alert-success';
            alertDiv.textContent = 'Transaction found!';
            
            let orderLink = '';
            if (result.order) {
                orderLink = `<tr><th>Order:</th><td><a href="${adminOrdersUrl}/${result.order.id}">${result.order.order_number}</a></td></tr>`;
            }
            
            detailsDiv.innerHTML = `
                <table class="table table-sm">
                    <tr><th>Transaction ID:</th><td>${result.data.transaction_id}</td></tr>
                    <tr><th>Payment ID:</th><td>${result.data.payment_id || 'N/A'}</td></tr>
                    <tr><th>Status:</th><td><span class="badge bg-${result.data.status === 'Completed' ? 'success' : 'warning'}">${result.data.status}</span></td></tr>
                    <tr><th>Amount:</th><td>৳${result.data.amount}</td></tr>
                    <tr><th>Merchant Invoice:</th><td>${result.data.merchant_invoice || 'N/A'}</td></tr>
                    ${orderLink}
                </table>
            `;
        } else {
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = result.message;
            detailsDiv.innerHTML = '';
        }
    } catch (error) {
        alertDiv.className = 'alert alert-danger';
        alertDiv.textContent = 'Error: ' + error.message;
        resultDiv.style.display = 'block';
    }
});

// Initiate Refund
function initiateRefund(orderId, amount) {
    document.getElementById('refundOrderId').value = orderId;
    document.getElementById('refundAmount').value = amount;
    new bootstrap.Modal(document.getElementById('refundModal')).show();
}

// Helper function for verifyPayment button in table
function verifyPayment(paymentId) {
    document.querySelector('[name="payment_id"]').value = paymentId;
    document.getElementById('verifyPaymentForm').dispatchEvent(new Event('submit'));
}

// Process Refund
document.getElementById('refundForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const orderId = document.getElementById('refundOrderId').value;
    const formData = new FormData(this);
    
    if (!confirm('Are you sure you want to process this refund?')) return;
    
    try {
        const response = await fetch(`${adminOrdersUrl}/${orderId}/bkash/refund`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                amount: formData.get('amount'),
                reason: formData.get('reason')
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Refund processed successfully!');
            bootstrap.Modal.getInstance(document.getElementById('refundModal')).hide();
            location.reload();
        } else {
            alert('Refund failed: ' + result.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});
</script>
@endpush

@extends('admin.layouts.app')

@section('admin-title', 'Invoice #' . $order->order_number)

@section('admin-content')
<div class="container-fluid">
    <div class="row mb-3 no-print">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Invoice</h1>
                <div>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left"></i> Back to Order
                    </a>
                    <button onclick="window.print()" class="btn btn-primary btn-sm me-2">
                        <i class="bi bi-printer"></i> Print
                    </button>
                    <a href="{{ route('orders.invoice', ['order' => $order, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" id="invoice-content">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            @if(!empty($settings['site_logo']) && file_exists(public_path($settings['site_logo'])))
                                <img src="{{ asset($settings['site_logo']) }}" alt="Logo" style="max-height: 60px;">
                            @else
                                <h2 class="mb-0">{{ $settings['site_name'] ?? 'Your Company' }}</h2>
                            @endif
                            <div class="mt-3">
                                <p class="mb-1">
                                    @if(!empty($settings['site_address']))
                                        {{ $settings['site_address'] }}
                                    @endif
                                </p>
                                @if(!empty($settings['site_phone']))
                                    <p class="mb-1">Phone: {{ $settings['site_phone'] }}</p>
                                @endif
                                @if(!empty($settings['site_email']))
                                    <p class="mb-1">Email: {{ $settings['site_email'] }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h1 class="mb-3">INVOICE</h1>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @if($order->status === 'delivered') bg-success 
                                    @elseif($order->status === 'cancelled') bg-danger 
                                    @elseif($order->status === 'processing') bg-info 
                                    @else bg-warning 
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p class="mb-1">
                                <strong>Payment:</strong> 
                                <span class="badge 
                                    @if($order->payment_status === 'paid') bg-success 
                                    @elseif($order->payment_status === 'failed') bg-danger 
                                    @else bg-warning 
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Billing & Shipping Info -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="mb-3">Bill To:</h5>
                            <address>
                                <strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong><br>
                                @if($order->billing_company)
                                    {{ $order->billing_company }}<br>
                                @endif
                                {{ $order->billing_address_line_1 }}<br>
                                @if($order->billing_address_line_2)
                                    {{ $order->billing_address_line_2 }}<br>
                                @endif
                                {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                                {{ $order->billing_country }}<br>
                                @if($order->billing_phone)
                                    Phone: {{ $order->billing_phone }}<br>
                                @endif
                                @if($order->user && $order->user->email)
                                    Email: {{ $order->user->email }}
                                @endif
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Ship To:</h5>
                            <address>
                                <strong>{{ $order->shipping_first_name ?? $order->billing_first_name }} {{ $order->shipping_last_name ?? $order->billing_last_name }}</strong><br>
                                @if($order->shipping_company)
                                    {{ $order->shipping_company }}<br>
                                @endif
                                {{ $order->shipping_address_line_1 ?? $order->billing_address_line_1 }}<br>
                                @if($order->shipping_address_line_2)
                                    {{ $order->shipping_address_line_2 }}<br>
                                @endif
                                {{ $order->shipping_city ?? $order->billing_city }}, {{ $order->shipping_state ?? $order->billing_state }} {{ $order->shipping_postal_code ?? $order->billing_postal_code }}<br>
                                {{ $order->shipping_country ?? $order->billing_country }}<br>
                                @if($order->shipping_phone)
                                    Phone: {{ $order->shipping_phone }}
                                @endif
                            </address>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="45%">Product</th>
                                            <th width="10%" class="text-center">SKU</th>
                                            <th width="10%" class="text-end">Unit Price</th>
                                            <th width="10%" class="text-center">Qty</th>
                                            <th width="20%" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->product_name }}</strong>
                                            </td>
                                            <td class="text-center">{{ $item->product_sku ?? 'N/A' }}</td>
                                            <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-5">
                            <table class="table table-sm">
                                <tr>
                                    <th width="60%">Subtotal:</th>
                                    <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->tax_amount > 0)
                                <tr>
                                    <th>Tax:</th>
                                    <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($order->shipping_amount > 0)
                                <tr>
                                    <th>Shipping:</th>
                                    <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($order->shipping_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($order->discount_amount > 0)
                                <tr>
                                    <th>Discount:</th>
                                    <td class="text-end text-danger">-{{ $order->currency ?? 'BDT' }} {{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-light">
                                    <th class="h5">Total:</th>
                                    <td class="text-end h5"><strong>{{ $order->currency ?? 'BDT' }} {{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                                @if($order->payments->count() > 0)
                                <tr>
                                    <th>Paid Amount:</th>
                                    <td class="text-end text-success">{{ $order->currency ?? 'BDT' }} {{ number_format($order->paidAmount(), 2) }}</td>
                                </tr>
                                @php
                                    $balance = $order->total_amount - $order->paidAmount();
                                @endphp
                                @if($balance > 0)
                                <tr>
                                    <th>Balance Due:</th>
                                    <td class="text-end text-danger"><strong>{{ $order->currency ?? 'BDT' }} {{ number_format($balance, 2) }}</strong></td>
                                </tr>
                                @endif
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if($order->payments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">Payment History:</h5>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($payment->status === 'completed') bg-success 
                                                @elseif($payment->status === 'failed') bg-danger 
                                                @elseif($payment->status === 'refunded') bg-warning 
                                                @else bg-secondary 
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ $order->currency ?? 'BDT' }} {{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($order->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-2">Notes:</h5>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Footer -->
                    <div class="row mt-5 pt-4 border-top">
                        <div class="col-12 text-center">
                            <p class="mb-0 text-muted">Thank you for your business!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide admin layout elements */
    .navbar,
    .sidebar,
    .header,
    .page-header,
    .btn,
    .breadcrumb,
    .d-flex.justify-content-between,
    .breadcrumb-nav,
    header,
    footer,
    nav {
        display: none !important;
    }
    
    /* Reset card styling */
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    /* Reset container */
    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    /* Body styling */
    body {
        margin: 0 !important;
        padding: 10px !important;
        font-size: 12pt;
    }
    
    /* Show only invoice content */
    #invoice-content {
        display: block !important;
        width: 100% !important;
    }
    
    /* Table styling for print */
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    thead {
        display: table-header-group;
    }
    
    /* Badge styling for print */
    .badge {
        border: 1px solid #000;
        padding: 2px 6px;
        border-radius: 3px;
    }
    
    /* Page setup */
    @page {
        size: A4;
        margin: 1.5cm;
    }
    
    /* Hide scrollbars */
    html, body {
        overflow: visible !important;
    }
}

/* Print button enhancement */
@media screen {
    .print-only {
        display: none;
    }
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
}
</style>
@endsection

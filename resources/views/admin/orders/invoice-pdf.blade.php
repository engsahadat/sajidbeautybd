<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header-left {
            width: 50%;
            float: left;
        }
        .header-right {
            width: 50%;
            float: right;
            text-align: right;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-block {
            width: 48%;
            float: left;
            margin-right: 4%;
        }
        .info-block:last-child {
            margin-right: 0;
        }
        .info-block h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-block address {
            font-style: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 50%;
            float: right;
            margin-top: 20px;
        }
        .totals-table td {
            border: none;
            padding: 5px 10px;
        }
        .totals-table .total-row {
            font-size: 14px;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .payment-history {
            margin-top: 30px;
        }
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 3px solid #007bff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="header-left">
            <div class="company-name">{{ $settings['site_name'] ?? 'Sajib Beauty Bd' }}</div>
            @if(!empty($settings['site_address']))
                <div>{{ $settings['site_address'] }}</div>
            @endif
            @if(!empty($settings['site_phone']))
                <div>Phone: {{ $settings['site_phone'] }}</div>
            @endif
            @if(!empty($settings['site_email']))
                <div>Email: {{ $settings['site_email'] }}</div>
            @endif
        </div>
        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div><strong>Invoice #:</strong> {{ $order->order_number }}</div>
            <div><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</div>
            <div>
                <strong>Status:</strong> 
                <span class="badge 
                    @if($order->status === 'delivered') badge-success 
                    @elseif($order->status === 'cancelled') badge-danger 
                    @elseif($order->status === 'processing') badge-info 
                    @else badge-warning 
                    @endif">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
            <div>
                <strong>Payment:</strong> 
                <span class="badge 
                    @if($order->payment_status === 'paid') badge-success 
                    @elseif($order->payment_status === 'failed') badge-danger 
                    @else badge-warning 
                    @endif">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Billing & Shipping Info -->
    <div class="info-section clearfix">
        <div class="info-block">
            <h3>Bill To:</h3>
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
        <div class="info-block">
            <h3>Ship To:</h3>
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
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="40%">Product</th>
                <th width="10%" class="text-center">SKU</th>
                <th width="10%" class="text-right">Unit Price</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->variant_display)
                        <br><small style="color: #666;">{{ $item->variant_display }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->product_sku ?? 'N/A' }}</td>
                <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($item->unit_price, 2) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="clearfix">
        <table class="totals-table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax_amount > 0)
            <tr>
                <td><strong>Tax:</strong></td>
                <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->shipping_amount > 0)
            <tr>
                <td><strong>Shipping:</strong></td>
                <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($order->shipping_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->discount_amount > 0)
            <tr>
                <td><strong>Discount:</strong></td>
                <td class="text-right" style="color: #dc3545;">-{{ $order->currency ?? 'BDT' }} {{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>{{ $order->currency ?? 'BDT' }} {{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
            @if($order->payments->count() > 0)
            <tr>
                <td><strong>Paid Amount:</strong></td>
                <td class="text-right" style="color: #28a745;">{{ $order->currency ?? 'BDT' }} {{ number_format($order->paidAmount(), 2) }}</td>
            </tr>
            @php
                $balance = $order->total_amount - $order->paidAmount();
            @endphp
            @if($balance > 0)
            <tr>
                <td><strong>Balance Due:</strong></td>
                <td class="text-right" style="color: #dc3545;"><strong>{{ $order->currency ?? 'BDT' }} {{ number_format($balance, 2) }}</strong></td>
            </tr>
            @endif
            @endif
        </table>
    </div>

    <!-- Payment History -->
    @if($order->payments->count() > 0)
    <div class="payment-history clearfix">
        <h3 style="color: #007bff; margin-bottom: 10px;">Payment History:</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
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
                            @if($payment->status === 'completed') badge-success 
                            @elseif($payment->status === 'failed') badge-danger 
                            @elseif($payment->status === 'refunded') badge-warning 
                            @else badge-info 
                            @endif">
                            {{ strtoupper($payment->status) }}
                        </span>
                    </td>
                    <td class="text-right">{{ $order->currency ?? 'BDT' }} {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Notes -->
    @if($order->notes)
    <div class="notes">
        <strong>Notes:</strong><br>
        {{ $order->notes }}
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>

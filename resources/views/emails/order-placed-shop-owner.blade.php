<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .alert-badge {
            background: #ff5722;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .order-summary {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .order-summary h2 {
            margin-top: 0;
            color: #f57c00;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        .info-box p {
            margin: 5px 0;
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        .products-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .products-table tr:hover {
            background: #f8f9fa;
        }
        .total-section {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 15px;
        }
        .total-row.grand-total {
            font-size: 24px;
            font-weight: bold;
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 15px;
            margin-top: 10px;
        }
        .customer-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .customer-section h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            font-weight: 600;
        }
        .button.secondary {
            background: #6c757d;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 13px;
        }
        .highlight {
            background: #ffeb3b;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="alert-badge">🔔 NEW ORDER ALERT</div>
            <h1>New Order Received!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Order #{{ $order->order_number }}</p>
        </div>

        <div class="order-summary">
            <h2>📦 Order Summary</h2>
            <p>A new order has been placed on your website. Please review and process it promptly.</p>
            <p style="margin-top: 15px;">
                <strong>Order Time:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
                <strong>Order Status:</strong> <span class="highlight">{{ ucfirst($order->status) }}</span><br>
                <strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'COD') }}<br>
                <strong>Payment Status:</strong> <span class="highlight">{{ ucfirst($order->payment_status) }}</span>
            </p>
        </div>

        <div class="customer-section">
            <h3>👤 Customer Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p><strong>Name:</strong><br>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                    @if($order->billing_phone)
                    <p><strong>Phone:</strong><br>{{ $order->billing_phone }}</p>
                    @endif
                </div>
                <div>
                    <p><strong>Shipping Address:</strong><br>
                        {{ $order->shipping_address_line_1 }}<br>
                        @if($order->shipping_address_line_2)
                        {{ $order->shipping_address_line_2 }}<br>
                        @endif
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </p>
                    @if($order->shipping_phone)
                    <p><strong>Contact:</strong> {{ $order->shipping_phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <h3 style="color: #333; margin-top: 30px;">📋 Order Items ({{ $order->items->count() }} items)</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center; width: 80px;">Qty</th>
                    <th style="text-align: right; width: 120px;">Unit Price</th>
                    <th style="text-align: right; width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong style="color: #333;">{{ $item->product_name }}</strong><br>
                        <small style="color: #666;">SKU: {{ $item->product_sku }}</small>
                    </td>
                    <td style="text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">৳{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: 600; color: #11998e;">৳{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>৳{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>- ৳{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            @if($order->shipping_amount > 0)
            <div class="total-row">
                <span>Shipping:</span>
                <span>৳{{ number_format($order->shipping_amount, 2) }}</span>
            </div>
            @endif
            @if($order->tax_amount > 0)
            <div class="total-row">
                <span>Tax:</span>
                <span>৳{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>GRAND TOTAL:</span>
                <span>৳{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        @if($order->notes)
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #856404;">📝 Customer Notes:</h4>
            <p style="margin: 0; color: #856404;">{{ $order->notes }}</p>
        </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('orders.show', $order->id) }}" class="button">View Order Details</a>
            <a href="{{ route('orders.edit', $order->id) }}" class="button secondary">Manage Order</a>
        </div>

        <div class="footer">
            <p><strong>Important Reminders:</strong></p>
            <ul style="list-style: none; padding: 0; margin: 10px 0;">
                <li>✅ Verify customer contact information</li>
                <li>✅ Check product availability</li>
                <li>✅ Process payment if COD</li>
                <li>✅ Prepare shipping within 24 hours</li>
            </ul>
            <p style="margin-top: 20px; color: #999; font-size: 12px;">
                This is an automated notification from Sajid Beauty BD<br>
                Generated on {{ now()->format('F d, Y h:i A') }}
            </p>
        </div>
    </div>
</body>
</html>

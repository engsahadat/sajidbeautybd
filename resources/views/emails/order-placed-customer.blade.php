<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
        }
        .success-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-details h2 {
            color: #333;
            margin-top: 0;
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 16px;
        }
        .total-row.grand-total {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            border-top: 2px solid #667eea;
            padding-top: 10px;
            margin-top: 10px;
        }
        .shipping-info {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .shipping-info h3 {
            margin-top: 0;
            color: #0066cc;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>Order Confirmed!</h1>
            <p style="color: #666; margin: 10px 0 0 0;">Thank you for your order</p>
        </div>

        <p>Dear <strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong>,</p>
        
        <p>Thank you for shopping with <strong>Sajid Beauty BD</strong>! We're excited to confirm that we've received your order and it's being processed.</p>

        <div class="order-details">
            <h2>Order Details</h2>
            <div class="detail-row">
                <span class="detail-label">Order Number:</span>
                <span class="detail-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                <span class="detail-value">{{ $order->created_at->format('F d, Y h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ strtoupper($order->payment_method ?? 'COD') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Status:</span>
                <span class="detail-value" style="color: #ff9800; font-weight: 600;">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <h3 style="color: #333; margin-top: 30px;">Order Items</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <small style="color: #666;">SKU: {{ $item->product_sku }}</small>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">৳{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: 600;">৳{{ number_format($item->total_price, 2) }}</td>
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
            <div class="total-row" style="color: #28a745;">
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
                <span>TOTAL:</span>
                <span>৳{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="shipping-info">
            <h3>Shipping Address</h3>
            <p style="margin: 5px 0;">
                <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                {{ $order->shipping_address_line_1 }}<br>
                @if($order->shipping_address_line_2)
                {{ $order->shipping_address_line_2 }}<br>
                @endif
                {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}<br>
                @if($order->shipping_phone)
                Phone: {{ $order->shipping_phone }}
                @endif
            </p>
        </div>

        <p style="margin-top: 30px;">We'll send you another email once your order has been shipped. You can track your order status anytime.</p>

        <div style="text-align: center;">
            <a href="{{ route('home') }}" class="button">Continue Shopping</a>
        </div>

        <div class="footer">
            <p><strong>Sajid Beauty BD</strong></p>
            <p>Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205</p>
            <p>Phone: +88 01648-022175 | Email: sajidbeautybd@gmail.com</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '📬';
            font-size: 50px;
            display: block;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .alert-badge {
            display: inline-block;
            background: #ff5722;
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 10px;
            letter-spacing: 1px;
        }
        .content {
            padding: 35px;
        }
        .timestamp {
            background: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin-bottom: 25px;
            border-radius: 4px;
            font-size: 14px;
            color: #856404;
        }
        .timestamp strong {
            color: #f57c00;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        .info-title {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 100px;
            display: flex;
            align-items: center;
        }
        .info-label::before {
            content: '▸';
            color: #667eea;
            margin-right: 8px;
            font-weight: bold;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .info-value a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .info-value a:hover {
            text-decoration: underline;
        }
        .message-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .message-title {
            color: #1976d2;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
            display: flex;
            align-items: center;
        }
        .message-title::before {
            content: '💬';
            margin-right: 10px;
            font-size: 24px;
        }
        .message-content {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
            color: #333;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            border-top: 3px solid #667eea;
        }
        .footer p {
            margin: 5px 0;
            color: #666;
            font-size: 13px;
        }
        .footer strong {
            color: #667eea;
            font-size: 16px;
        }
        .action-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            color: #856404;
            font-weight: 600;
        }
        .action-note::before {
            content: '⚡';
            margin-right: 8px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>New Contact Message</h1>
            <span class="alert-badge">🔔 Requires Attention</span>
        </div>

        <div class="content">
            <div class="timestamp">
                <strong>📅 Received:</strong> {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
            </div>

            <div class="info-section">
                <h2 class="info-title">👤 Customer Information</h2>
                
                <div class="info-row">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $data['name'] }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
                    </div>
                </div>

                @if(!empty($data['phone']))
                <div class="info-row">
                    <div class="info-label">Phone</div>
                    <div class="info-value">
                        <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a>
                    </div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Subject</div>
                    <div class="info-value"><strong>{{ $data['subject'] }}</strong></div>
                </div>
            </div>

            <div class="message-section">
                <h2 class="message-title">Message Details</h2>
                <div class="message-content">{{ $data['message'] }}</div>
            </div>

            <div class="action-note">
                Please respond to this inquiry within 24 hours
            </div>
        </div>

        <div class="footer">
            <p><strong>Sajid Beauty BD</strong></p>
            <p>Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall</p>
            <p>Dhaka-1205, Bangladesh</p>
            <p>Phone: +88 01648-022175 | Email: sajidbeautybd@gmail.com</p>
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                This is an automated notification from your website contact form
            </p>
        </div>
    </div>
</body>
</html>

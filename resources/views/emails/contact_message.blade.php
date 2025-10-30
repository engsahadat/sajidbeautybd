<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Message</title>
    <style>
        body { font-family: Arial, sans-serif; line-height:1.5; color:#222; }
        .container { max-width:600px; margin:0 auto; padding:16px; border:1px solid #eee; }
        h1 { font-size:20px; margin:0 0 12px; }
        .meta { font-size:12px; color:#666; margin-bottom:12px; }
        .label { font-weight:600; }
        pre { white-space:pre-wrap; word-wrap:break-word; background:#f7f7f7; padding:12px; border-radius:4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Contact Message</h1>
        <div class="meta">Submitted at {{ now()->format('Y-m-d H:i:s') }}</div>
        <p><span class="label">Name:</span> {{ $data['name'] }}</p>
        <p><span class="label">Email:</span> {{ $data['email'] }}</p>
        @if(!empty($data['phone']))<p><span class="label">Phone:</span> {{ $data['phone'] }}</p>@endif
        <p><span class="label">Subject:</span> {{ $data['subject'] }}</p>
        <p class="label">Message:</p>
        <pre>{{ $data['message'] }}</pre>
    </div>
</body>
</html>

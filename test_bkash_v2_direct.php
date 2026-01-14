<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing bKash v2 API with Different Methods ===\n\n";

$config = config('payment.bkash');

// Test with v2 endpoint
$testUrls = [
    'v2 direct' => 'https://tokenized.pay.bka.sh/v2/tokenized/checkout/token/grant',
    'v1.2.0-beta' => 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant',
];

foreach ($testUrls as $label => $url) {
    echo "=== Testing: $label ===\n";
    echo "URL: $url\n\n";
    
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'username' => $config['username'],
                'password' => $config['password'],
            ])->post($url, [
                'app_key' => $config['app_key'],
                'app_secret' => $config['app_secret'],
            ]);

        echo "Status: " . $response->status() . "\n";
        $data = $response->json();
        
        if (isset($data['id_token'])) {
            echo "✅ SUCCESS! Token received\n";
            echo "Token: " . substr($data['id_token'], 0, 50) . "...\n\n";
            exit(0);
        } else {
            echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n\n";
    }
}

echo "❌ All tests failed\n";

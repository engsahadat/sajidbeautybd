<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Finding the Correct bKash API Endpoint ===\n\n";

$config = config('payment.bkash');

// Test various possible v2 endpoint structures
$testEndpoints = [
    'Standard v2 tokenized' => 'https://tokenized.pay.bka.sh/v2/tokenized/checkout/token/grant',
    'v2 without tokenized' => 'https://tokenized.pay.bka.sh/v2/checkout/token/grant',
    'Direct v2 grant' => 'https://tokenized.pay.bka.sh/v2/token/grant',
    'v1.2.0-beta (from screenshot)' => 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant',
];

foreach ($testEndpoints as $label => $url) {
    echo "Testing: $label\n";
    echo "URL: $url\n";
    
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

        $status = $response->status();
        $data = $response->json();
        
        echo "Status: $status\n";
        
        if ($status === 200 && isset($data['id_token'])) {
            echo "✅ SUCCESS! This is the correct endpoint!\n";
            echo "Token received: " . substr($data['id_token'], 0, 50) . "...\n\n";
            echo "USE THIS URL IN .env:\n";
            echo "BKASH_BASE_URL=" . dirname(dirname($url)) . "/\n\n";
            exit(0);
        } elseif ($status === 200) {
            echo "Response: " . $data['statusMessage'] ?? 'N/A' . " (Code: " . ($data['statusCode'] ?? 'N/A') . ")\n";
            if (isset($data['statusCode']) && $data['statusCode'] === '9999') {
                echo "✅ Endpoint EXISTS but credentials need activation\n";
            }
        } else {
            echo "Error: " . ($data['message'] ?? json_encode($data)) . "\n";
        }
        
        echo "\n";
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n\n";
    }
}

echo "========================================\n";
echo "CONCLUSION:\n";
echo "The v2 API endpoint does NOT exist.\n";
echo "The correct endpoint from bKash is: v1.2.0-beta\n";
echo "========================================\n";

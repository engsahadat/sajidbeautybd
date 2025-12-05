<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== bKash API Connection Test ===\n\n";

$config = config('payment.bkash');

echo "Configuration:\n";
echo "Base URL: " . $config['base_url'] . "\n";
echo "App Key: " . substr($config['app_key'], 0, 10) . "...\n";
echo "Username: " . $config['username'] . "\n";
echo "Sandbox: " . ($config['sandbox'] ? 'Yes' : 'No') . "\n";
echo "Callback URL: " . $config['callback_url'] . "\n\n";

echo "Testing Token Grant...\n";

$tokenUrl = $config['base_url'] . 'tokenized/checkout/token/grant';
echo "Token URL: " . $tokenUrl . "\n\n";

try {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'username' => $config['username'],
        'password' => $config['password'],
    ])->post($tokenUrl, [
        'app_key' => $config['app_key'],
        'app_secret' => $config['app_secret'],
    ]);

    echo "Response Status: " . $response->status() . "\n";
    echo "Response Body:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";

    $data = $response->json();
    
    if (isset($data['id_token'])) {
        echo "✅ SUCCESS! Token received.\n";
        echo "Token: " . substr($data['id_token'], 0, 50) . "...\n";
        
        // Test create payment
        echo "\n=== Testing Create Payment ===\n\n";
        
        $createUrl = $config['base_url'] . 'tokenized/checkout/create';
        echo "Create URL: " . $createUrl . "\n\n";
        
        $payload = [
            'mode' => '0011',
            'payerReference' => ' ',
            'callbackURL' => $config['callback_url'] . '?order_id=TEST123',
            'amount' => '100.00',
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => 'TEST' . time(),
        ];
        
        echo "Request Payload:\n";
        echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";
        
        $createResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'authorization' => $data['id_token'],
            'x-app-key' => $config['app_key'],
        ])->post($createUrl, $payload);
        
        echo "Create Response Status: " . $createResponse->status() . "\n";
        echo "Create Response Body:\n";
        echo json_encode($createResponse->json(), JSON_PRETTY_PRINT) . "\n\n";
        
        $createData = $createResponse->json();
        if (isset($createData['paymentID']) && isset($createData['bkashURL'])) {
            echo "✅ Payment created successfully!\n";
            echo "Payment ID: " . $createData['paymentID'] . "\n";
            echo "bKash URL: " . $createData['bkashURL'] . "\n";
        } else {
            echo "❌ Payment creation failed!\n";
            if (isset($createData['errorMessage'])) {
                echo "Error: " . $createData['errorMessage'] . "\n";
            }
            if (isset($createData['errorCode'])) {
                echo "Error Code: " . $createData['errorCode'] . "\n";
            }
        }
        
    } else {
        echo "❌ FAILED! No token received.\n";
        if (isset($data['errorMessage'])) {
            echo "Error: " . $data['errorMessage'] . "\n";
        }
        if (isset($data['errorCode'])) {
            echo "Error Code: " . $data['errorCode'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";

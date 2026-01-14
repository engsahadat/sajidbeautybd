<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== bKash v2 API Authentication Test ===\n\n";

$config = config('payment.bkash');

echo "Configuration:\n";
echo "Base URL: " . $config['base_url'] . "\n";
echo "Username: " . $config['username'] . "\n";
echo "Password: " . substr($config['password'], 0, 3) . "...\n";
echo "App Key: " . substr($config['app_key'], 0, 10) . "...\n";
echo "App Secret: " . substr($config['app_secret'], 0, 10) . "...\n";
echo "Sandbox: " . ($config['sandbox'] ? 'true' : 'false') . "\n\n";

$tokenUrl = $config['base_url'] . 'tokenized/checkout/token/grant';
echo "Token URL: " . $tokenUrl . "\n\n";

// Test 1: Original format (v1.2.0-beta style)
echo "=== Test 1: Headers with username/password ===\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(30)
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username' => $config['username'],
            'password' => $config['password'],
        ])->post($tokenUrl, [
            'app_key' => $config['app_key'],
            'app_secret' => $config['app_secret'],
        ]);

    echo "Status: " . $response->status() . "\n";
    echo "Response:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";
    
    if ($response->successful() && isset($response->json()['id_token'])) {
        echo "✅ Test 1 PASSED - Token received!\n\n";
        exit(0);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Try with Basic Auth
echo "=== Test 2: Basic Authentication ===\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(30)
        ->withBasicAuth($config['username'], $config['password'])
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($tokenUrl, [
            'app_key' => $config['app_key'],
            'app_secret' => $config['app_secret'],
        ]);

    echo "Status: " . $response->status() . "\n";
    echo "Response:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";
    
    if ($response->successful() && isset($response->json()['id_token'])) {
        echo "✅ Test 2 PASSED - Token received with Basic Auth!\n\n";
        exit(0);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Try with all credentials in body
echo "=== Test 3: All credentials in request body ===\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(30)
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($tokenUrl, [
            'app_key' => $config['app_key'],
            'app_secret' => $config['app_secret'],
            'username' => $config['username'],
            'password' => $config['password'],
        ]);

    echo "Status: " . $response->status() . "\n";
    echo "Response:\n";
    echo json_encode($response->json(), JSON_PRETTY_PRINT) . "\n\n";
    
    if ($response->successful() && isset($response->json()['id_token'])) {
        echo "✅ Test 3 PASSED - Token received with body auth!\n\n";
        exit(0);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "❌ All tests failed. Please verify:\n";
echo "1. Production credentials are correct\n";
echo "2. Production API URL is correct\n";
echo "3. Your IP is whitelisted with bKash\n";
echo "4. Production mode is activated by bKash\n\n";

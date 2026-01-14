<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing bKash v2 API with ALL Authentication Methods ===\n\n";

$config = config('payment.bkash');
$tokenUrl = 'https://tokenized.pay.bka.sh/v2/tokenized/checkout/token/grant';

echo "Testing URL: $tokenUrl\n";
echo "Username: {$config['username']}\n";
echo "App Key: " . substr($config['app_key'], 0, 10) . "...\n\n";

// Method 1: Headers with username/password (current implementation)
echo "=== Method 1: Username/Password in Headers (Current) ===\n";
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
    $data = $response->json();
    
    if (isset($data['id_token'])) {
        echo "✅ SUCCESS! Method 1 works!\n\n";
        exit(0);
    }
    echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Method 2: All credentials in request body
echo "=== Method 2: All Credentials in Body ===\n";
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
    $data = $response->json();
    
    if (isset($data['id_token'])) {
        echo "✅ SUCCESS! Method 2 works!\n\n";
        echo "CODE NEEDS UPDATE: Move username/password to request body\n\n";
        exit(0);
    }
    echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Method 3: API Key in header
echo "=== Method 3: API Keys in Authorization Header ===\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(30)
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-App-Key' => $config['app_key'],
            'username' => $config['username'],
            'password' => $config['password'],
        ])->post($tokenUrl, [
            'app_key' => $config['app_key'],
            'app_secret' => $config['app_secret'],
        ]);

    echo "Status: " . $response->status() . "\n";
    $data = $response->json();
    
    if (isset($data['id_token'])) {
        echo "✅ SUCCESS! Method 3 works!\n\n";
        echo "CODE NEEDS UPDATE: Add X-App-Key header\n\n";
        exit(0);
    }
    echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Method 4: Try with grant_type
echo "=== Method 4: With grant_type Parameter ===\n";
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
            'grant_type' => 'client_credentials',
        ]);

    echo "Status: " . $response->status() . "\n";
    $data = $response->json();
    
    if (isset($data['id_token'])) {
        echo "✅ SUCCESS! Method 4 works!\n\n";
        echo "CODE NEEDS UPDATE: Add grant_type parameter\n\n";
        exit(0);
    }
    echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Method 5: Different endpoint path - maybe v2 uses different path structure
echo "=== Method 5: Alternative v2 Endpoint Paths ===\n";
$altPaths = [
    'https://tokenized.pay.bka.sh/v2/checkout/token/grant',
    'https://tokenized.pay.bka.sh/v2/token/grant',
    'https://checkout.pay.bka.sh/v2/tokenized/checkout/token/grant',
    'https://api.bka.sh/v2/tokenized/checkout/token/grant',
];

foreach ($altPaths as $altUrl) {
    echo "Trying: $altUrl\n";
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'username' => $config['username'],
                'password' => $config['password'],
            ])->post($altUrl, [
                'app_key' => $config['app_key'],
                'app_secret' => $config['app_secret'],
            ]);

        if ($response->status() !== 403) {
            echo "Status: " . $response->status() . " - Different from 403!\n";
            $data = $response->json();
            
            if (isset($data['id_token'])) {
                echo "✅ SUCCESS! This is the correct endpoint!\n";
                echo "Update BKASH_BASE_URL to: " . dirname(dirname($altUrl)) . "/\n\n";
                exit(0);
            }
            echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
    } catch (\Exception $e) {
        // Silent fail
    }
}
echo "\n";

echo "========================================\n";
echo "❌ CONCLUSION: v2 endpoint is not accessible\n";
echo "========================================\n";
echo "\nPossible reasons:\n";
echo "1. v2 API requires IP whitelisting (contact bKash)\n";
echo "2. v2 is not yet available for your merchant\n";
echo "3. bKash developer meant v1.2.0-beta (their 'version 2' API)\n\n";
echo "RECOMMENDATION: Contact bKash developer and ask:\n";
echo "- Is /v2/ endpoint active for your merchant?\n";
echo "- Do they need to whitelist your IP?\n";
echo "- Or did they mean v1.2.0-beta?\n";

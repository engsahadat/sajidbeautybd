<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing bKash Service with v2 URL and Automatic Fallback ===\n\n";

$config = config('payment.bkash');
echo "Configured Base URL: {$config['base_url']}\n";
echo "Username: {$config['username']}\n\n";

// Test using the actual BkashService class
$bkashService = new \App\Services\PaymentGateway\BkashService();

// Use reflection to access protected getToken method
$reflection = new ReflectionClass($bkashService);
$method = $reflection->getMethod('getToken');
$method->setAccessible(true);

echo "Attempting to get token...\n";
echo "(Will try v2, then automatically fallback to v1.2.0-beta if v2 fails)\n\n";

try {
    $token = $method->invoke($bkashService);
    
    if ($token) {
        echo "✅ SUCCESS! Token received!\n";
        echo "Token: " . substr($token, 0, 50) . "...\n\n";
        echo "Check logs for which URL was actually used.\n";
        
        // Show last few log lines
        echo "\n=== Recent Log Entries ===\n";
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -5);
            echo implode('', $recentLines);
        }
    } else {
        echo "❌ Failed to get token\n";
        echo "Check the Laravel log for details:\n";
        echo storage_path('logs/laravel.log') . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

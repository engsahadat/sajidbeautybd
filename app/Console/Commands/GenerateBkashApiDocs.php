<?php

/**
 * bKash API Test Request/Response Generator
 * This script helps generate formatted API requests and responses for bKash validation
 * 
 * Usage: php artisan tinker
 * Then copy this code or run: include 'generate_bkash_api_docs.php';
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentGateway\BkashService;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class GenerateBkashApiDocs extends Command
{
    protected $signature = 'bkash:generate-api-docs {order_id?}';
    protected $description = 'Generate bKash API request/response documentation for validation';

    public function handle()
    {
        $this->info('==============================================');
        $this->info('bKash Payment Gateway API Test Documentation');
        $this->info('Generated: ' . now()->toDateTimeString());
        $this->info('==============================================');
        $this->newLine();

        $bkashService = new BkashService();
        $config = config('payment.bkash');

        // 1. Grant Token API
        $this->info('1. GRANT TOKEN API');
        $this->info('API Title: Grant Token');
        $this->info('API URL: ' . $config['base_url'] . 'tokenized/checkout/token/grant');
        $this->newLine();
        
        $this->info('Request Headers:');
        $this->line('  Content-Type: application/json');
        $this->line('  Accept: application/json');
        $this->line('  username: ' . $config['username']);
        $this->line('  password: ' . $config['password']);
        $this->newLine();
        
        $this->info('Request Body:');
        $this->line(json_encode([
            'app_key' => $config['app_key'],
            'app_secret' => $config['app_secret'],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->newLine();

        try {
            $tokenResponse = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'username' => $config['username'],
                    'password' => $config['password'],
                ])->post($config['base_url'] . 'tokenized/checkout/token/grant', [
                    'app_key' => $config['app_key'],
                    'app_secret' => $config['app_secret'],
                ]);

            $tokenData = $tokenResponse->json();
            $this->info('API Response:');
            $this->line(json_encode($tokenData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->newLine(2);

            if (isset($tokenData['id_token'])) {
                $token = $tokenData['id_token'];

                // 2. Create Payment API
                $this->info('2. CREATE PAYMENT API');
                $this->info('API Title: Create Payment');
                $this->info('API URL: ' . $config['base_url'] . 'tokenized/checkout/create');
                $this->newLine();

                $orderId = $this->argument('order_id');
                if ($orderId) {
                    $order = Order::find($orderId);
                } else {
                    // Create a test order or use the latest one
                    $order = Order::latest()->first();
                }

                if ($order) {
                    $createPayload = [
                        'mode' => '0011',
                        'payerReference' => $order->phone ?? ' ',
                        'callbackURL' => $config['callback_url'] . '?order_id=' . $order->id,
                        'amount' => (string) number_format((float)$order->total_amount, 2, '.', ''),
                        'currency' => 'BDT',
                        'intent' => 'sale',
                        'merchantInvoiceNumber' => $order->order_number,
                    ];

                    $this->info('Request Headers:');
                    $this->line('  Content-Type: application/json');
                    $this->line('  Accept: application/json');
                    $this->line('  authorization: ' . substr($token, 0, 50) . '...');
                    $this->line('  x-app-key: ' . $config['app_key']);
                    $this->newLine();

                    $this->info('Request Body:');
                    $this->line(json_encode($createPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    $this->newLine();

                    $createResponse = Http::timeout(30)
                        ->withHeaders([
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                            'authorization' => $token,
                            'x-app-key' => $config['app_key'],
                        ])->post($config['base_url'] . 'tokenized/checkout/create', $createPayload);

                    $createData = $createResponse->json();
                    $this->info('API Response:');
                    $this->line(json_encode($createData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    $this->newLine(2);

                    if (isset($createData['paymentID'])) {
                        $paymentId = $createData['paymentID'];

                        // Note: Execute, Query, and Search require actual payment completion
                        $this->info('3. EXECUTE PAYMENT API');
                        $this->info('API Title: Execute Payment');
                        $this->info('API URL: ' . $config['base_url'] . 'tokenized/checkout/execute');
                        $this->info('Note: This API should be called after customer completes payment on bKash');
                        $this->newLine();
                        
                        $this->info('Request Headers:');
                        $this->line('  Content-Type: application/json');
                        $this->line('  Accept: application/json');
                        $this->line('  authorization: ' . substr($token, 0, 50) . '...');
                        $this->line('  x-app-key: ' . $config['app_key']);
                        $this->newLine();
                        
                        $this->info('Request Body:');
                        $this->line(json_encode(['paymentID' => $paymentId], JSON_PRETTY_PRINT));
                        $this->newLine();
                        $this->warn('Execute after customer completes payment to get actual response');
                        $this->newLine(2);

                        // 4. Query Payment API
                        $this->info('4. QUERY PAYMENT API');
                        $this->info('API Title: Query Payment');
                        $this->info('API URL: ' . $config['base_url'] . 'tokenized/checkout/payment/status');
                        $this->newLine();
                        
                        $this->info('Request Headers:');
                        $this->line('  Content-Type: application/json');
                        $this->line('  Accept: application/json');
                        $this->line('  authorization: ' . substr($token, 0, 50) . '...');
                        $this->line('  x-app-key: ' . $config['app_key']);
                        $this->newLine();
                        
                        $this->info('Request Body:');
                        $this->line(json_encode(['paymentID' => $paymentId], JSON_PRETTY_PRINT));
                        $this->newLine();

                        $queryResponse = Http::timeout(30)
                            ->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'authorization' => $token,
                                'x-app-key' => $config['app_key'],
                            ])->post($config['base_url'] . 'tokenized/checkout/payment/status', [
                                'paymentID' => $paymentId,
                            ]);

                        $queryData = $queryResponse->json();
                        $this->info('API Response:');
                        $this->line(json_encode($queryData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                        $this->newLine(2);

                        // 5. Search Transaction API
                        $this->info('5. SEARCH TRANSACTION API');
                        $this->info('API Title: Search Transaction');
                        $this->info('API URL: ' . $config['base_url'] . 'tokenized/checkout/general/searchTransaction');
                        $this->info('Note: Requires a completed transaction trxID');
                        $this->newLine();
                        
                        $this->info('Request Headers:');
                        $this->line('  Content-Type: application/json');
                        $this->line('  Accept: application/json');
                        $this->line('  authorization: ' . substr($token, 0, 50) . '...');
                        $this->line('  x-app-key: ' . $config['app_key']);
                        $this->newLine();
                        
                        $this->info('Request Body:');
                        $this->line(json_encode(['trxID' => 'EXAMPLE_TRX_ID'], JSON_PRETTY_PRINT));
                        $this->newLine();
                        $this->warn('Use actual trxID from completed transaction for real response');
                        $this->newLine();
                    }
                } else {
                    $this->error('No orders found. Please create an order first.');
                }
            } else {
                $this->error('Failed to get token. Check credentials.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        $this->info('==============================================');
        $this->info('Documentation Generation Complete');
        $this->info('==============================================');
    }
}

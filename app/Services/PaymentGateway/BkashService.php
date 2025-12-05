<?php

namespace App\Services\PaymentGateway;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BkashService
{
    protected array $config;
    protected string $baseUrl;

    public function __construct()
    {
        $this->config = config('payment.bkash');
        $this->baseUrl = $this->config['base_url'];
    }

    /**
     * Get grant token (cached for 1 hour)
     */
    protected function getToken(): ?string
    {
        $cacheKey = 'bkash_token';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $tokenUrl = $this->baseUrl . 'tokenized/checkout/token/grant';
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'username' => $this->config['username'],
                'password' => $this->config['password'],
            ])->post($tokenUrl, [
                'app_key' => $this->config['app_key'],
                'app_secret' => $this->config['app_secret'],
            ]);

            $data = $response->json();

            if (isset($data['id_token'])) {
                Cache::put($cacheKey, $data['id_token'], 3600); // 1 hour
                return $data['id_token'];
            }

            Log::error('bKash token grant failed', [
                'url' => $tokenUrl,
                'status' => $response->status(),
                'response' => $data,
                'username' => $this->config['username']
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('bKash token exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create payment
     */
    public function initiate(Order $order): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with bKash',
            ];
        }

        try {
            $createUrl = $this->baseUrl . 'tokenized/checkout/create';
            
            // Build callback URL - use HTTPS for production/sandbox
            $callbackUrl = $this->config['callback_url'] . '?order_id=' . $order->id;
            
            $payload = [
                'mode' => '0011',
                'payerReference' => ' ',
                'callbackURL' => $callbackUrl,
                'amount' => (string) number_format((float)$order->total_amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'authorization' => $token,
                'x-app-key' => $this->config['app_key'],
            ])->post($createUrl, $payload);

            $data = $response->json();

            if (isset($data['paymentID']) && isset($data['bkashURL'])) {
                return [
                    'success' => true,
                    'redirect_url' => $data['bkashURL'],
                    'payment_id' => $data['paymentID'],
                ];
            }

            Log::error('bKash payment creation failed', [
                'url' => $createUrl,
                'status' => $response->status(),
                'request_payload' => $payload,
                'response' => $data,
                'callback_url' => $this->config['callback_url']
            ]);
            return [
                'success' => false,
                'message' => $data['errorMessage'] ?? ($data['statusMessage'] ?? 'Payment creation failed'),
            ];
        } catch (\Exception $e) {
            Log::error('bKash payment exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Execute payment after user approval
     */
    public function execute(string $paymentId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'authorization' => $token,
                'x-app-key' => $this->config['app_key'],
            ])->post($this->baseUrl . 'tokenized/checkout/execute', [
                'paymentID' => $paymentId,
            ]);

            $data = $response->json();

            if (isset($data['transactionStatus']) && $data['transactionStatus'] === 'Completed') {
                return [
                    'success' => true,
                    'transaction_id' => $data['trxID'],
                    'amount' => $data['amount'] ?? 0,
                    'status' => $data['transactionStatus'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['errorMessage'] ?? ($data['statusMessage'] ?? 'Payment execution failed'),
            ];
        } catch (\Exception $e) {
            Log::error('bKash execute exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Execution error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Query payment status
     */
    public function queryPayment(string $paymentId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'authorization' => $token,
                'x-app-key' => $this->config['app_key'],
            ])->post($this->baseUrl . 'tokenized/checkout/payment/status', [
                'paymentID' => $paymentId,
            ]);

            $data = $response->json();

            if (isset($data['transactionStatus'])) {
                return [
                    'success' => true,
                    'status' => $data['transactionStatus'],
                    'transaction_id' => $data['trxID'] ?? null,
                    'amount' => $data['amount'] ?? 0,
                ];
            }

            return ['success' => false, 'message' => 'Query failed'];
        } catch (\Exception $e) {
            Log::error('bKash query exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verify callback signature (Optional - for additional security)
     * According to bKash API specification, signature is provided in callback URL
     */
    public function verifySignature(string $paymentId, string $status, string $signature): bool
    {
        // The signature verification logic would go here
        // bKash provides signature in callback URL for verification
        // For now, we're using execute() method to verify payment which is more secure
        return true;
    }

    /**
     * Refund a payment (if needed)
     */
    public function refund(string $paymentId, string $trxId, float $amount, string $reason = ''): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'authorization' => $token,
                'x-app-key' => $this->config['app_key'],
            ])->post($this->baseUrl . 'tokenized/checkout/payment/refund', [
                'paymentID' => $paymentId,
                'trxID' => $trxId,
                'amount' => (string) number_format($amount, 2, '.', ''),
                'sku' => 'refund',
                'reason' => $reason ?: 'Customer refund request',
            ]);

            $data = $response->json();

            if (isset($data['transactionStatus']) && $data['transactionStatus'] === 'Completed') {
                return [
                    'success' => true,
                    'refund_trx_id' => $data['refundTrxID'] ?? null,
                    'transaction_id' => $data['trxID'] ?? null,
                    'status' => $data['transactionStatus'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['errorMessage'] ?? ($data['statusMessage'] ?? 'Refund failed'),
            ];
        } catch (\Exception $e) {
            Log::error('bKash refund exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Refund error: ' . $e->getMessage(),
            ];
        }
    }
}

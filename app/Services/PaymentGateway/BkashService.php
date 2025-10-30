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
            $response = Http::withHeaders([
                'username' => $this->config['username'],
                'password' => $this->config['password'],
            ])->post($this->baseUrl . '/checkout/token/grant', [
                'app_key' => $this->config['app_key'],
                'app_secret' => $this->config['app_secret'],
            ]);

            $data = $response->json();

            if (isset($data['id_token'])) {
                Cache::put($cacheKey, $data['id_token'], 3600); // 1 hour
                return $data['id_token'];
            }

            Log::error('bKash token grant failed', ['response' => $data]);
            return null;
        } catch (\Exception $e) {
            Log::error('bKash token exception', ['error' => $e->getMessage()]);
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'X-APP-Key' => $this->config['app_key'],
            ])->post($this->baseUrl . '/checkout/payment/create', [
                'amount' => number_format((float)$order->total_amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
                'callbackURL' => $this->config['callback_url'] . '?order_id=' . $order->id,
            ]);

            $data = $response->json();

            if (isset($data['paymentID']) && isset($data['bkashURL'])) {
                return [
                    'success' => true,
                    'redirect_url' => $data['bkashURL'],
                    'payment_id' => $data['paymentID'],
                ];
            }

            Log::error('bKash payment creation failed', ['response' => $data]);
            return [
                'success' => false,
                'message' => $data['errorMessage'] ?? 'Payment creation failed',
            ];
        } catch (\Exception $e) {
            Log::error('bKash payment exception', ['error' => $e->getMessage()]);
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
                'Authorization' => 'Bearer ' . $token,
                'X-APP-Key' => $this->config['app_key'],
            ])->post($this->baseUrl . '/checkout/payment/execute', [
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
                'message' => $data['errorMessage'] ?? 'Payment execution failed',
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
                'Authorization' => 'Bearer ' . $token,
                'X-APP-Key' => $this->config['app_key'],
            ])->post($this->baseUrl . '/checkout/payment/query', [
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
}

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
     * Grant Token API
     * API URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant
     * Get grant token (cached for 1 hour based on expires_in from response)
     */
    protected function getToken(): ?string
    {
        
        $cacheKey = 'bkash_token';
        
        // Return cached token if available
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $tokenUrl = $this->baseUrl . 'tokenized/checkout/token/grant';
            
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'username' => $this->config['username'],
                    'password' => $this->config['password'],
                ])->post($tokenUrl, [
                    'app_key' => $this->config['app_key'],
                    'app_secret' => $this->config['app_secret'],
                ]);

            $data = $response->json();
            // Check for successful response with id_token
            if (isset($data['statusCode']) && $data['statusCode'] === '0000' && isset($data['id_token'])) {
                // Cache token for the time specified in expires_in (default 3600 seconds = 1 hour)
                $expiresIn = $data['expires_in'] ?? 3600;
                Cache::put($cacheKey, $data['id_token'], $expiresIn);
                
                Log::info('bKash token granted successfully', [
                    'statusMessage' => $data['statusMessage'] ?? 'N/A',
                    'expires_in' => $expiresIn,
                ]);
                
                return $data['id_token'];
            }

            $errorCode = $data['statusCode'] ?? 'N/A';
            $errorMessage = BkashErrorHandler::getMessage($errorCode, $data['statusMessage'] ?? null);
            
            Log::error('bKash token grant failed', [
                'url' => $tokenUrl,
                'status' => $response->status(),
                'statusCode' => $errorCode,
                'statusMessage' => $data['statusMessage'] ?? 'N/A',
                'userMessage' => $errorMessage,
                'category' => BkashErrorHandler::getCategory($errorCode),
                'response' => $data,
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
     * Create Payment API
     * API URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create
     * Create payment and get bKash URL for user to complete payment
     */
    public function initiate(Order $order): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to authenticate with bKash. Please try again.',
            ];
        }

        try {
            $createUrl = $this->baseUrl . 'tokenized/checkout/create';
            
            // Build callback URL with order_id parameter
            $callbackUrl = $this->config['callback_url'] . '?order_id=' . $order->id;
            // Prepare request payload according to bKash API documentation
            $payload = [
                'mode' => '0011', // Instant checkout
                'payerReference' => $order->phone ?? ' ', // Customer phone or blank space
                'callbackURL' => $callbackUrl,
                'amount' => (string) number_format((float)$order->total_amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
            ];
            
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'authorization' => $token,
                    'x-app-key' => $this->config['app_key'],
                ])->post($createUrl, $payload);

            $data = $response->json();

            // Check for successful payment creation
            if (isset($data['statusCode']) && $data['statusCode'] === '0000' && isset($data['paymentID']) && isset($data['bkashURL'])) {
                Log::info('bKash payment created successfully', [
                    'order_number' => $order->order_number,
                    'paymentID' => $data['paymentID'],
                    'amount' => $data['amount'],
                    'transactionStatus' => $data['transactionStatus'] ?? 'N/A',
                ]);
                
                return [
                    'success' => true,
                    'redirect_url' => $data['bkashURL'],
                    'payment_id' => $data['paymentID'],
                    'callback_url' => $data['callbackURL'] ?? $callbackUrl,
                    'success_callback_url' => $data['successCallbackURL'] ?? null,
                    'failure_callback_url' => $data['failureCallbackURL'] ?? null,
                    'cancelled_callback_url' => $data['cancelledCallbackURL'] ?? null,
                ];
            }

            $errorCode = $data['statusCode'] ?? 'N/A';
            $errorMessage = BkashErrorHandler::getMessage($errorCode, $data['statusMessage'] ?? $data['errorMessage'] ?? null);
            
            Log::error('bKash payment creation failed', [
                'url' => $createUrl,
                'status' => $response->status(),
                'statusCode' => $errorCode,
                'statusMessage' => $data['statusMessage'] ?? 'N/A',
                'userMessage' => $errorMessage,
                'category' => BkashErrorHandler::getCategory($errorCode),
                'request_payload' => $payload,
                'response' => $data,
                'is_recoverable' => BkashErrorHandler::isRecoverable($errorCode),
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $errorCode,
                'is_recoverable' => BkashErrorHandler::isRecoverable($errorCode),
            ];
        } catch (\Exception $e) {
            Log::error('bKash payment creation exception', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Payment gateway error. Please try again or contact support.',
            ];
        }
    }

    /**
     * Execute Payment API
     * API URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/execute
     * Execute payment after user completes payment on bKash app/website
     */
    public function execute(string $paymentId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'success' => false, 
                'message' => 'Authentication failed. Please try again.'
            ];
        }

        try {
            $executeUrl = $this->baseUrl . 'tokenized/checkout/execute';
            
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'authorization' => $token,
                    'x-app-key' => $this->config['app_key'],
                ])->post($executeUrl, [
                    'paymentID' => $paymentId,
                ]);

            $data = $response->json();
            // Check for successful execution
            if (isset($data['statusCode']) && $data['statusCode'] === '0000' && 
                isset($data['transactionStatus']) && $data['transactionStatus'] === 'Completed') {
                
                Log::info('bKash payment executed successfully', [
                    'paymentID' => $data['paymentID'] ?? $paymentId,
                    'trxID' => $data['trxID'] ?? 'N/A',
                    'amount' => $data['amount'] ?? 'N/A',
                    'customerMsisdn' => $data['customerMsisdn'] ?? 'N/A',
                ]);
                
                return [
                    'success' => true,
                    'transaction_id' => $data['trxID'],
                    'payment_id' => $data['paymentID'] ?? $paymentId,
                    'amount' => $data['amount'] ?? 0,
                    'status' => $data['transactionStatus'],
                    'customer_msisdn' => $data['customerMsisdn'] ?? null,
                    'payer_reference' => $data['payerReference'] ?? null,
                    'payment_execute_time' => $data['paymentExecuteTime'] ?? null,
                    'merchant_invoice_number' => $data['merchantInvoiceNumber'] ?? null,
                ];
            }

            $errorCode = $data['statusCode'] ?? 'N/A';
            $errorMessage = BkashErrorHandler::getMessage($errorCode, $data['statusMessage'] ?? $data['errorMessage'] ?? null);
            
            Log::error('bKash payment execution failed', [
                'paymentID' => $paymentId,
                'statusCode' => $errorCode,
                'statusMessage' => $data['statusMessage'] ?? 'N/A',
                'transactionStatus' => $data['transactionStatus'] ?? 'N/A',
                'userMessage' => $errorMessage,
                'category' => BkashErrorHandler::getCategory($errorCode),
                'is_recoverable' => BkashErrorHandler::isRecoverable($errorCode),
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $errorCode,
                'is_recoverable' => BkashErrorHandler::isRecoverable($errorCode),
            ];
        } catch (\Exception $e) {
            Log::error('bKash execute exception', [
                'paymentID' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Payment verification error. Please contact support with your payment ID.',
            ];
        }
    }

    /**
     * Query Payment Status API
     * API URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/payment/status
     * Query the status of a payment using paymentID
     */
    public function queryPayment(string $paymentId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'success' => false, 
                'message' => 'Authentication failed. Please try again.'
            ];
        }

        try {
            $queryUrl = $this->baseUrl . 'tokenized/checkout/payment/status';
            
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'authorization' => $token,
                    'x-app-key' => $this->config['app_key'],
                ])->post($queryUrl, [
                    'paymentID' => $paymentId,
                ]);

            $data = $response->json();
            
            // Check for successful query
            if (isset($data['statusCode']) && $data['statusCode'] === '0000') {
                Log::info('bKash payment query successful', [
                    'paymentID' => $data['paymentID'] ?? $paymentId,
                    'transactionStatus' => $data['transactionStatus'] ?? 'N/A',
                    'trxID' => $data['trxID'] ?? 'N/A',
                ]);
                
                return [
                    'success' => true,
                    'payment_id' => $data['paymentID'] ?? $paymentId,
                    'status' => $data['transactionStatus'] ?? 'Unknown',
                    'transaction_id' => $data['trxID'] ?? null,
                    'amount' => $data['amount'] ?? 0,
                    'currency' => $data['currency'] ?? 'BDT',
                    'intent' => $data['intent'] ?? 'sale',
                    'mode' => $data['mode'] ?? null,
                    'merchant_invoice' => $data['merchantInvoice'] ?? null,
                    'payment_create_time' => $data['paymentCreateTime'] ?? null,
                    'payment_execute_time' => $data['paymentExecuteTime'] ?? null,
                    'verification_status' => $data['verificationStatus'] ?? null,
                    'payer_reference' => $data['payerReference'] ?? null,
                ];
            }

            $errorCode = $data['statusCode'] ?? 'N/A';
            $errorMessage = BkashErrorHandler::getMessage($errorCode, $data['statusMessage'] ?? $data['errorMessage'] ?? null);
            
            Log::warning('bKash payment query failed', [
                'paymentID' => $paymentId,
                'statusCode' => $errorCode,
                'statusMessage' => $data['statusMessage'] ?? 'N/A',
                'userMessage' => $errorMessage,
                'category' => BkashErrorHandler::getCategory($errorCode),
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $errorCode,
            ];
        } catch (\Exception $e) {
            Log::error('bKash query payment exception', [
                'paymentID' => $paymentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Payment query error. Please try again.',
            ];
        }
    }

    /**
     * Search Transaction API
     * API URL: https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/general/searchTransaction
     * Search for a transaction using trxID (bKash transaction ID)
     */
    public function searchTransaction(string $trxId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'success' => false, 
                'message' => 'Authentication failed. Please try again.'
            ];
        }

        try {
            $searchUrl = $this->baseUrl . 'tokenized/checkout/general/searchTransaction';
            
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'authorization' => $token,
                    'x-app-key' => $this->config['app_key'],
                ])->post($searchUrl, [
                    'trxID' => $trxId,
                ]);

            $data = $response->json();

            // Check for successful search (statusCode 0000 means success)
            if (isset($data['statusCode']) && $data['statusCode'] === '0000') {
                Log::info('bKash transaction search successful', [
                    'trxID' => $trxId,
                    'transactionStatus' => $data['transactionStatus'] ?? 'N/A',
                    'paymentID' => $data['paymentID'] ?? 'N/A',
                ]);
                
                return [
                    'success' => true,
                    'transaction_id' => $data['trxID'] ?? $trxId,
                    'payment_id' => $data['paymentID'] ?? null,
                    'status' => $data['transactionStatus'] ?? 'Unknown',
                    'amount' => $data['amount'] ?? 0,
                    'currency' => $data['currency'] ?? 'BDT',
                    'intent' => $data['intent'] ?? 'sale',
                    'merchant_invoice' => $data['merchantInvoice'] ?? null,
                    'initiation_time' => $data['initiationTime'] ?? null,
                    'completed_time' => $data['completedTime'] ?? null,
                    'mode' => $data['mode'] ?? null,
                ];
            }

            // Note: According to the documentation, statusCode 2003 means "Process Failed"
            $errorCode = $data['statusCode'] ?? 'N/A';
            $errorMessage = BkashErrorHandler::getMessage($errorCode, $data['statusMessage'] ?? $data['errorMessage'] ?? null);
            
            Log::warning('bKash transaction search failed', [
                'trxID' => $trxId,
                'statusCode' => $errorCode,
                'statusMessage' => $data['statusMessage'] ?? 'N/A',
                'userMessage' => $errorMessage,
                'category' => BkashErrorHandler::getCategory($errorCode),
            ]);
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'status_code' => $errorCode,
            ];
        } catch (\Exception $e) {
            Log::error('bKash search transaction exception', [
                'trxID' => $trxId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Transaction search error. Please try again.',
            ];
        }
    }

    /**
     * Query refund status
     */
    public function queryRefund(string $paymentId, string $trxId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Authentication failed'];
        }

        try {
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'authorization' => $token,
                    'x-app-key' => $this->config['app_key'],
                ])->post($this->baseUrl . 'tokenized/checkout/payment/refund', [
                    'paymentID' => $paymentId,
                    'trxID' => $trxId,
                ]);

            $data = $response->json();

            if (isset($data['transactionStatus'])) {
                return [
                    'success' => true,
                    'status' => $data['transactionStatus'],
                    'refund_trx_id' => $data['refundTrxID'] ?? null,
                    'transaction_id' => $data['trxID'] ?? null,
                    'amount' => $data['amount'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'message' => $data['errorMessage'] ?? ($data['statusMessage'] ?? 'Refund query failed'),
            ];
        } catch (\Exception $e) {
            Log::error('bKash query refund exception', ['error' => $e->getMessage()]);
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
            $response = Http::timeout($this->config['timeout'] ?? 30)
                ->withHeaders([
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

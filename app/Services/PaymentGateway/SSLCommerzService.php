<?php

namespace App\Services\PaymentGateway;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzService
{
    protected array $config;
    protected string $baseUrl;

    public function __construct()
    {
        $this->config = config('payment.sslcommerz');
        $this->baseUrl = $this->config['sandbox'] 
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    /**
     * Initiate payment session with SSLCommerz
     */
    public function initiate(Order $order): array
    {
        $postData = [
            'store_id' => $this->config['store_id'],
            'store_passwd' => $this->config['store_password'],
            'total_amount' => number_format((float)$order->total_amount, 2, '.', ''),
            'currency' => $order->currency ?? 'BDT',
            'tran_id' => $order->order_number . '-' . time(),
            'success_url' => $this->config['success_url'] . '?order_id=' . $order->id,
            'fail_url' => $this->config['fail_url'] . '?order_id=' . $order->id,
            'cancel_url' => $this->config['cancel_url'] . '?order_id=' . $order->id,
            'ipn_url' => $this->config['ipn_url'],

            // Customer information
            'cus_name' => $order->billing_first_name . ' ' . $order->billing_last_name,
            'cus_email' => $order->user?->email ?? 'guest@example.com',
            'cus_add1' => $order->billing_address_line_1,
            'cus_city' => $order->billing_city,
            'cus_postcode' => $order->billing_postal_code,
            'cus_country' => $order->billing_country,
            'cus_phone' => $order->billing_phone ?? 'N/A',

            // Shipping information
            'shipping_method' => 'YES',
            'ship_name' => $order->shipping_first_name . ' ' . $order->shipping_last_name,
            'ship_add1' => $order->shipping_address_line_1,
            'ship_city' => $order->shipping_city,
            'ship_postcode' => $order->shipping_postal_code,
            'ship_country' => $order->shipping_country,

            // Product information
            'product_name' => 'Order ' . $order->order_number,
            'product_category' => 'General',
            'product_profile' => 'general',
        ];

        try {
            $response = Http::asForm()->post($this->baseUrl . '/gwprocess/v4/api.php', $postData);
            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'SUCCESS' && isset($data['GatewayPageURL'])) {
                return [
                    'success' => true,
                    'redirect_url' => $data['GatewayPageURL'],
                    'session_key' => $data['sessionkey'] ?? null,
                ];
            }

            Log::error('SSLCommerz initiation failed', ['response' => $data]);
            return [
                'success' => false,
                'message' => $data['failedreason'] ?? 'Payment initiation failed',
            ];
        } catch (\Exception $e) {
            Log::error('SSLCommerz exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate callback from SSLCommerz
     */
    public function validateCallback(array $data): array
    {
        if (!isset($data['val_id']) || !isset($data['store_id'])) {
            return ['valid' => false, 'message' => 'Invalid callback data'];
        }

        // Verify with SSLCommerz validation API
        try {
            $validationUrl = $this->baseUrl . '/validator/api/validationserverAPI.php';
            $params = [
                'val_id' => $data['val_id'],
                'store_id' => $this->config['store_id'],
                'store_passwd' => $this->config['store_password'],
                'format' => 'json',
            ];

            $response = Http::get($validationUrl, $params);
            $validation = $response->json();

            if (isset($validation['status']) && $validation['status'] === 'VALID') {
                return [
                    'valid' => true,
                    'transaction_id' => $validation['tran_id'] ?? $data['tran_id'],
                    'amount' => $validation['amount'] ?? $data['amount'],
                    'currency' => $validation['currency_type'] ?? $data['currency'],
                    'status' => $validation['status'],
                    'card_type' => $validation['card_type'] ?? null,
                ];
            }

            return [
                'valid' => false,
                'message' => 'Transaction validation failed',
            ];
        } catch (\Exception $e) {
            Log::error('SSLCommerz validation exception', ['error' => $e->getMessage()]);
            return [
                'valid' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ];
        }
    }
}

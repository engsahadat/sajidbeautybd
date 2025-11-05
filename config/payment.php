<?php

return [
    // When true, we simulate payments without hitting real gateways
    'demo' => env('PAYMENT_DEMO', true),

    // SSLCommerz Configuration
    'sslcommerz' => [
        'store_id' => env('SSLCOMMERZ_STORE_ID', ''),
        'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', ''),
        'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
        'success_url' => env('APP_URL') . 'payment/callback/sslcommerz',
        'fail_url' => env('APP_URL') . 'payment/callback/sslcommerz',
        'cancel_url' => env('APP_URL') . 'payment/callback/sslcommerz',
        'ipn_url' => env('APP_URL') . 'payment/ipn/sslcommerz',
    ],

    // bKash Configuration
    'bkash' => [
        'app_key' => env('BKASH_APP_KEY', ''),
        'app_secret' => env('BKASH_APP_SECRET', ''),
        'username' => env('BKASH_USERNAME', ''),
        'password' => env('BKASH_PASSWORD', ''),
        'sandbox' => env('BKASH_SANDBOX', true),
        'base_url' => env('BKASH_SANDBOX', true) 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta' 
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta',
        'callback_url' => env('APP_URL') . '/payment/callback/bkash',
    ],
];

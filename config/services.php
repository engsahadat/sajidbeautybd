<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your SMS gateway credentials here.
    | Supported gateways: SSL Wireless, BulkSMS BD
    |
    */

    'sms' => [
        'default' => env('SMS_GATEWAY', 'log'), // 'ssl_wireless', 'bulksms_bd', or 'log'
        
        'ssl_wireless' => [
            'api_token' => env('SSL_WIRELESS_API_TOKEN'),
            'sid' => env('SSL_WIRELESS_SID'),
            'url' => env('SSL_WIRELESS_URL', 'https://smsplus.sslwireless.com/api/v3/send-sms'),
        ],
        
        'bulksms_bd' => [
            'api_key' => env('BULKSMS_BD_API_KEY'),
            'sender_id' => env('BULKSMS_BD_SENDER_ID'),
            'url' => env('BULKSMS_BD_URL', 'http://bulksmsbd.net/api/smsapi'),
        ],
    ],

];

<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send SMS notification for order placement
     * 
     * @param Order $order
     * @param string $phoneNumber
     * @return bool
     */
    public function sendOrderPlacedSms(Order $order, string $phoneNumber): bool
    {
        try {
            // Clean phone number (remove spaces, dashes, etc.)
            $phone = preg_replace('/[^0-9+]/', '', $phoneNumber);
            
            // Ensure Bangladesh country code
            if (!str_starts_with($phone, '88') && !str_starts_with($phone, '+88')) {
                // If phone starts with 0, replace with 88
                if (str_starts_with($phone, '0')) {
                    $phone = '88' . substr($phone, 1);
                } else {
                    $phone = '88' . $phone;
                }
            }
            
            // Remove + if present
            $phone = str_replace('+', '', $phone);
            
            // Format message
            $message = $this->formatOrderMessage($order);
            
            // Get SMS gateway from config
            $gateway = config('services.sms.default', 'log');
            
            if ($gateway === 'bulksms_bd') {
                return $this->sendViaBulkSmsBD($phone, $message);
            } else {
                // Log mode - just log the SMS
                Log::info('SMS Log Mode - Message would be sent:', [
                    'phone' => $phone,
                    'message' => $message,
                    'order_id' => $order->id,
                ]);
                return true;
            }
            
        } catch (\Exception $e) {
            Log::error('SMS sending failed:', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'phone' => $phoneNumber ?? 'N/A',
            ]);
            return false;
        }
    }
    
    /**
     * Format order confirmation message
     * 
     * @param Order $order
     * @return string
     */
    protected function formatOrderMessage(Order $order): string
    {
        $message = "Dear {$order->billing_first_name},\n\n";
        $message .= "Your order #{$order->order_number} has been confirmed!\n";
        $message .= "Total: ৳" . number_format((float) $order->total_amount, 0) . "\n";
        $message .= "Status: " . ucfirst($order->status) . "\n\n";
        $message .= "We'll notify you when your order ships.\n\n";
        $message .= "Thank you for shopping with Sajid Beauty BD!";
        
        return $message;
    }
    
    /**
     * Send SMS via BulkSMS BD using cURL
     * 
     * @param string $phone
     * @param string $message
     * @return bool
     */
    protected function sendViaBulkSmsBD(string $phone, string $message): bool
    {
        try {
            $url = config('services.sms.bulksms_bd.url', 'http://bulksmsbd.net/api/smsapi');
            $apiKey = config('services.sms.bulksms_bd.api_key');
            $senderId = config('services.sms.bulksms_bd.sender_id');
            
            // Validate configuration
            if (empty($apiKey) || empty($senderId)) {
                Log::error('BulkSMS BD: API credentials not configured');
                return false;
            }
            
            // Prepare data
            $data = [
                'api_key' => $apiKey,
                'senderid' => $senderId,
                'number' => $phone,
                'message' => $message
            ];
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            // Execute request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            // Log the response
            Log::info('BulkSMS BD Response:', [
                'phone' => $phone,
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $curlError,
            ]);
            
            // Check for success
            if ($httpCode === 200 && !$curlError) {
                return true;
            }
            
            Log::error('BulkSMS BD: SMS sending failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $curlError,
            ]);
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('BulkSMS BD Exception:', [
                'error' => $e->getMessage(),
                'phone' => $phone,
            ]);
            return false;
        }
    }
}

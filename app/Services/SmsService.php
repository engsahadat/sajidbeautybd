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
            $phone = $this->cleanPhoneNumber($phoneNumber);
            if (!$phone) {
                Log::error('SMS: Invalid phone number format', [
                    'original_phone' => $phoneNumber,
                ]);
                return false;
            }
            $message = $this->formatOrderMessage($order);
            $gateway = config('services.sms.default', 'log');
            if ($gateway === 'bulksms_bd') {
                return $this->sendViaBulkSmsBD($phone, $message);
            } else {
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
                'trace' => $e->getTraceAsString(),
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
     * Clean and format Bangladesh phone number
     * Valid formats: 01712345678, 8801712345678, +8801712345678
     * Returns: 01712345678 (11 digits starting with 01)
     * 
     * @param string $phoneNumber
     * @return string|null
     */
    protected function cleanPhoneNumber(string $phoneNumber): ?string
    {
        $phone = preg_replace('/[^0-9+]/', '', $phoneNumber);
        $phone = str_replace('+', '', $phone);
        if (str_starts_with($phone, '88')) {
            $phone = substr($phone, 2);
        }
        if (strlen($phone) == 11 && str_starts_with($phone, '01')) {
            $validPrefixes = ['013', '014', '015', '016', '017', '018', '019'];
            $prefix = substr($phone, 0, 3);
            
            if (in_array($prefix, $validPrefixes)) {
                return $phone;
            }
        }
        if (strlen($phone) == 10 && str_starts_with($phone, '1')) {
            $phone = '0' . $phone;
            return $phone;
        }
        return null;
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
            $baseUrl = config('services.sms.bulksms_bd.url', 'http://bulksmsbd.net/api/smsapi');
            $apiKey = config('services.sms.bulksms_bd.api_key');
            $senderId = config('services.sms.bulksms_bd.sender_id');
            $encodedMessage = urlencode($message);
            $url = $baseUrl . '?api_key=' . $apiKey
                . '&type=text'
                . '&number=' . $phone
                . '&senderid=' . $senderId
                . '&message=' . $encodedMessage;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            $responseData = json_decode($response, true);
            if ($httpCode === 200 && !$curlError) {
                if (isset($responseData['response_code'])) {
                    $responseCode = $responseData['response_code'];
                    $errorMessage = $responseData['error_message'] ?? 'Unknown error';
                    if ($responseCode == 1032) {
                        Log::error('BulkSMS BD: IP not whitelisted', [
                            'error' => $errorMessage,
                            'solution' => 'Please whitelist your server IP in BulkSMS BD Phonebook settings',
                        ]);
                    } elseif ($responseCode == 1001) {
                        Log::error('BulkSMS BD: Invalid phone number', [
                            'phone' => $phone,
                            'error' => $errorMessage,
                            'solution' => 'Phone number must be 11 digits starting with 01 (e.g., 01712345678)',
                        ]);
                    } else {
                        Log::error('BulkSMS BD: SMS sending failed', [
                            'response_code' => $responseCode,
                            'error_message' => $errorMessage,
                        ]);
                    }
                }
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

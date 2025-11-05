<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BrevoMailService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY', '');
    }

    /**
     * Send email using Brevo API
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return bool
     */
    public function sendEmail(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlContent,
        ?string $fromEmail = null,
        ?string $fromName = null
    ): bool {
        try {
            $fromEmail = $fromEmail ?? 'sajidbeautybd@gmail.com';
            $fromName = $fromName ?? 'Sajid Beauty BD';

            $data = [
                'sender' => [
                    'name' => $fromName,
                    'email' => $fromEmail
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName
                    ]
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent
            ];

            $jsonData = json_encode($data);

            $headers = [
                'Accept: application/json',
                'api-key: ' . $this->apiKey,
                'Content-Type: application/json'
            ];

            $curl = curl_init($this->apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            curl_close($curl);
            if ($httpCode === 201 && !$curlError) {
                return true;
            }

            Log::error('Brevo API: Email sending failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $curlError,
                'to' => $toEmail
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Brevo API Exception:', [
                'error' => $e->getMessage(),
                'to' => $toEmail,
                'subject' => $subject
            ]);
            return false;
        }
    }

    /**
     * Send order confirmation email to customer
     *
     * @param \App\Models\Order $order
     * @return bool
     */
    public function sendOrderConfirmation($order): bool
    {
        $customerName = $order->billing_first_name . ' ' . $order->billing_last_name;
        $customerEmail = $order->user ? $order->user->email : $order->billing_email;

        $subject = 'Order Confirmation - ' . $order->order_number;
        $htmlContent = view('emails.order-placed-customer', ['order' => $order])->render();

        return $this->sendEmail($customerEmail, $customerName, $subject, $htmlContent);
    }

    /**
     * Send order notification to shop owner
     *
     * @param \App\Models\Order $order
     * @return bool
     */
    public function sendOrderNotificationToShop($order): bool
    {
        $shopEmail = 'sajidbeautybd@gmail.com';
        $shopName = 'Shop Owner';

        $subject = 'New Order Received - ' . $order->order_number;
        $htmlContent = view('emails.order-placed-shop-owner', ['order' => $order])->render();

        return $this->sendEmail($shopEmail, $shopName, $subject, $htmlContent);
    }

    /**
     * Send contact form message
     *
     * @param array $data
     * @return bool
     */
    public function sendContactMessage(array $data): bool
    {
        try {
            $shopEmail = 'sajidbeautybd@gmail.com';
            $shopName = 'Shop Owner';
            
            $subject = '[Contact] ' . $data['subject'];
            $htmlContent = view('emails.contact_message', ['data' => $data])->render();

            $emailData = [
                'sender' => [
                    'name' => 'Sajid Beauty BD',
                    'email' => 'sajidbeautybd@gmail.com'
                ],
                'to' => [
                    [
                        'email' => $shopEmail,
                        'name' => $shopName
                    ]
                ],
                'replyTo' => [
                    'email' => $data['email'],
                    'name' => $data['name']
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent
            ];

            $jsonData = json_encode($emailData);

            $headers = [
                'Accept: application/json',
                'api-key: ' . $this->apiKey,
                'Content-Type: application/json'
            ];

            $curl = curl_init($this->apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            curl_close($curl);

            if ($httpCode === 201 && !$curlError) {
                return true;
            }

            Log::error('Brevo API: Contact email failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $curlError,
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Brevo API Contact Exception:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }
}

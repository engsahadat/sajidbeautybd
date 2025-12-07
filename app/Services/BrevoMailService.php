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

    /**
     * Send password reset email
     *
     * @param string $email
     * @param string $name
     * @param string $resetUrl
     * @return bool
     */
    public function sendPasswordResetEmail(string $email, string $name, string $resetUrl): bool
    {
        $subject = 'Reset Your Password - Sajid Beauty BD';
        
        $htmlContent = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
                <h2 style="color: #d63384; margin-bottom: 20px;">Reset Your Password</h2>
                
                <p>Hello ' . htmlspecialchars($name) . ',</p>
                
                <p>You are receiving this email because we received a password reset request for your account.</p>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="' . $resetUrl . '" 
                       style="display: inline-block; background-color: #d63384; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                        Reset Password
                    </a>
                </div>
                
                <p>This password reset link will expire in 60 minutes.</p>
                
                <p>If you did not request a password reset, no further action is required.</p>
                
                <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
                
                <p style="font-size: 12px; color: #666;">
                    If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                </p>
                <p style="font-size: 12px; color: #666; word-break: break-all;">
                    ' . htmlspecialchars($resetUrl) . '
                </p>
                
                <p style="margin-top: 30px; font-size: 14px; color: #666;">
                    Best regards,<br>
                    <strong>Sajid Beauty BD</strong>
                </p>
            </div>
        </body>
        </html>
        ';
        
        return $this->sendEmail($email, $name, $subject, $htmlContent);
    }
}

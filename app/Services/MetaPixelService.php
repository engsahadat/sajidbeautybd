<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Meta (Facebook) Pixel Service
 * 
 * This service handles both browser-side and server-side Meta Pixel tracking
 * for e-commerce events following Meta's standard event specifications.
 * 
 * Documentation: https://developers.facebook.com/docs/meta-pixel
 */
class MetaPixelService
{
    protected ?string $pixelId;
    protected ?string $accessToken;
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = (bool) Setting::get('meta_pixel_enabled', false);
        $this->pixelId = Setting::get('meta_pixel_id');
        $this->accessToken = Setting::get('meta_pixel_access_token');
    }

    /**
     * Check if Meta Pixel is enabled and configured
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->pixelId);
    }

    /**
     * Get the Pixel ID
     */
    public function getPixelId(): ?string
    {
        return $this->pixelId;
    }

    /**
     * Generate browser-side pixel script for inclusion in HTML head
     */
    public function getPixelScript(): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        return <<<HTML
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{$this->pixelId}');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={$this->pixelId}&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
HTML;
    }

    /**
     * Track ViewContent event (browser-side JS)
     */
    public function getViewContentScript(array $data): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        return "<script>if(typeof fbq !== 'undefined') fbq('track', 'ViewContent', {$json});</script>";
    }

    /**
     * Track AddToCart event (browser-side JS)
     */
    public function getAddToCartScript(array $data): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        return "<script>if(typeof fbq !== 'undefined') fbq('track', 'AddToCart', {$json});</script>";
    }

    /**
     * Track InitiateCheckout event (browser-side JS)
     */
    public function getInitiateCheckoutScript(array $data): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        return "<script>if(typeof fbq !== 'undefined') fbq('track', 'InitiateCheckout', {$json});</script>";
    }

    /**
     * Track Purchase event (browser-side JS)
     */
    public function getPurchaseScript(array $data): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        return "<script>if(typeof fbq !== 'undefined') fbq('track', 'Purchase', {$json});</script>";
    }

    /**
     * Track Search event (browser-side JS)
     */
    public function getSearchScript(string $searchQuery): string
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $data = ['search_string' => $searchQuery];
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        return "<script>if(typeof fbq !== 'undefined') fbq('track', 'Search', {$json});</script>";
    }

    /**
     * Send server-side event to Meta Conversion API
     * 
     * @param string $eventName Standard event name (e.g., 'Purchase', 'ViewContent')
     * @param array $eventData Event parameters
     * @param array $userData User data for matching (email, phone, etc.)
     * @return bool Success status
     */
    public function sendServerEvent(string $eventName, array $eventData = [], array $userData = []): bool
    {
        if (!$this->isEnabled() || empty($this->accessToken)) {
            return false;
        }

        try {
            $url = "https://graph.facebook.com/v18.0/{$this->pixelId}/events";
            
            $payload = [
                'data' => [
                    [
                        'event_name' => $eventName,
                        'event_time' => time(),
                        'action_source' => 'website',
                        'event_source_url' => url()->current(),
                        'user_data' => $this->hashUserData($userData),
                        'custom_data' => $eventData,
                    ]
                ],
                'access_token' => $this->accessToken,
            ];

            $response = Http::timeout(10)->post($url, $payload);

            if ($response->successful()) {
                Log::info('Meta Pixel server event sent', [
                    'event' => $eventName,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::warning('Meta Pixel server event failed', [
                'event' => $eventName,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Meta Pixel server event exception', [
                'event' => $eventName,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Hash user data according to Meta's requirements
     * 
     * @param array $userData
     * @return array
     */
    protected function hashUserData(array $userData): array
    {
        $hashed = [];

        // Hash email
        if (!empty($userData['email'])) {
            $hashed['em'] = hash('sha256', strtolower(trim($userData['email'])));
        }

        // Hash phone (should be in E.164 format)
        if (!empty($userData['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', $userData['phone']);
            $hashed['ph'] = hash('sha256', $phone);
        }

        // Hash first name
        if (!empty($userData['first_name'])) {
            $hashed['fn'] = hash('sha256', strtolower(trim($userData['first_name'])));
        }

        // Hash last name
        if (!empty($userData['last_name'])) {
            $hashed['ln'] = hash('sha256', strtolower(trim($userData['last_name'])));
        }

        // Hash city
        if (!empty($userData['city'])) {
            $hashed['ct'] = hash('sha256', strtolower(trim($userData['city'])));
        }

        // Hash state
        if (!empty($userData['state'])) {
            $hashed['st'] = hash('sha256', strtolower(trim($userData['state'])));
        }

        // Hash country (ISO 2-letter code)
        if (!empty($userData['country'])) {
            $hashed['country'] = hash('sha256', strtolower(trim($userData['country'])));
        }

        // Hash zip/postal code
        if (!empty($userData['zip'])) {
            $hashed['zp'] = hash('sha256', strtolower(trim($userData['zip'])));
        }

        // Add client IP address (not hashed)
        if (!empty($userData['client_ip_address'])) {
            $hashed['client_ip_address'] = $userData['client_ip_address'];
        } else {
            $hashed['client_ip_address'] = request()->ip();
        }

        // Add user agent (not hashed)
        if (!empty($userData['client_user_agent'])) {
            $hashed['client_user_agent'] = $userData['client_user_agent'];
        } else {
            $hashed['client_user_agent'] = request()->userAgent();
        }

        // Add Facebook Click ID if available
        if (!empty($userData['fbc'])) {
            $hashed['fbc'] = $userData['fbc'];
        }

        // Add Facebook Browser ID if available
        if (!empty($userData['fbp'])) {
            $hashed['fbp'] = $userData['fbp'];
        }

        return $hashed;
    }

    /**
     * Helper: Format product data for Meta Pixel standard
     */
    public function formatProductData($product): array
    {
        return [
            'content_ids' => [(string) $product->id],
            'content_type' => 'product',
            'content_name' => $product->name,
            'value' => (float) $product->price,
            'currency' => 'BDT',
        ];
    }

    /**
     * Helper: Format order data for Purchase event
     */
    public function formatOrderData($order): array
    {
        $contentIds = $order->items->pluck('product_id')->toArray();
        
        return [
            'content_ids' => $contentIds,
            'content_type' => 'product',
            'value' => (float) $order->total_amount,
            'currency' => 'BDT',
            'num_items' => $order->items->count(),
        ];
    }

    /**
     * Helper: Format cart data for AddToCart or InitiateCheckout events
     */
    public function formatCartData($cartItems): array
    {
        $contentIds = $cartItems->pluck('product_id')->toArray();
        $value = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return [
            'content_ids' => $contentIds,
            'content_type' => 'product',
            'value' => (float) $value,
            'currency' => 'BDT',
            'num_items' => $cartItems->count(),
        ];
    }
}

<?php

namespace App\Services\SocialMedia;

use App\Models\Product;
use App\Models\SocialMediaPage;
use App\Models\SocialMediaPost;
use App\Models\SocialMediaSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialMediaService
{
    /**
     * Get authorization URL for connecting a platform
     */
    public function getAuthorizationUrl($platform, $redirectUri)
    {
        $setting = SocialMediaSetting::where('platform', $platform)->first();
        if (!$setting) {
            throw new \Exception("Platform {$platform} is not configured");
        }
        switch ($platform) {
            case SocialMediaSetting::PLATFORM_FACEBOOK:
                return $this->getFacebookAuthUrl($setting, $redirectUri);
            case SocialMediaSetting::PLATFORM_TWITTER:
                return $this->getTwitterAuthUrl($setting, $redirectUri);
            default:
                throw new \Exception("Platform {$platform} is not supported yet");
        }
    }

    /**
     * Get Facebook authorization URL
     */
    protected function getFacebookAuthUrl($setting, $redirectUri)
    {
        $permissions = 'pages_show_list,pages_read_engagement,pages_manage_posts,pages_manage_metadata,pages_manage_engagement,public_profile,email';
        
        return 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
            'client_id' => $setting->app_id,
            'redirect_uri' => $redirectUri,
            'scope' => $permissions,
            'state' => csrf_token()
        ]);
    }

    /**
     * Handle OAuth callback and get access token
     */
    public function handleCallback($platform, $code, $redirectUri)
    {
        $setting = SocialMediaSetting::where('platform', $platform)->first();
        if (!$setting) {
            throw new \Exception("Platform {$platform} is not configured");
        }
        switch ($platform) {
            case SocialMediaSetting::PLATFORM_FACEBOOK:
                return $this->handleFacebookCallback($setting, $code, $redirectUri);
            default:
                throw new \Exception("Platform {$platform} is not supported yet");
        }
    }

    /**
     * Handle Facebook OAuth callback
     */
    protected function handleFacebookCallback($setting, $code, $redirectUri)
    {
        $response = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
            'client_id' => $setting->app_id,
            'client_secret' => $setting->app_secret,
            'redirect_uri' => $redirectUri,
            'code' => $code
        ]);
        if ($response->failed()) {
            throw new \Exception('Failed to get access token from Facebook');
        }
        $data = $response->json();
        $setting->update([
            'access_token' => $data['access_token'],
            'token_expires_at' => now()->addSeconds($data['expires_in'] ?? 5184000) // 60 days default
        ]);
        return $setting;
    }

    /**
     * Check Facebook token permissions
     */
    public function checkFacebookPermissions($setting)
    {
        $response = Http::get('https://graph.facebook.com/v18.0/me/permissions', [
            'access_token' => $setting->access_token
        ]);

        if ($response->successful()) {
            $permissions = $response->json()['data'] ?? [];
            $grantedPermissions = collect($permissions)
                ->filter(fn($p) => $p['status'] === 'granted')
                ->pluck('permission')
                ->toArray();
            
            Log::info('Facebook permissions granted', ['permissions' => $grantedPermissions]);
            return $grantedPermissions;
        }

        return [];
    }

    /**
     * Fetch user's Facebook pages
     */
    public function fetchFacebookPages($setting)
    {
        Log::info('Fetching Facebook pages', [
            'setting_id' => $setting->id,
            'has_token' => !empty($setting->access_token)
        ]);

        // Check permissions first
        $permissions = $this->checkFacebookPermissions($setting);
        $requiredPermissions = ['pages_show_list', 'pages_read_engagement', 'pages_manage_posts'];
        $missingPermissions = array_diff($requiredPermissions, $permissions);
        
        if (!empty($missingPermissions)) {
            Log::warning('Missing required Facebook permissions', ['missing' => $missingPermissions]);
        }

        // Get user info for debugging
        $userResponse = Http::get('https://graph.facebook.com/v18.0/me', [
            'access_token' => $setting->access_token,
            'fields' => 'id,name,email'
        ]);
        
        Log::info('Facebook user info', ['user' => $userResponse->json()]);

        // Try to get accounts with more details
        $response = Http::get('https://graph.facebook.com/v18.0/me/accounts', [
            'access_token' => $setting->access_token,
            'fields' => 'id,name,access_token,picture,username,tasks'
        ]);

        Log::info('Facebook API Response', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        if ($response->failed()) {
            $error = $response->json();
            Log::error('Failed to fetch Facebook pages', ['response' => $error]);
            throw new \Exception('Failed to fetch Facebook pages: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        $pages = $response->json()['data'] ?? [];
        
        // If no pages found with /me/accounts, try checking for business-managed pages
        if (empty($pages)) {
            Log::info('No pages found via /me/accounts, checking business pages');
            
            $businessResponse = Http::get('https://graph.facebook.com/v18.0/me/businesses', [
                'access_token' => $setting->access_token,
                'fields' => 'id,name'
            ]);
            
            if ($businessResponse->successful()) {
                $businesses = $businessResponse->json()['data'] ?? [];
                Log::info('Businesses found', ['count' => count($businesses), 'businesses' => $businesses]);
                
                foreach ($businesses as $business) {
                    $businessPagesResponse = Http::get("https://graph.facebook.com/v18.0/{$business['id']}/client_pages", [
                        'access_token' => $setting->access_token,
                        'fields' => 'id,name,access_token,picture,username'
                    ]);
                    
                    if ($businessPagesResponse->successful()) {
                        $businessPages = $businessPagesResponse->json()['data'] ?? [];
                        $pages = array_merge($pages, $businessPages);
                        Log::info('Business pages found', ['business' => $business['name'], 'pages_count' => count($businessPages)]);
                    }
                }
            }
        }
        
        Log::info('Facebook pages found', ['count' => count($pages)]);
        
        if (empty($pages)) {
            Log::warning('No Facebook pages found for user');
        }
        
        $connectedPages = [];

        foreach ($pages as $pageData) {
            Log::info('Saving Facebook page', ['page_name' => $pageData['name'], 'page_id' => $pageData['id']]);
            
            $page = SocialMediaPage::updateOrCreate(
                [
                    'platform' => SocialMediaSetting::PLATFORM_FACEBOOK,
                    'page_id' => $pageData['id']
                ],
                [
                    'social_media_setting_id' => $setting->id,
                    'page_name' => $pageData['name'],
                    'page_username' => $pageData['username'] ?? null,
                    'page_access_token' => $pageData['access_token'],
                    'page_picture' => $pageData['picture']['data']['url'] ?? null,
                    'page_url' => 'https://facebook.com/' . ($pageData['username'] ?? $pageData['id']),
                    'is_connected' => true,
                    'connected_at' => now()
                ]
            );

            Log::info('Facebook page saved', ['page_id' => $page->id, 'page_name' => $page->page_name]);
            $connectedPages[] = $page;
        }

        Log::info('Total pages connected', ['count' => count($connectedPages)]);
        return $connectedPages;
    }

    /**
     * Manually add a Facebook page by page ID
     */
    public function connectFacebookPageManually($setting, $pageId)
    {
        Log::info('Manually connecting Facebook page', ['page_id' => $pageId]);

        // Try to get page info with user token
        $response = Http::get("https://graph.facebook.com/v18.0/{$pageId}", [
            'access_token' => $setting->access_token,
            'fields' => 'id,name,username,picture,access_token'
        ]);

        if ($response->failed()) {
            $error = $response->json();
            Log::error('Failed to get page info', ['page_id' => $pageId, 'error' => $error]);
            throw new \Exception('Failed to connect page: ' . ($error['error']['message'] ?? 'Page not found or no access'));
        }

        $pageData = $response->json();
        Log::info('Page data retrieved', ['page' => $pageData]);

        $page = SocialMediaPage::updateOrCreate(
            [
                'platform' => SocialMediaSetting::PLATFORM_FACEBOOK,
                'page_id' => $pageData['id']
            ],
            [
                'social_media_setting_id' => $setting->id,
                'page_name' => $pageData['name'],
                'page_username' => $pageData['username'] ?? null,
                'page_access_token' => $pageData['access_token'] ?? $setting->access_token,
                'page_picture' => $pageData['picture']['data']['url'] ?? null,
                'page_url' => 'https://facebook.com/' . ($pageData['username'] ?? $pageData['id']),
                'is_connected' => true,
                'connected_at' => now()
            ]
        );

        Log::info('Page manually connected', ['page_id' => $page->id, 'page_name' => $page->page_name]);
        return $page;
    }

    /**
     * Share product to social media page
     */
    public function shareProduct(Product $product, SocialMediaPage $page, $message = null, $userId = null)
    {
        $post = SocialMediaPost::create([
            'social_media_page_id' => $page->id,
            'product_id' => $product->id,
            'user_id' => $userId ?? Auth::id(),
            'platform' => $page->platform,
            'message' => $message ?? $this->generateDefaultMessage($product),
            'status' => SocialMediaPost::STATUS_PENDING
        ]);
        try {
            switch ($page->platform) {
                case SocialMediaSetting::PLATFORM_FACEBOOK:
                    return $this->shareToFacebook($product, $page, $post);
                default:
                    throw new \Exception("Sharing to {$page->platform} is not supported yet");
            }
        } catch (\Exception $e) {
            $post->update([
                'status' => SocialMediaPost::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Share product to Facebook page
     */
    protected function shareToFacebook(Product $product, SocialMediaPage $page, SocialMediaPost $post)
    {
        $productUrl = url('/product-details/' . $product->id);
        $imageUrl = $product->image_url ?? null;

        $postData = [
            'access_token' => $page->page_access_token,
            'message' => $post->message,
            'link' => $productUrl,
            'published' => true,
            'privacy' => json_encode(['value' => 'EVERYONE'])  // Set explicit public visibility
        ];

        // If we have an image, use photo endpoint
        if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $response = Http::post("https://graph.facebook.com/v18.0/{$page->page_id}/photos", [
                'access_token' => $page->page_access_token,
                'url' => $imageUrl,
                'caption' => $post->message . "\n\n" . $productUrl,
                'published' => true,
                'privacy' => json_encode(['value' => 'EVERYONE'])  // Set explicit public visibility
            ]);
        } else {
            $response = Http::post("https://graph.facebook.com/v18.0/{$page->page_id}/feed", $postData);
        }

        if ($response->failed()) {
            throw new \Exception('Failed to post to Facebook: ' . $response->body());
        }

        $responseData = $response->json();
        $post->update([
            'status' => SocialMediaPost::STATUS_PUBLISHED,
            'post_id' => $responseData['id'] ?? $responseData['post_id'] ?? null,
            'post_url' => "https://facebook.com/{$page->page_id}/posts/" . ($responseData['id'] ?? ''),
            'published_at' => now(),
            'media_urls' => $imageUrl ? [$imageUrl] : null
        ]);
        return $post;
    }

    /**
     * Generate default message for product
     */
    protected function generateDefaultMessage(Product $product)
    {
        $message = "🛍️ {$product->name}\n\n";
        if ($product->description) {
            $description = strip_tags($product->description);
            $message .= substr($description, 0, 200);
            if (strlen($description) > 200) {
                $message .= '...';
            }
            $message .= "\n\n";
        }
        $message .= "💰 Price: ৳" . number_format((float)($product->price ?? 0), 2) . "\n";
        if ($product->stock_quantity > 0) {
            $message .= "✅ In Stock\n";
        }
        $message .= "\n🔗 Shop now!";
        return $message;
    }

    /**
     * Disconnect a social media page
     */
    public function disconnectPage(SocialMediaPage $page)
    {
        $page->update([
            'is_connected' => false,
            'page_access_token' => null
        ]);

        return true;
    }

    /**
     * Get post analytics (if supported by platform)
     */
    public function getPostAnalytics(SocialMediaPost $post)
    {
        if (!$post->post_id || $post->status !== SocialMediaPost::STATUS_PUBLISHED) {
            return null;
        }

        try {
            switch ($post->platform) {
                case SocialMediaSetting::PLATFORM_FACEBOOK:
                    return $this->getFacebookPostAnalytics($post);
                
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error('Failed to get post analytics: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get Facebook post analytics
     */
    protected function getFacebookPostAnalytics(SocialMediaPost $post)
    {
        $response = Http::get("https://graph.facebook.com/v18.0/{$post->post_id}", [
            'access_token' => $post->page->page_access_token,
            'fields' => 'shares,likes.summary(true),comments.summary(true),reactions.summary(true)'
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        $analytics = [
            'likes' => $data['likes']['summary']['total_count'] ?? 0,
            'comments' => $data['comments']['summary']['total_count'] ?? 0,
            'shares' => $data['shares']['count'] ?? 0,
            'reactions' => $data['reactions']['summary']['total_count'] ?? 0,
            'fetched_at' => now()->toDateTimeString()
        ];
        $post->update(['analytics' => $analytics]);
        return $analytics;
    }
}

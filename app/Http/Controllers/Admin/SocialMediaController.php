<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SocialMediaPage;
use App\Models\SocialMediaPost;
use App\Models\SocialMediaSetting;
use App\Services\SocialMedia\SocialMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialMediaController extends Controller
{
    protected $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Show social media settings
     */
    public function settings()
    {
        $platforms = SocialMediaSetting::getPlatforms();
        $settings = SocialMediaSetting::all()->keyBy('platform');

        return view('admin.social-media.settings', compact('platforms', 'settings'));
    }

    /**
     * Update social media settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:' . implode(',', array_keys(SocialMediaSetting::getPlatforms())),
            'app_id' => 'required|string',
            'app_secret' => 'required|string',
        ]);

        SocialMediaSetting::updateOrCreate(
            ['platform' => $request->platform],
            [
                'app_id' => $request->app_id,
                'app_secret' => $request->app_secret,
                'is_active' => $request->has('is_active')
            ]
        );

        return redirect()->back()->with('success', ucfirst($request->platform) . ' settings updated successfully');
    }

    /**
     * Show connect pages view
     */
    public function connectPages()
    {
        $settings = SocialMediaSetting::where('is_active', true)->get();
        $connectedPages = SocialMediaPage::with('setting')
            ->where('is_connected', true)
            ->get();

        return view('admin.social-media.connect-pages', compact('settings', 'connectedPages'));
    }

    /**
     * Initiate OAuth connection
     */
    public function initiateConnection($platform)
    {
        try {
            $redirectUri = route('admin.social-media.callback', $platform);
            $authUrl = $this->socialMediaService->getAuthorizationUrl($platform, $redirectUri);

            return redirect($authUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(Request $request, $platform)
    {
        try {
            if ($request->has('error')) {
                throw new \Exception('Authorization failed: ' . $request->error_description);
            }

            $redirectUri = route('admin.social-media.callback', $platform);
            $setting = $this->socialMediaService->handleCallback($platform, $request->code, $redirectUri);

            // Fetch and save pages
            if ($platform === SocialMediaSetting::PLATFORM_FACEBOOK) {
                Log::info('Starting Facebook page fetch for setting ID: ' . $setting->id);
                $pages = $this->socialMediaService->fetchFacebookPages($setting);
                Log::info('Facebook pages fetched', ['count' => count($pages)]);
                
                if (empty($pages)) {
                    return redirect()->route('admin.social-media.connect-pages')
                        ->with('warning', ucfirst($platform) . ' connected successfully, but no pages found. Make sure you have Facebook pages and granted the required permissions.');
                }
            }

            return redirect()->route('admin.social-media.connect-pages')
                ->with('success', ucfirst($platform) . ' connected successfully!');

        } catch (\Exception $e) {
            Log::error('OAuth callback error', ['platform' => $platform, 'error' => $e->getMessage()]);
            return redirect()->route('admin.social-media.connect-pages')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Disconnect a page
     */
    public function disconnectPage(SocialMediaPage $page)
    {
        try {
            $this->socialMediaService->disconnectPage($page);
            return redirect()->back()->with('success', 'Page disconnected successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Manually fetch pages for a platform
     */
    public function fetchPages(Request $request, $platform)
    {
        try {
            $setting = SocialMediaSetting::where('platform', $platform)
                ->where('is_active', true)
                ->firstOrFail();

            if (!$setting->access_token || $setting->isTokenExpired()) {
                return redirect()->back()->with('error', 'Platform not authorized or token expired. Please reconnect.');
            }

            if ($platform === SocialMediaSetting::PLATFORM_FACEBOOK) {
                // Check permissions first
                $permissions = $this->socialMediaService->checkFacebookPermissions($setting);
                $requiredPermissions = ['pages_show_list', 'pages_read_engagement', 'pages_manage_posts'];
                $missingPermissions = array_diff($requiredPermissions, $permissions);
                
                $pages = $this->socialMediaService->fetchFacebookPages($setting);
                
                if (empty($pages)) {
                    $message = 'No Facebook pages found. ';
                    
                    if (!empty($missingPermissions)) {
                        $message .= 'Missing permissions: ' . implode(', ', $missingPermissions) . '. ';
                    } else {
                        $message .= 'All permissions are granted correctly. ';
                    }
                    
                    $message .= 'This means your page might be managed through Meta Business Manager. Try entering your Page ID manually below using "Connect by Page ID" button.';
                    
                    return redirect()->back()->with('warning', $message);
                }
                
                return redirect()->back()->with('success', count($pages) . ' Facebook page(s) fetched successfully!');
            }

            return redirect()->back()->with('error', 'Page fetching for ' . $platform . ' is not supported yet.');
        } catch (\Exception $e) {
            Log::error('Manual page fetch error', ['platform' => $platform, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to fetch pages: ' . $e->getMessage());
        }
    }

    /**
     * Manually connect a Facebook page by Page ID
     */
    public function connectPageManually(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:facebook',
            'page_id' => 'required|string'
        ]);

        try {
            $setting = SocialMediaSetting::where('platform', $request->platform)
                ->where('is_active', true)
                ->firstOrFail();

            if (!$setting->access_token || $setting->isTokenExpired()) {
                return redirect()->back()->with('error', 'Platform not authorized or token expired. Please reconnect.');
            }

            $page = $this->socialMediaService->connectFacebookPageManually($setting, $request->page_id);

            return redirect()->back()->with('success', 'Page "' . $page->page_name . '" connected successfully!');
        } catch (\Exception $e) {
            Log::error('Manual page connection error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to connect page: ' . $e->getMessage());
        }
    }

    /**
     * Show products list for sharing
     */
    public function products()
    {
        $products = Product::where('is_active', 1)
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(20);

        $connectedPages = SocialMediaPage::where('is_connected', true)->get();

        return view('admin.social-media.products', compact('products', 'connectedPages'));
    }

    /**
     * Share product to social media
     */
    public function shareProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'page_id' => 'required|exists:social_media_pages,id',
            'message' => 'nullable|string|max:5000'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $page = SocialMediaPage::findOrFail($request->page_id);

            $post = $this->socialMediaService->shareProduct(
                $product,
                $page,
                $request->message,
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Product shared successfully!',
                'post' => $post
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show shared posts history
     */
    public function posts()
    {
        $posts = SocialMediaPost::with(['product', 'page', 'user'])
            ->latest()
            ->paginate(50);

        return view('admin.social-media.posts', compact('posts'));
    }

    /**
     * Refresh post analytics
     */
    public function refreshAnalytics(SocialMediaPost $post)
    {
        try {
            $analytics = $this->socialMediaService->getPostAnalytics($post);
            
            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

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
                $this->socialMediaService->fetchFacebookPages($setting);
            }

            return redirect()->route('admin.social-media.connect-pages')
                ->with('success', ucfirst($platform) . ' connected successfully!');

        } catch (\Exception $e) {
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

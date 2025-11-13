<?php

namespace App\Http\Controllers\Front;

use \App\Services\BrevoMailService;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Discount;
use App\Models\HomePage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', 1)->where('status', 'active')->orderBy('sort_order', 'asc')->take(8)->get();
        $brands = Brand::where('is_active', 1)->where('status', 'active')->orderBy('sort_order', 'asc')->get();
        $homePages = HomePage::all();
        $sliderImages = $homePages->where('type', 'slider');
        $bannerImages = $homePages->where('type', 'banner');
        $middleImages = $homePages->where('type', 'middle');
        $serviceImages = $homePages->where('type', 'service');
        $categoryId = $request->query('category_id');
        $productsQuery = Product::with(['brand', 'category'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');
        if ($categoryId) {
            $productsQuery->where('category_id', (int)$categoryId);
        } else {
            $productsQuery->where('is_featured', 1);
        }
        $products = $productsQuery->paginate(20)->withQueryString();
        $blogs = Blog::with('author')
            ->active()
            ->published()
            ->orderBy('published_at', 'desc')
            ->get();
        return view('front-end.index', compact('categories', 'brands', 'homePages', 'sliderImages', 'bannerImages', 'middleImages', 'serviceImages', 'products', 'blogs'));
    }
    public function productDetails($id){
        $product = Product::with([
                'brand', 
                'category', 
                'reviews' => function($q) {
                    $q->where('status', 'approved')->latest();
                },
                'variants' => function($q) {
                    $q->orderBy('sort_order')->orderByDesc('id');
                },
                'attributes' => function($q) {
                    $q->orderBy('attribute_group')->orderBy('sort_order');
                }
            ])
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();
        $product->reviews_count = $product->reviews()->where('status', 'approved')->count();
        $variantGroups = $product->variants ? $product->variants->groupBy('name') : collect();
        $attributeGroups = $product->attributes ? $product->attributes->groupBy('attribute_group') : collect();
        return view('front-end.home.product-details', compact('product','variantGroups','attributeGroups'));
    }
    public function allCategory(Request $request){
        $query = Category::where('is_active', 1)->where('status', 'active');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();
        return view('front-end.home.all-category', compact('categories'));
    }
    public function allProducts(Request $request){
        $query = Product::with(['brand:id,name', 'category:id,name'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(20)->withQueryString();
        
        return view('front-end.home.all-products', compact('products'));
    }
    public function category($id, Request $request){
        $category = Category::where('id', $id)->where('is_active', 1)->where('status', 'active')->firstOrFail();
        $query = Product::with(['brand', 'category'])
            ->where('category_id', $id)
            ->where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order', 'asc')->get();
        return view('front-end.home.category', compact('products', 'category'));
    }
    public function allBrand(Request $request){
        $query = Brand::where('is_active', 1)
            ->where('status', 'active');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $brands = $query->orderBy('sort_order', 'asc')->paginate(12)->withQueryString();
        return view('front-end.home.all-brand', compact('brands'));
    }
    public function brand($id, Request $request){
        $brand = Brand::where('id', $id)->firstOrFail();
        $query = Product::with(['brand', 'category'])
            ->where('brand_id', $id)
            ->where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('sort_order', 'asc')->get();
        return view('front-end.home.brand', compact('products', 'brand'));
    }

    public function discount(){
        $discounts = Discount::where('status', 'active')->orderBy('created_at', 'desc')->get();
        return view('front-end.home.discount', compact('discounts'));
    }

    public function aboutUs()
    {
        return view('front-end.home.about-us');
    }

    public function contactUs()
    {
        return view('front-end.home.contact-us');
    }
    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:160'],
            'phone' => ['nullable','string','max:30'],
            'subject' => ['required','string','max:160'],
            'message' => ['required','string','max:2000'],
        ]);
        
        // Store contact message in database
        ContactMessage::create($data + [
            'meta' => [
                'ip' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(),0,255),
            ],
        ]);

        $mailer = 'brevo-api';
        $mailSent = true;
        
        try {
            // Use Brevo API for reliable email delivery
            $brevoMail = new BrevoMailService();
            $mailSent = $brevoMail->sendContactMessage($data);
        } catch(\Throwable $e) {
            $mailSent = false;
            Log::error('Contact mail failed:', [
                'error' => $e->getMessage(),
                'from' => $data['email'],
                'subject' => $data['subject']
            ]);
        }

        return back()->with([
            'contact_submitted' => true,
            'contact_mail_sent' => $mailSent,
            'contact_mailer' => $mailer,
        ]);
    }

    public function termsAndConditions()
    {
        return view('front-end.home.terms-and-conditions');
    }

    public function privacyPolicy()
    {
        return view('front-end.home.privacy-policy');
    }

    public function refundPolicy()
    {
        return view('front-end.home.refund-policy');
    }

    public function deliveryPolicy()
    {
        return view('front-end.home.delivery-policy');
    }
}

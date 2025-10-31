<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Discount;
use App\Models\HomePage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $product = Product::with(['brand', 'category', 'reviews'])
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();
        $product->reviews_count = $product->reviews()->count();
        return view('front-end.home.product-details', compact('product'));
    }
    public function allCategory(){
        $categories = Category::where('is_active', 1)->where('status', 'active')->orderBy('sort_order', 'asc')->paginate(12);
        return view('front-end.home.all-category', compact('categories'));
    }
    public function category($id){
        $category = Category::where('id', $id)->where('is_active', 1)->where('status', 'active')->firstOrFail();
        $products = Product::with(['brand', 'category'])
            ->where('category_id', $id)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
        return view('front-end.home.category', compact('products', 'category'));
    }
    public function allBrand(){
        $brands = Brand::where('is_active', 1)
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->paginate(12);
        return view('front-end.home.all-brand', compact('brands'));
    }
    public function brand($id){
        $brand = Brand::where('id', $id)->firstOrFail();
        $products = Product::with(['brand', 'category'])
            ->where('brand_id', $id)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
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
        $stored = ContactMessage::create($data + [
            'meta' => [
                'ip' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(),0,255),
            ],
        ]);

        $mailer = config('mail.default');
        $mailSent = true;
        try {
            $to = \App\Models\Setting::get('contact_email') ?: (config('mail.to.address') ?: config('mail.from.address'));
            if(!$to) {
                throw new \RuntimeException('No recipient email configured (MAIL_TO_ADDRESS or MAIL_FROM_ADDRESS).');
            }
            Mail::to($to)->send(new ContactMessageMail($data));
        } catch(\Throwable $e) {
            $mailSent = false;
            Log::warning('Contact mail failed: '.$e->getMessage());
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

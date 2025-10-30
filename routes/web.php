<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Front\BlogController as FrontBlogController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentController as FrontPaymentController;
use App\Http\Controllers\Front\MyOrdersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/**
 * Admin
 */
Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->controller(AdminAuthController::class)->group(function () {
        Route::get('/', 'loginCreate')->name('admin.login');
        Route::post('/login', 'loginStore')->name('admin.login.store');
        Route::get('/forgot-password', 'forgotPasswordCreate')->name('admin.forgot-password');
        Route::post('/forgot-password', 'forgotPasswordStore')->name('admin.forgot-password.store');
        Route::get('/reset-password/{token}', 'resetPasswordCreate')->name('admin.reset-password');
        Route::post('/reset-password', 'resetPasswordStore')->name('admin.reset-password.store');
    });
    Route::middleware(['auth', 'admin'])->group(function () {
        //logout Route
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        //dashboard Route
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
        // Category Routes
        Route::resource('categories', CategoryController::class);
        // Brand Routes
        Route::resource('brands', BrandController::class);
        // Product Routes
        Route::resource('products', ProductController::class);
        // User Routes
        Route::resource('users', UserController::class);
        // Home Pages CRUD
        Route::resource('home-pages', HomePageController::class);
        // Coupons Routes
        Route::resource('coupons', CouponController::class);
        // Discount Routes
        Route::resource('discounts', DiscountController::class);
        // Suppliers
        Route::resource('suppliers', SupplierController::class);
        // Vendors
        Route::resource('vendors', VendorController::class);
        // Blogs
        Route::resource('blogs', BlogController::class);
        // Orders Routes
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'edit', 'update']);
        //product Routes
        Route::controller(ProductController::class)->group(function () {
            Route::get('products/reviews', 'reviews')->name('products.reviews.index');
            Route::get('products/{product}/reviews', 'reviews')->name('products.reviews.view');
            Route::delete('products/{product}/reviews/{review}', 'deleteReview')->name('products.reviews.destroy');
        });
        
        Route::prefix('orders/{order}')->name('orders.')->group(function () {
            Route::post('items', [OrderItemController::class, 'store'])->name('items.store');
            Route::put('items/{item}', [OrderItemController::class, 'update'])->name('items.update');
            Route::delete('items/{item}', [OrderItemController::class, 'destroy'])->name('items.destroy');
            Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
            Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
            Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        });
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Settings
        Route::get('settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');

        // Reports
        Route::get('reports', [ReportsController::class, 'index'])->name('admin.reports.index');
    });
});



/**
 * client (front-end). Prevent admins from visiting these routes.
 */
Route::middleware('front')->controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/all-category', 'allCategory')->name('home.all-category');
    Route::get('/category/{id}', 'category')->name('home.category');
    Route::get('/all-brand', 'allBrand')->name('home.all-brand');
    Route::get('/brand/{id}', 'brand')->name('home.brand');
    Route::get('/discount', 'discount')->name('home.discount');
    Route::get('/product-details/{id}', 'productDetails')->name('home.product.details');
    Route::get('/about-us', 'aboutUs')->name('home.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('home.contactUs');
    Route::post('/contact-us', 'submitContact')->name('home.contact.submit');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('home.terms-and-conditions');
});
Route::controller(FrontBlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('front.blog.index');
    Route::get('/blogs/{id}', 'show')->name('front.blog.show');
});

// Cart routes (front-end)
Route::controller(CartController::class)->prefix('cart')->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add', 'add')->name('cart.add');
    Route::post('/item/{item}/update', 'update')->name('cart.item.update');
    Route::delete('/item/{item}', 'remove')->name('cart.item.remove');
    Route::delete('/clear', 'clear')->name('cart.clear');
    Route::get('/count', 'getCartCount')->name('cart.count');
    Route::post('/apply-coupon', 'applyCoupon')->name('cart.applyCoupon');
    Route::delete('/remove-coupon', 'removeCoupon')->name('cart.removeCoupon');

    // Wishlist routes
    Route::post('/cart/wishlist/toggle', 'toggleWishlist')->name('cart.toggleWishlist');
    Route::get('/wishlist', 'wishlist')->name('wishlist.index')->middleware('auth');
    Route::get('/wishlist/count', 'getWishlistCount')->name('wishlist.count');
    Route::get('/wishlist/items', 'getWishlistItems')->name('get.wishlist.items');

    // Compare routes
    Route::post('/cart/compare/toggle', 'toggleCompare')->name('cart.toggleCompare');
    Route::get('/compare', 'compare')->name('compare.index')->middleware('auth');
    Route::get('/compare/count', 'getCompareCount')->name('compare.count');
    Route::get('/compare/items', 'getCompareItems')->name('get.compare.items');
    // User preferences route
    Route::get('/user/preferences', 'getUserPreferences')->name('user.preferences');
});

// Checkout routes
Route::controller(CheckoutController::class)->prefix('checkout')->group(function () {
    Route::get('/', 'show')->name('checkout.show');
    Route::post('/', 'place')->name('checkout.place');
    Route::get('/success/{orderNumber}', 'success')->name('checkout.success');
});

// Payment routes (front)
Route::get('/payment/initiate/{gateway}/{order}', [FrontPaymentController::class, 'initiate'])
    ->where(['gateway' => 'sslcommerz|bkash'])
    ->middleware('signed')
    ->name('payment.initiate');
Route::match(['GET','POST'], '/payment/callback/{gateway}', [FrontPaymentController::class, 'callback'])
    ->where(['gateway' => 'sslcommerz|bkash'])
    ->name('payment.callback');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Orders (front-end, authenticated)
    Route::get('/my-orders', [MyOrdersController::class, 'index'])->name('account.orders.index');
    Route::get('/my-orders/{orderNumber}', [MyOrdersController::class, 'show'])->name('account.orders.show');
});

require __DIR__ . '/auth.php';

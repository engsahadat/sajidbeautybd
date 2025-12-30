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
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Front\BlogController as FrontBlogController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentController as FrontPaymentController;
use App\Http\Controllers\Front\MyOrdersController;
use App\Http\Controllers\Front\ProductReviewController as FrontProductReviewController;
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
        // Product Variant Routes (following category pattern)
        Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
            Route::get('/', [ProductVariantController::class, 'index'])->name('index');
            Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
            Route::post('/', [ProductVariantController::class, 'store'])->name('store');
            Route::get('/{variant}', [ProductVariantController::class, 'show'])->name('show');
            Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
            Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
            Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
        });
        // Product Attribute Routes
        Route::prefix('products/{product}/attributes')->name('products.attributes.')->group(function () {
            Route::get('/', [ProductAttributeController::class, 'index'])->name('index');
            Route::get('/create', [ProductAttributeController::class, 'create'])->name('create');
            Route::post('/', [ProductAttributeController::class, 'store'])->name('store');
            Route::get('/{attribute}', [ProductAttributeController::class, 'show'])->name('show');
            Route::get('/{attribute}/edit', [ProductAttributeController::class, 'edit'])->name('edit');
            Route::put('/{attribute}', [ProductAttributeController::class, 'update'])->name('update');
            Route::delete('/{attribute}', [ProductAttributeController::class, 'destroy'])->name('destroy');
        });
        //product Routes
        Route::controller(ProductController::class)->group(function () {
            Route::get('products/reviews', 'reviews')->name('products.reviews.index');
            Route::get('products/{product}/reviews', 'reviews')->name('products.reviews.view');
            Route::delete('products/{product}/reviews/{review}', 'deleteReview')->name('products.reviews.destroy');
        });
        
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
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        
        Route::prefix('orders/{order}')->name('orders.')->group(function () {
            Route::post('items', [OrderItemController::class, 'store'])->name('items.store');
            Route::put('items/{item}', [OrderItemController::class, 'update'])->name('items.update');
            Route::delete('items/{item}', [OrderItemController::class, 'destroy'])->name('items.destroy');
            Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
            Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
            Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
            
            // bKash specific payment actions
            Route::post('bkash/refund', [PaymentController::class, 'processBkashRefund'])->name('admin.bkash.refund');
            Route::get('bkash/refund-status', [PaymentController::class, 'checkBkashRefundStatus'])->name('admin.bkash.refund-status');
        });
        
        // bKash payment verification & search routes (not order-specific)
        Route::prefix('payments/bkash')->name('admin.payments.bkash.')->group(function () {
            Route::get('tools', [PaymentController::class, 'bkashTools'])->name('tools');
            Route::post('verify', [PaymentController::class, 'verifyBkashPayment'])->name('verify');
            Route::post('search', [PaymentController::class, 'searchBkashTransaction'])->name('search');
        });
        
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Settings
        Route::get('settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');

        // Reports
        Route::get('reports', [ReportsController::class, 'index'])->name('admin.reports.index');
        Route::get('reports/sales', [ReportsController::class, 'sales'])->name('admin.reports.sales');
        Route::get('reports/products', [ReportsController::class, 'products'])->name('admin.reports.products');
        Route::get('reports/customers', [ReportsController::class, 'customers'])->name('admin.reports.customers');
        Route::get('reports/payments', [ReportsController::class, 'payments'])->name('admin.reports.payments');
        Route::get('reports/orders', [ReportsController::class, 'orders'])->name('admin.reports.orders');
        Route::get('reports/stock-alert', [ReportsController::class, 'stockAlert'])->name('admin.reports.stock-alert');
        
        // Report exports
        Route::get('reports/sales/export', [ReportsController::class, 'exportSales'])->name('admin.reports.sales.export');
        Route::get('reports/products/export', [ReportsController::class, 'exportProducts'])->name('admin.reports.products.export');
        Route::get('reports/customers/export', [ReportsController::class, 'exportCustomers'])->name('admin.reports.customers.export');
        Route::get('reports/payments/export', [ReportsController::class, 'exportPayments'])->name('admin.reports.payments.export');
        Route::get('reports/orders/export', [ReportsController::class, 'exportOrders'])->name('admin.reports.orders.export');
        Route::get('reports/stock-alert/export', [ReportsController::class, 'exportStockAlert'])->name('admin.reports.stock-alert.export');

        // Social Media Routes
        Route::get('social-media/settings', [SocialMediaController::class, 'settings'])->name('admin.social-media.settings');
        Route::post('social-media/settings', [SocialMediaController::class, 'updateSettings'])->name('admin.social-media.settings.update');
        Route::get('social-media/connect-pages', [SocialMediaController::class, 'connectPages'])->name('admin.social-media.connect-pages');
        Route::get('social-media/connect/{platform}', [SocialMediaController::class, 'initiateConnection'])->name('admin.social-media.connect');
        Route::get('social-media/callback/{platform}', [SocialMediaController::class, 'handleCallback'])->name('admin.social-media.callback');
        Route::post('social-media/fetch-pages/{platform}', [SocialMediaController::class, 'fetchPages'])->name('admin.social-media.fetch-pages');
        Route::post('social-media/connect-page-manually', [SocialMediaController::class, 'connectPageManually'])->name('admin.social-media.connect-page-manually');
        Route::delete('social-media/pages/{page}', [SocialMediaController::class, 'disconnectPage'])->name('admin.social-media.disconnect');
        Route::get('social-media/products', [SocialMediaController::class, 'products'])->name('admin.social-media.products');
        Route::post('social-media/share', [SocialMediaController::class, 'shareProduct'])->name('admin.social-media.share');
        Route::get('social-media/posts', [SocialMediaController::class, 'posts'])->name('admin.social-media.posts');
        Route::post('social-media/posts/{post}/analytics', [SocialMediaController::class, 'refreshAnalytics'])->name('admin.social-media.posts.analytics');
    });
});



/**
 * client (front-end). Prevent admins from visiting these routes.
 */
Route::middleware('front')->controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/all-category', 'allCategory')->name('home.all-category');
    Route::get('/all-products', 'allProducts')->name('home.all-products');
    Route::get('/category/{id}', 'category')->name('home.category');
    Route::get('/all-brand', 'allBrand')->name('home.all-brand');
    Route::get('/brand/{id}', 'brand')->name('home.brand');
    Route::get('/discount', 'discount')->name('home.discount');
    Route::get('/product-details/{id}', 'productDetails')->name('home.product.details');
    Route::get('/about-us', 'aboutUs')->name('home.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('home.contactUs');
    Route::post('/contact-us', 'submitContact')->name('home.contact.submit');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('home.terms-and-conditions');
    Route::get('/privacy-policy', 'privacyPolicy')->name('home.privacy-policy');
    Route::get('/refund-policy', 'refundPolicy')->name('home.refund-policy');
    Route::get('/delivery-policy', 'deliveryPolicy')->name('home.delivery-policy');
});
Route::controller(FrontBlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('front.blog.index');
    Route::get('/blogs/{id}', 'show')->name('front.blog.show');
});

// Product reviews (front-end)
Route::middleware(['front','auth'])->group(function () {
    Route::post('/product-details/{product}/reviews', [FrontProductReviewController::class, 'store'])->name('front.reviews.store');
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
    Route::get('/wishlist', 'wishlist')->name('wishlist.index');
    Route::get('/wishlist/count', 'getWishlistCount')->name('wishlist.count');
    Route::get('/wishlist/items', 'getWishlistItems')->name('get.wishlist.items');

    // Compare routes
    Route::post('/cart/compare/toggle', 'toggleCompare')->name('cart.toggleCompare');
    Route::get('/compare', 'compare')->name('compare.index');
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

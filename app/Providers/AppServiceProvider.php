<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Compare;
use App\Models\ShoppingCart;
use App\Models\WishlistItem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        View::composer(['front-end.components.header','front-end.components.footer'], function ($view) {
            $categories = Cache::remember('layout_categories', now()->addMinutes(30), function () {
                try {
                    return Category::query()
                        ->where('is_active', 1)
                        ->active()
                        ->orderBy('sort_order')
                        ->limit(8)
                        ->get(['id','name','slug']);
                } catch (\Throwable $e) {
                    return collect();
                }
            });
            
            $brands = Cache::remember('layout_brands', now()->addMinutes(30), function () {
                try {
                    return Brand::query()
                        ->where('is_active', 1)
                        ->where('status', 'active')
                        ->orderBy('sort_order')
                        ->limit(9)
                        ->get(['id','name','slug']);
                } catch (\Throwable $e) {
                    return collect();
                }
            });
            $cartCount = 0;
            $wishlistCount = 0;
            $compareCount = 0;
            
            if (Auth::check()) {
                try {
                    $cart = ShoppingCart::forCurrent()->with('items')->first();
                    $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                    
                    $wishlistCount = WishlistItem::where('user_id', Auth::id())->count();
                    $compareCount = Compare::where('user_id', Auth::id())->count();
                } catch (\Throwable $e) {
                    $cartCount = 0;
                    $wishlistCount = 0;
                    $compareCount = 0;
                }
            }
            $view->with('categories', $categories);
            $view->with('brands', $brands);
            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
            $view->with('compareCount', $compareCount);
        });
    }
}

<?php

use App\Services\MetaPixelService;

if (!function_exists('meta_pixel')) {
    /**
     * Get Meta Pixel service instance
     *
     * @return \App\Services\MetaPixelService
     */
    function meta_pixel(): MetaPixelService
    {
        return app(MetaPixelService::class);
    }
}

if (!function_exists('track_pixel_event')) {
    /**
     * Quick helper to track Meta Pixel event (server-side)
     *
     * @param string $eventName
     * @param array $eventData
     * @param array $userData
     * @return bool
     */
    function track_pixel_event(string $eventName, array $eventData = [], array $userData = []): bool
    {
        return meta_pixel()->sendServerEvent($eventName, $eventData, $userData);
    }
}

if (!function_exists('pixel_view_content')) {
    /**
     * Track ViewContent event (returns script for browser-side)
     *
     * @param mixed $product Product model or array
     * @return string
     */
    function pixel_view_content($product): string
    {
        $data = is_array($product) ? $product : meta_pixel()->formatProductData($product);
        return meta_pixel()->getViewContentScript($data);
    }
}

if (!function_exists('pixel_add_to_cart')) {
    /**
     * Track AddToCart event (returns script for browser-side)
     *
     * @param mixed $product Product model or array
     * @return string
     */
    function pixel_add_to_cart($product): string
    {
        $data = is_array($product) ? $product : meta_pixel()->formatProductData($product);
        return meta_pixel()->getAddToCartScript($data);
    }
}

if (!function_exists('pixel_purchase')) {
    /**
     * Track Purchase event (returns script for browser-side)
     *
     * @param mixed $order Order model or array
     * @return string
     */
    function pixel_purchase($order): string
    {
        $data = is_array($order) ? $order : meta_pixel()->formatOrderData($order);
        return meta_pixel()->getPurchaseScript($data);
    }
}

if (!function_exists('pixel_initiate_checkout')) {
    /**
     * Track InitiateCheckout event (returns script for browser-side)
     *
     * @param mixed $cartItems Collection or array
     * @return string
     */
    function pixel_initiate_checkout($cartItems): string
    {
        $data = is_array($cartItems) ? $cartItems : meta_pixel()->formatCartData($cartItems);
        return meta_pixel()->getInitiateCheckoutScript($data);
    }
}

if (!function_exists('pixel_search')) {
    /**
     * Track Search event (returns script for browser-side)
     *
     * @param string $searchQuery
     * @return string
     */
    function pixel_search(string $searchQuery): string
    {
        return meta_pixel()->getSearchScript($searchQuery);
    }
}

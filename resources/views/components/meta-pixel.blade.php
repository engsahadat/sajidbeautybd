{{-- 
    Meta (Facebook) Pixel Component
    
    This component renders the Meta Pixel base code and optional event tracking.
    
    Usage:
    @include('components.meta-pixel')                    // Base pixel only
    @include('components.meta-pixel', ['event' => 'ViewContent', 'data' => [...]])  // With event
--}}

@php
    $metaPixel = app(\App\Services\MetaPixelService::class);
@endphp

@if($metaPixel->isEnabled())
    {{-- Base Meta Pixel Script --}}
    {!! $metaPixel->getPixelScript() !!}
    
    {{-- Optional Event Tracking --}}
    @if(isset($event) && isset($data))
        @switch($event)
            @case('ViewContent')
                {!! $metaPixel->getViewContentScript($data) !!}
                @break
            
            @case('AddToCart')
                {!! $metaPixel->getAddToCartScript($data) !!}
                @break
            
            @case('InitiateCheckout')
                {!! $metaPixel->getInitiateCheckoutScript($data) !!}
                @break
            
            @case('Purchase')
                {!! $metaPixel->getPurchaseScript($data) !!}
                @break
            
            @case('Search')
                {!! $metaPixel->getSearchScript($data['search_string'] ?? '') !!}
                @break
        @endswitch
    @endif
@endif

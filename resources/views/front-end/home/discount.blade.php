@extends('front-end.layouts.app')
@section('title', 'Discount')
@section('content')
<div class="py-4 border-bottom bg-light">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-2 small">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('Discount') }}</li>
			</ol>
		</nav>
	</div>
</div>
<!-- section start -->
<section class="blog-page section-b-space ratio2_3">
    <div class="container">
        <div class="row g-sm-4 g-3">
            <div class="col-lg-12 col-xl-12 col-xxl-12 no-sidebar">
                <div class="row g-6">
                    @forelse ($discounts as $discount)
                        <div class="col-sm-6 col-xxl-6">
                            <div class="blog-box sticky-blog-box">
                                <div class="blog-image">
                                    <img class="rounded" src="{{ $discount->image_url }}" alt="The Art of Stylish Furnishing">
                                </div>
                                <div class="blog-contain">
                                    <h3 class="text-center">{{ ucfirst($discount->title) }}</h3>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h3 class="text-center">Discount not Found..!</h3>
                    @endforelse
                </div>  
            </div>
        </div>
    </div>
</section>
<!-- Section ends -->
@endsection
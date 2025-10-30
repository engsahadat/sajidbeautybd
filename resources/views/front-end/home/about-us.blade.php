@extends('front-end.layouts.app')
@section('title', 'About Us')
@section('content')
<div class="py-4 border-bottom bg-light">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-2 small">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
				<li class="breadcrumb-item active" aria-current="page">{{ __('About Us') }}</li>
			</ol>
		</nav>
	<h1 class="h3 mb-0">{{ __('About') }} {{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}</h1>
	<p class="text-muted mt-2 mb-0">{{ \App\Models\Setting::get('about_intro', __('Your trusted destination for authentic beauty & cosmetic essentials in Bangladesh.')) }}</p>
	</div>
</div>

<section class="py-5">
	<div class="container">
		<div class="row align-items-center g-4">
			<div class="col-lg-6">
				<picture>
					<img src="{{ asset('images/shop-front.jpg') }}" class="img-fluid rounded shadow-sm" alt="Inside Sajid Beauty BD store" onerror="this.src='{{ asset('images/default-image.png') }}'">
				</picture>
			</div>
			<div class="col-lg-6">
				<h2 class="h4">{{ __('Who We Are') }}</h2>
				<p>{{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }} {{ __('is a customer‑centric beauty & cosmetics store located at') }} <strong>{{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205') }}</strong>. {{ __('We curate genuine, high quality skincare, haircare, fragrance and makeup products sourced from reliable, authentic suppliers.') }}</p>
				<p>{{ __('We bridge the gap between global beauty trends and the local market—offering fair pricing, product education, and a safe space to explore your personal care needs.') }}</p>
				<ul class="list-unstyled mb-4">
					<li class="d-flex mb-2"><i class="ri-checkbox-circle-line text-success me-2"></i><span>100% Authentic Products</span></li>
					<li class="d-flex mb-2"><i class="ri-checkbox-circle-line text-success me-2"></i><span>Expert Advice & Personalized Support</span></li>
					<li class="d-flex mb-2"><i class="ri-checkbox-circle-line text-success me-2"></i><span>Fast Delivery & Secure Packaging</span></li>
					<li class="d-flex mb-2"><i class="ri-checkbox-circle-line text-success me-2"></i><span>Customer Satisfaction First</span></li>
				</ul>
				<div class="row g-3">
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<h6 class="fw-bold mb-2"><i class="ri-time-line me-1 text-primary"></i>{{ __('Store Hours') }}</h6>
							<ul class="list-unstyled small mb-0">
								<li class="d-flex justify-content-between"><span>Sunday</span><span>{{ \App\Models\Setting::get('hours_sunday', '10:30 AM – 8:30 PM') }}</span></li>
								<li class="d-flex justify-content-between"><span>Monday</span><span>{{ \App\Models\Setting::get('hours_monday', '10:30 AM – 8:30 PM') }}</span></li>
								<li style="color: red;" class="d-flex justify-content-between"><span>Tuesday</span><span>{{ \App\Models\Setting::get('hours_tuesday', 'Closed') }}</span></li>
								<li class="d-flex justify-content-between"><span>Wednesday</span><span>{{ \App\Models\Setting::get('hours_wednesday', '10:30 AM – 8:30 PM') }}</span></li>
								<li class="d-flex justify-content-between"><span>Thursday</span><span>{{ \App\Models\Setting::get('hours_thursday', '10:30 AM – 8:30 PM') }}</span></li>
								<li class="d-flex justify-content-between"><span>Friday</span><span>{{ \App\Models\Setting::get('hours_friday', '10:30 AM – 9:00 PM') }}</span></li>
								<li class="d-flex justify-content-between"><span>Saturday</span><span>{{ \App\Models\Setting::get('hours_saturday', '10:30 AM – 9:00 PM') }}</span></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-3 h-100">
							<h6 class="fw-bold mb-2"><i class="ri-truck-line me-1 text-primary"></i>{{ __('Delivery') }}</h6>
							<p class="small mb-0">{{ __('Delivery within') }} <strong>{{ (int) (\App\Models\Setting::get('delivery_days', 3)) }}</strong> {{ __('day(s) across Bangladesh') }}</p>
						</div>
					</div>
				</div>
				<a href="{{ route('home.contactUs') }}" class="btn btn-primary btn-sm">Contact Us</a>
			</div>
		</div>
	</div>
</section>

<section class="py-5 bg-light">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-4">
				<h3 class="h5">{{ __('Our Mission') }}</h3>
				<p class="mb-0">{{ __('To empower individuals through safe, authentic and accessible beauty products—helping every customer feel confident, informed and valued.') }}</p>
			</div>
			<div class="col-lg-4">
				<h3 class="h5">{{ __('Our Vision') }}</h3>
				<p class="mb-0">{{ __('To become Bangladesh\'s most trusted and educational beauty destination—online and offline—where authenticity and service set the standard.') }}</p>
			</div>
			<div class="col-lg-4">
				<h3 class="h5">{{ __('What Makes Us Different') }}</h3>
				<p class="mb-0">{{ __('Transparent sourcing, curated brands, genuine product guarantees, and a commitment to ethical business practices and customer care.') }}</p>
			</div>
		</div>
	</div>
</section>

<section class="py-5">
	<div class="container">
		<div class="row mb-4">
			<div class="col-lg-8">
				<h2 class="h4 mb-3">{{ __('Our Core Values') }}</h2>
				<p class="text-muted mb-0">{{ __('These values guide every interaction—from sourcing to after‑sales support.') }}</p>
			</div>
		</div>
		<div class="row g-4">
			<div class="col-sm-6 col-lg-3">
				<div class="h-100 p-3 border rounded">
					<h6 class="text-uppercase small fw-bold mb-2">{{ __('Authenticity') }}</h6>
					<p class="small mb-0">{{ __('Guaranteed original products. No compromises.') }}</p>
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				<div class="h-100 p-3 border rounded">
					<h6 class="text-uppercase small fw-bold mb-2">{{ __('Integrity') }}</h6>
					<p class="small mb-0">{{ __('Ethical sourcing & transparent business practices.') }}</p>
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				<div class="h-100 p-3 border rounded">
					<h6 class="text-uppercase small fw-bold mb-2">{{ __('Service') }}</h6>
					<p class="small mb-0">{{ __('We listen first and tailor solutions to your needs.') }}</p>
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				<div class="h-100 p-3 border rounded">
					<h6 class="text-uppercase small fw-bold mb-2">{{ __('Education') }}</h6>
					<p class="small mb-0">{{ __('We help you make informed product decisions.') }}</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="py-5 bg-light">
	<div class="container">
		<div class="row text-center">
			<div class="col-6 col-md-3 mb-4 mb-md-0">
				<h3 class="mb-0">5K+</h3>
				<p class="text-muted small mb-0">{{ __('Happy Customers') }}</p>
			</div>
			<div class="col-6 col-md-3 mb-4 mb-md-0">
				<h3 class="mb-0">1K+</h3>
				<p class="text-muted small mb-0">{{ __('Products Curated') }}</p>
			</div>
			<div class="col-6 col-md-3 mb-4 mb-md-0">
				<h3 class="mb-0">50+</h3>
				<p class="text-muted small mb-0">{{ __('Brands Available') }}</p>
			</div>
			<div class="col-6 col-md-3 mb-0">
				<h3 class="mb-0">4.9★</h3>
				<p class="text-muted small mb-0">{{ __('Customer Rating') }}</p>
			</div>
		</div>
	</div>
</section>

<section class="py-5">
	<div class="container">
		<div class="row align-items-center g-4">
			<div class="col-lg-5 order-lg-2">
				<picture>
					<img src="{{ asset('images/shop-front.jpg') }}" class="img-fluid rounded shadow-sm" alt="Sajid Beauty BD storefront" onerror="this.src='{{ asset('images/default-image.png') }}'">
				</picture>
			</div>
			<div class="col-lg-7 order-lg-1">
				<h2 class="h4">{{ __('Visit Our Store') }}</h2>
				<p class="mb-1"><i class="ri-map-pin-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205.') }}</p>
				<p class="mb-1"><i class="ri-phone-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</p>
				<p class="mb-3"><i class="ri-mail-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</p>
				<p>{{ __('We welcome walk‑ins and consultations. Our team is trained to help match products to your specific skin, hair and lifestyle needs.') }}</p>
				<a href="https://maps.google.com/?q=Shimanto+Shambar+Shopping+Mall+Dhaka" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">{{ __('Get Directions') }}</a>
			</div>
		</div>
	</div>
</section>

<section class="py-5 bg-primary text-white">
	<div class="container">
		<div class="row align-items-center g-3">
			<div class="col-lg-8">
				<h2 class="h4 mb-2">{{ __('Join Our Beauty Community') }}</h2>
				<p class="mb-0">{{ __('Follow us for tips, launches & exclusive offers. Your journey to authentic beauty starts here.') }}</p>
			</div>
			<div class="col-lg-4 text-lg-end">
				<a href="{{ route('home.contactUs') }}" class="btn btn-light btn-sm me-2 mb-2">{{ __('Contact Support') }}</a>
				<a href="{{ route('front.blog.index') }}" class="btn btn-outline-light btn-sm mb-2">{{ __('Read Our Blog') }}</a>
			</div>
		</div>
	</div>
</section>
@endsection
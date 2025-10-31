@extends('front-end.layouts.app')
@section('title', 'Delivery Policy')
@section('content')

<div class="py-6 bg-gray-50 border-b">
	<div class="container mx-auto px-4">
		<div class="max-w-4xl mx-auto text-center">
			<h1 class="text-2xl md:text-3xl font-semibold text-gray-800">{{ __('Delivery Policy') }}</h1>
			<p class="mt-2 text-sm text-gray-500">{{ \App\Models\Setting::get('site_tagline', 'Fast. Reliable. Trusted.') }}</p>
		</div>
	</div>
</div>

<section class="py-10">
	<div class="container mx-auto px-4">
		<div class="max-w-5xl mx-auto grid lg:grid-cols-4 gap-8">
			<!-- TOC -->
			<aside class="lg:col-span-1 sticky top-20 hidden lg:block">
				<div class="bg-white border rounded-lg p-4 shadow-sm">
					<h3 class="text-sm font-semibold text-gray-700 mb-3">On this page</h3>
					<nav class="text-sm space-y-2">
						<a href="#processing" class="block text-gray-600 hover:text-indigo-600">Order Processing Time</a>
						<a href="#methods" class="block text-gray-600 hover:text-indigo-600">Delivery Methods</a>
						<a href="#address" class="block text-gray-600 hover:text-indigo-600">Shipping Addresses</a>
						<a href="#restrictions" class="block text-gray-600 hover:text-indigo-600">Shipping Restrictions</a>
						<a href="#confirmation" class="block text-gray-600 hover:text-indigo-600">Delivery Confirmation</a>
						<a href="#delays" class="block text-gray-600 hover:text-indigo-600">Shipping Delays</a>
						<a href="#returns" class="block text-gray-600 hover:text-indigo-600">Returns Due to Non-Delivery</a>
						<a href="#contact" class="block text-gray-600 hover:text-indigo-600">Contact Information</a>
					</nav>
				</div>
			</aside>

			<!-- Content -->
			<div class="lg:col-span-3">
				<div class="bg-white rounded-lg shadow-md overflow-hidden">
					<div class="p-6 prose prose-slate max-w-none">
						<p class="lead mt-3">Thank you for choosing <strong>{{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}</strong>. This Delivery Policy outlines the terms and conditions for delivery of products and services purchased through our platform. By placing an order with us, you agree to the policies below.</p>

						<h3 id="processing">Order Processing Time</h3>
						<p>Orders are typically processed within <strong>{{ \App\Models\Setting::get('default_processing_time', '1-4') }}</strong> business days from the date of purchase. Processing times may vary depending on the nature of the product or service and stock availability.</p>

						<h3 id="methods">Delivery Methods</h3>
						<p>We offer various delivery methods depending on the product or service, including:</p>
						<ul>
							<li>Standard shipping</li>
							<li>Express shipping</li>
							<li>Digital delivery (for downloadable products or services)</li>
						</ul>

						<h3 class="mt-3" id="address">Shipping Addresses</h3>
						<p>It is the customer's responsibility to provide accurate and complete shipping information. We are not responsible for deliveries to incorrect addresses provided by the customer. Please double-check shipping details before completing checkout.</p>

						<h3 id="restrictions">Shipping Restrictions</h3>
						<p>Some products or services may have shipping restrictions based on geographic location, customs, or local regulations. Customers must check these restrictions before placing an order. If an item cannot be shipped to your location, we will notify you and offer alternatives where possible.</p>

						<h3 id="confirmation">Delivery Confirmation</h3>
						<p>Upon successful delivery, you will receive a confirmation email with relevant details, including tracking information when applicable. Digital products or services will be delivered via email or through your account on our platform.</p>

						<h3 id="delays">Shipping Delays</h3>
						<p>While we strive to meet estimated delivery timelines, unforeseen circumstances (weather, customs, carrier issues, public holidays, etc.) may cause delays. We appreciate your patience and will communicate any significant delays via email.</p>

						<h3 id="returns">Returns Due to Non-Delivery</h3>
						<p>If a product is returned to us due to non-delivery (for example, incorrect address provided), the customer will be responsible for any additional shipping charges. If an item is lost in transit, we will work with the carrier to investigate and, where appropriate, issue a refund or replacement following our Returns Policy.</p>

						<h3 id="contact">Contact Information</h3>
						<p>If you have questions or concerns about your order or this Delivery Policy, please contact our customer service team:</p>
						<ul>
							<li><strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</li>
							<li><strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
							<li><strong>Address:</strong> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205') }}</li>
						</ul>

						<p class="text-sm text-gray-500">This Delivery Policy was last updated on {{ now()->format('F j, Y') }}.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
@extends('front-end.layouts.app')
@section('title', 'Refund Policy')
@section('content')

<div class="py-6 bg-gray-50 border-b">
	<div class="container mx-auto px-4">
		<div class="max-w-4xl mx-auto text-center">
			<h1 class="text-2xl md:text-3xl font-semibold text-gray-800">{{ __('Refund Policy') }}</h1>
			<p class="mt-2 text-sm text-gray-500">{{ \App\Models\Setting::get('site_tagline', 'Your satisfaction is our priority.') }}</p>
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
						<a href="#eligibility" class="block text-gray-600 hover:text-indigo-600">Eligibility for Refund</a>
						<a href="#process" class="block text-gray-600 hover:text-indigo-600">Refund Process</a>
						<a href="#late" class="block text-gray-600 hover:text-indigo-600">Late or Missing Refunds</a>
						<a href="#changes" class="block text-gray-600 hover:text-indigo-600">Changes to this Policy</a>
						<a href="#contact" class="block text-gray-600 hover:text-indigo-600">Contact</a>
					</nav>
				</div>
			</aside>

			<!-- Content -->
			<div class="lg:col-span-3">
				<div class="bg-white rounded-lg shadow-md overflow-hidden">
					<div class="p-6 prose prose-slate max-w-none">
						<p class="lead">Thank you for shopping with <strong>{{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}</strong>. We want you to be satisfied with your purchase. Please read our refund policy carefully to understand your rights and responsibilities.</p>

						<h3 id="eligibility">1. Eligibility for Refund</h3>
						<p>To be eligible for a refund, items must meet the following conditions:</p>
						<ul>
							<li>The item must be unused and in the same condition that you received it.</li>
							<li>The item must be in its original packaging.</li>
							<li>Items that are damaged, used, or not in their original condition may not be eligible for a refund.</li>
							<li>Refund requests must be made within <strong>{{ \App\Models\Setting::get('refund_days', '7') }}</strong> days of receiving the product unless otherwise indicated on the product page.</li>
						</ul>

						<h3 id="process" class="mt-3">2. Refund Process</h3>
						<p>To initiate a refund, contact our customer service team with your order number and details of the issue. You can reach us at:</p>
						<ul>
							<li><strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</li>
							<li><strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
						</ul>
						<p>Our team will review your request and notify you of approval or rejection. If approved, refunds will be processed to your original method of payment within <strong>{{ \App\Models\Setting::get('refund_processing_days', '5-7') }}</strong> business days, depending on your payment provider.</p>

						<h3 id="late">3. Late or Missing Refunds</h3>
						<p>If you haven’t received a refund within the specified timeframe, please:</p>
						<ol>
							<li>Check your bank account or payment method.</li>
							<li>Contact your payment provider (bank or MFS) — sometimes refunds take additional processing time.</li>
							<li>If you still haven’t received the refund, contact us at <strong>{{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</strong> and provide your order number and refund details.</li>
						</ol>

						<h3 id="changes">4. Changes to this Refund Policy</h3>
						<p>We reserve the right to modify this refund policy at any time. Changes and clarifications will take effect immediately upon posting on our website. Your continued use of the website after changes are posted constitutes acceptance of the revised policy.</p>

						<h3 id="contact">Contact</h3>
						<p>If you have any questions about this refund policy, please contact our customer service team:</p>
						<ul>
							<li><strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</li>
							<li><strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
							<li><strong>Address:</strong> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205') }}</li>
						</ul>

						<p class="text-sm text-gray-500">This Refund Policy was last updated on {{ now()->format('F j, Y') }}.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
@extends('front-end.layouts.app')
@section('title', 'Privacy Policy')
@section('content')

<x-policy-layout :title="__('Privacy Policy')" :tagline="\App\Models\Setting::get('site_tagline', 'We respect your privacy.')">
	<x-slot name="toc">
		<h3 class="text-sm font-semibold text-gray-700 mb-3">On this page</h3>
		<nav class="text-sm space-y-2">
			<a href="#info-collect" class="block text-gray-600 hover:text-indigo-600">Information We Collect</a>
			<a href="#when-collect" class="block text-gray-600 hover:text-indigo-600">When We Collect</a>
			<a href="#how-use" class="block text-gray-600 hover:text-indigo-600">How We Use</a>
			<a href="#share" class="block text-gray-600 hover:text-indigo-600">Information Sharing</a>
			<a href="#security" class="block text-gray-600 hover:text-indigo-600">Data Security</a>
			<a href="#rights" class="block text-gray-600 hover:text-indigo-600">Your Rights</a>
			<a href="#cookies" class="block text-gray-600 hover:text-indigo-600">Cookies</a>
			<a href="#changes" class="block text-gray-600 hover:text-indigo-600">Changes</a>
			<a href="#contact" class="block text-gray-600 hover:text-indigo-600">Contact</a>
		</nav>
	</x-slot>

	<p class="lead mt-3">This Privacy Policy describes how <strong>{{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}</strong> collects, uses, shares, and protects information obtained from users of our e‑commerce website.</p>

	<h3 id="info-collect">Information We Collect</h3>
	<p>We may collect personal information such as:</p>
	<ul>
		<li>Name</li>
		<li>Contact information including email address, mailing address, and phone number</li>
		<li>Payment details necessary to process payments</li>
		<li>Demographic information</li>
		<li>Preferences and interests</li>
	</ul>

	<h3 id="when-collect">When We Collect Information</h3>
	<p>We collect this information when you:</p>
	<ul>
		<li>Make a purchase</li>
		<li>Create an account</li>
		<li>Sign up for newsletters or promotions</li>
		<li>Contact our customer support</li>
		<li>Interact with our website through cookies and similar technologies</li>
	</ul>

	<h3 id="how-use">How We Use Your Information</h3>
	<p>We use the collected information for the following purposes:</p>
	<ul>
		<li>To process your orders and payments</li>
		<li>To personalize your shopping experience</li>
		<li>To improve our products and services</li>
		<li>To send promotional emails and newsletters (only when you opt in)</li>
		<li>To communicate with you regarding your orders, inquiries, or account-related matters</li>
	</ul>

	<h3 id="share">Information Sharing</h3>
	<p>We may share your information with:</p>
	<ol>
		<li>Service providers who assist us in operating our website or conducting our business (for example, payment processors, shipping partners, hosting and analytics providers)</li>
		<li>Third-party partners for marketing or promotional purposes where you have consented</li>
		<li>Law enforcement or government agencies when required by law or to protect our rights</li>
	</ol>

	<h3 id="security">Data Security</h3>
	<p>We implement appropriate technical and organizational security measures to protect your information. However, no method of transmission over the internet or electronic storage is entirely secure. If you suspect a breach, contact us right away.</p>

	<h3 id="rights">Your Rights</h3>
	<p>You have the right to:</p>
	<ul>
		<li>Access, update, or delete your personal information</li>
		<li>Object to the processing of your data</li>
		<li>Opt-out of receiving marketing communications</li>
	</ul>

	<h3 id="cookies">Cookies</h3>
	<p>Our website uses cookies and similar technologies to enhance your browsing experience. You can manage your cookie preferences through your browser settings.</p>

	<h3 id="changes">Changes to This Privacy Policy</h3>
	<p>We reserve the right to update or modify this Privacy Policy at any time. Any changes will be effective immediately upon posting the revised policy on our website. We recommend that you review this page regularly.</p>

	<h3 id="contact">Contact Us</h3>
	<p>If you have any questions, concerns, or requests regarding this Privacy Policy or the handling of your personal information, please contact us:</p>
	<ul>
		<li><strong>Address:</strong> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205') }}</li>
		<li><strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
		<li><strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</li>
	</ul>

	<p class="text-sm text-gray-500">This Privacy Policy was last updated on {{ now()->format('F j, Y') }}.</p>

</x-policy-layout>
@endsection
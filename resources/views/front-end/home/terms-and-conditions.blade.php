@extends('front-end.layouts.app')
@section('title', 'Terms and Conditions')
@section('content')

<div class="py-6 bg-gray-50 border-b">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">{{ __('Terms & Conditions') }}</h1>
            <p class="mt-2 text-sm text-gray-500">{{ \App\Models\Setting::get('site_tagline', 'Please read these terms and conditions carefully before using our website.') }}</p>
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
                        <a href="#intro" class="block text-gray-600 hover:text-indigo-600">Introduction</a>
                        <a href="#orders" class="block text-gray-600 hover:text-indigo-600">Orders & Pricing</a>
                        <a href="#payment" class="block text-gray-600 hover:text-indigo-600">Payment</a>
                        <a href="#shipping" class="block text-gray-600 hover:text-indigo-600">Shipping & Delivery</a>
                        <a href="#returns" class="block text-gray-600 hover:text-indigo-600">Returns & Refunds</a>
                        <a href="#ip" class="block text-gray-600 hover:text-indigo-600">Intellectual Property</a>
                        <a href="#liability" class="block text-gray-600 hover:text-indigo-600">Limitation of Liability</a>
                        <a href="#accounts" class="block text-gray-600 hover:text-indigo-600">Customer Accounts</a>
                        <a href="#privacy" class="block text-gray-600 hover:text-indigo-600">Privacy</a>
                        <a href="#law" class="block text-gray-600 hover:text-indigo-600">Governing Law</a>
                        <a href="#changes" class="block text-gray-600 hover:text-indigo-600">Changes</a>
                        <a href="#contact" class="block text-gray-600 hover:text-indigo-600">Contact</a>
                    </nav>
                </div>
            </aside>

            <!-- Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 prose prose-slate max-w-none">
                        <h3 class="mt-3" id="intro">1. Introduction</h3>
                        <p>Welcome to <strong>{{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}</strong>. By accessing or using our website and services, you agree to be bound by these Terms &amp; Conditions. If you do not agree with any part of these terms, please do not use our site.</p>

                        <h3 id="orders">2. Orders &amp; Pricing</h3>
                        <p>All prices listed on the website are in BDT and are subject to change without prior notice. We make every effort to ensure the prices and product information on the site are accurate, but errors may occur. In case of a pricing error, we will notify you and give you the option to reconfirm your order at the correct price or cancel it.</p>

                        <h3 id="payment">3. Payment</h3>
                        <p>Payments are processed via our supported payment gateways. By placing an order you authorize us to charge your chosen payment method for the amount of your order including any applicable taxes and shipping charges. If a payment cannot be processed, we will notify you and your order will not be confirmed.</p>

                        <h3 id="shipping">4. Shipping &amp; Delivery</h3>
                        <p>Delivery times are estimates only and commence from the date of shipping. {{ \App\Models\Setting::get('site_name', 'Our store') }} aims to deliver within the timeframe indicated on the product page or at checkout. Delivery may be affected by factors outside our control.</p>

                        <h3 id="returns">5. Returns, Refunds &amp; Cancellations</h3>
                        <p>Our returns and refund policy is available on the Returns page. Generally, you may request a return for eligible items within the timeframe specified in the product listing, subject to the item being unused and in original condition. Refunds are issued after inspection and processing.</p>

                        <h3 id="ip">6. Intellectual Property</h3>
                        <p>All content on this site, including text, graphics, logos, images, and software, is the property of {{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }} or its content suppliers and is protected by intellectual property laws. You may not reproduce or distribute any content from this site without prior written permission.</p>

                        <h3 id="liability">7. Limitation of Liability</h3>
                        <p>To the fullest extent permitted by applicable law, {{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }} shall not be liable for any indirect, incidental, special or consequential damages arising out of or in connection with the use of the site or products purchased through the site.</p>

                        <h3 id="accounts">8. Customer Accounts</h3>
                        <p>If you create an account on our site, you are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately if you suspect any unauthorized use of your account.</p>

                        <h3 id="privacy">9. Privacy</h3>
                        <p>Our <a href="{{ route('home.privacy-policy') }}">Privacy Policy</a> explains how we collect, use and protect your personal information. By using the site you consent to such processing and you warrant that all data provided by you is accurate.</p>

                        <h3 id="law">10. Governing Law</h3>
                        <p>These terms are governed by and construed in accordance with the laws of Bangladesh. Any disputes arising in connection with these terms shall be subject to the exclusive jurisdiction of the courts of Bangladesh.</p>

                        <h3 id="changes">11. Changes to Terms</h3>
                        <p>We may update these Terms &amp; Conditions from time to time. Changes will be effective when posted on this page with an updated effective date. Continued use of the site after changes are posted constitutes your acceptance of the new terms.</p>

                        <h3 id="contact">12. Contact Us</h3>
                        <p>If you have questions about these Terms &amp; Conditions, please contact us:</p>
                        <ul>
                            <li><strong>Address:</strong> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205') }}</li>
                            <li><strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
                            <li><strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</li>
                        </ul>

                        <p class="text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

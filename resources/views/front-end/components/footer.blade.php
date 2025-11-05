<footer class="footer-style-1">
    <section class="section-b-space darken-layout">
        <div class="container">
            <div class="row footer-theme g-md-5 g-2">
                <div class="col-xl-3 col-lg-5 col-md-6 sub-title">
                    <div>
                        <div class="footer-logo">
                            <a href="{{ url('/') }}"><img style="height:80px; width: 90px;" alt="logo" class="img-fluid" src="{{ asset('images/logo-white.svg') }}"></a>
                        </div>
                        <p>{{ \App\Models\Setting::get('site_tagline', 'Sajid Beauty BD is a beauty and cosmetics Shop') }}</p>
                        <ul class="contact-list">
                            <li><i class="ri-map-pin-line"></i>{{ \App\Models\Setting::get('contact_address', 'located at Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205.') }} </li>
                            <li><i class="ri-phone-line"></i>Call Us: {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }} </li>
                            <li><i class="ri-mail-line"></i>Email Us: {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }} </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Categories</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="{{ route('home.category', $category->id) }}" class="text-content">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl col-lg-3 col-md-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Useful Links</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a class="text-content" href="{{ route('home') }}">{{ __('Home') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.all-brand') }}">{{ __('Brands') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.all-category') }}">{{ __('Category') }}</a></li>
                                <li><a class="text-content" href="{{ route('front.blog.index') }}">{{ __('Blogs') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.discount') }}">{{ __('Offers') }}</a></li>
                                <li><a class="text-content" href="{{ route('wishlist.index') }}">{{ __('Wishlist') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Help Center</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                <li><a class="text-content" href="{{ route('profile.edit') }}">{{ __('My Account') }}</a></li>
                                <li><a class="text-content" href="{{ route('account.orders.index') }}">{{ __('My Orders') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.aboutUs') }}">{{ __('About Us') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.contactUs') }}">{{ __('Contact Us') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.terms-and-conditions') }}">{{ __('Terms & Conditions') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.privacy-policy') }}">{{ __('Privacy Policy') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.refund-policy') }}">{{ __('Refund Policy') }}</a></li>
                                <li><a class="text-content" href="{{ route('home.delivery-policy') }}">{{ __('Delivery Policy') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Follow Us</h4>
                        </div>
                        <div class="footer-content">
                            <p class="mb-cls-content">Never Miss Anything From Store By Signing Up To Our Newsletter.</p>
                            <form novalidate="" class="form-inline">
                                <div class="form-group me-sm-3 mb-2">
                                    <input type="email" class="form-control" placeholder="Enter Email Address">
                                </div>
                                <button class="btn btn-solid mb-2">Subscribe</button>
                            </form>
                            <div class="footer-social">
                                <ul>
                                    <li>
                                        <a target="_blank" href="{{ \App\Models\Setting::get('facebook_url', 'https://facebook.com/') }}">
                                            <i class="ri-facebook-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="{{ \App\Models\Setting::get('twitter_url', 'https://twitter.com/') }}">
                                            <i class="ri-twitter-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="{{ \App\Models\Setting::get('instagram_url', 'https://instagram.com/') }}">
                                            <i class="ri-instagram-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="{{ \App\Models\Setting::get('youtube_url', 'https://pinterest.com/') }}">
                                            <i class="ri-pinterest-fill"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="sub-footer dark-subfooter">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <p><i class="ri-copyright-line"></i> {{ now()->year }} {{ \App\Models\Setting::get('site_name', 'Sajid Beauty BD') }}, Develop By eng.md.sahadathossain@gmail.com</p>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="payment-card-bottom">
                        <img style="border-radius: 5px" alt="payment options" src="{{ asset('images/ssl_pament_icon.jpg') }}" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
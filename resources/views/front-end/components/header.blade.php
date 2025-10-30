<header>
    <div class="top-header">
        <div class="mobile-fix-option"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="header-contact">
                        <ul>
                            <li>Welcome to {{ \App\Models\Setting::get('site_name', 'sajidbeautybd') }}</li>
                            <li><i class="ri-phone-fill"></i>Call Us: {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu">
                    <div class="menu-left">
                        <div class="brand-logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('images/logo.svg') }}" class="img-fluid blur-up lazyload" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i></div>
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li class="mobile-box">
                                        <div class="mobile-back text-end">Menu<i class="ri-close-line"></i></div>
                                    </li>
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li>
                                        <a href="{{ route('home.all-category') }}">All Category</a>
                                        <ul>
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a href="{{ route('home.category', $category->id) }}">{{ $category->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ route('home.all-brand') }}">Brands</a>
                                        <ul>
                                            @foreach ($brands as $brand)
                                                <li>
                                                    <a href="{{ route('home.brand', $brand->id) }}">{{ $brand->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ route('home.discount') }}">Discount</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('front.blog.index') }}">blog</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home.aboutUs') }}">About Us</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('home.contactUs') }}">Contact Us</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">My Account</a>
                                        <ul class="onhover-show-div">
                                            @guest
                                                <li>
                                                    <a href="{{ route('login') }}">Login</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('register') }}">Register</a>
                                                </li>
                                            @endguest
                                            @auth
                                                <li>
                                                    <a href="{{ route('profile.edit') }}">Profile</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('account.orders.index') }}">My Orders</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="document.getElementById('logoutform').submit();">
                                                        Logout
                                                    </a>
                                                </li>

                                                <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            @endauth
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div>
                            <div class="icon-nav">
                                <ul>
                                    <li class="onhover-div mobile-search">
                                        <div data-bs-toggle="modal" data-bs-target="#searchModal">
                                            <i class="ri-search-line"></i>
                                        </div>
                                    </li>
                                    @auth
                                    <li class="onhover-div mobile-wishlist">
                                        <a href="{{ route('wishlist.index') }}" class="position-relative" title="Wishlist">
                                            <i class="ri-heart-line"></i>
                                            @if(($wishlistCount ?? 0) > 0)
                                                <span class="cart_qty_cls wishlist-count">{{ $wishlistCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="onhover-div mobile-compare">
                                        <a href="{{ route('compare.index') }}" class="position-relative" title="Compare">
                                            <i class="ri-refresh-line"></i>
                                            @if(($compareCount ?? 0) > 0)
                                                <span class="cart_qty_cls compare-count">{{ $compareCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    @endauth
                                    <li class="onhover-div mobile-setting">
                                        <div><i class="ri-equalizer-2-line"></i></div>
                                        <div class="show-div setting">
                                            <h6>language</h6>
                                            <ul>
                                                <li><a href="#!">english</a></li>
                                                <li><a href="#!">french</a></li>
                                            </ul>
                                            <h6>currency</h6>
                                            <ul class="list-inline">
                                                <li><a href="#!">euro</a></li>
                                                <li><a href="#!">rupees</a></li>
                                                <li><a href="#!">pound</a></li>
                                                <li><a href="#!">dollar</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="onhover-div mobile-cart">
                                        <a href="{{ route('cart.index') }}" class="position-relative" title="Cart">
                                            <i class="ri-shopping-cart-line"></i>
                                            <span class="cart_qty_cls cart-count">{{ $cartCount ?? 0 }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    function setWishlistCount(count) {
        try {
            const existing = document.querySelectorAll('.wishlist-count');
            if (count && parseInt(count) > 0) {
                if (existing.length) {
                    existing.forEach(el => el.textContent = count);
                    return;
                }
                const wishlistLink = document.querySelector('a[href="{{ route('wishlist.index') }}"]');
                if (wishlistLink) {
                    const span = document.createElement('span');
                    span.className = 'cart_qty_cls wishlist-count';
                    span.textContent = count;
                    wishlistLink.appendChild(span);
                }
            } else {
                if (existing.length) {
                    existing.forEach(el => el.remove());
                }
            }
        } catch (e) {
            console.error('setWishlistCount error', e);
        }
    }
    function setCompareCount(count) {
        try {
            const existing = document.querySelectorAll('.compare-count');
            if (count && parseInt(count) > 0) {
                if (existing.length) { existing.forEach(el => el.textContent = count); return; }
                const compareLink = document.querySelector('a[href="{{ route('compare.index') }}"]');
                if (compareLink) {
                    const span = document.createElement('span');
                    span.className = 'cart_qty_cls compare-count';
                    span.textContent = count;
                    compareLink.appendChild(span);
                }
            } else {
                if (existing.length) existing.forEach(el => el.remove());
            }
        } catch (e) { console.error('setCompareCount error', e); }
    }
</script>
<div class="page-sidebar">
    <div class="main-header-left d-none d-lg-block">
        <div class="logo-wrapper">
            <a href="{{ route('admin.dashboard') }}">
                <img class="d-none d-lg-block blur-up lazyloaded"
                    src="{{ asset('images/logo-white.svg') }}" alt="">
            </a>
        </div>
    </div>
        @push('admin-scripts')
        <script>
                (function($){
                    $(function(){
                        var current = window.location.origin + window.location.pathname; // ignore query/hash
                        current = current.replace(/\/$/, '');

                        // Mark active link by exact/startsWith match and open its parents
                        $('.sidebar-menu a').each(function(){
                            var href = (this.href || '').replace(/\/$/, '');
                            if(!href || href.indexOf('javascript:void') === 0) return;
                            if(current === href || current.indexOf(href) === 0){
                                var $a = $(this).addClass('active');
                                var $submenu = $a.closest('ul.sidebar-submenu');
                                if($submenu.length){
                                    $submenu.show();
                                    var $parentLi = $submenu.closest('li');
                                    $parentLi.addClass('active');
                                    var $header = $parentLi.children('a.sidebar-header');
                                    $header.addClass('active');
                                    // Rotate arrow icon if present
                                    var $icon = $header.find('.fa-angle-right');
                                    if($icon.length){ $icon.removeClass('fa-angle-right').addClass('fa-angle-down'); }
                                }
                            }
                        });

                                // Do not override click behavior; sidebar-menu.js handles toggling.
                    });
                })(jQuery);
            </script>
        @endpush
    <div class="sidebar custom-scrollbar">
        <a href="javascript:void(0)" class="sidebar-back d-lg-none d-block"><i class="fa fa-times"
                aria-hidden="true"></i></a>
        <ul class="sidebar-menu">
            <li>
                <a class="sidebar-header" href="{{ route('admin.dashboard') }}">
                    <i data-feather="home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="box"></i>
                    <span>Products</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>

                <ul class="sidebar-submenu">
                   <li>
                        <a href="{{ route('categories.index') }}">
                            <i class="fa fa-circle"></i>Category
                        </a>
                    </li>
                   <li>
                        <a href="{{ route('brands.index') }}">
                            <i class="fa fa-circle"></i>Brand
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}">
                            <i class="fa fa-circle"></i>Suppliers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}">
                            <i class="fa fa-circle"></i>Product List</a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="dollar-sign"></i>
                    <span>Sales</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('orders.index') }}">
                            <i class="fa fa-circle"></i>Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}">
                            <i class="fa fa-circle"></i>Transactions
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="tag"></i>
                    <span>Coupons</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('coupons.index') }}">
                            <i class="fa fa-circle"></i>List Coupons
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('coupons.create') }}">
                            <i class="fa fa-circle"></i>Create Coupon
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="tag"></i>
                    <span>Discounts</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('discounts.index') }}">
                            <i class="fa fa-circle"></i>List Discounts
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('discounts.create') }}">
                            <i class="fa fa-circle"></i>Create Discount
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="clipboard"></i>
                    <span>Pages</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('home-pages.index') }}">
                            <i class="fa fa-circle"></i>Home Page
                        </a>
                    </li>
                    {{-- <li>
                        <a href="page-create.html">
                            <i class="fa fa-circle"></i>Create Page
                        </a>
                    </li> --}}
                </ul>
            </li>

            {{-- <li>
                <a class="sidebar-header" href="media.html">
                    <i data-feather="camera"></i>
                    <span>Media</span>
                </a>
            </li> --}}

            {{-- <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="align-left"></i>
                    <span>Menus</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="menu-list.html">
                            <i class="fa fa-circle"></i>Menu Lists
                        </a>
                    </li>
                    <li>
                        <a href="create-menu.html">
                            <i class="fa fa-circle"></i>Create Menu
                        </a>
                    </li>
                </ul>
            </li> --}}

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="user-plus"></i>
                    <span>Users</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="fa fa-circle"></i>User List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.create') }}">
                            <i class="fa fa-circle"></i>Create User
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="users"></i>
                    <span>Vendors</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('vendors.index') }}">
                            <i class="fa fa-circle"></i>Vendor List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vendors.create') }}">
                            <i class="fa fa-circle"></i>Create Vendor
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="edit"></i>
                    <span>Blogs</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('blogs.index') }}">
                            <i class="fa fa-circle"></i>Blog List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blogs.create') }}">
                            <i class="fa fa-circle"></i>Create Blog
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li>
                <a class="sidebar-header" href="javascript:void(0)">
                    <i data-feather="chrome"></i>
                    <span>Localization</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="translations.html"><i class="fa fa-circle"></i>Translations
                        </a>
                    </li>
                    <li>
                        <a href="currency-rates.html"><i class="fa fa-circle"></i>Currency Rates
                        </a>
                    </li>
                    <li>
                        <a href="taxes.html"><i class="fa fa-circle"></i>Taxes
                        </a>
                    </li>
                </ul>
            </li> --}}

            {{-- <li>
                <a class="sidebar-header" href="support-ticket.html"><i
                        data-feather="phone"></i><span>Support Ticket</span>
                </a>
            </li> --}}

            <li>
                <a class="sidebar-header" href="{{ route('admin.reports.index') }}"><i
                        data-feather="bar-chart"></i><span>Reports</span>
                </a>
            </li>

            <li>
                <a class="sidebar-header" href="javascript:void(0)"><i
                        data-feather="settings"></i><span>Settings</span><i
                        class="fa fa-angle-right pull-right"></i></a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.settings.index') }}"><i class="fa fa-circle"></i>General Settings</a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="sidebar-header" href="invoice.html"><i
                        data-feather="archive"></i><span>Invoice</span></a>
            </li>

        </ul>
    </div>
</div>
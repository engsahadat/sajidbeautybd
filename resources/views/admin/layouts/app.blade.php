@include('admin.components.header')
        <!-- Page Header Start-->
        @include('admin.components.page-header')
        <!-- Page Header Ends -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            @include('admin.components.page-sidebar')
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                @yield('admin-content')
            </div>
            <!-- footer start-->
            @include('admin.components.top-footer')
            <!-- footer end-->
        </div>
@include('admin.components.footer')

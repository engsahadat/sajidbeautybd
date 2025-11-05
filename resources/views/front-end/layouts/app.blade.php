<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sajid Beauty BD is a beauty and cosmetics shop located at Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205.">
    <meta name="author" content="http://www.sajidbeautybd.com/">
    <meta name="keywords" content="Sajid Beauty BD, cosmetics, beauty shop, Dhaka, Shimanto Shambar Shopping Mall">
    <meta name="robots" content="index, follow">
    <meta name="format-detection" content="telephone=no">

    <link rel="canonical" href="http://www.sajidbeautybd.com/">
    <link rel="icon" href="{{ asset('images/logo.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon">

    <!-- Open Graph -->
    <meta property="og:title" content="Sajid Beauty BD">
    <meta property="og:description" content="Visit us at Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://www.sajidbeautybd.com/">
    <meta property="og:image" content="http://www.sajidbeautybd.com/social-image.png">
    <meta property="og:image:alt" content="Sajid Beauty BD">
    <meta property="og:site_name" content="Sajid Beauty BD">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sajid Beauty BD">
    <meta name="twitter:description" content="Beauty and cosmetics store at Shimanto Shambar Shopping Mall, Dhaka.">
    <meta name="twitter:image" content="http://www.sajidbeautybd.com/social-image.png">

    <title>@yield('title', 'Sajid Beauty BD')</title>

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">

    <!-- Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">

    <!-- Slick slider css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">

    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="theme-color-1">
    <!-- header start -->
    @include('front-end.components.header')
    <!-- header end -->
    
    @yield('content')


    <!-- Footer Section Start -->
    @include('front-end.components.footer')
    <!-- Footer Section End -->
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>

    <!-- fly cart ui jquery-->
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

    <!-- exitintent jquery-->
    <script src="{{ asset('assets/js/jquery.exitintent.js') }}"></script>
    <script src="{{ asset('assets/js/exit.js') }}"></script>

    <!-- slick js-->
    <script src="{{ asset('assets/js/slick.js') }}"></script>

    <!-- menu js-->
    <script src="{{ asset('assets/js/menu.js') }}"></script>

    <!-- lazyload js-->
    <script src="{{ asset('assets/js/lazysizes.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Bootstrap Notification js-->
    <script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

    <!-- Fly cart js-->
    <script src="{{ asset('assets/js/fly-cart.js') }}"></script>

    <!-- Theme js-->
    <script src="{{ asset('assets/js/theme-setting.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('script')
    {{-- <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#exampleModal').modal('show');
            }, 2500);
        });
    </script> --}}
</body>
</html>

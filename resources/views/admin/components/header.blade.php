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

    <title>@yield('admin-title', 'Sajid Beauty BD')</title>

    <!-- Google font-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/font-awesome.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/flag-icon.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/icofont.css') }}">
    <!-- Prism css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/prism.css') }}">
    <!-- Chartist css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/chartist.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/css/toastify.min.css') }}">
    @stack('admin-styles')
</head>
<body>
    <!-- page-wrapper Start-->
    <div class="page-wrapper">
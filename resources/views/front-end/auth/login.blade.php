@extends('front-end.layouts.app')
@section('title', 'Login')
@section('content')
 <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>{{ __('Customer\'s login') }}</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Customer\'s login') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto">
                    <h3>Login</h3>
                    <div class="theme-card">
                        <form class="theme-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-box">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="text" name="email" class="form-control mb-2" id="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" name="password" class="form-control mb-2 mt-2" id="password" placeholder="Enter your password">
                                @error('password')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <a href="{{ route('password.request') }}" class="text-muted">{{ __('Forgot Password?') }}</a><br>
                            <button type="submit" class="btn btn-solid mt-3">{{ __('Login') }}</button>
                        </form>
                        <p class="mt-2 text-muted">{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}">{{ __('Register Now') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->
@endsection

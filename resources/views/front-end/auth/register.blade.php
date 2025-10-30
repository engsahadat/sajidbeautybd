@extends('front-end.layouts.app')
@section('title', 'Register')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>{{ __('Create account') }}</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create account') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->
    <!--section start-->
    <section class="login-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-auto" >
                    <h3>{{ __('Create account') }}</h3>
                    <div class="theme-card">
                        <form class="theme-form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-box">
                                <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" class="form-control mb-2" id="first_name" placeholder="First Name" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" class="form-control mb-2" id="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="email" class="form-label">{{ __('email') }}</label>
                                <input type="email" name="email" class="form-control mb-2" id="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" name="password" class="form-control mb-2" id="password" placeholder="Enter your password">
                                @error('password')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-box">
                                <label for="password_confirmation" class="form-label">{{ __('Re-type Password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control mb-2" id="password_confirmation" placeholder="Re-Enter your password">
                                @error('password_confirmation')
                                    <div class="text-danger mb-2">{{ $message }}</div>
                                @enderror
                            </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-solid w-auto mt-2">{{ __('Create Account') }}</button>
                                </div>
                            </div>
                        </form>
                        <p class="mt-2 text-muted">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <!--Section ends-->
@endsection

@extends('admin.layouts.auth')
@section('auth-title', 'Forgot Password')
@section('auth-content')
    <div class="card tab2-card card-login">
        <div class="card-body">
            <form class="form-horizontal auth-form" action="{{ route('admin.forgot-password.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input name="email" type="email" class="form-control" placeholder="Email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-button">
                    <button class="btn btn-primary" type="submit">{{ __('Send Password Reset Link') }}</button>
                </div>
            </form>
            <div class="text-center">
                <p class="mb-0">
                    {{ __('Remember your password?') }} 
                    <a href="{{ route('admin.login') }}" class="text-primary">{{ __('Back to Login') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
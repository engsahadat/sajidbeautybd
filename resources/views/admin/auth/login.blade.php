@extends('admin.layouts.auth')
@section('auth-title', 'Login')
@section('auth-content')
    <div class="card tab2-card card-login">
        <div class="card-body">
            <form class="form-horizontal auth-form" action="{{ route('admin.login.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input name="email" type="email" class="form-control" placeholder="Email" id="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="input-group">
                        <input name="password" type="password" class="form-control" placeholder="Password" id="password">
                        <button type="button" class="btn" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-terms">
                    <div class="form-check mesm-2">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label ps-2" for="remember">{{ __('Remember me') }}</label>
                        <a href="{{ route('admin.forgot-password') }}" class="btn btn-default forgot-pass">{{ __('Forgot Password?') }}</a>
                    </div>
                </div>
                <div class="form-button">
                    <button class="btn btn-primary" type="submit">{{ __('Login') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('auth-scripts')
<script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            const passwordInput = $('input[name="password"]');
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
                $(this).attr('title', 'Hide password');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
                $(this).attr('title', 'Show password');
            }
        });
    });
</script>
@endpush
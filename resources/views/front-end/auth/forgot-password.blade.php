<x-guest-layout>
    <div class="mb-4 text-sm text-center" style="color: #6c757d;">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm text-center" style="color: #28a745; font-weight: 500;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>

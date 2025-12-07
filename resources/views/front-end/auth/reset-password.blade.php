<x-guest-layout>
    <h4 class="text-center mb-4">Reset Password</h4>
    
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus />
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" class="form-control" type="password" name="password" required />
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            @error('password_confirmation')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>

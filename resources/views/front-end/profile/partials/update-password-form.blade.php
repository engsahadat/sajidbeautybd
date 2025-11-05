<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="h5 mb-1">Update Password</h2>
            <p class="text-muted small mb-0">Ensure your account is using a long, random password to stay secure.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password','updatePassword') is-invalid @enderror" autocomplete="current-password" required>
            @error('current_password','updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password','updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password','updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation','updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password_confirmation','updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Save</button>
            @if (session('status') === 'password-updated')
                <span class="text-success small" id="password-updated-msg">Saved.</span>
                <script>
                    (function(){
                        const el = document.getElementById('password-updated-msg');
                        if(el){ setTimeout(()=>{ el.style.display='none'; }, 2000); }
                    })();
                </script>
            @endif
        </div>
    </form>
</section>

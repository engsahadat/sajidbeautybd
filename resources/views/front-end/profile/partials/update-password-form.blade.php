<section class="mb-4">
    <div class="profile-section-header">
        <h2>Update Password</h2>
        <p>Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password','updatePassword') is-invalid @enderror" autocomplete="current-password" required>
            @error('current_password','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password','updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation','updatePassword') is-invalid @enderror" autocomplete="new-password" required>
            @error('password_confirmation','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="btn-group-form">
            <button type="submit" class="btn-save">
                <i class="ri-save-line me-2"></i>Update Password
            </button>
            @if (session('status') === 'password-updated')
                <span class="success-message" id="password-updated-msg">
                    <i class="ri-check-circle-line me-1"></i>Password updated successfully.
                </span>
                <script>
                    (function(){
                        const el = document.getElementById('password-updated-msg');
                        if(el){ setTimeout(()=>{ el.style.display='none'; }, 3000); }
                    })();
                </script>
            @endif
        </div>
    </form>
</section>

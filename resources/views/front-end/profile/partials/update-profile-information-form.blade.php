<section class="mb-4">
    <div class="profile-section-header">
        <h2>Profile Information</h2>
        <p>Update your account's profile information and email address.</p>
    </div>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('PATCH')
        
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="first_name">
                    @error('first_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required autofocus autocomplete="last_name">
                    @error('last_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert-warning mt-2">
                    <div class="small mb-2"><i class="ri-alert-line me-2"></i>Your email address is unverified.</div>
                    <button form="send-verification" class="btn-secondary-custom btn-sm mt-2">Resend verification email</button>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2 py-2 px-3 mb-0 small">
                        <i class="ri-check-line me-2"></i>A new verification link has been sent to your email address.
                    </div>
                @endif
            @endif
        </div>

        <div class="btn-group-form">
            <button type="submit" class="btn-save">
                <i class="ri-save-line me-2"></i>Save Changes
            </button>
            @if (session('status') === 'profile-updated')
                <span class="success-message" id="profile-updated-msg">
                    <i class="ri-check-circle-line me-1"></i>Saved successfully.
                </span>
                <script>
                    (function(){
                        const el = document.getElementById('profile-updated-msg');
                        if(el){ setTimeout(()=>{ el.style.display='none'; }, 3000); }
                    })();
                </script>
            @endif
        </div>
    </form>
</section>

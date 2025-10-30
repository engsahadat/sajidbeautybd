<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h5 mb-1">Profile Information</h2>
            <p class="text-muted small mb-0">Update your account's profile information and email address.</p>
        </div>
    </div>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus autocomplete="first_name">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required autofocus autocomplete="last_name">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning py-2 px-3 mb-2">
                        <div class="small mb-2">Your email address is unverified.</div>
                        <button form="send-verification" class="btn btn-sm btn-outline-secondary">Resend verification email</button>
                    </div>
                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success py-2 px-3 mb-0 small">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Save</button>
            @if (session('status') === 'profile-updated')
                <span class="text-success small" id="profile-updated-msg">Saved.</span>
                <script>
                    (function(){
                        const el = document.getElementById('profile-updated-msg');
                        if(el){ setTimeout(()=>{ el.style.display='none'; }, 2000); }
                    })();
                </script>
            @endif
        </div>
    </form>
</section>

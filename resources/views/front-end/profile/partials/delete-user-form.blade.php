<section class="mb-4">
    <div class="profile-section-header">
        <h2>Delete Account</h2>
        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting, please download any data you wish to retain.</p>
    </div>

    <button type="button" class="btn-danger-custom" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="ri-delete-bin-line me-2"></i>Delete Account
    </button>

    <!-- Confirm Deletion Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                        <i class="ri-alert-line me-2" style="color: #dc3545;"></i>Delete Your Account?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}" novalidate>
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <div class="form-group">
                            <label for="delete_account_password" class="form-label">Enter Your Password</label>
                            <input type="password" name="password" id="delete_account_password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Password" autofocus>
                            @if($errors->userDeletion->has('password'))
                                <div class="invalid-feedback d-block">{{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-danger-custom">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            (function(){
                const openModal = function(){
                    const modalEl = document.getElementById('confirmUserDeletionModal');
                    if(!modalEl) return;
                    if(typeof bootstrap !== 'undefined' && bootstrap.Modal){
                        const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
                        instance.show();
                    } else {
                        modalEl.classList.add('show');
                        modalEl.style.display='block';
                        modalEl.removeAttribute('aria-hidden');
                    }
                };
                if(document.readyState === 'complete' || document.readyState === 'interactive'){
                    openModal();
                } else {
                    document.addEventListener('DOMContentLoaded', openModal);
                }
            })();
        </script>
    @endif
</section>

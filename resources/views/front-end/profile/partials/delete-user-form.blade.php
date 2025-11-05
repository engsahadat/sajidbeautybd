<section class="mb-4">
    <div class="mb-2">
        <h2 class="h5 mb-1">Delete Account</h2>
        <p class="text-muted small mb-0">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">Delete Account</button>

    <!-- Confirm Deletion Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">Are you sure you want to delete your account?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}" novalidate>
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="small text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <div class="mb-3">
                            <label for="delete_account_password" class="form-label">Password</label>
                            <input type="password" name="password" id="delete_account_password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Password" autofocus>
                            @if($errors->userDeletion->has('password'))
                                <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
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
                        // Fallback: force display if Bootstrap JS not yet loaded
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

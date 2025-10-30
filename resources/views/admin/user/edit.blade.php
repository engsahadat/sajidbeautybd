@extends('admin.layouts.app')
@section('admin-title','Edit User')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit User') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('users.index') }}">{{ __('Users') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit User') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="card">
                <div class="card-body">
                    <form id="user-form" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" value="{{ $user->first_name }}">
                                    <div class="text-danger d-none error-message" id="first_name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" value="{{ $user->last_name }}">
                                    <div class="text-danger d-none error-message" id="last_name-error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ $user->email }}">
                                    <div class="text-danger d-none error-message" id="email-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="{{ $user->phone }}">
                                    <div class="text-danger d-none error-message" id="phone-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }} <small class="text-muted">(Leave blank to keep current password)</small></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                                    <div class="text-danger d-none error-message" id="password-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                    <div class="text-danger d-none error-message" id="password_confirmation-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('Role') }} <span class="text-danger">*</span></label>
                                    <select name="role" id="role" class="form-select">
                                        <option value="2" {{ $user->role == '2' ? 'selected' : '' }}>{{ __('User') }}</option>
                                        <option value="1" {{ $user->role == '1' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="role-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>{{ __('Suspended') }}</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">{{ __('Date of Birth') }}</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $user->date_of_birth }}">
                                    <div class="text-danger d-none error-message" id="date_of_birth-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Profile Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                    
                                    @if($user->image)
                                        <div class="current-image mt-2">
                                            <label class="form-label">{{ __('Current Image') }}</label>
                                            <div>
                                                <img src="{{ asset($user->image) }}" alt="Current Image" 
                                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                <button type="button" class="btn btn-sm btn-danger ms-2 remove-current-image">
                                                    {{ __('Remove') }}
                                                </button>
                                            </div>
                                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <div>
                                <button id="user-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Update User') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('admin-scripts')
<script>
$(document).ready(function() {
    // Clear error messages when user starts typing/changing values
    $('input, select, textarea').on('input change', function() {
        let fieldName = $(this).attr('name');
        if (fieldName) {
            $(`#${fieldName}-error`).addClass('d-none').html('');
        }
    });

    // Image preview functionality
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.image-preview').remove();
                const preview = `
                    <div class="image-preview mt-2">
                        <label class="form-label">{{ __('New Image Preview') }}</label>
                        <div>
                            <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                            <button type="button" class="btn btn-sm btn-danger ms-2 remove-preview">{{ __('Remove') }}</button>
                        </div>
                    </div>
                `;
                $('#image').parent().append(preview);
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image preview
    $(document).on('click', '.remove-preview', function() {
        $('#image').val('');
        $('.image-preview').remove();
    });

    // Remove current image
    $(document).on('click', '.remove-current-image', function() {
        $('#remove_image').val('1');
        $('.current-image').hide();
        $(this).text('{{ __('Image will be removed on save') }}');
    });
});

$('#user-submit').click(function (e) {
    e.preventDefault();
    const form = $('#user-form');
    let formData = new FormData(form[0]); 
    
    // Add the _method field for Laravel method spoofing
    formData.append('_method', 'PUT');
    
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#user-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');

    axios.post("{{ route('users.update', $user->id) }}", formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.status === 200) {
            Toastify({
                text: response.data.message,
                backgroundColor: "green",
                close: true,
            }).showToast();
            setTimeout(function () {
                window.location.href = response.data.redirect;
            }, 2000);
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            let errors = error.response.data.errors;
            console.log(errors);
            
            for (let field in errors) {
                $(`#${field}-error`).removeClass('d-none').html(errors[field][0]);
            }
        } else {
            console.error("An unexpected error occurred.");
            Toastify({
                text: "An unexpected error occurred.",
                backgroundColor: "red",
                close: true,
            }).showToast();
        }
    })
    .finally(() => {
        $('#user-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Update User") }}');
    });
});
</script>
@endpush
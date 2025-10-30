@extends('admin.layouts.app')
@section('admin-title','Create Vendor')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Vendor') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Vendor') }}</li>
                    <li class="breadcrumb-item active">{{ __('Create Vendor') }}</li>
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
                    <form id="vendor-form" method="POST" action="{{ route('vendors.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter vendor name" value="{{ old('name') }}" required>
                                    <div class="text-danger d-none error-message" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company" class="form-label">{{ __('Company') }}</label>
                                    <input type="text" class="form-control" id="company" name="company" placeholder="Enter company name" value="{{ old('company') }}">
                                    <div class="text-danger d-none error-message" id="company-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contact_name" class="form-label">{{ __('Contact Name') }}</label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter contact name" value="{{ old('contact_name') }}">
                                    <div class="text-danger d-none error-message" id="contact_name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}">
                                    <div class="text-danger d-none error-message" id="email-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                                    <div class="text-danger d-none error-message" id="phone-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_line_1" class="form-label">{{ __('Address Line 1') }}</label>
                                    <input type="text" class="form-control" id="address_line_1" name="address_line_1" placeholder="Enter address line 1" value="{{ old('address_line_1') }}">
                                    <div class="text-danger d-none error-message" id="address_line_1-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_line_2" class="form-label">{{ __('Address Line 2') }}</label>
                                    <input type="text" class="form-control" id="address_line_2" name="address_line_2" placeholder="Enter address line 2" value="{{ old('address_line_2') }}">
                                    <div class="text-danger d-none error-message" id="address_line_2-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">{{ __('City') }}</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="{{ old('city') }}">
                                    <div class="text-danger d-none error-message" id="city-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">{{ __('State') }}</label>
                                    <input type="text" class="form-control" id="state" name="state" placeholder="Enter state" value="{{ old('state') }}">
                                    <div class="text-danger d-none error-message" id="state-error"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">{{ __('Postal Code') }}</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Enter postal code" value="{{ old('postal_code') }}">
                                    <div class="text-danger d-none error-message" id="postal_code-error"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="country" class="form-label">{{ __('Country (ISO)') }}</label>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="BD" maxlength="2" value="{{ old('country', 'BD') }}">
                                    <div class="text-danger d-none error-message" id="country-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">{{ __('Website') }}</label>
                                    <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com" value="{{ old('website') }}">
                                    <div class="text-danger d-none error-message" id="website-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Enter notes">{{ old('notes') }}</textarea>
                            <div class="text-danger d-none error-message" id="notes-error"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vendors.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Reset') }}</button>
                                <button id="vendor-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Save Vendor') }}
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
$('#vendor-submit').click(function (e) {
    const form = $('#vendor-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#vendor-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');
    axios.post("{{ route('vendors.store') }}", formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        method: 'post',
        params: {
            '_method': 'POST'
        }
    })
    .then(response => {
        if (response.status === 201) {
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
        }
    })
    .finally(() => {
        $('#vendor-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Save Vendor") }}');
    });
});
</script>
@endpush

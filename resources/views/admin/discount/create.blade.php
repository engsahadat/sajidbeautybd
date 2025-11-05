@extends('admin.layouts.app')
@section('admin-title','Create Discount')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Discount') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Discount') }}</li>
                    <li class="breadcrumb-item active">{{ __('Create Discount') }}</li>
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
                    <form id="discount-form" method="POST" action="{{ route('discounts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter discount title" value="{{ old('title') }}">
                                    <div class="text-danger d-none error-message" id="title-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">{{ __('Type') }}</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="percentage" {{ old('type')=='percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed" {{ old('type')=='fixed' ? 'selected' : '' }}>Fixed</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="type-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="value" class="form-label">{{ __('Value') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="value" name="value" placeholder="0.00" value="{{ old('value') }}">
                                    <div class="text-danger d-none error-message" id="value-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                                    <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    <div class="text-danger d-none error-message" id="start_date-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    <div class="text-danger d-none error-message" id="end_date-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('discounts.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Reset') }}</button>
                                <button id="discount-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Save Discount') }}
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
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.image-preview').remove();
                const preview = `
                    <div class="image-preview mt-2">
                        <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                        <button type="button" class="btn mt-1 remove-preview">X</button>
                    </div>
                `;
                $('#image').parent().append(preview);
            };
            reader.readAsDataURL(file);
        }
    });
    $(document).on('click', '.remove-preview', function() {
        $('#image').val('');
        $('.image-preview').remove();
    });
});

$('#discount-submit').click(function (e) {
    const form = $('#discount-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#discount-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');
    axios.post("{{ route('discounts.store') }}", formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.status === 201) {
            Toastify({ text: response.data.message, backgroundColor: "green", close: true, }).showToast();
            setTimeout(function () { window.location.href = response.data.redirect; }, 2000);
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            let errors = error.response.data.errors;
            for (let field in errors) {
                $(`#${field}-error`).removeClass('d-none').html(errors[field][0]);
            }
        } else {
            console.error("An unexpected error occurred.");
        }
    })
    .finally(() => {
        $('#discount-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Save Discount") }}');
    });
});
</script>
@endpush

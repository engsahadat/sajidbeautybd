@extends('admin.layouts.app')
@section('admin-title','Create Coupon')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Coupon') }}</h3>
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
                        <a href="{{ route('coupons.index') }}">{{ __('Coupons') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Create Coupon') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="card">
                <div class="card-body">
                    <form id="coupon-form" method="POST" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Code') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="code" name="code" placeholder="Enter coupon code" value="{{ old('code') }}">
                                <div class="text-danger d-none error-message" id="code-error"></div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="name" name="name" placeholder="Enter coupon name" value="{{ old('name') }}">
                                <div class="text-danger d-none error-message" id="name-error"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Description') }}</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Enter coupon description">{{ old('description') }}</textarea>
                                <div class="text-danger d-none error-message" id="description-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type">
                                    <option value="fixed" {{ old('type', 'fixed') == 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                                </select>
                                <div class="text-danger d-none error-message" id="type-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Value') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="value" type="number" step="0.01" min="0.01" name="value" placeholder="0.00" value="{{ old('value') }}">
                                <div class="text-danger d-none error-message" id="value-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Min Order Amount') }}</label>
                                <input class="form-control" id="minimum_amount" type="number" step="0.01" min="0" name="minimum_amount" placeholder="0.00" value="{{ old('minimum_amount') }}">
                                <div class="text-danger d-none error-message" id="minimum_amount-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Max Discount') }}</label>
                                <input class="form-control" id="maximum_discount" type="number" step="0.01" min="0" name="maximum_discount" placeholder="0.00" value="{{ old('maximum_discount') }}">
                                <div class="text-danger d-none error-message" id="maximum_discount-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Usage Limit') }}</label>
                                <input class="form-control" id="usage_limit" type="number" min="1" name="usage_limit" placeholder="Unlimited" value="{{ old('usage_limit') }}">
                                <div class="text-danger d-none error-message" id="usage_limit-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Per Customer') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="usage_limit_per_customer" type="number" min="1" name="usage_limit_per_customer" placeholder="1" value="{{ old('usage_limit_per_customer', 1) }}">
                                <div class="text-danger d-none error-message" id="usage_limit_per_customer-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Starts At') }}</label>
                                <input class="form-control" id="starts_at" type="datetime-local" name="starts_at" value="{{ old('starts_at') }}">
                                <div class="text-danger d-none error-message" id="starts_at-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Expires At') }}</label>
                                <input class="form-control" id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at') }}">
                                <div class="text-danger d-none error-message" id="expires_at-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                                <div class="text-danger d-none error-message" id="status-error"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('coupons.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Reset') }}</button>
                                <button id="coupon-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Save Coupon') }}
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
});

$('#coupon-submit').click(function (e) {
    const form = $('#coupon-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#coupon-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');
    
    axios.post("{{ route('coupons.store') }}", formData, {
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
            Toastify({
                text: "An unexpected error occurred.",
                backgroundColor: "red",
                close: true,
            }).showToast();
        }
    })
    .finally(() => {
        $('#coupon-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Save Coupon") }}');
    });
});
</script>
@endpush

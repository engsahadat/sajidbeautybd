@extends('admin.layouts.app')
@section('admin-title','Create Attribute - ' . $product->name)
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Attribute') }}</h3>
                    <p class="text-muted mb-0">Product: <strong>{{ $product->name }}</strong></p>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.attributes.index', $product) }}">{{ __('Attributes') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Attribute') }}</li>
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
                    <form id="attribute-form" method="POST" action="{{ route('products.attributes.store', $product) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attribute_name" class="form-label">{{ __('Attribute Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="attribute_name" name="attribute_name" placeholder="e.g., Material, Weight, Ingredients" value="{{ old('attribute_name') }}">
                                    <small class="text-muted">Example: Material, Weight, Ingredients, Brand</small>
                                    <div class="text-danger d-none error-message" id="attribute_name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attribute_value" class="form-label">{{ __('Attribute Value') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="attribute_value" name="attribute_value" rows="1" placeholder="e.g., 100% Cotton, 250g">{{ old('attribute_value') }}</textarea>
                                    <small class="text-muted">The value or description for this attribute</small>
                                    <div class="text-danger d-none error-message" id="attribute_value-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attribute_group" class="form-label">{{ __('Attribute Group') }}</label>
                                    <input type="text" class="form-control" id="attribute_group" name="attribute_group" placeholder="e.g., Technical Specs, Features" value="{{ old('attribute_group') }}">
                                    <small class="text-muted">Group similar attributes together (optional)</small>
                                    <div class="text-danger d-none error-message" id="attribute_group-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" placeholder="0" min="0" value="{{ old('sort_order', 0) }}">
                                    <small class="text-muted">Display order (lower numbers appear first)</small>
                                    <div class="text-danger d-none error-message" id="sort_order-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> <strong>Tip:</strong> Use attribute groups like "Technical Specs", "Features", or "Benefits" to organize attributes on the product page.
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Create Attribute') }}</button>
                        <a href="{{ route('products.attributes.index', $product) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
        $('#attribute-form').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.error-message').addClass('d-none').text('');
            $('.form-control, .form-select').removeClass('is-invalid');

            var formData = new FormData(this);
            var submitButton = $(this).find('button[type="submit"]');
            var originalButtonText = submitButton.html();

            submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    submitButton.prop('disabled', false).html(originalButtonText);

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var firstErrorField = null;

                        $.each(errors, function(field, messages) {
                            var fieldId = field.replace(/\./g, '_');
                            var errorDiv = $('#' + fieldId + '-error');
                            var inputField = $('#' + fieldId);

                            if (errorDiv.length && inputField.length) {
                                errorDiv.removeClass('d-none').text(messages[0]);
                                inputField.addClass('is-invalid');

                                if (!firstErrorField) {
                                    firstErrorField = inputField;
                                }
                            }
                        });

                        // Scroll to first error
                        if (firstErrorField) {
                            $('html, body').animate({
                                scrollTop: firstErrorField.offset().top - 100
                            }, 500);
                        }
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        // Clear error on input change
        $('.form-control, .form-select').on('input change', function() {
            $(this).removeClass('is-invalid');
            var fieldId = $(this).attr('id');
            $('#' + fieldId + '-error').addClass('d-none').text('');
        });
    });
</script>
@endpush

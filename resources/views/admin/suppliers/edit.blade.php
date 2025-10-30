@extends('admin.layouts.app')
@section('admin-title','Edit Supplier')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit Supplier') }}</h3>
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
                        <a href="{{ route('suppliers.index') }}">{{ __('Suppliers') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Supplier') }}</li>
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
                    <form id="supplier-form" method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Product') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="product_id" name="product_id">
                                    <option value="">{{ __('Select product') }}</option>
                                    @foreach($products as $pid => $pname)
                                        <option value="{{ $pid }}" {{ $supplier->product_id == $pid ? 'selected' : '' }}>{{ $pname }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger d-none error-message" id="product_id-error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Vendor') }} <span class="text-danger">*</span></label>
                                <select class="form-select" id="vendor_id" name="vendor_id">
                                    <option value="">{{ __('Select vendor') }}</option>
                                    @foreach($vendors as $vid => $vname)
                                        <option value="{{ $vid }}" {{ $supplier->vendor_id == $vid ? 'selected' : '' }}>{{ __('Vendor') }} #{{ $vname }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger d-none error-message" id="vendor_id-error"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Supplier SKU') }}</label>
                                <input class="form-control" id="supplier_sku" name="supplier_sku" maxlength="50" placeholder="Enter supplier SKU" value="{{ $supplier->supplier_sku }}">
                                <div class="text-danger d-none error-message" id="supplier_sku-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Cost Price') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="cost_price" type="number" step="0.01" min="0" name="cost_price" placeholder="0.00" value="{{ $supplier->cost_price }}">
                                <div class="text-danger d-none error-message" id="cost_price-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Minimum Order Quantity') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="minimum_order_quantity" type="number" min="1" name="minimum_order_quantity" placeholder="1" value="{{ $supplier->minimum_order_quantity }}">
                                <div class="text-danger d-none error-message" id="minimum_order_quantity-error"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Lead Time (days)') }} <span class="text-danger">*</span></label>
                                <input class="form-control" id="lead_time_days" type="number" min="0" name="lead_time_days" placeholder="0" value="{{ $supplier->lead_time_days }}">
                                <div class="text-danger d-none error-message" id="lead_time_days-error"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="is_primary" {{ $supplier->is_primary ? 'checked' : '' }}>
                                    <label for="is_primary" class="form-check-label">{{ __('Primary Supplier') }}</label>
                                </div>
                                <div class="text-danger d-none error-message" id="is_primary-error"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button id="supplier-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Update Supplier') }}
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

$('#supplier-submit').click(function (e) {
    const form = $('#supplier-form');
    let formData = new FormData(form[0]); 
    
    // Add the _method field for Laravel method spoofing
    formData.append('_method', 'PUT');
    
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#supplier-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');
    
    axios.post("{{ route('suppliers.update', $supplier->id) }}", formData, {
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
            
            // Show validation errors
            for (let field in errors) {
                $(`#${field}-error`).removeClass('d-none').html(errors[field][0]);
            }
            
            // Keep the form values as they are (don't reset to original values)
            // The form already contains the user-submitted values
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
        $('#supplier-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Update Supplier") }}');
    });
});
</script>
@endpush
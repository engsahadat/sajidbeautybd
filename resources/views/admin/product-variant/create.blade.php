@extends('admin.layouts.app')
@section('admin-title','Create Variant - ' . $product->name)
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Variant') }}</h3>
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
                    <li class="breadcrumb-item"><a href="{{ route('products.variants.index', $product) }}">{{ __('Variants') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Create Variant') }}</li>
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
                    <form id="variant-form" method="POST" action="{{ route('products.variants.store', $product) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Variant Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Size, Color, Material" value="{{ old('name') }}">
                                    <small class="text-muted">Example: Size, Color, Material, Weight</small>
                                    <div class="text-danger d-none error-message" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">{{ __('Variant Value') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="value" name="value" placeholder="e.g., M, Red, Cotton" value="{{ old('value') }}">
                                    <small class="text-muted">Example: M, L, XL or Red, Blue, Green</small>
                                    <div class="text-danger d-none error-message" id="value-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">{{ __('SKU') }}</label>
                                    <input type="text" class="form-control" id="sku" name="sku" placeholder="e.g., PROD-M-001" value="{{ old('sku') }}">
                                    <small class="text-muted">Unique identifier for this variant (optional)</small>
                                    <div class="text-danger d-none error-message" id="sku-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Price') }} (৳)</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Leave empty to use product price" step="0.01" min="0" value="{{ old('price') }}">
                                    <small class="text-muted">Override product price (optional)</small>
                                    <div class="text-danger d-none error-message" id="price-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">{{ __('Stock Quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0">
                                    <div class="text-danger d-none error-message" id="stock_quantity-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" placeholder="0" min="0" value="{{ old('sort_order', 0) }}">
                                    <div class="text-danger d-none error-message" id="sort_order-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_style" class="form-label">{{ __('Display Style') }}</label>
                                    <select name="display_style" id="display_style" class="form-select">
                                        <option value="rectangle" {{ old('display_style', 'rectangle') == 'rectangle' ? 'selected' : '' }}>Rectangle Buttons</option>
                                        <option value="circle" {{ old('display_style') == 'circle' ? 'selected' : '' }}>Circle Buttons</option>
                                        <option value="image" {{ old('display_style') == 'image' ? 'selected' : '' }}>Image Swatch</option>
                                        <option value="color" {{ old('display_style') == 'color' ? 'selected' : '' }}>Color Swatch</option>
                                        <option value="radio" {{ old('display_style') == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                        <option value="dropdown" {{ old('display_style') == 'dropdown' ? 'selected' : '' }}>Dropdown Select</option>
                                    </select>
                                    <small class="text-muted">How this variant will be displayed on product page</small>
                                    <div class="text-danger d-none error-message" id="display-style-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_default" class="form-label">{{ __('Set as Default') }}</label>
                                    <select name="is_default" id="is_default" class="form-select">
                                        <option value="0" {{ old('is_default') == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_default') == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <small class="text-muted">Default variant will be pre-selected</small>
                                    <div class="text-danger d-none error-message" id="is_default-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Variant Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Optional variant-specific image. Max size: 2MB</small>
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6" id="swatch-image-container" style="display: none;">
                                <div class="mb-3">
                                    <label for="swatch_image" class="form-label">{{ __('Swatch Image') }}</label>
                                    <input type="file" class="form-control" id="swatch_image" name="swatch_image" accept="image/*">
                                    <small class="form-text text-muted">Small image shown as swatch (for Image Swatch style)</small>
                                    <div class="text-danger d-none error-message" id="swatch-image-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="color-code-container" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color_code" class="form-label">{{ __('Color Code') }}</label>
                                    <input type="color" class="form-control form-control-color" id="color_code" name="color_code" value="{{ old('color_code', '#000000') }}">
                                    <small class="form-text text-muted">Color for swatch display (for Color Swatch style)</small>
                                    <div class="text-danger d-none error-message" id="color-code-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.variants.index', $product) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <button type="button" class="btn btn-primary" id="submit-btn">
                                <i class="fa fa-save"></i> {{ __('Create Variant') }}
                            </button>
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
(function() {
    const form = document.getElementById('variant-form');
    const submitBtn = document.getElementById('submit-btn');
    const displayStyleSelect = document.getElementById('display_style');
    const swatchImageContainer = document.getElementById('swatch-image-container');
    const colorCodeContainer = document.getElementById('color-code-container');

    // Toggle conditional fields based on display style
    function toggleConditionalFields() {
        const style = displayStyleSelect.value;
        
        // Show/hide swatch image field for 'image' style
        if (style === 'image') {
            swatchImageContainer.style.display = '';
        } else {
            swatchImageContainer.style.display = 'none';
        }
        
        // Show/hide color code field for 'color' style
        if (style === 'color') {
            colorCodeContainer.style.display = '';
        } else {
            colorCodeContainer.style.display = 'none';
        }
    }

    // Initialize on page load
    toggleConditionalFields();
    
    // Listen for changes
    displayStyleSelect.addEventListener('change', toggleConditionalFields);

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('d-none');
            el.innerHTML = '';
        });
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    function handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating...';
        clearErrors();

        const csrfToken = (form.querySelector('input[name="_token"]').value || document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                window.location.href = data.redirect;
                return;
            }
            // Show validation errors
            if (response.status === 422 && data.errors) {
                let firstErrorEl = null;
                for (const [field, messages] of Object.entries(data.errors)) {
                    const errorEl = document.getElementById(field + '-error') || document.getElementById(field.replace(/_/g,'-') + '-error');
                    const inputEl = document.getElementById(field) || document.getElementById(field.replace(/_/g,'-'));
                    if (errorEl) {
                        const msg = Array.isArray(messages) ? messages[0] : messages;
                        errorEl.innerHTML = '<small>' + msg + '</small>';
                        errorEl.classList.remove('d-none');
                    }
                    if (inputEl) {
                        inputEl.classList.add('is-invalid');
                        if (!firstErrorEl) firstErrorEl = inputEl;
                    }
                }
                if (firstErrorEl) {
                    firstErrorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorEl.focus();
                }
            } else {
                alert(data.message || 'Failed to create variant');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }

    // Submit on button click (category pattern)
    submitBtn.addEventListener('click', handleSubmit);
    // Also support pressing Enter to submit the form
    form.addEventListener('submit', handleSubmit);
})();
</script>
@endpush

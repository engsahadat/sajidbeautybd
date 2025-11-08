@extends('admin.layouts.app')
@section('admin-title','Edit Variant - ' . $product->name)
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit Variant') }}</h3>
                    <p class="text-muted mb-0">Product: <strong>{{ $product->name }}</strong></p>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.variants.index', $product) }}">{{ __('Variants') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
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
                    <form id="variant-form" method="POST" action="{{ route('products.variants.update', [$product, $variant]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Variant Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $variant->name) }}" required>
                                    <div class="text-danger d-none error-message" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">{{ __('Variant Value') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="value" name="value" value="{{ old('value', $variant->value) }}" required>
                                    <div class="text-danger d-none error-message" id="value-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">{{ __('SKU') }}</label>
                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $variant->sku) }}">
                                    <div class="text-danger d-none error-message" id="sku-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Price') }} (৳)</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ old('price', $variant->price) }}">
                                    <div class="text-danger d-none error-message" id="price-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">{{ __('Stock Quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" required min="0">
                                    <div class="text-danger d-none error-message" id="stock_quantity-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $variant->sort_order ?? 0) }}">
                                    <div class="text-danger d-none error-message" id="sort_order-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_style" class="form-label">{{ __('Display Style') }}</label>
                                    <select name="display_style" id="display_style" class="form-select">
                                        <option value="rectangle" {{ old('display_style', $variant->display_style) == 'rectangle' ? 'selected' : '' }}>Rectangle Buttons</option>
                                        <option value="circle" {{ old('display_style', $variant->display_style) == 'circle' ? 'selected' : '' }}>Circle Buttons</option>
                                        <option value="image" {{ old('display_style', $variant->display_style) == 'image' ? 'selected' : '' }}>Image Swatch</option>
                                        <option value="color" {{ old('display_style', $variant->display_style) == 'color' ? 'selected' : '' }}>Color Swatch</option>
                                        <option value="radio" {{ old('display_style', $variant->display_style) == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                        <option value="dropdown" {{ old('display_style', $variant->display_style) == 'dropdown' ? 'selected' : '' }}>Dropdown Select</option>
                                    </select>
                                    <small class="text-muted">How this variant will be displayed on product page</small>
                                    <div class="text-danger d-none error-message" id="display-style-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_default" class="form-label">{{ __('Set as Default') }}</label>
                                    <select name="is_default" id="is_default" class="form-select">
                                        <option value="0" {{ old('is_default', $variant->is_default) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('is_default', $variant->is_default) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="is_default-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Variant Image') }}</label>
                                    @if($variant->image)
                                        <div class="mb-2">
                                            <img src="{{ asset($variant->image) }}" alt="Current" class="img-thumbnail" style="max-width: 150px;">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label" for="remove_image">{{ __('Remove current image') }}</label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6" id="swatch-image-container" style="display: none;">
                                <div class="mb-3">
                                    <label for="swatch_image" class="form-label">{{ __('Swatch Image') }}</label>
                                    @if($variant->swatch_image)
                                        <div class="mb-2">
                                            <img src="{{ $variant->swatch_image_url }}" alt="Swatch" class="img-thumbnail" style="max-width: 80px;">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="remove_swatch_image" name="remove_swatch_image" value="1">
                                                <label class="form-check-label" for="remove_swatch_image">{{ __('Remove swatch image') }}</label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="swatch_image" name="swatch_image" accept="image/*">
                                    <small class="form-text text-muted">Small image shown as swatch</small>
                                    <div class="text-danger d-none error-message" id="swatch-image-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="color-code-container" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color_code" class="form-label">{{ __('Color Code') }}</label>
                                    <input type="color" class="form-control form-control-color" id="color_code" name="color_code" value="{{ old('color_code', $variant->color_code ?? '#000000') }}">
                                    <small class="form-text text-muted">Color for swatch display</small>
                                    <div class="text-danger d-none error-message" id="color-code-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.variants.index', $product) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <button type="submit" class="btn btn-primary" id="submit-btn"><i class="fa fa-save"></i> {{ __('Update Variant') }}</button>
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
        submitBtn.disabled = true;
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
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
            if (response.status === 422 && data.errors && typeof data.errors === 'object') {
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
                alert(data.message || 'Failed to update variant');
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }

    form.addEventListener('submit', handleSubmit);
})();
</script>
@endpush

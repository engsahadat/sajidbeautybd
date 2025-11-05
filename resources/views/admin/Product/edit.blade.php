@extends('admin.layouts.app')
@section('admin-title', 'Edit Product')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit Product') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit Product') }}</li>
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
                    <form id="product-edit-form" method="POST" action="{{ route('products.update', $product->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}">
                                    <div class="text-danger d-none error-message" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                                    <div class="text-danger d-none error-message" id="slug-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">{{ ('Category') }} <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger d-none error-message" id="category_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">{{ __('Brand') }}</label>
                                    <select name="brand_id" id="brand_id" class="form-select">
                                        <option value="">Choose One....</option>
                                        @foreach($brands as $br)
                                            <option value="{{ $br->id }}" {{ old('brand_id', $product->brand_id) == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger d-none error-message" id="brand_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">{{ __('SKU') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                                    <div class="text-danger d-none error-message" id="sku-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">{{ __('Price') }}</label>
                                    <input type="number" step="0.01" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                                    <div class="text-danger d-none error-message" id="price-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">{{ __('Sale Price') }}</label>
                                    <input type="number" step="0.01" id="sale_price" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                                    <div class="text-danger d-none error-message" id="sale_price-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">{{ __('Stock Qty') }}</label>
                                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                    <div class="text-danger d-none error-message" id="stock_quantity-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock_status" class="form-label">{{ __('Stock Status') }}</label>
                                    <select id="stock_status" name="stock_status" class="form-select">
                                        <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                                        <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>{{ __('Out of Stock') }}</option>
                                        <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>{{ __('On Backorder') }}</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="stock_status-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">{{ __('Weight') }}</label>
                                    <input type="number" step="0.01" id="weight" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                                    <div class="text-danger d-none error-message" id="weight-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dimensions" class="form-label">{{ __('Dimensions') }}</label>
                                    <input type="text" id="dimensions" name="dimensions" class="form-control" value="{{ old('dimensions', $product->dimensions) }}">
                                    <div class="text-danger d-none error-message" id="dimensions-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control rich-text" rows="6">{{ old('description', $product->description) }}</textarea>
                            <div class="text-danger d-none error-message" id="description-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="short_description" class="form-label">{{ __('Short Description') }}</label>
                            <textarea name="short_description" id="short_description" class="form-control rich-text" rows="4">{{ old('short_description', $product->short_description) }}</textarea>
                            <div class="text-danger d-none error-message" id="short_description-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="highlight" class="form-label">{{ __('Highlight') }}</label>
                            <textarea name="highlight" id="highlight" class="form-control rich-text" rows="6" placeholder="One per line or paragraph">{{ old('highlight', $product->highlight) }}</textarea>
                            <div class="text-danger d-none error-message" id="highlight-error"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="skin_concern" class="form-label">{{ __('Skin Concern') }}</label>
                                    <input name="skin_concern" id="skin_concern" class="form-control" placeholder="e.g., Redness, Itching" value="{{ old('skin_concern', $product->skin_concern) }}">
                                    <div class="text-danger d-none error-message" id="skin_concern-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="skin_type" class="form-label">{{ __('Skin Type') }}</label>
                                    <input name="skin_type" id="skin_type" class="form-control" placeholder="e.g., Sensitive Skin, All Skin Types" value="{{ old('skin_type', $product->skin_type) }}">
                                    <div class="text-danger d-none error-message" id="skin_type-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="remark" class="form-label">{{ __('Remark') }}</label>
                                    <input name="remark" id="remark" class="form-control" placeholder="e.g., Paraben Free, Alcohol-Free" value="{{ old('remark', $product->remark) }}">
                                    <div class="text-danger d-none error-message" id="remark-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country_of_origin" class="form-label">{{ __('Country of Origin') }}</label>
                                    <input name="country_of_origin" id="country_of_origin" class="form-control" placeholder="e.g., Taiwan" value="{{ old('country_of_origin', $product->country_of_origin) }}">
                                    <div class="text-danger d-none error-message" id="country_of_origin-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="current-image mt-2">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $product->image_url }}" alt="Current" class="img-thumbnail"
                                                style="width: 100px; height: 100px; object-fit: cover;"
                                                onerror="this.onerror=null;this.src='{{ asset('images/default-image.png') }}';">
                                            @if(is_string($product->image) && trim($product->image) !== '')
                                                <button type="button" class="btn position-absolute remove-current-image"
                                                    style="top:-8px; right:-8px;"
                                                    title="{{ __('Remove') }}">&times;</button>
                                            @endif
                                        </div>
                                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gallery" class="form-label">{{ __('Add Gallery Images') }}</label>
                                    <input type="file" class="form-control" id="gallery" name="gallery[]" accept="image/*" multiple>
                                    @php($rawGallery = $product->gallery ?? [])
                                    @php($urls = $product->gallery_urls)
                                    @if(!empty($urls))
                                    <div class="d-flex gap-2 flex-wrap mt-2" id="existing-gallery">
                                        @foreach($urls as $i => $url)
                                        @php($orig = $rawGallery[$i] ?? null)
                                        <div class="gallery-existing position-relative"
                                            data-path="{{ is_string($orig) ? $orig : '' }}">
                                            <img src="{{ $url }}" alt="Gallery" class="img-thumbnail"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                            <button type="button" class="btn position-absolute remove-existing-gallery"
                                                style="top:-8px; right:-8px;" title="Remove">&times;</button>
                                            @if(is_string($orig))
                                                <input type="hidden" name="keep_gallery[]" value="{{ $orig }}">
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    <div id="gallery-previews" class="d-flex gap-2 flex-wrap mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3"><label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">{{ __('Is Active') }}</label>
                                    <div class="form-check ms-3">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">{{ __('Is Featured') }}</label>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <button id="product-update" type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{ __('Update Product') }}</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin-styles')
<style>
    textarea.form-control { min-height: 140px; resize: vertical; }
    textarea.form-control.rich-text { min-height: 260px; }
    /* Summernote tweaks */
    .note-editor.note-frame { border: 1px solid #ced4da; border-radius: .375rem; }
    .note-toolbar { border-bottom: 1px solid #e9ecef; background: #f8f9fa; border-top-left-radius: .375rem; border-top-right-radius: .375rem; }
    .note-statusbar { display: none; }
    .note-editing-area .note-editable { background: #fff; min-height: 220px; }
</style>
@endpush

@push('admin-scripts')
    <!-- Summernote Lite (no key required) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function(){
            // Init Summernote
            if ($.fn.summernote) {
                $('textarea.rich-text').summernote({
                    height: 260,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol']],
                        ['insert', ['link', 'table']],
                        ['view', ['undo','redo','codeview']]
                    ]
                });
            }

            $('#name').on('input', function () {
                const name = $(this).val();
                let slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            });

            $('#image').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('.image-preview').remove();
                        const preview = `
                        <label class="form-label mt-2">{{ __('New Image Preview') }}</label>
                            <div class="image-preview ">
                                <div class="position-relative d-inline-block">
                                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button" class="btn position-absolute remove-preview" style="top:0px; right:-16px;" title="{{ __('Remove') }}">&times;</button>
                                </div>
                            </div>
                        `;
                        $('#image').parent().append(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
            $(document).on('click', '.remove-preview', function () {
                $('#image').val('');
                $('.image-preview').remove();
            });
            $(document).on('click', '.remove-current-image', function () {
                $('#remove_image').val('1');
                $('.current-image').hide();
                $(this).text('Image will be removed on save');
            });

            // Remove existing gallery image (mark for deletion)
            $(document).on('click', '.remove-existing-gallery', function () {
                const $wrap = $(this).closest('.gallery-existing');
                $wrap.find('input[name="keep_gallery[]"]').remove();
                const path = $wrap.data('path');
                let $deleted = $('#deleted_gallery');
                if ($deleted.length === 0) {
                    $deleted = $('<div style="display:none"></div>').attr('id', 'deleted_gallery');
                    $('#product-edit-form').append($deleted);
                }
                $deleted.append($('<input>').attr({ type: 'hidden', name: 'delete_gallery[]' }).val(path));
                $wrap.remove();
            });

            // Sync Summernote content on submit
            $('#product-edit-form').on('submit', function (e) {
                if ($.fn.summernote) {
                    $('textarea.rich-text').each(function(){
                        const code = $(this).summernote('code');
                        $(this).val(code);
                    });
                }
                e.preventDefault();
                $('#product-update').trigger('click');
            });

            $('#product-update').click(function (e) {
                e.preventDefault();
                if ($.fn.summernote) {
                    $('textarea.rich-text').each(function(){
                        const code = $(this).summernote('code');
                        $(this).val(code);
                    });
                }
                const form = $('#product-edit-form');
                let formData = new FormData(form[0]);
                const spinner = `<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"visually-hidden\">Loading...</span>`;
                $('#product-update').html(spinner);
                $('.error-message').addClass('d-none').html('');
                axios.post(form.attr('action'), formData, { headers: { 'Content-Type': 'multipart/form-data', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(response => { if (response.status === 200) { Toastify({ text: response.data.message, backgroundColor: 'green', close: true }).showToast(); setTimeout(function () { window.location.href = response.data.redirect; }, 1500); } })
                    .catch(error => { if (error.response && error.response.status === 422) { let errors = error.response.data.errors || {}; Object.keys(errors).forEach((field) => { const base = field.replace(/\..*$/, '').replace(/\[\d+\]/g, ''); const targetId = `#${base}-error`; const msg = Array.isArray(errors[field]) ? errors[field][0] : errors[field]; const $el = $(targetId); if ($el.length) { $el.removeClass('d-none').html(msg); } }); } else { console.error('An unexpected error occurred.'); } })
                    .finally(() => { $('#product-update').html('<i class="fa fa-floppy-o"></i> Update Product'); });
            });

            // Gallery previews for edit
            function renderGalleryPreviews(files) {
                const $wrap = $('#gallery-previews');
                $wrap.empty();
                Array.from(files).forEach((file, idx) => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const item = `
                        <div class="gallery-item position-relative" data-index="${idx}">
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            <button type="button" class="btn position-absolute remove-gallery" style="top:-8px; right:-8px;">&times;</button>
                        </div>`;
                        $wrap.append(item);
                    };
                    reader.readAsDataURL(file);
                });
            }

            $('#gallery').on('change', function () {
                renderGalleryPreviews(this.files);
            });

            $(document).on('click', '.remove-gallery', function () {
                const removeIndex = $(this).closest('.gallery-item').data('index');
                const input = document.getElementById('gallery');
                const dt = new DataTransfer();
                Array.from(input.files).forEach((file, idx) => { if (idx !== removeIndex) dt.items.add(file); });
                input.files = dt.files;
                renderGalleryPreviews(input.files);
            });
        });
    </script>
@endpush
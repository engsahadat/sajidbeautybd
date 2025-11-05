@extends('admin.layouts.app')
@section('admin-title','Edit Category')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit Category') }}</h3>
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
                        <a href="{{ route('categories.index') }}">{{ __('Categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Edit Category') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>{{ __('Edit Category') }}</h5>
                </div>
                <div class="card-body">
                    <form id="category-edit-form" method="POST" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" value="{{ old('name', $category->name) }}">
                                    <div class="text-danger d-none error-message" id="name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('Slug') }} <small class="text-muted">(auto-generated if empty)</small></label>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="category-slug" value="{{ old('slug', $category->slug) }}">
                                    <div class="text-danger d-none error-message" id="slug-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            <div class="text-danger d-none error-message" id="description-error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter meta title" value="{{ old('meta_title', $category->meta_title) }}">
                                    <div class="text-danger d-none error-message" id="meta_title-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" placeholder="0" min="0" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                                    <div class="text-danger d-none error-message" id="sort_order-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="2" placeholder="Enter meta description">{{ old('meta_description', $category->meta_description) }}</textarea>
                            <div class="text-danger d-none error-message" id="meta_description-error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                    
                                    @if($category->image)
                                        <div class="current-image mt-2">
                                            <label class="form-label">{{ __('Current Image') }}</label>
                                            <div>
                                                <img src="{{ $category->image_url }}" alt="Current Image" 
                                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                <button type="button" class="btn ms-2 remove-current-image">
                                                    {{ __('x') }}
                                                </button>
                                            </div>
                                            <input type="hidden" name="remove_image" id="remove_image" value="0">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">{{ __('Is Feature') }}</label>
                                    <select name="is_active" id="is_active" class="form-select">
                                        <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <div>
                                <button id="category-update" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Update Category') }}
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
    $('#name').on('input', function() {
        if ($('#slug').val() === '') {
            let name = $(this).val();
            let slug = name.toLowerCase()
                          .replace(/[^\w\s-]/g, '')
                          .replace(/[\s_-]+/g, '-')
                          .replace(/^-+|-+$/g, '');
            $('#slug').val(slug);
        }
    });
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
                            <button type="button" class="btn ms-2 remove-preview">X</button>
                        </div>
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
    $(document).on('click', '.remove-current-image', function() {
        $('#remove_image').val('1');
        $('.current-image').hide();
        $(this).text('Image will be removed on save');
    });
    $('#category-edit-form').on('submit', function(e) {
        e.preventDefault();
        $('#category-update').trigger('click');
    });
});

$('#category-update').click(function (e) {
    e.preventDefault();
    const form = $('#category-edit-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#category-update').html(spinner);
    $('.error-message').addClass('d-none').html('');
    axios.post(form.attr('action'), formData, {
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
            for (let field in errors) {
                $(`#${field}-error`).removeClass('d-none').html(errors[field][0]);
            }
        } else {
            console.error("An unexpected error occurred.");
        }
    })
    .finally(() => {
        $('#category-update').html('<i class="fa fa-floppy-o"></i> {{ __("Update Category") }}');
    });
});
</script>
@endpush
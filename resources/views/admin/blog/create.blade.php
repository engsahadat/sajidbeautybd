@extends('admin.layouts.app')
@section('admin-title','Create Blog')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Blog') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Blog') }}</li>
                    <li class="breadcrumb-item active">{{ __('Create Blog') }}</li>
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
                    <form id="blog-form" method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter blog title" value="{{ old('title') }}">
                                    <div class="text-danger d-none error-message" id="title-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">{{ __('Slug') }} <small class="text-muted">(auto-generated if empty)</small></label>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="blog-slug" value="{{ old('slug') }}">
                                    <div class="text-danger d-none error-message" id="slug-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control rich-text" rows="4" placeholder="Enter blog description">{{ old('description') }}</textarea>
                            <div class="text-danger d-none error-message" id="description-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">{{ __('Content') }}</label>
                            <textarea name="content" id="content" class="form-control rich-text" rows="8" placeholder="Enter blog content">{{ old('content') }}</textarea>
                            <div class="text-danger d-none error-message" id="content-error"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter meta title" value="{{ old('meta_title') }}">
                                    <div class="text-danger d-none error-message" id="meta_title-error"></div>
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
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="2" placeholder="Enter meta description">{{ old('meta_description') }}</textarea>
                            <div class="text-danger d-none error-message" id="meta_description-error"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('Image') }}</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                    <div class="text-danger d-none error-message" id="image-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="text-danger d-none error-message" id="status-error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="author_id" class="form-label">{{ __('Author') }}</label>
                                    <select name="author_id" id="author_id" class="form-select">
                                        @foreach($authors as $id => $name)
                                            <option value="{{ $id }}" {{ old('author_id', auth()->id()) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger d-none error-message" id="author_id-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="published_at" class="form-label">{{ __('Published Date') }}</label>
                            <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                            <div class="text-danger d-none error-message" id="published_at-error"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('blogs.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Reset') }}</button>
                                <button id="blog-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Save Blog') }}
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
@push('admin-styles')
<style>
    textarea.form-control.rich-text { min-height: 260px; }
    .note-editor.note-frame { border: 1px solid #ced4da; border-radius: .375rem; }
    .note-toolbar { border-bottom: 1px solid #e9ecef; background: #f8f9fa; border-top-left-radius: .375rem; border-top-right-radius: .375rem; }
    .note-statusbar { display: none; }
    .note-editing-area .note-editable { background: #fff; min-height: 220px; }
    .note-editor .dropdown-menu { z-index: 1056; }
</style>
@endpush

@push('admin-scripts')
<!-- Summernote Lite (no key required) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // Init Summernote for blog textareas
    if ($.fn.summernote) {
        $('textarea.rich-text').summernote({
            height: 260,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol']],
                ['insert', ['link', 'picture', 'table']],
                ['view', ['undo','redo','codeview']]
            ]
        });
    }
    $('#title').on('input', function() {
        let title = $(this).val();
        let slug = title.toLowerCase()
                      .replace(/[^\w\s-]/g, '')
                      .replace(/[\s_-]+/g, '-')
                      .replace(/^-+|-+$/g, '');
        $('#slug').val(slug);
    });
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

$('#blog-submit').click(function (e) {
    // Sync Summernote HTML back to textareas before building FormData
    if ($.fn.summernote) {
        $('textarea.rich-text').each(function(){
            const code = $(this).summernote('code');
            $(this).val(code);
        });
    }
    const form = $('#blog-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#blog-submit').html(spinner);
    $('.error-message').addClass('d-none').html('');
    axios.post("{{ route('blogs.store') }}", formData, {
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
        $('#blog-submit').html('<i class="fa fa-floppy-o"></i> {{ __("Save Blog") }}');
    });
});
</script>
@endpush

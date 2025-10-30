@extends('admin.layouts.app')
@section('admin-title','Edit Home Page Item')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Edit Home Page Item') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('home-pages.index') }}">{{ __('Home Pages') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form id="home-page-edit-form" method="POST" action="{{ route('home-pages.update', $homePage->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">{{ __('Type') }}</label>
                                    <select id="type" name="type" class="form-select">
                                        <option value="slider" {{ $homePage->type == 'slider' ? 'selected' : '' }}>Slider</option>
                                        <option value="banner" {{ $homePage->type == 'banner' ? 'selected' : '' }}>Banner</option>
                                        <option value="middle" {{ $homePage->type == 'middle' ? 'selected' : '' }}>Middle</option>
                                        <option value="service" {{ $homePage->type == 'service' ? 'selected' : '' }}>Service</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title', $homePage->title) }}">
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">{{ __('Subtitle') }}</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtitle" value="{{ old('subtitle', $homePage->subtitle) }}">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">{{ __('Images') }}</label>
                            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                            <small class="form-text text-muted">Max size: 2MB each. Formats: JPG, PNG, GIF</small>
                            
                            @if(!empty($homePage->image_urls))
                            <div class="mt-3">
                                <label class="form-label">{{ __('Current Images') }}</label>
                                <div id="existing-images" class="d-flex flex-wrap">
                                    @foreach($homePage->image_urls as $img)
                                        <div class="p-1 position-relative" style="width:160px">
                                            <img src="{{ $img }}" style="max-width:100%; border: 1px solid #ddd; border-radius: 4px;" />
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="keep_existing_images" name="keep_existing_images" value="1">
                                        <label class="form-check-label" for="keep_existing_images">
                                            {{ __('Keep existing images and add new ones') }}
                                        </label>
                                        <small class="form-text text-muted d-block">{{ __('If unchecked, new images will replace existing ones') }}</small>
                                    </div>
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" id="remove_all_images" name="remove_all_images" value="1">
                                        <label class="form-check-label" for="remove_all_images">
                                            {{ __('Remove all existing images') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div id="preview" class="mt-2">
                                <label class="form-label d-none" id="preview-label">{{ __('New Images Preview') }}</label>
                                <div class="d-flex flex-wrap" id="preview-container"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home-pages.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button id="home-page-update" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Update') }}
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
$('#images').on('change', function(e){
    const files = e.target.files;
    const $preview = $('#preview-container');
    const $previewLabel = $('#preview-label');
    
    $preview.html('');
    
    if (files.length > 0) {
        $previewLabel.removeClass('d-none');
        Array.from(files).forEach(f => {
            const reader = new FileReader();
            reader.onload = function(ev){
                $preview.append(`<div class="p-1" style="width:160px"><img src="${ev.target.result}" style="max-width:100%; border: 1px solid #ddd; border-radius: 4px;"/></div>`);
            };
            reader.readAsDataURL(f);
        });
    } else {
        $previewLabel.addClass('d-none');
    }
});

// Handle checkbox logic
$('#remove_all_images').on('change', function() {
    if (this.checked) {
        $('#keep_existing_images').prop('checked', false);
    }
});

$('#keep_existing_images').on('change', function() {
    if (this.checked) {
        $('#remove_all_images').prop('checked', false);
    }
});

$('#home-page-edit-form').on('submit', function(e) {
    e.preventDefault();
    $('#home-page-update').trigger('click');
});

$('#home-page-update').click(function (e) {
    e.preventDefault();
    const form = $('#home-page-edit-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"visually-hidden\">Loading...</span>`;
    $('#home-page-update').html(spinner);
    axios.post(form.attr('action'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.status === 200) {
            Toastify({ text: response.data.message, backgroundColor: "green", close: true }).showToast();
            setTimeout(function () { window.location.href = response.data.redirect; }, 1200);
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            let errors = error.response.data.errors;
            console.log(errors);
        } else { console.error(error); }
    })
    .finally(() => {
        $('#home-page-update').html('<i class="fa fa-floppy-o"></i> {{ __('Update') }}');
    });
});
</script>
@endpush

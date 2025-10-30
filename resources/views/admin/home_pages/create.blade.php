@extends('admin.layouts.app')
@section('admin-title','Create Home Page Item')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Create Home Page Item') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">{{ __('Home Pages') }}</li>
                    <li class="breadcrumb-item active">{{ __('Create') }}</li>
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
                    <form id="home-page-form" method="POST" action="{{ route('home-pages.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">{{ __('Type') }}</label>
                                    <select id="type" name="type" class="form-select">
                                        <option value="slider">Slider</option>
                                        <option value="banner">Banner</option>
                                        <option value="middle">Middle</option>
                                        <option value="service">Service</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">{{ __('Subtitle') }}</label>
                            <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="Subtitle" value="{{ old('subtitle') }}">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">{{ __('Images') }}</label>
                            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                            <small class="form-text text-muted">Max size: 2MB each. Formats: JPG, PNG, GIF</small>
                            <div id="preview" class="mt-2 d-flex flex-wrap"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home-pages.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Reset') }}</button>
                                <button id="home-page-submit" type="button" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> {{ __('Save') }}
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
// preview multiple
$('#images').on('change', function(e){
    const files = e.target.files;
    const $preview = $('#preview');
    $preview.html('');
    Array.from(files).forEach(f => {
        const reader = new FileReader();
        reader.onload = function(ev){
            $preview.append(`<div class="p-1" style="width:160px"><img src="${ev.target.result}" style="max-width:100%"/></div>`);
        };
        reader.readAsDataURL(f);
    });
});
$('#home-page-submit').click(function (e) {
    const form = $('#home-page-form');
    let formData = new FormData(form[0]); 
    const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
    $('#home-page-submit').html(spinner);
    axios.post("{{ route('home-pages.store') }}", formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.status === 201) {
            Toastify({ text: response.data.message, backgroundColor: "green", close: true }).showToast();
            setTimeout(function () {
                window.location.href = response.data.redirect;
            }, 1500);
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            let errors = error.response.data.errors;
            console.log(errors);
        } else {
            console.error("An unexpected error occurred.");
        }
    })
    .finally(() => {
        $('#home-page-submit').html('<i class="fa fa-floppy-o"></i> {{ __('Save') }}');
    });
});
</script>
@endpush

@extends('admin.layouts.app')
@section('admin-title','Show Home Page Item')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Home Page Item') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-2">Type</dt>
                <dd class="col-sm-10">{{ ucfirst($homePage->type) }}</dd>
                <dt class="col-sm-2">Title</dt>
                <dd class="col-sm-10">{{ $homePage->title }}</dd>
                <dt class="col-sm-2">Subtitle</dt>
                <dd class="col-sm-10">{{ $homePage->subtitle }}</dd>
                <dt class="col-sm-2">Images</dt>
                <dd class="col-sm-10 d-flex flex-wrap">
                    @foreach($homePage->image_urls as $u)
                        <img src="{{ $u }}" alt="" style="max-width:200px; margin:4px;" />
                    @endforeach
                </dd>
            </dl>
            <a href="{{ route('home-pages.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')
@section('admin-title','Home Pages')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('Home Pages') }}</h3>
                </div>
            </div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Home Pages') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <form class="search-form" action="{{ route('home-pages.index') }}" method="GET">
                <div class="d-flex">
                    <div class="form-group me-2 mb-2">
                        <input class="form-control" type="search" placeholder="Search..." name="search" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
                    <a class="btn btn-secondary mb-2" href="{{ route('home-pages.index') }}">{{ __('Reset') }}</a>
                </div>
            </form>
            <a href="{{ route('home-pages.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add Item') }}</a>
        </div>
        @if(session('message'))
            <div class="alert alert-success m-3">{{ session('message') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger m-3">{{ session('error') }}</div>
        @endif
        <div class="card-body">
            <div class="table-responsive table-desi">
                <table class="table" id="editableTable">
                    <thead>
                        <tr>
                            <th>{{ __('Sl') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Subtitle') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homePages as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @foreach($item->image_urls as $u)
                                        <img src="{{ $u }}" alt="" style="width: 80px; height: 50px; object-fit: cover; margin-right:4px;">
                                    @endforeach
                                </td>
                                <td>{{ ucfirst($item->type) }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->subtitle }}</td>
                                <td>
                                    <a href="{{ route('home-pages.edit', $item->id) }}"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('home-pages.destroy', $item->id) }}" onclick="event.preventDefault(); if(confirm('Delete?')) document.getElementById('delete-form-{{ $item->id }}').submit();"><i class="fa fa-trash"></i></a>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('home-pages.destroy', $item->id) }}" method="POST" style="display:none">@csrf @method('DELETE')</form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($homePages->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <p class="mb-0 text-muted">{{ __('Showing') }} {{ $homePages->firstItem() }} to {{ $homePages->lastItem() }} {{ __('of') }} {{ $homePages->total() }} {{ __('results') }}</p>
                </div>
                <div>
                    {{ $homePages->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

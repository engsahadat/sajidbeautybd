@extends('admin.layouts.app')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @foreach($groups as $group)
                            <div class="mb-4">
                                <h6 class="mb-3">{{ $group['title'] }}</h6>
                                <div class="row g-3">
                                    @foreach($group['fields'] as $field)
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $field['label'] }}</label>
                                            @php($name = $field['key'])
                                            @php($type = $field['type'] ?? 'string')
                                            @if($type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="{{ $name }}" value="0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="{{ $name }}" name="{{ $name }}" value="1" {{ old($name, $field['value'] ?? false) ? 'checked' : '' }}>
                                                </div>
                                            @else
                                                <input type="text" name="{{ $name }}" value="{{ old($name, $field['value'] ?? '') }}" class="form-control @error($name) is-invalid @enderror">
                                            @endif
                                            @error($name)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <button class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

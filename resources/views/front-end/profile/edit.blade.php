@extends('front-end.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success" role="alert">
                            {{ __('Profile updated successfully.') }}
                        </div>
                    @endif

                    @include('front-end.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    @include('front-end.profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    @include('front-end.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



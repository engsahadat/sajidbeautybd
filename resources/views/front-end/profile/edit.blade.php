@extends('front-end.layouts.app')

@push('styles')
<style>
    /* Profile Page Styles */
    .profile-container {
        background-color: #f8f9fa;
        padding: 40px 0;
        margin-bottom: 40px;
    }
    
    .profile-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .profile-card .card-body {
        padding: 30px;
    }
    
    .profile-section-header {
        border-bottom: 2px solid #f5f5f5;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }
    
    .profile-section-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .profile-section-header p {
        color: #999;
        font-size: 14px;
        margin-bottom: 0;
        line-height: 1.5;
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-control {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        background-color: #f9f9f9;
    }
    
    .form-control:focus {
        border-color: var(--theme-color);
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(236, 137, 81, 0.1);
    }
    
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-row .col-md-6 {
        flex: 1;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-control-static {
        padding: 10px 12px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        min-height: 40px;
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #333;
    }
    
    /* Button Styles */
    .btn-save {
        background-color: var(--theme-color);
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        background-color: #d97a3e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(236, 137, 81, 0.3);
    }
    
    .btn-save:active {
        transform: translateY(0);
    }
    
    .btn-danger-custom {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-danger-custom:hover {
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    
    .btn-secondary-custom {
        background-color: #6c757d;
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-secondary-custom:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }
    
    .btn-group-form {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .success-message {
        color: #28a745;
        font-size: 14px;
        font-weight: 600;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        padding: 12px 15px;
        margin-top: 12px;
    }
    
    .alert-success {
        background-color: #d4edda;
        border: 1px solid #28a745;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-container {
            padding: 20px 0;
            margin-bottom: 20px;
        }
        
        .profile-container h1 {
            font-size: 24px !important;
            line-height: 1.2;
        }
        
        .profile-card .card-body {
            padding: 18px;
        }
        
        .profile-section-header {
            padding-bottom: 15px;
            margin-bottom: 18px;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .profile-section-header h2 {
            font-size: 14px;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .profile-section-header p {
            font-size: 13px;
            line-height: 1.4;
        }
        
        .form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }
        
        .form-control {
            padding: 8px 10px;
            font-size: 13px;
            min-height: 36px;
        }
        
        .form-row {
            flex-direction: column;
            gap: 0;
            margin-bottom: 15px;
        }
        
        .form-row .col-md-6 {
            margin-bottom: 12px;
        }
        
        .form-row .col-md-6:last-child {
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .btn-save,
        .btn-danger-custom,
        .btn-secondary-custom {
            padding: 8px 16px;
            font-size: 12px;
        }
        
        .btn-group-form {
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .success-message {
            font-size: 12px;
        }
        
        .alert-warning,
        .alert-success {
            padding: 10px 12px;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .alert-warning .small,
        .alert-success .small {
            font-size: 11px;
        }
    }
    
    @media (max-width: 576px) {
        .profile-container {
            padding: 15px 0;
            margin-bottom: 15px;
        }
        
        .profile-container h1 {
            font-size: 20px !important;
        }
        
        .profile-card {
            margin-bottom: 18px;
        }
        
        .profile-card .card-body {
            padding: 15px;
        }
        
        .profile-section-header {
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        
        .profile-section-header h2 {
            font-size: 12px;
            letter-spacing: 0px;
        }
        
        .profile-section-header p {
            font-size: 12px;
        }
        
        .form-label {
            font-size: 12px;
        }
        
        .form-control {
            padding: 7px 9px;
            font-size: 12px;
            min-height: 34px;
        }
        
        .form-group {
            margin-bottom: 12px;
        }
        
        .btn-save,
        .btn-danger-custom,
        .btn-secondary-custom {
            padding: 7px 14px;
            font-size: 11px;
        }
        
        .btn-save i,
        .btn-danger-custom i,
        .btn-secondary-custom i {
            font-size: 12px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <div class="container">
        <h1 style="color: #333; margin-bottom: 0;">Account Settings</h1>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success" role="alert">
                    <i class="ri-check-line me-2"></i>{{ __('Profile updated successfully.') }}
                </div>
            @endif

            <div class="profile-card">
                <div class="card-body">
                    @include('front-end.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="card-body">
                    @include('front-end.profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="card-body">
                    @include('front-end.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



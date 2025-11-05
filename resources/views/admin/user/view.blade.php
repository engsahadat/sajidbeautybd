@extends('admin.layouts.app')
@section('admin-title','Show User')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="page-header-left">
                    <h3>{{ __('User Details') }}</h3>
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
                        <a href="{{ route('users.index') }}">{{ __('Users') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('Details') }}</li>
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
                    <dl class="row">
                        <dt class="col-sm-3">{{ __('Profile Image') }}</dt>
                        <dd class="col-sm-9">
                            @if($user->image)
                                <img src="{{ asset($user->image) }}" alt="Profile Image" 
                                     style="max-width: 150px; max-height: 150px; object-fit: cover;" 
                                     class="img-thumbnail"
                                     onerror="this.onerror=null;this.src='{{ asset('images/default-user.png') }}';">
                            @else
                                <img src="{{ asset('images/default-user.png') }}" alt="Default Profile" 
                                     style="max-width: 150px; max-height: 150px; object-fit: cover;" 
                                     class="img-thumbnail">
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">{{ __('Full Name') }}</dt>
                        <dd class="col-sm-9">{{ $user->first_name }} {{ $user->last_name }}</dd>
                        
                        <dt class="col-sm-3">{{ __('First Name') }}</dt>
                        <dd class="col-sm-9">{{ $user->first_name }}</dd>
                        
                        <dt class="col-sm-3">{{ __('Last Name') }}</dt>
                        <dd class="col-sm-9">{{ $user->last_name }}</dd>
                        
                        <dt class="col-sm-3">{{ __('Email') }}</dt>
                        <dd class="col-sm-9">{{ $user->email }}</dd>
                        
                        <dt class="col-sm-3">{{ __('Phone') }}</dt>
                        <dd class="col-sm-9">{{ $user->phone ?: 'N/A' }}</dd>
                        
                        <dt class="col-sm-3">{{ __('Role') }}</dt>
                        <dd class="col-sm-9">
                            @if($user->role == 1)
                                <span class="badge bg-danger">{{ __('Admin') }}</span>
                            @else
                                <span class="badge bg-primary">{{ __('User') }}</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">{{ __('Status') }}</dt>
                        <dd class="col-sm-9">
                            @if($user->status == 'active')
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @elseif($user->status == 'inactive')
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            @elseif($user->status == 'suspended')
                                <span class="badge bg-warning">{{ __('Suspended') }}</span>
                            @else
                                <span class="badge bg-dark">{{ ucfirst($user->status) }}</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">{{ __('Date of Birth') }}</dt>
                        <dd class="col-sm-9">
                            @if($user->date_of_birth)
                                {{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years old)
                                </small>
                            @else
                                {{ __('N/A') }}
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">{{ __('Email Verified') }}</dt>
                        <dd class="col-sm-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">{{ __('Verified') }}</span>
                                <small class="text-muted d-block">
                                    {{ $user->email_verified_at->format('M d, Y h:i A') }}
                                </small>
                            @else
                                <span class="badge bg-warning">{{ __('Not Verified') }}</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-3">{{ __('Created At') }}</dt>
                        <dd class="col-sm-9">{{ $user->created_at->format('M d, Y h:i A') }}</dd>
                        
                        <dt class="col-sm-3">{{ __('Updated At') }}</dt>
                        <dd class="col-sm-9">{{ $user->updated_at->format('M d, Y h:i A') }}</dd>
                    </dl>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> {{ __('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
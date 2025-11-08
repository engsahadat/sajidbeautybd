@extends('admin.layouts.app')

@section('title', 'Connect Social Media Pages')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-link me-2"></i>
                        Connect Social Media Pages
                    </h4>
                    <p class="text-muted small mb-0">Connect your social media pages to share products</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Available Platforms -->
                    <div class="mb-4">
                        <h5>Available Platforms</h5>
                        <div class="row">
                            @forelse($settings as $setting)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="fa fa-{{ $setting->platform }} fa-3x mb-3" style="color: {{ 
                                                $setting->platform == 'facebook' ? '#1877f2' : (
                                                $setting->platform == 'instagram' ? '#e4405f' : (
                                                $setting->platform == 'twitter' ? '#1da1f2' : '#6c757d'
                                            )) }}"></i>
                                            <h5 class="text-capitalize">{{ $setting->platform }}</h5>
                                            
                                            @if($setting->access_token && !$setting->isTokenExpired())
                                                <span class="badge bg-success mb-2">
                                                    <i class="fa fa-check-circle me-1"></i> Authorized
                                                </span>
                                                <br>
                                                <a href="{{ route('admin.social-media.connect', $setting->platform) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-sync me-1"></i> Reconnect
                                                </a>
                                            @else
                                                <span class="badge bg-warning mb-2">Not Connected</span>
                                                <br>
                                                <a href="{{ route('admin.social-media.connect', $setting->platform) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fa fa-link me-1"></i> Connect {{ ucfirst($setting->platform) }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle me-2"></i>
                                        No platforms configured yet. Please configure API settings first.
                                        <a href="{{ route('admin.social-media.settings') }}" class="alert-link">Go to Settings</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <hr>

                    <!-- Connected Pages -->
                    <div class="mb-4">
                        <h5>Connected Pages</h5>
                        @forelse($connectedPages as $page)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            @if($page->page_picture)
                                                <img src="{{ $page->page_picture }}" 
                                                     alt="{{ $page->page_name }}" 
                                                     class="rounded-circle"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <i class="fa fa-{{ $page->platform }} fa-3x" 
                                                   style="color: {{ $page->platform_color }}"></i>
                                            @endif
                                        </div>
                                        <div class="col-md-7">
                                            <h6 class="mb-1">{{ $page->page_name }}</h6>
                                            <div class="text-muted small">
                                                <span class="badge" style="background-color: {{ $page->platform_color }}">
                                                    <i class="fa fa-{{ $page->platform }} me-1"></i>
                                                    {{ ucfirst($page->platform) }}
                                                </span>
                                                @if($page->page_username)
                                                    <span class="ms-2">@{{ $page->page_username }}</span>
                                                @endif
                                                <br>
                                                <small>Connected: {{ $page->connected_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            @if($page->page_url)
                                                <a href="{{ $page->page_url }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="fa fa-external-link-alt me-1"></i> View Page
                                                </a>
                                            @endif
                                            <form method="POST" 
                                                  action="{{ route('admin.social-media.disconnect', $page->id) }}" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to disconnect this page?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-unlink me-1"></i> Disconnect
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                No pages connected yet. Connect a platform above to get started.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

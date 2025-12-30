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
                                                <form method="POST" action="{{ route('admin.social-media.fetch-pages', $setting->platform) }}" style="display: inline-block;" class="me-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fa fa-download me-1"></i> Fetch Pages
                                                    </button>
                                                </form>
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

                    <!-- Manual Page Connection -->
                    @if($settings->where('access_token', '!=', null)->where('platform', 'facebook')->count() > 0)
                    <div class="mb-4">
                        <h5>Connect by Page ID <small class="text-muted">(If pages don't auto-fetch)</small></h5>
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.social-media.connect-page-manually') }}">
                                    @csrf
                                    <input type="hidden" name="platform" value="facebook">
                                    <div class="row align-items-end">
                                        <div class="col-md-9">
                                            <label class="form-label">Facebook Page ID</label>
                                            <input type="text" name="page_id" class="form-control" 
                                                   placeholder="Enter your Facebook Page ID (e.g., 122103959102127148)" 
                                                   required>
                                            <small class="text-muted">
                                                Find your Page ID: Go to your page → About → Page ID, or check the URL after "facebook.com/"
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa fa-plus me-1"></i> Connect Page
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                                    <span class="ms-2">{{ $page->page_username }}</span>
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
                                <strong>No pages connected yet.</strong>
                                @if($settings->where('access_token', '!=', null)->count() > 0)
                                    <p class="mb-2 mt-2">Click <strong>"Fetch Pages"</strong> button above to retrieve your pages.</p>
                                    <p class="mb-0 small">
                                        <strong>Note:</strong> You must have Facebook Pages (not just a profile). 
                                        If "Fetch Pages" returns no results:
                                        <br>1. Create a Facebook Page at <a href="https://www.facebook.com/pages/create" target="_blank" class="alert-link">facebook.com/pages/create</a>
                                        <br>2. Click "Reconnect" above and ensure you grant all permissions
                                        <br>3. Try "Fetch Pages" again
                                    </p>
                                @else
                                    Connect a platform above to get started.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

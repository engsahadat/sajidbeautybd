@extends('admin.layouts.app')

@section('title', 'Social Media Settings')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-cog me-2"></i>
                        Social Media Settings
                    </h4>
                    <p class="text-muted small mb-0">Configure API credentials for social media platforms</p>
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

                    <div class="row">
                        @foreach($platforms as $key => $name)
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fa fa-{{ $key }} me-2"></i>
                                        {{ $name }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.social-media.settings.update') }}">
                                        @csrf
                                        <input type="hidden" name="platform" value="{{ $key }}">

                                        <div class="mb-3">
                                            <label class="form-label">App ID / Client ID</label>
                                            <input type="text" 
                                                   name="app_id" 
                                                   class="form-control" 
                                                   value="{{ $settings[$key]->app_id ?? '' }}"
                                                   placeholder="Enter {{ $name }} App ID">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">App Secret / Client Secret</label>
                                            <input type="password" 
                                                   name="app_secret" 
                                                   class="form-control" 
                                                   value="{{ $settings[$key]->app_secret ?? '' }}"
                                                   placeholder="Enter {{ $name }} App Secret">
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   class="form-check-input" 
                                                   id="active_{{ $key }}"
                                                   {{ ($settings[$key]->is_active ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active_{{ $key }}">
                                                Enable this platform
                                            </label>
                                        </div>

                                        @if(isset($settings[$key]) && $settings[$key]->access_token)
                                            <div class="alert alert-success">
                                                <i class="fa fa-check-circle me-2"></i>
                                                Connected
                                                @if($settings[$key]->token_expires_at)
                                                    <br><small>Token expires: {{ $settings[$key]->token_expires_at->format('M d, Y H:i') }}</small>
                                                @endif
                                            </div>
                                        @endif

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-1"></i> Save {{ $name }} Settings
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-info mt-4">
                        <h6><i class="fa fa-info-circle me-2"></i> Setup Instructions</h6>
                        <ul class="mb-0">
                            <li><strong>Facebook:</strong> Create app at <a href="https://developers.facebook.com" target="_blank">developers.facebook.com</a></li>
                            <li>Configure OAuth redirect URL: <code>{{ route('admin.social-media.callback', 'facebook') }}</code></li>
                            <li>Required permissions: pages_show_list, pages_read_engagement, pages_manage_posts, pages_manage_metadata, pages_manage_engagement</li>
                            <li>For detailed instructions, see SOCIAL_MEDIA_MODULE.md documentation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

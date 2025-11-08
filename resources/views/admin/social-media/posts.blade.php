@extends('admin.layouts.app')

@section('title', 'Social Media Posts History')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-list me-2"></i>
                        Social Media Posts History
                    </h4>
                    <p class="text-muted small mb-0">View all products shared to social media</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Product</th>
                                    <th>Platform</th>
                                    <th>Page</th>
                                    <th>Status</th>
                                    <th>Analytics</th>
                                    <th>Posted</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            <strong>{{ $post->product->name ?? 'Product Deleted' }}</strong>
                                            <br>
                                            <small class="text-muted">By: {{ $post->user->name ?? 'Unknown' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $post->page->platform_color ?? '#6c757d' }}">
                                                <i class="fa fa-{{ $post->platform }} me-1"></i>
                                                {{ ucfirst($post->platform) }}
                                            </span>
                                        </td>
                                        <td>{{ $post->page->page_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($post->status === 'published')
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check-circle me-1"></i> Published
                                                </span>
                                            @elseif($post->status === 'failed')
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-times-circle me-1"></i> Failed
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fa fa-clock me-1"></i> Pending
                                                </span>
                                            @endif

                                            @if($post->error_message)
                                                <br>
                                                <small class="text-danger">{{ $post->error_message }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->analytics)
                                                <div class="small">
                                                    <i class="fa fa-thumbs-up text-primary"></i> {{ $post->analytics['likes'] ?? 0 }}
                                                    <i class="fa fa-comment text-info ms-2"></i> {{ $post->analytics['comments'] ?? 0 }}
                                                    <i class="fa fa-share text-success ms-2"></i> {{ $post->analytics['shares'] ?? 0 }}
                                                </div>
                                                @if(isset($post->analytics['fetched_at']))
                                                    <small class="text-muted">
                                                        Updated: {{ \Carbon\Carbon::parse($post->analytics['fetched_at'])->diffForHumans() }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted small">No data</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->published_at)
                                                {{ $post->published_at->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $post->published_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">Not published</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->post_url && $post->status === 'published')
                                                <a href="{{ $post->post_url }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary mb-1">
                                                    <i class="fa fa-external-link-alt"></i> View Post
                                                </a>
                                            @endif

                                            @if($post->status === 'published')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success refresh-analytics"
                                                        data-post-id="{{ $post->id }}">
                                                    <i class="fa fa-sync"></i> Refresh
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                            No posts shared yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin-scripts')
<script>
(function($) {
    $('.refresh-analytics').on('click', function() {
        const $btn = $(this);
        const postId = $btn.data('post-id');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/admin/social-media/posts/${postId}/analytics`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Analytics updated successfully!');
                    location.reload();
                } else {
                    alert('Failed to fetch analytics');
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Failed to fetch analytics';
                alert('❌ ' + error);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
})(jQuery);
</script>
@endpush

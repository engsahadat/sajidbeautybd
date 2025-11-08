@extends('admin.layouts.app')

@section('title', 'Share Products to Social Media')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-share-alt me-2"></i>
                        Share Products to Social Media
                    </h4>
                    <p class="text-muted small mb-0">Select products and share them to your connected social media pages</p>
                </div>
                <div class="card-body">
                    @if($connectedPages->count() == 0)
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            No social media pages connected yet. 
                            <a href="{{ route('admin.social-media.connect-pages') }}" class="alert-link">Connect Pages First</a>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->image || $product->gallery)
                                                <img src="{{ $product->image_url }}" 
                                                     alt="{{ $product->name }}"
                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                     class="rounded">
                                            @else
                                                <div style="width: 60px; height: 60px;" 
                                                     class="bg-light d-flex align-items-center justify-content-center rounded">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                                        </td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>৳{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if($product->stock_quantity > 0)
                                                <span class="badge bg-success">{{ $product->stock_quantity }} in stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of stock</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary share-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-description="{{ strip_tags($product->description ?? '') }}"
                                                    {{ $connectedPages->count() == 0 ? 'disabled' : '' }}>
                                                <i class="fa fa-share-alt me-1"></i> Share
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-share-alt me-2"></i>
                    Share Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="shareForm">
                    <input type="hidden" name="product_id" id="shareProductId">

                    <div class="mb-3">
                        <label class="form-label">Select Page to Share</label>
                        <select name="page_id" id="sharePageId" class="form-select" required>
                            <option value="">-- Select a page --</option>
                            @foreach($connectedPages as $page)
                                <option value="{{ $page->id }}">
                                    {{ $page->page_name }} ({{ ucfirst($page->platform) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message / Caption</label>
                        <textarea name="message" 
                                  id="shareMessage" 
                                  class="form-control" 
                                  rows="6"
                                  placeholder="Customize your post message (optional)"></textarea>
                        <small class="text-muted">Leave empty to use auto-generated message</small>
                    </div>

                    <div class="alert alert-info">
                        <strong>Preview:</strong>
                        <div id="messagePreview" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="shareNowBtn">
                    <i class="fa fa-paper-plane me-1"></i> Share Now
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('admin-scripts')
<script>
(function($) {
    let currentProduct = {};
    const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));

    // Generate default message
    function generateMessage(product) {
        let message = `🛍️ ${product.name}\n\n`;
        
        if (product.description) {
            let desc = product.description.substring(0, 200);
            if (product.description.length > 200) desc += '...';
            message += desc + '\n\n';
        }
        
        message += `💰 Price: ৳${parseFloat(product.price).toFixed(2)}\n`;
        message += `✅ In Stock\n\n`;
        message += `🔗 Shop now!`;
        
        return message;
    }

    // Open share modal
    $('.share-btn').on('click', function() {
        currentProduct = {
            id: $(this).data('product-id'),
            name: $(this).data('product-name'),
            price: $(this).data('product-price'),
            description: $(this).data('product-description')
        };

        $('#shareProductId').val(currentProduct.id);
        
        // Generate and set default message
        const defaultMessage = generateMessage(currentProduct);
        $('#shareMessage').val(defaultMessage);
        $('#messagePreview').text(defaultMessage);

        shareModal.show();
    });

    // Update preview when message changes
    $('#shareMessage').on('input', function() {
        const message = $(this).val() || generateMessage(currentProduct);
        $('#messagePreview').text(message);
    });

    // Share product
    $('#shareNowBtn').on('click', function() {
        const $btn = $(this);
        const pageId = $('#sharePageId').val();
        const message = $('#shareMessage').val();

        if (!pageId) {
            alert('Please select a page to share to');
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Sharing...');

        $.ajax({
            url: '{{ route("admin.social-media.share") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: currentProduct.id,
                page_id: pageId,
                message: message
            },
            success: function(response) {
                shareModal.hide();
                alert('✅ Product shared successfully!');
                
                // Reset form
                $('#shareForm')[0].reset();
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Failed to share product';
                alert('❌ ' + error);
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane me-1"></i> Share Now');
            }
        });
    });

})(jQuery);
</script>
@endpush

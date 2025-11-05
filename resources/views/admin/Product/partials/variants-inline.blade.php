<div class="card mt-4" id="variants-inline-card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="mb-0">Variants</h5>
    <div>
      <button type="button" class="btn btn-sm btn-success" id="btn-open-add-variant"><i class="fa fa-plus"></i> Add Variant</button>
      <a target="_blank" href="{{ route('product-variants.index', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-external-link"></i> Open Full Page</a>
    </div>
  </div>
  <div class="card-body">
    <div id="variants-inline-toolbar" class="d-flex flex-wrap gap-2 mb-3">
      <input type="text" id="variant-search" class="form-control" placeholder="Search by SKU..." style="max-width:220px;">
      <select id="variant-status" class="form-select" style="max-width:160px;">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
      <button class="btn btn-primary" id="variant-filter-apply"><i class="fa fa-search"></i></button>
      <button class="btn btn-secondary" id="variant-filter-reset">Reset</button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped align-middle" id="variants-inline-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th style="width:160px">Actions</th>
          </tr>
        </thead>
        <tbody><tr><td colspan="7" class="text-center text-muted">Loading...</td></tr></tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end mt-2" id="variants-inline-pagination"></div>
  </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="variantModalTitle">Add Variant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="variant-inline-form" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="id" id="variant_id">
          <input type="hidden" name="parent_product_id" id="variant_parent_product_id" value="{{ $product->id }}">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">SKU <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="sku" id="variant_sku">
              <div class="text-danger d-none error-message" id="sku-error"></div>
            </div>
            <div class="col-md-2">
              <label class="form-label">Price <span class="text-danger">*</span></label>
              <input class="form-control" type="number" name="price" id="variant_price" step="0.01" min="0">
              <div class="text-danger d-none error-message" id="price-error"></div>
            </div>
            <div class="col-md-2">
              <label class="form-label">Compare</label>
              <input class="form-control" type="number" name="compare_price" id="variant_compare_price" step="0.01" min="0">
              <div class="text-danger d-none error-message" id="compare_price-error"></div>
            </div>
            <div class="col-md-2">
              <label class="form-label">Stock <span class="text-danger">*</span></label>
              <input class="form-control" type="number" name="stock_quantity" id="variant_stock_quantity" min="0" value="0">
              <div class="text-danger d-none error-message" id="stock_quantity-error"></div>
            </div>
            <div class="col-md-2">
              <label class="form-label">Weight (kg)</label>
              <input class="form-control" type="number" name="weight" id="variant_weight" step="0.001" min="0">
              <div class="text-danger d-none error-message" id="weight-error"></div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" id="variant_status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              <div class="text-danger d-none error-message" id="status-error"></div>
            </div>
            <div class="col-md-9">
              <label class="form-label">Image</label>
              <input class="form-control" type="file" name="image" id="variant_image" accept="image/*">
              <div class="text-danger d-none error-message" id="image-error"></div>
              <div class="mt-2 d-flex align-items-center gap-3" id="variant_image_preview_wrap" style="display:none;">
                <div class="position-relative">
                  <img id="variant_image_preview" src="#" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #e0e0e0;"/>
                  <button type="button" id="variant_remove_preview" class="btn btn-sm btn-danger position-absolute" style="top:-10px;right:-10px;border-radius:50%"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <input type="hidden" name="remove_image" id="variant_remove_image" value="0">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="variant_save_btn"><i class="fa fa-floppy-o"></i> Save</button>
      </div>
    </div>
  </div>
</div>

@push('admin-scripts')
<script>
(function(){
  const productId = {{ $product->id }};
  let currentPage=1, currentSearch='', currentStatus='';
  const modalEl = document.getElementById('variantModal');
  let bsModal;
  if (window.bootstrap) { bsModal = new bootstrap.Modal(modalEl); }

  function fetchVariants(page=1){
    currentPage = page;
    const url = new URL(`{{ route('product-variants.index') }}`, window.location.origin);
    url.searchParams.set('product_id', productId);
    if(currentSearch) url.searchParams.set('search', currentSearch);
    if(currentStatus) url.searchParams.set('status', currentStatus);
    url.searchParams.set('page', page);

    $('#variants-inline-table tbody').html('<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>');
    axios.get(url.toString()).then(res=>{
      const html = $(res.data).find('.card .table-responsive tbody').html();
      const pager = $(res.data).find('.card .d-flex.justify-content-end').html();
      $('#variants-inline-table tbody').html(html || '<tr><td colspan="7" class="text-center text-muted">No variants found.</td></tr>');
      $('#variants-inline-pagination').html(pager || '');
    }).catch(()=>{
      $('#variants-inline-table tbody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load variants</td></tr>');
    });
  }

  function resetForm(){
    $('#variant_id').val('');
    $('#variant_sku').val('');
    $('#variant_price').val('');
    $('#variant_compare_price').val('');
    $('#variant_stock_quantity').val('0');
    $('#variant_weight').val('');
    $('#variant_status').val('active');
    $('#variant_image').val('');
    $('#variant_image_preview_wrap').hide();
    $('#variant_remove_image').val('0');
    $('.error-message').addClass('d-none').html('');
  }

  function openAddModal(){
    resetForm();
    $('#variantModalTitle').text('Add Variant');
    bsModal && bsModal.show();
  }
  function openEditModal(id){
    resetForm();
    $('#variantModalTitle').text('Edit Variant');
    axios.get(`{{ url('/admin/product-variants') }}/${id}`).then(res=>{
      const $doc = $(res.data);
      const sku = $doc.find('#variant-sku').text().trim();
      const priceRaw = $doc.find('#variant-price').attr('data-raw');
      const compareRaw = $doc.find('#variant-compare-price').attr('data-raw');
      const stockRaw = $doc.find('#variant-stock').attr('data-raw');
      const weightRaw = $doc.find('#variant-weight').attr('data-raw');
      const statusRaw = ($doc.find('#variant-status').attr('data-raw')||'').toLowerCase();
      const img = $doc.find('#variant-image-url').attr('src');
      $('#variant_id').val(id);
      $('#variant_sku').val(sku);
      $('#variant_price').val(priceRaw||'');
      $('#variant_compare_price').val(compareRaw||'');
      $('#variant_stock_quantity').val(stockRaw||0);
      $('#variant_weight').val(weightRaw||'');
      $('#variant_status').val(statusRaw||'active');
      if(img){ $('#variant_image_preview').attr('src', img); $('#variant_image_preview_wrap').show(); }
      bsModal && bsModal.show();
    }).catch(()=>{ Toastify({ text:'Failed to load variant', backgroundColor:'red' }).showToast(); });
  }

  function saveVariant(){
    const id = $('#variant_id').val();
    const isEdit = !!id;
    const form = document.getElementById('variant-inline-form');
    const formData = new FormData(form);
    const url = isEdit ? `{{ url('/admin/product-variants') }}/${id}` : `{{ route('product-variants.store') }}`;
    if(isEdit){ formData.append('_method','PUT'); }

    $('#variant_save_btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $('.error-message').addClass('d-none').html('');
    axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
      .then(res=>{
        const expected = isEdit ? 200 : 201;
        if(res.status===expected){
          Toastify({ text: res.data.message || 'Saved', backgroundColor:'green', close:true }).showToast();
          bsModal && bsModal.hide();
          fetchVariants(currentPage);
        }
      })
      .catch(err=>{
        if(err.response && err.response.status===422){
          const errors = err.response.data.errors || {};
          Object.keys(errors).forEach(field=>{
            const $el = $(`#${field}-error`);
            if($el.length){ $el.removeClass('d-none').html(Array.isArray(errors[field])?errors[field][0]:errors[field]); }
          });
        } else {
          Toastify({ text:'Unexpected error', backgroundColor:'red' }).showToast();
        }
      })
      .finally(()=>{ $('#variant_save_btn').prop('disabled', false).html('<i class="fa fa-floppy-o"></i> Save'); });
  }

  // Events
  $('#btn-open-add-variant').on('click', openAddModal);
  $('#variant_image').on('change', function(){
    const file=this.files[0];
    if(file){ const url=URL.createObjectURL(file); $('#variant_image_preview').attr('src',url); $('#variant_image_preview_wrap').show(); $('#variant_remove_image').val('0'); }
  });
  $('#variant_remove_preview').on('click', function(){ $('#variant_image').val(''); $('#variant_image_preview_wrap').hide(); $('#variant_remove_image').val('1'); });
  $('#variant_save_btn').on('click', saveVariant);

  $('#variant-filter-apply').on('click', function(){ currentSearch=$('#variant-search').val(); currentStatus=$('#variant-status').val(); fetchVariants(1); });
  $('#variant-filter-reset').on('click', function(){ $('#variant-search').val(''); $('#variant-status').val(''); currentSearch=''; currentStatus=''; fetchVariants(1); });

  // Delegate actions from table (view/edit/delete)
  $(document).on('click', '#variants-inline-table a.btn-info', function(e){ e.preventDefault(); const href=$(this).attr('href'); window.open(href, '_blank'); });
  $(document).on('click', '#variants-inline-table a.btn-primary', function(e){ e.preventDefault(); const href=$(this).attr('href'); const id = href.split('/').pop(); openEditModal(id); });
  $(document).on('submit', '#variants-inline-table form', function(e){
    e.preventDefault();
    if(!confirm('Delete this variant?')) return;
    const action=$(this).attr('action');
    const data=new FormData();
    data.append('_method','DELETE');
    data.append('_token','{{ csrf_token() }}');
    axios.post(action, data).then(()=>{ Toastify({ text:'Deleted', backgroundColor:'green' }).showToast(); fetchVariants(currentPage); })
    .catch(()=>{ Toastify({ text:'Delete failed', backgroundColor:'red' }).showToast(); });
  });

  // Handle pagination clicks from loaded HTML
  $(document).on('click', '#variants-inline-pagination a, #variants-inline-pagination .pagination a', function(e){
    e.preventDefault();
    const url = new URL($(this).attr('href'), window.location.origin);
    const page = url.searchParams.get('page') || 1;
    fetchVariants(parseInt(page));
  });

  // Initial load
  fetchVariants(1);
})();
</script>
@endpush

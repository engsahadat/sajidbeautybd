@extends('admin.layouts.app')
@section('admin-title','Edit Brand')
@section('admin-content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-lg-6">
				<div class="page-header-left">
					<h3>{{ __('Edit Brand') }}</h3>
				</div>
			</div>
			<div class="col-lg-6">
				<ol class="breadcrumb pull-right">
					<li class="breadcrumb-item">
						<a href="{{ route('admin.dashboard') }}">
							<i data-feather="home"></i>
						</a>
					</li>
					<li class="breadcrumb-item"><a href="{{ route('brands.index') }}">{{ __('Brands') }}</a></li>
					<li class="breadcrumb-item active">{{ __('Edit Brand') }}</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header pb-0">
					<h5>{{ __('Edit Brand') }}</h5>
				</div>
				<div class="card-body">
					<form id="brand-edit-form" method="POST" action="{{ route('brands.update', $brand->id) }}" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="name" name="name" value="{{ old('name', $brand->name) }}">
									<div class="text-danger d-none error-message" id="name-error"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label for="slug" class="form-label">{{ __('Slug') }}</label>
									<input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}">
									<div class="text-danger d-none error-message" id="slug-error"></div>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="description" class="form-label">{{ __('Description') }}</label>
							<textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $brand->description) }}</textarea>
							<div class="text-danger d-none error-message" id="description-error"></div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="website" class="form-label">{{ __('Website') }}</label>
									<input type="url" class="form-control" id="website" name="website" value="{{ old('website', $brand->website) }}">
									<div class="text-danger d-none error-message" id="website-error"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
									<input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $brand->sort_order ?? 0) }}">
									<div class="text-danger d-none error-message" id="sort_order-error"></div>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
							<input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $brand->meta_title) }}">
							<div class="text-danger d-none error-message" id="meta_title-error"></div>
						</div>
						<div class="mb-3">
							<label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
							<textarea name="meta_description" id="meta_description" class="form-control" rows="2">{{ old('meta_description', $brand->meta_description) }}</textarea>
							<div class="text-danger d-none error-message" id="meta_description-error"></div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="logo" class="form-label">{{ __('Logo') }}</label>
									<input type="file" class="form-control" id="logo" name="logo" accept="image/*">
									<small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
									<div class="text-danger d-none error-message" id="logo-error"></div>

									@if($brand->logo)
										<div class="current-image mt-2">
											<label class="form-label">{{ __('Current Logo') }}</label>
											<div>
												<img src="{{ $brand->logo_url }}" alt="Current Logo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
												<button type="button" class="btn ms-2 remove-current-logo">{{ __('X') }}</button>
											</div>
											<input type="hidden" name="remove_logo" id="remove_logo" value="0">
										</div>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label for="status" class="form-label">{{ __('Status') }}</label>
									<select name="status" id="status" class="form-select">
										<option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>Active</option>
										<option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
									</select>
									<div class="text-danger d-none error-message" id="status-error"></div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between">
							<a href="{{ route('brands.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
							<div>
								<button id="brand-update" type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> {{ __('Update Brand') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('admin-scripts')
<script>
$(document).ready(function() {
	$('#name').on('input', function() {
		if ($('#slug').val() === '') {
			let name = $(this).val();
			let slug = name.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
			$('#slug').val(slug);
		}
	});
	$('#logo').change(function() {
		const file = this.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function(e) {
				$('.image-preview').remove();
				const preview = `
					<div class="image-preview mt-2">
						<label class="form-label">{{ __('New Logo Preview') }}</label>
						<div>
							<img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
							<button type="button" class="btn ms-2 remove-preview">X</button>
						</div>
					</div>
				`;
				$('#logo').parent().append(preview);
			};
			reader.readAsDataURL(file);
		}
	});
	$(document).on('click', '.remove-preview', function() { $('#logo').val(''); $('.image-preview').remove(); });
	$(document).on('click', '.remove-current-logo', function() { $('#remove_logo').val('1'); $('.current-image').hide(); $(this).text('Logo will be removed on save'); });
	$('#brand-edit-form').on('submit', function(e) { e.preventDefault(); $('#brand-update').trigger('click'); });
});

$('#brand-update').click(function (e) {
	e.preventDefault();
	const form = $('#brand-edit-form');
	let formData = new FormData(form[0]);
	const spinner = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>`;
	$('#brand-update').html(spinner);
	$('.error-message').addClass('d-none').html('');
	axios.post(form.attr('action'), formData,{ 
		headers: { 
			'Content-Type': 'multipart/form-data',
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		 } 
	})
	.then(response => {
		if (response.status === 200) {
			Toastify({ 
				text: response.data.message,
				backgroundColor: 'green',
				close: true
		 	}).showToast();
			setTimeout(function () { 
				window.location.href = response.data.redirect; 
			}, 1500);
		} 
	})
	.catch(error => { 
		if (error.response && error.response.status === 422) { 
			let errors = error.response.data.errors;
			for (let field in errors) {
				$(`#${field}-error`).removeClass('d-none').html(errors[field][0]); 
			} 
		} else { 
			console.error('An unexpected error occurred.');
		} 
	})
	.finally(() => { 
		$('#brand-update').html('<i class="fa fa-floppy-o"></i> {{ __('Update Brand') }}'); 
	});
});
</script>
@endpush

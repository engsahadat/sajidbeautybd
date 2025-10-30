@extends('admin.layouts.app')
@section('admin-title','Brand List')
@section('admin-content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-lg-6">
				<div class="page-header-left">
					<h3>{{ __('Brand List') }}</h3>
				</div>
			</div>
			<div class="col-lg-6">
				<ol class="breadcrumb pull-right">
					<li class="breadcrumb-item">
						<a href="{{ route('admin.dashboard') }}">
							<i data-feather="home"></i>
						</a>
					</li>
					<li class="breadcrumb-item">{{ __('Brand') }}</li>
					<li class="breadcrumb-item active">{{ __('Brand List') }}</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<form class="search-form" action="{{ route('brands.index') }}" method="GET">
						<div class="d-flex">
							<div class="form-group me-2 mb-2">
								<input class="form-control" type="search" placeholder="Search brands..." name="search" value="{{ request('search') }}">
							</div>
							<button class="btn btn-primary me-2 mb-2" type="submit">Search</button>
							<a class="btn btn-secondary mb-2" href="{{ route('brands.index') }}">Reset</a>
						</div>
					</form>
					<a href="{{ route('brands.create') }}" class="btn btn-primary add-row mt-md-0 mt-2"><i class="fa fa-plus"></i> {{ __('Add Brand') }}</a>
				</div>
				@if(session('message'))
					<div class="alert alert-success m-3">{{ session('message') }}</div>
				@endif
				@if(session('error'))
					<div class="alert alert-danger m-3">{{ session('error') }}</div>
				@endif
				<div class="card-body">
					<div class="table-responsive table-desi">
						<table class="table table-category" id="editableTable">
							<thead>
								<tr>
									<th>{{ __('Sl') }}</th>
									<th>{{ __('Logo') }}</th>
									<th>{{ __('Name') }}</th>
									<th>{{ __('Slug') }}</th>
									<th>{{ __('Sort order') }}</th>
									<th>{{ __('Status') }}</th>
									<th>{{ __('Created At') }}</th>
									<th>{{ __('Option') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($brands as $brand)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>
											<img src="{{ $brand->logo_url }}" alt="logo" style="width: 50px; height: 50px; object-fit: cover;">
										</td>
										<td>{{ $brand->name }}</td>
										<td>{{ $brand->slug }}</td>
										<td>{{ $brand->sort_order }}</td>
										<td class="{{ $brand->status == 'active' ? 'order-success' : 'order-cancle' }}">
											<span>{{ $brand->status }}</span>
										</td>
										<td>{{ $brand->created_at }}</td>
										<td>
											<a href="{{ route('brands.show', $brand->id) }}" class="text-primary"><i class="fa fa-eye" title="View"></i></a>
											<a href="{{ route('brands.edit', $brand->id) }}">
												<i class="fa fa-edit" title="Edit"></i>
											</a>
											<a href="{{ route('brands.destroy', $brand->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $brand->id }}').submit();">
												<i class="fa fa-trash" title="Delete"></i>
											</a>
											<form id="delete-form-{{ $brand->id }}" action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display: none;">
												@csrf
												@method('DELETE')
											</form>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="8" class="text-center text-muted">No brands found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if($brands->hasPages())
					<div class="d-flex justify-content-between align-items-center mt-3">
						<div>
							<p class="mb-0 text-muted">
								Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }} results
								@if(request('search')) for "<strong>{{ request('search') }}</strong>" @endif
							</p>
						</div>
						<div>
							{{ $brands->appends(request()->query())->links() }}
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('admin-scripts')
@endpush

@extends('admin.layouts.app')
@section('admin-title','Suppliers')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-lg-6">
        <div class="page-header-left">
          <h3>{{ __('Suppliers') }}</h3>
        </div>
      </div>
      <div class="col-lg-6">
        <ol class="breadcrumb pull-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
          <li class="breadcrumb-item active">{{ __('Suppliers') }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  @if(session('message'))
    <div class="alert alert-success">
      {{ session('message') }}
    </div>
  @endif
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex gap-2" method="GET">
          <select name="product_id" class="form-select" style="max-width:220px">
            <option value="">{{ __('All Products') }}</option>
            @foreach($products as $pid=>$pname)
              <option value="{{ $pid }}" {{ request('product_id')==$pid?'selected':'' }}>{{ $pname }}</option>
            @endforeach
          </select>
          <select name="vendor_id" class="form-select" style="max-width:180px">
            <option value="">{{ __('All Vendors') }}</option>
            @foreach($vendors as $vid=>$vname)
              <option value="{{ $vid }}" {{ request('vendor_id')==$vid?'selected':'' }}>{{ $vname }}</option>
            @endforeach
          </select>
          <select name="is_primary" class="form-select" style="max-width:150px">
            <option value="">{{ __('Any') }}</option>
            <option value="1" {{ request('is_primary','')==='1'?'selected':'' }}>{{ __('Primary Only') }}</option>
            <option value="0" {{ request('is_primary','')==='0'?'selected':'' }}>{{ __('Non-Primary') }}</option>
          </select>
          <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
          <a class="btn btn-secondary mb-2" href="{{ route('suppliers.index') }}">{{ __('Reset') }}</a>
        </form>
        <a class="btn btn-primary" href="{{ route('suppliers.create') }}"><i class="fa fa-plus"></i> {{ __('New Supplier') }}</a>
      </div>

    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>{{ __('Sl') }}</th>
            <th>{{ __('Product') }}</th>
            <th>{{ __('Vendor') }}</th>
            <th>{{ __('SKU') }}</th>
            <th>{{ __('Cost') }}</th>
            <th>{{ __('MOQ') }}</th>
            <th>{{ __('Lead (days)') }}</th>
            <th>{{ __('Primary') }}</th>
            <th>{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $s)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $s->product?->name}}</td>
              <td>{{ $s->vendor?->name }}</td>
              <td>{{ $s->supplier_sku }}</td>
              <td>{{ number_format($s->cost_price,2) }}</td>
              <td>{{ $s->minimum_order_quantity }}</td>
              <td>{{ $s->lead_time_days }}</td>
              <td>{!! $s->is_primary ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
              <td class="text-end">
                <a href="{{ route('suppliers.edit',$s->id) }}"><i class="fa fa-pencil"></i></a>
                <a href="{{ route('suppliers.destroy',$s->id) }}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this supplier?')) { document.getElementById('delete-form-{{ $s->id }}').submit(); }"><i class="fa fa-trash"></i></a>
                <form id="delete-form-{{ $s->id }}" action="{{ route('suppliers.destroy',$s->id) }}" method="POST" class="d-none">
                  @csrf @method('DELETE')
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted">No suppliers found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3 d-flex justify-content-end">{{ $suppliers->links() }}</div>
  </div></div>
</div>
@endsection

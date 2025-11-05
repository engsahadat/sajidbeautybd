@extends('admin.layouts.app')
@section('admin-title','Coupons')
@section('admin-content')
<div class="container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-lg-6">
        <div class="page-header-left">
          <h3>{{ __('Coupons') }}</h3>
        </div>
      </div>
      <div class="col-lg-6">
        <ol class="breadcrumb pull-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
          <li class="breadcrumb-item active">{{ __('Coupons') }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  @if(session('message'))<div class="alert alert-success">{{ session('message') }}</div>@endif
  <div class="card"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <form class="d-flex gap-2" method="GET">
        <input class="form-control" type="text" name="q" value="{{ request('q') }}" placeholder="Search code or name">
        <select class="form-select" name="status">
          <option value="">{{ __('All Status') }}</option>
          @foreach(['active'=>'Active','inactive'=>'Inactive'] as $key=>$label)
            <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $label }}</option>
          @endforeach
        </select>
        <button class="btn btn-primary me-2 mb-2" type="submit">{{ __('Search') }}</button>
        <a class="btn btn-secondary mb-2" href="{{ route('coupons.index') }}">{{ __('Reset') }}</a>
      </form>
      <a class="btn btn-primary" href="{{ route('coupons.create') }}"><i class="fa fa-plus"></i> {{ __('New Coupon') }}</a>
    </div>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>{{ __('Code') }}</th>
            <th>{{ __('Name')}}</th>
            {{-- <th>{{ __('Description')}}</th> --}}
            <th>{{ __('Type')}}</th>
            <th>{{ __('Value')}}</th>
            <th>{{ __('Min Order')}}</th>
            <th>{{ __('Max Discount')}}</th>
            <th>{{ __('Usage')}}</th>
            <th>{{ __('Per Customer')}}</th>
            <th>{{ __('Period')}}</th>
            <th>{{ __('Status')}}</th>
            {{-- <th>{{ __('Created')}}</th> --}}
            <th>{{ __('Action')}}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($coupons as $c)
            <tr>
                <td>
                  <code>{{ $c->code }}</code>
                </td>
              <td>{{ $c->name }}</td>
              {{-- <td>
                @if($c->description)
                  <span title="{{ $c->description }}">{{ Str::limit($c->description, 30) }}</span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td> --}}
              <td>{{ ucfirst($c->type) }}</td>
              <td>{{ $c->type==='percentage' ? $c->value.'%' : number_format($c->value,2) }}</td>
              <td>{{ $c->minimum_amount ? number_format($c->minimum_amount,2) : '-' }}</td>
              <td>{{ $c->maximum_discount ? number_format($c->maximum_discount,2) : '-' }}</td>
              <td>{{ $c->used_count }} / {{ $c->usage_limit ?? '∞' }}</td>
              <td>{{ $c->usage_limit_per_customer }}</td>
              <td>
                @if($c->starts_at) {{ $c->starts_at->format('Y-m-d') }} @else - @endif
                —
                @if($c->expires_at) {{ $c->expires_at->format('Y-m-d') }} @else - @endif
              </td>
              <td><span class="badge bg-{{ $c->status==='active'?'success':'secondary' }}">{{ ucfirst($c->status) }}</span></td>
              {{-- <td><small class="text-muted">{{ $c->created_at->format('M d, Y') }}</small></td> --}}
              <td class="text-end">
                <a href="{{ route('coupons.edit', $c->id) }}"><i class="fa fa-pencil"></i></a>
                <form action="{{ route('coupons.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete coupon?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="12" class="text-center text-muted">No coupons found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $coupons->links() }}</div>
  </div></div>
</div>
@endsection

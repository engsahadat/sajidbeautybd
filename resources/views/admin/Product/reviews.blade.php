@extends('admin.layouts.app')
@section('admin-title','Product Reviews')
@section('admin-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6"><div class="page-header-left"><h3>Product Reviews</h3></div></div>
            <div class="col-lg-6">
                <ol class="breadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Products</li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card"><div class="card-body">
        <form method="GET" action="{{ route('products.reviews.index') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-3">
                <label class="form-label">Product</label>
                <select name="product_id" class="form-select">
                    <option value="">All Products</option>
                    @foreach($products as $pid=>$pname)
                        <option value="{{ $pid }}" {{ (string)request('product_id', $selectedProductId) === (string)$pid ? 'selected' : '' }}>{{ $pname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Title or review text...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="">All</option>
                    @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}" {{ request('rating') == (string)$i ? 'selected' : '' }}>{{ $i }} star{{ $i>1?'s':'' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Verified</label>
                <select name="verified" class="form-select">
                    <option value="">All</option>
                    <option value="1" {{ request('verified')==='1'?'selected':'' }}>Yes</option>
                    <option value="0" {{ request('verified')==='0'?'selected':'' }}>No</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                    <option value="approved" {{ request('status')==='approved'?'selected':'' }}>Approved</option>
                    <option value="rejected" {{ request('status')==='rejected'?'selected':'' }}>Rejected</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Filter</button>
                <a class="btn btn-secondary" href="{{ route('products.reviews.index') }}">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Title</th>
                        <th>Review</th>
                        <th>Verified</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reviews as $idx=>$r)
                    <tr>
                        <td>{{ $reviews->firstItem() + $idx }}</td>
                        <td>{{ $r->product?->name }}</td>
                        <td>
                            @for($i=1;$i<=5;$i++)
                                <i class="fa {{ $i <= (int)$r->rating ? 'fa-star text-warning' : 'fa-star-o text-muted' }}"></i>
                            @endfor
                            <span class="ms-1">({{ $r->rating }})</span>
                        </td>
                        <td>{{ $r->title }}</td>
                        <td style="max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $r->review }}">{{ $r->review }}</td>
                        <td>{!! $r->is_verified_purchase ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                        <td><span class="badge {{ $r->status==='approved'?'bg-success':($r->status==='rejected'?'bg-danger':'bg-warning') }}">{{ ucfirst($r->status) }}</span></td>
                        <td>{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                        <td>
                            <form action="{{ route('products.reviews.destroy', ['product'=>$r->product_id, 'review'=>$r->id]) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No reviews found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">{{ $reviews->links() }}</div>
    </div></div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Stock Alert Report')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                        Stock Alert Report
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">Low Stock Products</h6>
                                            <h3 class="mb-0">{{ $totalLowStock }}</h3>
                                        </div>
                                        <i class="fa fa-box fa-2x" style="opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">Out of Stock Products</h6>
                                            <h3 class="mb-0">{{ $totalOutOfStock }}</h3>
                                        </div>
                                        <i class="fa fa-exclamation-circle fa-2x" style="opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">Low Stock Variants</h6>
                                            <h3 class="mb-0">{{ $totalVariantsLowStock }}</h3>
                                        </div>
                                        <i class="fa fa-layer-group fa-2x" style="opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">Urgent Restock Needed</h6>
                                            <h3 class="mb-0">{{ $urgentProducts }}</h3>
                                        </div>
                                        <i class="fa fa-truck fa-2x" style="opacity: 0.5;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.reports.stock-alert') }}" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <label for="threshold" class="form-label">Stock Threshold</label>
                                <input type="number" 
                                       name="threshold" 
                                       id="threshold" 
                                       class="form-control" 
                                       value="{{ $threshold }}" 
                                       min="0"
                                       placeholder="Enter threshold">
                            </div>
                            <div class="col-md-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-filter me-1"></i> Apply Filter
                                </button>
                                <a href="{{ route('admin.reports.stock-alert') }}" class="btn btn-secondary me-2">
                                    <i class="fa fa-redo me-1"></i> Reset
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fa fa-download me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.reports.stock-alert.export', ['format' => 'csv', 'threshold' => $threshold, 'category' => $category]) }}">
                                                <i class="fa fa-file-csv me-2"></i> Export as CSV
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.reports.stock-alert.export', ['format' => 'excel', 'threshold' => $threshold, 'category' => $category]) }}">
                                                <i class="fa fa-file-excel me-2"></i> Export as Excel
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.reports.stock-alert.export', ['format' => 'pdf', 'threshold' => $threshold, 'category' => $category]) }}">
                                                <i class="fa fa-file-pdf me-2"></i> Export as PDF
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Low Stock Products Table -->
                    @if($lowStockProducts->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fa fa-box text-warning me-2"></i>
                            Low Stock Products ({{ $lowStockProducts->count() }})
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="35%">Product Name</th>
                                        <th width="15%">SKU</th>
                                        <th width="15%">Category</th>
                                        <th width="10%" class="text-center">Stock</th>
                                        <th width="10%">Price</th>
                                        <th width="10%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockProducts as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->category_name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if($product->stock_quantity <= 0)
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-times-circle me-1"></i>
                                                    Out of Stock
                                                </span>
                                            @elseif($product->stock_quantity == 1)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fa fa-exclamation-triangle me-1"></i>
                                                    {{ $product->stock_quantity }} Left
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($product->price, 2) }}</td>
                                        <td class="text-center">
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Low Stock Variants Table -->
                    @if($lowStockVariants->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fa fa-layer-group text-info me-2"></i>
                            Low Stock Product Variants ({{ $lowStockVariants->count() }})
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="30%">Product Name</th>
                                        <th width="20%">Variant</th>
                                        <th width="15%">SKU</th>
                                        <th width="15%">Category</th>
                                        <th width="15%" class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockVariants as $index => $variant)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', $variant->product_id) }}" class="text-decoration-none">
                                                {{ $variant->product_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $variant->variant_name }}: {{ $variant->variant_value }}
                                            </span>
                                        </td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>{{ $variant->category_name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if($variant->stock_quantity <= 0)
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-times-circle me-1"></i>
                                                    Out of Stock
                                                </span>
                                            @elseif($variant->stock_quantity == 1)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fa fa-exclamation-triangle me-1"></i>
                                                    {{ $variant->stock_quantity }} Left
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    {{ $variant->stock_quantity }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($lowStockProducts->count() == 0 && $lowStockVariants->count() == 0)
                    <div class="alert alert-success text-center py-5">
                        <i class="fa fa-check-circle fa-3x mb-3"></i>
                        <h5>All Products Have Sufficient Stock!</h5>
                        <p class="mb-0">No products or variants found with stock ≤ {{ $threshold }}.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .opacity-50 {
        opacity: 0.5;
    }
</style>
@endsection

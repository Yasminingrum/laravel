@extends('layouts.app')

@section('title', 'Product Alerts - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold mb-0">Product Alerts</h1>
                    <p class="text-muted">Monitor inventory levels and product status</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="{{ route('products.list') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>View All Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="alert-summary-card critical">
                <div class="card-content">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-data">
                        <h3>{{ $alerts['critical']['out_of_stock']->count() + $alerts['critical']['high_value_low_stock']->count() }}</h3>
                        <p>Critical Alerts</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="alert-summary-card warning">
                <div class="card-content">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-data">
                        <h3>{{ $alerts['warning']['low_stock']->count() + $alerts['warning']['needs_restock']->count() }}</h3>
                        <p>Warning Alerts</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="alert-summary-card info">
                <div class="card-content">
                    <div class="alert-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="alert-data">
                        <h3>{{ $alerts['info']['new_products']->count() }}</h3>
                        <p>New Products</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="alert-summary-card success">
                <div class="card-content">
                    <div class="alert-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="alert-data">
                        <h3>{{ $alerts['warning']['overpriced']->count() }}</h3>
                        <p>Price Alerts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Critical Alerts
                        <span class="badge bg-light text-danger ms-2">
                            {{ $alerts['critical']['out_of_stock']->count() + $alerts['critical']['high_value_low_stock']->count() }}
                        </span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($alerts['critical']['out_of_stock']->count() > 0 || $alerts['critical']['high_value_low_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Issue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alerts['critical']['out_of_stock'] as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image_url)
                                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                                                        <small class="text-muted">#{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            </td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $product->stock }} units</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($alerts['critical']['high_value_low_stock'] as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image_url)
                                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                                                        <small class="text-muted">#{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            </td>
                                            <td>
                                                {{ $product->formatted_price }}
                                                <span class="badge bg-warning text-dark">Premium</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $product->stock }} units</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">High Value Low Stock</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle display-4 text-success mb-3"></i>
                            <h5 class="text-success">No Critical Alerts</h5>
                            <p class="text-muted">All products have adequate stock levels.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Alerts -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>Warning Alerts
                        <span class="badge bg-dark ms-2">
                            {{ $alerts['warning']['low_stock']->count() + $alerts['warning']['needs_restock']->count() }}
                        </span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($alerts['warning']['low_stock']->count() > 0 || $alerts['warning']['needs_restock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Issue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alerts['warning']['low_stock'] as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image_url)
                                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                                                        <small class="text-muted">#{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            </td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $product->stock }} units</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Low Stock</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach($alerts['warning']['needs_restock'] as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image_url)
                                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                                                        <small class="text-muted">#{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            </td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $product->stock }} units</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Needs Restock</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle display-4 text-success mb-3"></i>
                            <h5 class="text-success">No Warning Alerts</h5>
                            <p class="text-muted">All products have good stock levels.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- New Products -->
    @if($alerts['info']['new_products']->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>New Products (Last 7 Days)
                            <span class="badge bg-light text-info ms-2">{{ $alerts['info']['new_products']->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($alerts['info']['new_products'] as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="card border h-100">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="{{ $product->name }}">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <i class="fas fa-image text-muted fa-2x"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($product->name, 25) }}</h6>
                                            <p class="card-text small text-muted">{{ Str::limit($product->description, 50) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
                                                <span class="badge bg-success">{{ $product->stock }} in stock</span>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm me-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Price Alerts -->
    @if($alerts['warning']['overpriced']->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tags me-2"></i>Price Alerts
                            <span class="badge bg-light text-dark ms-2">{{ $alerts['warning']['overpriced']->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Current Price</th>
                                        <th>Suggested Price</th>
                                        <th>Issue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alerts['warning']['overpriced'] as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image_url)
                                                        <img src="{{ $product->image_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ Str::limit($product->name, 30) }}</div>
                                                        <small class="text-muted">#{{ $product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                            </td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td>
                                                <span class="text-success">{{ $product->formatted_discounted_price }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Overpriced</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .alert-summary-card {
        border-radius: 15px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .alert-summary-card.critical {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }

    .alert-summary-card.warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }

    .alert-summary-card.info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }

    .alert-summary-card.success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }

    .card-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .alert-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .alert-data h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .alert-data p {
        margin: 0;
        opacity: 0.9;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
    }

    .table td {
        vertical-align: middle;
        padding: 12px 8px;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .card-content {
            flex-direction: column;
            text-align: center;
        }

        .alert-icon {
            margin-bottom: 10px;
        }

        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard - Toko Saya')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6 fw-bold">Dashboard</h1>
                <div class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ now()->format('M d, Y - H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $metrics['total_products'] }}</h3>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $metrics['active_products'] }}</h3>
                            <p class="mb-0">Active Products</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">Rp {{ number_format($metrics['total_inventory_value'] / 1000000, 1) }}M</h3>
                            <p class="mb-0">Inventory Value</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">Rp {{ number_format($metrics['average_price'], 0) }}</h3>
                            <p class="mb-0">Average Price</p>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-tag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Inventory Insights -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Inventory Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Stock Distribution</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>In Stock</span>
                                <span class="badge bg-success">{{ $insights['stock_distribution']['in_stock'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Low Stock</span>
                                <span class="badge bg-warning">{{ $insights['stock_distribution']['low_stock'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Out of Stock</span>
                                <span class="badge bg-danger">{{ $insights['stock_distribution']['out_of_stock'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Good Stock</span>
                                <span class="badge bg-primary">{{ $insights['stock_distribution']['good_stock'] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Alerts</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Critical Stock</span>
                                <span class="badge bg-danger">{{ $insights['alerts']['critical_stock'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Overstock</span>
                                <span class="badge bg-info">{{ $insights['alerts']['overstock'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Value Products -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Top Value Products</h5>
                </div>
                <div class="card-body">
                    @if($insights['top_value_products']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($insights['top_value_products'] as $product)
                                        <tr>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                                            </td>
                                            <td>{{ $product->stock }}</td>
                                            <td>{{ $product->formatted_price }}</td>
                                            <td class="fw-bold">Rp {{ number_format($product->price * $product->stock, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No products available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="col-lg-4">
            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Products Added Today</span>
                        <span class="badge bg-success">{{ $recentActivity['products_added_today'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Products Updated Today</span>
                        <span class="badge bg-info">{{ $recentActivity['products_updated_today'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Low Stock Alerts</span>
                        <span class="badge bg-warning">{{ $recentActivity['low_stock_alerts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Out of Stock Alerts</span>
                        <span class="badge bg-danger">{{ $recentActivity['out_of_stock_alerts'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                        <a href="{{ route('products.list') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Products
                        </a>
                        <a href="{{ route('products.alerts') }}" class="btn btn-outline-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>View Alerts
                        </a>
                        <a href="{{ route('products.export') }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pricing Analysis Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Pricing Analysis</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="mb-0">{{ $pricingAnalysis['outliers']['expensive'] }}</h6>
                                <small class="text-muted">Overpriced</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-0">{{ $pricingAnalysis['outliers']['cheap'] }}</h6>
                            <small class="text-muted">Underpriced</small>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Min Price:</span>
                            <span>Rp {{ number_format($pricingAnalysis['statistics']['min'], 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Max Price:</span>
                            <span>Rp {{ number_format($pricingAnalysis['statistics']['max'], 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Average:</span>
                            <span>Rp {{ number_format($pricingAnalysis['statistics']['average'], 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Category Statistics</h5>
                </div>
                <div class="card-body">
                    @if(count($categoryStats) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Products</th>
                                        <th>Total Value</th>
                                        <th>Avg Price</th>
                                        <th>Inventory Value</th>
                                        <th>Stock Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryStats as $stat)
                                        <tr>
                                            <td><strong>{{ $stat['category'] }}</strong></td>
                                            <td>{{ $stat['total_products'] }}</td>
                                            <td>Rp {{ number_format($stat['total_value'], 0) }}</td>
                                            <td>Rp {{ number_format($stat['average_price'], 0) }}</td>
                                            <td>Rp {{ number_format($stat['inventory_value'], 0) }}</td>
                                            <td>
                                                @if($stat['out_of_stock'] > 0)
                                                    <span class="badge bg-danger">{{ $stat['out_of_stock'] }} Out</span>
                                                @endif
                                                @if($stat['low_stock'] > 0)
                                                    <span class="badge bg-warning">{{ $stat['low_stock'] }} Low</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No category data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Manage Orders - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold mb-0">Order Management</h1>
                    <p class="text-muted">Manage customer orders and track deliveries</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>Filter Orders
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">All Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Pending</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">Processing</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'shipped']) }}">Shipped</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'delivered']) }}">Delivered</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">Cancelled</a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', array_merge(request()->query(), ['export' => 'csv'])) }}">
                                <i class="fas fa-file-csv me-2"></i>Export as CSV
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index', array_merge(request()->query(), ['export' => 'excel'])) }}">
                                <i class="fas fa-file-excel me-2"></i>Export as Excel
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-search me-2"></i>Advanced Filters
                        <button class="btn btn-sm btn-outline-secondary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </h6>
                </div>
                <div class="collapse {{ request()->hasAny(['search', 'status', 'date_from', 'date_to', 'payment_method']) ? 'show' : '' }}" id="advancedFilters">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.orders.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Order number, customer name, email..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                        <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'payment_method']))
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>Clear Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-primary text-white">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-data">
                        <h3>{{ $stats['total_orders'] ?? 0 }}</h3>
                        <p>Total Orders</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-warning text-white">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-data">
                        <h3>{{ $stats['pending_orders'] ?? 0 }}</h3>
                        <p>Pending Orders</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-info text-white">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="stat-data">
                        <h3>{{ $stats['processing_orders'] ?? 0 }}</h3>
                        <p>Processing</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-data">
                        <h3>{{ number_format(($stats['total_revenue'] ?? 0) / 1000000, 1) }}M</h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtered Results Info -->
    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'payment_method']))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Showing {{ $filteredStats['filtered_count'] ?? 0 }} filtered results
                    @if(isset($filteredStats['filtered_revenue']))
                        with total revenue: <strong>Rp {{ number_format($filteredStats['filtered_revenue'], 0, ',', '.') }}</strong>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>All Orders
                                @if($orders->total() > 0)
                                    <span class="badge bg-primary">{{ $orders->total() }}</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" id="selectAllBtn">
                                    <i class="fas fa-check-square me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="bulkActionsBtn" disabled>
                                    <i class="fas fa-cogs me-1"></i>Bulk Actions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                                    </td>
                                    <td>
                                        <strong class="text-primary"># {{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->user->name }}</strong><br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $order->created_at->format('M d, Y') }}<br>
                                            {{ $order->created_at->format('g:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $order->items->count() }} items
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button"
                                                    class="btn btn-outline-secondary view-details-btn"
                                                    data-order-id="{{ $order->id }}"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('orders.show', $order) }}"
                                               class="btn btn-outline-primary"
                                               title="Full View">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-success dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        title="Change Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                        @if($status !== $order->status)
                                                            <li>
                                                                <form action="{{ route('admin.orders.update-status', $order) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('Change order status to {{ $status }}?')">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-circle text-{{ $statusColors[$status] ?? 'secondary' }} me-2"></i>
                                                                        {{ ucfirst($status) }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @if($order->status === 'pending')
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="if(confirm('Cancel this order?')) { document.getElementById('cancel-form-{{ $order->id }}').submit(); }"
                                                        title="Cancel Order">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <form id="cancel-form-{{ $order->id }}" action="{{ route('admin.orders.update-status', $order) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Order Details Row (Hidden by default) -->
                                <tr id="details-{{ $order->id }}" class="order-details d-none">
                                    <td colspan="9">
                                        <div class="bg-light p-4 rounded">
                                            <div class="row">
                                                <!-- Order Items -->
                                                <div class="col-md-8">
                                                    <h6 class="fw-bold mb-3">Order Items</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Price</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($order->items as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                @if($item->product_image_url)
                                                                                    <img src="{{ $item->product_image_url }}"
                                                                                         alt="{{ $item->product_name }}"
                                                                                         class="me-2"
                                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                                @endif
                                                                                <div>
                                                                                    <strong>{{ $item->product_name }}</strong>
                                                                                    @if($item->product_description)
                                                                                        <br><small class="text-muted">{{ Str::limit($item->product_description, 50) }}</small>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- Order Info -->
                                                <div class="col-md-4">
                                                    <h6 class="fw-bold mb-3">Order Information</h6>
                                                    <div class="small">
                                                        <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                                                        <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                                        <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                                                        <p><strong>Status:</strong>
                                                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                                {{ ucfirst($order->status) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Payment:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                                                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}</p>
                                                        @if($order->shipped_at)
                                                            <p><strong>Shipped:</strong> {{ $order->shipped_at->format('M d, Y g:i A') }}</p>
                                                        @endif
                                                        @if($order->delivered_at)
                                                            <p><strong>Delivered:</strong> {{ $order->delivered_at->format('M d, Y g:i A') }}</p>
                                                        @endif
                                                        <hr>
                                                        <p><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                                                        <p><strong>Tax:</strong> Rp {{ number_format($order->tax, 0, ',', '.') }}</p>
                                                        <p><strong>Shipping:</strong> Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                                        <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                                        @if($order->notes)
                                                            <hr>
                                                            <p><strong>Notes:</strong><br>{{ $order->notes }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 text-end">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary close-details-btn"
                                                        data-order-id="{{ $order->id }}">
                                                    <i class="fas fa-times me-1"></i>Close
                                                </button>
                                                <a href="{{ route('orders.show', $order) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>View Full Details
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                                </small>
                            </div>
                            <div>
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">No Orders Found</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['status', 'search', 'date_from', 'date_to', 'payment_method']))
                                No orders match your current filters. Try adjusting your search criteria.
                            @else
                                No orders have been placed yet.
                            @endif
                        </p>
                        @if(request()->hasAny(['status', 'search', 'date_from', 'date_to', 'payment_method']))
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.stat-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.stat-data h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stat-data p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.order-details {
    background-color: #f8f9fa;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle order details
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const detailsRow = document.getElementById('details-' + orderId);

            if (detailsRow.classList.contains('d-none')) {
                detailsRow.classList.remove('d-none');
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-secondary');
                this.title = 'Hide Details';
            } else {
                detailsRow.classList.add('d-none');
                this.innerHTML = '<i class="fas fa-eye"></i>';
                this.classList.remove('btn-secondary');
                this.classList.add('btn-outline-secondary');
                this.title = 'View Details';
            }
        });
    });

    // Close details
    document.querySelectorAll('.close-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const detailsRow = document.getElementById('details-' + orderId);
            const viewBtn = document.querySelector(`[data-order-id="${orderId}"].view-details-btn`);

            detailsRow.classList.add('d-none');
            viewBtn.innerHTML = '<i class="fas fa-eye"></i>';
            viewBtn.classList.remove('btn-secondary');
            viewBtn.classList.add('btn-outline-secondary');
            viewBtn.title = 'View Details';
        });
    });

    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActionsBtn = document.getElementById('bulkActionsBtn');

    selectAllCheckbox?.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActionsBtn();
    });

    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === orderCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < orderCheckboxes.length;
            updateBulkActionsBtn();
        });
    });

    function updateBulkActionsBtn() {
        const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
        bulkActionsBtn.disabled = checkedCount === 0;
        bulkActionsBtn.textContent = checkedCount > 0 ? `Bulk Actions (${checkedCount})` : 'Bulk Actions';
    }

    // Select All Button
    document.getElementById('selectAllBtn')?.addEventListener('click', function() {
        const allChecked = document.querySelectorAll('.order-checkbox:checked').length === orderCheckboxes.length;
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        selectAllCheckbox.checked = !allChecked;
        updateBulkActionsBtn();
    });
});
</script>
@endsection

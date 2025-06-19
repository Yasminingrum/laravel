{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Orders - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-1">Manage Orders</h1>
            <p class="text-muted mb-0">Overview and management of all customer orders</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary fs-6 px-3 py-2">Admin Panel</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-shopping-bag text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold">{{ $stats['total_orders'] }}</h3>
                        <p class="text-muted mb-0 small">Total Orders</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-clock text-warning fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold">{{ $stats['pending_orders'] }}</h3>
                        <p class="text-muted mb-0 small">Pending Orders</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-cog text-info fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold">{{ $stats['processing_orders'] }}</h3>
                        <p class="text-muted mb-0 small">Processing</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-dollar-sign text-success fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold">Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}M</h3>
                        <p class="text-muted mb-0 small">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Orders</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Order number, customer name...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date"
                               class="form-control"
                               id="date_from"
                               name="date_from"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date"
                               class="form-control"
                               id="date_to"
                               name="date_to"
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All Orders</h5>
                </div>
                <div class="col-auto">
                    <!-- Status Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                           class="btn {{ !request('status') || request('status') === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                            All
                        </a>
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'pending'])) }}"
                           class="btn {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'processing'])) }}"
                           class="btn {{ request('status') === 'processing' ? 'btn-info' : 'btn-outline-info' }}">
                            Processing
                        </a>
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'shipped'])) }}"
                           class="btn {{ request('status') === 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Shipped
                        </a>
                        <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => 'delivered'])) }}"
                           class="btn {{ request('status') === 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                            Delivered
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Order</th>
                                <th class="border-0">Customer</th>
                                <th class="border-0">Items</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="order-row" data-order-id="{{ $order->id }}">
                                    <td>
                                        <div>
                                            <strong>{{ $order->order_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-light text-dark me-2">{{ $order->items->count() }} items</span>
                                            <small class="text-muted">{{ $order->items->sum('quantity') }} total qty</small>
                                        </div>
                                        <div class="product-images mt-1">
                                            @foreach($order->items->take(2) as $item)
                                                @if($item->product_image_url)
                                                    <img src="{{ $item->product_image_url }}"
                                                         class="rounded me-1"
                                                         style="width: 30px; height: 30px; object-fit: cover;"
                                                         title="{{ $item->product_name }}">
                                                @endif
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                <span class="badge bg-secondary">+{{ $order->items->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $order->formatted_total }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->status_badge_class }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $order->created_at->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"
                                                    class="btn btn-outline-primary view-details-btn"
                                                    data-order-id="{{ $order->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button"
                                                        class="btn btn-outline-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    Status
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                        @if($status !== $order->status)
                                                            <li>
                                                                <form method="POST"
                                                                      action="{{ route('admin.orders.update-status', $order) }}"
                                                                      class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit"
                                                                            class="dropdown-item"
                                                                            onclick="return confirm('Change status to {{ ucfirst($status) }}?')">
                                                                        {{ ucfirst($status) }}
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Order Details Row (Hidden by default) -->
                                <tr id="details-{{ $order->id }}" class="order-details d-none">
                                    <td colspan="7">
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
                                                                                         class="rounded me-2"
                                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                                @endif
                                                                                <div>
                                                                                    <strong>{{ $item->product_name }}</strong>
                                                                                    <br>
                                                                                    <small class="text-muted">Electronics</small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                        <td>{{ $item->formatted_price }}</td>
                                                                        <td>{{ $item->formatted_total }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="mt-3">
                                                        <strong>Total: {{ $order->formatted_total }}</strong>
                                                    </div>
                                                </div>

                                                <!-- Shipping Info -->
                                                <div class="col-md-4">
                                                    <h6 class="fw-bold mb-3">Shipping Information</h6>
                                                    <div class="mb-3">
                                                        <strong>{{ $order->shipping_name }}</strong><br>
                                                        {{ $order->shipping_email }}<br>
                                                        {{ $order->shipping_phone }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <small class="text-muted">Address:</small><br>
                                                        {{ $order->shipping_address }}<br>
                                                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                                                        {{ $order->shipping_country }}
                                                    </div>
                                                    @if($order->notes)
                                                        <div class="mb-3">
                                                            <small class="text-muted">Notes:</small><br>
                                                            {{ $order->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-3 text-end">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary close-details-btn"
                                                        data-order-id="{{ $order->id }}">
                                                    Close
                                                </button>
                                                <a href="{{ route('orders.show', $order) }}"
                                                   class="btn btn-sm btn-primary">
                                                    View Full Details
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
                        <div class="d-flex justify-content-center">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No Orders Found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                            No orders match your current filters. Try adjusting your search criteria.
                        @else
                            No orders have been placed yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View order details functionality
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const detailsRow = document.getElementById('details-' + orderId);

            if (detailsRow.classList.contains('d-none')) {
                detailsRow.classList.remove('d-none');
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                detailsRow.classList.add('d-none');
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });

    // Close details functionality
    document.querySelectorAll('.close-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const detailsRow = document.getElementById('details-' + orderId);
            const viewBtn = document.querySelector(`.view-details-btn[data-order-id="${orderId}"]`);

            detailsRow.classList.add('d-none');
            viewBtn.innerHTML = '<i class="fas fa-eye"></i>';
        });
    });

    // Auto-submit filters on change
    document.getElementById('date_from').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('date_to').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Hover effects
    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });

        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
.order-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.product-images img {
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-details td {
    border-top: none !important;
}

.table th {
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.btn-group-sm .btn {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group {
        flex-direction: column;
    }

    .product-images img {
        width: 25px !important;
        height: 25px !important;
    }
}
</style>
@endsection

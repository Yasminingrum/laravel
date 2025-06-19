{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold mb-0">My Orders</h1>
                    <p class="text-muted">Track your orders and purchase history</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                    <a href="{{ route('products.list') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
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
                        <h3>{{ $stats['total_orders'] }}</h3>
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
                        <h3>{{ $stats['pending_orders'] }}</h3>
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
                        <h3>{{ $stats['processing_orders'] }}</h3>
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
                        <h3>{{ number_format($stats['total_spent'] / 1000, 1) }}K</h3>
                        <p>Total Spent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>Recent Orders
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">{{ $orders->total() }} total orders</small>
                        </div>
                    </div>
                </div>

                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-primary"># {{ $order->order_number }}</strong>
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
                                        <div class="btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary view-details-btn"
                                                    data-order-id="{{ $order->id }}">
                                                Details
                                            </button>
                                            <a href="{{ route('orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                            @if($order->status === 'delivered')
                                                <form action="{{ route('orders.reorder', $order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Reorder
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($order->status, ['pending', 'processing']))
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Order Details Row (Hidden by default) -->
                                <tr id="details-{{ $order->id }}" class="order-details d-none">
                                    <td colspan="6">
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
                                                                        <td>{{ $item->product_name }}</td>
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
                                                        <p><strong>Status:</strong>
                                                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                                {{ ucfirst($order->status) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}</p>
                                                        @if($order->shipped_at)
                                                            <p><strong>Shipped:</strong> {{ $order->shipped_at->format('M d, Y g:i A') }}</p>
                                                        @endif
                                                        @if($order->delivered_at)
                                                            <p><strong>Delivered:</strong> {{ $order->delivered_at->format('M d, Y g:i A') }}</p>
                                                        @endif
                                                        <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                                                    </div>
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
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="fas fa-inbox display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">No Orders Yet</h4>
                        <p class="text-muted">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="{{ route('products.list') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
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
                this.textContent = 'Hide';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-secondary');
            } else {
                detailsRow.classList.add('d-none');
                this.textContent = 'Details';
                this.classList.remove('btn-secondary');
                this.classList.add('btn-outline-secondary');
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
            viewBtn.textContent = 'Details';
            viewBtn.classList.remove('btn-secondary');
            viewBtn.classList.add('btn-outline-secondary');
        });
    });
});
</script>
@endsection

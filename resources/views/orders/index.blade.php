@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

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
                        <h3>{{ number_format($stats['total_revenue'] / 1000000, 1) }}M</h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Recent Orders
                                @if(request('status'))
                                    - {{ ucfirst(request('status')) }}
                                @endif
                            </h5>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Search orders..." id="searchOrders" style="width: 200px;">
                                <button class="btn btn-outline-primary btn-sm" onclick="refreshOrders()">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary">{{ $order->order_number }}</div>
                                                <small class="text-muted">{{ $order->payment_method }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2">
                                                        {{ substr($order->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $order->user->name }}</div>
                                                        <small class="text-muted">{{ $order->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ $order->items->count() }} items</div>
                                                <small class="text-muted">{{ $order->items->sum('quantity') }} units</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-success">{{ $order->formatted_total }}</div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $order->status_badge_class }} fs-6">
                                                    {{ $order->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Change Status">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($order->status !== 'pending')
                                                                <li>
                                                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="pending">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-clock text-warning me-2"></i>Set Pending
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            @if($order->status !== 'processing')
                                                                <li>
                                                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="processing">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-cog text-info me-2"></i>Set Processing
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            @if($order->status !== 'shipped')
                                                                <li>
                                                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="shipped">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-truck text-primary me-2"></i>Mark Shipped
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            @if($order->status !== 'delivered')
                                                                <li>
                                                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="delivered">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-check text-success me-2"></i>Mark Delivered
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            @if($order->status !== 'cancelled')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <input type="hidden" name="status" value="cancelled">
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                                            <i class="fas fa-times me-2"></i>Cancel Order
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">No Orders Found</h4>
                            <p class="text-muted">
                                @if(request('status'))
                                    No {{ request('status') }} orders at the moment.
                                @else
                                    No orders have been placed yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                @if($orders->hasPages())
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                            </div>
                            <div>
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchOrders').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const orderNumber = row.querySelector('td:first-child .fw-bold').textContent.toLowerCase();
            const customerName = row.querySelector('td:nth-child(2) .fw-medium').textContent.toLowerCase();
            const customerEmail = row.querySelector('td:nth-child(2) .text-muted').textContent.toLowerCase();

            if (orderNumber.includes(searchTerm) || customerName.includes(searchTerm) || customerEmail.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Refresh orders
    function refreshOrders() {
        location.reload();
    }

    // Auto-refresh every 30 seconds
    setInterval(function() {
        // Only refresh if no modals are open
        if (!document.querySelector('.modal.show')) {
            location.reload();
        }
    }, 30000);

    // Status update confirmation
    document.querySelectorAll('form[action*="update-status"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('input[name="status"]').value;
            const orderNumber = this.closest('tr').querySelector('.fw-bold').textContent;

            if (!confirm(`Are you sure you want to change order ${orderNumber} status to ${status}?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .stat-card {
        border-radius: 15px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .stat-data h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-data p {
        margin: 0;
        opacity: 0.9;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
        font-weight: 600;
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
        .stat-content {
            flex-direction: column;
            text-align: center;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group {
            flex-direction: column;
        }

        .stat-card {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

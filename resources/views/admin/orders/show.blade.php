@extends('layouts.app')

@section('title', 'Order Details - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold mb-1">Order Details</h1>
                    <p class="text-muted mb-0">Order #{{ $order->order_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-edit me-2"></i>Change Status
                        </button>
                        <ul class="dropdown-menu">
                            @php
                                $statusOptions = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            @foreach($statusOptions as $status)
                                @if($status !== $order->status)
                                    <li>
                                        <form action="{{ route('admin.orders.update-status', $order) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Change order status to {{ $status }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-circle text-{{ $statusColors[$status] }} me-2"></i>
                                                {{ ucfirst($status) }}
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-timeline me-2"></i>Order Timeline
                    </h6>
                    <div class="timeline">
                        <div class="timeline-item {{ $order->created_at ? 'completed' : '' }}">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                            <div class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Processing</h6>
                                <small class="text-muted">
                                    @if($order->status === 'processing' || in_array($order->status, ['shipped', 'delivered']))
                                        Order is being processed
                                    @else
                                        Waiting for processing
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                            <div class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Shipped</h6>
                                <small class="text-muted">
                                    @if($order->shipped_at)
                                        {{ $order->shipped_at->format('M d, Y g:i A') }}
                                    @elseif($order->status === 'shipped' || $order->status === 'delivered')
                                        Recently shipped
                                    @else
                                        Waiting for shipment
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="timeline-item {{ $order->status === 'delivered' ? 'completed' : '' }}">
                            <div class="timeline-marker {{ $order->status === 'delivered' ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Delivered</h6>
                                <small class="text-muted">
                                    @if($order->delivered_at)
                                        {{ $order->delivered_at->format('M d, Y g:i A') }}
                                    @elseif($order->status === 'delivered')
                                        Recently delivered
                                    @else
                                        Waiting for delivery
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Order Items
                        <span class="badge bg-primary">{{ $order->items->count() }} items</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Quantity</th>
                                    <th width="120">Unit Price</th>
                                    <th width="120">Total</th>
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
                                                         class="me-3 rounded"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                    @if($item->product_description)
                                                        <small class="text-muted">{{ Str::limit($item->product_description, 80) }}</small>
                                                    @endif
                                                    @if($item->product && $item->product->category)
                                                        <br><span class="badge bg-light text-dark">{{ $item->product->category->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-bold">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-bold text-success">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>Customer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Customer Details</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            @if($order->user->phone)
                                <p class="mb-3"><strong>Phone:</strong> {{ $order->user->phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Shipping Address</h6>
                            <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                            <p class="mb-1">{{ $order->shipping_country }}</p>
                            @if($order->shipping_phone)
                                <p class="mb-0"><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                            @endif
                        </div>
                    </div>
                    @if($order->notes)
                        <hr>
                        <h6 class="text-muted mb-2">Order Notes</h6>
                        <p class="mb-0">{{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <!-- Order Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Order Status
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6 px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="text-muted mb-0">
                        @switch($order->status)
                            @case('pending')
                                Order is waiting to be processed
                                @break
                            @case('processing')
                                Order is being prepared
                                @break
                            @case('shipped')
                                Order has been shipped
                                @break
                            @case('delivered')
                                Order has been delivered successfully
                                @break
                            @case('cancelled')
                                Order has been cancelled
                                @break
                            @default
                                Order status unknown
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Payment Method:</strong>
                        <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Payment Status:</strong>
                        @if($order->status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @elseif($order->status === 'delivered')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (11%):</span>
                        <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>
                            @if($order->shipping_cost > 0)
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @else
                                <span class="text-success">Free</span>
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <strong>Total:</strong>
                        <strong class="text-success fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->status === 'pending')
                        <button type="button" class="btn btn-info btn-sm w-100 mb-2"
                                onclick="updateOrderStatus('processing')">
                            <i class="fas fa-play me-2"></i>Start Processing
                        </button>
                    @endif

                    @if($order->status === 'processing')
                        <button type="button" class="btn btn-primary btn-sm w-100 mb-2"
                                onclick="updateOrderStatus('shipped')">
                            <i class="fas fa-shipping-fast me-2"></i>Mark as Shipped
                        </button>
                    @endif

                    @if($order->status === 'shipped')
                        <button type="button" class="btn btn-success btn-sm w-100 mb-2"
                                onclick="updateOrderStatus('delivered')">
                            <i class="fas fa-check-circle me-2"></i>Mark as Delivered
                        </button>
                    @endif

                    @if(in_array($order->status, ['pending', 'processing']))
                        <button type="button" class="btn btn-danger btn-sm w-100"
                                onclick="updateOrderStatus('cancelled')">
                            <i class="fas fa-times me-2"></i>Cancel Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-item.completed .timeline-marker {
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-item.completed .timeline-content h6 {
    color: #28a745;
}
</style>

<script>
function updateOrderStatus(status) {
    if (confirm(`Are you sure you want to change the order status to ${status}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.orders.update-status", $order) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';

        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);

        document.body.appendChild(form);
        form.submit();
    }
}

// Print functionality
window.addEventListener('beforeprint', function() {
    // Hide unnecessary elements when printing
    document.querySelectorAll('.btn, .dropdown').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Show elements after printing
    document.querySelectorAll('.btn, .dropdown').forEach(el => {
        el.style.display = '';
    });
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-6 fw-bold mb-1">Order Details</h1>
                    <p class="text-muted mb-0">Order #{{ $order->order_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to My Orders
                    </a>
                    @if($order->status === 'delivered')
                        <form action="{{ route('orders.reorder', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-redo me-2"></i>Reorder
                            </button>
                        </form>
                    @endif
                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to cancel this order?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-4">
                        <i class="fas fa-shipping-fast me-2"></i>Order Status
                    </h6>
                    <div class="order-status-timeline">
                        <div class="status-step {{ $order->created_at ? 'completed' : '' }}">
                            <div class="status-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="status-content">
                                <h6>Order Placed</h6>
                                <small>{{ $order->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>

                        <div class="status-step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : 'pending' }}">
                            <div class="status-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="status-content">
                                <h6>Processing</h6>
                                <small>
                                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                        Order is being processed
                                    @else
                                        Waiting for processing
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="status-step {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : 'pending' }}">
                            <div class="status-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="status-content">
                                <h6>Shipped</h6>
                                <small>
                                    @if($order->shipped_at)
                                        {{ $order->shipped_at->format('M d, Y g:i A') }}
                                    @elseif(in_array($order->status, ['shipped', 'delivered']))
                                        Recently shipped
                                    @else
                                        Waiting for shipment
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="status-step {{ $order->status === 'delivered' ? 'completed' : 'pending' }}">
                            <div class="status-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-content">
                                <h6>Delivered</h6>
                                <small>
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

                    @if($order->status === 'cancelled')
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This order has been cancelled.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>Items Ordered
                        <span class="badge bg-primary">{{ $order->items->count() }} items</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @foreach($order->items as $item)
                        <div class="border-bottom p-3 {{ $loop->last ? 'border-0' : '' }}">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        @if($item->product_image_url)
                                            <img src="{{ $item->product_image_url }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="me-3 rounded"
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            @if($item->product_description)
                                                <p class="text-muted small mb-2">{{ Str::limit($item->product_description, 100) }}</p>
                                            @endif
                                            @if($item->product && $item->product->category)
                                                <span class="badge bg-light text-dark">{{ $item->product->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="mb-1">
                                        <small class="text-muted">Quantity:</small>
                                        <strong class="ms-1">{{ $item->quantity }}</strong>
                                    </div>
                                    <div class="mb-1">
                                        <small class="text-muted">Unit Price:</small>
                                        <strong class="ms-1">Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted">Total:</small>
                                        <strong class="text-success ms-1">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Shipping Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Delivery Address</h6>
                            <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                            <p class="mb-0">{{ $order->shipping_country }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Contact Information</h6>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->shipping_email }}</p>
                            @if($order->shipping_phone)
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                            @endif
                            <p class="mb-0"><strong>Payment Method:</strong>
                                <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </p>
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

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <!-- Current Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Current Status
                    </h6>
                </div>
                <div class="card-body text-center">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'shipped' => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $statusMessages = [
                            'pending' => 'Your order is being reviewed and will be processed soon.',
                            'processing' => 'We are preparing your order for shipment.',
                            'shipped' => 'Your order is on its way to you!',
                            'delivered' => 'Your order has been delivered successfully.',
                            'cancelled' => 'This order has been cancelled.'
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6 px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="text-muted mb-0">{{ $statusMessages[$order->status] ?? 'Status information not available.' }}</p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Order Summary
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

            <!-- Order Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>Order Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Order Number</small>
                        <p class="mb-0 fw-bold">{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Order Date</small>
                        <p class="mb-0">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    @if($order->shipped_at)
                        <div class="mb-3">
                            <small class="text-muted">Shipped Date</small>
                            <p class="mb-0">{{ $order->shipped_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="mb-3">
                            <small class="text-muted">Delivered Date</small>
                            <p class="mb-0">{{ $order->delivered_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                    <div class="mb-0">
                        <small class="text-muted">Payment Method</small>
                        <p class="mb-0">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(in_array($order->status, ['delivered', 'pending', 'processing']))
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($order->status === 'delivered')
                            <form action="{{ route('orders.reorder', $order) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-redo me-2"></i>Reorder Items
                                </button>
                            </form>
                        @endif

                        @if(in_array($order->status, ['pending', 'processing']))
                            <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-times me-2"></i>Cancel Order
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Need Help Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-4">
                    <h6 class="mb-2">Need Help?</h6>
                    <p class="text-muted mb-3">If you have any questions about your order, please don't hesitate to contact us.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="mailto:support@tokosaya.com" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>Email Support
                        </a>
                        <a href="tel:+628123456789" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-phone me-2"></i>Call Us
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-status-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 2rem 0;
}

.order-status-timeline::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 50px;
    right: 50px;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.status-step {
    position: relative;
    flex: 1;
    text-align: center;
    z-index: 2;
}

.status-step.completed .status-icon {
    background: #28a745;
    color: white;
}

.status-step.pending .status-icon {
    background: #e9ecef;
    color: #6c757d;
}

.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content-center;
    margin: 0 auto 1rem;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-content h6 {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.status-content small {
    color: #6c757d;
    font-size: 0.8rem;
}

.status-step.completed .status-content h6 {
    color: #28a745;
}

@media (max-width: 768px) {
    .order-status-timeline {
        flex-direction: column;
        align-items: center;
    }

    .order-status-timeline::before {
        top: 25px;
        bottom: 25px;
        left: 50%;
        right: auto;
        width: 2px;
        height: auto;
    }

    .status-step {
        display: flex;
        align-items: center;
        text-align: left;
        margin-bottom: 2rem;
        width: 100%;
    }

    .status-step:last-child {
        margin-bottom: 0;
    }

    .status-icon {
        margin: 0 1rem 0 0;
        flex-shrink: 0;
    }

    .status-content {
        flex: 1;
    }
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
// Auto-refresh order status every 30 seconds for active orders
@if(in_array($order->status, ['pending', 'processing', 'shipped']))
setInterval(function() {
    // You can implement AJAX call here to check order status updates
    // For now, we'll just add a subtle indicator
    console.log('Checking order status...');
}, 30000);
@endif

// Print functionality
function printOrder() {
    window.print();
}

// Copy order number to clipboard
function copyOrderNumber() {
    navigator.clipboard.writeText('{{ $order->order_number }}').then(function() {
        // You can add a toast notification here
        alert('Order number copied to clipboard!');
    });
}
</script>
@endsection

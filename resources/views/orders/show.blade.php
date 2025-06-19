@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - Toko Saya')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
                    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold mb-1">Order Details</h1>
                    <p class="text-muted mb-0">Order {{ $order->order_number }}</p>
                </div>
                <div class="text-end">
                    <span class="badge {{ $order->status_badge_class }} fs-6 mb-2">{{ $order->status_label }}</span>
                    <br>
                    <span class="text-muted small">{{ $order->created_at->format('M d, Y - H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Order Items</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->items as $item)
                        <div class="border-bottom p-4">
                            <div class="row align-items-center">
                                <!-- Product Image -->
                                <div class="col-md-2">
                                    @if($item->product_image_url)
                                        <img src="{{ $item->product_image_url }}"
                                             class="img-fluid rounded"
                                             style="max-height: 80px; object-fit: cover;"
                                             alt="{{ $item->product_name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-5">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    @if($item->product_description)
                                        <p class="text-muted small mb-1">{{ Str::limit($item->product_description, 100) }}</p>
                                    @endif
                                    @if($item->product)
                                        <a href="{{ route('products.show', $item->product->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Product
                                        </a>
                                    @endif
                                </div>

                                <!-- Price & Quantity -->
                                <div class="col-md-2 text-center">
                                    <div class="fw-bold">{{ $item->formatted_price }}</div>
                                    <small class="text-muted">per item</small>
                                </div>

                                <div class="col-md-1 text-center">
                                    <div class="fw-bold">{{ $item->quantity }}</div>
                                    <small class="text-muted">qty</small>
                                </div>

                                <div class="col-md-2 text-end">
                                    <div class="fw-bold text-primary">{{ $item->formatted_total }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Recipient</h6>
                            <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                            <p class="mb-1">{{ $order->shipping_email }}</p>
                            <p class="mb-0">{{ $order->shipping_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Address</h6>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-0">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                            <p class="mb-0">{{ $order->shipping_country }}</p>
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
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ $order->formatted_subtotal }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong class="text-primary fs-5">{{ $order->formatted_total }}</strong>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Payment Method</h6>
                        <div class="d-flex align-items-center">
                            @switch($order->payment_method)
                                @case('bank_transfer')
                                    <i class="fas fa-university text-primary me-2"></i>
                                    <span>Bank Transfer</span>
                                    @break
                                @case('credit_card')
                                    <i class="fas fa-credit-card text-success me-2"></i>
                                    <span>Credit Card</span>
                                    @break
                                @case('e_wallet')
                                    <i class="fas fa-mobile-alt text-info me-2"></i>
                                    <span>E-Wallet</span>
                                    @break
                                @case('cod')
                                    <i class="fas fa-hand-holding-usd text-warning me-2"></i>
                                    <span>Cash on Delivery</span>
                                    @break
                                @default
                                    <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            @endswitch
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="d-grid gap-2">
                        @if($order->status === 'pending')
                            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="fas fa-times me-2"></i>Cancel Order
                                </button>
                            </form>
                        @endif

                        @if(in_array($order->status, ['delivered', 'cancelled']))
                            <form method="POST" action="{{ route('orders.reorder', $order) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-redo me-2"></i>Reorder Items
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Shopping Cart - Toko Saya')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6 fw-bold">Shopping Cart</h1>
                <a href="{{ route('products.list') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Cart Items ({{ $cartItems->count() }})</h5>
                            </div>
                            <div class="col-auto">
                                <form method="POST" action="{{ route('cart.clear') }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to clear your cart?')">
                                        <i class="fas fa-trash me-1"></i>Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="border-bottom p-4 cart-item" data-cart-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2">
                                        @if($item->product->image_url)
                                            <img src="{{ $item->product->image_url }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 80px; object-fit: cover;"
                                                 alt="{{ $item->product->name }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="col-md-4">
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product->id) }}"
                                               class="text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                        <br>
                                        <small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>In Stock ({{ $item->product->stock }} available)
                                        </small>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2 text-center">
                                        <div class="fw-bold">{{ $item->formatted_price }}</div>
                                        @if($item->price < $item->product->price)
                                            <small class="text-muted text-decoration-line-through">
                                                {{ $item->product->formatted_price }}
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-sm quantity-btn"
                                                    type="button"
                                                    data-action="decrease"
                                                    data-cart-id="{{ $item->id }}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number"
                                                   class="form-control form-control-sm text-center quantity-input"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   max="{{ $item->product->stock }}"
                                                   data-cart-id="{{ $item->id }}">
                                            <button class="btn btn-outline-secondary btn-sm quantity-btn"
                                                    type="button"
                                                    data-action="increase"
                                                    data-cart-id="{{ $item->id }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Total & Remove -->
                                    <div class="col-md-2 text-end">
                                        <div class="fw-bold cart-item-total">{{ $item->formatted_total }}</div>
                                        <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="d-inline mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 small"
                                                    onclick="return confirm('Remove this item from cart?')">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (11%)</span>
                            <span id="tax">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span id="shipping">
                                @if($shipping == 0)
                                    <span class="text-success">FREE</span>
                                @else
                                    Rp {{ number_format($shipping, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if($subtotal < 500000 && $subtotal > 0)
                            <div class="alert alert-info py-2 px-3 mb-3">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Add Rp {{ number_format(500000 - $subtotal, 0, ',', '.') }} more for FREE shipping!
                                </small>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong class="text-primary" id="total">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recommended Products -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">You might also like</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $categoryIds = $cartItems->pluck('product.category_id')->unique();
                            $recommended = App\Models\Product::whereIn('category_id', $categoryIds)
                                                           ->whereNotIn('id', $cartItems->pluck('product_id'))
                                                           ->inStock()
                                                           ->take(3)
                                                           ->get();
                        @endphp

                        @forelse($recommended as $product)
                            <div class="d-flex align-items-center mb-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         class="rounded me-3"
                                         style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small">{{ Str::limit($product->name, 30) }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold small">{{ $product->formatted_price }}</span>
                                        <button class="btn btn-outline-primary btn-sm quick-add-btn"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No recommendations available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart display-1 text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`input[data-cart-id="${cartId}"]`);
            let quantity = parseInt(input.value);

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            input.value = quantity;
            updateCartItem(cartId, quantity);
        });
    });

    // Direct quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const quantity = parseInt(this.value);

            if (quantity >= 1) {
                updateCartItem(cartId, quantity);
            }
        });
    });

    // Quick add for recommended products
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            quickAddToCart(productId, this);
        });
    });

    function updateCartItem(cartId, quantity) {
        fetch(`/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                document.querySelector(`.cart-item[data-cart-id="${cartId}"] .cart-item-total`).textContent = data.cart_total;

                // Update summary
                document.getElementById('subtotal').textContent = data.subtotal;
                document.getElementById('tax').textContent = data.tax;
                document.getElementById('shipping').innerHTML = data.shipping === 'Rp 0' ? '<span class="text-success">FREE</span>' : data.shipping;
                document.getElementById('total').textContent = data.total;

                // Update cart count in navbar
                updateCartBadge(data.cart_count);

                showToast('success', data.message);
            } else {
                showToast('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while updating cart');
        });
    }

    function quickAddToCart(productId, button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch('/cart/quick-add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartBadge(data.cart_count);
                showToast('success', data.message);
                button.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            } else {
                showToast('error', data.message);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    function updateCartBadge(count) {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function showToast(type, message) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
});
</script>
@endsection

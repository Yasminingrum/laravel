@extends('layouts.app')

@section('title', 'Shopping Cart - Toko Saya')

@section('content')
<div class="cart-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-title-section">
                        <h1 class="page-title">Shopping Cart</h1>
                        <p class="page-subtitle">Review your items before checkout</p>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
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
                    <div class="cart-items-section">
                        <div class="section-header">
                            <h3 class="section-title">Cart Items ({{ $cartItems->count() }})</h3>
                            <form method="POST" action="{{ route('cart.clear') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <i class="fas fa-trash me-1"></i>Clear Cart
                                </button>
                            </form>
                        </div>

                        <div class="cart-items-list">
                            @foreach($cartItems as $item)
                                <div class="cart-item" data-cart-id="{{ $item->id }}">
                                    <div class="cart-item-content">
                                        <!-- Product Image -->
                                        <div class="item-image">
                                            @if($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="product-image">
                                            @else
                                                <div class="product-image-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Details -->
                                        <div class="item-details">
                                            <div class="item-header">
                                                <h5 class="item-title">
                                                    <a href="{{ route('products.show', $item->product->id) }}">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h5>
                                                <div class="item-category">{{ $item->product->category->name }}</div>
                                            </div>

                                            <div class="item-info">
                                                <div class="item-availability">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    In Stock ({{ $item->product->stock }} available)
                                                </div>
                                                <div class="item-price">
                                                    <span class="price-label">Unit Price:</span>
                                                    <span class="price-value">{{ $item->formatted_price }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="item-quantity">
                                            <label class="quantity-label">Quantity</label>
                                            <div class="quantity-controls">
                                                <button class="quantity-btn quantity-decrease"
                                                        type="button"
                                                        data-cart-id="{{ $item->id }}">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number"
                                                       class="quantity-input"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       max="{{ $item->product->stock }}"
                                                       data-cart-id="{{ $item->id }}">
                                                <button class="quantity-btn quantity-increase"
                                                        type="button"
                                                        data-cart-id="{{ $item->id }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Item Total & Actions -->
                                        <div class="item-actions">
                                            <div class="item-total">
                                                <span class="total-label">Total</span>
                                                <span class="total-value cart-item-total">{{ $item->formatted_total }}</span>
                                            </div>
                                            <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="remove-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger remove-btn"
                                                        onclick="return confirm('Remove this item from cart?')">
                                                    <i class="fas fa-trash"></i>
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
                    <div class="order-summary-card">
                        <div class="summary-header">
                            <h4 class="summary-title">Order Summary</h4>
                        </div>

                        <div class="summary-content">
                            <!-- Items Summary -->
                            <div class="summary-item">
                                <span class="summary-label">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                <span class="summary-value" id="subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">Tax (11%)</span>
                                <span class="summary-value" id="tax">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="summary-item">
                                <span class="summary-label">Shipping</span>
                                <span class="summary-value" id="shipping">
                                    @if($shipping == 0)
                                        <span class="text-success fw-bold">FREE</span>
                                    @else
                                        Rp {{ number_format($shipping, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            <!-- Shipping Notice -->
                            @if($subtotal < 500000 && $subtotal > 0)
                                <div class="shipping-notice">
                                    <div class="notice-content">
                                        <i class="fas fa-truck me-2"></i>
                                        <div>
                                            <strong>Free shipping available!</strong>
                                            <p class="notice-text">Add Rp {{ number_format(500000 - $subtotal, 0, ',', '.') }} more to qualify for free shipping</p>
                                        </div>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-success"
                                             style="width: {{ ($subtotal / 500000) * 100 }}%"></div>
                                    </div>
                                </div>
                            @elseif($subtotal >= 500000)
                                <div class="shipping-notice success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span><strong>You qualify for free shipping!</strong></span>
                                </div>
                            @endif

                            <div class="summary-divider"></div>

                            <!-- Total -->
                            <div class="summary-total">
                                <span class="total-label">Total</span>
                                <span class="total-value" id="total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <!-- Checkout Button -->
                            <div class="checkout-actions">
                                <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg w-100 checkout-btn">
                                    <i class="fas fa-lock me-2"></i>
                                    Proceed to Checkout
                                </a>

                                <!-- Security Notice -->
                                <div class="security-notice">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    <span>Secure checkout guaranteed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended Products -->
                    <div class="recommendations-card">
                        <h5 class="recommendations-title">You might also like</h5>
                        <div class="recommendations-list">
                            @php
                                $categoryIds = $cartItems->pluck('product.category_id')->unique();
                                $recommended = App\Models\Product::whereIn('category_id', $categoryIds)
                                                                 ->whereNotIn('id', $cartItems->pluck('product_id'))
                                                                 ->inStock()
                                                                 ->take(3)
                                                                 ->get();
                            @endphp

                            @forelse($recommended as $product)
                                <div class="recommendation-item">
                                    <div class="rec-image">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}"
                                                 alt="{{ $product->name }}"
                                                 class="rec-product-image">
                                        @else
                                            <div class="rec-image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="rec-details">
                                        <h6 class="rec-title">{{ Str::limit($product->name, 40) }}</h6>
                                        <div class="rec-footer">
                                            <span class="rec-price">{{ $product->formatted_price }}</span>
                                            <button class="btn btn-sm btn-outline-primary quick-add-btn"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small">No recommendations available at the moment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="empty-cart-section">
                <div class="empty-cart-content">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-title">Your cart is empty</h3>
                    <p class="empty-description">
                        Looks like you haven't added any items to your cart yet.
                        Start shopping to fill it up!
                    </p>
                    <div class="empty-actions">
                        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .cart-page {
        padding: 2rem 0;
        min-height: 60vh;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1.125rem;
        margin: 0;
    }

    /* Cart Items Section */
    .cart-items-section {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .section-title {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .cart-items-list {
        padding: 0;
    }

    .cart-item {
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item:hover {
        background-color: var(--light-gray);
    }

    .cart-item-content {
        display: grid;
        grid-template-columns: 120px 1fr auto auto auto;
        gap: 1.5rem;
        padding: 1.5rem;
        align-items: center;
    }

    /* Item Image */
    .item-image {
        width: 100px;
        height: 100px;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-image-placeholder {
        width: 100%;
        height: 100%;
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 1.5rem;
    }

    /* Item Details */
    .item-details {
        min-width: 0;
    }

    .item-header {
        margin-bottom: 0.75rem;
    }

    .item-title {
        margin: 0 0 0.25rem 0;
        font-size: 1.125rem;
        font-weight: 600;
    }

    .item-title a {
        color: var(--text-primary);
        text-decoration: none;
    }

    .item-title a:hover {
        color: var(--primary);
    }

    .item-category {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .item-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .item-availability {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .item-price {
        font-size: 0.875rem;
    }

    .price-label {
        color: var(--text-secondary);
        margin-right: 0.5rem;
    }

    .price-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Quantity Controls */
    .item-quantity {
        text-align: center;
    }

    .quantity-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        width: fit-content;
    }

    .quantity-btn {
        background: var(--light-gray);
        border: none;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .quantity-btn:hover {
        background: var(--medium-gray);
        color: var(--text-primary);
    }

    .quantity-input {
        border: none;
        width: 60px;
        height: 36px;
        text-align: center;
        font-weight: 500;
        background: white;
    }

    .quantity-input:focus {
        outline: none;
    }

    /* Item Actions */
    .item-actions {
        text-align: center;
    }

    .item-total {
        margin-bottom: 1rem;
    }

    .total-label {
        display: block;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .total-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--accent);
    }

    .remove-btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Order Summary */
    .order-summary-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        position: sticky;
        top: 2rem;
    }

    .summary-header {
        background: var(--primary);
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .summary-title {
        margin: 0;
        font-weight: 600;
    }

    .summary-content {
        padding: 1.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .summary-label {
        color: var(--text-secondary);
    }

    .summary-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Shipping Notice */
    .shipping-notice {
        background: var(--light-gray);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        padding: 1rem;
        margin: 1.5rem 0;
    }

    .shipping-notice.success {
        background: var(--accent-light);
        border-color: var(--accent);
        color: #065f46;
        display: flex;
        align-items: center;
    }

    .notice-content {
        display: flex;
        align-items: flex-start;
    }

    .notice-text {
        margin: 0;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .summary-divider {
        height: 1px;
        background: var(--border-color);
        margin: 1.5rem 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: var(--border-radius-sm);
    }

    .summary-total .total-label {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .summary-total .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }

    .checkout-btn {
        margin-bottom: 1rem;
    }

    .security-notice {
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Recommendations */
    .recommendations-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
    }

    .recommendations-title {
        margin-bottom: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .recommendation-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .recommendation-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .rec-image {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .rec-product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rec-image-placeholder {
        width: 100%;
        height: 100%;
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
    }

    .rec-details {
        flex: 1;
        min-width: 0;
    }

    .rec-title {
        margin: 0 0 0.5rem 0;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.3;
    }

    .rec-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .rec-price {
        font-weight: 600;
        color: var(--accent);
        font-size: 0.875rem;
    }

    /* Empty Cart */
    .empty-cart-section {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 50vh;
    }

    .empty-cart-content {
        text-align: center;
        max-width: 500px;
        padding: 2rem;
    }

    .empty-icon {
        font-size: 5rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        opacity: 0.6;
    }

    .empty-title {
        font-size: 2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .empty-description {
        color: var(--text-secondary);
        font-size: 1.125rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .order-summary-card {
            position: static;
        }
    }

    @media (max-width: 992px) {
        .cart-item-content {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .item-quantity,
        .item-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .item-quantity {
            border-top: none;
            margin-top: 0;
            padding-top: 0;
        }
    }

    @media (max-width: 768px) {
        .cart-page {
            padding: 1rem 0;
        }

        .page-title {
            font-size: 2rem;
        }

        .page-header {
            text-align: center;
        }

        .cart-item-content {
            grid-template-columns: 1fr;
            gap: 1rem;
            text-align: center;
        }

        .item-image {
            justify-self: center;
        }

        .item-details {
            text-align: left;
        }

        .quantity-controls {
            margin: 0 auto;
        }
    }

    @media (max-width: 576px) {
        .summary-content {
            padding: 1rem;
        }

        .recommendations-card {
            padding: 1rem;
        }

        .empty-cart-content {
            padding: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    document.querySelectorAll('.quantity-decrease, .quantity-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`input[data-cart-id="${cartId}"]`);
            let quantity = parseInt(input.value);

            if (this.classList.contains('quantity-increase')) {
                quantity++;
            } else if (this.classList.contains('quantity-decrease') && quantity > 1) {
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
            const originalHtml = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('{{ route("cart.quick-add") }}', {
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
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.add('btn-success');
                    this.classList.remove('btn-outline-primary');

                    showToast('success', data.message);

                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-outline-primary');
                        this.disabled = false;
                    }, 2000);
                } else {
                    showToast('error', data.message);
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred');
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
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
                document.getElementById('shipping').innerHTML = data.shipping === 'Rp 0' ? '<span class="text-success fw-bold">FREE</span>' : data.shipping;
                document.getElementById('total').textContent = data.total;

                // Update cart badge
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

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(toast);
        setTimeout(() => {
            if (toast.parentNode) {
                bootstrap.Alert.getOrCreateInstance(toast).close();
            }
        }, 4000);
    }
});
</script>
@endsection

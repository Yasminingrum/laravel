@extends('layouts.app')

@section('title', 'Checkout - Toko Saya')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>

            <h1 class="display-6 fw-bold mb-4">
                <i class="fas fa-credit-card me-2"></i>Checkout
            </h1>

            @guest
                <!-- Guest User Info Box -->
                <div class="alert alert-info mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Two ways to continue:
                            </h5>
                            <p class="mb-1"><strong>Option 1:</strong> Click "Login First" to login immediately and return to checkout.</p>
                            <p class="mb-0"><strong>Option 2:</strong> Fill the form below, then click "Fill Form & Continue" to save your info and login.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}?checkout=1" class="btn btn-success">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login First
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Left Column - Shipping Information -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('shipping_name') is-invalid @enderror"
                                           id="shipping_name"
                                           name="shipping_name"
                                           value="{{ old('shipping_name', $user->name ?? '') }}"
                                           required
                                           placeholder="Enter your full name">
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('shipping_email') is-invalid @enderror"
                                           id="shipping_email"
                                           name="shipping_email"
                                           value="{{ old('shipping_email', $user->email ?? '') }}"
                                           required
                                           placeholder="Enter your email">
                                    @error('shipping_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel"
                                           class="form-control @error('shipping_phone') is-invalid @enderror"
                                           id="shipping_phone"
                                           name="shipping_phone"
                                           value="{{ old('shipping_phone', $user->phone ?? '') }}"
                                           required
                                           placeholder="Enter your phone number">
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-select @error('shipping_country') is-invalid @enderror"
                                            id="shipping_country"
                                            name="shipping_country"
                                            required>
                                        <option value="Indonesia" {{ old('shipping_country', 'Indonesia') === 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Malaysia" {{ old('shipping_country') === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Singapore" {{ old('shipping_country') === 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                        <option value="Thailand" {{ old('shipping_country') === 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Philippines" {{ old('shipping_country') === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                    </select>
                                    @error('shipping_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('shipping_city') is-invalid @enderror"
                                           id="shipping_city"
                                           name="shipping_city"
                                           value="{{ old('shipping_city', $user->city ?? '') }}"
                                           required
                                           placeholder="Enter your city">
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('shipping_postal_code') is-invalid @enderror"
                                           id="shipping_postal_code"
                                           name="shipping_postal_code"
                                           value="{{ old('shipping_postal_code', $user->postal_code ?? '') }}"
                                           required
                                           placeholder="Enter postal code">
                                    @error('shipping_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                                      id="shipping_address"
                                      name="shipping_address"
                                      rows="3"
                                      placeholder="Enter your complete street address"
                                      required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control"
                                      id="notes"
                                      name="notes"
                                      rows="2"
                                      placeholder="Any special instructions for your order">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Payment Method
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check payment-option p-3 border rounded">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="bank_transfer"
                                           value="bank_transfer"
                                           {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="bank_transfer">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-university text-primary fa-2x me-3"></i>
                                            <div>
                                                <strong>Bank Transfer</strong>
                                                <small class="d-block text-muted">Pay via bank transfer</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check payment-option p-3 border rounded">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="credit_card"
                                           value="credit_card"
                                           {{ old('payment_method') === 'credit_card' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="credit_card">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-credit-card text-success fa-2x me-3"></i>
                                            <div>
                                                <strong>Credit Card</strong>
                                                <small class="d-block text-muted">Visa, MasterCard, etc.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check payment-option p-3 border rounded">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="e_wallet"
                                           value="e_wallet"
                                           {{ old('payment_method') === 'e_wallet' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="e_wallet">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-mobile-alt text-info fa-2x me-3"></i>
                                            <div>
                                                <strong>E-Wallet</strong>
                                                <small class="d-block text-muted">GoPay, OVO, Dana</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check payment-option p-3 border rounded">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="cod"
                                           value="cod"
                                           {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="cod">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hand-holding-usd text-warning fa-2x me-3"></i>
                                            <div>
                                                <strong>Cash on Delivery</strong>
                                                <small class="d-block text-muted">Pay when item arrives</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm checkout-summary">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="order-items mb-3">
                            <h6 class="text-muted mb-3">Items ({{ $cartItems->count() }})</h6>
                            <div class="max-height-items">
                                @foreach($cartItems as $item)
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="flex-shrink-0">
                                            @if($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="rounded"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 small">{{ Str::limit($item->product->name, 30) }}</h6>
                                            <div class="text-muted small">
                                                Qty: {{ $item->quantity }} × {{ $item->formatted_price }}
                                            </div>
                                            <div class="fw-bold text-primary small">{{ $item->formatted_total }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Order Totals -->
                        <div class="order-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (11%)</span>
                                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Shipping</span>
                                <span>
                                    @if($shipping > 0)
                                        Rp {{ number_format($shipping, 0, ',', '.') }}
                                    @else
                                        <span class="text-success">FREE</span>
                                    @endif
                                </span>
                            </div>

                            @if($shipping === 0)
                                <div class="alert alert-success small py-2 mb-3">
                                    <i class="fas fa-check-circle me-1"></i>
                                    You qualify for FREE shipping!
                                </div>
                            @endif

                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>

                            <!-- Action Buttons -->
                            @guest
                                <div class="d-grid gap-2">
                                    <button type="button"
                                            class="btn btn-primary btn-lg"
                                            id="continueWithFormBtn">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Fill Form & Continue
                                    </button>
                                </div>
                            @else
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                            class="btn btn-success btn-lg"
                                            id="placeOrderBtn">
                                        <i class="fas fa-lock me-2"></i>
                                        Place Order
                                    </button>
                                </div>
                            @endauth

                            <!-- Security Notice -->
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Secure checkout guaranteed
                                </small>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-link">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Cart
                            </a>
                        </div>

                        @guest
                            <!-- Quick Register Option -->
                            <div class="mt-3 text-center">
                                <hr>
                                <p class="small text-muted mb-2">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-plus me-1"></i>Create Account
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const continueWithFormBtn = document.getElementById('continueWithFormBtn');
    const placeOrderBtn = document.getElementById('placeOrderBtn');

    console.log('Checkout form initialized');
    console.log('Form:', form);
    console.log('Continue button:', continueWithFormBtn);
    console.log('Place order button:', placeOrderBtn);

    // Handle form-based continue for guests
    @guest
        if (continueWithFormBtn) {
            continueWithFormBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Continue with form button clicked');

                // Check if required fields are filled
                const requiredFields = [
                    'shipping_name', 'shipping_email', 'shipping_phone',
                    'shipping_address', 'shipping_city', 'shipping_postal_code'
                ];

                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (!field || !field.value.trim()) {
                        if (field) field.classList.add('is-invalid');
                        if (!firstInvalidField && field) {
                            firstInvalidField = field;
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Check payment method
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    isValid = false;
                    alert('Please select a payment method.');
                    return;
                }

                if (!isValid) {
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    alert('Please fill in all required shipping information before continuing.');
                    return;
                }

                // If validation passes, submit form to store data and redirect to login
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Redirecting to Login...';

                // Submit the form which will store data in session and redirect to login
                console.log('Submitting form for guest user');
                form.submit();
            });
        }
    @else
        // ✅ FIXED: For authenticated users - Complete implementation
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default form submission
                console.log('Place order button clicked');

                // Validate required fields before submitting
                const requiredFields = [
                    'shipping_name', 'shipping_email', 'shipping_phone',
                    'shipping_address', 'shipping_city', 'shipping_postal_code'
                ];

                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    console.log(`Checking field ${fieldName}:`, field ? field.value : 'not found');

                    if (!field || !field.value.trim()) {
                        if (field) field.classList.add('is-invalid');
                        if (!firstInvalidField && field) {
                            firstInvalidField = field;
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Check payment method
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                console.log('Payment method selected:', paymentMethod ? paymentMethod.value : 'none');

                if (!paymentMethod) {
                    isValid = false;
                    alert('Please select a payment method.');
                    return;
                }

                console.log('Form validation result:', isValid);

                if (!isValid) {
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    alert('Please fill in all required information before placing order.');
                    return;
                }

                // If validation passes, change button state and submit form
                console.log('Validation passed, processing order...');
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Order...';

                // ✅ Submit form after validation
                console.log('Submitting form...');
                setTimeout(() => {
                    if (form) {
                        console.log('Form found, submitting now');
                        form.submit();
                    } else {
                        console.error('Form not found!');
                    }
                }, 100); // Small delay to ensure button state is updated
            });
        } else {
            console.error('Place order button not found!');
        }
    @endauth

    // Auto-restore form data from localStorage if available
    @guest
        window.addEventListener('load', function() {
            try {
                const savedData = localStorage.getItem('checkout_form_data');
                if (savedData) {
                    console.log('Restoring form data from localStorage');
                    const formData = JSON.parse(savedData);

                    // Fill form fields
                    Object.keys(formData).forEach(key => {
                        const field = document.getElementById(key);
                        if (field && formData[key]) {
                            if (field.type === 'radio') {
                                const radio = document.querySelector(`input[name="${key}"][value="${formData[key]}"]`);
                                if (radio) radio.checked = true;
                            } else {
                                field.value = formData[key];
                            }
                        }
                    });

                    // Clear saved data after restoring
                    localStorage.removeItem('checkout_form_data');
                }
            } catch (e) {
                console.log('Could not restore form data:', e);
            }
        });
    @endguest

    // Form validation enhancement
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');

    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Payment method selection highlight
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove highlight from all payment options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('border-primary', 'bg-light');
            });

            // Add highlight to selected option
            if (this.checked) {
                this.closest('.payment-option').classList.add('border-primary', 'bg-light');
            }
        });
    });

    // Initialize selected payment method highlight
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
    if (selectedPayment) {
        selectedPayment.closest('.payment-option').classList.add('border-primary', 'bg-light');
    }
});
</script>
@endsection

@section('styles')
<style>
.payment-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-option:hover {
    background-color: #f8f9fa !important;
    border-color: #0d6efd !important;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
}

.checkout-summary {
    position: relative;
}

.max-height-items {
    max-height: 300px;
    overflow-y: auto;
}

.max-height-items::-webkit-scrollbar {
    width: 4px;
}

.max-height-items::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.max-height-items::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.max-height-items::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.order-items .border-bottom:last-child {
    border-bottom: none !important;
}

@media (max-width: 991px) {
    .checkout-summary {
        margin-top: 2rem;
    }
}

@media (max-width: 768px) {
    .payment-option {
        margin-bottom: 1rem;
    }

    .max-height-items {
        max-height: 200px;
    }

    .card-body {
        padding: 1rem;
    }
}

/* Loading state for buttons */
.btn:disabled {
    opacity: 0.8;
    cursor: not-allowed;
}

/* Alert animations */
.alert {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
@endsection

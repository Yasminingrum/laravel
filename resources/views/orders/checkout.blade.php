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

            <h1 class="display-6 fw-bold mb-4">Checkout</h1>

            @guest
                <!-- Guest User Info Box -->
                <div class="alert alert-info mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Two ways to continue:
                            </h5>
                            <p class="mb-0"><strong>Option 1:</strong> Click "Login First" to login immediately and return to checkout.</p>
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
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Shipping Information</h5>
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
                                           required>
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
                                           required>
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
                                           required>
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
                                    </select>
                                    @error('shipping_country')
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
                                      placeholder="Enter your complete address"
                                      required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('shipping_city') is-invalid @enderror"
                                           id="shipping_city"
                                           name="shipping_city"
                                           value="{{ old('shipping_city') }}"
                                           required>
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
                                           value="{{ old('shipping_postal_code') }}"
                                           required>
                                    @error('shipping_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" {{ old('payment_method', 'bank_transfer') === 'bank_transfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university text-primary me-2"></i>
                                        <strong>Bank Transfer</strong>
                                        <br>
                                        <small class="text-muted">Transfer to our bank account</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" {{ old('payment_method') === 'credit_card' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="fas fa-credit-card text-success me-2"></i>
                                        <strong>Credit Card</strong>
                                        <br>
                                        <small class="text-muted">Visa, MasterCard, AMEX</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="e_wallet" value="e_wallet" {{ old('payment_method') === 'e_wallet' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="e_wallet">
                                        <i class="fas fa-mobile-alt text-info me-2"></i>
                                        <strong>E-Wallet</strong>
                                        <br>
                                        <small class="text-muted">OVO, GoPay, DANA</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-hand-holding-usd text-warning me-2"></i>
                                        <strong>Cash on Delivery</strong>
                                        <br>
                                        <small class="text-muted">Pay when item arrives</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="Any special instructions for your order? (Optional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary - FIXED: Removed sticky-top -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm checkout-summary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-3">Items ({{ $cartItems->count() }})</h6>
                            <div class="max-height-items">
                                @foreach($cartItems as $item)
                                    <div class="d-flex align-items-center mb-2">
                                        @if($item->product->image_url)
                                            <img src="{{ $item->product->image_url }}"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;"
                                                 alt="{{ $item->product->name }}">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-image text-muted small"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">{{ Str::limit($item->product->name, 25) }}</div>
                                            <div class="small text-muted">{{ $item->quantity }}x {{ $item->formatted_price }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="small fw-bold">{{ $item->formatted_total }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <!-- Calculation -->
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
                                @if($shipping == 0)
                                    <span class="text-success">FREE</span>
                                @else
                                    Rp {{ number_format($shipping, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>

                        @if($subtotal >= 500000)
                            <div class="alert alert-success py-2 px-3 mb-3">
                                <small>
                                    <i class="fas fa-check-circle me-1"></i>
                                    You qualify for FREE shipping!
                                </small>
                            </div>
                        @else
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
                            <strong class="text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>

                        <!-- Place Order Button -->
                        <div class="d-grid">
                            @auth
                                <button type="submit" class="btn btn-primary btn-lg" id="placeOrderBtn">
                                    <i class="fas fa-lock me-2"></i>
                                    Place Order
                                </button>
                            @else
                                <!-- Two options for guests -->
                                <a href="{{ route('login') }}?checkout=1" class="btn btn-success btn-lg mb-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Login First
                                </a>
                                <button type="button" class="btn btn-outline-primary btn-lg" id="continueWithFormBtn">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Fill Form & Continue
                                </button>
                                <small class="text-muted text-center mt-2 d-block">
                                    Choose "Login First" for quick access, or fill the form above and then login
                                </small>
                            @endauth
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

    // Handle form-based continue for guests
    @guest
        if (continueWithFormBtn) {
            continueWithFormBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Check if required fields are filled
                const requiredFields = [
                    'shipping_name', 'shipping_email', 'shipping_phone',
                    'shipping_address', 'shipping_city', 'shipping_postal_code'
                ];

                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) {
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
                form.submit();
            });
        }
    @else
        // For authenticated users
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function(e) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Order...';
            });
        }
    @endauth

    // Auto-restore form data from localStorage if available
    @guest
        window.addEventListener('load', function() {
            try {
                const savedData = localStorage.getItem('checkout_form_data');
                if (savedData) {
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
                console.log('Could not restore form data');
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
            document.querySelectorAll('.form-check').forEach(check => {
                check.classList.remove('border', 'border-primary', 'bg-light');
            });

            // Highlight selected payment option
            if (this.checked) {
                this.closest('.form-check').classList.add('border', 'border-primary', 'bg-light');
            }
        });
    });

    // Initialize selected payment method highlight
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
    if (selectedPayment) {
        selectedPayment.closest('.form-check').classList.add('border', 'border-primary', 'bg-light');
    }
});
</script>
@endsection

@section('styles')
<style>
.form-check {
    padding: 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
}

/* FIXED: Remove sticky behavior and add responsive design */
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
}

.max-height-items::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

@media (max-width: 991px) {
    .checkout-summary {
        margin-top: 2rem;
    }
}

@media (max-width: 768px) {
    .form-check {
        margin-bottom: 1rem;
    }

    .max-height-items {
        max-height: 200px;
    }
}
</style>
@endsection

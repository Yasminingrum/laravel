@extends('layouts.app')

@section('title', 'My Profile - Toko Saya')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>

            <h1 class="display-6 fw-bold mb-4">My Profile</h1>
        </div>
    </div>

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Type</label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ ucfirst($user->role) }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Account Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-muted"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="fw-bold fs-5">{{ $user->created_at->format('M Y') }}</div>
                                <small class="text-muted">Member Since</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold fs-5 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <small class="text-muted">Verified</small>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->role === 'customer')
                <!-- Customer Stats -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Shopping Statistics</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $orderCount = $user->orders()->count();
                            $cartItems = $user->getCartItemsCount();
                        @endphp

                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fw-bold text-primary">{{ $orderCount }}</div>
                                <small class="text-muted">Orders</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-success">{{ $cartItems }}</div>
                                <small class="text-muted">In Cart</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-info">{{ $user->created_at->diffForHumans() }}</div>
                                <small class="text-muted">Joined</small>
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-shopping-bag me-1"></i>My Orders
                            </a>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-shopping-cart me-1"></i>My Cart
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title">Need Help?</h6>
                    <p class="card-text small text-muted mb-3">
                        Contact our support team if you need assistance with your account.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="mailto:support@tokosaya.com" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-1"></i>Email Support
                        </a>
                        <a href="tel:+621234567890" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-phone me-1"></i>Call Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Login - Toko Saya')

@section('content')
<div class="auth-container">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side - Branding -->
            <div class="col-lg-6 d-none d-lg-flex auth-branding">
                <div class="branding-content">
                    <div class="branding-header">
                        <div class="brand-logo">
                            <i class="fas fa-store-alt"></i>
                        </div>
                        <h1 class="brand-title">Toko Saya</h1>
                        <p class="brand-subtitle">Your trusted online marketplace</p>
                    </div>

                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="feature-text">
                                <h6>Secure Shopping</h6>
                                <p>Your data and transactions are protected with advanced security</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <div class="feature-text">
                                <h6>Fast Delivery</h6>
                                <p>Get your orders delivered quickly to your doorstep</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="feature-text">
                                <h6>24/7 Support</h6>
                                <p>Our customer service team is always ready to help</p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-container">
                        <div class="stat-item">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Products</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">99.9%</div>
                            <div class="stat-label">Uptime</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-lg-6 auth-form-section">
                <div class="auth-form-container">
                    <!-- Mobile Brand Header -->
                    <div class="mobile-brand d-lg-none">
                        <div class="brand-logo-small">
                            <i class="fas fa-store-alt"></i>
                        </div>
                        <h2 class="brand-title-small">Toko Saya</h2>
                    </div>

                    <div class="auth-form-wrapper">
                        <!-- Checkout Specific Message -->
                        @if(request('checkout') == '1' || session('checkout_pending'))
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shopping-cart fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Complete Your Purchase</h6>
                                        <p class="mb-0">Login to continue with your checkout. Your cart is waiting!</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="auth-header">
                            <h2 class="auth-title">
                                @if(request('checkout') == '1' || session('checkout_pending'))
                                    Login to Complete Order
                                @else
                                    Welcome Back!
                                @endif
                            </h2>
                            <p class="auth-subtitle">
                                @if(request('checkout') == '1' || session('checkout_pending'))
                                    Sign in to finish your purchase
                                @else
                                    Sign in to your account to continue shopping
                                @endif
                            </p>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="social-login mb-4">
                            <button type="button" class="btn btn-social btn-google">
                                <i class="fab fa-google me-2"></i>
                                Continue with Google
                            </button>
                            <button type="button" class="btn btn-social btn-facebook">
                                <i class="fab fa-facebook-f me-2"></i>
                                Continue with Facebook
                            </button>
                        </div>

                        <div class="divider">
                            <span>or sign in with email</span>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
                            @csrf

                            <!-- Hidden field for checkout redirect -->
                            @if(request('checkout') == '1')
                                <input type="hidden" name="redirect_to" value="{{ route('checkout') }}">
                            @endif

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-wrapper">
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email address"
                                           required
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-wrapper">
                                    <div class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           required>
                                    <button type="button" class="password-toggle" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="form-options">
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="remember"
                                           name="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="forgot-password">Forgot password?</a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-auth" id="loginBtn">
                                <span class="btn-text">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    @if(request('checkout') == '1' || session('checkout_pending'))
                                        Login & Continue Checkout
                                    @else
                                        Sign In
                                    @endif
                                </span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Signing In...
                                </span>
                            </button>
                        </form>

                        <!-- Sign Up Link -->
                        <div class="auth-footer">
                            <p class="signup-prompt">
                                Don't have an account?
                                <a href="{{ route('register') }}{{ request('checkout') == '1' ? '?checkout=1' : '' }}" class="signup-link">Create one now</a>
                            </p>
                        </div>

                        <!-- Demo Accounts -->
                        <div class="demo-accounts">
                            <p class="demo-title">Demo Accounts:</p>
                            <div class="demo-buttons">
                                <button type="button" class="btn btn-demo" data-email="admin@tokosaya.com" data-password="admin123">
                                    <i class="fas fa-user-shield me-1"></i>
                                    Admin Demo
                                </button>
                                <button type="button" class="btn btn-demo" data-email="customer@tokosaya.com" data-password="customer123">
                                    <i class="fas fa-user me-1"></i>
                                    Customer Demo
                                </button>
                            </div>
                        </div>

                        <!-- Back to Checkout Link -->
                        @if(request('checkout') == '1' || session('checkout_pending'))
                            <div class="text-center mt-3">
                                <a href="{{ route('checkout') }}" class="btn btn-link">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Back to Checkout
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Auth Container */
.auth-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Left Side - Branding */
.auth-branding {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    color: white;
    padding: 4rem 3rem;
    position: relative;
    overflow: hidden;
}

.auth-branding::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.branding-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.branding-header {
    text-align: center;
    margin-bottom: 3rem;
}

.brand-logo {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.brand-logo i {
    font-size: 2.5rem;
    color: white;
}

.brand-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Features List */
.features-list {
    margin-bottom: 3rem;
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.feature-icon i {
    font-size: 1.5rem;
    color: white;
}

.feature-text h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: white;
}

.feature-text p {
    margin-bottom: 0;
    opacity: 0.8;
    font-size: 0.9rem;
}

/* Stats Container */
.stats-container {
    display: flex;
    justify-content: space-around;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Right Side - Form */
.auth-form-section {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: white;
}

.auth-form-container {
    width: 100%;
    max-width: 450px;
}

/* Mobile Brand */
.mobile-brand {
    text-align: center;
    margin-bottom: 2rem;
}

.brand-logo-small {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.brand-logo-small i {
    font-size: 1.8rem;
    color: white;
}

.brand-title-small {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0;
}

/* Auth Header */
.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* Social Login */
.social-login {
    display: flex;
    gap: 1rem;
}

.btn-social {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    background: white;
    color: #495057;
    font-weight: 600;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.btn-social:hover {
    border-color: #007bff;
    background: #f8f9fa;
    color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
}

.btn-google:hover {
    border-color: #db4437;
    color: #db4437;
    box-shadow: 0 4px 12px rgba(219, 68, 55, 0.15);
}

.btn-facebook:hover {
    border-color: #3b5998;
    color: #3b5998;
    box-shadow: 0 4px 12px rgba(59, 89, 152, 0.15);
}

/* Divider */
.divider {
    position: relative;
    text-align: center;
    margin: 2rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e9ecef;
}

.divider span {
    background: white;
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.9rem;
    position: relative;
    z-index: 1;
}

/* Form Styles */
.auth-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 2;
}

.form-control {
    padding: 15px 50px 15px 45px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    background: white;
}

.form-control.is-invalid {
    border-color: #dc3545;
    background: #fff5f5;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 5px;
    z-index: 2;
}

.password-toggle:hover {
    color: #007bff;
}

.invalid-feedback {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #dc3545;
}

/* Form Options */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.form-check {
    display: flex;
    align-items: center;
}

.form-check-input {
    margin-right: 0.5rem;
}

.form-check-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.forgot-password {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.forgot-password:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Auth Button */
.btn-auth {
    width: 100%;
    padding: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-auth:active {
    transform: translateY(0);
}

.btn-auth:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Auth Footer */
.auth-footer {
    text-align: center;
    margin-bottom: 2rem;
}

.signup-prompt {
    color: #6c757d;
    margin-bottom: 0;
}

.signup-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}

.signup-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Demo Accounts */
.demo-accounts {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.demo-title {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 1rem;
    font-weight: 600;
}

.demo-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-demo {
    flex: 1;
    padding: 8px 12px;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    color: #495057;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-demo:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 991.98px) {
    .auth-form-section {
        min-height: 100vh;
    }

    .social-login {
        flex-direction: column;
    }

    .form-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .demo-buttons {
        flex-direction: column;
    }
}

@media (max-width: 575.98px) {
    .auth-container {
        padding: 1rem;
    }

    .auth-form-container {
        max-width: 100%;
    }

    .auth-title {
        font-size: 2rem;
    }

    .auth-subtitle {
        font-size: 1rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-form-wrapper {
    animation: fadeInUp 0.6s ease-out;
}

/* Loading Animation */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const demoButtons = document.querySelectorAll('.btn-demo');

    // Password toggle functionality
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Form submission with loading state
    if (form && loginBtn) {
        form.addEventListener('submit', function(e) {
            const btnText = loginBtn.querySelector('.btn-text');
            const btnLoading = loginBtn.querySelector('.btn-loading');

            if (btnText && btnLoading) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                loginBtn.disabled = true;
            }

            // Re-enable button after 5 seconds in case of error
            setTimeout(() => {
                if (btnText && btnLoading) {
                    btnText.classList.remove('d-none');
                    btnLoading.classList.add('d-none');
                    loginBtn.disabled = false;
                }
            }, 5000);
        });
    }

    // Demo account buttons
    demoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');

            if (email && password) {
                document.getElementById('email').value = email;
                document.getElementById('password').value = password;

                // Add visual feedback
                this.innerHTML = '<i class="fas fa-check me-1"></i>Credentials Set!';
                this.style.background = '#28a745';
                this.style.color = 'white';
                this.style.borderColor = '#28a745';

                // Reset button after 2 seconds
                setTimeout(() => {
                    this.innerHTML = this.getAttribute('data-email').includes('admin')
                        ? '<i class="fas fa-user-shield me-1"></i>Admin Demo'
                        : '<i class="fas fa-user me-1"></i>Customer Demo';
                    this.style.background = 'white';
                    this.style.color = '#495057';
                    this.style.borderColor = '#dee2e6';
                }, 2000);
            }
        });
    });

    // Social login buttons (placeholder functionality)
    document.querySelectorAll('.btn-social').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.classList.contains('btn-google') ? 'Google' : 'Facebook';

            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>Connecting to ${type}...`;
            this.disabled = true;

            // Simulate delay and show message
            setTimeout(() => {
                alert(`${type} login is not implemented yet. Please use email/password or demo accounts.`);
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1500);
        });
    });

    // Real-time form validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
                let feedback = this.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    this.parentNode.appendChild(feedback);
                }
                feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Please enter a valid email address';
            } else {
                this.classList.remove('is-invalid');
            }
        });

        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Forgot password link
    document.querySelector('.forgot-password')?.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Password reset functionality will be implemented soon. Please contact support for assistance.');
    });

    // Add smooth animations to form elements
    const formElements = document.querySelectorAll('.form-control, .btn-social, .btn-auth');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease';

        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection

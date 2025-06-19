@extends('layouts.app')

@section('title', 'Home - Toko Saya')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-3">Welcome to Toko Saya</h1>
                <p class="lead mb-4">Your trusted online shopping destination with {{ $stats['total_products'] }} amazing products across {{ $stats['total_categories'] }} categories.</p>

                @guest
                    <!-- Guest Call-to-Action -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('products.list') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </div>

                    <!-- Login Options Card -->
                    <div class="login-options-card">
                        <h5 class="text-white mb-3">Choose Your Account Type:</h5>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="login-option customer">
                                    <i class="fas fa-user fa-2x mb-2"></i>
                                    <h6>Customer</h6>
                                    <p class="small mb-2">Shop and buy products</p>
                                    <a href="{{ route('login') }}?role=customer" class="btn btn-sm btn-light">
                                        Login as Customer
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="login-option admin">
                                    <i class="fas fa-user-cog fa-2x mb-2"></i>
                                    <h6>Admin</h6>
                                    <p class="small mb-2">Manage products & orders</p>
                                    <a href="{{ route('login') }}?role=admin" class="btn btn-sm btn-warning">
                                        Login as Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-white-50">
                                Don't have an account? <a href="{{ route('register') }}" class="text-white">Register here</a>
                            </small>
                        </div>
                    </div>
                @else
                    <!-- Authenticated User Actions -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('products.list') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Browse Products
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('products.create') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-chart-line me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>My Cart
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i>My Orders
                            </a>
                        @endif
                    </div>
                @endguest
            </div>
            <div class="col-lg-5 text-center">
                <div class="hero-stats">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-item">
                                <i class="fas fa-box fa-2x mb-2"></i>
                                <h4>{{ $stats['total_products'] }}</h4>
                                <small>Products</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <i class="fas fa-tags fa-2x mb-2"></i>
                                <h4>{{ $stats['total_categories'] }}</h4>
                                <small>Categories</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4>1000+</h4>
                                <small>Customers</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <h4>4.8</h4>
                                <small>Rating</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="quick-stat">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['in_stock'] }}</h5>
                            <small class="text-muted">In Stock</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-stat">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ number_format($stats['average_price'] / 1000, 0) }}K</h5>
                            <small class="text-muted">Avg Price</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-stat">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['low_stock'] }}</h5>
                            <small class="text-muted">Low Stock</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="quick-stat">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['out_of_stock'] }}</h5>
                            <small class="text-muted">Out of Stock</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Products</h2>
            <p class="text-muted">Discover our best-selling and premium products</p>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts->take(4) as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-card h-100">
                        <div class="product-image-container">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
                            @else
                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fa-3x"></i>
                                </div>
                            @endif

                            @if($product->stock <= 0)
                                <span class="product-badge bg-danger">Out of Stock</span>
                            @elseif($product->is_expensive)
                                <span class="product-badge bg-warning">Premium</span>
                            @endif
                        </div>

                        <div class="product-body">
                            <div class="mb-2">
                                <span class="category-badge">{{ $product->category->name }}</span>
                            </div>
                            <h5 class="product-title">{{ Str::limit($product->name, 40) }}</h5>
                            <p class="product-description">{{ Str::limit($product->description, 80) }}</p>

                            <div class="product-footer">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="price-tag">{{ $product->formatted_price }}</span>
                                    <small class="stock-info">
                                        <i class="fas fa-box me-1"></i>{{ $product->stock }}
                                    </small>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>

                                    @if($product->stock > 0)
                                        <button type="button" class="btn btn-primary btn-sm flex-fill ms-2 quick-add-btn"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No featured products available.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Shop by Category</h2>
            <p class="text-muted">Browse our wide range of product categories</p>
        </div>

        <div class="row g-4">
            @forelse($categories->take(6) as $category)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('products.list', ['category_id' => $category->id]) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="category-icon">
                                @switch($category->name)
                                    @case('Electronics')
                                        <i class="fas fa-laptop"></i>
                                        @break
                                    @case('Clothing')
                                        <i class="fas fa-tshirt"></i>
                                        @break
                                    @case('Books')
                                        <i class="fas fa-book"></i>
                                        @break
                                    @case('Home & Garden')
                                        <i class="fas fa-home"></i>
                                        @break
                                    @case('Sports')
                                        <i class="fas fa-dumbbell"></i>
                                        @break
                                    @default
                                        <i class="fas fa-tag"></i>
                                @endswitch
                            </div>
                            <h5 class="category-name">{{ $category->name }}</h5>
                            <p class="category-count">{{ $category->products_count }} Products</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@guest
<!-- CTA Section for Guests -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Start Shopping?</h2>
        <p class="lead mb-4">Join thousands of satisfied customers and discover amazing products today!</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </a>
        </div>
    </div>
</section>
@endguest
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick add to cart functionality
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const originalText = this.innerHTML;

            // Update button state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';

            // Send request
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
                    // Update cart badge
                    updateCartBadge(data.cart_count);

                    // Show success state
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');

                    // Show toast notification
                    showToast('success', data.message);

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                } else {
                    showToast('error', data.message);
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while adding to cart');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // Card animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.product-card, .category-card, .quick-stat').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);
    setTimeout(() => { if (toast.parentNode) toast.remove(); }, 3000);
}
</script>
@endsection

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        min-height: 60vh;
        display: flex;
        align-items: center;
    }

    .login-options-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-option {
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .login-option:hover {
        transform: translateY(-3px);
    }

    .hero-stats .stat-item {
        text-align: center;
        color: rgba(255, 255, 255, 0.9);
    }

    .quick-stat {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }

    .quick-stat:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .product-body {
        padding: 20px;
    }

    .category-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 10px 0;
        color: #333;
    }

    .product-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .price-tag {
        font-weight: 700;
        color: #2e7d32;
        font-size: 1.1rem;
    }

    .stock-info {
        color: #666;
    }

    .product-actions {
        display: flex;
        gap: 8px;
    }

    .category-card {
        background: white;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        color: inherit;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        color: inherit;
    }

    .category-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
        color: white;
    }

    .category-name {
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    .category-count {
        color: #666;
        margin: 0;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 60px 0;
            text-align: center;
        }

        .product-image {
            height: 200px;
        }

        .hero-stats {
            margin-top: 30px;
        }

        .product-actions {
            flex-direction: column;
        }

        .btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Home - Toko Saya')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Welcome to <span class="text-primary">Toko Saya</span></h1>
                    <p class="hero-subtitle">Discover quality products with exceptional service. We bring you {{ $stats['total_products'] }} carefully curated items across {{ $stats['total_categories'] }} categories.</p>

                    @guest
                        <div class="hero-actions">
                            <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Join Us
                            </a>
                        </div>

                        <!-- Quick Login Section -->
                        <div class="quick-login-section">
                            <p class="small text-muted mb-3">Quick access:</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                    Customer Login
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                    Admin Access
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="hero-actions">
                            <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-shopping-bag me-2"></i>Browse Products
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-chart-line me-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>My Cart
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-content">
                            <h4 class="stat-number">{{ number_format($stats['total_products']) }}</h4>
                            <p class="stat-label">Products</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-content">
                            <h4 class="stat-number">{{ $stats['total_categories'] }}</h4>
                            <p class="stat-label">Categories</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h4 class="stat-number">{{ number_format($stats['in_stock']) }}</h4>
                            <p class="stat-label">In Stock</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <h4 class="stat-number">4.8</h4>
                            <p class="stat-label">Rating</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Info Bar -->
<section class="info-bar">
    <div class="container">
        <div class="row g-0">
            <div class="col-lg-3 col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="info-title">Free Shipping</h6>
                        <p class="info-text">Orders over Rp 500,000</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="info-title">Secure Payment</h6>
                        <p class="info-text">100% protected transactions</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="info-title">Easy Returns</h6>
                        <p class="info-text">30-day return policy</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="info-title">24/7 Support</h6>
                        <p class="info-text">Always here to help</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Discover our most popular and highly-rated items</p>
        </div>

        <div class="products-grid">
            @forelse($featuredProducts->take(8) as $product)
                <div class="product-card">
                    <div class="product-image-wrapper">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <div class="product-image product-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif

                        @if($product->stock <= 0)
                            <span class="product-badge badge-danger">Out of Stock</span>
                        @elseif($product->is_expensive)
                            <span class="product-badge badge-premium">Premium</span>
                        @endif

                        <div class="product-overlay">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($product->stock > 0)
                                <button type="button" class="btn btn-sm btn-light quick-add-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="product-content">
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h5 class="product-title">
                            <a href="{{ route('products.show', $product->id) }}">{{ Str::limit($product->name, 50) }}</a>
                        </h5>
                        <p class="product-description">{{ Str::limit($product->description, 80) }}</p>

                        <div class="product-footer">
                            <div class="product-price">{{ $product->formatted_price }}</div>
                            <div class="product-stock">
                                <i class="fas fa-box text-muted"></i>
                                <span class="text-muted small">{{ $product->stock }} left</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h4>No Products Available</h4>
                        <p>Check back later for new arrivals.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
                View All Products
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Browse our carefully organized product categories</p>
        </div>

        <div class="categories-grid">
            @forelse($categories->take(6) as $category)
                <a href="{{ route('products.list', ['category_id' => $category->id]) }}" class="category-card">
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
                            @case('Beauty & Health')
                                <i class="fas fa-heart"></i>
                                @break
                            @default
                                <i class="fas fa-tag"></i>
                        @endswitch
                    </div>
                    <h5 class="category-name">{{ $category->name }}</h5>
                    <p class="category-count">{{ $category->products_count }} Products</p>
                    <div class="category-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <h4>No Categories Available</h4>
                        <p>Categories will appear here soon.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

@guest
<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Ready to Start Shopping?</h2>
                    <p class="cta-subtitle">Join thousands of satisfied customers and discover amazing products with great deals.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endguest
@endsection

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 4rem 0;
        min-height: 70vh;
        display: flex;
        align-items: center;
    }

    .hero-content {
        padding-right: 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-secondary);
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-actions {
        margin-bottom: 2rem;
    }

    .quick-login-section {
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .hero-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        text-align: center;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    /* Info Bar */
    .info-bar {
        background: white;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 2rem 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 0 2rem;
        border-right: 1px solid var(--border-color);
    }

    .info-item:last-child {
        border-right: none;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: var(--light-gray);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
    }

    .info-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .info-text {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    /* Section Styling */
    .featured-section,
    .categories-section {
        padding: 5rem 0;
    }

    .categories-section {
        background: var(--light-gray);
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.125rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: var(--transition);
    }

    .product-image-placeholder {
        background: var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 2rem;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .badge-danger {
        background: var(--danger);
    }

    .badge-premium {
        background: var(--warning);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        opacity: 0;
        transition: var(--transition);
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .product-content {
        padding: 1.5rem;
    }

    .product-category {
        color: var(--primary);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .product-title {
        margin-bottom: 0.75rem;
    }

    .product-title a {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
    }

    .product-title a:hover {
        color: var(--primary);
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-weight: 700;
        color: var(--accent);
        font-size: 1.125rem;
    }

    .product-stock {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .category-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: var(--border-radius);
        text-align: center;
        text-decoration: none;
        color: inherit;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        color: inherit;
    }

    .category-icon {
        width: 80px;
        height: 80px;
        background: var(--light-gray);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        transition: var(--transition);
    }

    .category-card:hover .category-icon {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    .category-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .category-count {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .category-arrow {
        position: absolute;
        top: 1rem;
        right: 1rem;
        opacity: 0;
        transition: var(--transition);
        color: var(--primary);
    }

    .category-card:hover .category-arrow {
        opacity: 1;
        transform: translateX(0.25rem);
    }

    /* CTA Section */
    .cta-section {
        background: var(--primary);
        color: white;
        padding: 4rem 0;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .cta-actions {
        display: flex;
        gap: 1rem;
        justify-content: end;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .hero-title {
            font-size: 3rem;
        }
    }

    @media (max-width: 992px) {
        .hero-section {
            padding: 3rem 0;
        }

        .hero-content {
            padding-right: 0;
            margin-bottom: 3rem;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border-right: none;
            border-bottom: 1px solid var(--border-color);
        }

        .cta-actions {
            justify-content: center;
            margin-top: 2rem;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .hero-stats-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .categories-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .cta-actions {
            flex-direction: column;
            align-items: center;
        }

        .featured-section,
        .categories-section {
            padding: 3rem 0;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick add to cart functionality
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.dataset.productId;
            const originalHtml = this.innerHTML;

            // Update button state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.remove('btn-light');
                    this.classList.add('btn-success');

                    // Show toast notification
                    showToast('success', data.message);

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-light');
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
                showToast('error', 'An error occurred while adding to cart');
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.product-card, .category-card, .stat-card, .info-item').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });
});

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
</script>
@endsection

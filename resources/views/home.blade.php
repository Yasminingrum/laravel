@extends('layouts.app')

@section('title', 'Home - Toko Saya')

@section('content')
<!-- Hero Section - Compact -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 fw-bold mb-3">Welcome to Toko Saya</h1>
                <p class="mb-4">Discover amazing products and manage your inventory with ease. Browse through our collection of {{ $stats['total_products'] }} products.</p>
                <div class="d-flex gap-2">
                    <x-button
                        href="{{ route('products.list') }}"
                        variant="light"
                        size="sm"
                        icon="fas fa-shopping-bag"
                    >
                        Browse Products
                    </x-button>
                    <x-button
                        href="{{ route('products.create') }}"
                        variant="outline-light"
                        size="sm"
                        icon="fas fa-plus"
                    >
                        Add Product
                    </x-button>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-store display-6 text-white-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section - Compact -->
<section class="py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['total_products'] }}</h5>
                            <small class="text-muted">Products</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['total_categories'] }}</h5>
                            <small class="text-muted">Categories</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ number_format($stats['inventory_value'] / 1000000, 1) }}M</h5>
                            <small class="text-muted">Value</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $stats['low_stock'] + $stats['out_of_stock'] }}</h5>
                            <small class="text-muted">Alerts</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products - Compact -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Featured Products</h3>
            <x-button
                href="{{ route('products.list') }}"
                variant="outline-primary"
                size="sm"
            >
                View All
            </x-button>
        </div>
        <div class="row g-3">
            @forelse($featuredProducts->take(3) as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="product-card-img" alt="{{ $product->name }}">
                        @else
                            <div class="product-card-img bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                        <div class="product-card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">{{ Str::limit($product->name, 30) }}</h6>
                                <span class="category-badge">{{ $product->category->name }}</span>
                            </div>
                            <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">{{ $product->formatted_price }}</span>
                                <x-button
                                    href="{{ route('products.show', $product->id) }}"
                                    variant="outline-primary"
                                    size="sm"
                                >
                                    View
                                </x-button>
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
    </div>
</section>

<!-- Quick Actions & Recent -->
<section class="py-4">
    <div class="container">
        <div class="row g-4">
            <!-- Quick Actions -->
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3">Quick Actions</h4>
                <div class="list-group list-group-flush">
                    <a href="{{ route('products.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-plus text-primary me-3"></i>
                        <div>
                            <h6 class="mb-1">Add Product</h6>
                            <small class="text-muted">Add new product to inventory</small>
                        </div>
                    </a>
                    <a href="{{ route('products.list') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-list text-success me-3"></i>
                        <div>
                            <h6 class="mb-1">View All Products</h6>
                            <small class="text-muted">Browse complete product list</small>
                        </div>
                    </a>
                    <a href="{{ route('products.list', ['stock' => 'low']) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                        <div>
                            <h6 class="mb-1">Low Stock Alert</h6>
                            <small class="text-muted">{{ $stats['low_stock'] }} products need attention</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- New Arrivals -->
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3">New Arrivals</h4>
                <div class="d-flex flex-column gap-2">
                    @forelse($newArrivals->take(3) as $product)
                        <div class="mini-product-card">
                            <div class="d-flex align-items-center">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" class="mini-product-img me-3" alt="{{ $product->name }}">
                                @else
                                    <div class="mini-product-img bg-light d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->name, 25) }}</h6>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span class="price-tag small">{{ $product->formatted_price }}</span>
                                        <span class="badge bg-success">New</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No new arrivals.</p>
                    @endforelse
                </div>
            </div>

            <!-- Categories -->
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3">Categories</h4>
                <div class="row g-2">
                    @forelse($categories->take(4) as $category)
                        <div class="col-6">
                            <a href="{{ route('products.list', ['category_id' => $category->id]) }}" class="text-decoration-none">
                                <div class="category-mini-card">
                                    <i class="fas fa-tag text-primary mb-2"></i>
                                    <h6 class="mb-1">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->products_count }} items</small>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">No categories available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate statistics on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.stat-card, .product-card, .mini-product-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        // Add hover effects
        document.querySelectorAll('.stat-card, .product-card, .mini-product-card, .category-mini-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .product-card-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .product-card-body {
        padding: 16px;
    }

    .mini-product-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .mini-product-img {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
    }

    .category-mini-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 1px 6px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .category-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .price-tag {
        font-weight: 600;
        color: #2e7d32;
    }

    .list-group-item {
        border: none;
        border-radius: 8px !important;
        margin-bottom: 8px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.08);
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 40px 0;
        }

        .stat-card {
            padding: 16px;
        }

        .product-card-img {
            height: 150px;
        }
    }
</style>
@endsection

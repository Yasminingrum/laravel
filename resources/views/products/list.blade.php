@extends('layouts.app')

@section('title', 'Products - Toko Saya')

@section('content')
<div class="container py-4">
    <!-- Debug Information (Remove in production) -->
    @if(config('app.debug'))
        <div class="alert alert-info mb-3">
            <small>
                <strong>Debug Info:</strong>
                Total in DB: {{ $total_in_db ?? 'N/A' }} |
                After Filter: {{ $total_found ?? 'N/A' }} |
                Current Page: {{ $pagination['current_page'] ?? 'N/A' }} |
                Per Page: {{ $pagination['per_page'] ?? 'N/A' }} |
                Showing: {{ count($products ?? []) }}
            </small>
        </div>
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">Products</h1>
            <p class="text-muted">Discover amazing products in our collection</p>
        </div>
        <div class="col-md-4 text-md-end">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-section bg-light p-4 rounded mb-4">
        <form method="GET" action="{{ route('products.list') }}" id="filterForm">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-lg-3 col-md-6">
                    <label for="search" class="form-label">Search Products</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or description...">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-lg-2 col-md-6">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Per Page Selection -->
                <div class="col-lg-1 col-md-6">
                    <label for="per_page" class="form-label">Show</label>
                    <select class="form-select" id="per_page" name="per_page">
                        <option value="12" {{ request('per_page', 20) == 12 ? 'selected' : '' }}>12</option>
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="30" {{ request('per_page', 20) == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="col-lg-2 col-md-4">
                    <label for="min_price" class="form-label">Min Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                               class="form-control"
                               id="min_price"
                               name="min_price"
                               value="{{ request('min_price') }}"
                               placeholder="0"
                               min="0">
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label for="max_price" class="form-label">Max Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                               class="form-control"
                               id="max_price"
                               name="max_price"
                               value="{{ request('max_price') }}"
                               placeholder="999999999"
                               min="0">
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="col-lg-2 col-md-4">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="">Latest</option>
                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>

                    <!-- Show All Button -->
                    <a href="{{ route('products.list', ['per_page' => 100]) }}" class="btn btn-outline-info ms-2">
                        <i class="fas fa-eye me-1"></i>Show All
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="row mb-3">
        <div class="col-md-6">
            <p class="text-muted mb-0">
                @if(isset($pagination))
                    Showing {{ $pagination['from'] ?? 0 }} - {{ $pagination['to'] ?? 0 }}
                    of {{ $pagination['total'] ?? 0 }} products
                @endif
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <!-- Quick Sort Buttons -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_asc']) }}"
                   class="btn btn-outline-secondary {{ request('sort_by') == 'price_asc' ? 'active' : '' }}">
                    <i class="fas fa-arrow-up me-1"></i>Price ↑
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_desc']) }}"
                   class="btn btn-outline-secondary {{ request('sort_by') == 'price_desc' ? 'active' : '' }}">
                    <i class="fas fa-arrow-down me-1"></i>Price ↓
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name_asc']) }}"
                   class="btn btn-outline-secondary {{ request('sort_by') == 'name_asc' ? 'active' : '' }}">
                    <i class="fas fa-sort-alpha-down me-1"></i>A-Z
                </a>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsGrid">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm product-card h-100">
                        <!-- Product Image -->
                        <div class="position-relative">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}"
                                     class="card-img-top product-image"
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            @else
                                <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fa-3x"></i>
                                </div>
                            @endif

                            <!-- Stock Badge -->
                            @if($product->stock <= 0)
                                <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                    Out of Stock
                                </span>
                            @elseif($product->stock < 10)
                                <span class="position-absolute top-0 start-0 badge bg-warning m-2">
                                    Low Stock
                                </span>
                            @endif

                            <!-- Price Tag -->
                            <div class="position-absolute bottom-0 end-0 m-2">
                                @if(isset($product->is_expensive) && $product->is_expensive)
                                    <span class="badge bg-gold text-dark">Premium</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Category Badge -->
                            <div class="mb-2">
                                <span class="category-badge">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            <!-- Product Title -->
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $product->id) }}"
                                   class="text-decoration-none text-dark product-title">
                                    {{ $product->name }}
                                </a>
                            </h5>

                            <!-- Product Description -->
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($product->description, 100) }}
                            </p>

                            <!-- Price & Stock Info -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="price-container">
                                        <span class="price-tag fs-5 fw-bold text-primary">
                                            @if(isset($product->formatted_price))
                                                {{ $product->formatted_price }}
                                            @else
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-warehouse me-1"></i>Stock: {{ $product->stock }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <!-- Primary Action -->
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="btn btn-outline-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>

                                        <!-- Add to Cart Button for ALL users -->
                                        @if($product->stock > 0)
                                            <button type="button"
                                                    class="btn btn-primary quick-add-btn flex-fill"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary flex-fill" disabled>
                                                <i class="fas fa-times me-1"></i>Out of Stock
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Admin Actions -->
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <div class="btn-group btn-group-sm mt-2" role="group">
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('products.destroy', $product->id) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-box-open display-1 text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">No Products Found</h3>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'category_id', 'min_price', 'max_price']))
                            Try adjusting your search criteria or clear the filters to see more products.
                        @else
                            No products are available at the moment. Please check back later!
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'category_id', 'min_price', 'max_price']))
                        <a href="{{ route('products.list') }}" class="btn btn-primary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('products.create') }}" class="btn btn-success ms-2">
                                <i class="fas fa-plus me-2"></i>Add First Product
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @endif
    </div>

    <!-- Enhanced Pagination -->
    @if(isset($pagination) && $pagination['last_page'] > 1)
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Products pagination">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        @if($pagination['current_page'] > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        @endif

                        <!-- Page Numbers -->
                        @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                            <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <!-- Next Page -->
                        @if($pagination['current_page'] < $pagination['last_page'])
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}
                        ({{ $pagination['total'] }} total products)
                    </small>
                </div>
            </div>
        </div>
    @endif

    <!-- Floating Cart Button (for mobile) -->
    <div class="floating-cart d-md-none">
        <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lg rounded-circle">
            <i class="fas fa-shopping-cart"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge-mobile">
                @auth
                    {{ auth()->user()->isCustomer() ? auth()->user()->getCartItemsCount() : 0 }}
                @else
                    {{ session('cart') ? array_sum(array_column(session('cart', []), 'quantity')) : 0 }}
                @endauth
            </span>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on important changes
    document.getElementById('sort_by').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('category_id').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('per_page').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Quick add to cart functionality
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const originalText = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';

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
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    showToast('success', data.message);

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

    function updateCartBadge(count) {
        const badges = document.querySelectorAll('.cart-badge, .cart-badge-mobile');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline' : 'none';
        });
    }

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed shadow`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border: none;';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(toast);
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
    }
});
</script>
@endsection

@section('styles')
<style>
.search-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.product-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 15px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.product-image {
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.category-badge {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1976d2;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.price-tag {
    color: #2e7d32;
    font-weight: 700;
}

.floating-cart {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
}

@media (max-width: 768px) {
    .product-image {
        height: 200px;
    }

    .search-section .row > div {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

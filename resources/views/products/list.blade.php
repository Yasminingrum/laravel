@extends('layouts.app')

@section('title', 'Products - Toko Saya')

@section('content')
<div class="products-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-title-section">
                        <h1 class="page-title">Our Products</h1>
                        <p class="page-subtitle">Discover quality products carefully selected for you</p>
                    </div>
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
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-card">
                <form method="GET" action="{{ route('products.list') }}" id="filterForm">
                    <div class="filters-header">
                        <h5 class="filters-title">
                            <i class="fas fa-filter me-2"></i>Filter Products
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>

                    <div class="filters-content" id="filtersContent">
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-lg-3 col-md-6">
                                <label for="search" class="form-label">Search</label>
                                <div class="search-input-wrapper">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text"
                                           class="form-control"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search products...">
                                </div>
                            </div>

                            <!-- Category -->
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

                            <!-- Price Range -->
                            <div class="col-lg-2 col-md-6">
                                <label for="min_price" class="form-label">Min Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           class="form-control"
                                           id="min_price"
                                           name="min_price"
                                           value="{{ request('min_price') }}"
                                           placeholder="0">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="max_price" class="form-label">Max Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                           class="form-control"
                                           id="max_price"
                                           name="max_price"
                                           value="{{ request('max_price') }}"
                                           placeholder="999999999">
                                </div>
                            </div>

                            <!-- Sort -->
                            <div class="col-lg-2 col-md-6">
                                <label for="sort_by" class="form-label">Sort By</label>
                                <select class="form-select" id="sort_by" name="sort_by">
                                    <option value="">Latest</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
                                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
                                </select>
                            </div>

                            <!-- Per Page -->
                            <div class="col-lg-1 col-md-6">
                                <label for="per_page" class="form-label">Show</label>
                                <select class="form-select" id="per_page" name="per_page">
                                    <option value="12" {{ request('per_page', 20) == 12 ? 'selected' : '' }}>12</option>
                                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ request('per_page', 20) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                        </div>

                        <div class="filters-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('products.list') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Clear All
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Info -->
        <div class="results-info">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="results-text">
                        @if(isset($pagination))
                            Showing <strong>{{ $pagination['from'] ?? 0 }} - {{ $pagination['to'] ?? 0 }}</strong>
                            of <strong>{{ $pagination['total'] ?? 0 }}</strong> products
                            @if(request('search'))
                                for "<strong>{{ request('search') }}</strong>"
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="quick-sort">
                        <span class="quick-sort-label">Quick sort:</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_asc']) }}"
                               class="btn btn-outline-secondary {{ request('sort_by') == 'price_asc' ? 'active' : '' }}">
                                Price ↑
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_desc']) }}"
                               class="btn btn-outline-secondary {{ request('sort_by') == 'price_desc' ? 'active' : '' }}">
                                Price ↓
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name_asc']) }}"
                               class="btn btn-outline-secondary {{ request('sort_by') == 'name_asc' ? 'active' : '' }}">
                                A-Z
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-section">
            @if(isset($products) && count($products) > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         class="product-image"
                                         alt="{{ $product->name }}"
                                         loading="lazy">
                                @else
                                    <div class="product-image product-image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif

                                <!-- Stock Badge -->
                                @if($product->stock <= 0)
                                    <span class="product-badge badge-danger">Out of Stock</span>
                                @elseif($product->stock < 10)
                                    <span class="product-badge badge-warning">Low Stock</span>
                                @elseif(isset($product->is_expensive) && $product->is_expensive)
                                    <span class="product-badge badge-premium">Premium</span>
                                @endif

                                <!-- Quick Actions Overlay -->
                                <div class="product-overlay">
                                    <div class="overlay-actions">
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="btn btn-sm btn-light"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($product->stock > 0)
                                            <button type="button"
                                                    class="btn btn-sm btn-primary quick-add-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    title="Add to Cart">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="product-content">
                                <!-- Category -->
                                <div class="product-category">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </div>

                                <!-- Title -->
                                <h5 class="product-title">
                                    <a href="{{ route('products.show', $product->id) }}">
                                        {{ $product->name }}
                                    </a>
                                </h5>

                                <!-- Description -->
                                <p class="product-description">
                                    {{ Str::limit($product->description, 100) }}
                                </p>

                                <!-- Price & Stock -->
                                <div class="product-footer">
                                    <div class="product-price">
                                        @if(isset($product->formatted_price))
                                            {{ $product->formatted_price }}
                                        @else
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        @endif
                                    </div>
                                    <div class="product-stock">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $product->stock }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="product-actions">
                                    <a href="{{ route('products.show', $product->id) }}"
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>

                                    @if($product->stock > 0)
                                        <button type="button"
                                                class="btn btn-primary btn-sm flex-fill ms-2 quick-add-btn"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm flex-fill ms-2" disabled>
                                            <i class="fas fa-times me-1"></i>Out of Stock
                                        </button>
                                    @endif
                                </div>

                                <!-- Admin Actions -->
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <div class="admin-actions">
                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                   class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('products.destroy', $product->id) }}"
                                                      class="d-inline flex-fill"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger w-100">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="empty-title">No Products Found</h3>
                    <p class="empty-description">
                        @if(request()->hasAny(['search', 'category_id', 'min_price', 'max_price']))
                            We couldn't find any products matching your criteria. Try adjusting your filters or search terms.
                        @else
                            No products are available at the moment. Please check back later!
                        @endif
                    </p>
                    <div class="empty-actions">
                        @if(request()->hasAny(['search', 'category_id', 'min_price', 'max_price']))
                            <a href="{{ route('products.list') }}" class="btn btn-primary">
                                <i class="fas fa-times me-2"></i>Clear All Filters
                            </a>
                        @endif
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('products.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add First Product
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if(isset($pagination) && $pagination['last_page'] > 1)
            <div class="pagination-section">
                <nav aria-label="Products pagination">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        @if($pagination['current_page'] > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}">
                                    <i class="fas fa-chevron-left me-1"></i>Previous
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
                                    Next<i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="pagination-info">
                    Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}
                    ({{ number_format($pagination['total']) }} total products)
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .products-page {
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

    /* Filters Section */
    .filters-section {
        margin-bottom: 2rem;
    }

    .filters-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
    }

    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .filters-title {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .filters-content {
        padding: 1.5rem;
        transition: var(--transition);
    }

    .filters-content.collapsed {
        display: none;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 2;
    }

    .search-input-wrapper .form-control {
        padding-left: 2.5rem;
    }

    .filters-actions {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 1rem;
    }

    /* Results Info */
    .results-info {
        margin-bottom: 2rem;
        padding: 1rem 0;
    }

    .results-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .quick-sort {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
    }

    .quick-sort-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .product-image {
        width: 100%;
        height: 100%;
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
        z-index: 2;
    }

    .badge-danger {
        background: var(--danger);
    }

    .badge-warning {
        background: var(--warning);
    }

    .badge-premium {
        background: var(--primary);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(26, 54, 93, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .overlay-actions {
        display: flex;
        gap: 0.5rem;
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
        margin-bottom: 0.75rem;
    }

    .product-title {
        margin-bottom: 0.75rem;
        font-size: 1.125rem;
    }

    .product-title a {
        color: var(--text-primary);
        text-decoration: none;
        font-weight: 600;
        line-height: 1.4;
    }

    .product-title a:hover {
        color: var(--primary);
    }

    .product-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .product-price {
        font-weight: 700;
        color: var(--accent);
        font-size: 1.25rem;
    }

    .product-stock {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .admin-actions {
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .empty-description {
        font-size: 1rem;
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Pagination */
    .pagination-section {
        margin-top: 3rem;
        text-align: center;
    }

    .pagination {
        margin-bottom: 1rem;
    }

    .pagination-info {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .products-page {
            padding: 1rem 0;
        }

        .page-title {
            font-size: 2rem;
        }

        .filters-content {
            padding: 1rem;
        }

        .filters-actions {
            flex-direction: column;
        }

        .quick-sort {
            justify-content: center;
            margin-top: 1rem;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .product-content {
            padding: 1rem;
        }

        .product-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-actions {
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            text-align: center;
        }

        .results-info .row {
            text-align: center;
        }

        .results-info .col-md-6:first-child {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle functionality
    const toggleBtn = document.getElementById('toggleFilters');
    const filtersContent = document.getElementById('filtersContent');

    if (toggleBtn && filtersContent) {
        toggleBtn.addEventListener('click', function() {
            const isCollapsed = filtersContent.classList.contains('collapsed');

            if (isCollapsed) {
                filtersContent.classList.remove('collapsed');
                this.innerHTML = '<i class="fas fa-chevron-up"></i>';
            } else {
                filtersContent.classList.add('collapsed');
                this.innerHTML = '<i class="fas fa-chevron-down"></i>';
            }
        });
    }

    // Auto-submit filters on select change
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
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');

                    // Show toast notification
                    showToast('success', data.message);

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
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

    // Animate product cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe product cards for animation
    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
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

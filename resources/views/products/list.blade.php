@extends('layouts.app')

@section('title', 'Products List')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="display-6 fw-bold">Products</h1>
            <p class="text-muted">Manage and browse all products</p>
        </div>
        <div class="col-md-6 text-md-end">
            <x-button
                href="{{ route('products.create') }}"
                variant="primary"
                icon="fas fa-plus"
            >
                Add New Product
            </x-button>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-section">
        <form method="GET" action="{{ route('products.list') }}">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Products</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Search by name or description...">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="col-md-2">
                    <label for="min_price" class="form-label">Min Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="min_price" name="min_price"
                               value="{{ request('min_price') }}" placeholder="0">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="max_price" class="form-label">Max Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="max_price" name="max_price"
                               value="{{ request('max_price') }}" placeholder="999999">
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="col-md-2">
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
                    <x-button
                        type="submit"
                        variant="primary"
                        icon="fas fa-filter"
                    >
                        Apply Filters
                    </x-button>

                    <x-button
                        href="{{ route('products.list') }}"
                        variant="outline-secondary"
                        icon="fas fa-times"
                    >
                        Clear
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Info -->
    <div class="row mb-3">
        <div class="col-12">
            <p class="text-muted">
                @if(isset($products) && method_exists($products, 'total'))
                    Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}
                    of {{ $products->total() }} products
                @else
                    Showing {{ $total_found ?? count($products ?? []) }} products
                @endif
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm card-hover h-100">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-muted fa-3x"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <span class="category-badge">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">{{ $product->formatted_price }}</span>
                                @if($product->is_expensive)
                                    <span class="badge bg-warning">Premium</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-warehouse"></i>
                                Stock: {{ $product->stock }}
                                <span class="badge badge-sm {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }} ms-2">
                                    {{ $product->stock_label }}
                                </span>
                            </small>
                        </div>
                        <div class="d-flex gap-2 mt-auto">
                            <x-button
                                href="{{ route('products.show', $product->id) }}"
                                variant="outline-primary"
                                size="sm"
                                icon="fas fa-eye"
                            >
                                View
                            </x-button>

                            <x-button
                                href="{{ route('products.edit', $product->id) }}"
                                variant="outline-warning"
                                size="sm"
                                icon="fas fa-edit"
                            >
                                Edit
                            </x-button>

                            <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <x-button
                                    type="submit"
                                    variant="outline-danger"
                                    size="sm"
                                    icon="fas fa-trash"
                                    onclick="return confirm('Are you sure you want to delete this product?')"
                                >
                                    Delete
                                </x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-box-open display-1 text-muted mb-4"></i>
                    <h3 class="text-muted">No Products Found</h3>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'category_id', 'min_price', 'max_price']))
                            Try adjusting your search criteria or clear the filters.
                        @else
                            You haven't added any products yet. Start by adding your first product!
                        @endif
                    </p>
                    <x-button
                        href="{{ route('products.create') }}"
                        variant="primary"
                        icon="fas fa-plus"
                    >
                        Add First Product
                    </x-button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    @elseif(isset($pagination) && $pagination['total'] > $pagination['per_page'])
        <!-- Custom Pagination for Collection -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Products pagination">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if($pagination['current_page'] > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}">
                                    Previous
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endfor

                        @if($end < $pagination['last_page'])
                            @if($end < $pagination['last_page'] - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['last_page']]) }}">
                                    {{ $pagination['last_page'] }}
                                </a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if($pagination['current_page'] < $pagination['last_page'])
                            <li class="page-item">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}">
                                    Next
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>

                <div class="text-center text-muted mt-3">
                    Showing {{ $pagination['from'] }} to {{ $pagination['to'] }} of {{ $pagination['total'] }} results
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit form on sort change
    document.getElementById('sort_by').addEventListener('change', function() {
        this.form.submit();
    });

    // Add loading state to filter form
    document.querySelector('form[method="GET"]').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
            btn.disabled = true;
        }
    });

    // Auto-submit on category change
    document.getElementById('category_id').addEventListener('change', function() {
        this.form.submit();
    });

    // Add card hover effects
    document.querySelectorAll('.card-hover').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<style>
    .search-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }

    .category-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .price-tag {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2e7d32;
    }

    .card-hover {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .badge-sm {
        font-size: 0.7rem;
        padding: 2px 6px;
    }

    @media (max-width: 768px) {
        .search-section .row {
            row-gap: 1rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }

        .d-flex.gap-2 .btn {
            width: 100%;
        }
    }
</style>
@endsection

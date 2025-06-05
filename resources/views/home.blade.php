<x-template title="Product Manager - Home" bodyClass="bg-light">
    <!-- Hero Section -->
    <div class="bg-primary text-white py-5 mb-5 rounded">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">
                <i class="bi bi-shop me-3"></i>TOKO SAYA
            </h1>
            <p class="lead mb-4">Manage products with ease and efficiency</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('products') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-lg"
                               placeholder="Search products..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-primary mb-3">
                        <i class="bi bi-box-seam display-4"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ number_format($stats['total_products']) }}</h3>
                    <p class="text-muted mb-0">Total Products</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-success mb-3">
                        <i class="bi bi-tags display-4"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ number_format($stats['total_categories']) }}</h3>
                    <p class="text-muted mb-0">Categories</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-warning mb-3">
                        <i class="bi bi-currency-dollar display-4"></i>
                    </div>
                    <h3 class="fw-bold text-warning">Rp {{ number_format($stats['avg_price'], 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Average Price</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-info mb-3">
                        <i class="bi bi-archive display-4"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ number_format($stats['total_stock']) }}</h3>
                    <p class="text-muted mb-0">Total Stock</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-tags me-2"></i>Shop by Categories</h2>
                <a href="{{ route('products') }}" class="btn btn-outline-primary">
                    View All Products <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @foreach($categories as $category)
        <div class="col-md-6 col-lg-3 mb-3">
            <a href="{{ route('products', ['category' => $category->id]) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm category-card">
                    <div class="card-body text-center">
                        <div class="mb-3" style="color: {{ $category->color }}">
                            <i class="bi bi-tag display-5"></i>
                        </div>
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">{{ $category->description }}</p>
                        <span class="badge" style="background-color: {{ $category->color }}">
                            {{ $category->products_count }} products
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Featured Products -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-star me-2"></i>Featured Products</h2>
                <a href="{{ route('products') }}" class="btn btn-outline-success">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        @foreach($featuredProducts as $product)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm product-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">
                            <i class="bi bi-box me-2" style="color: {{ $product->category->color }}"></i>
                            {{ $product->name }}
                        </h5>
                        <span class="badge" style="background-color: {{ $product->category->color }}">
                            {{ $product->category->name }}
                        </span>
                    </div>
                    <p class="card-text flex-grow-1 text-muted">{{ Str::limit($product->description, 80) }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="text-success fw-bold mb-0 fs-5">
                                {{ $product->formatted_price }}
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-box-seam me-1"></i>{{ $product->stock }} in stock
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm flex-fill">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h3 class="mb-4">Quick Actions</h3>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('products.create') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Add New Product
                        </a>
                        <a href="{{ route('products') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-list-ul me-2"></i>Browse Products
                        </a>
                        <a href="{{ route('products', ['sort_by' => 'created_at', 'sort_order' => 'desc']) }}" class="btn btn-info btn-lg">
                            <i class="bi bi-clock-history me-2"></i>Recent Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .category-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
        }
    </style>
    @endpush
</x-template>

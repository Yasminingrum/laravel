<x-template title="Products List">
    <x-slot name="header">
        <h1><i class="bi bi-box-seam me-2"></i>Products List</h1>
        <x-button href="{{ route('products.create') }}" type="success">
            <i class="bi bi-plus-circle me-1"></i>Add new product
        </x-button>
    </x-slot>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Products</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Search by name or description...">
                </div>

                <!-- Category Filter -->
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="col-md-2">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input type="number" class="form-control" id="min_price" name="min_price"
                           value="{{ request('min_price') }}" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input type="number" class="form-control" id="max_price" name="max_price"
                           value="{{ request('max_price') }}" placeholder="Max">
                </div>

                <!-- Submit Button -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <small class="text-muted">
                @if($products->total() > 0)
                    Found {{ $products->total() }} products
                @else
                    No products found
                @endif
            </small>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;" onchange="updateSort(this, 'sort_by')">
                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Sort by Price</option>
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sort by Date</option>
            </select>
            <select class="form-select form-select-sm" style="width: auto;" onchange="updateSort(this, 'sort_order')">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">
                                <i class="bi bi-box me-2 text-primary"></i>{{ $product->name }}
                            </h5>
                            <span class="badge" style="background-color: {{ $product->category->color }}">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <p class="card-text flex-grow-1">{{ $product->description }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-success fw-bold mb-0">
                                    <i class="bi bi-currency-dollar me-1"></i>{{ $product->formatted_price }}
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-box-seam me-1"></i>Stock: {{ $product->stock }}
                                </small>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm flex-fill">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm flex-fill">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm flex-fill"
                                        onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <x-alert type="info" :dismissible="false">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>No products found.</strong>
                    @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                        <a href="{{ route('products') }}" class="alert-link">Clear filters</a> or
                    @endif
                    <a href="{{ route('products.create') }}" class="alert-link">Add your first product</a>
                </x-alert>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-5">
            {{ $products->links('pagination.custom') }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the product <strong id="productName"></strong>?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Delete Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateSort(select, param) {
            const url = new URL(window.location);
            url.searchParams.set(param, select.value);
            window.location.href = url.toString();
        }

        function confirmDelete(productId, productName) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('deleteForm').action = `/products/delete/${productId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
    @endpush
</x-template>

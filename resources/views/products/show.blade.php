@extends('layouts.app')

@section('title', $product->name . ' - Product Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top"
                         style="height: 400px; object-fit: cover;" alt="{{ $product->name }}">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 400px;">
                        <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <!-- Product Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="display-6 fw-bold">{{ $product->name }}</h1>
                            <span class="category-badge">{{ $product->category->name }}</span>
                        </div>
                        <div class="text-end">
                            <div class="price-tag fs-4 mb-2">{{ $product->formatted_price }}</div>
                            @if($product->is_expensive)
                                <span class="badge bg-warning">Premium Product</span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Product Information</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="fas fa-box text-primary mb-2"></i>
                                    <div class="fw-bold">{{ $product->stock }}</div>
                                    <small class="text-muted">In Stock</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <i class="fas fa-tag text-success mb-2"></i>
                                    <div class="fw-bold">{{ $product->formatted_discounted_price }}</div>
                                    <small class="text-muted">Discounted Price</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Description</h5>
                        <p class="text-dark">{{ $product->description }}</p>
                    </div>

                    <!-- Product Meta -->
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Product Details</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <strong>Category:</strong> {{ $product->category->name }}
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Stock:</strong>
                                @if($product->stock > 0)
                                    <span class="text-success">{{ $product->stock }} available</span>
                                @else
                                    <span class="text-danger">Out of stock</span>
                                @endif
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Updated:</strong> {{ $product->updated_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2">
                        <x-button
                            href="{{ route('products.list') }}"
                            variant="outline-secondary"
                            icon="fas fa-arrow-left"
                        >
                            Back to Products
                        </x-button>

                        <x-button
                            href="{{ route('products.edit', $product->id) }}"
                            variant="warning"
                            icon="fas fa-edit"
                        >
                            Edit Product
                        </x-button>

                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <x-button
                                type="submit"
                                variant="danger"
                                icon="fas fa-trash"
                                onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')"
                            >
                                Delete Product
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                @php
                    $relatedProducts = App\Models\Product::where('category_id', $product->category_id)
                                                         ->where('id', '!=', $product->id)
                                                         ->take(3)
                                                         ->get();
                @endphp

                @forelse($relatedProducts as $relatedProduct)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm card-hover h-100">
                            @if($relatedProduct->image_url)
                                <img src="{{ $relatedProduct->image_url }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                            @else
                                <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fa-2x"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                <p class="card-text text-muted small flex-grow-1">{{ Str::limit($relatedProduct->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="price-tag small">{{ $relatedProduct->formatted_price }}</span>
                                    <x-button
                                        href="{{ route('products.show', $relatedProduct->id) }}"
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
                    <div class="col-12">
                        <p class="text-muted">No related products found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image zoom functionality
    @if($product->image_url)
    document.querySelector('.card-img-top').addEventListener('click', function() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}">
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    });
    @endif

    // Add hover effects
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
</style>
@endsection

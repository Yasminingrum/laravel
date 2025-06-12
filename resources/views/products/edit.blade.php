@extends('layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Product: {{ $product->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $product->name) }}"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Enter product description"
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price and Stock Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control @error('price') is-invalid @enderror"
                                               id="price"
                                               name="price"
                                               value="{{ old('price', $product->price) }}"
                                               min="0"
                                               step="1000"
                                               placeholder="0"
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           id="stock"
                                           name="stock"
                                           value="{{ old('stock', $product->stock) }}"
                                           min="0"
                                           placeholder="0"
                                           required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image URL -->
                        <div class="mb-4">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="url"
                                   class="form-control @error('image_url') is-invalid @enderror"
                                   id="image_url"
                                   name="image_url"
                                   value="{{ old('image_url', $product->image_url) }}"
                                   placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Provide a URL for the product image</div>

                            @if($product->image_url)
                                <div class="mt-2">
                                    <p class="text-muted small">Current image:</p>
                                    <img src="{{ $product->image_url }}" class="img-thumbnail"
                                         style="max-width: 200px; max-height: 200px;" alt="Current image">
                                </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <x-button
                                href="{{ route('products.list') }}"
                                variant="outline-secondary"
                                icon="fas fa-arrow-left"
                            >
                                Back to Products
                            </x-button>

                            <div class="d-flex gap-2">
                                <x-button
                                    href="{{ route('products.show', $product->id) }}"
                                    variant="outline-info"
                                    icon="fas fa-eye"
                                >
                                    View Product
                                </x-button>

                                <x-button
                                    type="submit"
                                    variant="warning"
                                    icon="fas fa-save"
                                >
                                    Update Product
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview image when URL is changed
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value;
        const existingPreview = document.getElementById('new-image-preview');

        if (existingPreview) {
            existingPreview.remove();
        }

        if (url && isValidUrl(url)) {
            const preview = document.createElement('div');
            preview.id = 'new-image-preview';
            preview.className = 'mt-2';
            preview.innerHTML = `
                <p class="text-muted small">New image preview:</p>
                <img src="${url}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;"
                     onerror="this.style.display='none'" alt="New image preview">
            `;
            this.parentNode.appendChild(preview);
        }
    });

    // Validate URL format
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    // Format price input
    document.getElementById('price').addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toString();
            this.value = value;
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const requiredFields = ['name', 'category_id', 'description', 'price', 'stock'];
        let isValid = true;

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
</script>
@endsection

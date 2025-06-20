@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New Product
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                      required>{{ old('description') }}</textarea>
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
                                               value="{{ old('price') }}"
                                               min="0"
                                               step="1000"
                                               placeholder="0"
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Enter price in Indonesian Rupiah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           id="stock"
                                           name="stock"
                                           value="{{ old('stock', 0) }}"
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
                                   value="{{ old('image_url') }}"
                                   placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional: Provide a URL for the product image</small>
                        </div>

                        <!-- Image Preview Container -->
                        <div class="mb-3" id="image-preview-container" style="display: none;">
                            <label class="form-label">Image Preview</label>
                            <div class="border rounded p-3 text-center">
                                <img id="image-preview" src="" alt="Product preview" class="img-fluid" style="max-height: 200px;">
                            </div>
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
                                    type="reset"
                                    variant="outline-warning"
                                    icon="fas fa-undo"
                                >
                                    Reset Form
                                </x-button>

                                <x-button
                                    type="submit"
                                    variant="primary"
                                    icon="fas fa-save"
                                >
                                    Save Product
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Tips -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb text-warning"></i>
                        Tips for Adding Products
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Use clear, descriptive product names
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Write detailed descriptions to help customers
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Set competitive prices for your market
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Keep stock levels accurate and updated
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            High-quality images increase sales
                        </li>
                    </ul>
                </div>
            </div>

            @if(isset($insights))
            <!-- Market Insights -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-chart-line text-info"></i>
                        Market Insights
                    </h6>
                    @if(isset($insights['average_price']))
                        <p class="small mb-2">
                            <strong>Average Price:</strong>
                            Rp {{ number_format($insights['average_price']) }}
                        </p>
                    @endif
                    @if(isset($insights['suggested_stock']))
                        <p class="small mb-0">
                            <strong>Suggested Stock:</strong>
                            {{ round($insights['suggested_stock']) }} items
                        </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image URL preview functionality
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value;
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');

        if (url && isValidUrl(url)) {
            preview.src = url;
            preview.onload = function() {
                container.style.display = 'block';
            };
            preview.onerror = function() {
                container.style.display = 'none';
            };
        } else {
            container.style.display = 'none';
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

    // Auto-format price input
    document.getElementById('price').addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toString();
            this.value = value;
        }
    });

    // Form validation enhancement
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

    // Reset form functionality
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        document.getElementById('image-preview-container').style.display = 'none';

        // Clear validation states
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
    });
</script>
@endsection

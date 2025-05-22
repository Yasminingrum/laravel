<x-template :title="isset($product) ? 'Edit Product' : 'Create Product'">
    <x-slot name="header">
        <h1><i class="bi bi-{{ isset($product) ? 'pencil-square' : 'plus-circle' }} me-2"></i>
            {{ isset($product) ? 'Edit Product' : 'Add New Product' }}
        </h1>
        <x-button href="{{ route('products') }}" type="secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to List
        </x-button>
    </x-slot>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($product) ? route('products.update', $product['id']) : route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="{{ isset($product) ? $product['name'] : old('name') }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="4"
                        >{{ isset($product) ? $product['description'] : old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            class="form-control"
                            id="price"
                            name="price"
                            value="{{ isset($product) ? $product['price'] : old('price') }}"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-{{ isset($product) ? 'check-circle' : 'plus-circle' }} me-1"></i>
                            {{ isset($product) ? 'Update Product' : 'Create Product' }}
                        </button>
                        <a href="{{ route('products') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-template>

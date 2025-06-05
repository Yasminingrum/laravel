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
                    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ isset($product) ? $product->name : old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id"
                                required
                            >
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ (isset($product) ? $product->category_id : old('category_id')) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="4"
                            >{{ isset($product) ? $product->description : old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        class="form-control @error('price') is-invalid @enderror"
                                        id="price"
                                        name="price"
                                        value="{{ isset($product) ? $product->price : old('price') }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                    <input
                                        type="number"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        id="stock"
                                        name="stock"
                                        value="{{ isset($product) ? $product->stock : old('stock') }}"
                                        min="0"
                                        required
                                    >
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
    </div>
</x-template>

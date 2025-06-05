<x-template title="Product Detail">
    <x-slot name="header">
        <h1><i class="bi bi-eye me-2"></i>Product Detail</h1>
        <div class="d-flex gap-2">
            <x-button href="{{ route('products.edit', $product->id) }}" type="warning" size="sm">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </x-button>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
            <x-button href="{{ route('products') }}" type="secondary" size="sm">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </x-button>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="bi bi-box me-2"></i>Product Information</h3>
                    <span class="badge fs-6" style="background-color: {{ $product->category->color }}">
                        {{ $product->category->name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Product ID:</strong>
                        </div>
                        <div class="col-md-9">
                            #{{ $product->id }}
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Product Name:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $product->name }}
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Category:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge" style="background-color: {{ $product->category->color }}">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $product->description ?? 'No description available' }}
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Price:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-success fs-6">
                                {{ $product->formatted_price }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Stock:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge {{ $product->stock > 0 ? 'bg-info' : 'bg-danger' }} fs-6">
                                {{ $product->stock }} {{ $product->stock > 1 ? 'items' : 'item' }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $product->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        function confirmDelete(productId, productName) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('deleteForm').action = `/products/delete/${productId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
    @endpush
</x-template>

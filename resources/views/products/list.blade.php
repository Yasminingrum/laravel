<x-template title="Products List">
    <x-slot name="header">
        <h1><i class="bi bi-box-seam me-2"></i>Products List</h1>
        <x-button href="{{ route('products.create') }}" type="success">
            <i class="bi bi-plus-circle me-1"></i>Add new product
        </x-button>
    </x-slot>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <i class="bi bi-box me-2 text-primary"></i>{{ $product['name'] }}
                        </h5>
                        <p class="card-text flex-grow-1">{{ $product['description'] }}</p>
                        <div class="mt-auto">
                            <p class="text-success fw-bold mb-3">
                                <i class="bi bi-currency-dollar me-1"></i>Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product['id']) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                                <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
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
                    <a href="{{ route('products.create') }}" class="alert-link">Add your first product</a>
                </x-alert>
            </div>
        @endforelse
    </div>

    @if (count($products) > 0)
        <x-alert type="info" :dismissible="false" :icon="false" class="mt-4">
            <i class="bi bi-info-circle me-1"></i>Showing {{ count($products) }} products
        </x-alert>
    @endif
</x-template>

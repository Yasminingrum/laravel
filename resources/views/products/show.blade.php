<x-template title="Product Detail">
    <x-slot name="header">
        <h1><i class="bi bi-eye me-2"></i>Product Detail</h1>
        <div class="d-flex gap-2">
            <x-button href="{{ route('products.edit', $product['id']) }}" type="warning" size="sm">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </x-button>
            <x-button href="{{ route('products') }}" type="secondary" size="sm">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </x-button>
        </div>
    </x-slot>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-box me-2"></i>Product Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Product ID:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $product['id'] }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Product Name:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $product['name'] }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $product['description'] }}
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-3">
                        <strong>Price:</strong>
                    </div>
                    <div class="col-md-9">
                        <span class="badge bg-success fs-6">
                            Rp {{ number_format($product['price'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-template>

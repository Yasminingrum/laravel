@props(['title' => 'Laravel Application', 'bodyClass' => '', 'containerClass' => 'container'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    @stack('styles')
</head>
<body class="{{ $bodyClass }}">
    <!-- Navigation -->
    @isset($navigation)
        {{ $navigation }}
    @else
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('products') }}">
                    <i class="bi bi-box-seam me-2"></i>Product Manager
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}"
                               href="{{ route('products') }}">
                                <i class="bi bi-list-ul me-1"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}"
                               href="{{ route('products.create') }}">
                                <i class="bi bi-plus-circle me-1"></i>Add Product
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endisset

    <!-- Main Content -->
    <main class="{{ $containerClass }} mt-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <x-alert type="success" class="mb-4">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" class="mb-4">
                {{ session('error') }}
            </x-alert>
        @endif

        @if(session('warning'))
            <x-alert type="warning" class="mb-4">
                {{ session('warning') }}
            </x-alert>
        @endif

        @if(session('info'))
            <x-alert type="info" class="mb-4">
                {{ session('info') }}
            </x-alert>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <x-alert type="danger" class="mb-4">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <!-- Page Header -->
        @isset($header)
            <div class="d-flex justify-content-between align-items-center mb-4">
                {{ $header }}
            </div>
        @endisset

        <!-- Main Content Slot -->
        {{ $slot }}
    </main>

    <!-- Footer -->
    @isset($footer)
        {{ $footer }}
    @else
        <footer class="bg-light mt-5 py-4">
            <div class="container text-center">
                <p class="text-muted mb-0">
                    &copy; {{ date('Y') }} Product Manager. Built with Laravel & Bootstrap.
                </p>
            </div>
        </footer>
    @endisset

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>

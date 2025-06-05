<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProductController;

// ========== HOME ROUTE ==========
Route::get('/', [ProductController::class, 'home'])->name('home');

// ========== PRODUCT ROUTES ==========
Route::prefix('products')->controller(ProductController::class)->group(function () {
    // Basic CRUD routes
    Route::get('/', 'index')->name('products');
    Route::get('/create', 'create')->name('products.create');
    Route::post('/store', 'store')->name('products.store');
    Route::get('/show/{id}', 'show')->name('products.show');
    Route::get('/edit/{id}', 'edit')->name('products.edit');
    Route::post('/update/{id}', 'update')->name('products.update');
    Route::delete('/delete/{id}', 'destroy')->name('products.destroy');

    // ========== ENHANCED ROUTES ==========

    // Analytics & Reports
    Route::get('/analytics', 'analytics')->name('products.analytics');
    Route::get('/export', 'export')->name('products.export');

    // Bulk Operations
    Route::post('/bulk-update-stock', 'bulkUpdateStock')->name('products.bulk-update-stock');

    // Status Management
    Route::post('/toggle-status/{id}', 'toggleStatus')->name('products.toggle-status');
    Route::post('/toggle-featured/{id}', 'toggleFeatured')->name('products.toggle-featured');

    // Product Management
    Route::post('/duplicate/{id}', 'duplicate')->name('products.duplicate');

    // AJAX Routes
    Route::get('/by-category/{categoryId}', 'getByCategory')->name('products.by-category');
});


// ========== API ROUTES FOR AJAX CALLS ==========
Route::prefix('api')->name('api.')->group(function () {
    // Product API endpoints
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
    Route::get('/products/statistics', [ProductController::class, 'analytics'])->name('products.statistics');

    // Quick actions API
    Route::post('/products/{id}/quick-update', [ProductController::class, 'quickUpdate'])->name('products.quick-update');
});

// ========== SHORTCUT ROUTES ==========
// Quick access routes for common actions
Route::get('/featured-products', function () {
    return redirect()->route('products', ['featured' => 1]);
})->name('featured-products');

Route::get('/on-sale-products', function () {
    return redirect()->route('products', ['on_sale' => 1]);
})->name('on-sale-products');

Route::get('/out-of-stock', function () {
    return redirect()->route('products', ['stock_status' => 'out_of_stock']);
})->name('out-of-stock-products');

Route::get('/low-stock', function () {
    return redirect()->route('products', ['stock_status' => 'low_stock']);
})->name('low-stock-products');

// ========== CATEGORY FILTER SHORTCUTS ==========
// Dynamic category routes (optional)
Route::get('/category/{category:slug}', function ($category) {
    return redirect()->route('products', ['category' => $category->id]);
})->name('category.products');

// Price range shortcuts
Route::get('/under-100k', function () {
    return redirect()->route('products', ['price_range' => 'under_100k']);
})->name('products.under-100k');

Route::get('/budget-friendly', function () {
    return redirect()->route('products', ['price_range' => '100k_500k']);
})->name('products.budget-friendly');

Route::get('/premium', function () {
    return redirect()->route('products', ['price_range' => 'above_5m']);
})->name('products.premium');

// ========== DEVELOPMENT/TESTING ROUTES ==========
// Only enable in development environment
if (app()->environment('local', 'development')) {
    Route::prefix('dev')->name('dev.')->group(function () {
        // Test data generation
        Route::get('/generate-products/{count?}', function ($count = 10) {
            $products = \App\Models\Product::factory()->count($count)->create();
            return response()->json([
                'message' => "Generated {$count} test products",
                'products' => $products->pluck('name', 'id')
            ]);
        })->name('generate-products');

        Route::get('/generate-categories/{count?}', function ($count = 5) {
            $categories = \App\Models\Category::factory()->count($count)->create();
            return response()->json([
                'message' => "Generated {$count} test categories",
                'categories' => $categories->pluck('name', 'id')
            ]);
        })->name('generate-categories');

        // Database utilities
        Route::get('/seed-fresh', function () {
            Artisan::call('migrate:fresh --seed');
            return redirect()->route('home')->with('success', 'Database refreshed and seeded!');
        })->name('seed-fresh');

        Route::get('/clear-cache', function () {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            return redirect()->back()->with('success', 'All caches cleared!');
        })->name('clear-cache');
    });
}

// ========== FALLBACK ROUTES ==========
// Handle common route variations
Route::get('/product/{id}', function ($id) {
    return redirect()->route('products.show', $id);
});

Route::get('/products/category/{id}', function ($id) {
    return redirect()->route('products', ['category' => $id]);
});


// ========== QUICK 30 PRODUCTS GENERATION ROUTE ==========
Route::get('/generate-30-products', function () {
    try {
        // Pastikan categories ada
        $categories = \App\Models\Category::all();
        if ($categories->isEmpty()) {
            \App\Models\Category::create(['name' => 'Elektronik', 'description' => 'Produk elektronik', 'color' => '#007bff', 'is_active' => true, 'sort_order' => 1]);
            \App\Models\Category::create(['name' => 'Fashion', 'description' => 'Pakaian dan aksesoris', 'color' => '#e83e8c', 'is_active' => true, 'sort_order' => 2]);
            \App\Models\Category::create(['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga', 'color' => '#28a745', 'is_active' => true, 'sort_order' => 3]);
            \App\Models\Category::create(['name' => 'Olahraga', 'description' => 'Peralatan olahraga', 'color' => '#fd7e14', 'is_active' => true, 'sort_order' => 4]);
            $categories = \App\Models\Category::all();
        }

        // Generate 30 products
        \App\Models\Product::factory()->count(30)->create();

        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_categories' => \App\Models\Category::count(),
            'avg_price' => \App\Models\Product::avg('price'),
        ];

        return response()->json([
            'success' => true,
            'message' => '30 products generated successfully!',
            'stats' => $stats
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

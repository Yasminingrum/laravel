<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Public routes
Route::get('/', [ProductController::class, 'home'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Product routes (public browsing)
Route::get('/products', [ProductController::class, 'index'])->name('products.list');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{categoryId}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Cart routes (allow both guest and auth)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add');
Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout routes (allow guest to view, but require auth to process)
Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');


// ✅ ADD ALTERNATIVE ROUTE FOR TESTING
Route::get('/products-alt', [ProductController::class, 'indexAlternative'])->name('products.list.alt');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{categoryId}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Auth required routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Order routes (require auth)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

    // Admin Product routes (manual check inside controller)
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/admin/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('/admin/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('/admin/products/alerts', [ProductController::class, 'alerts'])->name('products.alerts');

    // Admin dashboard
    Route::get('/admin/dashboard', [ProductController::class, 'dashboard'])->name('admin.dashboard');


    // Admin order routes (manual check inside controller)
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});

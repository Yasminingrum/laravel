<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Home route
Route::get('/', [ProductController::class, 'home'])->name('home');

// Group routes untuk products
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('products');
    Route::get('/create', 'create')->name('products.create');
    Route::get('/edit/{id}', 'edit')->name('products.edit');
    Route::post('/store', 'store')->name('products.store');
    Route::post('/update/{id}', 'update')->name('products.update');
    Route::get('/show/{id}', 'show')->name('products.show');
    Route::delete('/delete/{id}', 'destroy')->name('products.destroy');
});

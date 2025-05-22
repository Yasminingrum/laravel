<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Data dummy untuk 20 produk
        $products = [];
        for ($i = 1; $i <= 20; $i++) {
            $products[] = [
                'id' => $i,
                'name' => 'Product ' . $i,
                'description' => 'Description for product ' . $i,
                'price' => rand(10000, 500000)
            ];
        }

        return view('products.list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Logika untuk menyimpan produk
        // Untuk sementara redirect ke index
        return redirect()->route('products')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Data dummy untuk produk spesifik
        $product = [
            'id' => $id,
            'name' => 'Product ' . $id,
            'description' => 'Description for product ' . $id,
            'price' => rand(10000, 500000)
        ];

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Data dummy untuk produk yang akan diedit
        $product = [
            'id' => $id,
            'name' => 'Product ' . $id,
            'description' => 'Description for product ' . $id,
            'price' => rand(10000, 500000)
        ];

        return view('products.form', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logika untuk update produk
        // Untuk sementara redirect ke index
        return redirect()->route('products')->with('success', 'Product updated successfully!');
    }
}

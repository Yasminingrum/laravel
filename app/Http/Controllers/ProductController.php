<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with advanced filtering.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->active()
            ->published();

        // Apply search using enhanced scope
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Price range filter using enhanced scope
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Stock status filters
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->inStock();
                    break;
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
            }
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // On sale filter
        if ($request->boolean('on_sale')) {
            $query->onSale();
        }

        // Advanced sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
            case 'price':
            case 'stock':
            case 'created_at':
                $query->orderBy($sortBy, $sortOrder);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $perPage = in_array($perPage, [6, 12, 24, 48]) ? $perPage : 12;

        $products = $query->paginate($perPage)->withQueryString();

        // Get categories for filter dropdown
        $categories = Category::active()->ordered()->withActiveProducts()->get();

        return view('products.list', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('products.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
        ]);

        // Set default values
        $validatedData['is_active'] = true;

        $product = Product::create($validatedData);

        return redirect()->route('products')
            ->with('success', "Product '{$product->name}' created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with(['category'])->findOrFail($id);
            return view('products.show', compact('product'));

        } catch (ModelNotFoundException $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::active()->ordered()->get();

            return view('products.form', compact('product', 'categories'));

        } catch (ModelNotFoundException $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
            ]);

            $product->update($validatedData);

            return redirect()->route('products')
                ->with('success', "Product '{$product->name}' updated successfully!");

        } catch (ModelNotFoundException $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found!');
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productName = $product->name;

            $product->delete();

            return redirect()->route('products')
                ->with('success', "Product '{$productName}' has been deleted successfully!");

        } catch (ModelNotFoundException $e) {
            return redirect()->route('products')
                ->with('error', 'Product not found!');
        } catch (Exception $e) {
            return redirect()->route('products')
                ->with('error', 'Failed to delete product. Please try again.');
        }
    }

    /**
     * Display the home page.
     */
    public function home()
    {
        $featuredProducts = Product::with('category')
            ->active()
            ->inRandomOrder()
            ->take(6)
            ->get();

        $categories = Category::withCount('products')->get();

        $stats = [
            'total_products' => Product::active()->count(),
            'total_categories' => Category::count(),
            'avg_price' => Product::active()->avg('price'),
            'total_stock' => Product::active()->sum('stock')
        ];

        return view('home', compact('featuredProducts', 'categories', 'stats'));
    }

    /**
     * Get analytics data for dashboard
     */
    public function analytics()
    {
        $products = Product::with('category')->active()->get();

        $analytics = [
            'total_products' => $products->count(),
            'avg_price' => $products->avg('price'),
            'total_value' => $products->sum(function($p) { return $p->price * $p->stock; }),
            'total_stock' => $products->sum('stock'),
        ];

        return response()->json($analytics);
    }

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->is_active = !$product->is_active;
            $product->save();

            $status = $product->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Product '{$product->name}' has been {$status}!");

        } catch (ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Product not found!');
        }
    }

    /**
     * Get products by category (AJAX)
     */
    public function getByCategory($categoryId)
    {
        $products = Product::with('category')
            ->active()
            ->where('category_id', $categoryId)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_price,
                    'stock' => $product->stock,
                ];
            });

        return response()->json($products);
    }
}

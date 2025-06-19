<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of products with advanced filtering using collection
     */
    public function index(Request $request)
    {
        // Debugging: Check total products in database
        $totalInDb = Product::count();
        Log::info("Total products in database: " . $totalInDb);

        // Get all products with relationships (cache for performance)
        $products = Cache::remember('products_with_categories', 300, function () {
            return Product::with('category')->get();
        });

        Log::info("Products retrieved from cache/database: " . $products->count());

        // Apply advanced filtering using collection methods
        $filteredProducts = $products->advancedFilter([
            'search' => $request->search,
            'category_id' => $request->category_id,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'stock_status' => $request->stock_status,
            'sort_by' => $request->sort_by ?? 'latest'
        ]);

        Log::info("Products after filtering: " . $filteredProducts->count());

        // Get pagination data using collection
        $perPage = $request->get('per_page', 20);
        $paginatedData = $filteredProducts->paginateCollection($perPage, $request->page ?? 1);

        Log::info("Paginated products: " . count($paginatedData['data']));

        // Get categories for filter dropdown
        $categories = Category::all();

        // Get quick statistics for the current filtered results
        $stats = $filteredProducts->quickStats();

        return view('products.list', [
            'products' => $paginatedData['data'],
            'pagination' => $paginatedData,
            'categories' => $categories,
            'filters' => $request->all(),
            'stats' => $stats,
            'total_found' => $filteredProducts->count(),
            'total_in_db' => $totalInDb,
            'per_page' => $perPage
        ]);
    }

    /**
     * Alternative method using direct database queries instead of collection
     */
    public function indexAlternative(Request $request)
    {
        Log::info("Using alternative index method");

        // Start with base query
        $query = Product::with('category');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            Log::info("Applied search filter: " . $search);
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
            Log::info("Applied category filter: " . $request->category_id);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
            Log::info("Applied min price filter: " . $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
            Log::info("Applied max price filter: " . $request->max_price);
        }

        // Apply stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<', 10);
                    break;
            }
            Log::info("Applied stock filter: " . $request->stock_status);
        }

        // Apply sorting
        switch ($request->get('sort_by')) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Get total before pagination
        $totalFound = $query->count();
        Log::info("Total products found before pagination: " . $totalFound);

        // Get paginated results
        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);

        Log::info("Products on current page: " . $products->count());

        // Get categories for filter
        $categories = Category::all();

        return view('products.list', [
            'products' => $products->items(),
            'pagination' => [
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'has_more' => $products->hasMorePages()
            ],
            'categories' => $categories,
            'filters' => $request->all(),
            'total_found' => $totalFound,
            'total_in_db' => Product::count(),
            'per_page' => $perPage,
            'paginatedProducts' => $products
        ]);
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();

        // Get some insights for the create form
        $products = Product::with('category')->get();
        $insights = [
            'average_price' => $products->averagePrice(),
            'popular_categories' => $products->groupByCategory()->map->count()->sortDesc()->take(3),
            'price_suggestions' => [
                'budget' => $products->filter(fn($p) => $p->price < 100000)->avg('price'),
                'premium' => $products->filter(fn($p) => $p->price > 500000)->avg('price')
            ]
        ];

        return view('products.form', compact('categories', 'insights'));
    }

    /**
     * Store a newly created product using collection validation
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);

        // Check for duplicate products using collection
        $existingProducts = Product::all();
        $duplicates = $existingProducts->filter(function ($product) use ($request) {
            return strtolower($product->name) === strtolower($request->name) &&
                   $product->category_id == $request->category_id;
        });

        if ($duplicates->count() > 0) {
            return back()->withErrors(['name' => 'A product with this name already exists in this category.'])
                        ->withInput();
        }

        // Create the product
        $product = Product::create($request->all());

        // Clear cache
        Cache::forget('products_with_categories');

        // Generate recommendations for the new product
        $allProducts = Product::with('category')->get();
        $recommendations = $allProducts->getRecommendations($product, 3);

        return redirect()->route('products.list')
                        ->with('success', 'Product created successfully!')
                        ->with('recommendations', $recommendations);
    }

    /**
     * Display the specified product with collection-based analytics
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $allProducts = Product::with('category')->get();

        // Get recommendations using collection
        $recommendations = $allProducts->getRecommendations($product, 6);

        // Get category statistics
        $categoryProducts = $allProducts->byCategory($product->category_id);
        $categoryStats = [
            'total_in_category' => $categoryProducts->count(),
            'average_price' => $categoryProducts->averagePrice(),
            'price_rank' => $categoryProducts->sortByDesc('price')->search($product) + 1,
            'stock_comparison' => [
                'this_product' => $product->stock,
                'category_average' => $categoryProducts->avg('stock')
            ]
        ];

        // Get pricing insights
        $pricingInsights = [
            'is_expensive' => $product->is_expensive,
            'price_tier' => $product->price_tier,
            'discount_price' => $product->discounted_price,
            'compared_to_average' => $product->price - $allProducts->averagePrice()
        ];

        return view('products.show', compact(
            'product',
            'recommendations',
            'categoryStats',
            'pricingInsights'
        ));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();

        // Get market insights for pricing suggestions
        $allProducts = Product::with('category')->get();
        $categoryProducts = $allProducts->byCategory($product->category_id);

        $insights = [
            'category_price_range' => [
                'min' => $categoryProducts->min('price'),
                'max' => $categoryProducts->max('price'),
                'average' => $categoryProducts->averagePrice()
            ],
            'similar_products' => $allProducts->getRecommendations($product, 3),
            'suggested_stock' => $categoryProducts->avg('stock'),
            'pricing_analysis' => $allProducts->pricingAnalysis()
        ];

        return view('products.edit', compact('product', 'categories', 'insights'));
    }

    /**
     * Update the specified product using collection validation
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);

        $product = Product::findOrFail($id);

        // Check for duplicates (excluding current product)
        $existingProducts = Product::where('id', '!=', $id)->get();
        $duplicates = $existingProducts->filter(function ($p) use ($request) {
            return strtolower($p->name) === strtolower($request->name) &&
                   $p->category_id == $request->category_id;
        });

        if ($duplicates->count() > 0) {
            return back()->withErrors(['name' => 'A product with this name already exists in this category.'])
                        ->withInput();
        }

        // Store old values for comparison
        $oldPrice = $product->price;
        $oldStock = $product->stock;

        // Update the product
        $product->update($request->all());

        // Clear cache
        Cache::forget('products_with_categories');

        // Generate change summary
        $changes = [];
        if ($oldPrice != $product->price) {
            $changes[] = "Price changed from Rp " . number_format($oldPrice) . " to Rp " . number_format($product->price);
        }
        if ($oldStock != $product->stock) {
            $changes[] = "Stock changed from {$oldStock} to {$product->stock}";
        }

        $message = 'Product updated successfully!';
        if (!empty($changes)) {
            $message .= ' Changes: ' . implode(', ', $changes);
        }

        return redirect()->route('products.list')->with('success', $message);
    }

    /**
     * Remove the specified product with collection-based validation
     */
    public function destroy($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Check if this is a critical product (high value or unique in category)
        $allProducts = Product::with('category')->get();
        $categoryProducts = $allProducts->byCategory($product->category_id);

        $warnings = [];

        // Check if it's expensive
        if ($product->is_expensive) {
            $warnings[] = 'This is a high-value product';
        }

        // Check if it's the only product in category
        if ($categoryProducts->count() === 1) {
            $warnings[] = 'This is the only product in its category';
        }

        // Check if it has high stock value
        $inventoryValue = $product->price * $product->stock;
        if ($inventoryValue > 1000000) {
            $warnings[] = 'This product has high inventory value (Rp ' . number_format($inventoryValue) . ')';
        }

        // Delete the product
        $productName = $product->name;
        $product->delete();

        // Clear cache
        Cache::forget('products_with_categories');

        $message = "Product '{$productName}' deleted successfully!";
        if (!empty($warnings)) {
            $message .= ' Note: ' . implode(', ', $warnings);
        }

        return redirect()->route('products.list')->with('success', $message);
    }

    /**
     * Homepage with collection-powered insights
     */
    public function home()
    {
        // Get all products with caching
        $products = Cache::remember('homepage_products', 300, function () {
            return Product::with('category')->get();
        });

        // Get homepage data using collection
        $homepageData = $products->forHomepage();

        // Get categories with product counts
        $categories = Category::withCount('products')->get();

        // Get comprehensive statistics
        $stats = [
            'total_products' => $products->count(),
            'total_categories' => $categories->count(),
            'inventory_value' => $products->totalInventoryValue(),
            'average_price' => $products->averagePrice(),
            'out_of_stock' => $products->outOfStock()->count(),
            'low_stock' => $products->lowStock()->count(),
            'in_stock' => $products->inStock()->count()
        ];

        // Get trending products
        $trending = $products->trending(6);

        // Get inventory insights
        $insights = $products->inventoryInsights();

        return view('home', [
            'featuredProducts' => $homepageData['featured'],
            'newArrivals' => $homepageData['new_arrivals'],
            'bestSellers' => $homepageData['best_sellers'],
            'onSale' => $homepageData['on_sale'],
            'trending' => $trending,
            'categories' => $categories,
            'stats' => $stats,
            'insights' => $insights
        ]);
    }

    /**
     * Dashboard with comprehensive analytics
     */
    public function dashboard()
    {
        $products = Product::with('category')->get();

        // Get performance metrics
        $metrics = $products->performanceMetrics();

        // Get inventory insights
        $insights = $products->inventoryInsights();

        // Get pricing analysis
        $pricingAnalysis = $products->pricingAnalysis();

        // Get category statistics
        $categoryStats = $products->categoryStatistics();

        // Get recent activity
        $recentActivity = [
            'products_added_today' => $products->filter(fn($p) => $p->created_at->isToday())->count(),
            'products_updated_today' => $products->filter(fn($p) => $p->updated_at->isToday() && !$p->created_at->isToday())->count(),
            'low_stock_alerts' => $products->lowStock()->count(),
            'out_of_stock_alerts' => $products->outOfStock()->count()
        ];

        return view('dashboard', compact(
            'metrics',
            'insights',
            'pricingAnalysis',
            'categoryStats',
            'recentActivity'
        ));
    }

    /**
     * Search with autocomplete and suggestions
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $products = Product::with('category')->get();
        $searchResults = $products->smartSearch($request->q);

        if ($request->ajax()) {
            return response()->json([
                'results' => $searchResults['results']->take(10),
                'suggestions' => $searchResults['suggestions'],
                'related_terms' => $searchResults['related_terms'],
                'total_found' => $searchResults['total_found']
            ]);
        }

        // For regular search page
        $paginatedResults = $searchResults['results']->paginateCollection(12, $request->page ?? 1);

        return view('products.search-results', [
            'query' => $request->q,
            'products' => $paginatedResults['data'],
            'pagination' => $paginatedResults,
            'suggestions' => $searchResults['suggestions'],
            'related_terms' => $searchResults['related_terms'],
            'total_found' => $searchResults['total_found']
        ]);
    }

    /**
     * Category view with collection analytics
     */
    public function category($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $allProducts = Product::with('category')->get();
        $categoryProducts = $allProducts->byCategory($categoryId);

        // Get category-specific statistics
        $categoryStats = [
            'total_products' => $categoryProducts->count(),
            'price_range' => $categoryProducts->priceStatistics(),
            'stock_info' => $categoryProducts->stockStatistics(),
            'inventory_value' => $categoryProducts->totalInventoryValue(),
            'featured_products' => $categoryProducts->inStock()->mostExpensive(3),
            'price_tiers' => $categoryProducts->byPriceTier()
        ];

        // Get recommendations (products from other categories)
        $otherProducts = $allProducts->filter(fn($p) => $p->category_id !== $categoryId);
        $recommendedCategories = $otherProducts->groupByCategory()
            ->map->count()
            ->sortDesc()
            ->take(3);

        return view('categories.show', [
            'category' => $category,
            'products' => $categoryProducts,
            'stats' => $categoryStats,
            'recommendedCategories' => $recommendedCategories
        ]);
    }

    /**
     * Export products with collection filtering
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,json,excel',
            'filters' => 'array'
        ]);

        $products = Product::with('category')->get();

        // Apply filters if provided
        if ($request->has('filters')) {
            $products = $products->advancedFilter($request->filters);
        }

        // Export data
        $exportData = $products->export($request->format);
        $filename = 'products_export_' . now()->format('Y_m_d_H_i_s');

        switch ($request->format) {
            case 'csv':
                return response()->streamDownload(function () use ($exportData) {
                    $handle = fopen('php://output', 'w');
                    foreach ($exportData as $row) {
                        fputcsv($handle, $row);
                    }
                    fclose($handle);
                }, $filename . '.csv', [
                    'Content-Type' => 'text/csv',
                ]);

            case 'json':
                return response()->json($exportData)
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '.json"');

            default:
                return response()->json([
                    'message' => 'Export format not supported',
                    'supported_formats' => ['csv', 'json']
                ], 400);
        }
    }

    /**
     * Bulk operations using collection methods
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_category,update_stock,apply_discount',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'value' => 'nullable'
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();
        $affectedCount = 0;

        switch ($request->action) {
            case 'delete':
                // Check for critical products before deletion
                $criticalProducts = $products->filter(function ($product) {
                    return $product->is_expensive || ($product->price * $product->stock) > 1000000;
                });

                if ($criticalProducts->count() > 0) {
                    return back()->withErrors([
                        'bulk_action' => 'Cannot delete high-value products in bulk. Please delete them individually.'
                    ]);
                }

                $affectedCount = $products->count();
                Product::whereIn('id', $request->product_ids)->delete();
                break;

            case 'update_category':
                $request->validate(['value' => 'required|exists:categories,id']);
                Product::whereIn('id', $request->product_ids)
                    ->update(['category_id' => $request->value]);
                $affectedCount = $products->count();
                break;

            case 'update_stock':
                $request->validate(['value' => 'required|integer|min:0']);
                Product::whereIn('id', $request->product_ids)
                    ->update(['stock' => $request->value]);
                $affectedCount = $products->count();
                break;

            case 'apply_discount':
                $request->validate(['value' => 'required|numeric|min:1|max:50']);
                $discountMultiplier = (100 - $request->value) / 100;

                foreach ($products as $product) {
                    $product->update(['price' => $product->price * $discountMultiplier]);
                }
                $affectedCount = $products->count();
                break;
        }

        // Clear cache
        Cache::forget('products_with_categories');
        Cache::forget('homepage_products');

        return back()->with('success',
            "Bulk action '{$request->action}' applied to {$affectedCount} products successfully!"
        );
    }

    /**
     * Get alerts for the current user/admin
     */
    public function alerts()
    {
        $products = Product::with('category')->get();

        $alerts = [
            'critical' => [
                'out_of_stock' => $products->outOfStock(),
                'high_value_low_stock' => $products->filter(function ($product) {
                    return $product->is_expensive && $product->stock < 5;
                })
            ],
            'warning' => [
                'low_stock' => $products->lowStock(),
                'needs_restock' => $products->needsRestock(5),
                'overpriced' => $products->filter(function ($product) use ($products) {
                    return $product->price > ($products->averagePrice() * 2);
                })
            ],
            'info' => [
                'new_products' => $products->filter(fn($p) => $p->created_at->diffInDays() < 7),
                'seasonal_opportunities' => $products->seasonal()
            ]
        ];

        return view('products.alerts', compact('alerts'));
    }
}

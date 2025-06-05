<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'color', 'icon',
        'is_featured', 'is_active', 'sort_order',
        'meta_title', 'meta_description'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products for the category.
     */
    public function activeProducts(): HasMany
    {
        return $this->products()->active();
    }

    /**
     * Get featured products for the category.
     */
    public function featuredProducts(): HasMany
    {
        return $this->products()->featured()->active();
    }

    /**
     * Get published products for the category.
     */
    public function publishedProducts(): HasMany
    {
        return $this->products()->published()->active();
    }

    /**
     * Get products that are on sale
     */
    public function productsOnSale(): HasMany
    {
        return $this->products()->onSale()->active();
    }

    /**
     * Get products that are in stock
     */
    public function productsInStock(): HasMany
    {
        return $this->products()->inStock()->active();
    }

    /**
     * Get products that are low on stock
     */
    public function productsLowStock(): HasMany
    {
        return $this->products()->lowStock();
    }

    /**
     * Get products that are out of stock
     */
    public function productsOutOfStock(): HasMany
    {
        return $this->products()->outOfStock();
    }

    // ========== CUSTOM ATTRIBUTES ==========

    /**
     * Get products count for the category.
     */
    protected function productsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->products()->count()
        );
    }

    /**
     * Get active products count for the category.
     */
    protected function activeProductsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->count()
        );
    }

    /**
     * Get featured products count for the category.
     */
    protected function featuredProductsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->featuredProducts()->count()
        );
    }

    /**
     * Get total value of products in category
     */
    protected function totalValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->get()->sum(function ($product) {
                return $product->price * $product->stock;
            })
        );
    }

    /**
     * Get formatted total value
     */
    protected function formattedTotalValue(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->total_value, 0, ',', '.')
        );
    }

    /**
     * Get total stock of products in category
     */
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->sum('stock')
        );
    }

    /**
     * Get average price of products in category
     */
    protected function averagePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->avg('price') ?: 0
        );
    }

    /**
     * Get formatted average price
     */
    protected function formattedAveragePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->average_price, 0, ',', '.')
        );
    }

    /**
     * Get highest priced product in category
     */
    protected function highestPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->max('price') ?: 0
        );
    }

    /**
     * Get lowest priced product in category
     */
    protected function lowestPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->min('price') ?: 0
        );
    }

    /**
     * Get price range as array
     */
    protected function priceRange(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'min' => $this->lowest_price,
                'max' => $this->highest_price
            ]
        );
    }

    /**
     * Check if category has products
     */
    protected function hasProducts(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->products()->exists()
        );
    }

    /**
     * Check if category has active products
     */
    protected function hasActiveProducts(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->activeProducts()->exists()
        );
    }

    /**
     * Get category URL
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('products', ['category' => $this->id])
        );
    }

    /**
     * Get category icon with fallback
     */
    protected function iconClass(): Attribute
    {
        return Attribute::make(
            get: fn () => 'bi bi-' . ($this->icon ?: 'tag')
        );
    }

    /**
     * Get category badge HTML
     */
    protected function badge(): Attribute
    {
        return Attribute::make(
            get: fn () => sprintf(
                '<span class="badge" style="background-color: %s">%s</span>',
                $this->color,
                $this->name
            )
        );
    }

    /**
     * Get low stock products count
     */
    protected function lowStockCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->productsLowStock()->count()
        );
    }

    /**
     * Get out of stock products count
     */
    protected function outOfStockCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->productsOutOfStock()->count()
        );
    }

    /**
     * Get products on sale count
     */
    protected function onSaleCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->productsOnSale()->count()
        );
    }

    // ========== QUERY SCOPES ==========

    /**
     * Scope a query to only include featured categories.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to order categories by sort order.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope a query to include categories with products.
     */
    public function scopeWithProducts(Builder $query): void
    {
        $query->has('products');
    }

    /**
     * Scope a query to include categories with active products.
     */
    public function scopeWithActiveProducts(Builder $query): void
    {
        $query->has('activeProducts');
    }

    /**
     * Scope to search categories by name or description
     */
    public function scopeSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    // ========== HELPER METHODS ==========

    /**
     * Get category statistics
     */
    public function getStatistics(): array
    {
        $products = $this->activeProducts()->get();

        return [
            'total_products' => $products->count(),
            'featured_products' => $products->where('is_featured', true)->count(),
            'digital_products' => $products->where('is_digital', true)->count(),
            'physical_products' => $products->where('is_digital', false)->count(),
            'total_stock' => $products->sum('stock'),
            'total_value' => $products->sum(fn($p) => $p->price * $p->stock),
            'average_price' => $products->avg('price'),
            'highest_price' => $products->max('price'),
            'lowest_price' => $products->min('price'),
            'on_sale_count' => $products->filter(fn($p) => $p->is_on_sale)->count(),
            'low_stock_count' => $products->filter(fn($p) => $p->is_low_stock)->count(),
            'out_of_stock_count' => $products->filter(fn($p) => $p->is_out_of_stock)->count(),
        ];
    }

    /**
     * Get top products in this category
     */
    public function getTopProducts(int $limit = 5, string $sortBy = 'stock')
    {
        $query = $this->activeProducts();

        switch ($sortBy) {
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->orderBy('created_at', 'desc');
                break;
            case 'stock':
            default:
                $query->orderBy('stock', 'desc');
                break;
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get products that need attention in this category
     */
    public function getProductsNeedingAttention(): array
    {
        return [
            'out_of_stock' => $this->productsOutOfStock()->get(),
            'low_stock' => $this->productsLowStock()->get(),
            'inactive' => $this->products()->where('is_active', false)->get(),
        ];
    }

    /**
     * Check if category is popular (has many products)
     */
    public function isPopular(int $threshold = 10): bool
    {
        return $this->active_products_count >= $threshold;
    }

    /**
     * Get similar categories based on price range
     */
    public function getSimilarCategories(int $limit = 3)
    {
        $avgPrice = $this->average_price;
        $priceThreshold = $avgPrice * 0.3; // 30% tolerance

        return static::where('id', '!=', $this->id)
            ->active()
            ->withActiveProducts()
            ->get()
            ->filter(function ($category) use ($avgPrice, $priceThreshold) {
                return abs($category->average_price - $avgPrice) <= $priceThreshold;
            })
            ->take($limit);
    }

    /**
     * Generate category report
     */
    public function generateReport(): array
    {
        $statistics = $this->getStatistics();
        $topProducts = $this->getTopProducts(10);
        $needsAttention = $this->getProductsNeedingAttention();

        return [
            'category' => [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'color' => $this->color,
                'is_featured' => $this->is_featured,
                'is_active' => $this->is_active,
            ],
            'statistics' => $statistics,
            'top_products' => $topProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_price,
                    'stock' => $product->stock,
                    'is_featured' => $product->is_featured,
                ];
            }),
            'alerts' => [
                'out_of_stock_count' => $needsAttention['out_of_stock']->count(),
                'low_stock_count' => $needsAttention['low_stock']->count(),
                'inactive_count' => $needsAttention['inactive']->count(),
            ],
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    // ========== MODEL EVENTS ==========

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

            if (empty($category->meta_title)) {
                $category->meta_title = $category->name;
            }

            if (empty($category->sort_order)) {
                $category->sort_order = static::max('sort_order') + 1;
            }
        });

        // Update slug when name changes
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}

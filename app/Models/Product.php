<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'price', 'compare_price', 'cost_price', 'category_id',
        'stock', 'min_stock', 'weight', 'dimensions',
        'sku', 'barcode', 'is_active', 'is_featured', 'is_digital',
        'requires_shipping', 'meta_title', 'meta_description', 'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'requires_shipping' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get related products (same category, excluding current product)
     */
    public function getRelatedProductsAttribute(): Collection
    {
        return static::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->active()
            ->limit(4)
            ->get();
    }

    // ========== CUSTOM ATTRIBUTES ==========

    /**
     * Format price for display with Indonesian Rupiah format
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->price, 0, ',', '.')
        );
    }

    /**
     * Format compare price (original price before discount)
     */
    protected function formattedComparePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price ?
                'Rp ' . number_format($this->compare_price, 0, ',', '.') : null
        );
    }

    /**
     * Format cost price for internal use
     */
    protected function formattedCostPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cost_price ?
                'Rp ' . number_format($this->cost_price, 0, ',', '.') : null
        );
    }

    /**
     * Calculate discount percentage
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price && $this->compare_price > $this->price ?
                round((($this->compare_price - $this->price) / $this->compare_price) * 100) : 0
        );
    }

    /**
     * Calculate profit margin
     */
    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cost_price ?
                round((($this->price - $this->cost_price) / $this->price) * 100, 2) : 0
        );
    }

    /**
     * Calculate profit amount
     */
    protected function profitAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->cost_price ?
                $this->price - $this->cost_price : 0
        );
    }

    /**
     * Check if product is on sale (has compare price)
     */
    protected function isOnSale(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price && $this->compare_price > $this->price
        );
    }

    /**
     * Check if product is low stock
     */
    protected function isLowStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock <= $this->min_stock && $this->stock > 0
        );
    }

    /**
     * Check if product is out of stock
     */
    protected function isOutOfStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock <= 0
        );
    }

    /**
     * Get stock status with color coding
     */
    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_out_of_stock) {
                    return ['status' => 'Out of Stock', 'class' => 'danger', 'icon' => 'x-circle'];
                } elseif ($this->is_low_stock) {
                    return ['status' => 'Low Stock', 'class' => 'warning', 'icon' => 'exclamation-triangle'];
                } else {
                    return ['status' => 'In Stock', 'class' => 'success', 'icon' => 'check-circle'];
                }
            }
        );
    }

    /**
     * Get short description or truncated description
     */
    protected function displayDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->short_description ?:
                Str::limit($this->description, 100, '...')
        );
    }

    /**
     * Get product thumbnail URL (placeholder for future image implementation)
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => 'https://via.placeholder.com/300x300/007bff/ffffff?text=' . urlencode(substr($this->name, 0, 10))
        );
    }

    /**
     * Get product URL
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('products.show', $this->id)
        );
    }

    /**
     * Get edit URL
     */
    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('products.edit', $this->id)
        );
    }

    /**
     * Get delete URL
     */
    protected function deleteUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('products.destroy', $this->id)
        );
    }

    // ========== QUERY SCOPES ==========

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include published products.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('published_at', '<=', now())
              ->orWhereNull('published_at');
    }

    /**
     * Scope a query to search products by name, description, or SKU.
     */
    public function scopeSearch(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('short_description', 'like', '%' . $search . '%')
              ->orWhere('sku', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope a query to filter products by price range.
     */
    public function scopePriceRange(Builder $query, ?float $minPrice, ?float $maxPrice): void
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
    }

    /**
     * Scope a query to filter products by stock status.
     */
    public function scopeInStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    public function scopeLowStock(Builder $query): void
    {
        $query->whereColumn('stock', '<=', 'min_stock')
              ->where('stock', '>', 0);
    }

    public function scopeOutOfStock(Builder $query): void
    {
        $query->where('stock', '<=', 0);
    }

    /**
     * Scope for products on sale
     */
    public function scopeOnSale(Builder $query): void
    {
        $query->whereNotNull('compare_price')
              ->whereColumn('compare_price', '>', 'price');
    }

    /**
     * Scope for digital products
     */
    public function scopeDigital(Builder $query): void
    {
        $query->where('is_digital', true);
    }

    /**
     * Scope for physical products
     */
    public function scopePhysical(Builder $query): void
    {
        $query->where('is_digital', false);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory(Builder $query, $categoryId): void
    {
        $query->where('category_id', $categoryId);
    }

    /**
     * Scope for expensive products (above average)
     */
    public function scopeExpensive(Builder $query): void
    {
        $avgPrice = static::avg('price');
        $query->where('price', '>', $avgPrice);
    }

    /**
     * Scope for cheap products (below average)
     */
    public function scopeCheap(Builder $query): void
    {
        $avgPrice = static::avg('price');
        $query->where('price', '<=', $avgPrice);
    }

    // ========== COLLECTION METHODS ==========

    /**
     * Create a new collection instance for products
     */
    public function newCollection(array $models = []): ProductCollection
    {
        return new ProductCollection($models);
    }

    // ========== HELPER METHODS ==========

    /**
     * Check if product can be purchased
     */
    public function canBePurchased(): bool
    {
        return $this->is_active &&
               $this->stock > 0 &&
               ($this->published_at === null || $this->published_at <= now());
    }

    /**
     * Get savings amount if on sale
     */
    public function getSavingsAmount(): float
    {
        return $this->is_on_sale ? $this->compare_price - $this->price : 0;
    }

    /**
     * Get formatted savings amount
     */
    public function getFormattedSavingsAmount(): string
    {
        $savings = $this->getSavingsAmount();
        return $savings > 0 ? 'Rp ' . number_format($savings, 0, ',', '.') : '';
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $quantity, string $operation = 'set'): bool
    {
        switch ($operation) {
            case 'add':
                $this->stock += $quantity;
                break;
            case 'subtract':
                $this->stock = max(0, $this->stock - $quantity);
                break;
            case 'set':
            default:
                $this->stock = max(0, $quantity);
                break;
        }

        return $this->save();
    }

    /**
     * Generate SKU if not exists
     */
    public function generateSku(): string
    {
        if ($this->sku) {
            return $this->sku;
        }

        $prefix = strtoupper(substr($this->category->name ?? 'PROD', 0, 3));
        $suffix = str_pad($this->id ?? rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $suffix;
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }

            if (empty($product->meta_title)) {
                $product->meta_title = $product->name;
            }
        });

        // Auto-generate SKU after saving
        static::saved(function ($product) {
            if (empty($product->sku)) {
                $product->sku = $product->generateSku();
                $product->saveQuietly(); // Avoid infinite loop
            }
        });
    }
}

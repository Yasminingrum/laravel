<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

class ProductCollection extends Collection
{
    // ========== VALUE CALCULATIONS ==========

    /**
     * Get total value of all products in collection (price × stock)
     */
    public function totalValue(): float
    {
        return $this->sum(fn ($product) => $product->price * $product->stock);
    }

    /**
     * Get total potential revenue if all compare prices sold
     */
    public function totalPotentialRevenue(): float
    {
        return $this->sum(function ($product) {
            $price = $product->compare_price ?? $product->price;
            return $price * $product->stock;
        });
    }

    /**
     * Get total cost value (cost price × stock)
     */
    public function totalCostValue(): float
    {
        return $this->sum(function ($product) {
            return ($product->cost_price ?? 0) * $product->stock;
        });
    }

    /**
     * Get total potential profit
     */
    public function totalPotentialProfit(): float
    {
        return $this->totalValue() - $this->totalCostValue();
    }

    // ========== STOCK CALCULATIONS ==========

    /**
     * Get total stock count
     */
    public function totalStock(): int
    {
        return $this->sum('stock');
    }

    /**
     * Get total weight of all products
     */
    public function totalWeight(): float
    {
        return $this->sum(fn ($product) => ($product->weight ?? 0) * $product->stock);
    }

    // ========== PRICING STATISTICS ==========

    /**
     * Get average price
     */
    public function averagePrice(): float
    {
        return $this->avg('price') ?? 0;
    }

    /**
     * Get median price
     */
    public function medianPrice(): float
    {
        $sorted = $this->sortBy('price')->values();
        $count = $sorted->count();

        if ($count === 0) return 0;

        if ($count % 2 === 0) {
            return ($sorted[$count / 2 - 1]->price + $sorted[$count / 2]->price) / 2;
        }

        return $sorted[floor($count / 2)]->price;
    }

    /**
     * Get price range (min and max)
     */
    public function priceRange(): array
    {
        if ($this->isEmpty()) {
            return ['min' => 0, 'max' => 0];
        }

        return [
            'min' => $this->min('price'),
            'max' => $this->max('price')
        ];
    }

    // ========== FILTERING METHODS ==========

    /**
     * Get products that are on sale
     */
    public function onSale(): self
    {
        return $this->filter(fn ($product) => $product->is_on_sale ?? false);
    }

    /**
     * Get featured products
     */
    public function featured(): self
    {
        return $this->filter(fn ($product) => $product->is_featured ?? false);
    }

    /**
     * Get active products
     */
    public function active(): self
    {
        return $this->filter(fn ($product) => $product->is_active ?? true);
    }

    /**
     * Get low stock products
     */
    public function lowStock(): self
    {
        return $this->filter(fn ($product) =>
            isset($product->stock) && isset($product->min_stock) &&
            $product->stock <= $product->min_stock && $product->stock > 0
        );
    }

    /**
     * Get out of stock products
     */
    public function outOfStock(): self
    {
        return $this->filter(fn ($product) => ($product->stock ?? 0) <= 0);
    }

    /**
     * Get in stock products
     */
    public function inStock(): self
    {
        return $this->filter(fn ($product) => ($product->stock ?? 0) > 0);
    }

    /**
     * Get digital products
     */
    public function digital(): self
    {
        return $this->filter(fn ($product) => $product->is_digital ?? false);
    }

    /**
     * Get physical products
     */
    public function physical(): self
    {
        return $this->filter(fn ($product) => !($product->is_digital ?? false));
    }

    /**
     * Get products by category
     */
    public function byCategory($categoryId = null)
    {
        if ($categoryId) {
            return $this->filter(fn ($product) => $product->category_id == $categoryId);
        }

        return $this->groupBy('category.name');
    }

    /**
     * Get expensive products (above average price)
     */
    public function expensive(): self
    {
        $avgPrice = $this->averagePrice();
        return $this->filter(fn ($product) => $product->price > $avgPrice);
    }

    /**
     * Get cheap products (below average price)
     */
    public function cheap(): self
    {
        $avgPrice = $this->averagePrice();
        return $this->filter(fn ($product) => $product->price <= $avgPrice);
    }

    // ========== SORTING METHODS ==========

    /**
     * Sort by best sellers (highest stock assuming popularity)
     */
    public function bestSellers(): self
    {
        return $this->sortByDesc('stock');
    }

    /**
     * Sort by newest products
     */
    public function newest(): self
    {
        return $this->sortByDesc('created_at');
    }

    // ========== GROUPING METHODS ==========

    /**
     * Group products by category with stats
     */
    public function groupByCategoryWithStats(): Collection
    {
        return $this->groupBy('category.name')->map(function ($products) {
            return [
                'products' => $products,
                'count' => $products->count(),
                'total_value' => $products->sum(fn ($p) => $p->price * $p->stock),
                'total_stock' => $products->sum('stock'),
                'avg_price' => $products->avg('price'),
                'featured_count' => $products->filter(fn ($p) => $p->is_featured ?? false)->count(),
                'on_sale_count' => $products->filter(fn ($p) => $p->is_on_sale ?? false)->count(),
            ];
        });
    }

    /**
     * Group by stock status
     */
    public function groupByStockStatus(): Collection
    {
        return collect([
            'in_stock' => $this->inStock(),
            'low_stock' => $this->lowStock(),
            'out_of_stock' => $this->outOfStock(),
        ]);
    }

    /**
     * Group by price ranges
     */
    public function groupByPriceRanges(): Collection
    {
        return collect([
            'under_100k' => $this->filter(fn ($p) => $p->price < 100000),
            '100k_500k' => $this->filter(fn ($p) => $p->price >= 100000 && $p->price < 500000),
            '500k_1m' => $this->filter(fn ($p) => $p->price >= 500000 && $p->price < 1000000),
            '1m_5m' => $this->filter(fn ($p) => $p->price >= 1000000 && $p->price < 5000000),
            'above_5m' => $this->filter(fn ($p) => $p->price >= 5000000),
        ]);
    }

    // ========== UTILITY METHODS ==========

    /**
     * Format collection as options for select input
     */
    public function toSelectOptions(): array
    {
        return $this->pluck('name', 'id')->toArray();
    }

    /**
     * Get products with low stock warnings
     */
    public function stockAlerts(): array
    {
        return [
            'critical' => $this->outOfStock(),
            'warning' => $this->lowStock(),
            'ok' => $this->inStock()->filter(function ($p) {
                return isset($p->stock) && isset($p->min_stock) &&
                       $p->stock > $p->min_stock;
            }),
        ];
    }

    /**
     * Search products in collection
     */
    public function searchProducts(string $query): self
    {
        $query = strtolower($query);

        return $this->filter(function ($product) use ($query) {
            return str_contains(strtolower($product->name ?? ''), $query) ||
                   str_contains(strtolower($product->description ?? ''), $query) ||
                   str_contains(strtolower($product->sku ?? ''), $query);
        });
    }

    // ========== STATISTICS & ANALYTICS ==========

    /**
     * Calculate comprehensive collection statistics
     */
    public function statistics(): array
    {
        $stockAlerts = $this->stockAlerts();
        $priceRange = $this->priceRange();

        return [
            // Basic counts
            'total_products' => $this->count(),
            'active_products' => $this->active()->count(),
            'featured_products' => $this->featured()->count(),
            'digital_products' => $this->digital()->count(),
            'physical_products' => $this->physical()->count(),

            // Stock statistics
            'total_stock' => $this->totalStock(),
            'in_stock_count' => $stockAlerts['ok']->count(),
            'low_stock_count' => $stockAlerts['warning']->count(),
            'out_of_stock_count' => $stockAlerts['critical']->count(),

            // Financial statistics
            'total_value' => $this->totalValue(),
            'total_cost_value' => $this->totalCostValue(),
            'total_potential_profit' => $this->totalPotentialProfit(),
            'average_price' => $this->averagePrice(),
            'median_price' => $this->medianPrice(),
            'price_range' => $priceRange,

            // Sales statistics
            'on_sale_count' => $this->onSale()->count(),

            // Weight statistics
            'total_weight' => $this->totalWeight(),
            'average_weight' => $this->avg(fn ($p) => $p->weight ?? 0),

            // Category distribution
            'categories' => $this->groupBy('category_id')->map->count(),
        ];
    }

    /**
     * Get top performing products by various metrics
     */
    public function topPerformers(int $limit = 5): array
    {
        return [
            'highest_value' => $this->sortByDesc(fn ($p) => $p->price * $p->stock)->take($limit),
            'highest_stock' => $this->sortByDesc('stock')->take($limit),
            'highest_price' => $this->sortByDesc('price')->take($limit),
        ];
    }

    /**
     * Generate inventory report
     */
    public function inventoryReport(): array
    {
        $stockAlerts = $this->stockAlerts();
        $byCategory = $this->groupByCategoryWithStats();

        return [
            'summary' => $this->statistics(),
            'stock_alerts' => [
                'critical_count' => $stockAlerts['critical']->count(),
                'warning_count' => $stockAlerts['warning']->count(),
                'critical_products' => $stockAlerts['critical']->pluck('name', 'id'),
                'warning_products' => $stockAlerts['warning']->pluck('name', 'id'),
            ],
            'by_category' => $byCategory,
            'top_performers' => $this->topPerformers(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get products that need attention
     */
    public function needsAttention(): array
    {
        return [
            'out_of_stock' => $this->outOfStock(),
            'low_stock' => $this->lowStock(),
            'no_price' => $this->filter(fn ($p) => ($p->price ?? 0) <= 0),
            'no_category' => $this->filter(fn ($p) => !($p->category_id ?? null)),
            'inactive' => $this->filter(fn ($p) => !($p->is_active ?? true)),
            'missing_sku' => $this->filter(fn ($p) => empty($p->sku ?? null)),
        ];
    }
}

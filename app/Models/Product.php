<?php

namespace App\Models;

use App\Collections\ProductCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        'image_url'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    /**
     * Create a new Eloquent Collection instance.
     */
    public function newCollection(array $models = [])
    {
        return new ProductCollection($models);
    }

    // Relationship dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Custom Attributes
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->price * 0.9; // 10% discount
    }

    public function getFormattedDiscountedPriceAttribute()
    {
        return 'Rp ' . number_format($this->discounted_price, 0, ',', '.');
    }

    public function getIsExpensiveAttribute()
    {
        return $this->price > 100000;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'out_of_stock';
        } elseif ($this->stock < 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockLabelAttribute()
    {
        switch ($this->stock_status) {
            case 'out_of_stock':
                return 'Out of Stock';
            case 'low_stock':
                return 'Low Stock';
            default:
                return 'In Stock';
        }
    }

    public function getInventoryValueAttribute()
    {
        return $this->price * $this->stock;
    }

    public function getPriceTierAttribute()
    {
        if ($this->price < 50000) {
            return 'budget';
        } elseif ($this->price < 200000) {
            return 'mid_range';
        } elseif ($this->price < 1000000) {
            return 'premium';
        } else {
            return 'luxury';
        }
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    // Scope untuk filter harga
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // Scope untuk sorting
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }

    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    // Scope untuk status stock
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '>', 0)->where('stock', '<', $threshold);
    }

    // Scope untuk harga
    public function scopeExpensive($query, $threshold = 100000)
    {
        return $query->where('price', '>', $threshold);
    }

    public function scopeAffordable($query, $threshold = 100000)
    {
        return $query->where('price', '<=', $threshold);
    }

    // Scope untuk kategori
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Scope untuk featured products
    public function scopeFeatured($query)
    {
        return $query->inStock()->expensive()->orderByPrice('desc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    // Relationship dengan Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Custom attribute untuk menghitung total produk
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
}

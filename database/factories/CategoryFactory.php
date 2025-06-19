<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition()
    {
        $categories = [
            'Electronics' => 'Latest gadgets and electronic devices',
            'Clothing' => 'Fashion and apparel for all ages',
            'Books' => 'Educational and entertainment books',
            'Home & Garden' => 'Home improvement and gardening supplies',
            'Sports' => 'Sports equipment and accessories',
            'Beauty' => 'Cosmetics and personal care products',
            'Automotive' => 'Car parts and automotive accessories',
            'Toys' => 'Children toys and games',
            'Food & Beverages' => 'Groceries and beverages',
            'Health' => 'Health and wellness products'
        ];

        $name = $this->faker->randomElement(array_keys($categories));

        return [
            'name' => $name,
            'description' => $categories[$name],
            'slug' => Str::slug($name),
        ];
    }
}

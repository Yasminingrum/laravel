<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class Quick30ProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan categories ada
        if (Category::count() == 0) {
            Category::create(['name' => 'Elektronik', 'description' => 'Produk elektronik', 'color' => '#007bff']);
            Category::create(['name' => 'Fashion', 'description' => 'Pakaian dan aksesoris', 'color' => '#e83e8c']);
            Category::create(['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga', 'color' => '#28a745']);
            Category::create(['name' => 'Olahraga', 'description' => 'Peralatan olahraga', 'color' => '#fd7e14']);
        }

        // Generate 30 products
        Product::factory()->count(30)->create();

        $this->command->info('✅ 30 products created successfully!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Creating products...');

        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('❌ No categories found. Please run CategorySeeder first.');
            $this->command->info('💡 Run: php artisan db:seed --class=CategorySeeder');
            return;
        }

        $this->command->info("📦 Found {$categories->count()} categories");

        // Create products for each category
        $categories->each(function ($category) {
            $this->command->info("🔄 Creating products for category: {$category->name}");

            // Featured products (2-3 per category)
            $featuredCount = rand(2, 3);
            Product::factory()
                ->count($featuredCount)
                ->featured()
                ->active()
                ->create(['category_id' => $category->id]);

            $this->command->info("  ⭐ Created {$featuredCount} featured products");

            // Regular active products (5-8 per category)
            $regularCount = rand(5, 8);
            Product::factory()
                ->count($regularCount)
                ->active()
                ->create(['category_id' => $category->id]);

            $this->command->info("  ✅ Created {$regularCount} regular products");

            // Some low stock products (1-2 per category)
            $lowStockCount = rand(1, 2);
            Product::factory()
                ->count($lowStockCount)
                ->lowStock()
                ->active()
                ->create(['category_id' => $category->id]);

            $this->command->info("  ⚠️  Created {$lowStockCount} low stock products");

            // Some out of stock products (0-1 per category)
            if (rand(0, 1)) {
                Product::factory()
                    ->count(1)
                    ->outOfStock()
                    ->create(['category_id' => $category->id]);

                $this->command->info("  ❌ Created 1 out of stock product");
            }

            $this->command->info("  ✅ Finished category: {$category->name}");
            $this->command->info('');
        });

        // Create some special premium products across categories
        $this->command->info('🔄 Creating premium products...');
        $premiumProducts = Product::factory()
            ->count(5)
            ->expensive()
            ->featured()
            ->onSale()
            ->create();

        $this->command->info("💎 Created {$premiumProducts->count()} premium products");

        // Create some digital products
        $this->command->info('🔄 Creating digital products...');
        $digitalProducts = Product::factory()
            ->count(8)
            ->digital()
            ->active()
            ->cheap()
            ->create();

        $this->command->info("💻 Created {$digitalProducts->count()} digital products");

        // Create some inactive products for testing
        $this->command->info('🔄 Creating inactive products...');
        $inactiveProducts = Product::factory()
            ->count(12)
            ->create(['is_active' => false]);

        $this->command->info("😴 Created {$inactiveProducts->count()} inactive products");

        // Generate final statistics
        $this->displayStatistics();
    }

    private function displayStatistics(): void
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $digitalProducts = Product::where('is_digital', true)->count();
        $onSaleProducts = Product::whereNotNull('compare_price')
            ->whereColumn('compare_price', '>', 'price')
            ->count();

        $this->command->info('');
        $this->command->info('🎉 Products seeded successfully!');
        $this->command->info('=====================================');
        $this->command->info("📦 Total products: {$totalProducts}");
        $this->command->info("✅ Active products: {$activeProducts}");
        $this->command->info("⭐ Featured products: {$featuredProducts}");
        $this->command->info("💻 Digital products: {$digitalProducts}");
        $this->command->info("🏷️  On sale products: {$onSaleProducts}");
        $this->command->info("❌ Out of stock: {$outOfStockProducts}");
        $this->command->info("😴 Inactive products: " . ($totalProducts - $activeProducts));

        // Category breakdown
        $this->command->info('');
        $this->command->info('📊 Products by Category:');
        $this->command->info('========================');

        Category::withCount('products')->get()->each(function ($category) {
            $activeCount = $category->products()->where('is_active', true)->count();
            $this->command->info("  {$category->name}: {$category->products_count} total ({$activeCount} active)");
        });

        $this->command->info('');
        $this->command->info('💡 Sample products created with realistic data including:');
        $this->command->info('   - Product names with brands');
        $this->command->info('   - Realistic pricing in Indonesian Rupiah');
        $this->command->info('   - Stock levels and inventory management');
        $this->command->info('   - Featured and sale products');
        $this->command->info('   - SEO-friendly slugs and meta data');
        $this->command->info('   - Digital and physical product variants');
        $this->command->info('');
    }
}

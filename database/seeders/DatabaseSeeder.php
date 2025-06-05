<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Starting Laravel Product Management Database Seeding...');
        $this->command->info('=========================================================');

        $startTime = microtime(true);

        // Disable foreign key checks untuk menghindari constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Truncate tables untuk fresh seeding (opsional)
            if ($this->command->confirm('Do you want to truncate existing data?', false)) {
                $this->truncateTables();
            }

            // Order matters untuk foreign key constraints
            $this->call([
                CategorySeeder::class,
                ProductSeeder::class,
                // UserSeeder::class, // Uncomment jika ada UserSeeder
            ]);

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            $this->command->info('');
            $this->command->info('✅ Database seeding completed successfully!');
            $this->command->info("⏱️  Execution time: {$executionTime} seconds");

            // Display comprehensive summary
            $this->displayComprehensiveSummary();

            // Show usage examples
            $this->showUsageExamples();

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('❌ Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Truncate tables for fresh seeding
     */
    private function truncateTables(): void
    {
        $this->command->info('🗑️  Truncating existing data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate in correct order (children first)
        DB::table('products')->truncate();
        DB::table('categories')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Tables truncated successfully');
    }

    /**
     * Display comprehensive summary of seeded data
     */
    private function displayComprehensiveSummary(): void
    {
        $this->command->info('');
        $this->command->info('📊 SEEDING SUMMARY');
        $this->command->info('==================');

        // Categories summary
        $totalCategories = \App\Models\Category::count();
        $featuredCategories = \App\Models\Category::where('is_featured', true)->count();

        $this->command->info("📁 Categories: {$totalCategories} total ({$featuredCategories} featured)");

        // Products summary
        $totalProducts = \App\Models\Product::count();
        $activeProducts = \App\Models\Product::where('is_active', true)->count();
        $featuredProducts = \App\Models\Product::where('is_featured', true)->count();
        $digitalProducts = \App\Models\Product::where('is_digital', true)->count();
        $outOfStockProducts = \App\Models\Product::where('stock', '<=', 0)->count();

        $this->command->info("📦 Products: {$totalProducts} total");
        $this->command->info("  ✅ Active: {$activeProducts}");
        $this->command->info("  ⭐ Featured: {$featuredProducts}");
        $this->command->info("  💻 Digital: {$digitalProducts}");
        $this->command->info("  ❌ Out of stock: {$outOfStockProducts}");
        $this->command->info("  😴 Inactive: " . ($totalProducts - $activeProducts));

        // Price statistics
        $avgPrice = \App\Models\Product::where('is_active', true)->avg('price');
        $maxPrice = \App\Models\Product::where('is_active', true)->max('price');
        $minPrice = \App\Models\Product::where('is_active', true)->min('price');
        $totalStock = \App\Models\Product::where('is_active', true)->sum('stock');

        $this->command->info('');
        $this->command->info('💰 PRICING STATISTICS');
        $this->command->info('=====================');
        $this->command->info('  Average price: Rp ' . number_format($avgPrice, 0, ',', '.'));
        $this->command->info('  Highest price: Rp ' . number_format($maxPrice, 0, ',', '.'));
        $this->command->info('  Lowest price: Rp ' . number_format($minPrice, 0, ',', '.'));
        $this->command->info('  Total stock: ' . number_format($totalStock) . ' items');

        // Category breakdown
        $this->command->info('');
        $this->command->info('📊 PRODUCTS BY CATEGORY');
        $this->command->info('=======================');

        \App\Models\Category::withCount(['products', 'products as active_products_count' => function ($query) {
            $query->where('is_active', true);
        }])->orderBy('sort_order')->get()->each(function ($category) {
            $icon = $category->icon ?? '📦';
            $this->command->info("  {$icon} {$category->name}: {$category->products_count} total ({$category->active_products_count} active)");
        });
    }

    /**
     * Show usage examples and helpful commands
     */
    private function showUsageExamples(): void
    {
        $this->command->info('');
        $this->command->info('🚀 NEXT STEPS & USAGE EXAMPLES');
        $this->command->info('==============================');
        $this->command->info('');

        $this->command->info('🌐 Start the application:');
        $this->command->info('   php artisan serve');
        $this->command->info('   Visit: http://localhost:8000');
        $this->command->info('');

        $this->command->info('🔧 Useful Artisan Commands:');
        $this->command->info('   php artisan tinker                    # Test models interactively');
        $this->command->info('   php artisan route:list                # View all routes');
        $this->command->info('   php artisan db:seed --class=ProductSeeder   # Reseed products only');
        $this->command->info('');

        $this->command->info('📝 Test the Factory in Tinker:');
        $this->command->info('   Product::factory()->featured()->create()');
        $this->command->info('   Category::factory()->electronics()->create()');
        $this->command->info('   Product::factory()->count(5)->expensive()->create()');
        $this->command->info('');

        $this->command->info('🔍 Test Scopes and Relationships:');
        $this->command->info('   Product::active()->featured()->get()');
        $this->command->info('   Category::withCount("products")->get()');
        $this->command->info('   Product::with("category")->search("samsung")->get()');
        $this->command->info('');

        $this->command->info('📊 Sample Data Includes:');
        $this->command->info('   ✓ Realistic Indonesian product names');
        $this->command->info('   ✓ Proper price formatting (Rupiah)');
        $this->command->info('   ✓ Category color coding');
        $this->command->info('   ✓ Stock management scenarios');
        $this->command->info('   ✓ Featured/sale products');
        $this->command->info('   ✓ SEO-friendly URLs and meta data');
        $this->command->info('   ✓ Digital and physical product variants');
        $this->command->info('');

        $this->command->info('🎯 Key URLs to test:');
        $this->command->info('   /                                     # Homepage dashboard');
        $this->command->info('   /products                             # Product list with filters');
        $this->command->info('   /products/create                      # Add new product');
        $this->command->info('   /products?search=samsung              # Search products');
        $this->command->info('   /products?category=1                  # Filter by category');
        $this->command->info('');

        $this->command->info('💡 Pro tip: Check the generated data in your database');
        $this->command->info('    to see realistic product names, prices, and relationships!');
        $this->command->info('');
    }
}

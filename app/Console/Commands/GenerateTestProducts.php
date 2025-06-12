<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\ProductSeeder;

class GenerateTestProducts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'products:generate
                           {count=30 : Number of products to generate}
                           {--quick : Quick mode without detailed seeding}
                           {--with-categories : Also create categories if none exist}';

    /**
     * The console command description.
     */
    protected $description = 'Generate test products for development and testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $quick = $this->option('quick');
        $withCategories = $this->option('with-categories');

        $this->info("🚀 Generating {$count} test products...");

        // Check and create categories if needed
        if (Category::count() === 0) {
            if ($withCategories || $this->confirm('No categories found. Create basic categories?')) {
                $this->createBasicCategories();
                $this->info('✅ Basic categories created');
            } else {
                $this->error('❌ Cannot generate products without categories');
                return 1;
            }
        }

        $startTime = microtime(true);
        $beforeCount = Product::count();

        try {
            if ($quick) {
                // Quick generation
                Product::factory()->count($count)->create();
                $this->info("✅ Quick generation completed");
            } else {
                // Use seeder for more realistic data
                $seeder = new ProductSeeder(true, $count);
                $seeder->run();
            }

            $endTime = microtime(true);
            $afterCount = Product::count();
            $generated = $afterCount - $beforeCount;
            $duration = round($endTime - $startTime, 2);

            $this->newLine();
            $this->info("🎉 Successfully generated {$generated} products in {$duration} seconds");

            // Statistics
            $stats = $this->getStatistics();
            $this->displayStatistics($stats);

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error generating products: " . $e->getMessage());
            return 1;
        }
    }

    private function createBasicCategories(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik', 'color' => '#007bff'],
            ['name' => 'Fashion', 'description' => 'Pakaian dan aksesoris', 'color' => '#e83e8c'],
            ['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga', 'color' => '#28a745'],
            ['name' => 'Olahraga', 'description' => 'Peralatan olahraga', 'color' => '#fd7e14'],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'color' => $category['color'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function getStatistics(): array
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_categories' => Category::count(),
            'avg_price' => Product::avg('price'),
            'total_stock' => Product::sum('stock'),
            'digital_products' => Product::where('is_digital', true)->count(),
        ];
    }

    private function displayStatistics(array $stats): void
    {
        $this->newLine();
        $this->info('📊 Current Statistics:');
        $this->info('=====================');
        $this->info("📦 Total products: {$stats['total_products']}");
        $this->info("✅ Active products: {$stats['active_products']}");
        $this->info("⭐ Featured products: {$stats['featured_products']}");
        $this->info("🏷️  Categories: {$stats['total_categories']}");
        $this->info("💰 Average price: Rp " . number_format($stats['avg_price'], 0, ',', '.'));
        $this->info("📦 Total stock: " . number_format($stats['total_stock']));
        $this->info("💻 Digital products: {$stats['digital_products']}");

        $this->newLine();
        $this->info('💡 Usage examples:');
        $this->info('  php artisan products:generate 50 --quick');
        $this->info('  php artisan products:generate 100 --with-categories');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data produk yang akan dibuat
        $products = [
            // Electronics (8 products)
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Latest Apple smartphone with titanium design, A17 Pro chip, and advanced camera system.',
                'price' => 18999000,
                'category' => 'Electronics',
                'stock' => 25
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with S Pen, 200MP camera, and AI features.',
                'price' => 16999000,
                'category' => 'Electronics',
                'stock' => 30
            ],
            [
                'name' => 'MacBook Pro 14" M3',
                'description' => 'Professional laptop with M3 chip, Liquid Retina XDR display, and all-day battery life.',
                'price' => 28999000,
                'category' => 'Electronics',
                'stock' => 15
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'description' => 'Ultra-thin laptop with 12th Gen Intel Core processor and 13.4" InfinityEdge display.',
                'price' => 22500000,
                'category' => 'Electronics',
                'stock' => 20
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Industry-leading noise canceling wireless headphones with 30-hour battery life.',
                'price' => 5499000,
                'category' => 'Electronics',
                'stock' => 40
            ],
            [
                'name' => 'iPad Air 5th Gen',
                'description' => 'Powerful tablet with M1 chip, 10.9-inch Liquid Retina display, and Apple Pencil support.',
                'price' => 8999000,
                'category' => 'Electronics',
                'stock' => 35
            ],
            [
                'name' => 'AirPods Pro 2nd Gen',
                'description' => 'Advanced wireless earbuds with active noise cancellation and spatial audio.',
                'price' => 3499000,
                'category' => 'Electronics',
                'stock' => 50
            ],
            [
                'name' => 'LG OLED55C3PSA 55" TV',
                'description' => '55-inch 4K OLED Smart TV with webOS and Dolby Vision IQ.',
                'price' => 18999000,
                'category' => 'Electronics',
                'stock' => 12
            ],

            // Clothing (8 products)
            [
                'name' => 'Nike Air Force 1 \'07',
                'description' => 'Classic white leather sneakers with Nike Air cushioning and rubber sole.',
                'price' => 1549000,
                'category' => 'Clothing',
                'stock' => 60
            ],
            [
                'name' => 'Adidas Ultraboost 23',
                'description' => 'High-performance running shoes with Boost midsole and Primeknit upper.',
                'price' => 2999000,
                'category' => 'Clothing',
                'stock' => 45
            ],
            [
                'name' => 'Levi\'s 501 Original Jeans',
                'description' => 'Iconic straight-fit jeans made with premium denim and classic five-pocket styling.',
                'price' => 1299000,
                'category' => 'Clothing',
                'stock' => 55
            ],
            [
                'name' => 'Uniqlo Heattech Crew Neck',
                'description' => 'Ultra-warm thermal innerwear that generates heat using body moisture.',
                'price' => 199000,
                'category' => 'Clothing',
                'stock' => 100
            ],
            [
                'name' => 'Champion Reverse Weave Hoodie',
                'description' => 'Premium cotton hoodie with iconic Champion logo and comfortable fit.',
                'price' => 899000,
                'category' => 'Clothing',
                'stock' => 40
            ],
            [
                'name' => 'Zara Tailored Blazer',
                'description' => 'Modern slim-fit blazer perfect for business and casual occasions.',
                'price' => 1799000,
                'category' => 'Clothing',
                'stock' => 25
            ],
            [
                'name' => 'H&M Cotton T-Shirt',
                'description' => 'Basic cotton t-shirt available in multiple colors with comfortable regular fit.',
                'price' => 129000,
                'category' => 'Clothing',
                'stock' => 80
            ],
            [
                'name' => 'Converse Chuck Taylor All Star',
                'description' => 'Timeless canvas sneakers with rubber toe cap and classic All Star styling.',
                'price' => 799000,
                'category' => 'Clothing',
                'stock' => 70
            ],

            // Home & Garden (6 products)
            [
                'name' => 'IKEA HEMNES Bookcase',
                'description' => 'Solid wood bookcase with adjustable shelves, perfect for books and decorations.',
                'price' => 2299000,
                'category' => 'Home & Garden',
                'stock' => 20
            ],
            [
                'name' => 'Dyson V15 Detect Absolute',
                'description' => 'Powerful cordless vacuum with laser dust detection and LCD screen.',
                'price' => 11999000,
                'category' => 'Home & Garden',
                'stock' => 8
            ],
            [
                'name' => 'Philips Hue White Ambiance Starter Kit',
                'description' => 'Smart LED lighting system with voice control and smartphone app.',
                'price' => 1899000,
                'category' => 'Home & Garden',
                'stock' => 30
            ],
            [
                'name' => 'KitchenAid Artisan Stand Mixer',
                'description' => 'Professional 5-quart stand mixer with 10 speeds and multiple attachments.',
                'price' => 7299000,
                'category' => 'Home & Garden',
                'stock' => 12
            ],
            [
                'name' => 'Weber Genesis II E-335 Gas Grill',
                'description' => 'Premium 3-burner gas grill with GS4 grilling system and side burner.',
                'price' => 14999000,
                'category' => 'Home & Garden',
                'stock' => 5
            ],
            [
                'name' => 'Muji Aroma Diffuser',
                'description' => 'Ultrasonic aroma diffuser with timer function and LED light.',
                'price' => 1299000,
                'category' => 'Home & Garden',
                'stock' => 25
            ],

            // Sports (4 products)
            [
                'name' => 'Wilson Pro Staff Tennis Racket',
                'description' => 'Professional tennis racket used by tour players, 97 sq in head size.',
                'price' => 3499000,
                'category' => 'Sports',
                'stock' => 18
            ],
            [
                'name' => 'Spalding Official NBA Basketball',
                'description' => 'Official size 7 basketball with composite leather cover.',
                'price' => 649000,
                'category' => 'Sports',
                'stock' => 35
            ],
            [
                'name' => 'YETI Rambler 32oz Tumbler',
                'description' => 'Insulated stainless steel tumbler that keeps drinks cold or hot for hours.',
                'price' => 899000,
                'category' => 'Sports',
                'stock' => 50
            ],
            [
                'name' => 'Bowflex SelectTech 552 Dumbbells',
                'description' => 'Adjustable dumbbells that replace 15 sets of weights, 5-52.5 lbs per dumbbell.',
                'price' => 8999000,
                'category' => 'Sports',
                'stock' => 10
            ],

            // Books (4 products)
            [
                'name' => 'Clean Code by Robert C. Martin',
                'description' => 'A handbook of agile software craftsmanship with practical programming advice.',
                'price' => 599000,
                'category' => 'Books',
                'stock' => 30
            ],
            [
                'name' => 'The Pragmatic Programmer 20th Anniversary',
                'description' => 'Updated edition of the classic programming methodology book.',
                'price' => 699000,
                'category' => 'Books',
                'stock' => 25
            ],
            [
                'name' => 'Laravel: Up & Running 3rd Edition',
                'description' => 'Comprehensive guide to Laravel PHP framework development.',
                'price' => 549000,
                'category' => 'Books',
                'stock' => 40
            ],
            [
                'name' => 'JavaScript: The Definitive Guide 7th Edition',
                'description' => 'Complete reference and tutorial for JavaScript programming language.',
                'price' => 799000,
                'category' => 'Books',
                'stock' => 35
            ]
        ];

        // Insert produk ke database
        foreach ($products as $index => $productData) {
            // Cari kategori berdasarkan nama
            $category = Category::where('name', $productData['category'])->first();

            if ($category) {
                Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'category_id' => $category->id,
                    'stock' => $productData['stock'],
                    'image_url' => 'https://picsum.photos/400/300?random=' . ($index + 1),
                ]);
            }
        }

        $this->command->info(count($products) . ' products have been created successfully!');

        // Tampilkan statistik
        $this->showStatistics();
    }

    /**
     * Tampilkan statistik produk yang dibuat
     */
    private function showStatistics()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $this->command->info("\n=== STATISTICS ===");
        $this->command->info("Total Products: {$totalProducts}");
        $this->command->info("Total Categories: {$totalCategories}");

        // Statistik per kategori
        $this->command->info("\nProducts per Category:");
        $categories = Category::withCount('products')->get();
        foreach ($categories as $category) {
            $this->command->info("- {$category->name}: {$category->products_count} products");
        }

        // Statistik harga
        $avgPrice = number_format(Product::avg('price'));
        $minPrice = number_format(Product::min('price'));
        $maxPrice = number_format(Product::max('price'));

        $this->command->info("\nPrice Statistics:");
        $this->command->info("- Average Price: Rp {$avgPrice}");
        $this->command->info("- Lowest Price: Rp {$minPrice}");
        $this->command->info("- Highest Price: Rp {$maxPrice}");

        // Statistik stok
        $totalStock = Product::sum('stock');
        $outOfStock = Product::where('stock', 0)->count();
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<', 10)->count();

        $this->command->info("\nStock Statistics:");
        $this->command->info("- Total Stock: {$totalStock} items");
        $this->command->info("- Out of Stock: {$outOfStock} products");
        $this->command->info("- Low Stock: {$lowStock} products");
    }
}

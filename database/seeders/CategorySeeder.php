<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔄 Creating categories...');

        // Predefined categories with detailed information
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Produk elektronik dan gadget modern untuk kehidupan digital yang lebih mudah dan efisien',
                'color' => '#007bff',
                'icon' => 'laptop',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Fashion',
                'description' => 'Pakaian dan aksesoris fashion terkini untuk gaya hidup modern dan penampilan yang menarik',
                'color' => '#e83e8c',
                'icon' => 'bag',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Rumah Tangga',
                'description' => 'Peralatan dan kebutuhan rumah tangga untuk kenyamanan dan kemudahan aktivitas sehari-hari',
                'color' => '#28a745',
                'icon' => 'house',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Peralatan dan perlengkapan olahraga untuk mendukung gaya hidup sehat dan aktif',
                'color' => '#fd7e14',
                'icon' => 'bicycle',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Makanan',
                'description' => 'Berbagai jenis makanan dan minuman berkualitas untuk kebutuhan nutrisi harian',
                'color' => '#ffc107',
                'icon' => 'cup-hot',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Produk kesehatan dan perawatan untuk menjaga kondisi tubuh tetap fit dan sehat',
                'color' => '#20c997',
                'icon' => 'heart-pulse',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Buku',
                'description' => 'Koleksi buku dan materi edukasi untuk menambah wawasan dan pengetahuan',
                'color' => '#6f42c1',
                'icon' => 'book',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Toys',
                'description' => 'Mainan dan permainan edukatif untuk anak-anak dari berbagai usia',
                'color' => '#ff6b6b',
                'icon' => 'puzzle',
                'is_featured' => false,
                'sort_order' => 8,
            ],
        ];

        // Create predefined categories
        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
            $this->command->info("✅ Created category: {$categoryData['name']}");
        }

        // Generate additional random categories using factory
        $additionalCategories = Category::factory()
            ->count(4)
            ->create([
                'is_featured' => false,
                'sort_order' => function() {
                    return Category::max('sort_order') + 1;
                }
            ]);

        foreach ($additionalCategories as $category) {
            $this->command->info("✅ Generated random category: {$category->name}");
        }

        $totalCategories = Category::count();
        $featuredCategories = Category::where('is_featured', true)->count();

        $this->command->info('');
        $this->command->info("🎉 Categories seeded successfully!");
        $this->command->info("📊 Total categories: {$totalCategories}");
        $this->command->info("⭐ Featured categories: {$featuredCategories}");
        $this->command->info('');
    }
}

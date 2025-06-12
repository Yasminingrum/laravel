<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices, gadgets, and technology products including smartphones, laptops, tablets, and accessories.'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel for men, women, and children including shoes, shirts, pants, and accessories.'
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement, furniture, kitchen appliances, garden tools, and home decoration items.'
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment, fitness gear, outdoor activities, and athletic accessories.'
            ],
            [
                'name' => 'Books',
                'description' => 'Educational materials, novels, technical books, magazines, and digital publications.'
            ],
            [
                'name' => 'Beauty & Health',
                'description' => 'Cosmetics, skincare products, health supplements, and personal care items.'
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car accessories, motorcycle parts, automotive tools, and vehicle maintenance products.'
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Children toys, board games, video games, educational toys, and entertainment products.'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        $this->command->info('Categories have been created successfully!');
    }
}

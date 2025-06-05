<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            ['name' => 'Elektronik', 'color' => '#007bff', 'icon' => 'laptop'],
            ['name' => 'Fashion', 'color' => '#e83e8c', 'icon' => 'bag'],
            ['name' => 'Rumah Tangga', 'color' => '#28a745', 'icon' => 'house'],
            ['name' => 'Olahraga', 'color' => '#fd7e14', 'icon' => 'bicycle'],
            ['name' => 'Makanan', 'color' => '#ffc107', 'icon' => 'cup-hot'],
            ['name' => 'Kesehatan', 'color' => '#20c997', 'icon' => 'heart-pulse'],
            ['name' => 'Buku', 'color' => '#6f42c1', 'icon' => 'book'],
            ['name' => 'Toys', 'color' => '#fd7e14', 'icon' => 'puzzle'],
            ['name' => 'Otomotif', 'color' => '#dc3545', 'icon' => 'car-front'],
            ['name' => 'Kecantikan', 'color' => '#f8d7da', 'icon' => 'flower1']
        ];

        $category = $this->faker->randomElement($categories);

        return [
            'name' => $category['name'],
            'description' => $this->faker->sentence(8, true),
            'color' => $category['color'],
            'icon' => $category['icon'] ?? 'tag',
            'is_featured' => $this->faker->boolean(30), // 30% chance featured
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    // State methods untuk variasi data
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function electronics(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Elektronik',
            'color' => '#007bff',
            'icon' => 'laptop',
            'description' => 'Produk elektronik dan gadget modern untuk kehidupan digital',
        ]);
    }

    public function fashion(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Fashion',
            'color' => '#e83e8c',
            'icon' => 'bag',
            'description' => 'Pakaian dan aksesoris fashion terkini untuk gaya hidup modern',
        ]);
    }

    public function household(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Rumah Tangga',
            'color' => '#28a745',
            'icon' => 'house',
            'description' => 'Peralatan dan kebutuhan rumah tangga untuk kenyamanan keluarga',
        ]);
    }

    public function sports(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Olahraga',
            'color' => '#fd7e14',
            'icon' => 'bicycle',
            'description' => 'Peralatan dan perlengkapan olahraga untuk hidup sehat',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $productNames = [
            'Elektronik' => [
                'Smartphone Samsung Galaxy', 'iPhone Pro Max', 'Laptop ASUS VivoBook',
                'MacBook Air M2', 'Headphone Sony WH-1000XM5', 'AirPods Pro',
                'Smart TV LG OLED', 'Monitor Gaming ASUS', 'Kamera Canon EOS',
                'Tablet iPad Air', 'Speaker Bluetooth JBL', 'Smartwatch Apple Watch'
            ],
            'Fashion' => [
                'Kemeja Batik Premium', 'Dress Casual Wanita', 'Sepatu Sneakers Nike',
                'Tas Kulit Branded', 'Jaket Denim Vintage', 'Kaos Polos Premium',
                'Celana Jeans Slim Fit', 'Sandal Kulit Pria', 'Hoodie Unisex'
            ],
            'Rumah Tangga' => [
                'Rice Cooker Digital', 'Blender Multifungsi', 'Vacuum Cleaner Wireless',
                'Air Fryer Philips', 'Microwave Sharp', 'Dispenser Galon',
                'Set Peralatan Masak', 'Lemari Pakaian 3 Pintu', 'Meja Makan Set'
            ],
            'Olahraga' => [
                'Sepeda Gunung MTB', 'Treadmill Home Fitness', 'Dumbell Set Adjustable',
                'Matras Yoga Premium', 'Raket Badminton Pro', 'Sepatu Lari Adidas',
                'Bola Sepak Original', 'Tas Gym Multifungsi'
            ]
        ];

        $brands = [
            'Samsung', 'Apple', 'Sony', 'Nike', 'Adidas', 'Uniqlo', 'Zara',
            'Philips', 'LG', 'Canon', 'ASUS', 'HP', 'Dell', 'Xiaomi', 'Oppo'
        ];

        // Ambil kategori random atau buat baru jika belum ada
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $categoryProducts = $productNames[$category->name] ?? ['Product ' . $this->faker->word()];

        $basePrice = $this->faker->randomFloat(2, 50000, 10000000); // 50k - 10jt

        return [
            'name' => $this->faker->randomElement($brands) . ' ' . $this->faker->randomElement($categoryProducts),
            'slug' => $this->faker->unique()->slug(3),
            'description' => $this->faker->paragraph(3),
            'short_description' => $this->faker->sentence(10),
            'price' => $basePrice,
            'compare_price' => $this->faker->boolean(60) ?
                round($basePrice * $this->faker->randomFloat(2, 1.1, 1.8), 2) : null,
            'cost_price' => round($basePrice * $this->faker->randomFloat(2, 0.4, 0.7), 2),
            'category_id' => $category->id,
            'stock' => $this->faker->numberBetween(0, 100),
            'min_stock' => $this->faker->numberBetween(1, 10),
            'weight' => $this->faker->randomFloat(2, 0.1, 50), // kg
            'dimensions' => $this->faker->randomElement([
                '10x10x5', '20x15x8', '30x25x12', '15x10x3', '25x20x10'
            ]),
            'sku' => strtoupper($this->faker->unique()->bothify('??##-####')),
            'barcode' => $this->faker->unique()->numerify('############'),
            'is_active' => $this->faker->boolean(85), // 85% active
            'is_featured' => $this->faker->boolean(20), // 20% featured
            'is_digital' => $this->faker->boolean(10), // 10% digital products
            'requires_shipping' => function (array $attributes) {
                return !$attributes['is_digital'];
            },
            'meta_title' => function (array $attributes) {
                return $attributes['name'] . ' - Best Price Online';
            },
            'meta_description' => $this->faker->sentence(15),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    // State methods untuk variasi data
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_active' => true,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(1, 5),
            'min_stock' => $this->faker->numberBetween(5, 10),
        ]);
    }

    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, 5000000, 50000000),
        ]);
    }

    public function cheap(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, 10000, 500000),
        ]);
    }

    public function digital(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_digital' => true,
            'requires_shipping' => false,
            'weight' => 0,
            'dimensions' => null,
        ]);
    }

    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $originalPrice = $attributes['price'] ?? 1000000;
            return [
                'compare_price' => $originalPrice * 1.3,
                'price' => $originalPrice,
            ];
        });
    }

    public function electronics(): static
    {
        return $this->state(function (array $attributes) {
            $category = Category::where('name', 'Elektronik')->first();
            return [
                'category_id' => $category?->id ?? Category::factory()->electronics(),
                'weight' => $this->faker->randomFloat(2, 0.5, 10),
                'requires_shipping' => true,
            ];
        });
    }

    public function fashion(): static
    {
        return $this->state(function (array $attributes) {
            $category = Category::where('name', 'Fashion')->first();
            return [
                'category_id' => $category?->id ?? Category::factory()->fashion(),
                'weight' => $this->faker->randomFloat(2, 0.1, 2),
            ];
        });
    }
}

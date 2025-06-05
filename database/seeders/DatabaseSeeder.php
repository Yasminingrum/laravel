<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Produk elektronik dan gadget modern',
                'color' => '#007bff'
            ],
            [
                'name' => 'Fashion',
                'description' => 'Pakaian dan aksesoris fashion terkini',
                'color' => '#e83e8c'
            ],
            [
                'name' => 'Rumah Tangga',
                'description' => 'Peralatan dan kebutuhan rumah tangga',
                'color' => '#28a745'
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Peralatan dan perlengkapan olahraga',
                'color' => '#fd7e14'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create products
        $products = [
            // Elektronik
            ['name' => 'Smartphone Samsung Galaxy', 'description' => 'Smartphone dengan kamera 108MP dan layar AMOLED', 'price' => 8500000, 'category_id' => 1, 'stock' => 15],
            ['name' => 'Laptop ASUS VivoBook', 'description' => 'Laptop ringan dengan processor Intel Core i5', 'price' => 12500000, 'category_id' => 1, 'stock' => 8],
            ['name' => 'Headphone Sony WH-1000XM4', 'description' => 'Headphone wireless dengan noise cancelling', 'price' => 4200000, 'category_id' => 1, 'stock' => 12],
            ['name' => 'Smart TV LG 55 inch', 'description' => 'Smart TV 4K dengan fitur webOS', 'price' => 9800000, 'category_id' => 1, 'stock' => 5],
            ['name' => 'Kamera Canon EOS M50', 'description' => 'Kamera mirrorless untuk fotografi dan vlog', 'price' => 7500000, 'category_id' => 1, 'stock' => 7],
            ['name' => 'Tablet iPad Air', 'description' => 'Tablet dengan layar Liquid Retina 10.9 inch', 'price' => 8900000, 'category_id' => 1, 'stock' => 10],
            ['name' => 'Speaker Bluetooth JBL', 'description' => 'Speaker portable dengan bass yang powerful', 'price' => 1200000, 'category_id' => 1, 'stock' => 20],
            ['name' => 'Smartwatch Apple Watch', 'description' => 'Smartwatch dengan fitur kesehatan lengkap', 'price' => 5500000, 'category_id' => 1, 'stock' => 13],

            // Fashion
            ['name' => 'Kemeja Batik Premium', 'description' => 'Kemeja batik dengan motif tradisional modern', 'price' => 350000, 'category_id' => 2, 'stock' => 25],
            ['name' => 'Dress Casual Wanita', 'description' => 'Dress casual dengan bahan katun yang nyaman', 'price' => 280000, 'category_id' => 2, 'stock' => 18],
            ['name' => 'Sepatu Sneakers Nike', 'description' => 'Sneakers sport dengan teknologi Air Max', 'price' => 1800000, 'category_id' => 2, 'stock' => 15],
            ['name' => 'Tas Kulit Branded', 'description' => 'Tas kulit asli dengan desain elegan', 'price' => 1200000, 'category_id' => 2, 'stock' => 12],
            ['name' => 'Jaket Denim Vintage', 'description' => 'Jaket denim dengan style vintage yang trendy', 'price' => 450000, 'category_id' => 2, 'stock' => 20],
            ['name' => 'Kaos Polos Premium', 'description' => 'Kaos polos dengan bahan cotton combed 30s', 'price' => 85000, 'category_id' => 2, 'stock' => 50],
            ['name' => 'Celana Jeans Slim Fit', 'description' => 'Celana jeans dengan potongan slim fit modern', 'price' => 320000, 'category_id' => 2, 'stock' => 22],
            ['name' => 'Sandal Kulit Pria', 'description' => 'Sandal kulit dengan kualitas premium', 'price' => 275000, 'category_id' => 2, 'stock' => 16],

            // Rumah Tangga
            ['name' => 'Rice Cooker Digital', 'description' => 'Rice cooker dengan teknologi fuzzy logic', 'price' => 850000, 'category_id' => 3, 'stock' => 14],
            ['name' => 'Blender Multifungsi', 'description' => 'Blender dengan berbagai fungsi untuk kitchen', 'price' => 420000, 'category_id' => 3, 'stock' => 18],
            ['name' => 'Vacuum Cleaner Wireless', 'description' => 'Vacuum cleaner tanpa kabel yang praktis', 'price' => 1500000, 'category_id' => 3, 'stock' => 10],
            ['name' => 'Set Peralatan Masak', 'description' => 'Set lengkap peralatan masak anti lengket', 'price' => 680000, 'category_id' => 3, 'stock' => 12],
            ['name' => 'Dispenser Air Galon', 'description' => 'Dispenser air panas dingin dengan galon bawah', 'price' => 950000, 'category_id' => 3, 'stock' => 8],
            ['name' => 'Lemari Pakaian 3 Pintu', 'description' => 'Lemari pakaian dengan desain minimalis', 'price' => 1800000, 'category_id' => 3, 'stock' => 6],
            ['name' => 'Meja Makan Set 4 Kursi', 'description' => 'Set meja makan kayu dengan 4 kursi', 'price' => 2200000, 'category_id' => 3, 'stock' => 4],
            ['name' => 'Kasur Spring Bed', 'description' => 'Kasur spring bed dengan kualitas hotel', 'price' => 3500000, 'category_id' => 3, 'stock' => 5],

            // Olahraga
            ['name' => 'Sepeda Gunung MTB', 'description' => 'Sepeda gunung dengan frame aluminium ringan', 'price' => 4500000, 'category_id' => 4, 'stock' => 8],
            ['name' => 'Treadmill Home Fitness', 'description' => 'Treadmill elektrik untuk olahraga di rumah', 'price' => 6800000, 'category_id' => 4, 'stock' => 3],
            ['name' => 'Dumbell Set Adjustable', 'description' => 'Set dumbell dengan beban yang bisa diatur', 'price' => 1200000, 'category_id' => 4, 'stock' => 15],
            ['name' => 'Matras Yoga Premium', 'description' => 'Matras yoga dengan bahan eco-friendly', 'price' => 180000, 'category_id' => 4, 'stock' => 25],
            ['name' => 'Raket Badminton Pro', 'description' => 'Raket badminton untuk pemain profesional', 'price' => 850000, 'category_id' => 4, 'stock' => 12],
            ['name' => 'Sepatu Lari Adidas', 'description' => 'Sepatu lari dengan teknologi Boost foam', 'price' => 1400000, 'category_id' => 4, 'stock' => 18],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}

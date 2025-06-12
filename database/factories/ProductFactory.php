namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        $productNames = [
            'Electronics' => [
                'Smartphone Pro Max', 'Wireless Earbuds', 'Gaming Laptop', 'Smart TV 55"',
                'Tablet Ultra', 'Digital Camera', 'Bluetooth Speaker', 'Smartwatch Series',
                'Gaming Console', 'Wireless Charger'
            ],
            'Clothing' => [
                'Cotton T-Shirt', 'Denim Jeans', 'Winter Jacket', 'Running Shoes',
                'Formal Shirt', 'Casual Dress', 'Sports Hoodie', 'Leather Boots',
                'Summer Shorts', 'Business Suit'
            ],
            'Books' => [
                'Programming Guide', 'Fiction Novel', 'History Book', 'Cookbook Deluxe',
                'Self-Help Manual', 'Science Textbook', 'Art Collection', 'Biography',
                'Poetry Anthology', 'Travel Guide'
            ],
            'Home & Garden' => [
                'Garden Tools Set', 'Kitchen Appliance', 'Decorative Lamp', 'Storage Cabinet',
                'Plant Fertilizer', 'Cleaning Supplies', 'Furniture Set', 'Wall Art',
                'Outdoor Grill', 'Home Security System'
            ],
            'Sports' => [
                'Basketball', 'Tennis Racket', 'Yoga Mat', 'Dumbbells Set',
                'Cycling Helmet', 'Swimming Goggles', 'Running Shoes', 'Golf Club',
                'Soccer Ball', 'Fitness Tracker'
            ]
        ];

        $categories = Category::all();
        $category = $categories->random();

        $categoryProducts = $productNames[$category->name] ?? [
            'Premium Product', 'Standard Item', 'Basic Model', 'Deluxe Version',
            'Professional Grade', 'Economy Option', 'Limited Edition', 'Classic Design'
        ];

        $baseName = $this->faker->randomElement($categoryProducts);
        $productName = $baseName . ' ' . $this->faker->randomElement(['Pro', 'Plus', 'Max', 'Elite', 'Standard', 'Basic']);

        return [
            'name' => $productName,
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 10000, 5000000), // Rp 10,000 - Rp 5,000,000
            'category_id' => $category->id,
            'stock' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->imageUrl(400, 400, 'products', true, $baseName),
        ];
    }

    public function expensive()
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => $this->faker->randomFloat(2, 1000000, 10000000), // Rp 1,000,000 - Rp 10,000,000
            ];
        });
    }

    public function outOfStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'stock' => 0,
            ];
        });
    }
}

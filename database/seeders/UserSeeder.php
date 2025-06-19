<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Toko Saya',
            'email' => 'admin@tokosaya.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+628123456789',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'is_active' => true,
        ]);

        // Create test customer
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@tokosaya.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '+628987654321',
            'address' => 'Jl. Customer No. 2, Surabaya',
            'is_active' => true,
        ]);

        // Create additional test customers
        User::factory(10)->create([
            'role' => 'customer'
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@tokosaya.com / admin123');
        $this->command->info('Customer: customer@tokosaya.com / customer123');
    }
}

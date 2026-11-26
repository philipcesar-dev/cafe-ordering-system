<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (safe - won't fail if already exists)
        User::updateOrCreate(
            ['email' => 'admin@cafe.com'],
            [
                'name' => 'Admin Cafe',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Normal user
        User::updateOrCreate(
            ['email' => 'user@cafe.com'],
            [
                'name' => 'Customer Test',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // Categories (only create if empty)
        if (Category::count() === 0) {
            $coffee = Category::create(['name' => 'Coffee', 'description' => 'Hot & cold coffee']);
            $tea = Category::create(['name' => 'Tea', 'description' => 'Various teas']);
            $pastry = Category::create(['name' => 'Pastries', 'description' => 'Fresh baked goods']);
            $food = Category::create(['name' => 'Light Meals', 'description' => 'Sandwiches & more']);

            // Products
            Product::create(['category_id' => $coffee->id, 'name' => 'Espresso', 'price' => 25000, 'description' => 'Strong Italian espresso', 'is_available' => true]);
            Product::create(['category_id' => $coffee->id, 'name' => 'Cappuccino', 'price' => 35000, 'description' => 'Espresso with steamed milk foam', 'is_available' => true]);
            Product::create(['category_id' => $coffee->id, 'name' => 'Latte', 'price' => 38000, 'description' => 'Smooth milk coffee', 'is_available' => true]);
            Product::create(['category_id' => $tea->id, 'name' => 'Green Tea', 'price' => 22000, 'description' => 'Fresh green tea', 'is_available' => true]);
            Product::create(['category_id' => $pastry->id, 'name' => 'Croissant', 'price' => 28000, 'description' => 'Buttery French croissant', 'is_available' => true]);
            Product::create(['category_id' => $food->id, 'name' => 'Chicken Sandwich', 'price' => 45000, 'description' => 'Grilled chicken sandwich', 'is_available' => true]);
        }
    }
}
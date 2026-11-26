<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with('category')->get();

        foreach ($products as $product) {
            Menu::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'name'        => $product->name,
                    'slug'        => $product->slug ?? Str::slug($product->name),
                    'description' => $product->description,
                    'price'       => $product->price,
                    'category'    => $product->category->name ?? null,
                    'image'       => $product->image,
                    'is_active'   => $product->is_available ?? true,
                    'sort_order'  => 0,
                ]
            );
        }
    }
}
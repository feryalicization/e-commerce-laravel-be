<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada kategori di database
        $category = Category::first();

        if (!$category) {
            $category = Category::create([
                'name' => 'Default Category',
                'description' => 'Default category for products'
            ]);
        }

        // Tambahkan produk dummy
        Product::insert([
            [
                'name' => 'Laptop Lenovo ThinkPad',
                'description' => 'Laptop bisnis dengan performa tinggi',
                'price' => 15000000,
                'stock' => 10,
                'category_id' => $category->id,
                'image_url' => 'https://via.placeholder.com/150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'description' => 'HP flagship dengan kamera terbaik',
                'price' => 18000000,
                'stock' => 15,
                'category_id' => $category->id,
                'image_url' => 'https://via.placeholder.com/150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mesin Cuci LG 8 Kg',
                'description' => 'Mesin cuci hemat listrik',
                'price' => 5000000,
                'stock' => 8,
                'category_id' => $category->id,
                'image_url' => 'https://via.placeholder.com/150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

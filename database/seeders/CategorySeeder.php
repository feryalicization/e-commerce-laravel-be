<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Fashion', 'description' => 'Clothes, shoes, and accessories'],
            ['name' => 'Home Tooles', 'description' => 'Products for home use'],
        ]);
    }
}

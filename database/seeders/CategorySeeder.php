<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronics products', 'status' => 1],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Clothing products', 'status' => 1],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Books products', 'status' => 1],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home & Garden products', 'status' => 1],
            ['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports & Outdoors products', 'status' => 1],
            ['name' => 'Toys & Games', 'slug' => 'toys-games', 'description' => 'Toys & Games products', 'status' => 1],
            ['name' => 'Beauty & Personal Care', 'slug' => 'beauty-personal-care', 'description' => 'Beauty & Personal Care products', 'status' => 1],
            ['name' => 'Health & Household', 'slug' => 'health-household', 'description' => 'Health & Household products', 'status' => 1],
            ['name' => 'Grocery', 'slug' => 'grocery', 'description' => 'Grocery products', 'status' => 1],
            ['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Automotive products', 'status' => 1],
        ];

        foreach ($categories as $category) {
            // create category
            Category::firstOrCreate($category);
        }
    }
}

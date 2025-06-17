<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Wait for admin seeder to complete
        $shop = \App\Models\Shop::first();
        $admin = \App\Models\User::first();
        
        if (!$shop || !$admin) {
            throw new \Exception('Please run AdminSeeder first!');
        }

        $categories = [
            [
                'name' => 'Rice Products',
                'description' => 'All types of rice including boiled, raw, and custom milled',
                'shop_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Wheat Products',
                'description' => 'Wheat and wheat-based products like Atta',
                'shop_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Sugar Products',
                'description' => 'Sugar and sugar-based products',
                'shop_id' => 1,
                'user_id' => 1,
            ],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::create($categoryData);
        }
    }
}

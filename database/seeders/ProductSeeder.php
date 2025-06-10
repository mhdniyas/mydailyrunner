<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::all();

        $products = [
            [
                'name' => 'Premium Cricket Ball',
                'description' => 'High quality cricket ball',
                'unit' => 'pcs',
                'min_stock_level' => 10.00,
                'current_stock' => 50.00,
                'avg_cost' => 150.00,
                'sale_price' => 200.00,
                'user_id' => 1,
            ],
            [
                'name' => 'Cricket Bat',
                'description' => 'Professional cricket bat',
                'unit' => 'pcs',
                'min_stock_level' => 5.00,
                'current_stock' => 25.00,
                'avg_cost' => 1000.00,
                'sale_price' => 1500.00,
                'user_id' => 1,
            ],
            [
                'name' => 'Cricket Gloves',
                'description' => 'Protective cricket gloves',
                'unit' => 'pcs',
                'min_stock_level' => 8.00,
                'current_stock' => 30.00,
                'avg_cost' => 250.00,
                'sale_price' => 350.00,
                'user_id' => 1,
            ],
            [
                'name' => 'Cricket Helmet',
                'description' => 'Safety cricket helmet',
                'unit' => 'pcs',
                'min_stock_level' => 3.00,
                'current_stock' => 15.00,
                'avg_cost' => 800.00,
                'sale_price' => 1200.00,
                'user_id' => 1,
            ],
        ];

        foreach ($shops as $shop) {
            foreach ($products as $product) {
                $product['shop_id'] = $shop->id;
                Product::create($product);
            }
        }
    }
}

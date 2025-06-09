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
                'unit' => 'pcs',
                'cost_price' => 150.00,
                'sale_price' => 200.00,
            ],
            [
                'name' => 'Cricket Bat',
                'unit' => 'pcs',
                'cost_price' => 1000.00,
                'sale_price' => 1500.00,
            ],
            [
                'name' => 'Cricket Gloves',
                'unit' => 'pcs',
                'cost_price' => 250.00,
                'sale_price' => 350.00,
            ],
            [
                'name' => 'Cricket Helmet',
                'unit' => 'pcs',
                'cost_price' => 800.00,
                'sale_price' => 1200.00,
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

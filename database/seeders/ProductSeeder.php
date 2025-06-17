<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $riceCategory = \App\Models\ProductCategory::where('name', 'Rice Products')->first();
        $wheatCategory = \App\Models\ProductCategory::where('name', 'Wheat Products')->first();
        $sugarCategory = \App\Models\ProductCategory::where('name', 'Sugar Products')->first();

        if (!$riceCategory || !$wheatCategory || !$sugarCategory) {
            throw new \Exception('Please run ProductCategorySeeder first!');
        }

        $products = [
            [
                'name' => 'Boiled Rice',
                'unit' => 'kg',
                'sale_price' => 50.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'Premium quality boiled rice (BR)',
                'category_id' => $riceCategory->id,
            ],
            [
                'name' => 'Raw Rice',
                'unit' => 'kg',
                'sale_price' => 45.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'Fresh raw rice (RR)',
                'category_id' => $riceCategory->id,
            ],
            [
                'name' => 'Custom Milled Rice',
                'unit' => 'kg',
                'sale_price' => 55.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'Specially milled rice (CMR)',
                'category_id' => $riceCategory->id,
            ],
            [
                'name' => 'Wheat',
                'unit' => 'kg',
                'sale_price' => 40.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'High quality wheat',
                'category_id' => $wheatCategory->id,
            ],
            [
                'name' => 'Atta',
                'unit' => 'kg',
                'sale_price' => 42.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'Wheat flour (Atta)',
                'category_id' => $wheatCategory->id,
            ],
            [
                'name' => 'Sugar',
                'unit' => 'kg',
                'sale_price' => 52.00,
                'current_stock' => 0,
                'min_stock_level' => 200,
                'description' => 'Pure white sugar',
                'category_id' => $sugarCategory->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::create([
                'shop_id' => 1,
                'user_id' => 1,
                'name' => $productData['name'],
                'unit' => $productData['unit'],
                'sale_price' => $productData['sale_price'],
                'current_stock' => $productData['current_stock'],
                'min_stock_level' => $productData['min_stock_level'],
                'description' => $productData['description'],
                'category_id' => $productData['category_id'],
            ]);
        }
    }
}

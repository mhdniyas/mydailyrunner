<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Customer;
use App\Models\ShopFinanceEntry;
use App\Models\PersonalFinanceEntry;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test products with random stock levels
        Product::factory(10)->create()->each(function ($product) {
            // Add random stock entries
            StockEntry::factory()->count(rand(3, 8))->create([
                'product_id' => $product->id
            ]);
        });

        // Create test customers with product relationships
        Customer::factory(15)->create()->each(function ($customer) {
            $products = Product::inRandomOrder()->limit(rand(1, 3))->get();
            
            foreach ($products as $product) {
                $customer->products()->attach($product->id, [
                    'advance_amount' => rand(100, 1000),
                    'advance_payment_mode' => rand(0, 1) ? 'cash' : 'online',
                    'pending_amount' => rand(0, 500),
                    'pending_payment_mode' => rand(0, 1) ? 'cash' : 'online',
                ]);
            }
        });

        // Create test finance entries
        for ($i = 0; $i < 20; $i++) {
            ShopFinanceEntry::factory()->create();
            PersonalFinanceEntry::factory()->create();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shop = Shop::first();
        
        if (!$shop) {
            echo "No shop found. Please run UserSeeder first.\n";
            return;
        }

        // Create sample customers
        $customers = [
            [
                'name' => 'Walk-in Customer',
                'phone' => null,
                'address' => null,
                'ration_card_number' => null,
                'card_type' => null,
                'notes' => 'Default walk-in customer',
                'is_walk_in' => true,
                'shop_id' => $shop->id,
                'user_id' => 1,
            ],
            [
                'name' => 'John Doe',
                'phone' => '+1234567890',
                'address' => '123 Main Street',
                'ration_card_number' => 'RC001',
                'card_type' => 'AAY',
                'notes' => 'Regular customer',
                'is_walk_in' => false,
                'shop_id' => $shop->id,
                'user_id' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '+0987654321',
                'address' => '456 Oak Avenue',
                'ration_card_number' => 'RC002',
                'card_type' => 'PHH',
                'notes' => 'VIP customer',
                'is_walk_in' => false,
                'shop_id' => $shop->id,
                'user_id' => 1,
            ],
            [
                'name' => 'Mike Johnson',
                'phone' => '+1122334455',
                'address' => '789 Pine Road',
                'ration_card_number' => 'RC003',
                'card_type' => 'NPS',
                'notes' => 'Bulk buyer',
                'is_walk_in' => false,
                'shop_id' => $shop->id,
                'user_id' => 1,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        echo "Created " . count($customers) . " customers for shop: " . $shop->name . "\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'owner@example.com')->first();
        
        // Create two test shops
        $shops = [
            [
                'name' => 'Main Cricket Shop',
                'location' => 'City Center',
                'owner_id' => $owner->id,
            ],
            [
                'name' => 'Branch Cricket Shop',
                'location' => 'Suburb Area',
                'owner_id' => $owner->id,
            ],
        ];

        foreach ($shops as $shopData) {
            $shop = Shop::create($shopData);

            // Assign roles to users
            $shop->users()->attach([
                $owner->id => ['role' => 'owner'],
                User::where('email', 'manager@example.com')->first()->id => ['role' => 'manager'],
                User::where('email', 'stock@example.com')->first()->id => ['role' => 'stock_checker'],
                User::where('email', 'finance@example.com')->first()->id => ['role' => 'finance'],
            ]);
        }
    }
}

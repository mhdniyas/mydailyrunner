<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Muhammed Niyas K M',
            'email' => 'mhdniyas37@gmail.com',
            'password' => Hash::make('pwd12345'),
            'is_subscribed' => true,
            'is_admin_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Create or get the first shop
        $shop = Shop::first();
        if (!$shop) {
            $shop = Shop::create([
                'name' => 'ARD 5',
                'address' => 'Kavumannam PO, Wayanad, Kerala 673122',
                'phone' => '+91-9400960223',
                'owner_id' => $adminUser->id,
            ]);
        }

        // Assign admin role to the user
        ShopUser::create([
            'user_id' => $adminUser->id,
            'shop_id' => $shop->id,
            'role' => 'admin',
        ]);
    }
}

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
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_subscribed' => true,
            'is_admin_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Create shop
        $shop = Shop::create([
            'name' => 'RATION Shop',
            'address' => 'KAVUMANNAM',
            'phone' => '9400960223',
            'owner_id' => $adminUser->id,
        ]);

        // Assign admin role to the user for the shop
        ShopUser::create([
            'user_id' => $adminUser->id,
            'shop_id' => $shop->id,
            'role' => 'admin',
        ]);
    }
}

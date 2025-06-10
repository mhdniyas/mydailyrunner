<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@mydailyrunner.com',
            'password' => Hash::make('password123'),
            'is_subscribed' => true,
            'is_admin_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Create or get the first shop
        $shop = Shop::first();
        if (!$shop) {
            $shop = Shop::create([
                'name' => 'Main Shop',
                'address' => '123 Main Street',
                'phone' => '+1234567890',
                'email' => 'shop@mydailyrunner.com',
                'owner_id' => $adminUser->id,
            ]);
        }

        // Assign admin role to the user
        ShopUser::create([
            'user_id' => $adminUser->id,
            'shop_id' => $shop->id,
            'role' => 'admin',
        ]);

        // Create other sample users
        $users = [
            [
                'name' => 'Shop Owner',
                'email' => 'owner@mydailyrunner.com',
                'password' => Hash::make('password123'),
                'is_subscribed' => true,
                'is_admin_approved' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Shop Manager',
                'email' => 'manager@mydailyrunner.com',
                'password' => Hash::make('password123'),
                'is_subscribed' => true,
                'is_admin_approved' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Stock User',
                'email' => 'stock@mydailyrunner.com',
                'password' => Hash::make('password123'),
                'is_subscribed' => true,
                'is_admin_approved' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Finance User',
                'email' => 'finance@mydailyrunner.com',
                'password' => Hash::make('password123'),
                'is_subscribed' => true,
                'is_admin_approved' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Assign appropriate roles to sample users
            $role = match($userData['email']) {
                'owner@mydailyrunner.com' => 'owner',
                'manager@mydailyrunner.com' => 'manager',
                'stock@mydailyrunner.com' => 'stock',
                'finance@mydailyrunner.com' => 'finance',
                default => 'viewer'
            };

            ShopUser::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'role' => $role,
            ]);
        }
    }
}

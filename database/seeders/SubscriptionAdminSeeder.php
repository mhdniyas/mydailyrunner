<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SubscriptionAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates users with admin role who can approve subscription requests.
     */
    public function run(): void
    {
        $this->command->info('Creating subscription admin users...');

        // Admin users who can approve subscription requests
        $adminUsers = [
            [
                'name' => 'Subscription Admin',
                'email' => 'subscription.admin@mydailyrunner.com',
                'password' => Hash::make('SecurePass123!'),
            ],
            [
                'name' => 'Main Administrator',
                'email' => 'main.admin@mydailyrunner.com',
                'password' => Hash::make('SecurePass123!'),
            ]
        ];

        // Get or create the first shop
        $shop = Shop::first();
        if (!$shop) {
            $shop = Shop::create([
                'name' => 'Default Shop',
                'address' => '123 Business Avenue',
                'phone' => '+1-555-0123',
                'email' => 'shop@mydailyrunner.com',
                'owner_id' => 1, // Will be updated after creating the first admin
            ]);
        }

        foreach ($adminUsers as $adminData) {
            // Check if user already exists
            $existingUser = User::where('email', $adminData['email'])->first();
            
            if ($existingUser) {
                $this->command->warn("User {$adminData['email']} already exists. Updating to admin role...");
                $user = $existingUser;
            } else {
                $user = User::create($adminData);
                $this->command->info("Created user: {$adminData['name']} ({$adminData['email']})");
            }

            // Update shop owner if this is the first admin
            if ($shop->owner_id === 1 && $user->id !== 1) {
                $shop->update(['owner_id' => $user->id]);
            }

            // Check if user already has admin role in this shop
            $existingShopUser = ShopUser::where('user_id', $user->id)
                ->where('shop_id', $shop->id)
                ->first();

            if ($existingShopUser) {
                $existingShopUser->update(['role' => 'admin']);
                $this->command->info("Updated {$user->name} to admin role in shop: {$shop->name}");
            } else {
                ShopUser::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'role' => 'admin',
                ]);
                $this->command->info("Assigned admin role to {$user->name} in shop: {$shop->name}");
            }
        }

        $this->command->info('✓ Subscription admin users created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        foreach ($adminUsers as $adminData) {
            $this->command->info("Email: {$adminData['email']} | Password: SecurePass123!");
        }
        $this->command->info('');
        $this->command->info('These users can now approve/reject subscription requests.');
    }
}

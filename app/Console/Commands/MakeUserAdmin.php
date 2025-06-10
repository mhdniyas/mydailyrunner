<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email} {--shop-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an admin in a shop';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $shopId = $this->option('shop-id');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        if (!$shopId) {
            // Get the first shop
            $shop = Shop::first();
            if (!$shop) {
                $this->error("No shops found. Please create a shop first.");
                return 1;
            }
            $shopId = $shop->id;
        } else {
            $shop = Shop::find($shopId);
            if (!$shop) {
                $this->error("Shop with ID {$shopId} not found.");
                return 1;
            }
        }
        
        // Check if user already has a role in this shop
        $existingShopUser = ShopUser::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->first();
            
        if ($existingShopUser) {
            $existingShopUser->role = 'admin';
            $existingShopUser->save();
            $this->info("Updated user {$user->name} to admin role in shop {$shop->name}");
        } else {
            ShopUser::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
                'role' => 'admin',
            ]);
            $this->info("Added user {$user->name} as admin to shop {$shop->name}");
        }
        
        $this->info("User {$user->name} ({$user->email}) is now an admin!");
        
        return 0;
    }
}

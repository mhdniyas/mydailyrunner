<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSubscriptionAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:create-admin 
                            {name : The name of the admin user}
                            {email : The email address of the admin user}
                            {--password= : The password for the admin user (will be generated if not provided)}
                            {--shop-id= : The shop ID to assign admin role (will use first shop if not provided)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user who can approve subscription requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->option('password') ?? $this->generatePassword();
        $shopId = $this->option('shop-id');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line("  - $error");
            }
            return 1;
        }

        // Get shop
        if ($shopId) {
            $shop = Shop::find($shopId);
            if (!$shop) {
                $this->error("Shop with ID {$shopId} not found.");
                return 1;
            }
        } else {
            $shop = Shop::first();
            if (!$shop) {
                $this->error("No shops found. Please create a shop first.");
                return 1;
            }
        }

        try {
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            // Assign admin role
            ShopUser::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'role' => 'admin',
            ]);

            $this->info("✓ Admin user created successfully!");
            $this->line("");
            $this->line("User Details:");
            $this->line("  Name: {$user->name}");
            $this->line("  Email: {$user->email}");
            $this->line("  Password: {$password}");
            $this->line("  Shop: {$shop->name}");
            $this->line("  Role: admin");
            $this->line("");
            $this->info("This user can now approve/reject subscription requests.");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error creating admin user: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate a secure random password.
     */
    private function generatePassword(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < 12; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $password;
    }
}

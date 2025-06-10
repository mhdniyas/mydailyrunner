<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have any shop user records with the owner role
        $firstOwner = DB::table('shop_users')
            ->where('role', 'owner')
            ->first();
        
        // If we have an owner, update their role to admin
        if ($firstOwner) {
            DB::table('shop_users')
                ->where('id', $firstOwner->id)
                ->update(['role' => 'admin']);
        }
    }
}

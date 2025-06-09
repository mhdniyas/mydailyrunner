<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'superadmin' => true,
        ]);

        // Create regular users
        $users = [
            [
                'name' => 'Shop Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'superadmin' => false,
            ],
            [
                'name' => 'Shop Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'superadmin' => false,
            ],
            [
                'name' => 'Stock Checker',
                'email' => 'stock@example.com',
                'password' => Hash::make('password'),
                'superadmin' => false,
            ],
            [
                'name' => 'Finance User',
                'email' => 'finance@example.com',
                'password' => Hash::make('password'),
                'superadmin' => false,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     

        // Call other seeders
        $this->call([
            AdminSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            DefaultCategoriesSeeder::class,
        ]);
    }
}
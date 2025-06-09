<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialCategory;
use App\Models\Shop;

class DefaultCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::all();

        foreach ($shops as $shop) {
            // Income categories
            $incomeCategories = [
                ['name' => 'Sales', 'type' => 'income', 'description' => 'Income from product sales'],
                ['name' => 'Investment', 'type' => 'income', 'description' => 'Capital investment'],
                ['name' => 'Other Income', 'type' => 'income', 'description' => 'Miscellaneous income'],
            ];

            // Expense categories
            $expenseCategories = [
                ['name' => 'Rent', 'type' => 'expense', 'description' => 'Shop rent'],
                ['name' => 'Utilities', 'type' => 'expense', 'description' => 'Electricity, water, etc.'],
                ['name' => 'Salaries', 'type' => 'expense', 'description' => 'Staff salaries'],
                ['name' => 'Transportation', 'type' => 'expense', 'description' => 'Transportation costs'],
                ['name' => 'Maintenance', 'type' => 'expense', 'description' => 'Shop maintenance'],
                ['name' => 'Supplies', 'type' => 'expense', 'description' => 'Office supplies'],
                ['name' => 'Marketing', 'type' => 'expense', 'description' => 'Advertising and marketing'],
                ['name' => 'Taxes', 'type' => 'expense', 'description' => 'Business taxes'],
                ['name' => 'Other Expense', 'type' => 'expense', 'description' => 'Miscellaneous expenses'],
            ];

            // Create income categories
            foreach ($incomeCategories as $category) {
                FinancialCategory::create([
                    'shop_id' => $shop->id,
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'description' => $category['description'],
                ]);
            }

            // Create expense categories
            foreach ($expenseCategories as $category) {
                FinancialCategory::create([
                    'shop_id' => $shop->id,
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'description' => $category['description'],
                ]);
            }
        }
    }
}
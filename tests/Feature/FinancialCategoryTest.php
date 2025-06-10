<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use App\Models\FinancialCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $shop;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create a user and shop for testing
        $this->user = User::factory()->create();
        $this->shop = Shop::factory()->create(['owner_id' => $this->user->id]);
        
        // Attach user to shop with owner role
        $this->user->shops()->attach($this->shop->id, ['role' => 'owner']);
    }

    /** @test */
    public function user_can_create_income_category()
    {
        // Set current shop in session
        session(['current_shop_id' => $this->shop->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('financial-categories.store'), [
                'name' => 'Sales Revenue',
                'type' => 'income',
                'description' => 'Revenue from product sales'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Income category created successfully.',
                'category' => [
                    'name' => 'Sales Revenue',
                    'type' => 'income'
                ]
            ]);

        $this->assertDatabaseHas('financial_categories', [
            'shop_id' => $this->shop->id,
            'user_id' => $this->user->id,
            'name' => 'Sales Revenue',
            'type' => 'income',
            'description' => 'Revenue from product sales'
        ]);
    }

    /** @test */
    public function user_can_create_expense_category()
    {
        session(['current_shop_id' => $this->shop->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('financial-categories.store'), [
                'name' => 'Office Supplies',
                'type' => 'expense',
                'description' => 'Expenses for office supplies'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Expense category created successfully.',
                'category' => [
                    'name' => 'Office Supplies',
                    'type' => 'expense'
                ]
            ]);

        $this->assertDatabaseHas('financial_categories', [
            'shop_id' => $this->shop->id,
            'user_id' => $this->user->id,
            'name' => 'Office Supplies',
            'type' => 'expense'
        ]);
    }

    /** @test */
    public function cannot_create_duplicate_category_name_for_same_type()
    {
        session(['current_shop_id' => $this->shop->id]);

        // Create first category
        FinancialCategory::create([
            'shop_id' => $this->shop->id,
            'user_id' => $this->user->id,
            'name' => 'Sales Revenue',
            'type' => 'income'
        ]);

        // Try to create duplicate
        $response = $this->actingAs($this->user)
            ->postJson(route('financial-categories.store'), [
                'name' => 'Sales Revenue',
                'type' => 'income'
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'A income category with this name already exists.'
            ]);
    }

    /** @test */
    public function user_without_shop_access_cannot_create_category()
    {
        $otherUser = User::factory()->create();
        session(['current_shop_id' => $this->shop->id]);

        $response = $this->actingAs($otherUser)
            ->postJson(route('financial-categories.store'), [
                'name' => 'Test Category',
                'type' => 'income'
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You do not have access to this shop.'
            ]);
    }

    /** @test */
    public function viewer_role_cannot_create_category()
    {
        $viewerUser = User::factory()->create();
        $viewerUser->shops()->attach($this->shop->id, ['role' => 'viewer']);
        
        session(['current_shop_id' => $this->shop->id]);

        $response = $this->actingAs($viewerUser)
            ->postJson(route('financial-categories.store'), [
                'name' => 'Test Category',
                'type' => 'income'
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'You do not have permission to create financial categories.'
            ]);
    }

    /** @test */
    public function validation_errors_are_returned()
    {
        session(['current_shop_id' => $this->shop->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('financial-categories.store'), [
                'name' => '', // Invalid - required
                'type' => 'invalid_type' // Invalid - not in enum
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed'
            ])
            ->assertJsonValidationErrors(['name', 'type']);
    }
}

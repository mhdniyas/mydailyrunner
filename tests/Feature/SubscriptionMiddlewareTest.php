<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $regularUser;
    protected $adminUser;
    protected $nonSubscribedUser;
    protected $pendingUser;
    protected $approvedUser;
    protected $shop;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a shop
        $this->shop = Shop::factory()->create([
            'name' => 'Test Shop',
        ]);

        // Create users with different subscription statuses
        $this->regularUser = User::factory()->create();
        
        $this->adminUser = User::factory()->create();
        
        // Assign admin role
        ShopUser::create([
            'user_id' => $this->adminUser->id,
            'shop_id' => $this->shop->id,
            'role' => 'admin',
        ]);
        
        // Non-subscribed user
        $this->nonSubscribedUser = User::factory()->create([
            'is_subscribed' => false,
            'is_admin_approved' => false,
        ]);

        // Pending approval user
        $this->pendingUser = User::factory()->create([
            'is_subscribed' => true,
            'is_admin_approved' => false,
        ]);

        // Approved user
        $this->approvedUser = User::factory()->create([
            'is_subscribed' => true,
            'is_admin_approved' => true,
        ]);
        
        // Assign normal roles to other users
        foreach ([$this->regularUser, $this->nonSubscribedUser, $this->pendingUser, $this->approvedUser] as $user) {
            ShopUser::create([
                'user_id' => $user->id,
                'shop_id' => $this->shop->id,
                'role' => 'manager',
            ]);
        }
    }

    #[Test]
    public function subscription_admin_middleware_allows_admin_access()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function subscription_admin_middleware_blocks_non_admin_access()
    {
        // Regular user without admin role
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(403)
            ->assertSee('You do not have permission to access this page');

        // Even subscribed users without admin role cannot access
        $response = $this->actingAs($this->approvedUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(403)
            ->assertSee('You do not have permission to access this page');
    }

    #[Test]
    public function subscription_middleware_allows_subscribed_and_approved_users()
    {
        // Use a route protected by the subscribed middleware
        $response = $this->actingAs($this->approvedUser)
            ->get(route('products.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function subscription_middleware_redirects_non_subscribed_users()
    {
        // Use a route protected by the subscribed middleware
        $response = $this->actingAs($this->nonSubscribedUser)
            ->get(route('products.index'));

        $response->assertRedirect(route('subscription.status'));
    }

    #[Test]
    public function subscription_middleware_redirects_pending_users()
    {
        // Use a route protected by the subscribed middleware
        $response = $this->actingAs($this->pendingUser)
            ->get(route('products.index'));

        $response->assertRedirect(route('subscription.status'));
    }

    #[Test]
    public function admin_users_can_always_access_protected_routes_regardless_of_subscription()
    {
        // Set admin to not subscribed
        $this->adminUser->is_subscribed = false;
        $this->adminUser->is_admin_approved = false;
        $this->adminUser->save();
        
        // Admin should still be able to access subscription admin routes
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(200);
        
        // And protected routes
        $response = $this->actingAs($this->adminUser)
            ->get(route('products.index'));

        $response->assertStatus(200);
    }
}

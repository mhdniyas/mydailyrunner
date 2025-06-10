<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionStatusChanged;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $regularUser;
    protected $adminUser;
    protected $shop;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users and shop
        $this->regularUser = User::factory()->create([
            'is_subscribed' => false,
            'is_admin_approved' => false,
        ]);
        
        $this->adminUser = User::factory()->create([
            'is_subscribed' => true,
            'is_admin_approved' => true,
        ]);

        $this->shop = Shop::factory()->create([
            'name' => 'Test Shop',
            'owner_id' => $this->adminUser->id,
        ]);

        // Assign admin role to admin user
        ShopUser::create([
            'user_id' => $this->adminUser->id,
            'shop_id' => $this->shop->id,
            'role' => 'admin',
        ]);

        // Assign regular user role
        ShopUser::create([
            'user_id' => $this->regularUser->id,
            'shop_id' => $this->shop->id,
            'role' => 'manager',
        ]);

        // Disable real notifications during testing
        Notification::fake();
    }

    #[Test]
    public function regular_user_can_view_subscription_status()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('subscription.status'));

        $response->assertStatus(200);
        $response->assertViewIs('subscription.status');
    }

    #[Test]
    public function regular_user_can_request_subscription()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('subscription.request'));

        $response->assertStatus(200);
        $response->assertViewIs('subscription.request');

        $response = $this->actingAs($this->regularUser)
            ->post(route('subscription.submit'));

        $response->assertRedirect(route('subscription.status'));
        $response->assertSessionHas('success', 'Your subscription request has been submitted and is pending approval.');

        // Verify user subscription status is updated
        $this->regularUser->refresh();
        $this->assertTrue($this->regularUser->is_subscribed);
        $this->assertFalse($this->regularUser->is_admin_approved);

        // Verify admin notifications were sent
        Notification::assertSentTo(
            [$this->adminUser], SubscriptionStatusChanged::class
        );
    }

    #[Test]
    public function regular_user_can_cancel_subscription()
    {
        // Set up user with active subscription
        $this->regularUser->is_subscribed = true;
        $this->regularUser->is_admin_approved = true;
        $this->regularUser->save();

        $response = $this->actingAs($this->regularUser)
            ->post(route('subscription.cancel'));

        $response->assertRedirect(route('subscription.status'));
        $response->assertSessionHas('success', 'Your subscription has been cancelled.');

        // Verify user subscription status is updated
        $this->regularUser->refresh();
        $this->assertFalse($this->regularUser->is_subscribed);
        $this->assertFalse($this->regularUser->is_admin_approved);

        // Verify admin notifications were sent
        Notification::assertSentTo(
            [$this->adminUser], SubscriptionStatusChanged::class
        );
    }

    #[Test]
    public function regular_user_cannot_access_admin_subscription_pages()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(403);

        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.subscriptions.pending'));

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_view_subscription_management_pages()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.subscriptions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.subscriptions.index');

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.subscriptions.pending'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.subscriptions.pending');
    }

    #[Test]
    public function admin_can_approve_subscription()
    {
        // Set up user with pending subscription
        $this->regularUser->is_subscribed = true;
        $this->regularUser->is_admin_approved = false;
        $this->regularUser->save();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.subscriptions.approve', $this->regularUser->id));

        $response->assertRedirect(route('admin.subscriptions.pending'));
        $response->assertSessionHas('success', 'Subscription approved successfully for ' . $this->regularUser->name);

        // Verify user subscription status is updated
        $this->regularUser->refresh();
        $this->assertTrue($this->regularUser->is_subscribed);
        $this->assertTrue($this->regularUser->is_admin_approved);

        // Verify notifications were sent to the user
        Notification::assertSentTo(
            [$this->regularUser], SubscriptionStatusChanged::class
        );
    }

    #[Test]
    public function admin_can_reject_subscription()
    {
        // Set up user with pending subscription
        $this->regularUser->is_subscribed = true;
        $this->regularUser->is_admin_approved = false;
        $this->regularUser->save();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.subscriptions.reject', $this->regularUser->id));

        $response->assertRedirect(route('admin.subscriptions.pending'));
        $response->assertSessionHas('success', 'Subscription rejected for ' . $this->regularUser->name);

        // Verify user subscription status is updated
        $this->regularUser->refresh();
        $this->assertFalse($this->regularUser->is_subscribed);
        $this->assertFalse($this->regularUser->is_admin_approved);

        // Verify notifications were sent to the user
        Notification::assertSentTo(
            [$this->regularUser], SubscriptionStatusChanged::class
        );
    }

    #[Test]
    public function admin_can_toggle_subscription()
    {
        // Set up user with inactive subscription
        $this->regularUser->is_subscribed = false;
        $this->regularUser->is_admin_approved = false;
        $this->regularUser->save();

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.subscriptions.toggle', $this->regularUser->id));

        $response->assertRedirect(route('admin.subscriptions.index'));
        $response->assertSessionHas('success', 'Subscription activated successfully for ' . $this->regularUser->name);

        // Verify user subscription status is activated
        $this->regularUser->refresh();
        $this->assertTrue($this->regularUser->is_subscribed);
        $this->assertTrue($this->regularUser->is_admin_approved);

        // Toggle again to deactivate
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.subscriptions.toggle', $this->regularUser->id));

        // Verify user subscription status is deactivated
        $this->regularUser->refresh();
        $this->assertFalse($this->regularUser->is_subscribed);
        $this->assertFalse($this->regularUser->is_admin_approved);

        // Verify notifications were sent to the user
        Notification::assertSentTo(
            [$this->regularUser], SubscriptionStatusChanged::class, 2
        );
    }
}

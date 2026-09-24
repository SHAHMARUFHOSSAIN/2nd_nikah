<?php

namespace Tests\Feature;

use App\Livewire\Member\Payments;
use App\Livewire\Membership\Checkout;
use App\Livewire\Membership\Index;
use App\Models\MembershipPlan;
use App\Models\MemberProfile;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class MembershipPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'sslcommerz.store_id' => 'test_store_id',
            'sslcommerz.store_password' => 'test_store_password',
            'sslcommerz.sandbox' => true,
        ]);

        // Seed membership plans if empty
        if (MembershipPlan::count() === 0) {
            MembershipPlan::create([
                'name' => 'Weekly',
                'slug' => 'weekly-bdt',
                'country_scope' => 'BD',
                'amount' => 100.00,
                'currency' => 'BDT',
                'billing_interval' => 'weekly',
                'duration_days' => 7,
                'is_active' => true,
            ]);
            MembershipPlan::create([
                'name' => 'Monthly',
                'slug' => 'monthly-bdt',
                'country_scope' => 'BD',
                'amount' => 300.00,
                'currency' => 'BDT',
                'billing_interval' => 'monthly',
                'duration_days' => 30,
                'is_active' => true,
            ]);
            MembershipPlan::create([
                'name' => 'Weekly',
                'slug' => 'weekly-usd',
                'country_scope' => 'INTERNATIONAL',
                'amount' => 5.00,
                'currency' => 'USD',
                'billing_interval' => 'weekly',
                'duration_days' => 7,
                'is_active' => true,
            ]);
            MembershipPlan::create([
                'name' => 'Monthly',
                'slug' => 'monthly-usd',
                'country_scope' => 'INTERNATIONAL',
                'amount' => 10.00,
                'currency' => 'USD',
                'billing_interval' => 'monthly',
                'duration_days' => 30,
                'is_active' => true,
            ]);
        }
    }

    private function createVerifiedUser(array $userAttrs = [], array $profileAttrs = []): User
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'is_active' => true,
            'is_admin' => false,
        ], $userAttrs));

        MemberProfile::create(array_merge([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'Member',
            'country' => 'Bangladesh',
            'is_profile_visible' => true,
        ], $profileAttrs));

        return $user;
    }

    public function test_1_membership_plans_page_loads_successfully(): void
    {
        $response = $this->get('/membership');
        $response->assertStatus(200);
        $response->assertSee('Choose Your Matrimonial Plan');
    }

    public function test_2_bangladesh_selects_bdt_plans(): void
    {
        Livewire::test(Index::class, ['country' => 'Bangladesh'])
            ->assertSee('weekly-bdt')
            ->assertSee('monthly-bdt')
            ->assertDontSee('weekly-usd');
    }

    public function test_3_international_country_selects_usd_plans(): void
    {
        Livewire::test(Index::class, ['country' => 'United States'])
            ->assertSee('weekly-usd')
            ->assertSee('monthly-usd')
            ->assertDontSee('weekly-bdt');
    }

    public function test_4_unauthenticated_user_cannot_checkout(): void
    {
        Livewire::test(Checkout::class, ['planSlug' => 'weekly-bdt', 'country' => 'Bangladesh'])
            ->assertRedirect(route('login'));
    }

    public function test_5_unverified_user_cannot_checkout(): void
    {
        $unverifiedUser = User::factory()->create(['email_verified_at' => null, 'is_active' => true]);
        MemberProfile::create(['user_id' => $unverifiedUser->id, 'first_name' => 'Unverified', 'country' => 'Bangladesh']);

        $this->actingAs($unverifiedUser);

        Livewire::test(Checkout::class, ['planSlug' => 'weekly-bdt', 'country' => 'Bangladesh'])
            ->assertRedirect(route('verification.notice'));
    }

    public function test_6_browser_cannot_override_database_amount_and_currency(): void
    {
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // Mock SSLCommerz API endpoint response
        Http::fake([
            'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' => Http::response([
                'status' => 'SUCCESS',
                'GatewayPageURL' => 'https://sandbox.sslcommerz.com/easy-checkout/session123',
                'sessionkey' => 'session123',
            ], 200),
        ]);

        Livewire::test(Checkout::class, ['planSlug' => 'weekly-bdt', 'country' => 'Bangladesh'])
            ->call('initiatePayment')
            ->assertRedirect('https://sandbox.sslcommerz.com/easy-checkout/session123');

        // Check local transaction record in DB: amount must be exactly 100.00 (from DB, not client)
        $this->assertDatabaseHas('payment_transactions', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'initiated',
        ]);
    }

    public function test_7_success_callback_requires_valid_gateway_validation(): void
    {
        $user = $this->createVerifiedUser();
        $plan = MembershipPlan::where('slug', 'weekly-bdt')->first();

        $txn = PaymentTransaction::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'NIKAH_TXN_TEST_123',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'initiated',
        ]);

        // Fake SSLCommerz validation API returning VALID
        Http::fake([
            'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php*' => Http::response([
                'status' => 'VALID',
                'amount' => '100.00',
                'currency' => 'BDT',
                'bank_tran_id' => 'BANK_TXN_999',
            ], 200),
        ]);

        $response = $this->post('/payment/success', [
            'tran_id' => 'NIKAH_TXN_TEST_123',
            'val_id' => 'VAL_ID_999',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => 'NIKAH_TXN_TEST_123',
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        $this->assertTrue($user->fresh()->isPremium());
    }

    public function test_8_wrong_amount_callback_is_rejected(): void
    {
        $user = $this->createVerifiedUser();
        $plan = MembershipPlan::where('slug', 'weekly-bdt')->first();

        $txn = PaymentTransaction::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'NIKAH_TXN_WRONG_AMT',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'initiated',
        ]);

        // Fake SSLCommerz validation API returning WRONG amount (50.00 instead of 100.00)
        Http::fake([
            'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php*' => Http::response([
                'status' => 'VALID',
                'amount' => '50.00',
                'currency' => 'BDT',
                'bank_tran_id' => 'BANK_TXN_999',
            ], 200),
        ]);

        $response = $this->post('/payment/success', [
            'tran_id' => 'NIKAH_TXN_WRONG_AMT',
            'val_id' => 'VAL_ID_999',
        ]);

        $response->assertRedirect(route('membership.index'));

        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => 'NIKAH_TXN_WRONG_AMT',
            'status' => 'failed',
        ]);

        $this->assertDatabaseCount('subscriptions', 0);
        $this->assertFalse($user->fresh()->isPremium());
    }

    public function test_9_idempotency_prevents_duplicate_subscription(): void
    {
        $user = $this->createVerifiedUser();
        $plan = MembershipPlan::where('slug', 'weekly-bdt')->first();

        $txn = PaymentTransaction::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'NIKAH_TXN_DUP_123',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'paid',
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
            'payment_transaction_id' => $txn->id,
        ]);

        // Duplicate POST to success endpoint
        $response = $this->post('/payment/success', [
            'tran_id' => 'NIKAH_TXN_DUP_123',
            'val_id' => 'VAL_ID_999',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('subscriptions', 1);
    }

    public function test_10_active_subscription_blocks_overlapping_purchase(): void
    {
        $user = $this->createVerifiedUser();

        $plan = MembershipPlan::where('slug', 'weekly-bdt')->first();
        Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
        ]);

        $this->actingAs($user);

        Livewire::test(Checkout::class, ['planSlug' => 'monthly-bdt', 'country' => 'Bangladesh'])
            ->assertSee('Active Premium Membership Exists')
            ->call('initiatePayment')
            ->assertSee('You already have an active Premium membership subscription');
    }

    public function test_11_expired_subscription_is_not_premium(): void
    {
        $user = $this->createVerifiedUser();
        $plan = MembershipPlan::where('slug', 'weekly-bdt')->first();

        Subscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDays(3), // Expired 3 days ago
        ]);

        $this->assertFalse($user->fresh()->isPremium());
    }

    public function test_12_payment_history_shows_only_own_records(): void
    {
        $userA = $this->createVerifiedUser();
        $userB = $this->createVerifiedUser();

        $plan = MembershipPlan::first();

        PaymentTransaction::create([
            'user_id' => $userA->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'TXN_USER_A_111',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'paid',
        ]);

        PaymentTransaction::create([
            'user_id' => $userB->id,
            'membership_plan_id' => $plan->id,
            'transaction_id' => 'TXN_USER_B_999',
            'amount' => 100.00,
            'currency' => 'BDT',
            'status' => 'paid',
        ]);

        $this->actingAs($userA);

        Livewire::test(Payments::class)
            ->assertSee('TXN_USER_A_111')
            ->assertDontSee('TXN_USER_B_999');
    }
}

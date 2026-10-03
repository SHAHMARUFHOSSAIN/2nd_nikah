<?php

namespace App\Livewire\Membership;

use App\Models\MembershipPlan;
use App\Models\PaymentTransaction;
use App\Services\Payment\SSLCommerzPaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class Checkout extends Component
{
    #[Url(as: 'plan')]
    public string $planSlug = 'weekly-bdt';

    #[Url]
    public string $country = 'Bangladesh';

    public ?string $errorMessage = null;

    public bool $agreeTerms = false;

    public function mount(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        if (! Auth::user()->hasVerifiedEmail()) {
            $this->redirect(route('verification.notice'));
            return;
        }
    }

    public function initiatePayment(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->hasVerifiedEmail()) {
            $this->errorMessage = 'You must be logged in and email verified to purchase a membership.';
            return;
        }

        if (! $this->agreeTerms) {
            $this->errorMessage = 'You must read and agree to our Terms & Conditions, Privacy Policy, and Return & Refund Policy to proceed with payment.';
            return;
        }

        // Active subscription check
        if ($user->isPremium()) {
            $this->errorMessage = 'You already have an active Premium membership subscription. Duplicate overlapping purchases are not allowed.';
            return;
        }

        // Server-side plan lookup (Client overrides for amount/currency are IMPOSSIBLE)
        $plan = MembershipPlan::where('slug', $this->planSlug)
            ->forCountry($this->country)
            ->active()
            ->first();

        if (! $plan) {
            $this->errorMessage = 'The requested membership plan is invalid or not available for your country scope.';
            return;
        }

        try {
            /** @var PaymentTransaction $transaction */
            $transaction = DB::transaction(function () use ($user, $plan) {
                return PaymentTransaction::create([
                    'user_id' => $user->id,
                    'membership_plan_id' => $plan->id,
                    'transaction_id' => PaymentTransaction::generateTransactionId(),
                    'amount' => $plan->amount,
                    'currency' => $plan->currency,
                    'status' => 'initiated',
                    'gateway' => 'sslcommerz',
                ]);
            });

            $gateway = new SSLCommerzPaymentGateway();
            $result = $gateway->initiatePayment($transaction, $user);

            if (isset($result['status']) && $result['status'] === 'SUCCESS' && ! empty($result['gateway_url'])) {
                $this->redirect($result['gateway_url']);
                return;
            }

            $transaction->update([
                'status' => 'failed',
                'failed_at' => now(),
                'gateway_response' => json_encode($result),
            ]);

            $this->errorMessage = $result['message'] ?? 'Unable to initialize SSLCommerz gateway session. Please try again.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Payment initiation error: ' . $e->getMessage();
        }
    }

    public function render()
    {
        $plan = MembershipPlan::where('slug', $this->planSlug)
            ->forCountry($this->country)
            ->active()
            ->first();

        if (! $plan) {
            // Fallback lookup if exact slug mismatch
            $plan = MembershipPlan::active()
                ->forCountry($this->country)
                ->first();
        }

        $user = Auth::user();
        $hasActiveSub = $user ? $user->isPremium() : false;
        $activeSub = $user ? $user->activeSubscription : null;

        return view('livewire.membership.checkout', [
            'plan' => $plan,
            'hasActiveSub' => $hasActiveSub,
            'activeSub' => $activeSub,
        ])->layout('components.layouts.app', ['title' => 'Membership Checkout']);
    }
}

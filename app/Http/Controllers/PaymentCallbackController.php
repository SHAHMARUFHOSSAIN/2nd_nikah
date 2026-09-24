<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\PaymentSuccessfulNotification;
use App\Notifications\SubscriptionActivatedNotification;
use App\Services\Payment\SSLCommerzPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    public function success(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $valId = (string) $request->input('val_id');

        if (empty($tranId)) {
            return redirect()->route('membership.index')->with('error', 'Invalid payment callback: missing transaction ID.');
        }

        $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();

        if (! $transaction) {
            return redirect()->route('membership.index')->with('error', 'Payment transaction record not found.');
        }

        // Idempotency: If already paid, do not re-process or extend
        if ($transaction->status === 'paid') {
            return redirect()->route('dashboard')->with('status', 'Payment already processed and membership is active.');
        }

        $gateway = new SSLCommerzPaymentGateway();
        $validation = $gateway->validateTransaction($valId, $tranId, (float) $transaction->amount, $transaction->currency);

        if ($validation['status'] !== 'SUCCESS') {
            $transaction->update([
                'status' => 'failed',
                'failed_at' => now(),
                'gateway_response' => json_encode($request->all()),
            ]);

            if ($transaction->user) {
                $transaction->user->notify(new PaymentFailedNotification($transaction, $validation['message'] ?? 'Validation failed'));
            }

            return redirect()->route('membership.index')->with('error', 'Payment verification failed: ' . ($validation['message'] ?? 'Invalid response from gateway.'));
        }

        // Secure DB Transaction: Mark paid + Create active subscription
        DB::transaction(function () use ($transaction, $valId, $request) {
            $plan = $transaction->membershipPlan;

            $subscription = Subscription::create([
                'user_id' => $transaction->user_id,
                'membership_plan_id' => $transaction->membership_plan_id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days),
                'payment_transaction_id' => $transaction->id,
            ]);

            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'bank_transaction_id' => $validation['bank_transaction_id'] ?? $valId,
                'subscription_id' => $subscription->id,
                'gateway_response' => json_encode($request->all()),
            ]);

            if ($transaction->user) {
                $transaction->user->notify(new PaymentSuccessfulNotification($transaction));
                $transaction->user->notify(new SubscriptionActivatedNotification($subscription));
            }
        });

        return redirect()->route('dashboard')->with('status', 'Payment verified successfully! Your Premium membership is now active.');
    }

    public function fail(Request $request)
    {
        $tranId = (string) $request->input('tran_id');

        if ($tranId) {
            $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();
            if ($transaction && $transaction->status !== 'paid') {
                $transaction->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'gateway_response' => json_encode($request->all()),
                ]);

                if ($transaction->user) {
                    $transaction->user->notify(new PaymentFailedNotification($transaction, 'Payment failed at gateway.'));
                }
            }
        }

        return redirect()->route('membership.index')->with('error', 'Payment transaction failed or was declined by the bank.');
    }

    public function cancel(Request $request)
    {
        $tranId = (string) $request->input('tran_id');

        if ($tranId) {
            $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();
            if ($transaction && $transaction->status !== 'paid') {
                $transaction->update([
                    'status' => 'cancelled',
                    'failed_at' => now(),
                    'gateway_response' => json_encode($request->all()),
                ]);
            }
        }

        return redirect()->route('membership.index')->with('status', 'Payment process was cancelled.');
    }

    public function ipn(Request $request)
    {
        $tranId = (string) $request->input('tran_id');
        $valId = (string) $request->input('val_id');

        if ($tranId && $valId) {
            $transaction = PaymentTransaction::where('transaction_id', $tranId)->first();

            if ($transaction && $transaction->status !== 'paid') {
                $gateway = new SSLCommerzPaymentGateway();
                $validation = $gateway->validateTransaction($valId, $tranId, (float) $transaction->amount, $transaction->currency);

                if ($validation['status'] === 'SUCCESS') {
                    DB::transaction(function () use ($transaction, $valId, $request, $validation) {
                        $plan = $transaction->membershipPlan;

                        $subscription = Subscription::create([
                            'user_id' => $transaction->user_id,
                            'membership_plan_id' => $transaction->membership_plan_id,
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => now()->addDays($plan->duration_days),
                            'payment_transaction_id' => $transaction->id,
                        ]);

                        $transaction->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'bank_transaction_id' => $validation['bank_transaction_id'] ?? $valId,
                            'subscription_id' => $subscription->id,
                            'gateway_response' => json_encode($request->all()),
                        ]);

                        if ($transaction->user) {
                            $transaction->user->notify(new PaymentSuccessfulNotification($transaction));
                            $transaction->user->notify(new SubscriptionActivatedNotification($subscription));
                        }
                    });
                }
            }
        }

        return response('IPN Processed', 200);
    }
}

<?php

namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentSuccessfulNotification extends Notification
{
    use Queueable;

    public function __construct(public PaymentTransaction $transaction)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $planName = $this->transaction->membershipPlan->name ?? 'Membership';
        $formattedAmount = ($this->transaction->currency === 'BDT' ? '৳' : '$') . number_format($this->transaction->amount, 2);

        return [
            'type' => 'payment_successful',
            'transaction_id' => $this->transaction->transaction_id,
            'amount' => $this->transaction->amount,
            'currency' => $this->transaction->currency,
            'message' => "Payment of {$formattedAmount} for {$planName} Plan was successful. (Txn ID: {$this->transaction->transaction_id})",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

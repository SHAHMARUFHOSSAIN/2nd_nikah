<?php

namespace App\Notifications;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification
{
    use Queueable;

    public function __construct(public PaymentTransaction $transaction, public string $reason = 'Payment process was not completed.')
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_failed',
            'transaction_id' => $this->transaction->transaction_id,
            'reason' => $this->reason,
            'message' => "Payment attempt for transaction ID {$this->transaction->transaction_id} failed or was cancelled. Reason: {$this->reason}",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

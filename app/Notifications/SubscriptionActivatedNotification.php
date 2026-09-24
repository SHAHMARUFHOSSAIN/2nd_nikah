<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionActivatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Subscription $subscription)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $planName = $this->subscription->membershipPlan->name ?? 'Premium';
        $expiryDate = $this->subscription->ends_at ? $this->subscription->ends_at->format('M d, Y') : '';

        return [
            'type' => 'subscription_activated',
            'subscription_id' => $this->subscription->id,
            'plan_name' => $planName,
            'ends_at' => $this->subscription->ends_at?->toIso8601String(),
            'message' => "Your Premium {$planName} membership is now active until {$expiryDate}. Enjoy full matrimonial benefits!",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

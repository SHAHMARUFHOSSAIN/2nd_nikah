<?php

namespace App\Notifications;

use App\Models\Interest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InterestReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(public Interest $interest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $senderName = $this->interest->sender->memberProfile?->full_name ?: $this->interest->sender->name;

        return [
            'type' => 'interest_received',
            'interest_id' => $this->interest->id,
            'sender_id' => $this->interest->sender_id,
            'sender_name' => $senderName,
            'message' => "{$senderName} has sent you an interest connection request.",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

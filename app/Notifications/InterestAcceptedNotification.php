<?php

namespace App\Notifications;

use App\Models\Interest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InterestAcceptedNotification extends Notification
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
        $receiverName = $this->interest->receiver->memberProfile?->full_name ?: $this->interest->receiver->name;

        return [
            'type' => 'interest_accepted',
            'interest_id' => $this->interest->id,
            'receiver_id' => $this->interest->receiver_id,
            'receiver_name' => $receiverName,
            'message' => "{$receiverName} accepted your interest request! You are now connected.",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

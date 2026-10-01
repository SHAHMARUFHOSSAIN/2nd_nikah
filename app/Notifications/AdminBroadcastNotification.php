<?php

namespace App\Notifications;

use App\Models\NotificationCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminBroadcastNotification extends Notification
{
    use Queueable;

    public NotificationCampaign $campaign;

    public function __construct(NotificationCampaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Clean structured payload for in-app database notifications
     * and future Web Push / Firebase Cloud Messaging (FCM) integration.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->campaign->title,
            'message' => $this->campaign->message,
            'type' => $this->campaign->type,
            'action_label' => $this->campaign->action_label,
            'action_url' => $this->campaign->action_url,
            'campaign_id' => $this->campaign->id,
            'audience' => $this->campaign->audience,
            'sent_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Standardized array structure for future push channels.
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

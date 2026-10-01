<?php

namespace App\Models;

use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;

class NotificationCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'audience',
        'action_label',
        'action_url',
        'recipient_count',
        'sent_count',
        'status',
        'created_by_id',
        'sent_at',
    ];

    protected $casts = [
        'recipient_count' => 'integer',
        'sent_count' => 'integer',
        'sent_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Build the server-side target User query based on audience segment.
     */
    public function calculateRecipientQuery(?string $audienceOverride = null): Builder
    {
        $audience = $audienceOverride ?? $this->audience;
        $now = now();

        $baseQuery = User::query()->where('is_active', true);

        return match ($audience) {
            'premium' => $baseQuery->whereHas('subscriptions', function ($q) use ($now) {
                $q->where('status', 'active')
                  ->whereNotNull('starts_at')
                  ->whereNotNull('ends_at')
                  ->where('starts_at', '<=', $now)
                  ->where('ends_at', '>=', $now);
            }),
            'free' => $baseQuery->whereDoesntHave('subscriptions', function ($q) use ($now) {
                $q->where('status', 'active')
                  ->whereNotNull('starts_at')
                  ->whereNotNull('ends_at')
                  ->where('starts_at', '<=', $now)
                  ->where('ends_at', '>=', $now);
            }),
            default => $baseQuery, // all_active
        };
    }

    /**
     * Calculate the recipient count for this campaign's target audience.
     */
    public function calculateRecipientCount(?string $audienceOverride = null): int
    {
        return $this->calculateRecipientQuery($audienceOverride)->count();
    }

    /**
     * Send broadcast to the targeted users with duplicate send protection.
     */
    public function sendBroadcast(): bool
    {
        if (in_array($this->status, ['sent', 'sending'])) {
            return false;
        }

        $this->update([
            'status' => 'sending',
            'recipient_count' => $this->calculateRecipientCount(),
        ]);

        $query = $this->calculateRecipientQuery();
        $sentTotal = 0;

        $query->chunk(100, function ($users) use (&$sentTotal) {
            foreach ($users as $user) {
                $user->notify(new AdminBroadcastNotification($this));
                $sentTotal++;
            }
        });

        $this->update([
            'status' => 'sent',
            'sent_count' => $sentTotal,
            'sent_at' => now(),
        ]);

        return true;
    }
}

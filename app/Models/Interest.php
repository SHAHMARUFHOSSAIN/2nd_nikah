<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];

    /**
     * Relationship to sender User.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to receiver User.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope for pending status.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for accepted status.
     */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for rejected status.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for cancelled status.
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Find any active/pending or accepted relationship between two users in either direction.
     */
    public static function getActiveRelationship(int $userAId, int $userBId): ?self
    {
        return static::whereIn('status', ['pending', 'accepted'])
            ->where(function ($q) use ($userAId, $userBId) {
                $q->where(function ($sub) use ($userAId, $userBId) {
                    $sub->where('sender_id', $userAId)->where('receiver_id', $userBId);
                })->orWhere(function ($sub) use ($userAId, $userBId) {
                    $sub->where('sender_id', $userBId)->where('receiver_id', $userAId);
                });
            })
            ->first();
    }

    /**
     * Find any existing interest (regardless of status) between sender and receiver in exact direction.
     */
    public static function findBetween(int $senderId, int $receiverId): ?self
    {
        return static::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->latest()
            ->first();
    }
}

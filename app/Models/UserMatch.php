<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'matched_at',
    ];

    protected function casts(): array
    {
        return [
            'matched_at' => 'datetime',
        ];
    }

    /**
     * Relationship to first user.
     */
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    /**
     * Relationship to second user.
     */
    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Helper to retrieve the partner User model relative to a given User ID.
     */
    public function getPartnerUser(int $currentUserId): ?User
    {
        if ($this->user_one_id === $currentUserId) {
            return $this->userTwo;
        }

        if ($this->user_two_id === $currentUserId) {
            return $this->userOne;
        }

        return null;
    }

    /**
     * Scope query to find all matches involving a given user ID.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)
              ->orWhere('user_two_id', $userId);
        });
    }

    /**
     * Create a match safely ensuring user_one_id < user_two_id to prevent duplicates.
     */
    public static function createMatch(int $userAId, int $userBId): self
    {
        $userOne = min($userAId, $userBId);
        $userTwo = max($userAId, $userBId);

        return static::firstOrCreate(
            [
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo,
            ],
            [
                'matched_at' => now(),
            ]
        );
    }

    /**
     * Check if a mutual match exists between two users.
     */
    public static function hasMutualMatch(int $userAId, int $userBId): bool
    {
        if ($userAId === $userBId) {
            return false;
        }

        $userOne = min($userAId, $userBId);
        $userTwo = max($userAId, $userBId);

        return static::where('user_one_id', $userOne)->where('user_two_id', $userTwo)->exists();
    }
}
